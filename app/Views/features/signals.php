<?php
$pageTitle = 'Trading Signals';
?>

<div class="container mt-5">
    <div class="page-header mb-5">
        <h1 class="page-title">Trading Signals</h1>
        <p class="page-subtitle">AI-powered trading signals with technical analysis indicators</p>
    </div>

    <div class="signals-container">
        <?php if (!empty($signals)): ?>
            <?php foreach ($signals as $signal): ?>
                <div class="signal-card <?= strtolower(str_replace(' ', '-', $signal['signal'])) ?>">
                    <div class="signal-header">
                        <div class="signal-symbol">
                            <span class="symbol-name"><?= htmlspecialchars($signal['symbol']) ?></span>
                            <span class="symbol-label"><?= htmlspecialchars($signal['name'] ?? '') ?></span>
                        </div>
                        <div class="signal-badge <?= strtolower(str_replace(' ', '-', $signal['signal'])) ?>">
                            <?= htmlspecialchars($signal['signal']) ?>
                        </div>
                    </div>
                    
                    <div class="signal-metrics">
                        <div class="metric">
                            <span class="metric-label">Confidence</span>
                            <div class="confidence-bar">
                                <div class="confidence-fill" style="width: <?= $signal['confidence'] ?>%"></div>
                            </div>
                            <span class="metric-value"><?= $signal['confidence'] ?>%</span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">RSI</span>
                            <span class="metric-value"><?= $signal['rsi'] ?></span>
                        </div>
                        <div class="metric">
                            <span class="metric-label">Timeframe</span>
                            <span class="metric-value"><?= $signal['timeframe'] ?></span>
                        </div>
                    </div>
                    
                    <?php if (isset($signal['entry_price'])): ?>
                    <div class="signal-prices">
                        <div class="price-item">
                            <span class="price-label">Entry</span>
                            <span class="price-value">$<?= number_format($signal['entry_price'], 2) ?></span>
                        </div>
                        <div class="price-item target">
                            <span class="price-label">Target</span>
                            <span class="price-value">$<?= number_format($signal['target_price'], 2) ?></span>
                        </div>
                        <div class="price-item stop">
                            <span class="price-label">Stop Loss</span>
                            <span class="price-value">$<?= number_format($signal['stop_loss'], 2) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="signal-footer">
                        <span class="signal-time"><?= date('H:i', strtotime($signal['timestamp'])) ?></span>
                        <button class="btn btn-trade">Trade Now</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-data">
                <p>No trading signals available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header { text-align: center; margin-bottom: 40px; }
.page-title { font-size: 32px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.page-subtitle { font-size: 16px; color: #8899a6; }

.signals-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.signal-card {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(16, 185, 129, 0.1);
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s ease;
}

.signal-card:hover {
    border-color: rgba(16, 185, 129, 0.4);
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.15);
}

.signal-card.strong-buy, .signal-card.buy { border-left: 4px solid #10b981; }
.signal-card.strong-sell, .signal-card.sell { border-left: 4px solid #ef4444; }
.signal-card.hold { border-left: 4px solid #f59e0b; }

.signal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }

.signal-symbol { display: flex; flex-direction: column; }
.symbol-name { font-size: 18px; font-weight: 700; color: #fff; }
.symbol-label { font-size: 12px; color: #8899a6; }

.signal-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}

.signal-badge.strong-buy, .signal-badge.buy { background: rgba(16, 185, 129, 0.2); color: #10b981; }
.signal-badge.strong-sell, .signal-badge.sell { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
.signal-badge.hold { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }

.signal-metrics { display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px; }

.metric { display: flex; align-items: center; justify-content: space-between; }
.metric-label { font-size: 12px; color: #5c6c7c; }
.metric-value { font-size: 14px; font-weight: 600; color: #00d4aa; }

.confidence-bar {
    flex: 1;
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    margin: 0 12px;
    overflow: hidden;
}

.confidence-fill {
    height: 100%;
    background: linear-gradient(90deg, #00d4aa, #10b981);
    border-radius: 3px;
}

.signal-prices {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    padding: 16px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    margin-bottom: 16px;
}

.price-item { display: flex; flex-direction: column; gap: 4px; text-align: center; }
.price-label { font-size: 10px; color: #5c6c7c; text-transform: uppercase; }
.price-value { font-size: 13px; font-weight: 600; color: #fff; }
.price-item.target .price-value { color: #10b981; }
.price-item.stop .price-value { color: #ef4444; }

.signal-footer { display: flex; justify-content: space-between; align-items: center; }
.signal-time { font-size: 12px; color: #5c6c7c; }

.btn-trade {
    background: #00d4aa;
    color: #0a1628;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
}

.btn-trade:hover { background: #00b894; }

.no-data { grid-column: 1 / -1; text-align: center; padding: 40px 20px; color: #8899a6; }

@media (max-width: 768px) {
    .signals-container { grid-template-columns: 1fr; }
    .page-title { font-size: 24px; }
}
</style>
