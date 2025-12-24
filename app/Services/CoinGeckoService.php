<?php

namespace App\Services;

class CoinGeckoService
{
    private const API_URL = 'https://api.coingecko.com/api/v3';
    private const CACHE_TTL = 300;

    private static $coinMapping = [
        'BTC' => 'bitcoin',
        'ETH' => 'ethereum',
        'BNB' => 'binancecoin',
        'XRP' => 'ripple',
        'ADA' => 'cardano',
        'SOL' => 'solana',
        'LINK' => 'chainlink',
        'DOGE' => 'dogecoin',
        'LTC' => 'litecoin',
        'TRX' => 'tron',
        'FTM' => 'fantom',
        'ATOM' => 'cosmos',
        'NEAR' => 'near',
        'AVAX' => 'avalanche-2',
        'MATIC' => 'matic-network',
        'OP' => 'optimism',
        'ARB' => 'arbitrum',
        'USDT' => 'tether',
        'USDC' => 'usd-coin',
        'BUSD' => 'binance-usd',
        'DOT' => 'polkadot',
    ];

    public static function getPrices(): array
    {
        $prices = [
            'BTC' => 97500,
            'ETH' => 3650,
            'USDT' => 1,
            'BNB' => 685,
            'XRP' => 2.35,
            'SOL' => 225,
            'ADA' => 1.05,
            'DOT' => 35.50,
            'ATOM' => 10.25,
            'AVAX' => 38.50,
            'MATIC' => 0.85,
        ];

        try {
            $coinIds = implode(',', self::$coinMapping);
            $apiKey = getenv('COINGECKO_API_KEY');
            $url = self::API_URL . '/simple/price?ids=' . $coinIds . '&vs_currencies=usd&include_24hr_change=true';
            
            if ($apiKey) {
                $url .= '&x_cg_pro_api_key=' . urlencode($apiKey);
            }
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response) {
                $data = json_decode($response, true);
                if (is_array($data)) {
                    $result = [];
                    foreach (self::$coinMapping as $symbol => $coinId) {
                        $result[$symbol] = $data[$coinId]['usd'] ?? $prices[$symbol] ?? 100;
                    }
                    return $result;
                }
            }
        } catch (\Exception $e) {
            error_log('CoinGecko getPrices error: ' . $e->getMessage());
        }
        
        return $prices;
    }

    public static function getPrice(string $coinId): ?float
    {
        try {
            $apiKey = getenv('COINGECKO_API_KEY');
            $url = self::API_URL . '/simple/price?ids=' . $coinId . '&vs_currencies=usd';
            
            if ($apiKey) {
                $url .= '&x_cg_pro_api_key=' . urlencode($apiKey);
            }
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data[$coinId]['usd']) && $data[$coinId]['usd'] > 0) {
                    return (float)$data[$coinId]['usd'];
                }
            }
        } catch (\Exception $e) {
            error_log('CoinGecko getPrice error for ' . $coinId . ': ' . $e->getMessage());
        }
        
        return null;
    }

    public static function getMarketData(): array
    {
        try {
            $coinIds = implode(',', self::$coinMapping);
            $apiKey = getenv('COINGECKO_API_KEY');
            $url = self::API_URL . '/simple/price?ids=' . $coinIds . '&vs_currencies=usd&include_market_cap=true&include_24hr_vol=true&include_24hr_change=true';
            
            if ($apiKey) {
                $url .= '&x_cg_pro_api_key=' . urlencode($apiKey);
            }
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response) {
                $data = json_decode($response, true);
                if (is_array($data)) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            error_log('CoinGecko getMarketData error: ' . $e->getMessage());
        }
        
        return [];
    }

    public static function getCoinIdFromSymbol(string $symbol): ?string
    {
        $cleanSymbol = str_replace('/', '', strtoupper($symbol));
        
        foreach (self::$coinMapping as $key => $coinId) {
            if (stripos($cleanSymbol, $key) !== false) {
                return $coinId;
            }
        }
        
        return null;
    }
}
