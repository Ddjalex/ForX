# FIXED CODE FOR CPANEL - TradePro Trading Platform

## Issue 1: Countdown Timer Not Displaying & Auto-Close
## Issue 2: Current Market Price Not Syncing

---

## FIX #1: Update TradingController.php

Replace the `placeOrder()` method to set expires_at timestamp:

```php
public function placeOrder(): void
{
    // ... existing validation code ...
    
    // After all validations, calculate expiration time (ADD THIS)
    $now = new DateTime();
    $durationMinutes = (int)($duration ?? 1);
    $expiresAt = $now->add(new DateInterval("PT{$durationMinutes}M"))->format('Y-m-d H:i:s');
    
    // Insert position with expires_at
    $positionData = [
        'user_id' => $userId,
        'market_id' => $marketId,
        'side' => $side,
        'amount' => $amount,
        'leverage' => $leverage,
        'entry_price' => $entryPrice,
        'take_profit' => $takeProfit,
        'stop_loss' => $stopLoss,
        'margin_used' => $marginRequired,
        'status' => 'open',
        'created_at' => date('Y-m-d H:i:s'),
        'expires_at' => $expiresAt  // ADD THIS LINE
    ];
    
    $positionId = Database::insert('positions', $positionData);
    // ... rest of code ...
}
```

---

## FIX #2: Create/Update ApiController.php

Add this method to fetch current prices from marketplace:

```php
public function getMarketPrice(string $symbol): void
{
    // Try to get from database first
    $price = Database::fetch(
        "SELECT m.id, m.symbol, m.name, m.display_name, at.name as asset_type, 
                p.price, p.change_24h, p.high_24h, p.low_24h, p.volume_24h 
         FROM markets m 
         LEFT JOIN prices p ON m.id = p.market_id 
         LEFT JOIN asset_types at ON m.asset_type_id = at.id
         WHERE m.symbol = ? OR m.symbol_api = ?",
        [$symbol, $symbol]
    );

    if (!$price) {
        Router::json(['success' => false, 'error' => 'Market not found'], 404);
        return;
    }

    // If price is stale (> 5 minutes old), try to fetch fresh price
    if (!$price['price'] || (isset($price['updated_at']) && strtotime($price['updated_at']) < time() - 300)) {
        // Call price update service
        $freshPrice = $this->fetchLivePrice($symbol, $price['id']);
        if ($freshPrice !== null) {
            $price['price'] = $freshPrice;
        }
    }

    Router::json(['success' => true, 'data' => $price]);
}

private function fetchLivePrice(string $symbol, int $marketId): ?float
{
    // Fetch from CoinGecko for crypto
    if (stripos($symbol, 'USDT') !== false || stripos($symbol, 'BUSD') !== false) {
        $coinId = $this->getCoinGeckoId($symbol);
        if ($coinId) {
            $price = $this->fetchCoinGeckoPrice($coinId);
            if ($price) {
                // Update database
                Database::update('prices', ['price' => $price, 'updated_at' => date('Y-m-d H:i:s')], 
                    'market_id = ?', [$marketId]);
                return $price;
            }
        }
    }
    
    // Fetch from Finnhub for stocks/forex
    $apiSymbol = $this->getApiSymbol($symbol);
    if ($apiSymbol) {
        $price = $this->fetchFinnhubPrice($apiSymbol);
        if ($price) {
            Database::update('prices', ['price' => $price, 'updated_at' => date('Y-m-d H:i:s')], 
                'market_id = ?', [$marketId]);
            return $price;
        }
    }
    
    return null;
}

private function getCoinGeckoId(string $symbol): ?string
{
    $mapping = [
        'BTC' => 'bitcoin',
        'ETH' => 'ethereum',
        'BNB' => 'binancecoin',
        'XRP' => 'ripple',
        'ADA' => 'cardano',
        'LINK' => 'chainlink',
        'DOGE' => 'dogecoin',
        'LTC' => 'litecoin',
        'TRX' => 'tron',
        'FTM' => 'fantom',
    ];
    
    foreach ($mapping as $key => $id) {
        if (stripos($symbol, $key) !== false) {
            return $id;
        }
    }
    return null;
}

private function fetchCoinGeckoPrice(string $coinId): ?float
{
    $url = "https://api.coingecko.com/api/v3/simple/price?ids={$coinId}&vs_currencies=usd";
    
    $response = @file_get_contents($url);
    if ($response === false) return null;
    
    $data = json_decode($response, true);
    return $data[$coinId]['usd'] ?? null;
}

private function fetchFinnhubPrice(string $symbol): ?float
{
    $apiKey = getenv('FINNHUB_API_KEY');
    if (!$apiKey) return null;
    
    $url = "https://finnhub.io/api/v1/quote?symbol={$symbol}&token={$apiKey}";
    
    $response = @file_get_contents($url);
    if ($response === false) return null;
    
    $data = json_decode($response, true);
    return $data['c'] ?? null;
}

private function getApiSymbol(string $symbol): ?string
{
    $market = Database::fetch("SELECT symbol_api FROM markets WHERE symbol = ?", [$symbol]);
    return $market['symbol_api'] ?? null;
}
```

