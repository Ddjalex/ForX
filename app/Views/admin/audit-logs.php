<?php
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Audit Logs</h3>
    </div>
    <div class="card-body">
        <?php if (empty($logs)): ?>
            <div class="empty-state"><p>No audit logs yet</p></div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>Entity ID</th>
                        <th>IP Address</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= $log['id'] ?></td>
                            <td><?= date('M d, H:i:s', strtotime($log['created_at'])) ?></td>
                            <td><?= htmlspecialchars($log['name'] ?? 'System') ?></td>
                            <td><span class="badge badge-primary"><?= htmlspecialchars($log['action']) ?></span></td>
                            <td><?= htmlspecialchars($log['entity']) ?></td>
                            <td><?= $log['entity_id'] ?? '-' ?></td>
                            <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                            <td>
                                <?php if ($log['details']): ?>
                                    <code style="font-size: 11px;"><?= htmlspecialchars(substr($log['details'], 0, 50)) ?>...</code>
                                <?php else: ?>
                                    -
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
$pageTitle = 'Audit Logs';
include __DIR__ . '/../layouts/admin.php';
?>
