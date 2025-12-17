<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - Trade Flow Globalex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
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
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['first_name'] ?? 'User') ?>&background=00D4AA&color=0a1628" alt="User">
                </div>
                <div class="sidebar-user-info">
                    <span class="sidebar-user-name"><?= htmlspecialchars($_SESSION['user']['first_name'] ?? 'User') ?></span>
                    <span class="sidebar-user-status">Not Verified</span>
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
                    Account
                </a></li>
                
                <li><a href="/wallet/deposit" class="<?= ($_SERVER['REQUEST_URI'] === '/wallet/deposit') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    Deposit
                </a></li>
                
                <li><a href="/wallet/withdraw" class="<?= ($_SERVER['REQUEST_URI'] === '/wallet/withdraw') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="6" y1="12" x2="18" y2="12"></line></svg>
                    Withdraw
                </a></li>
                
                <li><a href="/dashboard/trade" class="<?= (strpos($_SERVER['REQUEST_URI'], '/markets') === 0 || strpos($_SERVER['REQUEST_URI'], '/dashboard/trade') === 0) ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22,7 13.5,15.5 8.5,10.5 2,17"></polyline><polyline points="16,7 22,7 22,13"></polyline></svg>
                    Trade
                </a></li>
                
                <li><a href="/copy-experts" class="<?= ($_SERVER['REQUEST_URI'] === '/copy-experts') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                    Copy Experts
                </a></li>
                
                <li><a href="/subscribe" class="<?= ($_SERVER['REQUEST_URI'] === '/subscribe') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    Subscribe
                </a></li>
                
                <li><a href="/nfts" class="<?= ($_SERVER['REQUEST_URI'] === '/nfts') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
                    NFTS
                </a></li>
                
                <li><a href="/signal" class="<?= ($_SERVER['REQUEST_URI'] === '/signal') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 20h.01"></path><path d="M7 20v-4"></path><path d="M12 20v-8"></path><path d="M17 20V8"></path><path d="M22 4v16"></path></svg>
                    Signal
                </a></li>
                
                <li><a href="/loan" class="<?= ($_SERVER['REQUEST_URI'] === '/loan') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2V5z"></path><path d="M2 9v1c0 1.1.9 2 2 2h1"></path><path d="M16 11h0"></path></svg>
                    Loan
                </a></li>
                
                <li><a href="/dashboard/trades/history" class="<?= (strpos($_SERVER['REQUEST_URI'], '/dashboard/trades/history') === 0) ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    Trade History
                </a></li>
                
                <li><a href="/transactions" class="<?= ($_SERVER['REQUEST_URI'] === '/transactions') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 1 21 5 17 9"></polyline><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path></svg>
                    Transactions
                </a></li>
                
                <li><a href="/news" class="<?= ($_SERVER['REQUEST_URI'] === '/news') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path><path d="M10 6h8v4h-8V6Z"></path></svg>
                    News
                </a></li>
                
                <li><a href="/settings" class="<?= ($_SERVER['REQUEST_URI'] === '/settings') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    Account Settings
                </a></li>
                
                <li><a href="/referrals" class="<?= ($_SERVER['REQUEST_URI'] === '/referrals') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Referrals
                </a></li>
                
                <li><a href="/logout" class="logout-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Logout
                </a></li>
            </ul>
            
            <div class="sidebar-dropdown">
                <div class="sidebar-dropdown-header" id="liveAnalysisToggle">
                    <span>Live Analysis</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div class="sidebar-dropdown-content" id="liveAnalysisContent">
                    <a href="/analysis/forex">Forex Analysis</a>
                    <a href="/analysis/crypto">Crypto Analysis</a>
                    <a href="/analysis/stocks">Stock Analysis</a>
                </div>
            </div>
        </nav>
    </aside>

    <main class="main-content">
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

    <div class="language-selector">
        <span>EN</span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
    </div>

    <div class="investment-popup" id="investmentPopup" style="display: none;">
        <div class="icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
        <div class="content">
            <p>Someone from <strong id="popupCountry">South Africa</strong> just invested</p>
            <div class="amount" id="popupAmount">$10,000</div>
        </div>
    </div>

    <script src="/assets/js/app.js"></script>
    <script>
        const countries = ['South Africa', 'United Kingdom', 'Germany', 'Canada', 'Australia', 'Mexico', 'Cyprus', 'Netherlands', 'France', 'Spain'];
        const actions = ['invested', 'withdrawn'];
        
        function showInvestmentPopup() {
            const popup = document.getElementById('investmentPopup');
            const countryEl = document.getElementById('popupCountry');
            const amountEl = document.getElementById('popupAmount');
            
            if (!popup || !countryEl || !amountEl) return;
            
            const country = countries[Math.floor(Math.random() * countries.length)];
            const action = actions[Math.floor(Math.random() * actions.length)];
            const amount = (Math.floor(Math.random() * 50) + 1) * 1000;
            
            countryEl.textContent = country;
            amountEl.textContent = '$' + amount.toLocaleString();
            const contentP = popup.querySelector('.content p');
            if (contentP) contentP.innerHTML = `Someone from <strong>${country}</strong> has ${action}`;
            
            popup.style.display = 'flex';
            
            setTimeout(() => {
                popup.style.display = 'none';
            }, 5000);
        }
        
        setInterval(showInvestmentPopup, 15000);
        setTimeout(showInvestmentPopup, 3000);
    </script>
</body>
</html>
