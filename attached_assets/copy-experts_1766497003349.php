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
                            <button class="btn-start-copying" data-trader-id="<?= $trader['id'] ?>" data-trader-name="<?= htmlspecialchars($trader['name']) ?>">Start Copying</button>
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

<!-- Confirmation Modal -->
<div id="copyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon">ⓘ</div>
        </div>
        <h2 class="modal-title">Start Copying?</h2>
        <p class="modal-text">Are you sure you want to start copying this expert?</p>
        <div class="modal-actions">
            <button class="btn-yes" id="confirmCopy">Yes, Start</button>
            <button class="btn-no" id="cancelCopy">No, Cancel</button>
        </div>
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
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
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
    color: #00d4aa;
    margin: 0 0 4px 0;
}

.trader-title {
    font-size: 12px;
    color: #8899a6;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 12px 0;
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

.btn-start-copying {
    width: 100%;
    background: #4a90e2;
    color: #fff;
    border: none;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-start-copying:hover {
    background: #357ace;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(74, 144, 226, 0.3);
}

.no-data {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px 20px;
    color: #8899a6;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: linear-gradient(135deg, #0f2744 0%, #1a3456 100%);
    border: 1px solid rgba(0, 212, 170, 0.2);
    border-radius: 16px;
    padding: 40px 32px;
    text-align: center;
    max-width: 400px;
    box-shadow: 0 24px 56px rgba(0, 0, 0, 0.4);
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    margin-bottom: 20px;
}

.modal-icon {
    font-size: 48px;
    color: #00d4aa;
    line-height: 1;
}

.modal-title {
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    margin: 16px 0 8px 0;
}

.modal-text {
    font-size: 14px;
    color: #8899a6;
    margin: 0 0 24px 0;
    line-height: 1.5;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.btn-yes,
.btn-no {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-yes {
    background: #2ecc71;
    color: #fff;
    min-width: 120px;
}

.btn-yes:hover {
    background: #27ae60;
    transform: translateY(-2px);
}

.btn-no {
    background: #e74c3c;
    color: #fff;
    min-width: 120px;
}

.btn-no:hover {
    background: #c0392b;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .traders-grid {
        grid-template-columns: 1fr;
    }
    
    .page-title {
        font-size: 24px;
    }

    .modal-content {
        padding: 32px 24px;
        max-width: 90%;
    }

    .modal-actions {
        flex-direction: column;
    }

    .btn-yes,
    .btn-no {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('copyModal');
    const confirmBtn = document.getElementById('confirmCopy');
    const cancelBtn = document.getElementById('cancelCopy');
    const copyButtons = document.querySelectorAll('.btn-start-copying');
    let selectedTrader = null;

    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            selectedTrader = {
                id: this.dataset.traderId,
                name: this.dataset.traderName
            };
            modal.classList.add('active');
        });
    });

    cancelBtn.addEventListener('click', function() {
        modal.classList.remove('active');
        selectedTrader = null;
    });

    confirmBtn.addEventListener('click', function() {
        if (selectedTrader) {
            // TODO: Send request to start copying this trader
            console.log('Starting to copy trader:', selectedTrader);
            // You can add actual API call here
            modal.classList.remove('active');
        }
    });

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            selectedTrader = null;
        }
    });
});
</script>