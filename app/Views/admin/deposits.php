<?php
$pending_count = 0;
$approved_count = 0;
$rejected_count = 0;
$deposits = $deposits ?? [];
$csrf_token = $csrf_token ?? '';

foreach ($deposits as $d) {
    if ($d['status'] === 'pending') $pending_count++;
    elseif ($d['status'] === 'approved') $approved_count++;
    elseif ($d['status'] === 'rejected') $rejected_count++;
}
?>
<div class="admin-deposits-wrapper">
    <div class="admin-deposits-container">
        <div class="admin-header-section">
            <div class="header-content">
                <div class="header-title">
                    <h1>Deposit Approvals</h1>
                    <p>Review and approve user deposit requests</p>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-card pending">
                    <div class="stat-icon">⏱️</div>
                    <div class="stat-content">
                        <p class="stat-label">Pending</p>
                        <h3 class="stat-number"><?= $pending_count ?></h3>
                    </div>
                </div>
                <div class="stat-card approved">
                    <div class="stat-icon">✓</div>
                    <div class="stat-content">
                        <p class="stat-label">Approved</p>
                        <h3 class="stat-number"><?= $approved_count ?></h3>
                    </div>
                </div>
                <div class="stat-card rejected">
                    <div class="stat-icon">✕</div>
                    <div class="stat-content">
                        <p class="stat-label">Rejected</p>
                        <h3 class="stat-number"><?= $rejected_count ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="deposits-list">
            <?php if (empty($deposits)): ?>
                <div class="empty-state">
                    <p>No deposits found</p>
                </div>
            <?php else: ?>
                <?php foreach ($deposits as $deposit): ?>
                    <div class="deposit-card" data-status="<?= $deposit['status'] ?>">
                        <div class="deposit-header">
                            <div class="user-info">
                                <div class="user-avatar"><?= strtoupper(substr($deposit['name'] ?? 'U', 0, 1)) ?></div>
                                <div class="user-details">
                                    <h3><?= htmlspecialchars($deposit['name'] ?? 'Unknown') ?></h3>
                                    <p class="user-email"><?= htmlspecialchars($deposit['email'] ?? '') ?></p>
                                </div>
                            </div>
                            <div class="deposit-meta">
                                <span class="status-badge" data-status="<?= $deposit['status'] ?>">
                                    <?= ucfirst($deposit['status']) ?>
                                </span>
                                <span class="date"><?= date('M d, Y', strtotime($deposit['created_at'])) ?></span>
                            </div>
                        </div>

                        <div class="deposit-details">
                            <div class="detail-row">
                                <span class="label">Amount:</span>
                                <span class="value">$<?= number_format($deposit['amount'], 2) ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Method:</span>
                                <span class="value"><?= ucfirst(htmlspecialchars($deposit['method'] ?? '')) ?></span>
                            </div>
                            <?php if ($deposit['txid']): ?>
                            <div class="detail-row">
                                <span class="label">Transaction ID:</span>
                                <span class="value"><?= htmlspecialchars($deposit['txid']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($deposit['status'] === 'pending'): ?>
                        <div class="deposit-actions">
                            <form method="POST" action="/admin/deposits/approve" style="display:inline;">
                                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                <input type="hidden" name="deposit_id" value="<?= $deposit['id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn btn-approve">Approve</button>
                            </form>
                            <form method="POST" action="/admin/deposits/approve" style="display:inline;">
                                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                                <input type="hidden" name="deposit_id" value="<?= $deposit['id'] ?>">
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn btn-reject" onclick="return confirm('Reject this deposit?');">Reject</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
:root {
    --bg-primary: #0a1628;
    --bg-secondary: #112236;
    --text-primary: #ffffff;
    --text-secondary: #a0aec0;
    --accent-primary: #00d4aa;
    --border-color: #2d5a8c;
    --success: #10b981;
    --danger: #ef4444;
}

.admin-deposits-wrapper {
    background: var(--bg-primary);
    min-height: 100vh;
    padding: 30px;
    font-family: 'Inter', sans-serif;
}

.admin-header-section { margin-bottom: 40px; }
.header-title h1 { font-size: 28px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
.header-title p { color: var(--text-secondary); font-size: 15px; }

.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-top: 30px; }
.stat-card { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; display: flex; align-items: center; gap: 20px; }
.stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; background: rgba(0, 212, 170, 0.1); }
.stat-number { font-size: 32px; font-weight: 700; color: var(--text-primary); margin: 0; }
.stat-label { font-size: 13px; color: var(--text-secondary); text-transform: uppercase; margin: 0; }

.deposits-list { }
.deposit-card { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 16px; margin-bottom: 20px; padding: 24px; }
.deposit-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.user-info { display: flex; gap: 16px; align-items: center; }
.user-avatar { width: 50px; height: 50px; border-radius: 50%; background: var(--accent-primary); color: var(--bg-primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; }
.user-details h3 { font-size: 18px; margin: 0 0 4px 0; color: var(--text-primary); }
.user-email { color: var(--accent-primary); font-size: 14px; margin: 0; }

.deposit-meta { display: flex; gap: 16px; align-items: center; }
.status-badge { padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; }
.status-badge[data-status="pending"] { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.status-badge[data-status="approved"] { background: rgba(16, 185, 129, 0.1); color: var(--success); }
.status-badge[data-status="rejected"] { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
.date { color: var(--text-secondary); font-size: 13px; }

.deposit-details { background: rgba(255,255,255,0.02); padding: 16px; border-radius: 12px; margin-bottom: 20px; border: 1px solid var(--border-color); }
.detail-row { display: flex; justify-content: space-between; padding: 8px 0; }
.detail-row .label { color: var(--text-secondary); font-size: 14px; }
.detail-row .value { color: var(--text-primary); font-weight: 600; }

.deposit-actions { display: flex; gap: 12px; }
.btn { padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; transition: 0.3s; }
.btn-approve { background: var(--success); color: #fff; }
.btn-reject { background: var(--danger); color: #fff; }
.btn:hover { opacity: 0.9; }

.empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
.empty-state p { font-size: 18px; }
</style>
