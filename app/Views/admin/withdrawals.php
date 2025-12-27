<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Withdrawals</h3>
    </div>
    <div class="card-body">
        <?php if (empty($withdrawals)): ?>
            <div class="empty-state"><p>No withdrawals yet</p></div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdrawals as $w): ?>
                        <tr>
                            <td><?= $w['id'] ?></td>
                            <td>
                                <?= htmlspecialchars($w['name']) ?><br>
                                <small style="color: var(--text-muted);"><?= htmlspecialchars($w['email']) ?></small>
                            </td>
                            <td><strong>$<?= number_format($w['amount'], 2) ?></strong></td>
                            <td><code style="font-size: 11px;"><?= htmlspecialchars($w['wallet_address']) ?></code></td>
                            <td>
                                <span class="badge badge-<?= $w['status'] === 'paid' ? 'success' : ($w['status'] === 'approved' ? 'info' : ($w['status'] === 'pending' ? 'warning' : 'danger')) ?>">
                                    <?= ucfirst($w['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, H:i', strtotime($w['created_at'])) ?></td>
                            <td>
                                <?php if ($w['status'] === 'pending'): ?>
                                    <div class="flex gap-2">
                                        <form method="POST" action="/admin/withdrawals/approve" style="display: inline;">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                            <input type="hidden" name="withdrawal_id" value="<?= $w['id'] ?>">
                                            <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                        <form method="POST" action="/admin/withdrawals/approve" style="display: inline;">
                                            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                            <input type="hidden" name="withdrawal_id" value="<?= $w['id'] ?>">
                                            <input type="hidden" name="reason" value="Declined">
                                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    </div>
                                <?php elseif ($w['status'] === 'approved'): ?>
                                    <form method="POST" action="/admin/withdrawals/approve">
                                        <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                        <input type="hidden" name="withdrawal_id" value="<?= $w['id'] ?>">
                                        <button type="submit" name="action" value="paid" class="btn btn-primary btn-sm">Mark Paid</button>
                                    </form>
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
