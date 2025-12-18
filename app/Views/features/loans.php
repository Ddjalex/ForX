<?php
$pageTitle = 'Crypto Loans';
?>

<div class="container mt-5">
    <div class="page-header mb-5">
        <h1 class="page-title">Crypto Loans</h1>
        <p class="page-subtitle">Get instant crypto-backed loans with competitive rates</p>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="loan-plans-grid">
        <?php if (!empty($plans)): ?>
            <?php foreach ($plans as $plan): ?>
                <div class="loan-plan-card">
                    <div class="plan-header">
                        <h3 class="plan-name"><?= htmlspecialchars($plan['name']) ?></h3>
                        <div class="plan-rate"><?= number_format($plan['interest_rate'], 1) ?>%</div>
                    </div>
                    
                    <p class="plan-description"><?= htmlspecialchars($plan['description'] ?? 'Flexible loan terms with competitive rates') ?></p>
                    
                    <div class="plan-details">
                        <div class="detail-row">
                            <span class="detail-label">Loan Amount</span>
                            <span class="detail-value">$<?= number_format($plan['min_amount']) ?> - $<?= number_format($plan['max_amount']) ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Duration</span>
                            <span class="detail-value"><?= $plan['duration_days'] ?> days</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Collateral Ratio</span>
                            <span class="detail-value"><?= $plan['collateral_ratio'] ?>%</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Interest Rate</span>
                            <span class="detail-value highlight"><?= number_format($plan['interest_rate'], 1) ?>% APR</span>
                        </div>
                    </div>
                    
                    <form action="/loans/apply" method="POST" class="loan-form">
                        <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                        <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                        
                        <div class="form-group">
                            <label>Loan Amount ($)</label>
                            <input type="number" name="amount" min="<?= $plan['min_amount'] ?>" max="<?= $plan['max_amount'] ?>" step="100" placeholder="Enter amount" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Collateral Asset</label>
                            <select name="collateral">
                                <option value="BTC">Bitcoin (BTC)</option>
                                <option value="ETH">Ethereum (ETH)</option>
                                <option value="USDT">Tether (USDT)</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-apply">Apply for Loan</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-data">
                <p>No loan plans available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($userLoans)): ?>
    <div class="user-loans-section">
        <h2 class="section-title">Your Loans</h2>
        <div class="loans-table">
            <table>
                <thead>
                    <tr>
                        <th>Loan ID</th>
                        <th>Amount</th>
                        <th>Interest</th>
                        <th>Total Repayment</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userLoans as $loan): ?>
                    <tr>
                        <td>#<?= $loan['id'] ?></td>
                        <td>$<?= number_format($loan['amount'], 2) ?></td>
                        <td>$<?= number_format($loan['interest_amount'], 2) ?></td>
                        <td>$<?= number_format($loan['total_repayment'], 2) ?></td>
                        <td><span class="status-badge <?= $loan['status'] ?>"><?= ucfirst($loan['status']) ?></span></td>
                        <td><?= date('M d, Y', strtotime($loan['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.page-header { text-align: center; margin-bottom: 40px; }
.page-title { font-size: 32px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.page-subtitle { font-size: 16px; color: #8899a6; }

.alert { padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; }
.alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; }
.alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; }

.loan-plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.loan-plan-card {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(16, 185, 129, 0.1);
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s ease;
}

.loan-plan-card:hover {
    border-color: rgba(16, 185, 129, 0.4);
    box-shadow: 0 12px 32px rgba(16, 185, 129, 0.15);
}

.plan-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.plan-name { font-size: 20px; font-weight: 700; color: #fff; margin: 0; }
.plan-rate { background: rgba(0, 212, 170, 0.1); color: #00d4aa; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: 700; }

.plan-description { font-size: 13px; color: #8899a6; margin-bottom: 20px; line-height: 1.5; }

.plan-details { margin-bottom: 20px; }
.detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.detail-label { font-size: 13px; color: #5c6c7c; }
.detail-value { font-size: 13px; font-weight: 600; color: #fff; }
.detail-value.highlight { color: #00d4aa; }

.loan-form .form-group { margin-bottom: 16px; }
.loan-form label { display: block; font-size: 12px; color: #8899a6; margin-bottom: 6px; }
.loan-form input, .loan-form select {
    width: 100%;
    padding: 12px 16px;
    background: rgba(10, 22, 40, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
}

.btn-apply {
    width: 100%;
    padding: 14px;
    background: #00d4aa;
    color: #0a1628;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-apply:hover { background: #00b894; }

.user-loans-section { margin-top: 40px; }
.section-title { font-size: 24px; font-weight: 700; color: #fff; margin-bottom: 20px; }

.loans-table { overflow-x: auto; }
.loans-table table { width: 100%; border-collapse: collapse; }
.loans-table th, .loans-table td { padding: 14px 16px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.loans-table th { font-size: 12px; color: #5c6c7c; text-transform: uppercase; font-weight: 600; }
.loans-table td { font-size: 14px; color: #fff; }

.status-badge { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.status-badge.pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
.status-badge.approved { background: rgba(16, 185, 129, 0.2); color: #10b981; }
.status-badge.rejected { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

.no-data { grid-column: 1 / -1; text-align: center; padding: 40px 20px; color: #8899a6; }

@media (max-width: 768px) {
    .loan-plans-grid { grid-template-columns: 1fr; }
    .page-title { font-size: 24px; }
}
</style>
