<?php

namespace App\Services;

class NewsService
{
    private const CACHE_FILE = '/tmp/news_cache.json';
    private const CACHE_TTL = 1800;
    
    public static function getLatestNews(int $limit = 10): array
    {
        $cached = self::getFromCache();
        if ($cached !== null) {
            return array_slice($cached, 0, $limit);
        }
        
        $news = self::fetchFromFinnhub();
        self::saveToCache($news);
        
        return array_slice($news, 0, $limit);
    }
    
    private static function fetchFromFinnhub(): array
    {
        try {
            $apiKey = getenv('FINNHUB_API_KEY') ?: 'demo';
            $url = "https://finnhub.io/api/v1/news?category=crypto&token={$apiKey}";
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 10,
                    'header' => "Accept: application/json\r\n",
                ]
            ]);
            
            $response = @file_get_contents($url, false, $context);
            
            if (!$response) {
                return self::getMockNews();
            }
            
            $data = json_decode($response, true);
            
            if (!is_array($data) || empty($data)) {
                return self::getMockNews();
            }
            
            $news = [];
            foreach (array_slice($data, 0, 20) as $item) {
                $news[] = [
                    'id' => $item['id'] ?? uniqid(),
                    'title' => $item['headline'] ?? 'Market Update',
                    'summary' => $item['summary'] ?? '',
                    'source' => $item['source'] ?? 'Financial News',
                    'url' => $item['url'] ?? '#',
                    'image' => $item['image'] ?? 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400',
                    'category' => $item['category'] ?? 'crypto',
                    'datetime' => date('Y-m-d H:i:s', $item['datetime'] ?? time()),
                    'related' => $item['related'] ?? '',
                ];
            }
            
            return !empty($news) ? $news : self::getMockNews();
        } catch (\Exception $e) {
            return self::getMockNews();
        }
    }
    
    private static function getMockNews(): array
    {
        return [
            [
                'id' => 'news_001',
                'title' => 'Bitcoin Surges Past $100K as Institutional Adoption Accelerates',
                'summary' => 'Major financial institutions continue to add Bitcoin to their portfolios, driving prices to new all-time highs.',
                'source' => 'CryptoNews',
                'url' => '#',
                'image' => 'https://images.unsplash.com/photo-1518546305927-5a555bb7020d?w=400',
                'category' => 'crypto',
                'datetime' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'related' => 'BTC,ETH',
            ],
            [
                'id' => 'news_002',
                'title' => 'Ethereum 2.0 Staking Rewards Hit Record Levels',
                'summary' => 'Validators are seeing increased rewards as network activity reaches unprecedented levels.',
                'source' => 'BlockchainDaily',
                'url' => '#',
                'image' => 'https://images.unsplash.com/photo-1622630998477-20aa696ecb05?w=400',
                'category' => 'crypto',
                'datetime' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'related' => 'ETH',
            ],
            [
                'id' => 'news_003',
                'title' => 'SEC Approves New Crypto ETF Applications',
                'summary' => 'Regulatory clarity brings new investment vehicles to mainstream investors.',
                'source' => 'FinanceWatch',
                'url' => '#',
                'image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400',
                'category' => 'regulation',
                'datetime' => date('Y-m-d H:i:s', strtotime('-5 hours')),
                'related' => 'BTC,ETH,SOL',
            ],
            [
                'id' => 'news_004',
                'title' => 'DeFi Total Value Locked Exceeds $200 Billion',
                'summary' => 'Decentralized finance protocols continue to attract massive capital inflows.',
                'source' => 'DeFiPulse',
                'url' => '#',
                'image' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?w=400',
                'category' => 'defi',
                'datetime' => date('Y-m-d H:i:s', strtotime('-8 hours')),
                'related' => 'AAVE,UNI',
            ],
            [
                'id' => 'news_005',
                'title' => 'Major Bank Launches Crypto Custody Services',
                'summary' => 'Traditional banking meets digital assets as institutional services expand.',
                'source' => 'BankingTech',
                'url' => '#',
                'image' => 'https://images.unsplash.com/photo-1565514020179-026b92b84bb6?w=400',
                'category' => 'institutional',
                'datetime' => date('Y-m-d H:i:s', strtotime('-12 hours')),
                'related' => 'BTC',
            ],
        ];
    }
    
    private static function getFromCache(): ?array
    {
        if (!file_exists(self::CACHE_FILE)) {
            return null;
        }
        
        $data = json_decode(file_get_contents(self::CACHE_FILE), true);
        if (!$data || !isset($data['expires']) || $data['expires'] < time()) {
            return null;
        }
        
        return $data['news'] ?? null;
    }
    
    private static function saveToCache(array $news): void
    {
        file_put_contents(self::CACHE_FILE, json_encode([
            'news' => $news,
            'expires' => time() + self::CACHE_TTL,
        ]));
    }
}
