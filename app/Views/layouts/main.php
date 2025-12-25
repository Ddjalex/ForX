<?php
use App\Services\Translation;

$currentLanguage = $_COOKIE['language'] ?? $_GET['lang'] ?? 'en';
Translation::setLanguage($currentLanguage);
setcookie('language', $currentLanguage, time() + (365 * 24 * 60 * 60), '/');

if (!function_exists('t')) {
    function t($key, $default = '') {
        return Translation::t($key, $default);
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $currentLanguage ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - Alpha Core Markets</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/ticker-prices.js" defer></script>
    <script src="/assets/js/market-data.js" defer></script>
    <script src="/assets/js/tradingview-market-data.js" defer></script>
    <script src="/assets/js/notifications.js" defer></script>
    <style>
        :root {
            --primary: #00D4AA;
            --bg-dark: #0a1628;
            --bg-secondary: #0f1822;
            --text-color: #ffffff;
        }
        .sidebar { position: fixed; top: 0; left: 0; bottom: 0; width: 260px; height: 100vh; overflow: hidden; z-index: 10002; background: #0a1628; border-right: 1px solid rgba(0, 212, 170, 0.1); transform: translateX(-260px); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; opacity: 0; visibility: hidden; }
        .sidebar.active { transform: translateX(0); opacity: 1; visibility: visible; }
        .sidebar-header { flex-shrink: 0; background: #0a1628; padding: 20px; border-bottom: 1px solid rgba(0, 212, 170, 0.1); display: flex; align-items: center; justify-content: space-between; }
        .sidebar-user { display: flex; align-items: center; gap: 12px; }
        .sidebar-user-avatar { width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--primary); overflow: hidden; }
        .sidebar-user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-user-info { display: flex; flex-direction: column; }
        .sidebar-user-name { font-size: 14px; font-weight: 600; color: #fff; }
        .sidebar-user-status { font-size: 11px; color: var(--primary); }
        .sidebar-close { background: none; border: none; color: #8899a6; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; }
        .sidebar-close:hover { color: #fff; }
        
        .sidebar-nav-scroll { flex-grow: 1; overflow-y: auto; padding: 10px 0; scrollbar-width: thin; scrollbar-color: rgba(0, 212, 170, 0.2) transparent; }
        .sidebar-nav-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-nav-scroll::-webkit-scrollbar-thumb { background: rgba(0, 212, 170, 0.2); border-radius: 2px; }
        
        .sidebar-nav { list-style: none; padding: 0; margin: 0; }
        .sidebar-nav li { margin: 4px 12px; }
        .sidebar-nav a { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #8899a6 !important; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: all 0.2s ease; }
        .sidebar-nav a:hover { background: rgba(0, 212, 170, 0.05); color: #fff !important; }
        .sidebar-nav a.active { background: rgba(0, 212, 170, 0.1); color: var(--primary) !important; }
        .sidebar-nav a svg { color: inherit !important; flex-shrink: 0; display: inline-block !important; visibility: visible !important; opacity: 1 !important; }
        
        .sidebar-dropdown { margin: 4px 12px; }
        .sidebar-dropdown-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; color: #8899a6; cursor: pointer; border-radius: 8px; font-size: 14px; font-weight: 500; transition: all 0.2s ease; }
        .sidebar-dropdown-header:hover { background: rgba(0, 212, 170, 0.05); color: #fff; }
        .sidebar-dropdown-content { display: none; padding-left: 28px; margin-top: 4px; }
        .sidebar-dropdown-content.show { display: block; }
        .sidebar-dropdown-content a { padding: 10px 16px; font-size: 13px; color: #8899a6 !important; }
        .sidebar-dropdown-content a:hover { color: #fff !important; }

        .main-content { margin-left: 0; padding-top: 70px; min-height: 100vh; background: #0a1628; position: relative; transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
        .main-content.shifted { margin-left: 260px; width: calc(100% - 260px); }
        
        .top-header { position: fixed; top: 0; left: 0; right: 0; height: 70px; background: #0a1628; display: flex; align-items: center; padding: 0 20px; z-index: 10000; border-bottom: 1px solid rgba(0, 212, 170, 0.1); transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .top-header.shifted { left: 260px; }
        .top-header-link { text-decoration: none; color: inherit; }
        .top-header-content { display: flex; align-items: center; gap: 15px; margin-left: 50px; }
        .top-header-logo { display: flex; align-items: center; gap: 12px; }
        .header-logo-img { height: 35px; width: auto; }
        .header-text { display: flex; flex-direction: column; }
        .header-title { font-size: 16px; font-weight: 700; color: #fff; margin: 0; letter-spacing: 0.5px; }
        .header-subtitle { font-size: 10px; color: var(--primary); margin: 0; text-transform: uppercase; font-weight: 600; }
        
        .header-controls { position: fixed; top: 0; right: 0; height: 70px; display: flex; align-items: center; padding: 0 20px; z-index: 10001; }
        .sidebar-toggle { position: fixed; top: 15px; left: 15px; z-index: 10003; background: rgba(0, 212, 170, 0.1); border: 1px solid rgba(0, 212, 170, 0.2); border-radius: 4px; padding: 8px; cursor: pointer; color: #00D4AA; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .sidebar-toggle:hover { background: rgba(0, 212, 170, 0.2); }
        .sidebar-toggle.active { left: 275px; }
        
        .notification-btn { background: none; border: none; color: #8899a6; cursor: pointer; padding: 8px; border-radius: 4px; position: relative; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .notification-btn:hover { color: var(--primary); background: rgba(0, 212, 170, 0.05); }
        .notification-badge { position: absolute; top: 2px; right: 2px; background: #ff4757; color: #fff; font-size: 10px; font-weight: 700; padding: 2px 5px; border-radius: 10px; border: 2px solid #0a1628; }
        
        .notification-panel { display: none; position: fixed; right: 20px; top: 75px; z-index: 100000; width: 350px; background: #0f1822; border: 1px solid rgba(0, 212, 170, 0.2); border-radius: 12px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5); overflow: hidden; }

        @media (max-width: 768px) { 
            .main-content.shifted { margin-left: 0; width: 100%; }
            .top-header.shifted { left: 0; }
            .sidebar-toggle.active { left: 15px; }
            .notification-panel { width: calc(100% - 40px); right: 20px; left: 20px; }
            .top-header-content { margin-left: 45px; }
            .header-title { font-size: 14px; }
            .header-subtitle { font-size: 8px; }
        }
    </style>
</head>
<body>
    <button class="sidebar-toggle" id="sidebarToggle">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="User" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=00D4AA&color=0a1628'">
                    <?php else: ?>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=00D4AA&color=0a1628" alt="User">
                    <?php endif; ?>
                </div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name"><?= htmlspecialchars($user['name'] ?? 'User') ?></span>
                    <span class="sidebar-user-status"><?= ($user['kyc_status'] ?? 'pending') === 'approved' ? t('verified') : t('not_verified') ?></span>
                </div>
            </div>
            <button class="sidebar-close" id="sidebarClose">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <nav class="sidebar-nav-scroll">
            <ul class="sidebar-nav">
                <li><a href="/accounts" class="<?= ($_SERVER['REQUEST_URI'] === '/accounts') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <?= t('account_settings') ?>
                </a></li>
                
                <li><a href="/kyc/verify" class="<?= ($_SERVER['REQUEST_URI'] === '/kyc/verify') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <?= t('verify_account') ?? 'Verify Account' ?>
                </a></li>
                
                <li><a href="/wallet/deposit" class="<?= ($_SERVER['REQUEST_URI'] === '/wallet/deposit') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    <?= t('deposit') ?>
                </a></li>
                
                <li><a href="/wallet/withdraw" class="<?= ($_SERVER['REQUEST_URI'] === '/wallet/withdraw') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="6" y1="12" x2="18" y2="12"></line></svg>
                    <?= t('withdraw') ?>
                </a></li>
                
                <li><a href="/dashboard/trade" class="<?= (strpos($_SERVER['REQUEST_URI'], '/markets') === 0 || strpos($_SERVER['REQUEST_URI'], '/dashboard/trade') === 0) ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22,7 13.5,15.5 8.5,10.5 2,17"></polyline><polyline points="16,7 22,7 22,13"></polyline></svg>
                    <?= t('trade') ?>
                </a></li>
                
                <li><a href="/copy-experts" class="<?= ($_SERVER['REQUEST_URI'] === '/copy-experts') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    <?= t('copy_experts') ?>
                </a></li>
                
                <li><a href="/news" class="<?= ($_SERVER['REQUEST_URI'] === '/news') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path><path d="M10 6h8v4h-8V6Z"></path></svg>
                    <?= t('news') ?>
                </a></li>
                
                <li><a href="/nfts" class="<?= ($_SERVER['REQUEST_URI'] === '/nfts') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
                    <?= t('nfts') ?>
                </a></li>
                
                <li><a href="/signals" class="<?= ($_SERVER['REQUEST_URI'] === '/signals') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 20h.01"></path><path d="M7 20v-4"></path><path d="M12 20v-8"></path><path d="M17 20V8"></path><path d="M22 4v16"></path></svg>
                    <?= t('signals') ?>
                </a></li>
                
                <li><a href="/loans" class="<?= ($_SERVER['REQUEST_URI'] === '/loans') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2V5z"></path><path d="M2 9v1c0 1.1.9 2 2 2h1"></path><path d="M16 11h0"></path></svg>
                    <?= t('loans') ?>
                </a></li>
                
                <li><a href="/dashboard/trades/history" class="<?= (strpos($_SERVER['REQUEST_URI'], '/dashboard/trades/history') === 0) ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <?= t('trade_history') ?>
                </a></li>
                
                <li><a href="/dashboard/trades/history" class="<?= ($_SERVER['REQUEST_URI'] === '/dashboard/trades/history') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                    <?= t('transactions') ?>
                </a></li>
                
                <li><a href="/referrals" class="<?= ($_SERVER['REQUEST_URI'] === '/referrals') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <?= t('referrals') ?>
                </a></li>
                
                <li><a href="/logout" class="logout-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <?= t('logout') ?>
                </a></li>
            </ul>
            
            <div class="sidebar-dropdown">
                <div class="sidebar-dropdown-header" id="liveAnalysisToggle">
                    <span><?= t('live_analysis') ?></span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="sidebar-dropdown-content" id="liveAnalysisContent">
                    <a href="/analysis/forex"><?= t('forex_analysis') ?></a>
                    <a href="/analysis/crypto"><?= t('crypto_analysis') ?></a>
                    <a href="/analysis/stocks"><?= t('stock_analysis') ?></a>
                </div>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <div class="top-header">
            <a href="/dashboard" class="top-header-link">
                <div class="top-header-content">
                    <div class="top-header-logo">
                        <img src="/assets/logo.png" alt="Alpha Core Markets Logo" class="header-logo-img">
                        <div class="header-text">
                            <h1 class="header-title">Alpha Core Markets</h1>
                            <p class="header-subtitle">Professional Trading Platform</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="header-controls">
            <div id="headerClock" style="color: #00D4AA; font-family: 'Inter', sans-serif; font-size: 14px; margin-right: 15px; font-weight: 600;"></div>
            <button class="notification-btn" id="notificationBtn" title="Notifications">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
            </button>
        </div>

        <div class="notification-panel" id="notificationPanel">
            <div class="notification-modal-header">
                <h2>Notifications</h2>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <button id="markAllReadBtn" style="background: none; border: 1px solid var(--primary); color: var(--primary); padding: 4px 10px; border-radius: 4px; font-size: 11px; cursor: pointer; font-weight: 500;">Mark all read</button>
                    <button class="notification-close" id="notificationClose" style="background: none; border: none; cursor: pointer; color: #8899a6; display: flex; align-items: center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="notification-list" id="notificationList">
                <div style="padding: 40px 20px; text-align: center; color: #556677; font-size: 13px;">
                    <div style="margin-bottom: 10px; opacity: 0.5;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg></div>
                    Loading notifications...
                </div>
            </div>
        </div>

        <div class="pro-ticker-wrapper">
            <div class="pro-ticker-track">
                <div class="pro-ticker-content">
                    <div class="ticker-category">
                        <span class="category-label indices">INDICES</span>
                    </div>
                    <div class="ticker-item" data-symbol="SPX500">
                        <span class="ticker-symbol">S&P 500</span>
                        <span class="ticker-price" id="price-spx">5,892.45</span>
                        <span class="ticker-change positive" id="change-spx">+0.82%</span>
                    </div>
                    <div class="ticker-item" data-symbol="NASDAQ">
                        <span class="ticker-symbol">NASDAQ</span>
                        <span class="ticker-price" id="price-nasdaq">18,542.18</span>
                        <span class="ticker-change positive" id="change-nasdaq">+1.24%</span>
                    </div>
                    <div class="ticker-item" data-symbol="DJI">
                        <span class="ticker-symbol">DOW JONES</span>
                        <span class="ticker-price" id="price-dji">42,156.89</span>
                        <span class="ticker-change negative" id="change-dji">-0.18%</span>
                    </div>
                    <div class="ticker-item" data-symbol="FTSE">
                        <span class="ticker-symbol">FTSE 100</span>
                        <span class="ticker-price" id="price-ftse">8,245.67</span>
                        <span class="ticker-change positive" id="change-ftse">+0.45%</span>
                    </div>
                    <div class="ticker-item" data-symbol="DAX">
                        <span class="ticker-symbol">DAX</span>
                        <span class="ticker-price" id="price-dax">18,892.34</span>
                        <span class="ticker-change positive" id="change-dax">+0.67%</span>
                    </div>
                    <div class="ticker-item" data-symbol="NIKKEI">
                        <span class="ticker-symbol">NIKKEI 225</span>
                        <span class="ticker-price" id="price-nikkei">38,456.12</span>
                        <span class="ticker-change negative" id="change-nikkei">-0.32%</span>
                    </div>
                    
                    <div class="ticker-divider"></div>
                    
                    <div class="ticker-category">
                        <span class="category-label crypto">CRYPTO</span>
                    </div>
                    <div class="ticker-item" data-symbol="BTC">
                        <span class="ticker-symbol">BTC/USD</span>
                        <span class="ticker-price" id="price-btc">$104,256.78</span>
                        <span class="ticker-change positive" id="change-btc">+3.45%</span>
                    </div>
                    <div class="ticker-item" data-symbol="ETH">
                        <span class="ticker-symbol">ETH/USD</span>
                        <span class="ticker-price" id="price-eth">$3,892.45</span>
                        <span class="ticker-change positive" id="change-eth">+2.18%</span>
                    </div>
                    <div class="ticker-item" data-symbol="XRP">
                        <span class="ticker-symbol">XRP/USD</span>
                        <span class="ticker-price" id="price-xrp">$2.3456</span>
                        <span class="ticker-change positive" id="change-xrp">+5.67%</span>
                    </div>
                    <div class="ticker-item" data-symbol="SOL">
                        <span class="ticker-symbol">SOL/USD</span>
                        <span class="ticker-price" id="price-sol">$218.92</span>
                        <span class="ticker-change negative" id="change-sol">-1.23%</span>
                    </div>
                    <div class="ticker-item" data-symbol="BNB">
                        <span class="ticker-symbol">BNB/USD</span>
                        <span class="ticker-price" id="price-bnb">$712.34</span>
                        <span class="ticker-change positive" id="change-bnb">+0.89%</span>
                    </div>
                    <div class="ticker-item" data-symbol="ADA">
                        <span class="ticker-symbol">ADA/USD</span>
                        <span class="ticker-price" id="price-ada">$1.0234</span>
                        <span class="ticker-change positive" id="change-ada">+4.12%</span>
                    </div>
                    
                    <div class="ticker-divider"></div>
                    
                    <div class="ticker-category">
                        <span class="category-label forex">FOREX</span>
                    </div>
                    <div class="ticker-item" data-symbol="EURUSD">
                        <span class="ticker-symbol">EUR/USD</span>
                        <span class="ticker-price" id="price-eurusd">1.0523</span>
                        <span class="ticker-change negative" id="change-eurusd">-0.15%</span>
                    </div>
                    <div class="ticker-item" data-symbol="GBPUSD">
                        <span class="ticker-symbol">GBP/USD</span>
                        <span class="ticker-price" id="price-gbpusd">1.2678</span>
                        <span class="ticker-change positive" id="change-gbpusd">+0.23%</span>
                    </div>
                    <div class="ticker-item" data-symbol="USDJPY">
                        <span class="ticker-symbol">USD/JPY</span>
                        <span class="ticker-price" id="price-usdjpy">153.45</span>
                        <span class="ticker-change positive" id="change-usdjpy">+0.42%</span>
                    </div>
                    <div class="ticker-item" data-symbol="AUDUSD">
                        <span class="ticker-symbol">AUD/USD</span>
                        <span class="ticker-price" id="price-audusd">0.6342</span>
                        <span class="ticker-change negative" id="change-audusd">-0.31%</span>
                    </div>
                    <div class="ticker-item" data-symbol="USDCAD">
                        <span class="ticker-symbol">USD/CAD</span>
                        <span class="ticker-price" id="price-usdcad">1.4234</span>
                        <span class="ticker-change positive" id="change-usdcad">+0.18%</span>
                    </div>
                    <div class="ticker-item" data-symbol="USDCHF">
                        <span class="ticker-symbol">USD/CHF</span>
                        <span class="ticker-price" id="price-usdchf">0.8923</span>
                        <span class="ticker-change negative" id="change-usdchf">-0.08%</span>
                    </div>
                    
                    <div class="ticker-divider"></div>
                    
                    <div class="ticker-category">
                        <span class="category-label commodities">COMMODITIES</span>
                    </div>
                    <div class="ticker-item" data-symbol="XAUUSD">
                        <span class="ticker-symbol">GOLD</span>
                        <span class="ticker-price" id="price-gold">$2,678.45</span>
                        <span class="ticker-change positive" id="change-gold">+0.56%</span>
                    </div>
                    <div class="ticker-item" data-symbol="XAGUSD">
                        <span class="ticker-symbol">SILVER</span>
                        <span class="ticker-price" id="price-silver">$31.23</span>
                        <span class="ticker-change positive" id="change-silver">+1.12%</span>
                    </div>
                    <div class="ticker-item" data-symbol="WTICOUSD">
                        <span class="ticker-symbol">CRUDE OIL</span>
                        <span class="ticker-price" id="price-oil">$71.45</span>
                        <span class="ticker-change negative" id="change-oil">-0.89%</span>
                    </div>
                    <div class="ticker-item" data-symbol="NATGAS">
                        <span class="ticker-symbol">NAT GAS</span>
                        <span class="ticker-price" id="price-natgas">$3.245</span>
                        <span class="ticker-change positive" id="change-natgas">+2.34%</span>
                    </div>
                    <div class="ticker-item" data-symbol="COPPER">
                        <span class="ticker-symbol">COPPER</span>
                        <span class="ticker-price" id="price-copper">$4.12</span>
                        <span class="ticker-change positive" id="change-copper">+0.78%</span>
                    </div>
                    <div class="ticker-item" data-symbol="PLATINUM">
                        <span class="ticker-symbol">PLATINUM</span>
                        <span class="ticker-price" id="price-platinum">$956.78</span>
                        <span class="ticker-change negative" id="change-platinum">-0.45%</span>
                    </div>
                    
                    <div class="ticker-divider"></div>
                    
                    <div class="ticker-category">
                        <span class="category-label stocks">STOCKS</span>
                    </div>
                    <div class="ticker-item" data-symbol="AAPL">
                        <span class="ticker-symbol">AAPL</span>
                        <span class="ticker-price" id="price-aapl">$248.92</span>
                        <span class="ticker-change positive" id="change-aapl">+1.45%</span>
                    </div>
                </div>
            </div>
        </div>

        <?= $content ?>
    </main>

    <a href="https://wa.me/1234567890" target="_blank" class="whatsapp-btn">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.767 5.767 0 1.267.405 2.436 1.094 3.388l-.71 2.593 2.656-.697a5.727 5.727 0 002.727.682c3.181 0 5.767-2.586 5.767-5.767 0-3.181-2.586-5.767-5.767-5.767zm3.39 8.13c-.147.414-.733.754-1.012.795-.27.041-.532.063-.844-.067-.204-.085-.86-.341-1.637-1.033-.604-.539-1.012-1.206-1.13-1.408-.118-.202-.013-.311.088-.411.091-.09.202-.236.303-.354.101-.118.135-.202.202-.337.067-.135.034-.253-.017-.354-.051-.101-.455-1.095-.624-1.503-.164-.397-.33-.343-.455-.349-.118-.006-.253-.007-.388-.007s-.354.051-.539.253c-.185.202-.708.691-.708 1.684s.725 1.954.826 2.089c.101.135 1.425 2.177 3.453 3.054.482.209.859.333 1.151.426.485.154.926.132 1.275.08.389-.058 1.2-.491 1.369-.965.169-.473.169-.878.118-.965-.051-.087-.185-.135-.37-.236z"/>
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.551 4.118 1.516 5.85L0 24l6.335-1.662C8.01 23.407 10.123 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.875 0-3.644-.486-5.187-1.336L2.68 21.727l1.094-3.996C2.923 16.204 2.4 14.161 2.4 12c0-5.302 4.298-9.6 9.6-9.6s9.6 4.298 9.6 9.6-4.298 9.6-9.6 9.6z"/>
        </svg>
    </a>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        const close = document.getElementById('sidebarClose');
        const mainContent = document.querySelector('.main-content');
        const topHeader = document.querySelector('.top-header');
        const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-header');

        function toggleSidebar() {
            const isActive = sidebar.classList.toggle('active');
            mainContent.classList.toggle('shifted');
            topHeader.classList.toggle('shifted');
            toggle.classList.toggle('active');
        }

        toggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleSidebar();
        });

        close?.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleSidebar();
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && 
                sidebar.classList.contains('active') && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                toggleSidebar();
            }
        });

        // Dropdown toggles
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const content = this.nextElementSibling;
                const icon = this.querySelector('svg');
                content.classList.toggle('show');
                if (icon) {
                    icon.style.transform = content.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
                    icon.style.transition = 'transform 0.3s ease';
                }
            });
        });
    });
    </script>
    
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2025 <a href="https://alphacoremarkets.com" target="_blank">Alpha Core Markets</a>. All rights reserved.</p>
        </div>
    </footer>

    <style>
        .footer {
            background: linear-gradient(135deg, rgba(0, 212, 170, 0.05) 0%, rgba(10, 22, 40, 0.5) 100%);
            border-top: 1px solid rgba(0, 212, 170, 0.1);
            padding: 30px 20px;
            text-align: center;
            margin-top: 40px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }
    </style>

    <!-- Tawk.to Script Integration -->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/69440144c73adf1980aab71c/1jcoq9t95';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!-- End Tawk.to Script -->
    <script src="/assets/js/app.js"></script>
</body>
</html>