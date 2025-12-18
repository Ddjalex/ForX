<?php
$pageTitle = 'Live Analysis';
?>

<div class="container mt-5">
    <div class="page-header mb-5">
        <h1 class="page-title">Live Market Analysis</h1>
        <p class="page-subtitle">Real-time market insights, signals, and breaking news</p>
    </div>

    <div class="analysis-grid">
        <div class="analysis-section signals-section">
            <h2 class="section-title">Top Trading Signals</h2>
            <div class="live-signals">
                <?php foreach (array_slice($signals, 0, 6) as $signal): ?>
                    <div class="live-signal-item <?= strtolower(str_replace(' ', '-', $signal['signal'])) ?>">
                        <div class="signal-info">
                            <span class="symbol"><?= htmlspecialchars($signal['symbol']) ?></span>
                            <span class="timeframe"><?= $signal['timeframe'] ?></span>
                        </div>
                        <div class="signal-action <?= strtolower(str_replace(' ', '-', $signal['signal'])) ?>">
                            <?= htmlspecialchars($signal['signal']) ?>
                        </div>
                        <div class="signal-confidence">
                            <div class="confidence-bar">
                                <div class="fill" style="width: <?= $signal['confidence'] ?>%"></div>
                            </div>
                            <span><?= $signal['confidence'] ?>%</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="analysis-section news-section">
            <h2 class="section-title">Breaking News</h2>
            <div class="live-news">
                <?php foreach (array_slice($news, 0, 5) as $item): ?>
                    <div class="live-news-item">
                        <div class="news-time"><?= date('H:i', strtotime($item['datetime'])) ?></div>
                        <div class="news-content">
                            <h4><?= htmlspecialchars($item['title']) ?></h4>
                            <span class="news-source"><?= htmlspecialchars($item['source']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="analysis-section market-overview">
            <h2 class="section-title">Market Overview</h2>
            <div class="market-stats">
                <div class="market-stat">
                    <span class="stat-label">Market Sentiment</span>
                    <span class="stat-value bullish">Bullish</span>
                </div>
                <div class="market-stat">
                    <span class="stat-label">Fear & Greed Index</span>
                    <span class="stat-value">72 - Greed</span>
                </div>
                <div class="market-stat">
                    <span class="stat-label">BTC Dominance</span>
                    <span class="stat-value">48.5%</span>
                </div>
                <div class="market-stat">
                    <span class="stat-label">24h Volume</span>
                    <span class="stat-value">$125.8B</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header { text-align: center; margin-bottom: 40px; }
.page-title { font-size: 32px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.page-subtitle { font-size: 16px; color: #8899a6; }

.analysis-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.analysis-section {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(16, 185, 129, 0.1);
    border-radius: 16px;
    padding: 24px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title::before {
    content: '';
    width: 4px;
    height: 20px;
    background: #00d4aa;
    border-radius: 2px;
}

.signals-section { grid-column: 1; grid-row: 1 / 3; }
.news-section { grid-column: 2; grid-row: 1; }
.market-overview { grid-column: 2; grid-row: 2; }

.live-signals { display: flex; flex-direction: column; gap: 12px; }

.live-signal-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    background: rgba(10, 22, 40, 0.5);
    border-radius: 12px;
    border-left: 3px solid #5c6c7c;
}

.live-signal-item.strong-buy, .live-signal-item.buy { border-left-color: #10b981; }
.live-signal-item.strong-sell, .live-signal-item.sell { border-left-color: #ef4444; }
.live-signal-item.hold { border-left-color: #f59e0b; }

.signal-info { display: flex; flex-direction: column; gap: 4px; }
.signal-info .symbol { font-size: 16px; font-weight: 700; color: #fff; }
.signal-info .timeframe { font-size: 11px; color: #5c6c7c; }

.signal-action {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.signal-action.strong-buy, .signal-action.buy { background: rgba(16, 185, 129, 0.2); color: #10b981; }
.signal-action.strong-sell, .signal-action.sell { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
.signal-action.hold { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }

.signal-confidence { display: flex; align-items: center; gap: 10px; min-width: 100px; }
.signal-confidence .confidence-bar { flex: 1; height: 6px; background: rgba(255, 255, 255, 0.1); border-radius: 3px; overflow: hidden; }
.signal-confidence .fill { height: 100%; background: #00d4aa; border-radius: 3px; }
.signal-confidence span { font-size: 12px; color: #00d4aa; font-weight: 600; }

.live-news { display: flex; flex-direction: column; gap: 16px; }

.live-news-item {
    display: flex;
    gap: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.live-news-item:last-child { border-bottom: none; padding-bottom: 0; }

.news-time {
    font-size: 12px;
    color: #00d4aa;
    font-weight: 600;
    min-width: 50px;
}

.news-content h4 { font-size: 14px; color: #fff; margin: 0 0 6px 0; line-height: 1.4; }
.news-source { font-size: 11px; color: #5c6c7c; }

.market-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.market-stat {
    background: rgba(10, 22, 40, 0.5);
    padding: 16px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.market-stat .stat-label { font-size: 11px; color: #5c6c7c; text-transform: uppercase; }
.market-stat .stat-value { font-size: 16px; font-weight: 700; color: #00d4aa; }
.market-stat .stat-value.bullish { color: #10b981; }
.market-stat .stat-value.bearish { color: #ef4444; }

@media (max-width: 992px) {
    .analysis-grid { grid-template-columns: 1fr; }
    .signals-section { grid-column: 1; grid-row: auto; }
    .news-section { grid-column: 1; grid-row: auto; }
    .market-overview { grid-column: 1; grid-row: auto; }
}

@media (max-width: 768px) {
    .page-title { font-size: 24px; }
}
</style>
