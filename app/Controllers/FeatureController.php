<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Router;
use App\Services\Session;
use App\Services\NewsService;
use App\Services\NFTService;
use App\Services\SignalService;
use App\Services\LoanService;
use App\Services\ReferralService;

class FeatureController
{
    public function news(): void
    {
        $news = NewsService::getLatestNews(12);
        
        echo Router::render('features/news', [
            'news' => $news,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }
    
    public function nfts(): void
    {
        $collections = NFTService::getTopCollections(12);
        
        echo Router::render('features/nfts', [
            'collections' => $collections,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }
    
    public function signals(): void
    {
        $signals = SignalService::getLatestSignals(10);
        
        echo Router::render('features/signals', [
            'signals' => $signals,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }
    
    public function loans(): void
    {
        $user = Auth::user();
        $plans = LoanService::getLoanPlans();
        $userLoans = LoanService::getUserLoans($user['id']);
        
        echo Router::render('features/loans', [
            'plans' => $plans,
            'userLoans' => $userLoans,
            'csrf_token' => Session::generateCsrfToken(),
            'success' => Session::getFlash('success'),
            'error' => Session::getFlash('error'),
        ]);
    }
    
    public function applyLoan(): void
    {
        $csrfToken = $_POST['_csrf_token'] ?? '';
        if (empty($csrfToken) || !Session::validateCsrfToken($csrfToken)) {
            Session::flash('error', 'Invalid request. Please try again.');
            Router::redirect('/loans');
            return;
        }
        
        $user = Auth::user();
        
        $planId = filter_input(INPUT_POST, 'plan_id', FILTER_VALIDATE_INT);
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        $collateral = filter_input(INPUT_POST, 'collateral', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'BTC';
        
        $allowedCollaterals = ['BTC', 'ETH', 'USDT'];
        if (!in_array($collateral, $allowedCollaterals)) {
            Session::flash('error', 'Invalid collateral type.');
            Router::redirect('/loans');
            return;
        }
        
        if (!$planId || !$amount) {
            Session::flash('error', 'Please fill in all required fields.');
            Router::redirect('/loans');
            return;
        }
        
        $result = LoanService::applyForLoan($user['id'], $planId, $amount, $collateral);
        
        if ($result['success']) {
            Session::flash('success', $result['message']);
        } else {
            Session::flash('error', $result['error']);
        }
        
        Router::redirect('/loans');
    }
    
    public function referrals(): void
    {
        $user = Auth::user();
        $stats = ReferralService::getReferralStats($user['id']);
        $tiers = ReferralService::getReferralTiers();
        
        $currentTier = $tiers[0];
        foreach ($tiers as $tier) {
            if ($stats['total_referrals'] >= $tier['min_referrals']) {
                $currentTier = $tier;
            }
        }
        
        // Use APP_URL environment variable for referral links (primary domain)
        $appUrl = getenv('APP_URL') ?: 'https://alphacoremarkets.com';
        $appUrl = rtrim($appUrl, '/');
        $referralLink = $appUrl . '/register?ref=' . htmlspecialchars($stats['code'], ENT_QUOTES, 'UTF-8');
        
        echo Router::render('features/referrals', [
            'stats' => $stats,
            'tiers' => $tiers,
            'currentTier' => $currentTier,
            'referralLink' => $referralLink,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }
    
    public function liveAnalysis(): void
    {
        $signals = SignalService::getLatestSignals(8);
        $news = NewsService::getLatestNews(5);
        
        echo Router::render('features/live-analysis', [
            'signals' => $signals,
            'news' => $news,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }
}
