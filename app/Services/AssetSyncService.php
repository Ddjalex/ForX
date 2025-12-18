<?php

namespace App\Services;

class AssetSyncService
{
    private const BINANCE_API = 'https://api.binance.com/api/v3';
    
    public function syncAllAssets(): array
    {
        $this->clearOldAssets();
        
        $results = [
            'crypto' => $this->syncCryptoAssets(),
            'forex' => $this->syncForexAssets(),
            'stock' => $this->syncStockAssets()
        ];
        
        $this->cleanupDuplicates();
        
        return $results;
    }
    
    private function cleanupDuplicates(): void
    {
        Database::query("DELETE FROM markets WHERE id NOT IN (
            SELECT MIN(id) FROM markets WHERE status = 'active' GROUP BY symbol_api, type
        ) AND symbol_api IS NOT NULL");
    }
    
    public function syncCryptoAssets(): array
    {
        $synced = [];
        
        try {
            $symbols = $this->fetchBinanceUsdtPairs();
            
            $assetTypeId = $this->getAssetTypeId('crypto');
            
            foreach ($symbols as $symbol) {
                $displayName = $this->formatCryptoDisplayName($symbol);
                $symbolApi = $symbol['symbol'];
                $symbolTradingView = 'BINANCE:' . $symbol['symbol'];
                
                $this->upsertMarket([
                    'symbol' => $symbolApi,
                    'name' => $symbol['baseAsset'],
                    'type' => 'crypto',
                    'display_name' => $displayName,
                    'symbol_api' => $symbolApi,
                    'symbol_tradingview' => $symbolTradingView,
                    'asset_type_id' => $assetTypeId
                ]);
                
                $synced[] = $displayName;
            }
        } catch (\Exception $e) {
            error_log("Crypto sync error: " . $e->getMessage());
        }
        
        return $synced;
    }
    
    private function fetchBinanceUsdtPairs(): array
    {
        $url = self::BINANCE_API . '/exchangeInfo';
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 30,
                'header' => "User-Agent: TradePro/1.0\r\nAccept: application/json",
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            return $this->getFallbackCryptoPairs();
        }
        
        $data = json_decode($response, true);
        
        if (!isset($data['symbols'])) {
            return $this->getFallbackCryptoPairs();
        }
        
        $usdtPairs = [];
        $popularBases = ['BTC', 'ETH', 'BNB', 'SOL', 'XRP', 'ADA', 'DOGE', 'AVAX', 'DOT', 'MATIC', 
                         'SHIB', 'LTC', 'TRX', 'LINK', 'ATOM', 'UNI', 'XLM', 'ETC', 'FIL', 'APT',
                         'ARB', 'OP', 'NEAR', 'AAVE', 'GRT', 'INJ', 'SUI', 'SEI', 'TIA', 'JUP'];
        
        foreach ($data['symbols'] as $symbol) {
            if ($symbol['quoteAsset'] === 'USDT' && 
                $symbol['status'] === 'TRADING' &&
                in_array($symbol['baseAsset'], $popularBases)) {
                $usdtPairs[] = [
                    'symbol' => $symbol['symbol'],
                    'baseAsset' => $symbol['baseAsset'],
                    'quoteAsset' => $symbol['quoteAsset']
                ];
            }
        }
        
        usort($usdtPairs, function($a, $b) use ($popularBases) {
            $aIndex = array_search($a['baseAsset'], $popularBases);
            $bIndex = array_search($b['baseAsset'], $popularBases);
            return $aIndex - $bIndex;
        });
        
