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
        <h3 class="card-title">All Deposits</h3>
    </div>
    <div class="card-body">
        <?php if (empty($deposits)): ?>
            <div class="empty-state"><p>No deposits yet</p></div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>TXID</th>
                        <th>Proof</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deposits as $d): ?>
                        <tr>
                            <td><?= $d['id'] ?></td>
                            <td>
                                <?= htmlspecialchars($d['name']) ?><br>
                                <small style="color: var(--text-muted);"><?= htmlspecialchars($d['email']) ?></small>
                            </td>
                            <td><strong>$<?= number_format($d['amount'], 2) ?></strong></td>
                            <td><code style="font-size: 11px;"><?= htmlspecialchars(substr($d['txid'], 0, 20)) ?>...</code></td>
                            <td>
                                <?php if ($d['proof_image']): ?>
                                    <a href="/storage/uploads/<?= htmlspecialchars($d['proof_image']) ?>" target="_blank" class="btn btn-secondary btn-sm">View</a>
                                <?php else: ?>
                                    <span style="color: var(--text-muted);">None</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $d['status'] === 'approved' ? 'success' : ($d['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                    <?= ucfirst($d['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, H:i', strtotime($d['created_at'])) ?></td>
                            <td>
                                <?php if ($d['status'] === 'pending'): ?>
                                    <div class="flex gap-2">
                                        <form method="POST" action="/admin/deposits/approve" style="display: inline;">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                            <input type="hidden" name="deposit_id" value="<?= $d['id'] ?>">
                                            <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                        <form method="POST" action="/admin/deposits/approve" style="display: inline;">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                            <input type="hidden" name="deposit_id" value="<?= $d['id'] ?>">
                                            <input type="hidden" name="reason" value="Invalid transaction">
                                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span style="color: var(--text-muted);">Processed</span>
                                <?php endif; ?>
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
$pageTitle = 'Deposit Management';
include __DIR__ . '/../layouts/admin.php';
?>
