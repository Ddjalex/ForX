<?php
ob_start();
$totalBalance = $wallet['balance'] ?? 0;
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="trade-action-bar">
    <a href="/dashboard/trades/history" class="action-btn trade-history">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        Trade History
    </a>
    <div class="action-buttons-right">
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
</div>

<!-- Confirmation Modal -->
<div id="tradeConfirmModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeConfirmModal()"></div>
    <div class="modal-content">
        <div class="modal-icon">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                <circle cx="40" cy="40" r="36" stroke="#f5a623" stroke-width="3" fill="none"/>
                <text x="40" y="52" text-anchor="middle" fill="#f5a623" font-size="44" font-weight="bold">!</text>
            </svg>
        </div>
        <h2 id="confirmModalTitle">Confirm BUY Trade</h2>
        <p>Are you sure you want to execute this trade?</p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">No, Cancel</button>
            <button type="button" id="confirmTradeBtn" class="btn btn-buy">Yes, BUY Now</button>
        </div>
    </div>
</div>

<div class="trading-layout">
    <div class="trading-panel">
        <div class="panel-header">
            <span class="panel-title">Live Trading</span>
            <div class="balance-display">
                <span class="balance-label">Balance:</span>
                <span class="balance-value">$<?= number_format(($wallet['balance'] ?? 0) - ($wallet['margin_used'] ?? 0), 2) ?></span>
            </div>
        </div>

        <form method="POST" action="/dashboard/trade/order" id="tradeForm">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="side" id="orderSide" value="buy">
            <input type="hidden" name="order_type" id="orderType" value="market">

            <div class="form-group">
                <label class="form-label">Asset Type</label>
                <select class="form-control" name="asset_type" id="assetType" onchange="updateAssetNames()">
                    <?php foreach ($assetTypes ?? [] as $type): ?>
                        <option value="<?= htmlspecialchars($type['name']) ?>" 
                            data-type="<?= htmlspecialchars($type['name']) ?>"
                            <?= ($market['type'] ?? '') === $type['name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['display_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Asset Name</label>
                <select class="form-control" name="market_id" id="assetName" onchange="updateTradingViewChart()">
                    <?php foreach ($allMarkets ?? [] as $m): ?>
                        <option value="<?= $m['id'] ?>" 
                            data-type="<?= htmlspecialchars($m['asset_type'] ?? $m['type']) ?>"
                            data-symbol="<?= htmlspecialchars($m['symbol']) ?>"
                            data-tradingview="<?= htmlspecialchars($m['symbol_tradingview'] ?? '') ?>"
                            <?= $m['id'] == $market['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['display_name'] ?? $m['symbol']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Leverage</label>
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

            <div class="leverage-slider-container">
                <input type="range" class="leverage-slider" id="leverageSlider" min="1" max="100" value="5" oninput="updateLeverageFromSlider(this.value)">
            </div>

            <div class="form-group">
                <label class="form-label">Trade Duration (in minutes)</label>
                <select class="form-control" name="duration" id="tradeDuration">
                    <option value="1">1 Minute</option>
                    <option value="5">5 Minutes</option>
                    <option value="15">15 Minutes</option>
                    <option value="30">30 Minutes</option>
                    <option value="60">1 Hour</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Amount</label>
                <input type="number" name="amount" id="tradeAmount" class="form-control" step="0.01" min="10" placeholder="Enter trade amount" required>
            </div>

            <div class="input-row">
                <div class="form-group">
                    <label class="form-label">Take Profit :</label>
                    <input type="number" name="take_profit" id="takeProfit" class="form-control" step="0.01" value="0.00" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Stop Loss :</label>
                    <input type="number" name="stop_loss" id="stopLoss" class="form-control" step="0.01" value="0.00" placeholder="0.00">
                </div>
            </div>

            <div class="trade-buttons">
                <button type="button" class="trade-btn buy" onclick="showConfirmModal('buy')">Buy</button>
                <button type="button" class="trade-btn sell" onclick="showConfirmModal('sell')">Sell</button>
            </div>
        </form>
    </div>

    <div>
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-symbol">
                    <span>Live Trading Chart</span>
                </div>
                <div class="chart-controls">
                    <button class="chart-control-btn" onclick="changeInterval('1')">1m</button>
                    <button class="chart-control-btn active" onclick="changeInterval('30')">30m</button>
                    <button class="chart-control-btn" onclick="changeInterval('60')">1h</button>
                </div>
            </div>
            <div class="tradingview-widget-container">
                <div id="tradingview_chart"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://s3.tradingview.com/tv.js"></script>
<script>
let tvChart = null;
let currentSymbol = 'BINANCE:BTCUSDT';

document.addEventListener('DOMContentLoaded', function() {
    initTradingViewChart();
    
    const assetSelect = document.getElementById('assetName');
    if (assetSelect) {
        assetSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const tradingViewSymbol = selectedOption.getAttribute('data-tradingview');
            if (tradingViewSymbol) {
                currentSymbol = tradingViewSymbol;
                updateTradingViewChart();
            }
        });
    }
});

