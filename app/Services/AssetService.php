<?php

namespace App\Services;

class AssetService
{
    private static $cryptoPriceCache = [];
    private static $forexRateCache = [];
    private static $lastCryptoUpdate = 0;
    private static $lastForexUpdate = 0;
    
    public function getAssetTypes(): array
    {
        return Database::fetchAll(
            "SELECT id, name, display_name FROM asset_types WHERE status = 'active' ORDER BY sort_order"
        );
    }
    
    public function getAssetsByType(string $type): array
    {
        return Database::fetchAll(
            "SELECT m.id, m.symbol, m.name, m.display_name, m.symbol_api, m.symbol_tradingview, m.type 
             FROM markets m 
             JOIN asset_types at ON m.asset_type_id = at.id 
             WHERE at.name = ? AND m.status = 'active' 
             ORDER BY m.name",
            [$type]
        );
    }
    
    public function getAllAssets(): array
    {
        return Database::fetchAll(
            "SELECT m.id, m.symbol, m.name, m.display_name, m.symbol_api, m.symbol_tradingview, m.type,
                    at.name as asset_type, at.display_name as asset_type_display
             FROM markets m 
             LEFT JOIN asset_types at ON m.asset_type_id = at.id 
             WHERE m.status = 'active' 
             ORDER BY at.sort_order, m.name"
        );
    }
    
    public function getAssetById(int $id): ?array
    {
        return Database::fetch(
            "SELECT m.*, at.name as asset_type, at.display_name as asset_type_display,
                    p.price as current_price, p.change_24h
             FROM markets m 
             LEFT JOIN asset_types at ON m.asset_type_id = at.id 
             LEFT JOIN prices p ON m.id = p.market_id
             WHERE m.id = ?",
            [$id]
        );
    }
    
    public function getAssetBySymbol(string $symbol): ?array
    {
        return Database::fetch(
            "SELECT m.*, at.name as asset_type, at.display_name as asset_type_display,
                    p.price as current_price, p.change_24h
             FROM markets m 
             LEFT JOIN asset_types at ON m.asset_type_id = at.id 
             LEFT JOIN prices p ON m.id = p.market_id
             WHERE m.symbol = ? OR m.symbol_api = ?",
            [$symbol, $symbol]
        );
    }
    
    public function getCurrentPrice(int $marketId): ?float
    {
        $price = Database::fetch(
            "SELECT price, updated_at FROM prices WHERE market_id = ?",
            [$marketId]
        );
        
        if (!$price) {
            $newPrice = $this->fetchExternalPrice($marketId);
            if ($newPrice !== null) {
                $this->updatePrice($marketId, $newPrice);
                return $newPrice;
            }
            return null;
        }
        
        $lastUpdate = strtotime($price['updated_at']);
        $maxAge = 30;
        
        if (time() - $lastUpdate > $maxAge) {
            $newPrice = $this->fetchExternalPrice($marketId);
            if ($newPrice !== null) {
                $this->updatePrice($marketId, $newPrice);
                return $newPrice;
            }
        }
        
        return (float) $price['price'];
    }
    
    public function fetchExternalPrice(int $marketId): ?float
    {
        $market = $this->getAssetById($marketId);
        if (!$market) {
            return null;
        }
        
        $type = $market['type'];
        
        try {
            switch ($type) {
                case 'crypto':
                    return $this->fetchCoinGeckoPrice($market);
                case 'forex':
                    return $this->fetchRealForexPrice($market['symbol']);
                case 'stock':
                    return $this->fetchStockPrice($market['symbol']);
                default:
                    return null;
            }
        } catch (\Exception $e) {
            error_log("Error fetching external price for {$market['symbol']}: " . $e->getMessage());
            return null;
        }
    }
    
    private function fetchCoinGeckoPrice(array $market): ?float
    {
        $coingeckoId = $market['coingecko_id'] ?? null;
        if (!$coingeckoId) {
            return null;
        }
        
        if (time() - self::$lastCryptoUpdate < 10 && isset(self::$cryptoPriceCache[$coingeckoId])) {
            return self::$cryptoPriceCache[$coingeckoId];
        }
        
        $allCryptoMarkets = Database::fetchAll(
            "SELECT id, coingecko_id FROM markets WHERE type = 'crypto' AND coingecko_id IS NOT NULL"
        );
        
        $ids = array_filter(array_column($allCryptoMarkets, 'coingecko_id'));
        if (empty($ids)) {
            return null;
        }
        
        $url = "https://api.coingecko.com/api/v3/simple/price?ids=" . urlencode(implode(',', $ids)) . "&vs_currencies=usd&include_24hr_change=true";
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'ignore_errors' => true,
                'header' => "User-Agent: TradeFlow/1.0\r\n"
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            error_log("CoinGecko API request failed");
            return self::$cryptoPriceCache[$coingeckoId] ?? null;
        }
        
        $data = json_decode($response, true);
        if (!$data || isset($data['error'])) {
            error_log("CoinGecko API error: " . ($data['error'] ?? 'Unknown'));
            return self::$cryptoPriceCache[$coingeckoId] ?? null;
        }
        
        self::$lastCryptoUpdate = time();
        
        foreach ($data as $id => $priceData) {
            if (isset($priceData['usd'])) {
                self::$cryptoPriceCache[$id] = (float) $priceData['usd'];
                
                $marketRow = Database::fetch(
                    "SELECT id FROM markets WHERE coingecko_id = ?",
                    [$id]
                );
                if ($marketRow) {
                    $this->updatePriceWithChange($marketRow['id'], $priceData['usd'], $priceData['usd_24h_change'] ?? 0);
                }
            }
        }
        
