<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Users</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Balance</th>
                    <th>Margin</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>$<?= number_format($u['balance'] ?? 0, 2) ?></td>
                        <td>$<?= number_format($u['margin_used'] ?? 0, 2) ?></td>
                        <td>
                            <span class="badge <?= $u['role'] === 'admin' || $u['role'] === 'superadmin' ? 'badge-primary' : 'badge-info' ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?= $u['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= ucfirst($u['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <form method="POST" action="/admin/users/update" style="display: inline;">
                                    <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <?php if ($u['status'] === 'active'): ?>
                                        <button type="submit" name="action" value="suspend" class="btn btn-danger btn-sm">Suspend</button>
                                    <?php else: ?>
                                        <button type="submit" name="action" value="activate" class="btn btn-success btn-sm">Activate</button>
                                    <?php endif; ?>
                                </form>
                                <button class="btn btn-secondary btn-sm" onclick="showBalanceModal(<?= $u['id'] ?>, <?= $u['balance'] ?? 0 ?>)">Edit Balance</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal-overlay" id="balanceModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit User Balance</h3>
            <button class="modal-close" onclick="hideBalanceModal()">&times;</button>
        </div>
        <form method="POST" action="/admin/users/balance">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="user_id" id="balanceUserId">
            
            <div class="form-group">
                <label class="form-label">New Balance ($)</label>
                <input type="number" name="balance" id="balanceInput" class="form-control" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Reason (for audit)</label>
                <input type="text" name="reason" class="form-control" placeholder="Reason for balance change" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Update Balance</button>
        </form>
    </div>
</div>

<script>
function showBalanceModal(userId, currentBalance) {
    document.getElementById('balanceUserId').value = userId;
    document.getElementById('balanceInput').value = currentBalance;
    document.getElementById('balanceModal').classList.add('active');
}

function hideBalanceModal() {
    document.getElementById('balanceModal').classList.remove('active');
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'User Management';
include __DIR__ . '/../layouts/admin.php';
?>
