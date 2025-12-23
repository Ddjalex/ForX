<?php

namespace App\Services;

class CoinGeckoService
{
    private const API_URL = 'https://api.coingecko.com/api/v3';
    private const CACHE_TTL = 300; // 5 minutes

    public static function getPrices(): array
    {
        $cacheKey = 'coingecko_prices_' . date('Hi');
        
        $prices = [
            'BTC' => 97500,
            'ETH' => 3650,
            'USDT' => 1,
            'BNB' => 685,
            'XRP' => 2.35,
            'SOL' => 225,
            'ADA' => 1.05,
            'DOT' => 35.50,
        ];

        try {
            $url = self::API_URL . '/simple/price?ids=bitcoin,ethereum,tether,binancecoin,ripple,solana,cardano,polkadot&vs_currencies=usd&include_24hr_change=true';
            $response = @file_get_contents($url);
            
            if ($response) {
                $data = json_decode($response, true);
                if (is_array($data)) {
                    return [
                        'BTC' => $data['bitcoin']['usd'] ?? 97500,
                        'ETH' => $data['ethereum']['usd'] ?? 3650,
                        'USDT' => $data['tether']['usd'] ?? 1,
                        'BNB' => $data['binancecoin']['usd'] ?? 685,
                        'XRP' => $data['ripple']['usd'] ?? 2.35,
                        'SOL' => $data['solana']['usd'] ?? 225,
                        'ADA' => $data['cardano']['usd'] ?? 1.05,
                        'DOT' => $data['polkadot']['usd'] ?? 35.50,
                    ];
                }
            }
        } catch (\Exception $e) {
            // Fallback to default prices
        }
        
        return $prices;
    }

    public static function getMarketData(): array
    {
        try {
            $url = self::API_URL . '/simple/price?ids=bitcoin,ethereum,tether,binancecoin,ripple,solana,cardano,polkadot,dogecoin,avalanche-2&vs_currencies=usd&include_market_cap=true&include_24hr_vol=true&include_24hr_change=true';
            $response = @file_get_contents($url);
            
            if ($response) {
                $data = json_decode($response, true);
                if (is_array($data)) {
                    return [
                        'bitcoin' => $data['bitcoin'] ?? [],
                        'ethereum' => $data['ethereum'] ?? [],
                        'tether' => $data['tether'] ?? [],
                        'binancecoin' => $data['binancecoin'] ?? [],
                        'ripple' => $data['ripple'] ?? [],
                        'solana' => $data['solana'] ?? [],
                        'cardano' => $data['cardano'] ?? [],
                        'polkadot' => $data['polkadot'] ?? [],
                        'dogecoin' => $data['dogecoin'] ?? [],
                        'avalanche-2' => $data['avalanche-2'] ?? [],
                    ];
                }
            }
        } catch (\Exception $e) {
            // Return empty array on error
        }
        
        return [];
    }
}
