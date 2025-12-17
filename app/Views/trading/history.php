<?php
ob_start();
$openPositions = array_filter($positions, fn($p) => $p['status'] === 'open');
$closedPositions = array_filter($positions, fn($p) => $p['status'] === 'closed');
$showSuccessModal = isset($_GET['success']) && $_GET['success'] == '1';
if (empty($success) && $showSuccessModal) {
    $success = "Trade executed successfully. Your trade has been placed successfully. You can review the details in your trade history.";
}
?>

<div class="trade-action-bar">
    <a href="/dashboard/trade" class="action-btn trade-now">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22,7 13.5,15.5 8.5,10.5 2,17"></polyline><polyline points="16,7 22,7 22,13"></polyline></svg>
        Trade Now
    </a>
    <div class="action-buttons-right">
        <a href="/accounts" class="action-btn account">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Account
        </a>
        <a href="/wallet/deposit" class="action-btn deposit">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            Make Deposit $
        </a>
        <a href="/wallet/withdraw" class="action-btn withdraw">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 19 19 12"></polyline></svg>
            Withdraw Funds
        </a>
        <a href="mailto:support@tradeflowglobalex.com" class="action-btn mail">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            Mail Us
        </a>
        <a href="/accounts?tab=settings" class="action-btn settings">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            Settings
        </a>
    </div>
</div>

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
        <h2 class="card-title" style="font-size: 24px; text-align: center; width: 100%; margin-bottom: 20px;">Trade History</h2>
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
                            <td class="time-left-cell" data-expires-timestamp="<?= $position['expires_at'] ? strtotime($position['expires_at']) * 1000 : '' ?>" data-status="open">
                                <?php if ($position['expires_at']): ?>
                                    <span class="countdown"></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                                <button type="button" class="btn btn-danger btn-sm close-position-btn" data-position-id="<?= $position['id'] ?>">Close</button>
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
                <?php if (empty($closedPositions)): ?>
                    <tr><td colspan="13" class="text-center">No closed trades found.</td></tr>
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
                            <td><span class="expired-text">Expired</span></td>
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
    border-collapse: collapse;
}
.history-table th {
    background: #2d3a4f;
    padding: 12px 10px;
    font-weight: 600;
    text-align: left;
    white-space: nowrap;
    color: #8899a6;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #3d4a5f;
}
.history-table tbody tr {
    background: #1a2332;
}
.history-table tbody tr:nth-child(even) {
    background: #1e2a3a;
}
.history-table tbody tr:hover {
    background: #243447;
}
.history-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #2d3a4f;
    white-space: nowrap;
    color: #e0e0e0;
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
    color: #ff6b6b;
    font-weight: 600;
    margin-right: 10px;
    font-size: 13px;
}
.countdown.active {
    color: #ff6b6b;
}
.expired-text {
    color: #8899a6;
    font-style: italic;
}
.close-position-btn {
    background: #dc3545 !important;
    color: #fff !important;
    border: none;
    padding: 5px 12px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.2s;
}
.close-position-btn:hover {
    background: #c82333 !important;
    transform: translateY(-1px);
}
.time-left-cell {
    display: flex;
    align-items: center;
    gap: 8px;
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
    document.querySelectorAll('.time-left-cell[data-expires-timestamp]').forEach(cell => {
        const expiresTimestamp = parseInt(cell.dataset.expiresTimestamp);
        const status = cell.dataset.status;
        
        if (!expiresTimestamp || status !== 'open') return;
        
        const countdown = cell.querySelector('.countdown');
        if (!countdown) return;
        
        const now = Date.now();
        const diff = expiresTimestamp - now;
        
        if (diff <= 0) {
            countdown.textContent = 'Closing...';
            countdown.style.color = '#ffc107';
            countdown.classList.remove('active');
            if (!hasExpired) {
                hasExpired = true;
                closeExpiredPositions();
            }
        } else {
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            countdown.textContent = `${minutes}m ${seconds.toString().padStart(2, '0')}s`;
            countdown.style.color = '#ff6b6b';
            countdown.classList.add('active');
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

// Manual close position buttons
document.querySelectorAll('.close-position-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const positionId = this.dataset.positionId;
        if (confirm('Are you sure you want to close this position?')) {
            fetch('/api/positions/close', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'position_id=' + positionId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Position closed. PnL: $' + data.pnl.toFixed(2));
                    window.location.reload();
                } else {
                    alert(data.error || 'Failed to close position');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Error closing position');
            });
        }
    });
});
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Trade History';
include __DIR__ . '/../layouts/main.php';
?>
