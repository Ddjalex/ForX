<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Positions</h3>
    </div>
    <div class="card-body">
        <?php if (empty($positions)): ?>
            <div class="empty-state"><p>No positions yet</p></div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Market</th>
                        <th>Side</th>
                        <th>Amount</th>
                        <th>Entry</th>
                        <th>Leverage</th>
                        <th>Margin</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($positions as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td>
                                <?= htmlspecialchars($p['name']) ?><br>
                                <small style="color: var(--text-muted);"><?= htmlspecialchars($p['email']) ?></small>
                            </td>
                            <td><strong><?= htmlspecialchars($p['symbol']) ?></strong></td>
                            <td>
                                <span class="badge <?= $p['side'] === 'buy' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= strtoupper($p['side']) ?>
                                </span>
                            </td>
                            <td><?= number_format($p['amount'], 4) ?></td>
                            <td>$<?= number_format($p['entry_price'], 2) ?></td>
                            <td><?= $p['leverage'] ?>x</td>
                            <td>$<?= number_format($p['margin_used'], 2) ?></td>
                            <td>
                                <span class="badge badge-<?= $p['status'] === 'open' ? 'success' : 'info' ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, H:i', strtotime($p['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
