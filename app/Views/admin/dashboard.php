<?php
ob_start();
?>

<div class="admin-header">
    <h2>Admin Dashboard</h2>
    <div class="admin-nav">
        <a href="/admin" class="btn btn-primary btn-sm">Dashboard</a>
        <a href="/admin/users" class="btn btn-secondary btn-sm">Users</a>
        <a href="/admin/deposits" class="btn btn-secondary btn-sm">Deposits</a>
        <a href="/admin/withdrawals" class="btn btn-secondary btn-sm">Withdrawals</a>
        <a href="/admin/positions" class="btn btn-secondary btn-sm">Positions</a>
        <a href="/admin/audit-logs" class="btn btn-secondary btn-sm">Audit Logs</a>
        <a href="/admin/settings" class="btn btn-secondary btn-sm">Settings</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value"><?= number_format($stats['total_users']) ?></div>
    </div>
    <div class="stat-card clickable" onclick="window.location='/admin/deposits'">
        <div class="stat-label">Pending Deposits</div>
        <div class="stat-value" style="color: var(--warning);"><?= number_format($stats['pending_deposits']) ?></div>
    </div>
    <div class="stat-card clickable" onclick="window.location='/admin/withdrawals'">
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
            <h3 class="card-title">Pending Deposits</h3>
            <a href="/admin/deposits" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php 
            $pendingDeposits = array_filter($recentDeposits, fn($d) => $d['status'] === 'pending');
            if (empty($pendingDeposits)): ?>
                <div class="empty-state"><p>No pending deposits</p></div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($pendingDeposits, 0, 5) as $d): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($d['name']) ?></strong>
                                    <small class="text-muted d-block"><?= htmlspecialchars($d['email']) ?></small>
                                </td>
                                <td><strong style="color: var(--success)">$<?= number_format($d['amount'], 2) ?></strong></td>
                                <td><?= htmlspecialchars($d['method'] ?? 'N/A') ?></td>
                                <td><?= date('M d, H:i', strtotime($d['created_at'])) ?></td>
                                <td>
                                    <form method="POST" action="/admin/deposits/approve" class="inline-form">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                        <input type="hidden" name="deposit_id" value="<?= $d['id'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-success btn-xs">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-xs" onclick="showRejectModal('deposit', <?= $d['id'] ?>)">Reject</button>
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
            <h3 class="card-title">Pending Withdrawals</h3>
            <a href="/admin/withdrawals" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php 
            $pendingWithdrawals = array_filter($recentWithdrawals, fn($w) => $w['status'] === 'pending');
            if (empty($pendingWithdrawals)): ?>
                <div class="empty-state"><p>No pending withdrawals</p></div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Address</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($pendingWithdrawals, 0, 5) as $w): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($w['name']) ?></strong>
                                    <small class="text-muted d-block"><?= htmlspecialchars($w['email']) ?></small>
                                </td>
                                <td><strong style="color: var(--danger)">$<?= number_format($w['amount'], 2) ?></strong></td>
                                <td>
                                    <small class="wallet-address"><?= htmlspecialchars(substr($w['wallet_address'] ?? '', 0, 20)) ?>...</small>
                                </td>
                                <td><?= date('M d, H:i', strtotime($w['created_at'])) ?></td>
                                <td>
                                    <form method="POST" action="/admin/withdrawals/approve" class="inline-form">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                        <input type="hidden" name="withdrawal_id" value="<?= $w['id'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-success btn-xs">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-xs" onclick="showRejectModal('withdrawal', <?= $w['id'] ?>)">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Users</h3>
            <a href="/admin/users" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentUsers ?? [])): ?>
                <div class="empty-state"><p>No users yet</p></div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentUsers as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['name']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $u['status'] === 'active' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($u['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
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
</div>

<div id="rejectModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reject Request</h3>
            <button class="close-btn" onclick="closeRejectModal()">&times;</button>
        </div>
        <form id="rejectForm" method="POST">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="action" value="reject">
            <input type="hidden" id="reject_id_field" name="">
            <div class="form-group">
                <label>Rejection Reason</label>
                <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for rejection..." required></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

<style>
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}
.admin-nav {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.stat-card.clickable {
    cursor: pointer;
    transition: transform 0.2s;
}
.stat-card.clickable:hover {
    transform: translateY(-2px);
}
.inline-form {
    display: inline;
}
.btn-xs {
    padding: 4px 8px;
    font-size: 11px;
}
.wallet-address {
    font-family: monospace;
    font-size: 11px;
}
.text-muted {
    color: var(--text-muted);
}
.d-block {
    display: block;
}
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: var(--bg-card);
    border-radius: 12px;
    padding: 24px;
    width: 100%;
    max-width: 450px;
    border: 1px solid var(--border-color);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.modal-header h3 {
    margin: 0;
}
.close-btn {
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: 24px;
    cursor: pointer;
}
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 20px;
}
</style>

<script>
function showRejectModal(type, id) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const idField = document.getElementById('reject_id_field');
    
    if (type === 'deposit') {
        form.action = '/admin/deposits/approve';
        idField.name = 'deposit_id';
    } else {
        form.action = '/admin/withdrawals/approve';
        idField.name = 'withdrawal_id';
    }
    idField.value = id;
    modal.style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Admin Dashboard';
include __DIR__ . '/../layouts/admin.php';
?>
