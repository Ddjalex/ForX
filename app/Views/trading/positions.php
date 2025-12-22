<?php
/**
 * @var array $openPositions Open trade positions
 * @var array $closedPositions Closed trade positions
 * @var string $csrf_token CSRF token for forms
 */
ob_start();
?>

<div class="tabs mb-4">
    <div class="tab active" onclick="showTab('open')">Open Positions</div>
    <div class="tab" onclick="showTab('closed')">Closed Positions</div>
</div>

<div id="openPositions" class="card">
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
                        <th>Entry Price</th>
                        <th>Leverage</th>
                        <th>Margin</th>
                        <th>PnL</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($openPositions as $position): ?>
                        <tr>
                            <td>
                                <a href="/trade/<?= htmlspecialchars($position['symbol']) ?>">
                                    <strong><?= htmlspecialchars($position['symbol']) ?></strong>
                                </a>
                            </td>
                            <td>
                                <span class="badge <?= $position['side'] === 'buy' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= strtoupper($position['side']) ?>
                                </span>
                            </td>
                            <td><?= number_format($position['amount'], 4) ?></td>
                            <td>$<?= number_format($position['entry_price'], 2) ?></td>
                            <td><?= $position['leverage'] ?>x</td>
                            <td>$<?= number_format($position['margin_used'], 2) ?></td>
                            <td class="<?= ($position['unrealized_pnl'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                                <?= ($position['unrealized_pnl'] ?? 0) >= 0 ? '+' : '' ?>$<?= number_format($position['unrealized_pnl'] ?? 0, 2) ?>
                            </td>
                            <td>
                                <form method="POST" action="/trade/close" style="display: inline;">
                                    <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                    <input type="hidden" name="position_id" value="<?= $position['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Close this position?')">Close</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div id="closedPositions" class="card" style="display: none;">
    <div class="card-body">
        <?php if (empty($closedPositions)): ?>
            <div class="empty-state">
                <p>No closed positions</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Market</th>
                        <th>Side</th>
                        <th>Amount</th>
                        <th>Entry</th>
                        <th>Exit</th>
                        <th>Result</th>
                        <th>PnL</th>
                        <th>Closed At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($closedPositions as $position): 
                        $pnl = $position['realized_pnl'] ?? 0;
                        $isWin = $pnl >= 0;
                    ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($position['symbol']) ?></strong></td>
                            <td>
                                <span class="badge <?= $position['side'] === 'buy' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= strtoupper($position['side']) ?>
                                </span>
                            </td>
                            <td><?= number_format($position['amount'], 4) ?></td>
                            <td>$<?= number_format($position['entry_price'], 2) ?></td>
                            <td>$<?= number_format($position['exit_price'], 2) ?></td>
                            <td>
                                <span class="result-badge <?= $isWin ? 'result-win' : 'result-loss' ?>">
                                    <?= $isWin ? 'WIN' : 'LOSS' ?>
                                </span>
                            </td>
                            <td class="<?= $isWin ? 'positive' : 'negative' ?>">
                                <?= $isWin ? '+' : '' ?>$<?= number_format(abs($pnl), 2) ?>
                            </td>
                            <td><?= date('M d, H:i', strtotime($position['closed_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
.result-badge {
    padding: 6px 14px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.result-pending {
    background: #ffc107;
    color: #000;
}
.result-win {
    background: #1dd1a1;
    color: #fff;
    box-shadow: 0 2px 8px rgba(29, 209, 161, 0.3);
}
.result-loss {
    background: #ff6b6b;
    color: #fff;
    box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
}
</style>

<script>
function showTab(tab) {
    document.querySelectorAll('.tabs .tab').forEach((t, i) => {
        t.classList.toggle('active', (tab === 'open' && i === 0) || (tab === 'closed' && i === 1));
    });
    document.getElementById('openPositions').style.display = tab === 'open' ? 'block' : 'none';
    document.getElementById('closedPositions').style.display = tab === 'closed' ? 'block' : 'none';
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Positions';
include __DIR__ . '/../layouts/main.php';
?>