        return self::$cryptoPriceCache[$coingeckoId] ?? null;
    }
    
    private function fetchRealForexPrice(string $symbol): ?float
    {
        if (time() - self::$lastForexUpdate < 60 && !empty(self::$forexRateCache)) {
            return $this->calculateForexRate($symbol, self::$forexRateCache);
        }
        
        $url = "https://open.er-api.com/v6/latest/USD";
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'ignore_errors' => true,
                'header' => "User-Agent: TradeFlow/1.0\r\n"
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            error_log("Forex API request failed");
            return $this->calculateForexRate($symbol, self::$forexRateCache);
        }
        
        $data = json_decode($response, true);
        if (!$data || $data['result'] !== 'success') {
            error_log("Forex API error");
            return $this->calculateForexRate($symbol, self::$forexRateCache);
        }
        
        self::$forexRateCache = $data['rates'];
        self::$lastForexUpdate = time();
        
        $allForexMarkets = Database::fetchAll(
            "SELECT id, symbol FROM markets WHERE type = 'forex'"
        );
        
        foreach ($allForexMarkets as $forexMarket) {
            $rate = $this->calculateForexRate($forexMarket['symbol'], self::$forexRateCache);
            if ($rate !== null) {
                $this->updatePrice($forexMarket['id'], $rate);
            }
        }
        
        return $this->calculateForexRate($symbol, self::$forexRateCache);
    }
    
    private function calculateForexRate(string $symbol, array $rates): ?float
    {
        if (empty($rates)) {
            return null;
        }
        
        $symbol = strtoupper(str_replace(['/', '-', '_'], '', $symbol));
        
        if (strlen($symbol) !== 6) {
            return null;
        }
        
        $base = substr($symbol, 0, 3);
        $quote = substr($symbol, 3, 3);
        
        if ($base === 'USD') {
            return isset($rates[$quote]) ? round($rates[$quote], 5) : null;
        }
        
        if ($quote === 'USD') {
            return isset($rates[$base]) ? round(1 / $rates[$base], 5) : null;
        }
        
        if (isset($rates[$base]) && isset($rates[$quote])) {
            return round($rates[$quote] / $rates[$base], 5);
        }
        
        return null;
    }
    
    private function fetchStockPrice(string $symbol): ?float
    {
        $price = Database::fetch(
            "SELECT price FROM prices WHERE market_id = (SELECT id FROM markets WHERE symbol = ?)",
            [$symbol]
        );
        
        if ($price) {
            $basePrice = (float) $price['price'];
            $variation = (mt_rand(-20, 20) / 10000);
            return round($basePrice * (1 + $variation), 2);
        }
        
        return null;
    }
    
    public function updatePrice(int $marketId, float $price): bool
    {
        $existing = Database::fetch("SELECT id FROM prices WHERE market_id = ?", [$marketId]);
        
        if ($existing) {
            Database::update('prices', 
                ['price' => $price, 'updated_at' => date('Y-m-d H:i:s')],
                'market_id = ?',
                [$marketId]
            );
        } else {
            Database::insert('prices', [
                'market_id' => $marketId,
                'price' => $price,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        Database::insert('prices_history', [
            'market_id' => $marketId,
            'price' => $price,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return true;
    }
    
    public function updatePriceWithChange(int $marketId, float $price, float $change24h): bool
    {
        $existing = Database::fetch("SELECT id FROM prices WHERE market_id = ?", [$marketId]);
        
        if ($existing) {
            Database::update('prices', 
                [
                    'price' => $price, 
                    'change_24h' => $change24h,
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                'market_id = ?',
                [$marketId]
            );
        } else {
            Database::insert('prices', [
                'market_id' => $marketId,
                'price' => $price,
                'change_24h' => $change24h,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        Database::insert('prices_history', [
            'market_id' => $marketId,
            'price' => $price,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return true;
    }
    
    public function getEntryPrice(int $marketId): float
    {
        $price = $this->getCurrentPrice($marketId);
        
        if ($price === null) {
            $priceData = Database::fetch(
                "SELECT price FROM prices WHERE market_id = ?",
                [$marketId]
            );
            
            if ($priceData) {
                $price = (float) $priceData['price'];
            } else {
                throw new \Exception("Unable to determine entry price for market ID: {$marketId}");
            }
        }
        
        return $price;
    }
    
    public function refreshAllPrices(): array
    {
        $results = ['crypto' => 0, 'forex' => 0, 'stock' => 0, 'errors' => []];
        
        $cryptoMarket = Database::fetch("SELECT id FROM markets WHERE type = 'crypto' AND coingecko_id IS NOT NULL LIMIT 1");
        if ($cryptoMarket) {
            try {
                $this->fetchCoinGeckoPrice($this->getAssetById($cryptoMarket['id']));
                $results['crypto'] = Database::fetch("SELECT COUNT(*) as cnt FROM markets WHERE type = 'crypto' AND coingecko_id IS NOT NULL")['cnt'] ?? 0;
            } catch (\Exception $e) {
                $results['errors'][] = "Crypto: " . $e->getMessage();
            }
        }
        
        $forexMarket = Database::fetch("SELECT id, symbol FROM markets WHERE type = 'forex' LIMIT 1");
        if ($forexMarket) {
            try {
                $this->fetchRealForexPrice($forexMarket['symbol']);
                $results['forex'] = Database::fetch("SELECT COUNT(*) as cnt FROM markets WHERE type = 'forex'")['cnt'] ?? 0;
            } catch (\Exception $e) {
                $results['errors'][] = "Forex: " . $e->getMessage();
            }
        }
        
        return $results;
    }
}
