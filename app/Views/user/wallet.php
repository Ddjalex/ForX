<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Balance</div>
        <div class="stat-value">$<?= number_format($wallet['balance'] ?? 0, 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Available Balance</div>
        <div class="stat-value">$<?= number_format(($wallet['balance'] ?? 0) - ($wallet['margin_used'] ?? 0), 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Margin Used</div>
        <div class="stat-value">$<?= number_format($wallet['margin_used'] ?? 0, 2) ?></div>
    </div>
</div>

<div class="flex gap-3 mb-4">
    <a href="/wallet/deposit" class="btn btn-primary">Deposit</a>
    <a href="/wallet/withdraw" class="btn btn-secondary">Withdraw</a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Deposit History</h3>
    </div>
    <div class="card-body">
        <?php if (empty($deposits)): ?>
            <div class="empty-state">
                <p>No deposit history</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>TXID</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deposits as $deposit): ?>
                        <tr>
                            <td><?= date('M d, Y H:i', strtotime($deposit['created_at'])) ?></td>
                            <td>$<?= number_format($deposit['amount'], 2) ?></td>
                            <td><code><?= htmlspecialchars(substr($deposit['txid'], 0, 20)) ?>...</code></td>
                            <td>
                                <span class="badge badge-<?= $deposit['status'] === 'approved' ? 'success' : ($deposit['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                    <?= ucfirst($deposit['status']) ?>
                                </span>
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
        <h3 class="card-title">Withdrawal History</h3>
    </div>
    <div class="card-body">
        <?php if (empty($withdrawals)): ?>
            <div class="empty-state">
                <p>No withdrawal history</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdrawals as $withdrawal): ?>
                        <tr>
                            <td><?= date('M d, Y H:i', strtotime($withdrawal['created_at'])) ?></td>
                            <td>$<?= number_format($withdrawal['amount'], 2) ?></td>
                            <td><code><?= htmlspecialchars(substr($withdrawal['wallet_address'], 0, 20)) ?>...</code></td>
                            <td>
                                <span class="badge badge-<?= $withdrawal['status'] === 'paid' ? 'success' : ($withdrawal['status'] === 'approved' ? 'info' : ($withdrawal['status'] === 'pending' ? 'warning' : 'danger')) ?>">
                                    <?= ucfirst($withdrawal['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Wallet';
include __DIR__ . '/../layouts/main.php';
?>
