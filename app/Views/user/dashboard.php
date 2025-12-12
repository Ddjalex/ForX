<?php
ob_start();
?>

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
    <div class="stat-card">
        <div class="stat-label">Unrealized PnL</div>
        <div class="stat-value <?= $totalPnL >= 0 ? 'positive' : 'negative' ?>">
            <?= $totalPnL >= 0 ? '+' : '' ?>$<?= number_format($totalPnL, 2) ?>
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

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quick Actions</h3>
    </div>
    <div class="card-body">
        <div class="flex gap-3">
            <a href="/wallet/deposit" class="btn btn-primary">Deposit Funds</a>
            <a href="/wallet/withdraw" class="btn btn-secondary">Withdraw</a>
            <a href="/markets" class="btn btn-secondary">Trade Now</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Dashboard';
include __DIR__ . '/../layouts/main.php';
?>
