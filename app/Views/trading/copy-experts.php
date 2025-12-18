<?php
// app/Views/trading/copy-experts.php
$pageTitle = 'Copy Experts';
?>

<div class="container mt-5">
    <div class="page-header mb-5">
        <h1 class="page-title">Copy Expert Traders</h1>
        <p class="page-subtitle">Follow and copy trades from professional traders. Profit from their expertise.</p>
    </div>

    <div class="traders-grid">
        <?php if (!empty($traders)): ?>
            <?php foreach ($traders as $trader): ?>
                <div class="trader-card">
                    <div class="trader-image">
                        <img src="<?= htmlspecialchars($trader['profile_image'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($trader['name']) . '&background=00D4AA&color=0a1628&size=300') ?>" alt="<?= htmlspecialchars($trader['name']) ?>">
                    </div>
                    
                    <div class="trader-content">
                        <h3 class="trader-name"><?= htmlspecialchars($trader['name']) ?></h3>
                        <p class="trader-title"><?= htmlspecialchars($trader['title']) ?></p>
                        
                        <?php if (!empty($trader['bio'])): ?>
                            <p class="trader-bio"><?= htmlspecialchars(substr($trader['bio'], 0, 100)) ?>...</p>
                        <?php endif; ?>
                        
                        <div class="trader-stats">
                            <div class="stat">
                                <span class="stat-label">Profit Share</span>
                                <span class="stat-value"><?= number_format($trader['profit_share'], 2) ?>%</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Win Rate</span>
                                <span class="stat-value"><?= number_format($trader['win_rate'], 2) ?>%</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Min Capital</span>
                                <span class="stat-value">$<?= number_format($trader['minimum_capital'], 0) ?></span>
                            </div>
                        </div>
                        
                        <div class="trader-actions">
                            <button class="btn btn-primary btn-sm btn-follow">Follow Trader</button>
                            <button class="btn btn-secondary btn-sm btn-view">View Profile</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-data">
                <p>No traders available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 8px;
}

.page-subtitle {
    font-size: 16px;
    color: #8899a6;
}

.traders-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.trader-card {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(16, 185, 129, 0.1);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.trader-card:hover {
    border-color: rgba(16, 185, 129, 0.4);
    box-shadow: 0 12px 32px rgba(16, 185, 129, 0.15);
    transform: translateY(-4px);
}

.trader-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #0a1628 0%, #0f2744 100%);
}

.trader-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.trader-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.trader-name {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 4px 0;
}

.trader-title {
    font-size: 12px;
    color: #00d4aa;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 12px 0;
}

.trader-bio {
    font-size: 13px;
    color: #8899a6;
    margin: 0 0 16px 0;
    line-height: 1.4;
}

.trader-stats {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 12px;
    padding: 16px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    margin-bottom: 16px;
    flex-grow: 1;
}

.stat {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-label {
    font-size: 11px;
    color: #5c6c7c;
    text-transform: uppercase;
    font-weight: 600;
}

.stat-value {
    font-size: 14px;
    font-weight: 700;
    color: #00d4aa;
}

.trader-actions {
    display: flex;
    gap: 8px;
}

.btn-follow,
.btn-view {
    flex: 1;
    font-size: 13px;
    padding: 10px 12px;
}

.btn-follow {
    background: #00d4aa;
    color: #0a1628;
}

.btn-follow:hover {
    background: #00b894;
}

.btn-view {
    background: transparent;
    color: #00d4aa;
    border: 1px solid #00d4aa;
}

.btn-view:hover {
    background: rgba(0, 212, 170, 0.1);
}

.no-data {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px 20px;
    color: #8899a6;
}

@media (max-width: 768px) {
    .traders-grid {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 24px;
    }
}
</style>