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
        <div class="tradingview-ticker-container">
            <div class="tradingview-widget-container">
                <div class="tradingview-widget-container__widget"></div>
                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                {
                    "symbols": [
                        {"proName": "FOREXCOM:SPXUSD", "title": "S&P 500"},
                        {"proName": "FOREXCOM:NSXUSD", "title": "Nasdaq 100"},
                        {"proName": "FX_IDC:EURUSD", "title": "EUR/USD"},
                        {"proName": "BITSTAMP:BTCUSD", "title": "BTC/USD"},
                        {"proName": "BITSTAMP:ETHUSD", "title": "ETH/USD"},
                        {"proName": "FOREXCOM:XAUUSD", "title": "Gold"},
                        {"proName": "FX_IDC:GBPUSD", "title": "GBP/USD"},
                        {"proName": "FX_IDC:USDJPY", "title": "USD/JPY"}
                    ],
                    "showSymbolLogo": true,
                    "isTransparent": true,
                    "displayMode": "adaptive",
                    "colorTheme": "dark",
                    "locale": "en"
                }
                </script>
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
            const country = countries[Math.floor(Math.random() * countries.length)];
            const action = actions[Math.floor(Math.random() * actions.length)];
            const amount = (Math.floor(Math.random() * 50) + 1) * 1000;
            
            document.getElementById('popupCountry').textContent = country;
            document.getElementById('popupAmount').textContent = '$' + amount.toLocaleString();
            popup.querySelector('.content p').innerHTML = `Someone from <strong>${country}</strong> has ${action}`;
            
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