function initTradingViewChart() {
    // Only initialize once
    if (!tvChart) {
        createTradingViewWidget();
    }
}

function createTradingViewWidget() {
    try {
        if (typeof TradingView === 'undefined') {
            console.log('TradingView library not loaded yet');
            setTimeout(createTradingViewWidget, 500);
            return;
        }
        
        tvChart = new TradingView.widget({
            "autosize": true,
            "symbol": currentSymbol,
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
    } catch (e) {
        console.log('TradingView widget creation error:', e);
    }
}

function updateTradingViewChart() {
    // Remove old chart
    const container = document.getElementById('tradingview_chart');
    if (container) {
        container.innerHTML = '';
    }
    tvChart = null;
    
    // Create new chart with new symbol
    setTimeout(createTradingViewWidget, 100);
}

// Override the existing updateTradingViewChart from app.js
const originalUpdateChart = window.updateTradingViewChart;
window.updateTradingViewChart = function() {
    updateTradingViewChart();
};
</script>

<div class="transactions-section">
    <div class="card-header collapsible">
        <h3 class="card-title">Trades Transactions</h3>
    </div>
    
    <div class="transactions-tabs">
        <div class="transactions-tab active" onclick="showTab('open')">Open</div>
        <div class="transactions-tab" onclick="showTab('closed')">Closed</div>
    </div>

    <div id="openTrades" class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Asset</th>
                    <th>Action</th>
                    <th>Amount</th>
                    <th>Leverage</th>
                    <th>Take Profit</th>
                    <th>Stop Loss</th>
                    <th>Status</th>
                    <th>Result</th>
                    <th>Profit/Loss</th>
                    <th>Opened At</th>
                    <th>Expires At</th>
                    <th>Time Left</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($openPositions)): ?>
                    <tr><td colspan="14" class="text-center">No open trades</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($openPositions as $position): ?>
                        <tr data-position-id="<?= $position['id'] ?>">
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($position['symbol']) ?></td>
                            <td class="<?= $position['side'] === 'buy' ? 'text-success' : 'text-danger' ?>"><?= strtoupper($position['side']) ?></td>
                            <td>$<?= number_format($position['amount'], 2) ?></td>
                            <td><?= $position['leverage'] ?>x</td>
                            <td><?= number_format($position['take_profit'] ?? 0, 2) ?></td>
                            <td><?= number_format($position['stop_loss'] ?? 0, 2) ?></td>
                            <td><span class="status-badge status-open">Open</span></td>
                            <td><span class="result-badge result-pending">PENDING</span></td>
                            <td>-</td>
                            <td><?= date('Y-m-d H:i', strtotime($position['created_at'])) ?></td>
                            <td><?= $position['expires_at'] ? date('Y-m-d H:i', strtotime($position['expires_at'])) : '-' ?></td>
                            <td class="time-left" data-expires="<?= $position['expires_at'] ?? '' ?>">
                                <?php if ($position['expires_at']): ?>
                                    <span class="countdown text-success"></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm close-position-btn" data-position-id="<?= $position['id'] ?>">Close</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="closedTrades" class="card-body" style="display: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Asset</th>
                    <th>Action</th>
                    <th>Amount</th>
                    <th>Leverage</th>
                    <th>Take Profit</th>
                    <th>Stop Loss</th>
                    <th>Status</th>
                    <th>Result</th>
                    <th>Profit/Loss</th>
                    <th>Opened At</th>
                    <th>Expires At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($closedPositions)): ?>
                    <tr><td colspan="12" class="text-center">No closed trades</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($closedPositions as $position): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($position['symbol']) ?></td>
                            <td class="<?= $position['side'] === 'buy' ? 'text-success' : 'text-danger' ?>"><?= strtoupper($position['side']) ?></td>
                            <td>$<?= number_format($position['amount'], 2) ?></td>
                            <td><?= $position['leverage'] ?>x</td>
                            <td><?= number_format($position['take_profit'] ?? 0, 2) ?></td>
                            <td><?= number_format($position['stop_loss'] ?? 0, 2) ?></td>
                            <td><span class="status-badge status-closed">Closed</span></td>
                            <td>
                                <span class="result-badge <?= ($position['realized_pnl'] ?? 0) >= 0 ? 'result-win' : 'result-loss' ?>">
                                    <?= ($position['realized_pnl'] ?? 0) >= 0 ? 'WIN' : 'LOSS' ?>
                                </span>
                            </td>
                            <td class="<?= ($position['realized_pnl'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= ($position['realized_pnl'] ?? 0) >= 0 ? '+' : '' ?>$<?= number_format($position['realized_pnl'] ?? 0, 2) ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($position['created_at'])) ?></td>
                            <td><?= $position['expires_at'] ? date('Y-m-d H:i', strtotime($position['expires_at'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.modal {
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
.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(4px);
}
.modal-content {
    position: relative;
    background: linear-gradient(180deg, #1a2a3a 0%, #0d1a2a 100%);
    border-radius: 16px;
    padding: 50px 40px;
    text-align: center;
    max-width: 450px;
    width: 90%;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    animation: modalFadeIn 0.3s ease-out;
}
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
.modal-icon {
    margin-bottom: 25px;
}
.modal-icon svg {
    filter: drop-shadow(0 0 20px rgba(245, 166, 35, 0.5));
}
.modal-content h2 {
    color: #ffffff;
    margin-bottom: 12px;
    font-size: 26px;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.modal-content p {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 35px;
    font-size: 16px;
    line-height: 1.5;
}
.modal-buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}
.modal-buttons .btn {
    padding: 14px 32px;
    font-weight: 700;
    min-width: 150px;
    border-radius: 10px;
    cursor: pointer;
    border: none;
    font-size: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.2s ease;
}
.modal-buttons .btn-secondary {
    background: #3a4a5a;
    color: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}
.modal-buttons .btn-secondary:hover {
    background: #4a5a6a;
    transform: translateY(-2px);
}
.btn-buy {
    background: linear-gradient(135deg, #00D4AA 0%, #00B894 100%) !important;
    color: #000 !important;
    box-shadow: 0 4px 20px rgba(0, 212, 170, 0.4) !important;
}
.btn-buy:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(0, 212, 170, 0.5) !important;
}
.btn-sell {
    background: linear-gradient(135deg, #ff4757 0%, #dc3545 100%) !important;
    color: #fff !important;
    box-shadow: 0 4px 20px rgba(220, 53, 69, 0.4) !important;
}
.btn-sell:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(220, 53, 69, 0.5) !important;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
.status-open {
    background: var(--accent-primary);
    color: #000;
}
.status-closed {
    background: #6c757d;
    color: #fff;
}
.result-badge {
    padding: 6px 14px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.result-pending {
    background: #ffc107;
    color: #000;
}
.result-win {
    background: #1dd1a1;
    color: #fff;
    box-shadow: 0 2px 8px rgba(29, 209, 161, 0.3);
}
.result-loss {
    background: #ff6b6b;
    color: #fff;
    box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
}
.text-success {
    color: var(--accent-primary) !important;
}
.text-danger {
    color: #dc3545 !important;
}
.text-muted {
    color: var(--text-secondary);
}
.countdown {
    font-weight: 600;
}
.close-position-btn {
    background: #dc3545 !important;
    color: #fff !important;
    border: none;
    padding: 6px 16px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}
.close-position-btn:hover {
    background: #c82333 !important;
    transform: translateY(-1px);
}
</style>

<script src="https://s3.tradingview.com/tv.js"></script>
<script>
let currentTradeAction = 'buy';
let tvWidget = null;
const initialSymbol = '<?= htmlspecialchars($market['symbol_tradingview'] ?? 'BINANCE:BTCUSDT') ?>';

function updateAssetNames() {
    const assetType = document.getElementById('assetType').value;
    const assetNameSelect = document.getElementById('assetName');
    const options = assetNameSelect.querySelectorAll('option');
    
    let firstVisible = null;
    options.forEach(option => {
        const optionType = option.dataset.type;
        if (optionType === assetType) {
            option.style.display = '';
            if (!firstVisible) firstVisible = option;
        } else {
            option.style.display = 'none';
        }
    });
    
    if (firstVisible) {
        assetNameSelect.value = firstVisible.value;
    }
    
    updateTradingViewChart();
}

function updateTradingViewChart() {
    const assetNameSelect = document.getElementById('assetName');
    if (!assetNameSelect) return;
    
    const selectedOption = assetNameSelect.options[assetNameSelect.selectedIndex];
    if (!selectedOption) return;
    
    const tradingviewSymbol = selectedOption.dataset.tradingview || 'BINANCE:BTCUSDT';
    
    const hiddenInput = document.querySelector('input[name="market_id"][type="hidden"]');
    if (hiddenInput) {
        hiddenInput.value = selectedOption.value;
    }
    
    if (tvWidget && tvWidget.iframe) {
        try {
            tvWidget.iframe.contentWindow.postMessage({
                name: 'setSymbol',
                data: { symbol: tradingviewSymbol }
            }, '*');
        } catch(e) {
            initTradingViewWidget(tradingviewSymbol);
        }
    } else {
        initTradingViewWidget(tradingviewSymbol);
    }
}

function initTradingViewWidget(symbol) {
    const container = document.getElementById('tradingview_chart');
    container.innerHTML = '';
    
    tvWidget = new TradingView.widget({
        "width": "100%",
        "height": "100%",
        "symbol": symbol,
        "interval": "30",
        "timezone": "Etc/UTC",
        "theme": "dark",
        "style": "1",
        "locale": "en",
        "toolbar_bg": "#0f2744",
        "enable_publishing": false,
        "hide_side_toolbar": false,
        "allow_symbol_change": true,
        "studies": ["MACD@tv-basicstudies", "RSI@tv-basicstudies"],
        "container_id": "tradingview_chart"
    });
}

function setLeverage(value) {
    document.getElementById('leverageValue').value = value;
    document.getElementById('leverageSlider').value = value;
    document.querySelectorAll('.leverage-btn').forEach(btn => {
        btn.classList.toggle('active', parseInt(btn.dataset.leverage) === value);
    });
}

function updateLeverageFromSlider(value) {
    document.getElementById('leverageValue').value = value;
    document.querySelectorAll('.leverage-btn').forEach(btn => {
        btn.classList.toggle('active', parseInt(btn.dataset.leverage) === parseInt(value));
    });
}

function showTab(tab) {
    document.querySelectorAll('.transactions-tab').forEach(t => t.classList.remove('active'));
    document.querySelector(`.transactions-tab:${tab === 'open' ? 'first-child' : 'last-child'}`).classList.add('active');
    document.getElementById('openTrades').style.display = tab === 'open' ? 'block' : 'none';
    document.getElementById('closedTrades').style.display = tab === 'closed' ? 'block' : 'none';
}

function showConfirmModal(action) {
    currentTradeAction = action;
    const modal = document.getElementById('tradeConfirmModal');
    const title = document.getElementById('confirmModalTitle');
    const confirmBtn = document.getElementById('confirmTradeBtn');
    
    if (action === 'buy') {
        title.textContent = 'Confirm BUY Trade';
        confirmBtn.textContent = 'Yes, BUY Now';
        confirmBtn.className = 'btn btn-buy';
    } else {
        title.textContent = 'Confirm SELL Trade';
        confirmBtn.textContent = 'Yes, SELL Now';
        confirmBtn.className = 'btn btn-sell';
    }
    
    modal.style.display = 'flex';
}

function closeConfirmModal() {
    document.getElementById('tradeConfirmModal').style.display = 'none';
}

let globalSubmitting = false;
let tradeListenerAttached = false;

function attachTradeListener() {
    if (tradeListenerAttached) return;
    tradeListenerAttached = true;
    
    const confirmBtn = document.getElementById('confirmTradeBtn');
    if (!confirmBtn) return;
    
    confirmBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (globalSubmitting) return;
        globalSubmitting = true;
        
        // Reset flag after 3 seconds if something goes wrong
        setTimeout(() => {
            globalSubmitting = false;
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Yes, BUY Now';
        }, 3000);
        
        document.getElementById('orderSide').value = currentTradeAction;
        const form = document.getElementById('tradeForm');
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Processing...';
        closeConfirmModal();
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.status === 302 || response.ok) {
                clearTimeout();
                globalSubmitting = false;
                window.location.href = '/dashboard/trades/history';
            } else {
                globalSubmitting = false;
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Error - Try again';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            globalSubmitting = false;
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Error - Try again';
        });
    });
}

document.addEventListener('DOMContentLoaded', attachTradeListener);
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachTradeListener);
} else {
    attachTradeListener();
}

