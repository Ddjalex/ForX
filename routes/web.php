<?php

use App\Services\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\WalletController;
use App\Controllers\TradingController;
use App\Controllers\AdminController;
use App\Controllers\ApiController;
use App\Controllers\AccountsController;
use App\Controllers\FeatureController;
use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\GuestMiddleware;

Router::get('/', function() {
    if (\App\Services\Auth::check()) {
        Router::redirect('/dashboard');
    } else {
        Router::redirect('/login');
    }
});

Router::get('/login', [AuthController::class, 'showLogin'], [GuestMiddleware::class]);
Router::post('/login', [AuthController::class, 'login']);
Router::get('/register', [AuthController::class, 'showRegister'], [GuestMiddleware::class]);
Router::post('/register', [AuthController::class, 'register']);
Router::get('/forgot-password', [AuthController::class, 'showForgotPassword'], [GuestMiddleware::class]);
Router::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Router::get('/verify-email', [AuthController::class, 'showVerifyEmail'], [GuestMiddleware::class]);
Router::post('/verify-email', [AuthController::class, 'verifyEmail']);
Router::get('/resend-verification', [AuthController::class, 'resendVerification'], [GuestMiddleware::class]);
Router::get('/logout', [AuthController::class, 'logout']);

Router::get('/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);

Router::get('/wallet', [WalletController::class, 'index'], [AuthMiddleware::class]);
Router::get('/wallet/deposit', [WalletController::class, 'showDeposit'], [AuthMiddleware::class]);
Router::post('/wallet/deposit', [WalletController::class, 'deposit'], [AuthMiddleware::class]);
Router::get('/wallet/withdraw', [WalletController::class, 'showWithdraw'], [AuthMiddleware::class]);
Router::post('/wallet/withdraw', [WalletController::class, 'withdraw'], [AuthMiddleware::class]);

Router::get('/markets', [TradingController::class, 'markets'], [AuthMiddleware::class]);
Router::get('/dashboard/trade', [TradingController::class, 'tradeDefault'], [AuthMiddleware::class]);
Router::get('/dashboard/trade/{symbol}', [TradingController::class, 'trade'], [AuthMiddleware::class]);
Router::post('/dashboard/trade/order', [TradingController::class, 'placeOrder'], [AuthMiddleware::class]);
Router::post('/dashboard/trade/close', [TradingController::class, 'closePosition'], [AuthMiddleware::class]);
Router::post('/dashboard/trade/cancel', [TradingController::class, 'cancelOrder'], [AuthMiddleware::class]);
Router::get('/positions', [TradingController::class, 'positions'], [AuthMiddleware::class]);
Router::get('/orders', [TradingController::class, 'orders'], [AuthMiddleware::class]);
Router::get('/dashboard/trades/history', [TradingController::class, 'history'], [AuthMiddleware::class]);
Router::get('/copy-experts', [TradingController::class, 'copyExperts'], [AuthMiddleware::class]);

Router::get('/news', [FeatureController::class, 'news'], [AuthMiddleware::class]);
Router::get('/nfts', [FeatureController::class, 'nfts'], [AuthMiddleware::class]);
Router::get('/signals', [FeatureController::class, 'signals'], [AuthMiddleware::class]);
Router::get('/loans', [FeatureController::class, 'loans'], [AuthMiddleware::class]);
Router::post('/loans/apply', [FeatureController::class, 'applyLoan'], [AuthMiddleware::class]);
Router::get('/referrals', [FeatureController::class, 'referrals'], [AuthMiddleware::class]);
Router::get('/live-analysis', [FeatureController::class, 'liveAnalysis'], [AuthMiddleware::class]);

Router::get('/admin', [AdminController::class, 'dashboard'], [AdminMiddleware::class]);
Router::get('/admin/users', [AdminController::class, 'users'], [AdminMiddleware::class]);
Router::post('/admin/users/update', [AdminController::class, 'updateUser'], [AdminMiddleware::class]);
Router::post('/admin/users/balance', [AdminController::class, 'editBalance'], [AdminMiddleware::class]);
Router::post('/admin/users/verify', [AdminController::class, 'verifyUser'], [AdminMiddleware::class]);
Router::get('/admin/deposits', [AdminController::class, 'deposits'], [AdminMiddleware::class]);
Router::post('/admin/deposits/approve', [AdminController::class, 'approveDeposit'], [AdminMiddleware::class]);
Router::get('/admin/withdrawals', [AdminController::class, 'withdrawals'], [AdminMiddleware::class]);
Router::post('/admin/withdrawals/approve', [AdminController::class, 'approveWithdrawal'], [AdminMiddleware::class]);
Router::get('/admin/markets', [AdminController::class, 'markets'], [AdminMiddleware::class]);
Router::post('/admin/markets/update', [AdminController::class, 'updateMarket'], [AdminMiddleware::class]);
Router::get('/admin/settings', [AdminController::class, 'settings'], [AdminMiddleware::class]);
Router::post('/admin/settings', [AdminController::class, 'updateSettings'], [AdminMiddleware::class]);
Router::get('/admin/audit-logs', [AdminController::class, 'auditLogs'], [AdminMiddleware::class]);
Router::get('/admin/positions', [AdminController::class, 'positions'], [AdminMiddleware::class]);

Router::get('/accounts', [AccountsController::class, 'index'], [AuthMiddleware::class]);
Router::post('/accounts/update-profile', [AccountsController::class, 'updateProfile'], [AuthMiddleware::class]);
Router::post('/accounts/change-password', [AccountsController::class, 'changePassword'], [AuthMiddleware::class]);
Router::post('/accounts/update-avatar', [AccountsController::class, 'updateAvatar'], [AuthMiddleware::class]);
Router::post('/accounts/create', [AccountsController::class, 'create'], [AuthMiddleware::class]);
Router::post('/accounts/set-balance', [AccountsController::class, 'setBalance'], [AuthMiddleware::class]);
Router::post('/accounts/archive', [AccountsController::class, 'archive'], [AuthMiddleware::class]);
Router::post('/accounts/restore', [AccountsController::class, 'restore'], [AuthMiddleware::class]);
Router::get('/accounts/trade', [AccountsController::class, 'trade'], [AuthMiddleware::class]);

Router::get('/api/asset-types', [ApiController::class, 'assetTypes']);
Router::get('/api/assets', [ApiController::class, 'assets']);
Router::get('/api/prices', [ApiController::class, 'prices']);
Router::get('/api/prices/{symbol}', [ApiController::class, 'marketPrice']);
Router::get('/api/prices/{symbol}/history', [ApiController::class, 'priceHistory']);
Router::get('/api/positions', [ApiController::class, 'userPositions']);
Router::get('/api/wallet', [ApiController::class, 'walletBalance']);
Router::post('/api/trade', [ApiController::class, 'executeTrade'], [AuthMiddleware::class]);
Router::post('/api/positions/close-expired', [ApiController::class, 'closeExpiredPositions'], [AuthMiddleware::class]);
Router::post('/api/positions/close', [ApiController::class, 'closePosition'], [AuthMiddleware::class]);
