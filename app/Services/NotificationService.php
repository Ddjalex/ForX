<?php

namespace App\Services;

class NotificationService
{
    public static function create(int $userId, string $type, string $title, string $message, string $icon = 'bell', ?string $actionUrl = null): int
    {
        Database::insert('notifications', [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'action_url' => $actionUrl,
            'is_read' => false,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return Database::lastInsertId();
    }

    public static function getUnreadCount(int $userId): int
    {
        $result = Database::fetch(
            "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = FALSE",
            [$userId]
        );
        return (int)($result['count'] ?? 0);
    }

    public static function getUnreadNotifications(int $userId, int $limit = 10): array
    {
        return Database::fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? AND is_read = FALSE ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        ) ?: [];
    }

    public static function getNotifications(int $userId, int $limit = 20): array
    {
        return Database::fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        ) ?: [];
    }

    public static function markAsRead(int $notificationId): bool
    {
        Database::update('notifications', [
            'is_read' => true,
        ], 'id = ?', [$notificationId]);
        return true;
    }

    public static function markAllAsRead(int $userId): bool
    {
        Database::update('notifications', [
            'is_read' => true,
        ], 'user_id = ? AND is_read = FALSE', [$userId]);
        return true;
    }

    public static function delete(int $notificationId): bool
    {
        Database::delete('notifications', 'id = ?', [$notificationId]);
        return true;
    }

    public static function deleteAll(int $userId): bool
    {
        Database::delete('notifications', 'user_id = ?', [$userId]);
        return true;
    }

    public static function notifyDepositApproved(int $userId, float $amount): void
    {
        self::create(
            $userId,
            'deposit_approved',
            'Deposit Approved!',
            "Your deposit of \${$amount} has been approved and added to your wallet.",
            'check-circle',
            '/wallet'
        );
    }

    public static function notifyDepositRejected(int $userId, string $reason = ''): void
    {
        self::create(
            $userId,
            'deposit_rejected',
            'Deposit Rejected',
            'Your deposit has been rejected. ' . ($reason ? "Reason: {$reason}" : ''),
            'x-circle',
            '/wallet'
        );
    }

    public static function notifyReferralBonus(int $userId, float $amount, string $referredName): void
    {
        self::create(
            $userId,
            'referral_bonus',
            'Referral Bonus Earned!',
            "{$referredName}'s deposit was approved. You earned \${$amount} bonus commission!",
            'gift',
            '/referrals'
        );
    }

    public static function notifyReferralClick(int $userId): void
    {
        self::create(
            $userId,
            'referral_click',
            'Referral Link Clicked',
            'Someone clicked your referral link!',
            'eye',
            '/referrals'
        );
    }

    public static function notifyWithdrawalApproved(int $userId, float $amount): void
    {
        self::create(
            $userId,
            'withdrawal_approved',
            'Withdrawal Approved!',
            "Your withdrawal of \${$amount} has been approved.",
            'arrow-down',
            '/wallet'
        );
    }

    public static function notifyNews(int $userId, string $headline): void
    {
        self::create(
            $userId,
            'news',
            'Market News',
            $headline,
            'newspaper',
            '/news'
        );
    }

    public static function notifyNewExpert(int $userId, string $expertName): void
    {
        self::create(
            $userId,
            'new_expert',
            'New Copy Trading Expert',
            "Check out {$expertName}'s trading strategy!",
            'star',
            '/copy-experts'
        );
    }

    public static function notifyNFTUpdate(int $userId, string $collectionName, string $update): void
    {
        self::create(
            $userId,
            'nft_update',
            "NFT Market Update: {$collectionName}",
            $update,
            'image',
            '/nfts'
        );
    }

    public static function notifyLoanApproved(int $userId, float $amount): void
    {
        self::create(
            $userId,
            'loan_approved',
            'Loan Approved!',
            "Your crypto loan of \${$amount} has been approved.",
            'credit-card',
            '/loans'
        );
    }

    public static function notifyLoanRejected(int $userId): void
    {
        self::create(
            $userId,
            'loan_rejected',
            'Loan Application Rejected',
            'Your loan application was not approved.',
            'alert-circle',
            '/loans'
        );
    }

    public static function notifyTradeAlert(int $userId, string $signal, string $asset): void
    {
        self::create(
            $userId,
            'trade_alert',
            "Trading Signal: {$signal} on {$asset}",
            "Strong {$signal} signal detected on {$asset}.",
            'trending-up',
            '/signals'
        );
    }

    public static function timeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) return "just now";
        if ($diff < 3600) return floor($diff / 60) . "m ago";
        if ($diff < 86400) return floor($diff / 3600) . "h ago";
        if ($diff < 604800) return floor($diff / 86400) . "d ago";
        return date('M j, Y', $time);
    }
}