// Countdown timer for open trades
let expiredPositions = new Set();

function updateCountdowns() {
    document.querySelectorAll('.time-left[data-expires]').forEach(cell => {
        const expiresAt = cell.dataset.expires;
        if (!expiresAt) return;
        
        const countdown = cell.querySelector('.countdown');
        if (!countdown) return;
        
        const row = cell.closest('tr');
        const positionId = row ? row.dataset.positionId : null;
        
        const now = new Date();
        const expires = new Date(expiresAt);
        const diff = expires - now;
        
        if (diff <= 0) {
            countdown.textContent = 'Closing...';
            countdown.className = 'countdown text-warning';
            
            if (positionId && !expiredPositions.has(positionId)) {
                expiredPositions.add(positionId);
                closeExpiredPosition();
            }
        } else {
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            countdown.textContent = `${minutes}m ${seconds}s`;
            countdown.className = 'countdown text-success';
        }
    });
}

function closeExpiredPosition() {
    fetch('/api/positions/close-expired', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.closed_count > 0) {
            // Show notification
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                padding: 16px 20px;
                background: #00C853;
                color: white;
                border-radius: 8px;
                font-weight: 600;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            `;
            notification.textContent = `${data.closed_count} trade(s) closed! Loading results...`;
            document.body.appendChild(notification);
            
            // Reload page after short delay
            setTimeout(() => {
                window.location.href = window.location.pathname + '#transactions';
            }, 1500);
        }
    })
    .catch(err => console.error('Error closing expired positions:', err));
}

// Handle hash navigation to transactions section
window.addEventListener('load', function() {
    if (window.location.hash === '#transactions') {
        setTimeout(() => {
            const transactionsSection = document.querySelector('.transactions-section');
            if (transactionsSection) {
                transactionsSection.scrollIntoView({ behavior: 'smooth' });
                // Switch to Closed tab
                showTab('closed');
            }
        }, 500);
    }
});

setInterval(updateCountdowns, 1000);
setInterval(closeExpiredPosition, 5000);
updateCountdowns();

// Manual close position buttons
document.querySelectorAll('.close-position-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const positionId = this.dataset.positionId;
        if (confirm('Are you sure you want to close this position?')) {
            fetch('/api/positions/close', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'position_id=' + positionId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Position closed. PnL: $' + data.pnl.toFixed(2));
                    window.location.reload();
                } else {
                    alert(data.error || 'Failed to close position');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Error closing position');
            });
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    updateAssetNames();
    initTradingViewWidget(initialSymbol);
});
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Trading';
include __DIR__ . '/../layouts/main.php';
?>
