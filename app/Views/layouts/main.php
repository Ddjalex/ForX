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
<html lang="<?= $currentLanguage ?>"><?php // Changed from "en" ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - Alpha Core Markets</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/market-data.js" defer></script>
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
            <button class="notification-btn" id="notificationBtn" title="Notifications">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notification-badge" id="notificationBadge">3</span>
            </button>
        </div>

        <div class="notification-panel" id="notificationPanel" style="display: none;">
            <div class="notification-backdrop" id="notificationBackdrop"></div>
            <div class="notification-modal">
                <div class="notification-modal-header">
                    <h2>Notifications</h2>
                    <button class="notification-close" id="notificationClose">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="notification-list" id="notificationList">
                    <div class="notification-item" data-action="deposit">
                        <div class="notification-item-icon success">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="notification-item-content">
                            <div class="notification-item-title">Deposit Confirmed</div>
                            <div class="notification-item-message">Your deposit of $500 has been processed</div>
                            <div class="notification-item-time">2 hours ago</div>
                        </div>
                    </div>

                    <div class="notification-item" data-action="news">
                        <div class="notification-item-icon info">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <div class="notification-item-content">
                            <div class="notification-item-title">New Market Update</div>
                            <div class="notification-item-message">EUR/USD has reached new support level</div>
                            <div class="notification-item-time">5 hours ago</div>
                        </div>
                    </div>

                    <div class="notification-item" data-action="dashboard">
                        <div class="notification-item-icon warning">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                        </div>
                        <div class="notification-item-content">
                            <div class="notification-item-title">Position Alert</div>
                            <div class="notification-item-message">Your BTC position is nearing stop loss</div>
                            <div class="notification-item-time">1 day ago</div>
                        </div>
                    </div>
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
                    <div class="ticker-item" data-symbol="MSFT">
                        <span class="ticker-symbol">MSFT</span>
                        <span class="ticker-price" id="price-msft">$438.56</span>
                        <span class="ticker-change positive" id="change-msft">+0.92%</span>
                    </div>
                    <div class="ticker-item" data-symbol="GOOGL">
                        <span class="ticker-symbol">GOOGL</span>
                        <span class="ticker-price" id="price-googl">$192.34</span>
                        <span class="ticker-change positive" id="change-googl">+2.18%</span>
                    </div>
                    <div class="ticker-item" data-symbol="AMZN">
                        <span class="ticker-symbol">AMZN</span>
                        <span class="ticker-price" id="price-amzn">$225.67</span>
                        <span class="ticker-change negative" id="change-amzn">-0.34%</span>
                    </div>
                    <div class="ticker-item" data-symbol="NVDA">
                        <span class="ticker-symbol">NVDA</span>
                        <span class="ticker-price" id="price-nvda">$138.45</span>
                        <span class="ticker-change positive" id="change-nvda">+3.67%</span>
                    </div>
                    <div class="ticker-item" data-symbol="TSLA">
                        <span class="ticker-symbol">TSLA</span>
                        <span class="ticker-price" id="price-tsla">$478.92</span>
                        <span class="ticker-change positive" id="change-tsla">+4.23%</span>
                    </div>
                    <div class="ticker-item" data-symbol="META">
                        <span class="ticker-symbol">META</span>
                        <span class="ticker-price" id="price-meta">$612.34</span>
                        <span class="ticker-change positive" id="change-meta">+1.56%</span>
                    </div>
                </div>
                <div class="pro-ticker-content">
                    <div class="ticker-category">
                        <span class="category-label indices">INDICES</span>
                    </div>
                    <div class="ticker-item" data-symbol="SPX500">
                        <span class="ticker-symbol">S&P 500</span>
                        <span class="ticker-price">5,892.45</span>
                        <span class="ticker-change positive">+0.82%</span>
                    </div>
                    <div class="ticker-item" data-symbol="NASDAQ">
                        <span class="ticker-symbol">NASDAQ</span>
                        <span class="ticker-price">18,542.18</span>
                        <span class="ticker-change positive">+1.24%</span>
                    </div>
                    <div class="ticker-item" data-symbol="DJI">
                        <span class="ticker-symbol">DOW JONES</span>
                        <span class="ticker-price">42,156.89</span>
                        <span class="ticker-change negative">-0.18%</span>
                    </div>
                    <div class="ticker-item" data-symbol="FTSE">
                        <span class="ticker-symbol">FTSE 100</span>
                        <span class="ticker-price">8,245.67</span>
                        <span class="ticker-change positive">+0.45%</span>
                    </div>
                    <div class="ticker-item" data-symbol="DAX">
                        <span class="ticker-symbol">DAX</span>
                        <span class="ticker-price">18,892.34</span>
                        <span class="ticker-change positive">+0.67%</span>
                    </div>
                    <div class="ticker-item" data-symbol="NIKKEI">
                        <span class="ticker-symbol">NIKKEI 225</span>
                        <span class="ticker-price">38,456.12</span>
                        <span class="ticker-change negative">-0.32%</span>
                    </div>
                    
                    <div class="ticker-divider"></div>
                    
                    <div class="ticker-category">
                        <span class="category-label crypto">CRYPTO</span>
                    </div>
                    <div class="ticker-item" data-symbol="BTC">
                        <span class="ticker-symbol">BTC/USD</span>
                        <span class="ticker-price">$104,256.78</span>
                        <span class="ticker-change positive">+3.45%</span>
                    </div>
                    <div class="ticker-item" data-symbol="ETH">
                        <span class="ticker-symbol">ETH/USD</span>
                        <span class="ticker-price">$3,892.45</span>
                        <span class="ticker-change positive">+2.18%</span>
                    </div>
                    <div class="ticker-item" data-symbol="XRP">
                        <span class="ticker-symbol">XRP/USD</span>
                        <span class="ticker-price">$2.3456</span>
                        <span class="ticker-change positive">+5.67%</span>
                    </div>
                    <div class="ticker-item" data-symbol="SOL">
                        <span class="ticker-symbol">SOL/USD</span>
                        <span class="ticker-price">$218.92</span>
                        <span class="ticker-change negative">-1.23%</span>
                    </div>
                    <div class="ticker-item" data-symbol="BNB">
                        <span class="ticker-symbol">BNB/USD</span>
                        <span class="ticker-price">$712.34</span>
                        <span class="ticker-change positive">+0.89%</span>
                    </div>
                    <div class="ticker-item" data-symbol="ADA">
                        <span class="ticker-symbol">ADA/USD</span>
                        <span class="ticker-price">$1.0234</span>
                        <span class="ticker-change positive">+4.12%</span>
                    </div>
                    
                    <div class="ticker-divider"></div>
                    
                    <div class="ticker-category">
                        <span class="category-label forex">FOREX</span>
                    </div>
                    <div class="ticker-item" data-symbol="EURUSD">
                        <span class="ticker-symbol">EUR/USD</span>
                        <span class="ticker-price">1.0523</span>
                        <span class="ticker-change negative">-0.15%</span>
                    </div>
                    <div class="ticker-item" data-symbol="GBPUSD">
                        <span class="ticker-symbol">GBP/USD</span>
                        <span class="ticker-price">1.2678</span>
                        <span class="ticker-change positive">+0.23%</span>
                    </div>
                    <div class="ticker-item" data-symbol="USDJPY">
                        <span class="ticker-symbol">USD/JPY</span>
                        <span class="ticker-price">153.45</span>
                        <span class="ticker-change positive">+0.42%</span>
                    </div>
                    <div class="ticker-item" data-symbol="AUDUSD">
                        <span class="ticker-symbol">AUD/USD</span>
                        <span class="ticker-price">0.6342</span>
                        <span class="ticker-change negative">-0.31%</span>
                    </div>
                    <div class="ticker-item" data-symbol="USDCAD">
                        <span class="ticker-symbol">USD/CAD</span>
                        <span class="ticker-price">1.4234</span>
                        <span class="ticker-change positive">+0.18%</span>
                    </div>
                    <div class="ticker-item" data-symbol="USDCHF">
                        <span class="ticker-symbol">USD/CHF</span>
                        <span class="ticker-price">0.8923</span>
                        <span class="ticker-change negative">-0.08%</span>
                    </div>
                    
                    <div class="ticker-divider"></div>
                    
                    <div class="ticker-category">
                        <span class="category-label commodities">COMMODITIES</span>
                    </div>
                    <div class="ticker-item" data-symbol="XAUUSD">
                        <span class="ticker-symbol">GOLD</span>
                        <span class="ticker-price">$2,678.45</span>
                        <span class="ticker-change positive">+0.56%</span>
                    </div>
                    <div class="ticker-item" data-symbol="XAGUSD">
                        <span class="ticker-symbol">SILVER</span>
                        <span class="ticker-price">$31.23</span>
                        <span class="ticker-change positive">+1.12%</span>
                    </div>
                    <div class="ticker-item" data-symbol="WTICOUSD">
                        <span class="ticker-symbol">CRUDE OIL</span>
                        <span class="ticker-price">$71.45</span>
                        <span class="ticker-change negative">-0.89%</span>
                    </div>
                    <div class="ticker-item" data-symbol="NATGAS">
                        <span class="ticker-symbol">NAT GAS</span>
                        <span class="ticker-price">$3.245</span>
                        <span class="ticker-change positive">+2.34%</span>
                    </div>
                    <div class="ticker-item" data-symbol="COPPER">
                        <span class="ticker-symbol">COPPER</span>
                        <span class="ticker-price">$4.12</span>
                        <span class="ticker-change positive">+0.78%</span>
                    </div>
                    <div class="ticker-item" data-symbol="PLATINUM">
                        <span class="ticker-symbol">PLATINUM</span>
                        <span class="ticker-price">$956.78</span>
                        <span class="ticker-change negative">-0.45%</span>
                    </div>
                    
                    <div class="ticker-divider"></div>
                    
                    <div class="ticker-category">
                        <span class="category-label stocks">STOCKS</span>
                    </div>
                    <div class="ticker-item" data-symbol="AAPL">
                        <span class="ticker-symbol">AAPL</span>
                        <span class="ticker-price">$248.92</span>
                        <span class="ticker-change positive">+1.45%</span>
                    </div>
                    <div class="ticker-item" data-symbol="MSFT">
                        <span class="ticker-symbol">MSFT</span>
                        <span class="ticker-price">$438.56</span>
                        <span class="ticker-change positive">+0.92%</span>
                    </div>
                    <div class="ticker-item" data-symbol="GOOGL">
                        <span class="ticker-symbol">GOOGL</span>
                        <span class="ticker-price">$192.34</span>
                        <span class="ticker-change positive">+2.18%</span>
                    </div>
                    <div class="ticker-item" data-symbol="AMZN">
                        <span class="ticker-symbol">AMZN</span>
                        <span class="ticker-price">$225.67</span>
                        <span class="ticker-change negative">-0.34%</span>
                    </div>
                    <div class="ticker-item" data-symbol="NVDA">
                        <span class="ticker-symbol">NVDA</span>
                        <span class="ticker-price">$138.45</span>
                        <span class="ticker-change positive">+3.67%</span>
                    </div>
                    <div class="ticker-item" data-symbol="TSLA">
                        <span class="ticker-symbol">TSLA</span>
                        <span class="ticker-price">$478.92</span>
                        <span class="ticker-change positive">+4.23%</span>
                    </div>
                    <div class="ticker-item" data-symbol="META">
                        <span class="ticker-symbol">META</span>
                        <span class="ticker-price">$612.34</span>
                        <span class="ticker-change positive">+1.56%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="username-bar">
            <div class="icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <span>Username: <strong><?= htmlspecialchars($user['username'] ?? $user['name'] ?? 'User') ?></strong></span>
        </div>

        <div class="content-area">
            <?= $content ?? '' ?>
        </div>
    </main>

    <a href="https://wa.me/1234567890" target="_blank" class="whatsapp-btn">
        <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>

    <div class="language-selector" id="languageSelector">
        <span><?= strtoupper($currentLanguage) ?></span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
    </div>

    <script src="/assets/js/app.js"></script>
    
    <script>
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationPanel = document.getElementById('notificationPanel');
        const notificationClose = document.getElementById('notificationClose');
        const notificationBackdrop = document.getElementById('notificationBackdrop');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationList = document.getElementById('notificationList');

        // Initialize badge count from localStorage
        function initializeBadgeCount() {
            let storedCount = localStorage.getItem('notificationCount');
            if (storedCount === null) {
                storedCount = 3; // Default count
                localStorage.setItem('notificationCount', storedCount);
            }
            updateBadgeDisplay(parseInt(storedCount));
        }

        // Update badge display
        function updateBadgeDisplay(count) {
            if (notificationBadge) {
                count = Math.max(0, count);
                if (count > 0) {
                    notificationBadge.textContent = count;
                    notificationBadge.style.display = 'inline-flex';
                } else {
                    notificationBadge.style.display = 'none';
                }
            }
        }

        // Initialize on page load
        initializeBadgeCount();

        if (notificationBtn && notificationPanel) {
            notificationBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationPanel.style.display = notificationPanel.style.display === 'none' ? 'flex' : 'none';
            });

            notificationClose.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationPanel.style.display = 'none';
            });

            if (notificationBackdrop) {
                notificationBackdrop.addEventListener('click', () => {
                    notificationPanel.style.display = 'none';
                });
            }

            document.addEventListener('click', (e) => {
                if (notificationPanel.style.display === 'flex' && 
                    !notificationBtn.contains(e.target) && 
                    !notificationPanel.contains(e.target)) {
                    notificationPanel.style.display = 'none';
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && notificationPanel.style.display === 'flex') {
                    notificationPanel.style.display = 'none';
                }
            });

            if (notificationList) {
                notificationList.addEventListener('click', (e) => {
                    const notificationItem = e.target.closest('.notification-item');
                    if (notificationItem) {
                        const action = notificationItem.getAttribute('data-action');
                        let targetUrl = '/dashboard';

                        switch(action) {
                            case 'deposit':
                                targetUrl = '/wallet/deposit';
                                break;
                            case 'news':
                                targetUrl = '/news';
                                break;
                            case 'dashboard':
                                targetUrl = '/dashboard';
                                break;
                        }

                        // Fade out notification
                        notificationItem.style.opacity = '0.5';
                        notificationItem.style.pointerEvents = 'none';
                        
                        // Update and save badge count
                        let currentCount = parseInt(localStorage.getItem('notificationCount')) || 0;
                        currentCount = Math.max(0, currentCount - 1);
                        localStorage.setItem('notificationCount', currentCount);
                        updateBadgeDisplay(currentCount);

                        setTimeout(() => {
                            window.location.href = targetUrl;
                        }, 300);
                    }
                });
            }
        }
    </script>
    
    <?php
    // Load Tawk.to settings safely
    try {
        $tawkPropertyResult = \App\Services\Database::fetch("SELECT value FROM settings WHERE key = ?", ['tawkto_property_id']);
        $tawkWidgetResult = \App\Services\Database::fetch("SELECT value FROM settings WHERE key = ?", ['tawkto_widget_id']);
        $tawkPropertyId = is_array($tawkPropertyResult) ? ($tawkPropertyResult['value'] ?? '') : '';
        $tawkWidgetId = is_array($tawkWidgetResult) ? ($tawkWidgetResult['value'] ?? '') : '';
        
        if (!empty($tawkPropertyId) && !empty($tawkWidgetId)):
        ?>
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/<?= htmlspecialchars($tawkPropertyId) ?>/<?= htmlspecialchars($tawkWidgetId) ?>';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
        <!--End of Tawk.to Script-->
        <?php endif;
    } catch (Exception $e) {
        // Silently fail if settings not available
    }
    ?>
</body>
</html>
