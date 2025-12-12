<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="flex-between mb-4">
    <div>
        <h2 style="font-size: 32px; margin-bottom: 4px;"><?= htmlspecialchars($market['symbol']) ?></h2>
        <span style="color: var(--text-secondary);"><?= htmlspecialchars($market['name']) ?></span>
    </div>
    <div style="text-align: right;">
        <div style="font-size: 32px; font-weight: 700;">$<?= number_format($market['price'] ?? 0, 2) ?></div>
        <div class="<?= ($market['change_24h'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
            <?= ($market['change_24h'] ?? 0) >= 0 ? '+' : '' ?><?= number_format($market['change_24h'] ?? 0, 2) ?>%
        </div>
    </div>
</div>

<div class="stats-grid mb-4" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card">
        <div class="stat-label">24h High</div>
        <div class="stat-value positive">$<?= number_format($market['high_24h'] ?? 0, 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">24h Low</div>
        <div class="stat-value negative">$<?= number_format($market['low_24h'] ?? 0, 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">24h Volume</div>
        <div class="stat-value">$<?= number_format($market['volume_24h'] ?? 0, 0) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Max Leverage</div>
        <div class="stat-value"><?= $market['max_leverage'] ?? 100 ?>x</div>
    </div>
</div>

<div class="trading-layout">
    <div>
        <div class="chart-container mb-4">
            <canvas id="priceChart"></canvas>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Open Positions</h3>
            </div>
            <div class="card-body">
                <?php if (empty($openPositions)): ?>
                    <div class="empty-state">
                        <p>No open positions for this market</p>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Side</th>
                                <th>Amount</th>
                                <th>Entry Price</th>
                                <th>Leverage</th>
                                <th>Margin</th>
                                <th>PnL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($openPositions as $position): ?>
                                <tr>
                                    <td>
                                        <span class="badge <?= $position['side'] === 'buy' ? 'badge-success' : 'badge-danger' ?>">
                                            <?= strtoupper($position['side']) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($position['amount'], 4) ?></td>
                                    <td>$<?= number_format($position['entry_price'], 2) ?></td>
                                    <td><?= $position['leverage'] ?>x</td>
                                    <td>$<?= number_format($position['margin_used'], 2) ?></td>
                                    <td class="<?= ($position['unrealized_pnl'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                                        <?= ($position['unrealized_pnl'] ?? 0) >= 0 ? '+' : '' ?>$<?= number_format($position['unrealized_pnl'] ?? 0, 2) ?>
                                    </td>
                                    <td>
                                        <form method="POST" action="/trade/close" style="display: inline;">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                            <input type="hidden" name="position_id" value="<?= $position['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Close this position?')">Close</button>
                                        </form>
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
                <h3 class="card-title">Pending Orders</h3>
            </div>
            <div class="card-body">
                <?php if (empty($pendingOrders)): ?>
                    <div class="empty-state">
                        <p>No pending orders</p>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Side</th>
                                <th>Amount</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingOrders as $order): ?>
                                <tr>
                                    <td><span class="badge badge-info"><?= strtoupper($order['type']) ?></span></td>
                                    <td>
                                        <span class="badge <?= $order['side'] === 'buy' ? 'badge-success' : 'badge-danger' ?>">
                                            <?= strtoupper($order['side']) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($order['amount'], 4) ?></td>
                                    <td>$<?= number_format($order['price'], 2) ?></td>
                                    <td>
                                        <form method="POST" action="/trade/cancel" style="display: inline;">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                            <button type="submit" class="btn btn-secondary btn-sm">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="order-panel">
        <form method="POST" action="/trade/order">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="market_id" value="<?= $market['id'] ?>">

            <div class="order-tabs">
                <button type="button" class="order-tab active" data-side="buy" onclick="setSide('buy')">Buy / Long</button>
                <button type="button" class="order-tab" data-side="sell" onclick="setSide('sell')">Sell / Short</button>
            </div>
            <input type="hidden" name="side" id="orderSide" value="buy">

            <div class="order-type-tabs">
                <button type="button" class="order-type-tab active" onclick="setOrderType('market')">Market</button>
                <button type="button" class="order-type-tab" onclick="setOrderType('limit')">Limit</button>
            </div>
            <input type="hidden" name="order_type" id="orderType" value="market">

            <div class="form-group" id="limitPriceGroup" style="display: none;">
                <label class="form-label">Limit Price</label>
                <input type="number" name="price" class="form-control" step="0.01" placeholder="Enter limit price">
            </div>

            <div class="form-group">
                <label class="form-label">Amount</label>
                <input type="number" name="amount" class="form-control" step="0.0001" min="<?= $market['min_trade_size'] ?? 0.001 ?>" placeholder="Enter amount" required>
            </div>

            <div class="leverage-slider">
                <div class="leverage-value">
                    <span class="form-label">Leverage</span>
                    <span id="leverageDisplay">10x</span>
                </div>
                <input type="range" class="leverage-input" name="leverage" id="leverageSlider" min="1" max="<?= $market['max_leverage'] ?? 100 ?>" value="10" oninput="updateLeverage()">
            </div>

            <div class="form-group">
                <label class="form-label">Stop Loss (Optional)</label>
                <input type="number" name="stop_loss" class="form-control" step="0.01" placeholder="Stop loss price">
            </div>

            <div class="form-group">
                <label class="form-label">Take Profit (Optional)</label>
                <input type="number" name="take_profit" class="form-control" step="0.01" placeholder="Take profit price">
            </div>

            <div class="position-row">
                <span class="position-label">Available Balance</span>
                <span class="position-value">$<?= number_format(($wallet['balance'] ?? 0) - ($wallet['margin_used'] ?? 0), 2) ?></span>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-success btn-block mt-4">Open Long Position</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function setSide(side) {
    document.getElementById('orderSide').value = side;
    document.querySelectorAll('.order-tab').forEach(tab => {
        tab.classList.toggle('active', tab.dataset.side === side);
    });
    updateSubmitButton();
}

function setOrderType(type) {
    document.getElementById('orderType').value = type;
    document.querySelectorAll('.order-type-tab').forEach(tab => {
        tab.classList.toggle('active', tab.textContent.toLowerCase() === type);
    });
    document.getElementById('limitPriceGroup').style.display = type === 'limit' ? 'block' : 'none';
}

function updateLeverage() {
    const value = document.getElementById('leverageSlider').value;
    document.getElementById('leverageDisplay').textContent = value + 'x';
}

function updateSubmitButton() {
    const side = document.getElementById('orderSide').value;
    const btn = document.getElementById('submitBtn');
    if (side === 'buy') {
        btn.textContent = 'Open Long Position';
        btn.className = 'btn btn-success btn-block mt-4';
    } else {
        btn.textContent = 'Open Short Position';
        btn.className = 'btn btn-danger btn-block mt-4';
    }
}

const priceHistory = <?= json_encode($priceHistory) ?>;
const ctx = document.getElementById('priceChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: priceHistory.map(p => new Date(p.created_at).toLocaleTimeString()),
        datasets: [{
            label: 'Price',
            data: priceHistory.map(p => p.price),
            borderColor: '#FFCF00',
            backgroundColor: 'rgba(255, 207, 0, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: { display: false },
            y: { 
                grid: { color: 'rgba(255,255,255,0.1)' },
                ticks: { color: '#666' }
            }
        }
    }
});
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Trade ' . $market['symbol'];
include __DIR__ . '/../layouts/main.php';
?>
