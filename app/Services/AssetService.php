<?php

namespace App\Services;

class AssetService
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    public function getAssetTypes(): array
    {
        $result = $this->db->query(
            "SELECT id, name, display_name FROM asset_types WHERE status = 'active' ORDER BY sort_order"
        );
        return $result ?: [];
    }
    
    public function getAssetsByType(string $type): array
    {
        $result = $this->db->query(
            "SELECT m.id, m.symbol, m.name, m.display_name, m.symbol_api, m.symbol_tradingview, m.type 
             FROM markets m 
             JOIN asset_types at ON m.asset_type_id = at.id 
             WHERE at.name = ? AND m.status = 'active' 
             ORDER BY m.name",
            [$type]
        );
        return $result ?: [];
    }
    
    public function getAllAssets(): array
    {
        $result = $this->db->query(
            "SELECT m.id, m.symbol, m.name, m.display_name, m.symbol_api, m.symbol_tradingview, m.type,
                    at.name as asset_type, at.display_name as asset_type_display
             FROM markets m 
             LEFT JOIN asset_types at ON m.asset_type_id = at.id 
             WHERE m.status = 'active' 
             ORDER BY at.sort_order, m.name"
        );
        return $result ?: [];
    }
    
    public function getAssetById(int $id): ?array
    {
        $result = $this->db->query(
            "SELECT m.*, at.name as asset_type, at.display_name as asset_type_display,
                    p.price as current_price, p.change_24h
             FROM markets m 
             LEFT JOIN asset_types at ON m.asset_type_id = at.id 
             LEFT JOIN prices p ON m.id = p.market_id
             WHERE m.id = ?",
            [$id]
        );
        return $result[0] ?? null;
    }
    
    public function getAssetBySymbol(string $symbol): ?array
    {
        $result = $this->db->query(
            "SELECT m.*, at.name as asset_type, at.display_name as asset_type_display,
                    p.price as current_price, p.change_24h
             FROM markets m 
             LEFT JOIN asset_types at ON m.asset_type_id = at.id 
             LEFT JOIN prices p ON m.id = p.market_id
             WHERE m.symbol = ? OR m.symbol_api = ?",
            [$symbol, $symbol]
        );
        return $result[0] ?? null;
    }
    
    public function getCurrentPrice(int $marketId): ?float
    {
        $result = $this->db->query(
            "SELECT price, updated_at FROM prices WHERE market_id = ?",
            [$marketId]
        );
        
        if (!$result || empty($result)) {
            return null;
        }
        
        $price = $result[0];
        $lastUpdate = strtotime($price['updated_at']);
        $maxAge = 60;
        
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
        
        $symbol = $market['symbol_api'] ?? $market['symbol'];
        $type = $market['type'];
        
        try {
            switch ($type) {
                case 'crypto':
                    return $this->fetchBinancePrice($symbol);
                case 'forex':
                    return $this->fetchForexPrice($symbol);
                case 'stock':
                    return $this->fetchStockPrice($symbol);
                default:
                    return null;
            }
        } catch (\Exception $e) {
            error_log("Error fetching external price for {$symbol}: " . $e->getMessage());
            return null;
        }
    }
    
    private function fetchBinancePrice(string $symbol): ?float
    {
        $url = "https://api.binance.com/api/v3/ticker/price?symbol=" . urlencode($symbol);
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'ignore_errors' => true
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            return null;
        }
        
        $data = json_decode($response, true);
        if (isset($data['price'])) {
            return (float) $data['price'];
        }
        
        return null;
    }
    
    private function fetchForexPrice(string $symbol): ?float
    {
        $result = $this->db->query(
            "SELECT price FROM prices WHERE market_id = (SELECT id FROM markets WHERE symbol = ?)",
            [$symbol]
        );
        
        if ($result && !empty($result)) {
            $basePrice = (float) $result[0]['price'];
            $variation = (mt_rand(-10, 10) / 10000);
            return round($basePrice * (1 + $variation), 5);
        }
        
        return null;
    }
    
    private function fetchStockPrice(string $symbol): ?float
    {
        $result = $this->db->query(
            "SELECT price FROM prices WHERE market_id = (SELECT id FROM markets WHERE symbol = ?)",
            [$symbol]
        );
        
        if ($result && !empty($result)) {
            $basePrice = (float) $result[0]['price'];
            $variation = (mt_rand(-50, 50) / 10000);
            return round($basePrice * (1 + $variation), 2);
        }
        
        return null;
    }
    
    public function updatePrice(int $marketId, float $price): bool
    {
        $existing = $this->db->query("SELECT id FROM prices WHERE market_id = ?", [$marketId]);
        
        if ($existing && !empty($existing)) {
            $this->db->query(
                "UPDATE prices SET price = ?, updated_at = CURRENT_TIMESTAMP WHERE market_id = ?",
                [$price, $marketId]
            );
        } else {
            $this->db->query(
                "INSERT INTO prices (market_id, price, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP)",
                [$marketId, $price]
            );
        }
        
        $this->db->query(
            "INSERT INTO prices_history (market_id, price) VALUES (?, ?)",
            [$marketId, $price]
        );
        
        return true;
    }
    
    public function getEntryPrice(int $marketId): float
    {
        $price = $this->getCurrentPrice($marketId);
        
        if ($price === null) {
            $result = $this->db->query(
                "SELECT price FROM prices WHERE market_id = ?",
                [$marketId]
            );
            
            if ($result && !empty($result)) {
                $price = (float) $result[0]['price'];
            } else {
                throw new \Exception("Unable to determine entry price for market ID: {$marketId}");
            }
        }
        
        return $price;
    }
}
