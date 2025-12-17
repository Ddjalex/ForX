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
    <aside class="sidebar">
        <div class="sidebar-logo">
            <h1>TradeFlow</h1>
        </div>
        <nav>
            <ul class="sidebar-nav">
                <li><a href="/dashboard" class="<?= ($_SERVER['REQUEST_URI'] === '/dashboard') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a></li>
                
                <li><a href="/markets" class="<?= (strpos($_SERVER['REQUEST_URI'], '/markets') === 0 || strpos($_SERVER['REQUEST_URI'], '/trade') === 0) ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22,7 13.5,15.5 8.5,10.5 2,17"></polyline><polyline points="16,7 22,7 22,13"></polyline></svg>
                    Trading
                </a></li>
                
                <li><a href="/positions" class="<?= ($_SERVER['REQUEST_URI'] === '/positions') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    Trade History
                </a></li>
                
                <li><a href="/wallet/deposit" class="<?= ($_SERVER['REQUEST_URI'] === '/wallet/deposit') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                    Make Deposit
                </a></li>
                
                <li><a href="/wallet/withdraw" class="<?= ($_SERVER['REQUEST_URI'] === '/wallet/withdraw') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 19 19 12"></polyline></svg>
                    Withdraw Funds
                </a></li>
                
                <li><a href="/accounts" class="<?= ($_SERVER['REQUEST_URI'] === '/accounts') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Account
                </a></li>
                
                <?php if (\App\Services\Auth::isAdmin()): ?>
                <li class="sidebar-section">Admin</li>
                <li><a href="/admin" class="<?= ($_SERVER['REQUEST_URI'] === '/admin') ? 'active' : '' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    Admin Panel
                </a></li>
                <?php endif; ?>
                
                <li><a href="/logout">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Logout
                </a></li>
            </ul>
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