        return array_slice($usdtPairs, 0, 30);
    }
    
    private function getFallbackCryptoPairs(): array
    {
        return [
            ['symbol' => 'BTCUSDT', 'baseAsset' => 'BTC', 'quoteAsset' => 'USDT'],
            ['symbol' => 'ETHUSDT', 'baseAsset' => 'ETH', 'quoteAsset' => 'USDT'],
            ['symbol' => 'BNBUSDT', 'baseAsset' => 'BNB', 'quoteAsset' => 'USDT'],
            ['symbol' => 'SOLUSDT', 'baseAsset' => 'SOL', 'quoteAsset' => 'USDT'],
            ['symbol' => 'XRPUSDT', 'baseAsset' => 'XRP', 'quoteAsset' => 'USDT'],
            ['symbol' => 'ADAUSDT', 'baseAsset' => 'ADA', 'quoteAsset' => 'USDT'],
            ['symbol' => 'DOGEUSDT', 'baseAsset' => 'DOGE', 'quoteAsset' => 'USDT'],
            ['symbol' => 'AVAXUSDT', 'baseAsset' => 'AVAX', 'quoteAsset' => 'USDT'],
            ['symbol' => 'DOTUSDT', 'baseAsset' => 'DOT', 'quoteAsset' => 'USDT'],
            ['symbol' => 'MATICUSDT', 'baseAsset' => 'MATIC', 'quoteAsset' => 'USDT'],
            ['symbol' => 'SHIBUSDT', 'baseAsset' => 'SHIB', 'quoteAsset' => 'USDT'],
            ['symbol' => 'LTCUSDT', 'baseAsset' => 'LTC', 'quoteAsset' => 'USDT'],
            ['symbol' => 'TRXUSDT', 'baseAsset' => 'TRX', 'quoteAsset' => 'USDT'],
            ['symbol' => 'LINKUSDT', 'baseAsset' => 'LINK', 'quoteAsset' => 'USDT'],
            ['symbol' => 'ATOMUSDT', 'baseAsset' => 'ATOM', 'quoteAsset' => 'USDT'],
            ['symbol' => 'UNIUSDT', 'baseAsset' => 'UNI', 'quoteAsset' => 'USDT'],
            ['symbol' => 'XLMUSDT', 'baseAsset' => 'XLM', 'quoteAsset' => 'USDT'],
            ['symbol' => 'ETCUSDT', 'baseAsset' => 'ETC', 'quoteAsset' => 'USDT'],
            ['symbol' => 'FILUSDT', 'baseAsset' => 'FIL', 'quoteAsset' => 'USDT'],
            ['symbol' => 'APTUSDT', 'baseAsset' => 'APT', 'quoteAsset' => 'USDT'],
            ['symbol' => 'ARBUSDT', 'baseAsset' => 'ARB', 'quoteAsset' => 'USDT'],
            ['symbol' => 'OPUSDT', 'baseAsset' => 'OP', 'quoteAsset' => 'USDT'],
            ['symbol' => 'NEARUSDT', 'baseAsset' => 'NEAR', 'quoteAsset' => 'USDT'],
            ['symbol' => 'AAVEUSDT', 'baseAsset' => 'AAVE', 'quoteAsset' => 'USDT'],
            ['symbol' => 'GRTUSDT', 'baseAsset' => 'GRT', 'quoteAsset' => 'USDT'],
            ['symbol' => 'INJUSDT', 'baseAsset' => 'INJ', 'quoteAsset' => 'USDT'],
            ['symbol' => 'SUIUSDT', 'baseAsset' => 'SUI', 'quoteAsset' => 'USDT'],
            ['symbol' => 'SEIUSDT', 'baseAsset' => 'SEI', 'quoteAsset' => 'USDT'],
            ['symbol' => 'TIAUSDT', 'baseAsset' => 'TIA', 'quoteAsset' => 'USDT'],
            ['symbol' => 'JUPUSDT', 'baseAsset' => 'JUP', 'quoteAsset' => 'USDT']
        ];
    }
    
    private function formatCryptoDisplayName(array $symbol): string
    {
        return 'USDT/' . $symbol['baseAsset'];
    }
    
    public function syncForexAssets(): array
    {
        $synced = [];
        
        $forexPairs = [
            ['symbol' => 'EURUSD', 'base' => 'EUR', 'quote' => 'USD', 'baseName' => 'Euro', 'quoteName' => 'US Dollar'],
            ['symbol' => 'GBPUSD', 'base' => 'GBP', 'quote' => 'USD', 'baseName' => 'Pound', 'quoteName' => 'US Dollar'],
            ['symbol' => 'USDJPY', 'base' => 'USD', 'quote' => 'JPY', 'baseName' => 'US Dollar', 'quoteName' => 'Japanese Yen'],
            ['symbol' => 'USDCHF', 'base' => 'USD', 'quote' => 'CHF', 'baseName' => 'US Dollar', 'quoteName' => 'Swiss Franc'],
            ['symbol' => 'AUDUSD', 'base' => 'AUD', 'quote' => 'USD', 'baseName' => 'Australian Dollar', 'quoteName' => 'US Dollar'],
            ['symbol' => 'USDCAD', 'base' => 'USD', 'quote' => 'CAD', 'baseName' => 'US Dollar', 'quoteName' => 'Canadian Dollar'],
            ['symbol' => 'NZDUSD', 'base' => 'NZD', 'quote' => 'USD', 'baseName' => 'New Zealand Dollar', 'quoteName' => 'US Dollar'],
            ['symbol' => 'EURGBP', 'base' => 'EUR', 'quote' => 'GBP', 'baseName' => 'Euro', 'quoteName' => 'Pound'],
            ['symbol' => 'EURJPY', 'base' => 'EUR', 'quote' => 'JPY', 'baseName' => 'Euro', 'quoteName' => 'Japanese Yen'],
            ['symbol' => 'GBPJPY', 'base' => 'GBP', 'quote' => 'JPY', 'baseName' => 'Pound', 'quoteName' => 'Japanese Yen'],
            ['symbol' => 'EURCHF', 'base' => 'EUR', 'quote' => 'CHF', 'baseName' => 'Euro', 'quoteName' => 'Swiss Franc'],
            ['symbol' => 'AUDJPY', 'base' => 'AUD', 'quote' => 'JPY', 'baseName' => 'Australian Dollar', 'quoteName' => 'Japanese Yen'],
            ['symbol' => 'EURAUD', 'base' => 'EUR', 'quote' => 'AUD', 'baseName' => 'Euro', 'quoteName' => 'Australian Dollar'],
            ['symbol' => 'GBPAUD', 'base' => 'GBP', 'quote' => 'AUD', 'baseName' => 'Pound', 'quoteName' => 'Australian Dollar'],
            ['symbol' => 'GBPCHF', 'base' => 'GBP', 'quote' => 'CHF', 'baseName' => 'Pound', 'quoteName' => 'Swiss Franc'],
            ['symbol' => 'CADJPY', 'base' => 'CAD', 'quote' => 'JPY', 'baseName' => 'Canadian Dollar', 'quoteName' => 'Japanese Yen'],
            ['symbol' => 'CHFJPY', 'base' => 'CHF', 'quote' => 'JPY', 'baseName' => 'Swiss Franc', 'quoteName' => 'Japanese Yen'],
            ['symbol' => 'NZDJPY', 'base' => 'NZD', 'quote' => 'JPY', 'baseName' => 'New Zealand Dollar', 'quoteName' => 'Japanese Yen'],
            ['symbol' => 'AUDCAD', 'base' => 'AUD', 'quote' => 'CAD', 'baseName' => 'Australian Dollar', 'quoteName' => 'Canadian Dollar'],
            ['symbol' => 'AUDCHF', 'base' => 'AUD', 'quote' => 'CHF', 'baseName' => 'Australian Dollar', 'quoteName' => 'Swiss Franc']
        ];
        
        $assetTypeId = $this->getAssetTypeId('forex');
        
        foreach ($forexPairs as $pair) {
            $displayName = $pair['symbol'] . ', ' . $pair['baseName'] . ' vs ' . $pair['quoteName'];
            $symbolApi = $pair['symbol'];
            $symbolTradingView = 'FX:' . $pair['symbol'];
            
            $this->upsertMarket([
                'symbol' => $pair['symbol'],
                'name' => $pair['baseName'] . '/' . $pair['quoteName'],
                'type' => 'forex',
                'display_name' => $displayName,
                'symbol_api' => $symbolApi,
                'symbol_tradingview' => $symbolTradingView,
                'asset_type_id' => $assetTypeId
            ]);
            
            $synced[] = $displayName;
        }
        
        return $synced;
    }
    
    public function syncStockAssets(): array
    {
        $synced = [];
        
        $stocks = [
            ['symbol' => 'AAPL', 'name' => 'APPLE INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'MSFT', 'name' => 'MICROSOFT CORP', 'exchange' => 'NASDAQ'],
            ['symbol' => 'GOOGL', 'name' => 'ALPHABET INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'AMZN', 'name' => 'AMAZON.COM INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'NVDA', 'name' => 'NVIDIA CORP', 'exchange' => 'NASDAQ'],
            ['symbol' => 'META', 'name' => 'META PLATFORMS INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'TSLA', 'name' => 'TESLA INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'BRK.B', 'name' => 'BERKSHIRE HATHAWAY', 'exchange' => 'NYSE'],
            ['symbol' => 'JPM', 'name' => 'JPMORGAN CHASE & CO', 'exchange' => 'NYSE'],
            ['symbol' => 'V', 'name' => 'VISA INC', 'exchange' => 'NYSE'],
            ['symbol' => 'JNJ', 'name' => 'JOHNSON & JOHNSON', 'exchange' => 'NYSE'],
            ['symbol' => 'WMT', 'name' => 'WALMART INC', 'exchange' => 'NYSE'],
            ['symbol' => 'MA', 'name' => 'MASTERCARD INC', 'exchange' => 'NYSE'],
            ['symbol' => 'PG', 'name' => 'PROCTER & GAMBLE CO', 'exchange' => 'NYSE'],
            ['symbol' => 'XOM', 'name' => 'EXXON MOBIL CORP', 'exchange' => 'NYSE'],
            ['symbol' => 'UNH', 'name' => 'UNITEDHEALTH GROUP', 'exchange' => 'NYSE'],
            ['symbol' => 'HD', 'name' => 'HOME DEPOT INC', 'exchange' => 'NYSE'],
            ['symbol' => 'BAC', 'name' => 'BANK OF AMERICA CORP', 'exchange' => 'NYSE'],
            ['symbol' => 'COST', 'name' => 'COSTCO WHOLESALE', 'exchange' => 'NASDAQ'],
            ['symbol' => 'DIS', 'name' => 'WALT DISNEY CO', 'exchange' => 'NYSE'],
            ['symbol' => 'NFLX', 'name' => 'NETFLIX INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'ADBE', 'name' => 'ADOBE INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'CRM', 'name' => 'SALESFORCE INC', 'exchange' => 'NYSE'],
            ['symbol' => 'INTC', 'name' => 'INTEL CORP', 'exchange' => 'NASDAQ'],
            ['symbol' => 'AMD', 'name' => 'ADVANCED MICRO DEVICES', 'exchange' => 'NASDAQ'],
            ['symbol' => 'PYPL', 'name' => 'PAYPAL HOLDINGS INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'CSCO', 'name' => 'CISCO SYSTEMS INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'PEP', 'name' => 'PEPSICO INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'AVGO', 'name' => 'BROADCOM INC', 'exchange' => 'NASDAQ'],
            ['symbol' => 'ORCL', 'name' => 'ORACLE CORP', 'exchange' => 'NYSE']
        ];
        
        $assetTypeId = $this->getAssetTypeId('stock');
        
        foreach ($stocks as $stock) {
            $displayName = $stock['name'];
            $symbolApi = $stock['symbol'];
            $symbolTradingView = $stock['exchange'] . ':' . $stock['symbol'];
            
            $this->upsertMarket([
                'symbol' => $stock['symbol'],
                'name' => $stock['name'],
                'type' => 'stock',
                'display_name' => $displayName,
                'symbol_api' => $symbolApi,
                'symbol_tradingview' => $symbolTradingView,
                'asset_type_id' => $assetTypeId
            ]);
            
            $synced[] = $displayName;
        }
        
        return $synced;
    }
    
    private function getAssetTypeId(string $typeName): ?int
    {
        $type = Database::fetch(
            "SELECT id FROM asset_types WHERE name = ?",
            [$typeName]
        );
        
        return $type ? (int)$type['id'] : null;
    }
    
    private function upsertMarket(array $data): void
    {
        $existing = Database::fetch(
            "SELECT id FROM markets WHERE symbol_api = ? AND type = ?",
            [$data['symbol_api'], $data['type']]
        );
        
        if (!$existing) {
            $existing = Database::fetch(
                "SELECT id FROM markets WHERE symbol = ? AND type = ?",
                [$data['symbol'], $data['type']]
            );
        }
        
        if ($existing) {
            Database::query(
                "UPDATE markets SET 
                    symbol = ?,
                    name = ?, 
                    display_name = ?, 
                    symbol_api = ?, 
                    symbol_tradingview = ?, 
                    asset_type_id = ?,
                    status = 'active'
                 WHERE id = ?",
                [
                    $data['symbol'],
                    $data['name'],
                    $data['display_name'],
                    $data['symbol_api'],
                    $data['symbol_tradingview'],
                    $data['asset_type_id'],
                    $existing['id']
                ]
            );
        } else {
            Database::query(
                "INSERT INTO markets (symbol, name, type, display_name, symbol_api, symbol_tradingview, asset_type_id, status, spread, max_leverage, min_trade_size, fee) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, 'active', 0.001, 100, 0.001, 0.001)",
                [
                    $data['symbol'],
                    $data['name'],
                    $data['type'],
                    $data['display_name'],
                    $data['symbol_api'],
                    $data['symbol_tradingview'],
                    $data['asset_type_id']
                ]
            );
        }
        
        $market = Database::fetch(
            "SELECT id FROM markets WHERE symbol = ? AND type = ?",
            [$data['symbol'], $data['type']]
        );
        
        if ($market) {
            $priceExists = Database::fetch(
                "SELECT id FROM prices WHERE market_id = ?",
                [$market['id']]
            );
            
            if (!$priceExists) {
                $defaultPrice = $this->getDefaultPrice($data['type'], $data['symbol']);
                Database::query(
                    "INSERT INTO prices (market_id, price, change_24h) VALUES (?, ?, ?)",
                    [$market['id'], $defaultPrice, 0]
                );
            }
        }
    }
    
    private function getDefaultPrice(string $type, string $symbol): float
    {
        $defaults = [
            'crypto' => [
                'USDT/BTC' => 97500.00,
                'USDT/ETH' => 3450.00,
                'USDT/BNB' => 720.00,
                'USDT/SOL' => 220.00,
                'USDT/XRP' => 2.50,
                'default' => 100.00
            ],
            'forex' => [
                'EURUSD' => 1.0520,
                'GBPUSD' => 1.2650,
                'USDJPY' => 157.50,
                'default' => 1.0000
            ],
            'stock' => [
                'AAPL' => 250.00,
                'MSFT' => 435.00,
                'GOOGL' => 195.00,
                'AMZN' => 225.00,
                'NVDA' => 135.00,
                'TSLA' => 440.00,
                'default' => 100.00
            ]
        ];
        
        if (isset($defaults[$type][$symbol])) {
            return $defaults[$type][$symbol];
        }
        
        return $defaults[$type]['default'] ?? 100.00;
    }
    
    public function clearOldAssets(): void
    {
        Database::query("DELETE FROM markets WHERE type = 'crypto' AND symbol LIKE '%/%'");
        
        Database::query("UPDATE markets SET status = 'inactive' WHERE display_name IS NULL OR display_name = ''");
    }
}
