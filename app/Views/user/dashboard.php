<?php
ob_start();

$totalBalance = $wallet['balance'] ?? 0;
$profit = $wallet['profit'] ?? 0;
$bonus = $wallet['bonus'] ?? 0;
$isVerified = $user['email_verified'] ?? false;
$totalTrades = $stats['total_trades'] ?? 0;
$openTrades = $stats['open_trades'] ?? 0;
$closedTrades = $stats['closed_trades'] ?? 0;
$winLossRatio = $stats['win_loss_ratio'] ?? 0;
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($totalBalance, 2) ?></div>
        <div class="stat-label">Total Balance</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($profit, 2) ?></div>
        <div class="stat-label">Profit</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($bonus, 2) ?></div>
        <div class="stat-label">Total Bonus</div>
    </div>
    <div class="stat-card <?= !$isVerified ? 'danger' : '' ?>">
        <div class="stat-value" style="font-size: 16px; <?= $isVerified ? 'color: var(--success)' : 'color: var(--danger)' ?>">
            <?= $isVerified ? 'VERIFIED' : 'UNVERIFIED' ?>
        </div>
        <div class="stat-label">Account Status</div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= $totalTrades ?></div>
        <div class="stat-label">Total Trades</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $openTrades ?></div>
        <div class="stat-label">Open Trades</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $closedTrades ?></div>
        <div class="stat-label">Closed Trades</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $winLossRatio ?></div>
        <div class="stat-label">Win/Loss Ratio</div>
    </div>
</div>

<div class="action-buttons">
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

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Open Positions</h3>
            <a href="/positions" class="btn btn-secondary btn-sm">View All</a>
        </div>
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
                            <th>Entry</th>
                            <th>PnL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($openPositions, 0, 5) as $position): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($position['symbol']) ?></strong></td>
                                <td>
                                    <span class="badge <?= $position['side'] === 'buy' ? 'badge-success' : 'badge-danger' ?>">
                                        <?= strtoupper($position['side']) ?>
                                    </span>
                                </td>
                                <td><?= number_format($position['amount'], 4) ?></td>
                                <td>$<?= number_format($position['entry_price'], 2) ?></td>
                                <td class="<?= ($position['unrealized_pnl'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                                    <?= ($position['unrealized_pnl'] ?? 0) >= 0 ? '+' : '' ?>$<?= number_format($position['unrealized_pnl'] ?? 0, 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Transactions</h3>
            <a href="/wallet" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php if (empty($recentTransactions)): ?>
                <div class="empty-state">
                    <p>No recent transactions</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentTransactions as $tx): ?>
                            <tr>
                                <td>
                                    <span class="badge <?= $tx['type'] === 'deposit' ? 'badge-success' : 'badge-warning' ?>">
                                        <?= ucfirst($tx['type']) ?>
                                    </span>
                                </td>
                                <td>$<?= number_format($tx['amount'], 2) ?></td>
                                <td>
                                    <span class="badge badge-<?= $tx['status'] === 'approved' || $tx['status'] === 'paid' ? 'success' : ($tx['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                        <?= ucfirst($tx['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, H:i', strtotime($tx['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Dashboard';
include __DIR__ . '/../layouts/main.php';
?>
