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
        <div class="stat-label"><?= t('total_balance') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($profit, 2) ?></div>
        <div class="stat-label"><?= t('profit') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($bonus, 2) ?></div>
        <div class="stat-label"><?= t('total_bonus') ?></div>
    </div>
    <div class="stat-card <?= !$isVerified ? 'danger' : '' ?>">
        <div class="stat-value" style="font-size: 16px; <?= $isVerified ? 'color: var(--success)' : 'color: var(--danger)' ?>">
            <?= $isVerified ? t('verified') : t('not_verified') ?>
        </div>
        <div class="stat-label"><?= t('account_status') ?></div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= $totalTrades ?></div>
        <div class="stat-label"><?= t('total_trades') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $openTrades ?></div>
        <div class="stat-label"><?= t('open_trades') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $closedTrades ?></div>
        <div class="stat-label"><?= t('closed_trades') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $winLossRatio ?></div>
        <div class="stat-label"><?= t('win_loss_ratio') ?></div>
    </div>
</div>

<div class="action-buttons">
    <a href="/accounts" class="action-btn account">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        <?= t('account') ?>
    </a>
    <a href="/wallet/deposit" class="action-btn deposit">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        <?= t('make_deposit') ?> $
    </a>
    <a href="/wallet/withdraw" class="action-btn withdraw">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 19 19 12"></polyline></svg>
        <?= t('withdraw_funds') ?>
    </a>
    <a href="mailto:support@tradeflowglobalex.com" class="action-btn mail">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        <?= t('mail_us') ?>
    </a>
    <a href="/accounts?tab=settings" class="action-btn settings">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        <?= t('settings') ?>
    </a>
</div>

