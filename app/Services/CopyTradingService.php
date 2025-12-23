<?php

namespace App\Services;

class CopyTradingService
{
    private const CACHE_TTL = 3600;
    private const CACHE_FILE = '/tmp/copy_traders_cache.json';
    
    public static function getTopTraders(int $limit = 12): array
    {
        // Check database first
        $dbTraders = Database::fetchAll(
            "SELECT * FROM copy_traders WHERE status = 'active' ORDER BY profit_share DESC LIMIT ?",
            [$limit]
        );
        
        if (!empty($dbTraders)) {
            return $dbTraders;
        }
        
        // Fetch from API and cache if database is empty
        $traders = self::fetchFromBingX();
        
        // Store in database for next time
        foreach ($traders as $trader) {
            Database::insert('copy_traders', [
                'name' => $trader['name'],
                'title' => $trader['title'],
                'bio' => $trader['bio'],
                'profile_image' => $trader['profile_image'],
                'win_rate' => $trader['win_rate'],
                'profit_share' => $trader['profit_share'],
                'minimum_capital' => $trader['minimum_capital'],
                'total_followers' => $trader['total_followers'],
                'monthly_return' => $trader['monthly_return'],
                'status' => 'active',
                'api_id' => $trader['api_id'] ?? null,
                'created_at' => date('Y-m-d H:i:s'),
            ], true); // ignore duplicates
        }
        
        return array_slice($traders, 0, $limit);
    }
    
    private static function fetchFromBingX(): array
    {
        try {
            // Use Finnhub service to fetch real market data
            $finnhubService = new \App\Services\FinnhubService();
            $traders = \App\Services\FinnhubService::generateTraderProfiles(20);
            
            if (!empty($traders)) {
                return $traders;
            }
            
            // Fallback to mock data if Finnhub fails
            return self::getMockTraders();
        } catch (\Exception $e) {
            // Fallback to mock data if API fails
            return self::getMockTraders();
        }
    }
    
    private static function getMockTraders(): array
    {
        return [
            [
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
