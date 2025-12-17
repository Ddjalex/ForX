<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- Confirmation Modal -->
<div id="tradeConfirmModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeConfirmModal()"></div>
    <div class="modal-content">
        <div class="modal-icon">
            <svg width="60" height="60" viewBox="0 0 60 60" fill="none">
                <circle cx="30" cy="30" r="28" stroke="#f5a623" stroke-width="2" fill="none"/>
                <text x="30" y="38" text-anchor="middle" fill="#f5a623" font-size="32" font-weight="bold">!</text>
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

        <form method="POST" action="/trade/order" id="tradeForm">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="market_id" value="<?= $market['id'] ?>">
            <input type="hidden" name="side" id="orderSide" value="buy">
            <input type="hidden" name="order_type" id="orderType" value="market">

            <div class="form-group">
                <label class="form-label">Asset Type</label>
                <select class="form-control" name="asset_type" id="assetType">
                    <option value="crypto">Crypto</option>
                    <option value="forex">Forex</option>
                    <option value="stocks">Stocks</option>
                    <option value="indices">Indices</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Asset Name</label>
                <select class="form-control" name="market_id" id="assetName">
                    <?php foreach ($allMarkets ?? [] as $m): ?>
                        <option value="<?= $m['id'] ?>" <?= $m['id'] == $market['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['symbol']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Leverage</label>
                <div class="leverage-grid">
                    <?php 
                    $leverages = [5, 2, 3, 4, 5, 10, 25, 30, 40, 50, 60, 70, 80, 90, 100];
                    foreach (array_slice($leverages, 0, 10) as $lev): 
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
            <div class="tradingview-widget-container" id="tradingview_chart"></div>
        </div>
    </div>
</div>

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
                    <th>Asset Name</th>
                    <th>Type</th>
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
                </tr>
            </thead>
            <tbody>
                <?php if (empty($openPositions)): ?>
                    <tr><td colspan="13" class="text-center">No open trades</td></tr>
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
                    <th>Asset Name</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Leverage</th>
                    <th>Take Profit</th>
                    <th>Stop Loss</th>
                    <th>Status</th>
                    <th>Result</th>
                    <th>Profit/Loss</th>
                    <th>Opened At</th>
                    <th>Closed At</th>
                    <th>Time Left</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($closedPositions)): ?>
                    <tr><td colspan="13" class="text-center">No closed trades</td></tr>
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
                            <td><?= date('Y-m-d H:i', strtotime($position['closed_at'] ?? $position['created_at'])) ?></td>
                            <td><span class="text-muted">Expired</span></td>
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
    z-index: 1000;
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
    background: rgba(0, 0, 0, 0.7);
}
.modal-content {
    position: relative;
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}
.modal-icon {
    margin-bottom: 20px;
}
.modal-content h2 {
    color: var(--text-primary);
    margin-bottom: 10px;
}
.modal-content p {
    color: var(--text-secondary);
    margin-bottom: 30px;
}
.modal-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}
.modal-buttons .btn {
    padding: 12px 24px;
    font-weight: 600;
    min-width: 130px;
    border-radius: 8px;
    cursor: pointer;
    border: none;
    font-size: 14px;
}
.modal-buttons .btn-secondary {
    background: #4a5568;
    color: #fff;
}
.modal-buttons .btn-secondary:hover {
    background: #5a6578;
}
.btn-buy {
    background: var(--primary, #00D4AA) !important;
    color: #000 !important;
}
.btn-buy:hover {
    background: var(--primary-dark, #00B894) !important;
}
.btn-sell {
    background: #dc3545 !important;
    color: #fff !important;
}
.btn-sell:hover {
    background: #c82333 !important;
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
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
.result-pending {
    background: #ffc107;
    color: #000;
}
.result-win {
    background: var(--accent-primary);
    color: #000;
}
.result-loss {
    background: #dc3545;
    color: #fff;
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
</style>

<script src="https://s3.tradingview.com/tv.js"></script>
<script>
let currentTradeAction = 'buy';

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

document.getElementById('confirmTradeBtn').addEventListener('click', function() {
    document.getElementById('orderSide').value = currentTradeAction;
    document.getElementById('tradeForm').submit();
});

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
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    })
    .catch(err => console.error('Error closing expired positions:', err));
}

setInterval(updateCountdowns, 1000);
setInterval(closeExpiredPosition, 5000);
updateCountdowns();

new TradingView.widget({
    "width": "100%",
    "height": "100%",
    "symbol": "NASDAQ:AMZN",
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
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Trading';
include __DIR__ . '/../layouts/main.php';
?>
