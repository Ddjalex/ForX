<?php

namespace App\Services;

class AssetService
{
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
            "SELECT price FROM prices WHERE market_id = ?",
            [$marketId]
        );
        
        if ($price) {
            return (float) $price['price'];
        }
        
        return null;
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
}
