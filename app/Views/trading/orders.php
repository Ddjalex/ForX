<?php
ob_start();
?>

<div class="tabs mb-4">
    <div class="tab active" onclick="showTab('pending')">Pending Orders</div>
    <div class="tab" onclick="showTab('history')">Order History</div>
</div>

<div id="pendingOrders" class="card">
    <div class="card-body">
        <?php if (empty($pendingOrders)): ?>
            <div class="empty-state">
                <p>No pending orders</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Market</th>
                        <th>Type</th>
                        <th>Side</th>
                        <th>Amount</th>
                        <th>Price</th>
                        <th>Leverage</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingOrders as $order): ?>
                        <tr>
                            <td>
                                <a href="/trade/<?= htmlspecialchars($order['symbol']) ?>">
                                    <strong><?= htmlspecialchars($order['symbol']) ?></strong>
                                </a>
                            </td>
                            <td><span class="badge badge-info"><?= strtoupper($order['type']) ?></span></td>
                            <td>
                                <span class="badge <?= $order['side'] === 'buy' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= strtoupper($order['side']) ?>
                                </span>
                            </td>
                            <td><?= number_format($order['amount'], 4) ?></td>
                            <td>$<?= number_format($order['price'], 2) ?></td>
                            <td><?= $order['leverage'] ?>x</td>
                            <td><?= date('M d, H:i', strtotime($order['created_at'])) ?></td>
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

<div id="orderHistory" class="card" style="display: none;">
    <div class="card-body">
        <?php if (empty($orderHistory)): ?>
            <div class="empty-state">
                <p>No order history</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Market</th>
                        <th>Type</th>
                        <th>Side</th>
                        <th>Amount</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderHistory as $order): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($order['symbol']) ?></strong></td>
                            <td><span class="badge badge-info"><?= strtoupper($order['type']) ?></span></td>
                            <td>
                                <span class="badge <?= $order['side'] === 'buy' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= strtoupper($order['side']) ?>
                                </span>
                            </td>
                            <td><?= number_format($order['amount'], 4) ?></td>
                            <td>$<?= number_format($order['price'], 2) ?></td>
                            <td>
                                <span class="badge badge-<?= $order['status'] === 'filled' ? 'success' : ($order['status'] === 'cancelled' ? 'warning' : 'danger') ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, H:i', strtotime($order['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tabs .tab').forEach((t, i) => {
        t.classList.toggle('active', (tab === 'pending' && i === 0) || (tab === 'history' && i === 1));
    });
    document.getElementById('pendingOrders').style.display = tab === 'pending' ? 'block' : 'none';
    document.getElementById('orderHistory').style.display = tab === 'history' ? 'block' : 'none';
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Orders';
include __DIR__ . '/../layouts/main.php';
?>
