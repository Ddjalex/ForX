<?php
$pageTitle = 'NFT Market';
?>

<div class="container mt-5">
    <div class="page-header mb-5">
        <h1 class="page-title">NFT Collections</h1>
        <p class="page-subtitle">Explore top NFT collections with real-time floor prices and market data</p>
    </div>

    <div class="nft-grid">
        <?php if (!empty($collections)): ?>
            <?php foreach ($collections as $nft): ?>
                <div class="nft-card">
                    <div class="nft-image">
                        <img src="<?= htmlspecialchars($nft['image']) ?>" alt="<?= htmlspecialchars($nft['name']) ?>">
                    </div>
                    <div class="nft-content">
                        <div class="nft-header">
                            <h3 class="nft-name"><?= htmlspecialchars($nft['name']) ?></h3>
                            <span class="nft-symbol"><?= htmlspecialchars($nft['symbol']) ?></span>
                        </div>
                        
                        <div class="nft-stats">
                            <div class="stat">
                                <span class="stat-label">Floor Price</span>
                                <span class="stat-value"><?= number_format($nft['floor_price'], 2) ?> ETH</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">24h Change</span>
                                <span class="stat-value <?= $nft['floor_price_change_24h'] >= 0 ? 'positive' : 'negative' ?>">
                                    <?= $nft['floor_price_change_24h'] >= 0 ? '+' : '' ?><?= number_format($nft['floor_price_change_24h'], 1) ?>%
                                </span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">24h Volume</span>
                                <span class="stat-value">$<?= number_format($nft['volume_24h']) ?></span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Owners</span>
                                <span class="stat-value"><?= number_format($nft['owners']) ?></span>
                            </div>
                        </div>
                        
                        <div class="nft-actions">
                            <button class="btn btn-primary btn-sm">View Collection</button>
                            <button class="btn btn-secondary btn-sm">Track</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-data">
                <p>No NFT collections available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header { text-align: center; margin-bottom: 40px; }
.page-title { font-size: 32px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.page-subtitle { font-size: 16px; color: #8899a6; }

.nft-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.nft-card {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(16, 185, 129, 0.1);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.nft-card:hover {
    border-color: rgba(16, 185, 129, 0.4);
    box-shadow: 0 12px 32px rgba(16, 185, 129, 0.15);
    transform: translateY(-4px);
}

.nft-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: linear-gradient(135deg, #0a1628 0%, #0f2744 100%);
}

.nft-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.nft-content { padding: 20px; }

.nft-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.nft-name {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    margin: 0;
}

.nft-symbol {
    background: rgba(0, 212, 170, 0.1);
    color: #00d4aa;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
}

.nft-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding: 16px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    margin-bottom: 16px;
}

.stat { display: flex; flex-direction: column; gap: 4px; }
.stat-label { font-size: 11px; color: #5c6c7c; text-transform: uppercase; font-weight: 600; }
.stat-value { font-size: 14px; font-weight: 700; color: #00d4aa; }
.stat-value.positive { color: #10b981; }
.stat-value.negative { color: #ef4444; }

.nft-actions { display: flex; gap: 8px; }
.nft-actions .btn { flex: 1; font-size: 13px; padding: 10px 12px; }
.btn-primary { background: #00d4aa; color: #0a1628; border: none; border-radius: 8px; cursor: pointer; }
.btn-primary:hover { background: #00b894; }
.btn-secondary { background: transparent; color: #00d4aa; border: 1px solid #00d4aa; border-radius: 8px; cursor: pointer; }
.btn-secondary:hover { background: rgba(0, 212, 170, 0.1); }

.no-data { grid-column: 1 / -1; text-align: center; padding: 40px 20px; color: #8899a6; }

@media (max-width: 768px) {
    .nft-grid { grid-template-columns: 1fr; }
    .page-title { font-size: 24px; }
}
</style>
