<?php
ob_start();

$totalBalance = $wallet['balance'] ?? 0;
$profit = $wallet['profit'] ?? 0;
$bonus = $wallet['bonus'] ?? 0;
$isVerified = $user['email_verified'] ?? false;
$totalTrades = $stats['total_trades'] ?? 0;
$openTrades = $stats['open_trades'] ?? 0;
$closedTrades = $stats['closed_trades'] ?? 0;
$winLossRatio = $stats['win_loss_ratio'] ?? 0;
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($totalBalance, 2) ?></div>
        <div class="stat-label">Total Balance</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($profit, 2) ?></div>
        <div class="stat-label">Profit</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($bonus, 2) ?></div>
        <div class="stat-label">Total Bonus</div>
    </div>
    <div class="stat-card <?= !$isVerified ? 'danger' : '' ?>">
        <div class="stat-value" style="font-size: 16px; <?= $isVerified ? 'color: var(--success)' : 'color: var(--danger)' ?>">
            <?= $isVerified ? 'VERIFIED' : 'UNVERIFIED' ?>
        </div>
        <div class="stat-label">Account Status</div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= $totalTrades ?></div>
        <div class="stat-label">Total Trades</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $openTrades ?></div>
        <div class="stat-label">Open Trades</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $closedTrades ?></div>
        <div class="stat-label">Closed Trades</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $winLossRatio ?></div>
        <div class="stat-label">Win/Loss Ratio</div>
    </div>
</div>

<div class="action-buttons">
    <a href="/accounts" class="action-btn account">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        Account
    </a>
    <a href="/wallet/deposit" class="action-btn deposit">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        Make Deposit $
    </a>
    <a href="/wallet/withdraw" class="action-btn withdraw">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 19 19 12"></polyline></svg>
        Withdraw Funds
    </a>
    <a href="mailto:support@tradeflowglobalex.com" class="action-btn mail">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        Mail Us
    </a>
    <a href="/accounts?tab=settings" class="action-btn settings">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        Settings
    </a>
</div>

