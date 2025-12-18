<?php
$pageTitle = 'Market News';
?>

<div class="container mt-5">
    <div class="page-header mb-5">
        <h1 class="page-title">Market News</h1>
        <p class="page-subtitle">Stay updated with the latest crypto and financial market news</p>
    </div>

    <div class="news-grid">
        <?php if (!empty($news)): ?>
            <?php foreach ($news as $item): ?>
                <div class="news-card">
                    <div class="news-image">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <span class="news-category"><?= htmlspecialchars(ucfirst($item['category'])) ?></span>
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-source"><?= htmlspecialchars($item['source']) ?></span>
                            <span class="news-date"><?= date('M d, Y', strtotime($item['datetime'])) ?></span>
                        </div>
                        <h3 class="news-title"><?= htmlspecialchars($item['title']) ?></h3>
                        <p class="news-summary"><?= htmlspecialchars(substr($item['summary'], 0, 120)) ?>...</p>
                        <?php if (!empty($item['related'])): ?>
                            <div class="news-tags">
                                <?php foreach (explode(',', $item['related']) as $tag): ?>
                                    <span class="tag"><?= htmlspecialchars(trim($tag)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-data">
                <p>No news available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header { text-align: center; margin-bottom: 40px; }
.page-title { font-size: 32px; font-weight: 700; color: #fff; margin-bottom: 8px; }
.page-subtitle { font-size: 16px; color: #8899a6; }

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.news-card {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(16, 185, 129, 0.1);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.news-card:hover {
    border-color: rgba(16, 185, 129, 0.4);
    box-shadow: 0 12px 32px rgba(16, 185, 129, 0.15);
    transform: translateY(-4px);
}

.news-image {
    position: relative;
    width: 100%;
    height: 180px;
    overflow: hidden;
}

.news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-category {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #00d4aa;
    color: #0a1628;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.news-content { padding: 20px; }

.news-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 12px;
    color: #5c6c7c;
}

.news-source { color: #00d4aa; font-weight: 600; }

.news-title {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 12px 0;
    line-height: 1.4;
}

.news-summary {
    font-size: 13px;
    color: #8899a6;
    line-height: 1.5;
    margin: 0 0 12px 0;
}

.news-tags { display: flex; gap: 8px; flex-wrap: wrap; }

.tag {
    background: rgba(0, 212, 170, 0.1);
    color: #00d4aa;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.no-data {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px 20px;
    color: #8899a6;
}

@media (max-width: 768px) {
    .news-grid { grid-template-columns: 1fr; }
    .page-title { font-size: 24px; }
}
</style>
