<?php

namespace App\Services;

class ReferralService
{
    public static function getUserReferralCode(int $userId): string
    {
        $existing = Database::fetch(
            "SELECT code FROM referral_links WHERE user_id = ?",
            [$userId]
        );
        
        if ($existing) {
            return $existing['code'];
        }
        
        $code = self::generateUniqueCode();
        Database::insert('referral_links', [
            'user_id' => $userId,
            'code' => $code,
            'clicks' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        
        return $code;
    }
    
    public static function getReferralStats(int $userId): array
    {
        $link = Database::fetch(
            "SELECT * FROM referral_links WHERE user_id = ?",
            [$userId]
        );
        
        $code = $link ? ($link['code'] ?? null) : null;
        if (!$code) {
            $code = self::getUserReferralCode($userId);
        }
        
        $referrals = Database::fetchAll(
            "SELECT u.name, u.email, u.created_at, re.amount, re.status 
             FROM referral_earnings re 
             JOIN users u ON re.referred_id = u.id 
             WHERE re.referrer_id = ? 
             ORDER BY re.created_at DESC",
            [$userId]
        ) ?: [];
        
        $totalEarnings = Database::fetch(
            "SELECT COALESCE(SUM(amount), 0) as total FROM referral_earnings WHERE referrer_id = ? AND status = 'paid'",
            [$userId]
        );
        
        $pendingEarnings = Database::fetch(
            "SELECT COALESCE(SUM(amount), 0) as total FROM referral_earnings WHERE referrer_id = ? AND status = 'pending'",
            [$userId]
        );
        
        return [
            'code' => $code,
            'clicks' => $link ? (int)($link['clicks'] ?? 0) : 0,
            'total_referrals' => count($referrals),
            'total_earnings' => (float)($totalEarnings['total'] ?? 0),
            'pending_earnings' => (float)($pendingEarnings['total'] ?? 0),
            'referrals' => $referrals,
        ];
    }
    
    public static function trackClick(string $code): bool
    {
        $link = Database::fetch("SELECT id FROM referral_links WHERE code = ?", [$code]);
        
        if (!$link) {
            return false;
        }
        
        Database::query(
            "UPDATE referral_links SET clicks = clicks + 1 WHERE code = ?",
            [$code]
        );
        
        return true;
    }
    
    public static function processReferral(int $referredUserId, string $code): bool
    {
        $link = Database::fetch(
            "SELECT user_id FROM referral_links WHERE code = ?",
            [$code]
        );
        
        if (!$link || $link['user_id'] === $referredUserId) {
            return false;
        }
        
        $existing = Database::fetch(
            "SELECT id FROM referral_earnings WHERE referred_id = ?",
            [$referredUserId]
        );
        
        if ($existing) {
            return false;
        }
        
        $settings = Database::fetch("SELECT value FROM settings WHERE setting_key = 'referral_bonus'");
        $bonus = (float)($settings['value'] ?? 10);
        
        Database::insert('referral_earnings', [
            'referrer_id' => $link['user_id'],
            'referred_id' => $referredUserId,
            'amount' => $bonus,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        
        return true;
    }
    
    public static function getReferralTiers(): array
    {
        return [
            [
                'tier' => 1,
                'name' => 'Bronze',
                'min_referrals' => 0,
                'commission_rate' => 5,
                'bonus' => 10,
            ],
            [
                'tier' => 2,
                'name' => 'Silver',
                'min_referrals' => 5,
                'commission_rate' => 7,
                'bonus' => 15,
            ],
            [
                'tier' => 3,
                'name' => 'Gold',
                'min_referrals' => 15,
                'commission_rate' => 10,
                'bonus' => 25,
            ],
            [
                'tier' => 4,
                'name' => 'Platinum',
                'min_referrals' => 30,
                'commission_rate' => 12,
                'bonus' => 50,
            ],
            [
                'tier' => 5,
                'name' => 'Diamond',
                'min_referrals' => 50,
                'commission_rate' => 15,
                'bonus' => 100,
            ],
        ];
    }
    
    private static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
            $exists = Database::fetch("SELECT id FROM referral_links WHERE code = ?", [$code]);
        } while ($exists);
        
        return $code;
    }
}
