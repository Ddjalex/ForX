<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="trading-layout">
    <div class="trading-panel">
        <div class="panel-header">
            <span class="panel-title">Live Trading</span>
            <div class="balance-display">
                <span class="balance-label">Balance:</span>
                <span class="balance-value">$<?= number_format(($wallet['balance'] ?? 0) - ($wallet['margin_used'] ?? 0), 2) ?></span>
            </div>
        </div>

        <form method="POST" action="/trade/order">
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
                <select class="form-control" name="duration">
                    <option value="1">1 Minute</option>
                    <option value="5">5 Minutes</option>
                    <option value="15">15 Minutes</option>
                    <option value="30">30 Minutes</option>
                    <option value="60">1 Hour</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Amount</label>
                <input type="number" name="amount" class="form-control" step="0.01" min="10" placeholder="Enter trade amount" required>
            </div>

            <div class="input-row">
                <div class="form-group">
                    <label class="form-label">Take Profit :</label>
                    <input type="number" name="take_profit" class="form-control" step="0.01" value="0.00" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Stop Loss :</label>
                    <input type="number" name="stop_loss" class="form-control" step="0.01" value="0.00" placeholder="0.00">
                </div>
            </div>

            <div class="trade-buttons">
                <button type="submit" class="trade-btn buy" onclick="document.getElementById('orderSide').value='buy'">Buy</button>
                <button type="submit" class="trade-btn sell" onclick="document.getElementById('orderSide').value='sell'">Sell</button>
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
                </tr>
            </thead>
            <tbody>
                <?php if (empty($openPositions)): ?>
                    <tr><td colspan="12" class="text-center">No open trades</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($openPositions as $position): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($position['symbol']) ?></td>
                            <td><?= ucfirst($position['side']) ?></td>
                            <td>$<?= number_format($position['amount'], 2) ?></td>
                            <td><?= $position['leverage'] ?>x</td>
                            <td><?= number_format($position['take_profit'] ?? 0, 2) ?></td>
                            <td><?= number_format($position['stop_loss'] ?? 0, 2) ?></td>
                            <td>Open</td>
                            <td><span class="badge badge-pending">PENDING</span></td>
                            <td>-</td>
                            <td><?= date('Y-m-d H:i', strtotime($position['created_at'])) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($position['expires_at'] ?? $position['created_at'])) ?></td>
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
                            <td><?= ucfirst($position['side']) ?></td>
                            <td>$<?= number_format($position['amount'], 2) ?></td>
                            <td><?= $position['leverage'] ?>x</td>
                            <td><?= number_format($position['take_profit'] ?? 0, 2) ?></td>
                            <td><?= number_format($position['stop_loss'] ?? 0, 2) ?></td>
                            <td>Closed</td>
                            <td>
                                <span class="badge <?= ($position['pnl'] ?? 0) >= 0 ? 'badge-win' : 'badge-loss' ?>">
                                    <?= ($position['pnl'] ?? 0) >= 0 ? 'WIN' : 'LOSS' ?>
                                </span>
                            </td>
                            <td class="<?= ($position['pnl'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                                <?= ($position['pnl'] ?? 0) >= 0 ? '+' : '' ?>$<?= number_format($position['pnl'] ?? 0, 2) ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($position['created_at'])) ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($position['closed_at'] ?? $position['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://s3.tradingview.com/tv.js"></script>
<script>
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