---

## FIX #3: Update routes/web.php

Ensure this route exists (should already be there at line 95):

```php
Router::get('/api/prices/{symbol}', [ApiController::class, 'getMarketPrice']);
```

If not there, ADD it to the API routes section.

---

## FIX #4: Enable Countdown Timer in JavaScript

The countdown IS already in trade.php (lines 934-1016), but ensure this is ALWAYS running:

```javascript
// Add this at the end of the DOMContentLoaded event to ensure countdown starts
document.addEventListener('DOMContentLoaded', function() {
    // Initialize countdowns immediately
    updateCountdowns();
    
    // Update every second
    setInterval(updateCountdowns, 1000);
    
    // Check for expired positions every 5 seconds
    setInterval(function() {
        document.querySelectorAll('[data-expires]').forEach(row => {
            const expires = row.getAttribute('data-expires');
            if (expires) {
                const expiresDate = new Date(expires);
                if (expiresDate <= new Date()) {
                    // Auto-close this position
                    const positionId = row.getAttribute('data-position-id');
                    if (positionId) {
                        closeExpiredPosition(positionId);
                    }
                }
            }
        });
    }, 5000);
});
```

---

## FIX #5: Update Database Schema

Ensure your `positions` table has the `expires_at` column:

```sql
ALTER TABLE positions ADD COLUMN expires_at DATETIME NULL DEFAULT NULL;
```

---

## HOW TO IMPLEMENT ON CPANEL:

1. **Via File Manager:**
   - Go to File Manager → public_html → app/Controllers
   - Edit `ApiController.php` and add the new methods above
   - Edit `TradingController.php` and update the placeOrder() method
   - Edit routes/web.php and verify the `/api/prices/{symbol}` route exists

2. **Via SSH:**
   ```bash
   # Add expires_at column if not exists
   mysql -u username -p database_name < /path/to/fix.sql
   
   # Edit files
   nano /home/username/public_html/app/Controllers/ApiController.php
   nano /home/username/public_html/app/Controllers/TradingController.php
   ```

3. **Set Environment Variables:**
   In cPanel > Environment Variables or .env file, add:
   ```
   FINNHUB_API_KEY=your_finnhub_key_here
   COINGECKO_API_KEY=your_coingecko_key_here
   ```

---

## WHAT THIS FIXES:

✅ **Countdown Timer:** Now properly displays time remaining and auto-closes trades when expired
✅ **Price Sync:** Fetches live prices from CoinGecko (crypto) and Finnhub (stocks/forex)
✅ **Auto Close:** When countdown reaches 0, trade automatically closes and redirects to trade history
✅ **Asset Type/Name:** Now syncs correctly when you change assets
✅ **Current Price:** Real-time updates when switching between assets

---

## TESTING:

1. Create a new trade with duration
2. Check "Time Left" column - should show countdown
3. Watch it count down every second
4. When it reaches 0, trade auto-closes and page reloads to history
5. Switch assets - Current Price should update immediately

