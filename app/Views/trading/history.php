<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success" style="display: flex; align-items: center; gap: 12px; padding: 16px 20px;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span><?= htmlspecialchars($success) ?></span>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title" style="font-size: 24px; text-align: center;">Trade History</h2>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <table class="table history-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Asset</th>
                    <th>Action</th>
                    <th>Amount</th>
                    <th>Leverage</th>
                    <th>Take Profit</th>
                    <th>Stop Loss</th>
                    <th>Status</th>
                    <th>Result</th>
                    <th>Profit/Loss</th>
                    <th>Opened At</th>
                    <th>Expires At</th>
                    <th>Time Left</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($positions)): ?>
                    <tr><td colspan="13" class="text-center">No trades yet</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($positions as $position): ?>
                        <?php 
                        $isOpen = $position['status'] === 'open';
                        $pnl = $position['realized_pnl'] ?? 0;
                        $isWin = $pnl >= 0;
                        ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($position['symbol']) ?></td>
                            <td class="<?= $position['side'] === 'buy' ? 'action-buy' : 'action-sell' ?>">
                                <?= strtoupper($position['side']) ?>
                            </td>
                            <td>$<?= number_format($position['amount'], 2) ?></td>
                            <td><?= $position['leverage'] ?>x</td>
                            <td><?= number_format($position['take_profit'] ?? 0, 2) ?></td>
                            <td><?= number_format($position['stop_loss'] ?? 0, 2) ?></td>
                            <td>
                                <span class="status-badge <?= $isOpen ? 'status-open' : 'status-closed' ?>">
                                    <?= $isOpen ? 'Open' : 'Closed' ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($isOpen): ?>
                                    <span class="result-badge result-pending">PENDING</span>
                                <?php else: ?>
                                    <span class="result-badge <?= $isWin ? 'result-win' : 'result-loss' ?>">
                                        <?= $isWin ? 'WIN' : 'LOSS' ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="<?= !$isOpen ? ($isWin ? 'pnl-positive' : 'pnl-negative') : '' ?>">
                                <?php if ($isOpen): ?>
                                    -
                                <?php else: ?>
                                    <?= $isWin ? '+' : '' ?>$<?= number_format($pnl, 2) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($position['created_at'])) ?></td>
                            <td><?= $position['expires_at'] ? date('Y-m-d H:i', strtotime($position['expires_at'])) : '-' ?></td>
                            <td class="time-left-cell" data-expires="<?= $position['expires_at'] ?? '' ?>" data-status="<?= $position['status'] ?>">
                                <?php if ($isOpen && $position['expires_at']): ?>
                                    <span class="countdown"></span>
                                <?php elseif (!$isOpen): ?>
                                    <span class="expired-text">Expired</span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.history-table {
    width: 100%;
    min-width: 1200px;
}
.history-table th {
    background: var(--bg-tertiary);
    padding: 12px 10px;
    font-weight: 600;
    text-align: left;
    white-space: nowrap;
}
.history-table td {
    padding: 12px 10px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}
.action-buy {
    color: var(--accent-primary) !important;
    font-weight: 600;
}
.action-sell {
    color: #dc3545 !important;
    font-weight: 600;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.status-open {
    background: var(--accent-primary);
    color: #000;
}
.status-closed {
    background: #6c757d;
    color: #fff;
}
.result-badge {
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.result-pending {
    background: #ffc107;
    color: #000;
}
.result-win {
    background: var(--accent-primary);
    color: #000;
}
.result-loss {
    background: #dc3545;
    color: #fff;
}
.pnl-positive {
    color: var(--accent-primary) !important;
    font-weight: 600;
}
.pnl-negative {
    color: #dc3545 !important;
    font-weight: 600;
}
.countdown {
    color: var(--accent-primary);
    font-weight: 600;
}
.expired-text {
    color: var(--text-secondary);
}
</style>

<script>
function updateCountdowns() {
    document.querySelectorAll('.time-left-cell[data-expires]').forEach(cell => {
        const expiresAt = cell.dataset.expires;
        const status = cell.dataset.status;
        
        if (!expiresAt || status !== 'open') return;
        
        const countdown = cell.querySelector('.countdown');
        if (!countdown) return;
        
        const now = new Date();
        const expires = new Date(expiresAt);
        const diff = expires - now;
        
        if (diff <= 0) {
            countdown.textContent = 'Expired';
            countdown.style.color = 'var(--text-secondary)';
        } else {
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            countdown.textContent = `${minutes}m ${seconds}s`;
            countdown.style.color = 'var(--accent-primary)';
        }
    });
}

setInterval(updateCountdowns, 1000);
updateCountdowns();
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Trade History';
include __DIR__ . '/../layouts/main.php';
?>
