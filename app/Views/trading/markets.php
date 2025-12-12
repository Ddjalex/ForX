<?php
ob_start();
?>

<div class="tabs mb-4">
    <div class="tab active" data-filter="all">All Markets</div>
    <div class="tab" data-filter="crypto">Crypto</div>
    <div class="tab" data-filter="forex">Forex</div>
</div>

<div class="markets-grid" id="marketsGrid">
    <?php if (empty($markets)): ?>
        <div class="empty-state" style="grid-column: 1/-1;">
            <p>No markets available at the moment.</p>
        </div>
    <?php else: ?>
        <?php foreach ($markets as $market): ?>
            <a href="/trade/<?= htmlspecialchars($market['symbol']) ?>" class="market-card" data-type="<?= htmlspecialchars($market['type']) ?>">
                <div class="market-symbol"><?= htmlspecialchars($market['symbol']) ?></div>
                <div class="market-name"><?= htmlspecialchars($market['name']) ?></div>
                <div class="market-price">$<?= number_format($market['price'] ?? 0, $market['type'] === 'crypto' ? 2 : 5) ?></div>
                <div class="market-change <?= ($market['change_24h'] ?? 0) >= 0 ? 'positive' : 'negative' ?>">
                    <?= ($market['change_24h'] ?? 0) >= 0 ? '+' : '' ?><?= number_format($market['change_24h'] ?? 0, 2) ?>%
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.tabs .tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.tabs .tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        document.querySelectorAll('.market-card').forEach(card => {
            if (filter === 'all' || card.dataset.type === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Markets';
include __DIR__ . '/../layouts/main.php';
?>