<div class="trading-section">
    <div class="card trading-chart-card">
        <div class="card-header collapsible" onclick="toggleCard('chartCard')">
            <h3 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                Live Trading Chart
            </h3>
            <div class="card-actions">
                <button class="icon-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button>
                <svg class="collapse-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        <div class="card-body" id="chartCard">
            <div class="tradingview-widget-container">
                <div id="tradingview_chart"></div>
            </div>
        </div>
    </div>

    <div class="card live-trading-card">
        <div class="card-header collapsible" onclick="toggleCard('tradingCard')">
            <h3 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Live Trading
            </h3>
            <div class="card-actions">
                <span class="balance-display">Balance: <strong>$<?= number_format($totalBalance, 2) ?></strong></span>
                <button class="icon-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button>
                <svg class="collapse-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        <div class="card-body" id="tradingCard">
            <form id="tradeForm" class="trading-form">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?? '' ?>">
                
                <div class="form-group">
                    <label>Asset Type</label>
                    <select name="asset_type" id="assetType" class="form-control">
                        <option value="crypto">Crypto</option>
                        <option value="forex">Forex</option>
                        <option value="stocks">Stocks</option>
                        <option value="commodities">Commodities</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Asset Name</label>
                    <select name="asset_name" id="assetName" class="form-control">
                        <option value="BTCUSD">BTC/USD</option>
                        <option value="ETHUSD">ETH/USD</option>
                        <option value="XRPUSD">XRP/USD</option>
                        <option value="SOLUSD">SOL/USD</option>
                        <option value="BNBUSD">BNB/USD</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Leverage:</label>
                    <div class="leverage-grid">
                        <button type="button" class="leverage-btn active" data-leverage="5">5x</button>
                        <button type="button" class="leverage-btn" data-leverage="2">2x</button>
                        <button type="button" class="leverage-btn" data-leverage="3">3x</button>
                        <button type="button" class="leverage-btn" data-leverage="4">4x</button>
                        <button type="button" class="leverage-btn" data-leverage="5">5x</button>
                        <button type="button" class="leverage-btn" data-leverage="10">10x</button>
                        <button type="button" class="leverage-btn" data-leverage="25">25x</button>
                        <button type="button" class="leverage-btn" data-leverage="30">30x</button>
                        <button type="button" class="leverage-btn" data-leverage="40">40x</button>
                        <button type="button" class="leverage-btn" data-leverage="50">50x</button>
                        <button type="button" class="leverage-btn" data-leverage="60">60x</button>
                        <button type="button" class="leverage-btn" data-leverage="70">70x</button>
                        <button type="button" class="leverage-btn" data-leverage="80">80x</button>
                        <button type="button" class="leverage-btn" data-leverage="90">90x</button>
                        <button type="button" class="leverage-btn" data-leverage="100">100x</button>
                    </div>
                    <input type="hidden" name="leverage" id="leverageInput" value="5">
                </div>

                <div class="form-group">
                    <label>Trade Duration (in minutes)</label>
                    <select name="duration" id="tradeDuration" class="form-control">
                        <option value="1">1 Minute</option>
                        <option value="5">5 Minutes</option>
                        <option value="15">15 Minutes</option>
                        <option value="30">30 Minutes</option>
                        <option value="60">1 Hour</option>
                        <option value="240">4 Hours</option>
                        <option value="1440">1 Day</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" name="amount" id="tradeAmount" class="form-control" placeholder="Enter trade amount" step="0.01" min="1" required>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Take Profit:</label>
                        <input type="number" name="take_profit" id="takeProfit" class="form-control" placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="form-group half">
                        <label>Stop Loss:</label>
                        <input type="number" name="stop_loss" id="stopLoss" class="form-control" placeholder="0.00" step="0.01" min="0">
                    </div>
                </div>

                <div class="trade-buttons">
                    <button type="button" class="trade-btn buy" onclick="executeTrade('buy')">Buy</button>
                    <button type="button" class="trade-btn sell" onclick="executeTrade('sell')">Sell</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card market-data-card">
        <div class="card-header">
            <h3 class="card-title">Cryptocurrency Market</h3>
            <div class="card-actions">
                <button class="icon-btn expand-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg></button>
                <button class="icon-btn collapse-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
            </div>
        </div>
        <div class="card-body market-widget-container">
            <div class="tradingview-widget-container">
                <div class="tradingview-widget-container__widget"></div>
            </div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-screener.js" async>
            {
                "width": "100%",
                "height": 400,
                "defaultColumn": "overview",
                "screener_type": "crypto_mkt",
                "displayCurrency": "USD",
                "colorTheme": "dark",
                "locale": "en",
                "isTransparent": true
            }
            </script>
        </div>
    </div>

    <div class="card market-data-card">
        <div class="card-header">
            <h3 class="card-title">Stock Market Data</h3>
            <div class="card-actions">
                <button class="icon-btn expand-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg></button>
                <button class="icon-btn collapse-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
            </div>
        </div>
        <div class="card-body market-widget-container">
            <div class="tradingview-widget-container">
                <div class="tradingview-widget-container__widget"></div>
            </div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-hotlists.js" async>
            {
                "colorTheme": "dark",
                "dateRange": "12M",
                "exchange": "US",
                "showChart": false,
                "locale": "en",
                "largeChartUrl": "",
                "isTransparent": true,
                "showSymbolLogo": true,
                "showFloatingTooltip": false,
                "width": "100%",
                "height": 400
            }
            </script>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Open Positions</h3>
            <a href="/positions" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($openPositions)): ?>
                <div class="empty-state">
                    <p>No open positions</p>
                    <a href="/markets" class="btn btn-primary btn-sm mt-3">Start Trading</a>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Market</th>
                            <th>Side</th>
                            <th>Amount</th>
                            <th>Entry</th>
                            <th>PnL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($openPositions, 0, 5) as $position): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($position['symbol']) ?></strong></td>
                                <td>
                                    <span class="badge <?= $position['side'] === 'buy' ? 'badge-success' : 'badge-danger' ?>">
                                        <?= strtoupper($position['side']) ?>
                                    </span>
                                </td>
                                <td><?= number_format($position['amount'], 4) ?></td>
                                <td>$<?= number_format($position['entry_price'], 2) ?></td>
                                <td class="<?= ($position['unrealized_pnl'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                                    <?= ($position['unrealized_pnl'] ?? 0) >= 0 ? '+' : '' ?>$<?= number_format($position['unrealized_pnl'] ?? 0, 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Transactions</h3>
            <a href="/wallet" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentTransactions)): ?>
                <div class="empty-state">
                    <p>No recent transactions</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentTransactions as $tx): ?>
                            <tr>
                                <td>
                                    <span class="badge <?= $tx['type'] === 'deposit' ? 'badge-success' : 'badge-warning' ?>">
                                        <?= ucfirst($tx['type']) ?>
                                    </span>
                                </td>
                                <td>$<?= number_format($tx['amount'], 2) ?></td>
                                <td>
                                    <span class="badge badge-<?= $tx['status'] === 'approved' || $tx['status'] === 'paid' ? 'success' : ($tx['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($tx['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, H:i', strtotime($tx['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://s3.tradingview.com/tv.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new TradingView.widget({
        "autosize": true,
        "symbol": "BINANCE:BTCUSDT",
        "interval": "1",
        "timezone": "Etc/UTC",
        "theme": "dark",
        "style": "1",
        "locale": "en",
        "toolbar_bg": "#1a2332",
        "enable_publishing": false,
        "allow_symbol_change": true,
        "container_id": "tradingview_chart",
        "hide_side_toolbar": false,
        "studies": ["MASimple@tv-basicstudies", "RSI@tv-basicstudies"],
        "show_popup_button": true,
        "popup_width": "1000",
        "popup_height": "650"
    });

    const assetType = document.getElementById('assetType');
    const assetName = document.getElementById('assetName');
    
    const assets = {
        crypto: [
            {value: 'BTCUSD', label: 'BTC/USD', symbol: 'BINANCE:BTCUSDT'},
            {value: 'ETHUSD', label: 'ETH/USD', symbol: 'BINANCE:ETHUSDT'},
            {value: 'XRPUSD', label: 'XRP/USD', symbol: 'BINANCE:XRPUSDT'},
            {value: 'SOLUSD', label: 'SOL/USD', symbol: 'BINANCE:SOLUSDT'},
            {value: 'BNBUSD', label: 'BNB/USD', symbol: 'BINANCE:BNBUSDT'}
        ],
        forex: [
            {value: 'EURUSD', label: 'EUR/USD', symbol: 'FX:EURUSD'},
            {value: 'GBPUSD', label: 'GBP/USD', symbol: 'FX:GBPUSD'},
            {value: 'USDJPY', label: 'USD/JPY', symbol: 'FX:USDJPY'},
            {value: 'AUDUSD', label: 'AUD/USD', symbol: 'FX:AUDUSD'}
        ],
        stocks: [
            {value: 'AAPL', label: 'Apple Inc.', symbol: 'NASDAQ:AAPL'},
            {value: 'GOOGL', label: 'Alphabet Inc.', symbol: 'NASDAQ:GOOGL'},
            {value: 'AMZN', label: 'Amazon.com', symbol: 'NASDAQ:AMZN'},
            {value: 'TSLA', label: 'Tesla Inc.', symbol: 'NASDAQ:TSLA'},
            {value: 'MSFT', label: 'Microsoft', symbol: 'NASDAQ:MSFT'}
        ],
        commodities: [
            {value: 'XAUUSD', label: 'Gold', symbol: 'TVC:GOLD'},
            {value: 'XAGUSD', label: 'Silver', symbol: 'TVC:SILVER'},
            {value: 'USOIL', label: 'Crude Oil', symbol: 'TVC:USOIL'}
        ]
    };

    assetType.addEventListener('change', function() {
        const type = this.value;
        assetName.innerHTML = '';
        assets[type].forEach(asset => {
            const option = document.createElement('option');
            option.value = asset.value;
            option.textContent = asset.label;
            option.dataset.symbol = asset.symbol;
            assetName.appendChild(option);
        });
    });

    document.querySelectorAll('.leverage-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.leverage-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('leverageInput').value = this.dataset.leverage;
        });
    });
});

function toggleCard(cardId) {
    const card = document.getElementById(cardId);
    const header = card.previousElementSibling;
    card.classList.toggle('collapsed');
    header.classList.toggle('collapsed');
}

function executeTrade(side) {
    const form = document.getElementById('tradeForm');
    const formData = new FormData(form);
    formData.append('side', side);
    
    const amount = document.getElementById('tradeAmount').value;
    if (!amount || parseFloat(amount) <= 0) {
        alert('Please enter a valid trade amount');
        return;
    }

    fetch('/api/trade', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Trade executed successfully!');
            window.location.reload();
        } else {
            alert(data.message || 'Trade failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Dashboard';
include __DIR__ . '/../layouts/main.php';
?>