<div class="trading-section">
    <div class="card trading-chart-card">
        <div class="card-header collapsible" onclick="toggleCard('chartCard')">
            <h3 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                <?= t('live_trading_chart') ?>
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
                <?= t('live_trading') ?>
            </h3>
            <div class="card-actions">
                <span class="balance-display"><?= t('balance') ?>: <strong>$<?= number_format($totalBalance, 2) ?></strong></span>
                <button class="icon-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button>
                <svg class="collapse-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        <div class="card-body" id="tradingCard">
            <form method="POST" action="/dashboard/trade/order" id="tradeForm" class="trading-form">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?? '' ?>">
                <input type="hidden" name="side" id="orderSide" value="buy">
                <input type="hidden" name="order_type" id="orderType" value="market">
                
                <div class="form-group">
                    <label>Asset Type</label>
                    <select name="asset_type" id="assetType" class="form-control" onchange="updateAssetNames()">
                        <?php foreach ($assetTypes ?? [] as $type): ?>
                            <option value="<?= htmlspecialchars($type['name']) ?>" 
                                data-type="<?= htmlspecialchars($type['name']) ?>">
                                <?= htmlspecialchars($type['display_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Asset Name</label>
                    <select name="market_id" id="assetName" class="form-control" onchange="updateTradingViewChart()">
                        <?php foreach ($allMarkets ?? [] as $m): ?>
                            <option value="<?= $m['id'] ?>" 
                                data-type="<?= htmlspecialchars($m['asset_type'] ?? $m['type']) ?>"
                                data-symbol="<?= htmlspecialchars($m['symbol']) ?>"
                                data-tradingview="<?= htmlspecialchars($m['symbol_tradingview'] ?? '') ?>">
                                <?= htmlspecialchars($m['display_name'] ?? $m['symbol']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Leverage:</label>
                    <div class="leverage-grid">
                        <?php 
                        $leverages = [5, 2, 3, 4, 10, 25, 30, 40, 50, 100];
                        foreach ($leverages as $lev): 
                        ?>
                            <button type="button" class="leverage-btn <?= $lev === 5 ? 'active' : '' ?>" data-leverage="<?= $lev ?>" onclick="setLeverage(<?= $lev ?>)"><?= $lev ?>x</button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="leverage" id="leverageValue" value="5">
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
                    <input type="number" name="amount" id="tradeAmount" class="form-control" placeholder="Enter trade amount" step="0.01" min="10" required>
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
                    <button type="button" class="trade-btn buy" onclick="showConfirmModal('buy')">Buy</button>
                    <button type="button" class="trade-btn sell" onclick="showConfirmModal('sell')">Sell</button>
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
            <div class="market-list">
                <?php if (empty($cryptoMarkets)): ?>
                    <div class="text-center text-muted p-4">No cryptocurrency data available</div>
                <?php else: ?>
                    <?php foreach ($cryptoMarkets as $market): ?>
                        <a href="/trade/<?= htmlspecialchars($market['symbol']) ?>" class="market-item">
                            <div class="market-info">
                                <span class="market-symbol"><?= htmlspecialchars($market['symbol']) ?></span>
                                <span class="market-name"><?= htmlspecialchars($market['name']) ?></span>
                            </div>
                            <div class="market-price">
                                <span class="price">$<?= number_format($market['price'] ?? 0, 2) ?></span>
                                <span class="change <?= ($market['change_24h'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                                    <?= ($market['change_24h'] ?? 0) >= 0 ? '+' : '' ?><?= number_format($market['change_24h'] ?? 0, 2) ?>%
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card market-data-card">
        <div class="card-header">
            <h3 class="card-title">Forex Market Data</h3>
            <div class="card-actions">
                <button class="icon-btn expand-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg></button>
                <button class="icon-btn collapse-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
            </div>
        </div>
        <div class="card-body market-widget-container">
            <div class="market-list">
                <?php if (empty($stockMarkets)): ?>
                    <div class="text-center text-muted p-4">No forex data available</div>
                <?php else: ?>
                    <?php foreach ($stockMarkets as $market): ?>
                        <a href="/trade/<?= htmlspecialchars($market['symbol']) ?>" class="market-item">
                            <div class="market-info">
                                <span class="market-symbol"><?= htmlspecialchars($market['symbol']) ?></span>
                                <span class="market-name"><?= htmlspecialchars($market['name']) ?></span>
                            </div>
                            <div class="market-price">
                                <span class="price">$<?= number_format($market['price'] ?? 0, 4) ?></span>
                                <span class="change <?= ($market['change_24h'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                                    <?= ($market['change_24h'] ?? 0) >= 0 ? '+' : '' ?><?= number_format($market['change_24h'] ?? 0, 2) ?>%
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div id="tradeConfirmModal" class="trade-confirm-modal" style="display: none;">
    <div class="trade-confirm-overlay"></div>
    <div class="trade-confirm-content">
        <div class="trade-confirm-icon">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ff9800" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <h2 class="trade-confirm-title">Confirm <span id="confirmSideText">BUY</span> Trade</h2>
        <p class="trade-confirm-message">Are you sure you want to execute this trade?</p>
        <div class="trade-confirm-buttons">
            <button type="button" onclick="closeConfirmModal()" class="trade-confirm-btn confirm-cancel">No, Cancel</button>
            <button type="button" id="confirmTradeBtn" class="trade-confirm-btn confirm-buy">Yes, BUY Now</button>
        </div>
    </div>
</div>

<style>
.trade-confirm-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.trade-confirm-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
}
.trade-confirm-content {
    position: relative;
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 40px 50px;
    text-align: center;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}
.trade-confirm-icon {
    margin-bottom: 20px;
}
.trade-confirm-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-primary);
}
.trade-confirm-message {
    color: var(--text-secondary);
    margin-bottom: 30px;
}
.trade-confirm-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}
.trade-confirm-btn {
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.confirm-buy {
    background: var(--accent-primary);
    color: #000;
}
.confirm-buy:hover {
    background: #00e6b8;
}
.confirm-sell {
    background: #dc3545;
    color: #fff;
}
.confirm-sell:hover {
    background: #c82333;
}
.confirm-cancel {
    background: #6c757d;
    color: #fff;
}
.confirm-cancel:hover {
    background: #5a6268;
}
.market-list {
    max-height: 280px;
    overflow-y: auto;
}
.market-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid var(--border-color);
    text-decoration: none;
    transition: background 0.2s;
}
.market-item:hover {
    background: rgba(255, 255, 255, 0.05);
}
.market-item:last-child {
    border-bottom: none;
}
.market-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.market-symbol {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}
.market-name {
    font-size: 12px;
    color: var(--text-secondary);
}
.market-price {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
}
.market-price .price {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}
.market-price .change {
    font-size: 12px;
    font-weight: 500;
}
.market-price .change.positive {
    color: var(--accent-primary);
}
.market-price .change.negative {
    color: #dc3545;
}
.p-4 {
    padding: 20px;
}
</style>

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

let pendingTradeSide = null;

function executeTrade(side) {
    const amount = document.getElementById('tradeAmount').value;
    if (!amount || parseFloat(amount) <= 0) {
        alert('Please enter a valid trade amount');
        return;
    }

    pendingTradeSide = side;
    showConfirmModal(side);
}

function showConfirmModal(side) {
    const modal = document.getElementById('tradeConfirmModal');
    const sideText = document.getElementById('confirmSideText');
    const confirmBtn = document.getElementById('confirmTradeBtn');
    
    sideText.textContent = side.toUpperCase();
    confirmBtn.textContent = 'Yes, ' + side.toUpperCase() + ' Now';
    
    confirmBtn.className = 'trade-confirm-btn ' + (side === 'buy' ? 'confirm-buy' : 'confirm-sell');
    
    confirmBtn.onclick = function() {
        confirmTrade();
    };
    
    modal.style.display = 'flex';
}

function closeConfirmModal() {
    document.getElementById('tradeConfirmModal').style.display = 'none';
    pendingTradeSide = null;
}

function confirmTrade() {
    if (!pendingTradeSide) return;
    
    const form = document.getElementById('tradeForm');
    const formData = new FormData(form);
    formData.append('side', pendingTradeSide);
    
    closeConfirmModal();

    fetch('/api/trade', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/dashboard/trades/history?success=1';
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
