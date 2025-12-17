<?php
ob_start();
$openPositions = array_filter($positions, fn($p) => $p['status'] === 'open');
$closedPositions = array_filter($positions, fn($p) => $p['status'] === 'closed');
$showSuccessModal = isset($_GET['success']) && $_GET['success'] == '1';
if (empty($success) && $showSuccessModal) {
    $success = "Trade executed successfully. Your trade has been placed successfully. You can review the details in your trade history.";
}
?>

<?php if (!empty($success)): ?>
    <div id="successModal" class="success-modal">
        <div class="success-modal-overlay" onclick="closeSuccessModal()"></div>
        <div class="success-modal-content">
            <p><?= htmlspecialchars($success) ?></p>
            <button type="button" onclick="closeSuccessModal()" class="success-modal-btn">Close</button>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="flex-direction: column; align-items: flex-start;">
        <h2 class="card-title" style="font-size: 24px; text-align: center; width: 100%; margin-bottom: 20px;">Trades Transactions</h2>
        <div class="trade-tabs">
            <button type="button" class="trade-tab active" id="openTab" onclick="showTab('open')">Open</button>
            <button type="button" class="trade-tab" id="closedTab" onclick="showTab('closed')">Closed</button>
        </div>
    </div>
    <div class="card-body" style="overflow-x: auto;">
        <table class="table history-table" id="openTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Asset Name</th>
                    <th>Type</th>
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
                <?php if (empty($openPositions)): ?>
                    <tr><td colspan="13" class="text-center">No trades found.</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($openPositions as $position): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($position['symbol']) ?></td>
                            <td class="<?= $position['side'] === 'buy' ? 'action-buy' : 'action-sell' ?>">
                                <?= ucfirst($position['side']) ?>
                            </td>
                            <td>$<?= number_format($position['amount'], 2) ?></td>
                            <td><?= $position['leverage'] ?>x</td>
                            <td><?= number_format($position['take_profit'] ?? 0, 2) ?></td>
                            <td><?= number_format($position['stop_loss'] ?? 0, 2) ?></td>
                            <td><span class="status-badge status-open">Open</span></td>
                            <td><span class="result-badge result-pending">PENDING</span></td>
                            <td>-</td>
                            <td><?= date('Y-m-d H:i', strtotime($position['created_at'])) ?></td>
                            <td><?= $position['expires_at'] ? date('Y-m-d H:i', strtotime($position['expires_at'])) : '-' ?></td>
                            <td class="time-left-cell" data-expires="<?= $position['expires_at'] ?? '' ?>" data-status="open">
                                <?php if ($position['expires_at']): ?>
                                    <span class="countdown"></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <table class="table history-table" id="closedTable" style="display: none;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Asset Name</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Leverage</th>
                    <th>Take Profit</th>
                    <th>Stop Loss</th>
                    <th>Status</th>
                    <th>Result</th>
                    <th>Profit/Loss</th>
                    <th>Opened At</th>
                    <th>Expires At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($closedPositions)): ?>
                    <tr><td colspan="12" class="text-center">No closed trades found.</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($closedPositions as $position): 
                        $pnl = $position['realized_pnl'] ?? 0;
                        $isWin = $pnl >= 0;
                    ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($position['symbol']) ?></td>
                            <td class="<?= $position['side'] === 'buy' ? 'action-buy' : 'action-sell' ?>">
                                <?= ucfirst($position['side']) ?>
                            </td>
                            <td>$<?= number_format($position['amount'], 2) ?></td>
                            <td><?= $position['leverage'] ?>x</td>
                            <td><?= number_format($position['take_profit'] ?? 0, 2) ?></td>
                            <td><?= number_format($position['stop_loss'] ?? 0, 2) ?></td>
                            <td><span class="status-badge status-closed">Closed</span></td>
                            <td>
                                <span class="result-badge <?= $isWin ? 'result-win' : 'result-loss' ?>">
                                    <?= $isWin ? 'WIN' : 'LOSS' ?>
                                </span>
                            </td>
                            <td class="<?= $isWin ? 'pnl-positive' : 'pnl-negative' ?>">
                                <?= $isWin ? '+' : '' ?>$<?= number_format($pnl, 2) ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($position['created_at'])) ?></td>
                            <td><?= $position['expires_at'] ? date('Y-m-d H:i', strtotime($position['expires_at'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.trade-tabs {
    display: flex;
    gap: 0;
}
.trade-tab {
    padding: 10px 30px;
    border: none;
    background: transparent;
    color: var(--text-secondary);
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}
.trade-tab.active {
    background: var(--accent-primary);
    color: #000;
    border-radius: 4px;
}
.trade-tab:hover:not(.active) {
    color: var(--text-primary);
}
.success-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 100px;
}
.success-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}
.success-modal-content {
    position: relative;
    background: #3b82f6;
    border-radius: 8px;
    padding: 20px 30px;
    max-width: 500px;
    width: 90%;
    color: #fff;
}
.success-modal-content p {
    margin-bottom: 15px;
    font-size: 14px;
}
.success-modal-btn {
    background: #2563eb;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
}
.success-modal-btn:hover {
    background: #1d4ed8;
}
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
function showTab(tab) {
    const openTable = document.getElementById('openTable');
    const closedTable = document.getElementById('closedTable');
    const openTab = document.getElementById('openTab');
    const closedTab = document.getElementById('closedTab');
    
    if (tab === 'open') {
        openTable.style.display = 'table';
        closedTable.style.display = 'none';
        openTab.classList.add('active');
        closedTab.classList.remove('active');
    } else {
        openTable.style.display = 'none';
        closedTable.style.display = 'table';
        openTab.classList.remove('active');
        closedTab.classList.add('active');
    }
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) modal.style.display = 'none';
}

let hasExpired = false;

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
            countdown.textContent = 'Closing...';
            countdown.style.color = '#ffc107';
            if (!hasExpired) {
                hasExpired = true;
                closeExpiredPositions();
            }
        } else {
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            countdown.textContent = `${minutes}m ${seconds}s`;
            countdown.style.color = 'var(--accent-primary)';
        }
    });
}

function closeExpiredPositions() {
    fetch('/api/positions/close-expired', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.closed_count > 0) {
            setTimeout(() => window.location.reload(), 1500);
        }
    })
    .catch(err => console.error('Error closing positions:', err));
}

setInterval(updateCountdowns, 1000);
setInterval(closeExpiredPositions, 5000);
updateCountdowns();
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Trade History';
include __DIR__ . '/../layouts/main.php';
?>
