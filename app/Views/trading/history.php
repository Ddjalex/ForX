<?php
/**
 * @var array $positions Trade positions data
 * @var string $csrf_token CSRF token for forms
 * @var string $success Success message
 * @var string $error Error message
 */
ob_start();
$allPositions = $positions ?? [];
usort($allPositions, fn($a, $b) => strtotime($b['created_at']) - strtotime($a['created_at']));
$showSuccessModal = isset($_GET['success']) && $_GET['success'] == '1';
if (empty($success) && $showSuccessModal) {
    $success = "Trade executed successfully. Your trade has been placed successfully. You can review the details in your trade history.";
}
$error = $error ?? '';
$success = $success ?? '';
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
    <div class="card-header">
        <h2 class="card-title" style="font-size: 24px; text-align: center; width: 100;">Trade History</h2>
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
                <?php if (empty($allPositions)): ?>
                    <tr><td colspan="13" class="text-center">No trades found.</td></tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($allPositions as $position): 
                        $isOpen = $position['status'] === 'open';
                        $pnl = $isOpen ? ($position['unrealized_pnl'] ?? 0) : ($position['realized_pnl'] ?? 0);
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
                            <td class="<?= $isWin ? 'pnl-positive' : 'pnl-negative' ?>">
                                <?php if ($isOpen): ?>
                                    <?= $pnl >= 0 ? '+' : '' ?>$<?= number_format($pnl, 2) ?>
                                <?php else: ?>
                                    <?= $isWin ? '+' : '' ?>$<?= number_format(abs($pnl), 2) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= date('Y-m-d H:i', strtotime($position['created_at'])) ?></td>
                            <td><?= $position['expires_at'] ? date('Y-m-d H:i', strtotime($position['expires_at'])) : '-' ?></td>
                            <td class="time-left-cell" data-expires-timestamp="<?= $position['expires_at'] ? strtotime($position['expires_at']) * 1000 : '' ?>" data-status="<?= $position['status'] ?>">
                                <?php if (!$isOpen): ?>
                                    <span class="expired-text">Expired</span>
                                <?php elseif ($position['expires_at']): ?>
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
    </div>
</div>

<style>
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
    font-size: 13px;
}
.countdown.active {
    color: #ff6b6b;
}
.expired-text {
    color: #8899a6;
    font-style: italic;
}
.time-left-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}
</style>

<script>
function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) modal.style.display = 'none';
}

let hasExpired = false;

function updateCountdowns() {
    let anyExpired = false;
    
    document.querySelectorAll('.time-left-cell[data-expires-timestamp]').forEach(cell => {
        const expiresTimestamp = parseInt(cell.dataset.expiresTimestamp);
        const status = cell.dataset.status;
        
        if (!expiresTimestamp || status !== 'open') return;
        
        const countdown = cell.querySelector('.countdown');
        if (!countdown) return;
        
        const now = Date.now();
        const diff = expiresTimestamp - now;
        
        if (diff <= 0) {
            countdown.textContent = 'Expired';
            countdown.style.color = '#8899a6';
            countdown.classList.remove('active');
            anyExpired = true;
        } else {
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            countdown.textContent = `${minutes}m ${seconds.toString().padStart(2, '0')}s`;
            countdown.style.color = '#ff6b6b';
            countdown.classList.add('active');
        }
    });
    
    if (anyExpired && !hasExpired) {
        hasExpired = true;
        closeExpiredAndRedirect();
    }
}

function closeExpiredAndRedirect() {
    fetch('/api/positions/close-expired', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        setTimeout(() => {
            window.location.href = '/dashboard/trade';
        }, 1500);
    })
    .catch(err => {
        console.error('Error closing positions:', err);
        setTimeout(() => {
            window.location.href = '/dashboard/trade';
        }, 1500);
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
