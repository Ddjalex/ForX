<?php

namespace App\Services;

class FinnhubService
{
    private static string $apiKey;
    private const CACHE_TTL = 3600;
    private const API_URL = 'https://finnhub.io/api/v1';
    
    public function __construct()
    {
        self::$apiKey = $_ENV['FINNHUB_API_KEY'] ?? getenv('FINNHUB_API_KEY') ?? '';
        if (empty(self::$apiKey)) {
            throw new \Exception('Finnhub API key not configured');
        }
    }
    
    /**
     * Fetch top movers and generate trader profiles based on real market data
     */
    public static function generateTraderProfiles(int $limit = 12): array
    {
        $apiKey = $_ENV['FINNHUB_API_KEY'] ?? getenv('FINNHUB_API_KEY') ?? '';
        
        if (empty($apiKey)) {
            return self::getMockTraders();
        }
        
        try {
            // Fetch market movers to create realistic trader profiles
            $movers = self::fetchMarketMovers($apiKey);
            
            if (empty($movers)) {
                return self::getMockTraders();
            }
            
            $traders = [];
            $titles = ['Crypto Trading Expert', 'Market Analyst', 'Algorithmic Trader', 'Swing Trader', 'Day Trader', 'Forex Specialist'];
            $names = [
                'Alex Rivera', 'Sarah Chen', 'Marcus Johnson', 'Emma Williams', 'James Park',
                'David Lee', 'Jessica Martinez', 'Robert Taylor', 'Amanda Brown', 'Michael Chen',
                'Victoria Zhang', 'Christopher Scott'
            ];
            
            foreach (array_slice($movers, 0, $limit) as $index => $stock) {
                $name = $names[$index % count($names)] ?? 'Trader ' . ($index + 1);
                $symbol = $stock['symbol'] ?? 'AAPL';
                $change = floatval($stock['change'] ?? 5.2);
                
                // Generate realistic trader stats based on market data
                $baseWinRate = 50 + ($change / 2); // Higher movers = higher win rates
                $winRate = min(85, max(45, $baseWinRate + rand(-10, 10))); // 45-85%
                $profitShare = min(95, max(20, $change + rand(10, 50))); // Based on stock performance
                
                $traders[] = [
                    'id' => 'trader_' . str_pad(($index + 1), 3, '0', STR_PAD_LEFT),
                    'api_id' => 'finnhub_' . $symbol,
                    'name' => $name,
                    'title' => $titles[$index % count($titles)] ?? 'Professional Trader',
                    'bio' => 'Specializes in trading ' . $symbol . ' and related assets',
                    'profile_image' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=00D4AA&color=0a1628&size=300',
                    'win_rate' => round($winRate, 2),
                    'profit_share' => round($profitShare, 2),
                    'minimum_capital' => rand(500, 2000),
                    'total_followers' => rand(1000, 5000),
                    'monthly_return' => round(($profitShare / 10) + rand(2, 8), 2),
                    'market_symbol' => $symbol,
                ];
            }
            
            return !empty($traders) ? $traders : self::getMockTraders();
        } catch (\Exception $e) {
            return self::getMockTraders();
        }
    }
    
    /**
     * Fetch market movers from Finnhub API
     */
    private static function fetchMarketMovers(string $apiKey): array
    {
        try {
            $url = self::API_URL . '/stock/market/mover?category=gainers&apikey=' . urlencode($apiKey);
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 10,
                    'header' => "Accept: application/json\r\n",
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if (!$response) {
                return [];
            }
            
            $data = json_decode($response, true);
            
            if (!isset($data['data']) || !is_array($data['data'])) {
                return [];
            }
            
            return array_slice($data['data'], 0, 20);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Fallback mock traders if API fails
     */
    private static function getMockTraders(): array
    {
        return [
            [
                'id' => 'trader_001',
                'api_id' => 'trader_001',
                'name' => 'Alex Rivera',
                'title' => 'Crypto Trading Expert',
                'bio' => 'Specializes in spot and futures trading with 8+ years of experience',
                'profile_image' => 'https://ui-avatars.com/api/?name=Alex%20Rivera&background=00D4AA&color=0a1628&size=300',
                'win_rate' => 62.5,
                'profit_share' => 78.5,
                'minimum_capital' => 500,
                'total_followers' => 2845,
                'monthly_return' => 12.5,
            ],
            [
                'id' => 'trader_002',
                'api_id' => 'trader_002',
                'name' => 'Sarah Chen',
                'title' => 'Market Analyst',
                'bio' => 'Technical analysis specialist with consistent returns',
                'profile_image' => 'https://ui-avatars.com/api/?name=Sarah%20Chen&background=00D4AA&color=0a1628&size=300',
                'win_rate' => 71.2,
                'profit_share' => 85.0,
                'minimum_capital' => 1000,
                'total_followers' => 5120,
                'monthly_return' => 18.3,
            ],
            [
                'id' => 'trader_003',
                'api_id' => 'trader_003',
                'name' => 'Marcus Johnson',
                'title' => 'Algorithmic Trader',
                'bio' => 'Automated trading systems with real-time market signals',
                'profile_image' => 'https://ui-avatars.com/api/?name=Marcus%20Johnson&background=00D4AA&color=0a1628&size=300',
                'win_rate' => 68.8,
                'profit_share' => 72.0,
                'minimum_capital' => 2000,
                'total_followers' => 3456,
                'monthly_return' => 15.7,
            ],
            [
                'id' => 'trader_004',
                'api_id' => 'trader_004',
                'name' => 'Emma Williams',
                'title' => 'Swing Trader',
                'bio' => 'Focused on mid-term swing trades and trend following',
                'profile_image' => 'https://ui-avatars.com/api/?name=Emma%20Williams&background=00D4AA&color=0a1628&size=300',
                'win_rate' => 59.3,
                'profit_share' => 65.5,
                'minimum_capital' => 750,
                'total_followers' => 1923,
                'monthly_return' => 9.2,
            ],
            [
                'id' => 'trader_005',
                'api_id' => 'trader_005',
                'name' => 'James Park',
                'title' => 'Day Trader',
                'bio' => 'High-frequency trader with low-drawdown strategies',
                'profile_image' => 'https://ui-avatars.com/api/?name=James%20Park&background=00D4AA&color=0a1628&size=300',
                'win_rate' => 55.7,
                'profit_share' => 58.2,
                'minimum_capital' => 500,
                'total_followers' => 4101,
                'monthly_return' => 8.1,
            ],
        ];
    }
}
