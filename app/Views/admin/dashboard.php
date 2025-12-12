<?php
ob_start();
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value"><?= number_format($stats['total_users']) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending Deposits</div>
        <div class="stat-value" style="color: var(--warning);"><?= number_format($stats['pending_deposits']) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending Withdrawals</div>
        <div class="stat-value" style="color: var(--warning);"><?= number_format($stats['pending_withdrawals']) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total User Balance</div>
        <div class="stat-value">$<?= number_format($stats['total_balance'], 2) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Open Positions</div>
        <div class="stat-value"><?= number_format($stats['open_positions']) ?></div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Deposits</h3>
            <a href="/admin/deposits" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentDeposits)): ?>
                <div class="empty-state"><p>No recent deposits</p></div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentDeposits as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['name']) ?></td>
                                <td>$<?= number_format($d['amount'], 2) ?></td>
                                <td>
                                    <span class="badge badge-<?= $d['status'] === 'approved' ? 'success' : ($d['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($d['status']) ?>
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
            <h3 class="card-title">Recent Withdrawals</h3>
            <a href="/admin/withdrawals" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentWithdrawals)): ?>
                <div class="empty-state"><p>No recent withdrawals</p></div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentWithdrawals as $w): ?>
                            <tr>
                                <td><?= htmlspecialchars($w['name']) ?></td>
                                <td>$<?= number_format($w['amount'], 2) ?></td>
                                <td>
                                    <span class="badge badge-<?= $w['status'] === 'paid' ? 'success' : ($w['status'] === 'approved' ? 'info' : ($w['status'] === 'pending' ? 'warning' : 'danger')) ?>">
                                        <?= ucfirst($w['status']) ?>
                                    </span>
                                </td>
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
        <h3 class="card-title">Recent Activity</h3>
        <a href="/admin/audit-logs" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div class="card-body">
        <?php if (empty($recentLogs)): ?>
            <div class="empty-state"><p>No recent activity</p></div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Entity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td><?= date('M d, H:i', strtotime($log['created_at'])) ?></td>
                            <td><?= htmlspecialchars($log['name'] ?? 'System') ?></td>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($log['action']) ?></span></td>
                            <td><?= htmlspecialchars($log['entity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Admin Dashboard';
include __DIR__ . '/../layouts/admin.php';
?>
