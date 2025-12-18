<?php
$pageTitle = 'Referral Program';
?>

<div class="container mt-5">
    <div class="page-header mb-5">
        <h1 class="page-title">Referral Program</h1>
        <p class="page-subtitle">Earn rewards by inviting friends to Alpha Core Markets</p>
    </div>

    <div class="referral-dashboard">
        <div class="referral-link-card">
            <h3>Your Referral Link</h3>
            <div class="link-box">
                <input type="text" id="referralLink" value="<?= htmlspecialchars($referralLink) ?>" readonly>
                <button class="btn-copy" onclick="copyLink()">Copy</button>
            </div>
            <p class="link-note">Share this link with friends and earn <?= $currentTier['bonus'] ?>$ for each signup!</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <span class="stat-value"><?= $stats['total_referrals'] ?></span>
                    <span class="stat-label">Total Referrals</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👁️</div>
                <div class="stat-info">
                    <span class="stat-value"><?= $stats['clicks'] ?></span>
                    <span class="stat-label">Link Clicks</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <span class="stat-value">$<?= number_format($stats['total_earnings'], 2) ?></span>
                    <span class="stat-label">Total Earnings</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <span class="stat-value">$<?= number_format($stats['pending_earnings'], 2) ?></span>
                    <span class="stat-label">Pending</span>
                </div>
            </div>
        </div>

        <div class="current-tier">
            <h3>Your Current Tier</h3>
            <div class="tier-badge tier-<?= strtolower($currentTier['name']) ?>">
                <?= $currentTier['name'] ?>
            </div>
            <div class="tier-benefits">
                <div class="benefit">
                    <span class="benefit-label">Commission Rate</span>
                    <span class="benefit-value"><?= $currentTier['commission_rate'] ?>%</span>
                </div>
                <div class="benefit">
                    <span class="benefit-label">Signup Bonus</span>
                    <span class="benefit-value">$<?= $currentTier['bonus'] ?></span>
                </div>
            </div>
        </div>

        <div class="tiers-section">
            <h3>Referral Tiers</h3>
            <div class="tiers-grid">
                <?php foreach ($tiers as $tier): ?>
                    <div class="tier-card <?= $tier['name'] === $currentTier['name'] ? 'active' : '' ?>">
                        <div class="tier-name"><?= $tier['name'] ?></div>
                        <div class="tier-requirement"><?= $tier['min_referrals'] ?>+ Referrals</div>
                        <div class="tier-perks">
                            <div class="perk"><?= $tier['commission_rate'] ?>% Commission</div>
                            <div class="perk">$<?= $tier['bonus'] ?> Per Signup</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!empty($stats['referrals'])): ?>
        <div class="referrals-list">
            <h3>Your Referrals</h3>
            <div class="referrals-table">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Joined</th>
                            <th>Earnings</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['referrals'] as $ref): ?>
                        <tr>
                            <td><?= htmlspecialchars($ref['name']) ?></td>
                            <td><?= htmlspecialchars($ref['email']) ?></td>
                            <td><?= date('M d, Y', strtotime($ref['created_at'])) ?></td>
                            <td>$<?= number_format($ref['amount'], 2) ?></td>
                            <td><span class="status-badge <?= $ref['status'] ?>"><?= ucfirst($ref['status']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function copyLink() {
    var input = document.getElementById('referralLink');
    input.select();
    document.execCommand('copy');
    alert('Referral link copied!');
}
</script>

<style>
.page-header { text-align: center; margin-bottom: 40px; }
.page-title { font-size: 32px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.page-subtitle { font-size: 16px; color: #8899a6; }

.referral-dashboard { max-width: 1000px; margin: 0 auto; }

.referral-link-card {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    text-align: center;
}

.referral-link-card h3 { color: #fff; margin-bottom: 20px; }

.link-box {
    display: flex;
    gap: 12px;
    max-width: 600px;
    margin: 0 auto 16px;
}

.link-box input {
    flex: 1;
    padding: 14px 20px;
    background: rgba(10, 22, 40, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #00d4aa;
    font-size: 14px;
}

.btn-copy {
    padding: 14px 24px;
    background: #00d4aa;
    color: #0a1628;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.btn-copy:hover { background: #00b894; }

.link-note { font-size: 13px; color: #8899a6; }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(16, 185, 129, 0.1);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon { font-size: 28px; }
.stat-info { display: flex; flex-direction: column; }
.stat-value { font-size: 24px; font-weight: 700; color: #00d4aa; }
.stat-label { font-size: 12px; color: #5c6c7c; }

.current-tier {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    text-align: center;
}

.current-tier h3 { color: #fff; margin-bottom: 20px; }

.tier-badge {
    display: inline-block;
    padding: 12px 32px;
    border-radius: 30px;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
}

.tier-bronze { background: linear-gradient(135deg, #cd7f32, #b87333); color: #fff; }
.tier-silver { background: linear-gradient(135deg, #c0c0c0, #a8a8a8); color: #333; }
.tier-gold { background: linear-gradient(135deg, #ffd700, #daa520); color: #333; }
.tier-platinum { background: linear-gradient(135deg, #e5e4e2, #c0c0c0); color: #333; }
.tier-diamond { background: linear-gradient(135deg, #b9f2ff, #00bfff); color: #333; }

.tier-benefits { display: flex; justify-content: center; gap: 40px; }
.benefit { display: flex; flex-direction: column; gap: 4px; }
.benefit-label { font-size: 12px; color: #5c6c7c; }
.benefit-value { font-size: 20px; font-weight: 700; color: #00d4aa; }

.tiers-section { margin-bottom: 30px; }
.tiers-section h3 { color: #fff; margin-bottom: 20px; }

.tiers-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }

.tier-card {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
}

.tier-card.active { border-color: #00d4aa; }
.tier-name { font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.tier-requirement { font-size: 12px; color: #00d4aa; margin-bottom: 12px; }
.tier-perks .perk { font-size: 11px; color: #8899a6; margin-bottom: 4px; }

.referrals-list { margin-top: 30px; }
.referrals-list h3 { color: #fff; margin-bottom: 20px; }

.referrals-table { overflow-x: auto; }
.referrals-table table { width: 100%; border-collapse: collapse; }
.referrals-table th, .referrals-table td { padding: 14px 16px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
.referrals-table th { font-size: 12px; color: #5c6c7c; text-transform: uppercase; }
.referrals-table td { font-size: 14px; color: #fff; }

.status-badge { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; }
.status-badge.pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
.status-badge.paid { background: rgba(16, 185, 129, 0.2); color: #10b981; }

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .tiers-grid { grid-template-columns: repeat(2, 1fr); }
    .page-title { font-size: 24px; }
}
</style>
