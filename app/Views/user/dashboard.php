<?php
ob_start();

$totalBalance = $wallet['balance'] ?? 0;
$profit = $wallet['profit'] ?? 0;
$bonus = $wallet['bonus'] ?? 0;
$isVerified = isset($isVerified) ? $isVerified : ($user['email_verified'] ?? false);
$kycStatus = $kycStatus ?? 'pending';
$totalTrades = $stats['total_trades'] ?? 0;
$openTrades = $stats['open_trades'] ?? 0;
$closedTrades = $stats['closed_trades'] ?? 0;
$winLossRatio = $stats['win_loss_ratio'] ?? 0;

// Mock notification data
$mockNotifications = [
    ['country' => 'Italy', 'action' => 'withdrawn', 'amount' => 40000],
    ['country' => 'Mexico', 'action' => 'withdrawn', 'amount' => 4500],
    ['country' => 'Turkey', 'action' => 'is trading with', 'amount' => 58623],
    ['country' => 'USA', 'action' => 'withdrawn', 'amount' => 58623],
    ['country' => 'Switzerland', 'action' => 'withdrawn', 'amount' => 40000],
    ['country' => 'Spain', 'action' => 'just invested', 'amount' => 52300],
    ['country' => 'Argentina', 'action' => 'is trading with', 'amount' => 10000],
    ['country' => 'Panama', 'action' => 'just invested', 'amount' => 4500],
    ['country' => 'Canada', 'action' => 'deposited', 'amount' => 15000],
    ['country' => 'Germany', 'action' => 'withdrawn', 'amount' => 32500],
    ['country' => 'UK', 'action' => 'just invested', 'amount' => 78900],
    ['country' => 'Australia', 'action' => 'is trading with', 'amount' => 22300],
];
?>

<div id="systemNotification" class="system-notification" style="display: none;">
    <div class="notification-content">
        <span class="notification-text"></span>
        <button class="notification-close-btn" onclick="closeNotification()">&times;</button>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div class="stat-value" id="dashboardTotalBalanceValue">$<?= number_format($totalBalance, 2) ?></div>
                <div class="stat-label"><?= t('total_balance') ?></div>
            </div>
            <button class="icon-btn" onclick="toggleBalanceVisibility('dashboard')" style="padding: 8px; align-self: flex-start;">
                <svg id="dashboardBalanceEyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($profit, 2) ?></div>
        <div class="stat-label"><?= t('profit') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value">$<?= number_format($bonus, 2) ?></div>
        <div class="stat-label"><?= t('total_bonus') ?></div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= $totalTrades ?></div>
        <div class="stat-label"><?= t('total_trades') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $openTrades ?></div>
        <div class="stat-label"><?= t('open_trades') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $closedTrades ?></div>
        <div class="stat-label"><?= t('closed_trades') ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $winLossRatio ?></div>
        <div class="stat-label"><?= t('win_loss_ratio') ?></div>
    </div>
</div>

<div class="action-buttons">
    <a href="/accounts" class="action-btn account">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        <?= t('account') ?>
    </a>
    <a href="/wallet/deposit" class="action-btn deposit">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        <?= t('make_deposit') ?> $
    </a>
    <a href="/wallet/withdraw" class="action-btn withdraw">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 19 19 12"></polyline></svg>
        <?= t('withdraw_funds') ?>
    </a>
    <a href="mailto:support@tradeflowglobalex.com" class="action-btn mail">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
        <?= t('mail_us') ?>
    </a>
    <a href="/accounts?tab=settings" class="action-btn settings">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        <?= t('settings') ?>
    </a>
</div>

<div class="trading-section">
    <div class="card live-trading-card">
        <div class="card-header collapsible" onclick="toggleCard('tradingCard')">
            <h3 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                <?= t('live_trading') ?>
            </h3>
            <div class="card-actions">
                <span class="balance-display"><?= t('balance') ?>: <strong id="dashboardLiveTradeBalanceValue">$<?= number_format($totalBalance, 2) ?></strong></span>
                <button class="icon-btn" onclick="toggleBalanceVisibility('liveTrading')" style="padding: 4px;">
                    <svg id="liveTradeBalanceEyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
                <button class="icon-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button>
                <svg class="collapse-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        <div class="card-body" id="tradingCard">
            <div style="background: #0f1822; border: 1px solid #2d3a4f; padding: 16px; margin-bottom: 20px; border-radius: 6px;">
                <div style="font-size: 11px; color: #8899a6; text-transform: uppercase; font-weight: 600; margin-bottom: 12px;">Current Market Info</div>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; font-size: 13px;">
                    <div>
                        <span style="color: #8899a6; display: block; margin-bottom: 6px;">Asset Type:</span>
                        <div style="color: #e0e0e0; font-weight: 600;" id="dashboardCurrentAssetType">Crypto</div>
                    </div>
                    <div>
                        <span style="color: #8899a6; display: block; margin-bottom: 6px;">Asset Name:</span>
                        <div style="color: #e0e0e0; font-weight: 600;" id="dashboardCurrentAssetName">BTC/USD</div>
                    </div>
                    <div>
                        <span style="color: #8899a6; display: block; margin-bottom: 6px;">Current Price:</span>
                        <div style="color: #10B981; font-weight: 700;" id="dashboardCurrentPrice">$0.00</div>
                    </div>
                </div>
            </div>
            <form method="POST" action="/dashboard/trade/order" id="tradeForm" class="trading-form">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?? '' ?>">
                <input type="hidden" name="side" id="orderSide" value="buy">
                <input type="hidden" name="order_type" id="orderType" value="market">
                
                <div class="form-group">
                    <label>Asset Type</label>
                    <select name="asset_type" id="assetType" class="form-control" onchange="updateAssetNames()">
                        <?php foreach ($assetTypes ?? [] as $type): ?>
                            <option value="<?= htmlspecialchars($type['name']) ?>" 
                                data-type="<?= htmlspecialchars($type['name']) ?>">
                                <?= htmlspecialchars($type['display_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Asset Name</label>
                    <select name="market_id" id="assetName" class="form-control" onchange="updateTradingViewChart()">
                        <?php foreach ($allMarkets ?? [] as $m): ?>
                            <option value="<?= $m['id'] ?>" 
                                data-type="<?= htmlspecialchars($m['asset_type'] ?? $m['type']) ?>"
                                data-symbol="<?= htmlspecialchars($m['symbol']) ?>"
                                data-price="<?= $m['price'] ?? 0 ?>"
                                data-tradingview="<?= htmlspecialchars($m['symbol_tradingview'] ?? '') ?>">
                                <?= htmlspecialchars($m['display_name'] ?? $m['symbol']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Leverage:</label>
                    <div class="leverage-grid">
                        <?php 
                        $leverages = [5, 2, 3, 4, 10, 25, 30, 40, 50, 100];
                        foreach ($leverages as $lev): 
                        ?>
                            <button type="button" class="leverage-btn <?= $lev === 5 ? 'active' : '' ?>" data-leverage="<?= $lev ?>" onclick="setLeverage(<?= $lev ?>)"><?= $lev ?>x</button>
                        <?php endforeach; ?>
                    </div>
                    <div class="leverage-slider-container">
                        <input type="range" id="leverageSlider" class="leverage-slider" min="0.1" max="100" step="0.1" value="5" oninput="setLeverageFromSlider(this.value)">
                        <span class="leverage-value-display" id="leverageDisplay">5x</span>
                    </div>
                    <input type="hidden" name="leverage" id="leverageValue" value="5">
                </div>

                <div class="form-group">
                    <label>Trade Duration (in minutes)</label>
                    <select name="duration" id="tradeDuration" class="form-control">
                        <option value="1">1 Minute</option>
                        <option value="5">5 Minutes</option>
                        <option value="15">15 Minutes</option>
                        <option value="30">30 Minutes</option>
                        <option value="60">1 Hour</option>
                        <option value="240">4 Hours</option>
                        <option value="1440">1 Day</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" name="amount" id="tradeAmount" class="form-control" placeholder="Enter trade amount" step="0.01" min="10" required oninput="validateTradeAmount()">
                    <div id="balanceError" style="color: #ff4757; font-size: 13px; margin-top: 8px; font-weight: 500; display: none; padding: 10px; background: rgba(255, 71, 87, 0.1); border-radius: 6px; border: 1px solid rgba(255, 71, 87, 0.2);"></div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>Take Profit:</label>
                        <input type="number" name="take_profit" id="takeProfit" class="form-control" placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="form-group half">
                        <label>Stop Loss:</label>
                        <input type="number" name="stop_loss" id="stopLoss" class="form-control" placeholder="0.00" step="0.01" min="0">
                    </div>
                </div>

                <div class="trade-buttons">
                    <button type="button" class="trade-btn buy" onclick="showConfirmModal('buy')">Buy</button>
                    <button type="button" class="trade-btn sell" onclick="showConfirmModal('sell')">Sell</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card trading-chart-card">
        <div class="card-header collapsible" onclick="toggleCard('chartCard')">
            <h3 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
                <?= t('live_trading_chart') ?>
            </h3>
            <div class="card-actions">
                <button class="icon-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg></button>
                <svg class="collapse-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        <div class="card-body" id="chartCard">
            <div class="tradingview-widget-container">
                <div id="tradingview_chart"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Update the modal button to submit the form instead of custom logic
document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById('confirmTradeBtn');
    if (confirmBtn) {
        confirmBtn.onclick = function() {
            const form = document.getElementById('tradeForm');
            if (form) form.submit();
        };
    }
});
</script>


<div class="grid-2">
    <div class="card market-data-card">
        <div class="card-header">
            <h3 class="card-title">Cryptocurrency Market</h3>
            <div class="card-actions">
                <button class="icon-btn expand-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg></button>
                <button class="icon-btn collapse-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
            </div>
        </div>
        <div class="card-body market-table-container">
            <table class="market-table" id="crypto-table">
                <thead>
                    <tr>
                        <th class="sortable" onclick="sortCryptoTable('name')">SYMBOL / NAME</th>
                        <th class="sortable" onclick="sortCryptoTable('mktCap')">MKT CAP</th>
                        <th class="sortable" onclick="sortCryptoTable('fdMktCap')">FD MKT CAP</th>
                        <th class="sortable" onclick="sortCryptoTable('price')">PRICE</th>
                        <th class="sortable" onclick="sortCryptoTable('availCoins')">AVAIL COINS</th>
                        <th class="sortable" onclick="sortCryptoTable('totalCoins')">TOTAL COINS</th>
                    </tr>
                </thead>
                <tbody id="crypto-market-body">
                    <tr><td colspan="6" class="text-center">Loading cryptocurrency data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card market-data-card">
        <div class="card-header">
            <h3 class="card-title">Stock Market Data</h3>
            <div class="card-actions">
                <button class="icon-btn expand-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg></button>
                <button class="icon-btn collapse-btn"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
            </div>
        </div>
        <div class="card-body market-table-container">
            <table class="market-table" id="stock-table">
                <thead>
                    <tr>
                        <th class="sortable" onclick="sortStockTable('name')">STOCK NAME</th>
                        <th class="sortable" onclick="sortStockTable('value')">VALUE</th>
                        <th class="sortable" onclick="sortStockTable('change')">CHANGE</th>
                        <th class="sortable" onclick="sortStockTable('changePercent')">CHANGE %</th>
                        <th class="sortable" onclick="sortStockTable('open')">OPEN</th>
                        <th class="sortable" onclick="sortStockTable('high')">HIGH</th>
                        <th class="sortable" onclick="sortStockTable('low')">LOW</th>
                    </tr>
                </thead>
                <tbody id="stock-market-body">
                    <tr><td colspan="7" class="text-center">Loading stock market data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- KYC Verification Modal -->
<div id="kycVerificationModal" class="kyc-verification-modal" style="display: none;">
    <div class="kyc-verification-overlay"></div>
    <div class="kyc-verification-content">
        <div class="kyc-verification-icon">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#ff9800" stroke-width="1.5">
                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 100-16 8 8 0 000 16zm0-9a1 1 0 011 1v4a1 1 0 11-2 0v-4a1 1 0 011-1zm0-3a1 1 0 110-2 1 1 0 010 2z"></path>
            </svg>
        </div>
        <h2 class="kyc-verification-title">Complete Identity Verification</h2>
        <p class="kyc-verification-message">
            To unlock full trading features and access advanced tools, please complete your identity verification (KYC) process.
        </p>
        <div class="kyc-verification-info">
            <ul>
                <li>✓ Quick and easy process</li>
                <li>✓ Secure document upload</li>
                <li>✓ Admin approval within 24 hours</li>
                <li>✓ Full access to all trading features</li>
            </ul>
        </div>
        <div class="kyc-verification-buttons">
            <button type="button" class="kyc-verification-btn verify-now" onclick="window.location.href='/kyc/verify'">
                Verify Now
            </button>
            <button type="button" class="kyc-verification-btn verify-later" onclick="closeKYCModal()">
                I'll Do It Later
            </button>
        </div>
    </div>
</div>

<div id="tradeConfirmModal" class="trade-confirm-modal" style="display: none;">
    <div class="trade-confirm-overlay"></div>
    <div class="trade-confirm-content">
        <div class="trade-confirm-icon">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ff9800" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <h2 class="trade-confirm-title">Confirm <span id="confirmSideText">BUY</span> Trade</h2>
        <p class="trade-confirm-message">Review your trade details before execution.</p>
        <div class="modal-guide" style="background: rgba(0, 212, 170, 0.05); border: 1px solid rgba(0, 212, 170, 0.1); border-radius: 8px; padding: 15px; margin-bottom: 25px; text-align: left;">
            <ul style="margin: 0; padding-left: 20px; color: rgba(255, 255, 255, 0.6); font-size: 13px;">
                <li style="margin-bottom: 5px;"><strong>Amount:</strong> <span id="confirmAmountDisplay" style="color: #00D4AA;">$0.00</span></li>
                <li style="margin-bottom: 5px;"><strong>Leverage:</strong> <span id="confirmLeverageDisplay" style="color: #00D4AA;">1x</span></li>
                <li style="margin-bottom: 0;"><strong>Duration:</strong> <span id="confirmDurationDisplay" style="color: #00D4AA;">1 min</span></li>
            </ul>
        </div>
        <div class="trade-confirm-buttons">
            <button type="button" onclick="closeConfirmModal()" class="trade-confirm-btn confirm-cancel">No, Cancel</button>
            <button type="button" id="confirmTradeBtn" class="trade-confirm-btn confirm-buy">Yes, BUY Now</button>
        </div>
    </div>
</div>

<style>
#systemNotification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    animation: slideIn 0.3s ease-out;
}

#systemNotification.show {
    display: block !important;
}

#systemNotification.hide {
    animation: slideOut 0.3s ease-out forwards;
}

@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

.notification-content {
    background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%);
    border: 1px solid rgba(0, 212, 170, 0.3);
    border-radius: 8px;
    padding: 16px 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-width: 300px;
    max-width: 400px;
}

.notification-text {
    color: #1a1a1a;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.4;
}

.notification-close-btn {
    background: none;
    border: none;
    color: #999;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s;
}

.notification-close-btn:hover {
    color: #333;
}

.trade-confirm-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.trade-confirm-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
}
.trade-confirm-content {
    position: relative;
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 35px 45px;
    text-align: center;
    max-width: 520px;
    width: 95%;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}
.trade-confirm-icon {
    margin-bottom: 20px;
}
.trade-confirm-title {
    font-size: 26px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--text-primary);
}
.trade-confirm-message {
    color: var(--text-secondary);
    margin-bottom: 30px;
    font-size: 14px;
}
.trade-confirm-buttons {
    display: flex;
    gap: 15px;
    justify-content: space-between;
    margin-top: 25px;
    width: 100%;
}
.trade-confirm-btn {
    padding: 12px 28px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    flex: 1;
}
.confirm-buy {
    background: var(--accent-primary);
    color: #000;
}
.confirm-buy:hover {
    background: #00e6b8;
}
.confirm-sell {
    background: #dc3545;
    color: #fff;
}
.confirm-sell:hover {
    background: #c82333;
}
.confirm-cancel {
    background: #6c757d;
    color: #fff;
}
.confirm-cancel:hover {
    background: #5a6268;
}
.market-list {
    max-height: 280px;
    overflow-y: auto;
}
.market-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid var(--border-color);
    text-decoration: none;
    transition: background 0.2s;
}
.market-item:hover {
    background: rgba(255, 255, 255, 0.05);
}
.market-item:last-child {
    border-bottom: none;
}
.market-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.market-symbol {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}
.market-name {
    font-size: 12px;
    color: var(--text-secondary);
}
.market-price {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
}
.market-price .price {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}
.market-price .change {
    font-size: 12px;
    font-weight: 500;
}
.market-price .change.positive {
    color: var(--accent-primary);
}
.market-price .change.negative {
    color: #dc3545;
}
.p-4 {
    padding: 20px;
}

.leverage-slider-container {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-top: 12px;
    padding: 15px;
    background: rgba(0, 212, 170, 0.05);
    border-radius: 8px;
    border: 1px solid rgba(0, 212, 170, 0.1);
}

.leverage-slider {
    flex: 1;
    height: 6px;
    border-radius: 3px;
    background: linear-gradient(to right, rgba(0, 212, 170, 0.2), rgba(0, 212, 170, 0.4));
    outline: none;
    -webkit-appearance: none;
    appearance: none;
    cursor: pointer;
}

.leverage-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--accent-primary);
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 212, 170, 0.4);
    transition: all 0.2s;
}

.leverage-slider::-webkit-slider-thumb:hover {
    transform: scale(1.2);
    box-shadow: 0 4px 12px rgba(0, 212, 170, 0.6);
}

.leverage-slider::-moz-range-thumb {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--accent-primary);
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 8px rgba(0, 212, 170, 0.4);
    transition: all 0.2s;
}

.leverage-slider::-moz-range-thumb:hover {
    transform: scale(1.2);
    box-shadow: 0 4px 12px rgba(0, 212, 170, 0.6);
}

.leverage-value-display {
    font-weight: 600;
    color: var(--accent-primary);
    font-size: 16px;
    min-width: 45px;
    text-align: right;
}

.kyc-verification-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9998;
    display: flex;
    align-items: center;
    justify-content: center;
}

.kyc-verification-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
}

.kyc-verification-content {
    position: relative;
    background: linear-gradient(135deg, #0f1822 0%, #1a2a3a 100%);
    border-radius: 16px;
    padding: 50px 45px;
    text-align: center;
    max-width: 520px;
    width: 95%;
    max-height: 85vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 212, 170, 0.2);
    border: 1px solid rgba(0, 212, 170, 0.3);
}

.kyc-verification-icon {
    margin-bottom: 30px;
    display: flex;
    justify-content: center;
}

.kyc-verification-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--text-primary);
    background: linear-gradient(135deg, var(--accent-primary), #00d4aa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.kyc-verification-message {
    color: var(--text-secondary);
    margin-bottom: 30px;
    font-size: 15px;
    line-height: 1.6;
}

.kyc-verification-info {
    background: rgba(0, 212, 170, 0.08);
    border: 1px solid rgba(0, 212, 170, 0.2);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    text-align: left;
}

.kyc-verification-info ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.kyc-verification-info li {
    padding: 10px 0;
    color: var(--text-primary);
    font-size: 14px;
    font-weight: 500;
}

.kyc-verification-info li:before {
    content: '✓ ';
    color: var(--accent-primary);
    font-weight: bold;
    margin-right: 8px;
}

.kyc-verification-buttons {
    display: flex;
    gap: 15px;
    justify-content: space-between;
    margin-top: 30px;
    width: 100%;
}

.kyc-verification-btn {
    padding: 14px 28px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
}

.verify-now {
    background: linear-gradient(135deg, var(--accent-primary), #00e6b8);
    color: #000;
}

.verify-now:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 212, 170, 0.4);
}

.verify-later {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.verify-later:hover {
    background: rgba(255, 255, 255, 0.15);
}
</style>

<script src="https://s3.tradingview.com/tv.js"></script>
<script>
// System Notification Data
const mockNotifications = <?php echo json_encode($mockNotifications); ?>;
let notificationIndex = 0;
let notificationTimer = null;

function getRandomNotification() {
    const notification = mockNotifications[notificationIndex % mockNotifications.length];
    notificationIndex++;
    return notification;
}

function showNotification(data) {
    const notifElement = document.getElementById('systemNotification');
    const textElement = notifElement.querySelector('.notification-text');
    
    textElement.innerHTML = `Someone from <strong>${data.country}</strong> has ${data.action} <strong>$${data.amount.toLocaleString()}</strong>`;
    
    notifElement.classList.remove('hide');
    notifElement.classList.add('show');
    notifElement.style.display = 'block';
    
    // Auto hide after 5 seconds
    clearTimeout(notificationTimer);
    notificationTimer = setTimeout(() => {
        closeNotification();
    }, 5000);
}

function closeNotification() {
    const notifElement = document.getElementById('systemNotification');
    notifElement.classList.remove('show');
    notifElement.classList.add('hide');
    setTimeout(() => {
        notifElement.style.display = 'none';
    }, 300);
}

// Start showing notifications every 10 seconds
function startNotifications() {
    // Show first notification immediately
    showNotification(getRandomNotification());
    
    // Then show every 10 seconds
    setInterval(() => {
        showNotification(getRandomNotification());
    }, 10000);
}

// Start notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        startNotifications();
    }, 1000);
    // Load balance visibility preferences
    loadBalanceVisibilityPreferences();
    // Show KYC modal if user not verified
    checkAndShowKYCModal();
});

function checkAndShowKYCModal() {
    const isVerified = <?= $isVerified ? 'true' : 'false' ?>;
    if (!isVerified) {
        const kycModal = document.getElementById('kycVerificationModal');
        if (kycModal) {
            kycModal.style.display = 'flex';
        }
    }
}

function closeKYCModal() {
    const kycModal = document.getElementById('kycVerificationModal');
    if (kycModal) {
        kycModal.style.display = 'none';
    }
}

// Store original balance values
let originalBalanceValues = {
    dashboard: '',
    liveTrading: ''
};

// Toggle balance visibility function
function toggleBalanceVisibility(location) {
    const settings = JSON.parse(localStorage.getItem('balanceVisibility') || '{"dashboard": true, "liveTrading": true, "tradePage": true}');
    
    if (location === 'dashboard') {
        settings.dashboard = !settings.dashboard;
        const balanceEl = document.getElementById('dashboardTotalBalanceValue');
        const eyeIcon = document.getElementById('dashboardBalanceEyeIcon');
        if (balanceEl) {
            if (settings.dashboard) {
                balanceEl.textContent = originalBalanceValues.dashboard;
                eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            } else {
                originalBalanceValues.dashboard = balanceEl.textContent;
                balanceEl.textContent = '***';
                eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            }
        }
    } else if (location === 'liveTrading') {
        settings.liveTrading = !settings.liveTrading;
        const balanceEl = document.getElementById('dashboardLiveTradeBalanceValue');
        const eyeIcon = document.getElementById('liveTradeBalanceEyeIcon');
        if (balanceEl) {
            if (settings.liveTrading) {
                balanceEl.textContent = originalBalanceValues.liveTrading;
                eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            } else {
                originalBalanceValues.liveTrading = balanceEl.textContent;
                balanceEl.textContent = '***';
                eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            }
        }
    }
    localStorage.setItem('balanceVisibility', JSON.stringify(settings));
}

// Load saved balance visibility preferences
function loadBalanceVisibilityPreferences() {
    const settings = JSON.parse(localStorage.getItem('balanceVisibility') || '{"dashboard": true, "liveTrading": true, "tradePage": true}');
    
    // Apply dashboard setting
    const dashboardBalance = document.getElementById('dashboardTotalBalanceValue');
    const dashboardEye = document.getElementById('dashboardBalanceEyeIcon');
    if (dashboardBalance) {
        originalBalanceValues.dashboard = dashboardBalance.textContent;
        if (!settings.dashboard) {
            dashboardBalance.textContent = '***';
            if (dashboardEye) {
                dashboardEye.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            }
        }
    }
    
    // Apply live trading setting
    const liveBalance = document.getElementById('dashboardLiveTradeBalanceValue');
    const liveEye = document.getElementById('liveTradeBalanceEyeIcon');
    if (liveBalance) {
        originalBalanceValues.liveTrading = liveBalance.textContent;
        if (!settings.liveTrading) {
            liveBalance.textContent = '***';
            if (liveEye) {
                liveEye.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            }
        }
    }
}

let tvChart = null;
let currentSymbol = 'BINANCE:BTCUSDT';

// Symbol mapping for TradingView
const symbolMap = {
    'BTCUSDT': 'BINANCE:BTCUSDT',
    'ETHUSDT': 'BINANCE:ETHUSDT',
    'BNBUSDT': 'BINANCE:BNBUSDT',
    'XRPUSDT': 'BINANCE:XRPUSDT',
    'ADAUSDT': 'BINANCE:ADAUSDT',
    'LINKUSDT': 'BINANCE:LINKUSDT',
    'DOGEUSDT': 'BINANCE:DOGEUSDT',
    'LTCUSDT': 'BINANCE:LTCUSDT',
    'TRXUSDT': 'BINANCE:TRXUSDT',
    'FTMUSDT': 'BINANCE:FTMUSDT',
    'FTMUSD': 'BINANCE:FTMUSDT',
    'AUDUSD': 'BINANCE:AUDUSD',
    'USDAUD': 'BINANCE:AUDUSD',
    'AUDJPY': 'BINANCE:AUDJPY',
    'GOLDUSDT': 'BINANCE:GOLDUSDT',
    'XAUUSD': 'BINANCE:XAUUSD',
    'OILUSD': 'BINANCE:OILUSD',
    'USOIL': 'BINANCE:USOIL'
};

function getProperTradingViewSymbol(symbol) {
    if (!symbol) return 'BINANCE:BTCUSDT';
    
    // Check if exact match exists
    if (symbolMap[symbol]) {
        return symbolMap[symbol];
    }
    
    // If symbol already has exchange prefix, use it
    if (symbol.includes(':')) {
        return symbol;
    }
    
    // Default to BINANCE prefix
    return 'BINANCE:' + symbol;
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize TradingView chart
    initTradingViewChart();
    
    // Initial sync and then every 30 seconds
    syncDashboardPrices();
    setInterval(syncDashboardPrices, 30000);
    
    // Initialize market info with current selection
    setTimeout(updateMarketInfo, 100);
    
    // Update chart when asset type changes
    const assetTypeSelect = document.getElementById('assetType');
    if (assetTypeSelect) {
        assetTypeSelect.addEventListener('change', function() {
            updateAssetNames();
        });
    }
    
    // Update chart when asset name changes
    const assetSelect = document.getElementById('assetName');
    if (assetSelect) {
        assetSelect.addEventListener('change', function() {
            updateTradingViewChart();
        });
    }
});

function updateAssetNames() {
    const assetType = document.getElementById('assetType').value;
    const assetNameSelect = document.getElementById('assetName');
    const options = assetNameSelect.querySelectorAll('option');
    
    let firstVisible = null;
    options.forEach(option => {
        const optionType = option.getAttribute('data-type');
        if (optionType === assetType) {
            option.style.display = '';
            if (!firstVisible) firstVisible = option;
        } else {
            option.style.display = 'none';
        }
    });
    
    if (firstVisible) {
        assetNameSelect.value = firstVisible.value;
        updateMarketInfo();
        updateTradingViewChart();
    }
}

function initTradingViewChart() {
    if (!tvChart) {
        createTradingViewWidget();
    }
}

function createTradingViewWidget() {
    try {
        if (typeof TradingView === 'undefined') {
            console.log('TradingView library not loaded yet');
            setTimeout(createTradingViewWidget, 500);
            return;
        }
        
        tvChart = new TradingView.widget({
            "autosize": true,
            "symbol": currentSymbol,
            "interval": "1",
            "timezone": "Etc/UTC",
            "theme": "dark",
            "style": "1",
            "locale": "en",
            "toolbar_bg": "#1a2332",
            "enable_publishing": false,
            "allow_symbol_change": true,
            "container_id": "tradingview_chart",
            "hide_side_toolbar": false,
            "studies": ["MASimple@tv-basicstudies", "RSI@tv-basicstudies"],
            "show_popup_button": true,
            "popup_width": "1000",
            "popup_height": "650"
        });
    } catch (e) {
        console.log('TradingView widget creation error:', e);
    }
}

function capitalizeAssetType(type) {
    if (!type) return 'Crypto';
    const typeMap = {
        'crypto': 'Crypto',
        'forex': 'Forex',
        'stocks': 'Stocks',
        'commodities': 'Commodities',
        'indices': 'Indices'
    };
    return typeMap[type.toLowerCase()] || type.charAt(0).toUpperCase() + type.slice(1);
}

function updateMarketInfo() {
    const assetNameSelect = document.getElementById('assetName');
    if (!assetNameSelect) return;
    
    const selectedOption = assetNameSelect.options[assetNameSelect.selectedIndex];
    if (!selectedOption) return;
    
    const assetTypeRaw = selectedOption.getAttribute('data-type') || 'crypto';
    const assetType = capitalizeAssetType(assetTypeRaw);
    const assetName = selectedOption.textContent.split('(')[0].trim() || 'BTC/USD';
    const assetPriceRaw = selectedOption.getAttribute('data-price') || '0';
    const assetPrice = parseFloat(assetPriceRaw);
    
    const currentAssetTypeEl = document.getElementById('dashboardCurrentAssetType');
    const currentAssetNameEl = document.getElementById('dashboardCurrentAssetName');
    const currentPriceEl = document.getElementById('dashboardCurrentPrice');
    
    if (currentAssetTypeEl) currentAssetTypeEl.textContent = assetType;
    if (currentAssetNameEl) currentAssetNameEl.textContent = assetName;
    if (currentPriceEl) {
        if (assetPrice > 0 && isFinite(assetPrice)) {
            currentPriceEl.textContent = '$' + assetPrice.toFixed(2);
        } else {
            currentPriceEl.textContent = '$0.00';
        }
    }
}

// Real-time price sync for dashboard
async function syncDashboardPrices() {
    try {
        const response = await fetch('/api/prices');
        const data = await response.json();
        
        if (data.success && Array.isArray(data.data)) {
            const assetNameSelect = document.getElementById('assetName');
            if (!assetNameSelect) return;
            
            let hasUpdates = false;
            data.data.forEach(priceData => {
                if (priceData && priceData.symbol && priceData.price) {
                    const options = assetNameSelect.querySelectorAll('option');
                    options.forEach(option => {
                        const optionSymbol = option.getAttribute('data-symbol');
                        if (optionSymbol === priceData.symbol) {
                            const newPrice = parseFloat(priceData.price);
                            if (newPrice > 0 && isFinite(newPrice)) {
                                option.setAttribute('data-price', newPrice.toString());
                                hasUpdates = true;
                            }
                        }
                    });
                }
            });
            
            if (hasUpdates) {
                updateMarketInfo();
            }
        }
    } catch (e) {
        console.log('Price sync failed:', e);
    }
}

function updateTradingViewChart() {
    const assetNameSelect = document.getElementById('assetName');
    if (!assetNameSelect) return;
    
    const selectedOption = assetNameSelect.options[assetNameSelect.selectedIndex];
    if (!selectedOption) return;
    
    updateMarketInfo();
    
    // Get tradingview symbol - use stored value or fallback to symbol mapping
    let tradingViewSymbol = selectedOption.getAttribute('data-tradingview');
    if (!tradingViewSymbol || tradingViewSymbol.trim() === '') {
        const symbol = selectedOption.getAttribute('data-symbol') || selectedOption.textContent;
        tradingViewSymbol = getProperTradingViewSymbol(symbol);
    }
    
    if (tradingViewSymbol && tradingViewSymbol !== currentSymbol) {
        currentSymbol = tradingViewSymbol;
        const container = document.getElementById('tradingview_chart');
        if (container) {
            container.innerHTML = '';
        }
        tvChart = null;
        setTimeout(createTradingViewWidget, 100);
    }
}

function toggleCard(cardId) {
    const card = document.getElementById(cardId);
    const header = card.previousElementSibling;
    card.classList.toggle('collapsed');
    header.classList.toggle('collapsed');
}

// Real-time balance validation
const walletBalance = <?= $wallet['balance'] ?? 0 ?>;
const marginUsed = <?= $wallet['margin_used'] ?? 0 ?>;

function setLeverage(value) {
    const numValue = parseFloat(value);
    document.getElementById('leverageValue').value = numValue;
    document.getElementById('leverageSlider').value = numValue;
    document.getElementById('leverageDisplay').textContent = numValue + 'x';
    document.querySelectorAll('.leverage-btn').forEach(btn => {
        const btnValue = parseInt(btn.dataset.leverage);
        btn.classList.toggle('active', btnValue === numValue);
    });
    validateTradeAmount();
}

function setLeverageFromSlider(value) {
    const numValue = parseFloat(value).toFixed(1);
    const numValueFloat = parseFloat(numValue);
    document.getElementById('leverageValue').value = numValueFloat;
    document.getElementById('leverageDisplay').textContent = numValueFloat + 'x';
    document.querySelectorAll('.leverage-btn').forEach(btn => {
        const btnValue = parseInt(btn.dataset.leverage);
        btn.classList.toggle('active', btnValue === numValueFloat);
    });
    validateTradeAmount();
}

function validateTradeAmount() {
    const amountInput = document.getElementById('tradeAmount');
    if (!amountInput) return;
    
    const leverage = parseFloat(document.getElementById('leverageValue').value) || 5;
    const amount = parseFloat(amountInput.value) || 0;
    const balanceErrorDiv = document.getElementById('balanceError');
    
    // Find buttons - try multiple selectors
    let buyBtn = document.querySelector('.trade-buttons .buy') || document.querySelector('button.buy');
    let sellBtn = document.querySelector('.trade-buttons .sell') || document.querySelector('button.sell');
    
    if (amount <= 0) {
        if (balanceErrorDiv) balanceErrorDiv.style.display = 'none';
        if (buyBtn) { buyBtn.disabled = true; buyBtn.style.opacity = '0.5'; }
        if (sellBtn) { sellBtn.disabled = true; sellBtn.style.opacity = '0.5'; }
        return;
    }
    
    const marketSelect = document.getElementById('assetName');
    if (!marketSelect) return;
    
    const selectedOption = marketSelect.options[marketSelect.selectedIndex];
    const symbol = selectedOption ? selectedOption.getAttribute('data-symbol') : '';
    
    const availableBalance = walletBalance - marginUsed;
    const maxTradeAmount = availableBalance * leverage;
    const minTradeAmount = 10;
    
    let estimatedPrice = 1;
    if (symbol && symbol.includes('BTC')) estimatedPrice = 50000;
    else if (symbol && symbol.includes('ETH')) estimatedPrice = 3000;
    else if (symbol && symbol.includes('GOLD')) estimatedPrice = 2000;
    else if (symbol && symbol.includes('OIL')) estimatedPrice = 100;
    
    const marginRequired = (amount * estimatedPrice) / leverage;
    
    let hasError = false;
    let errorMessage = '';
    
    if (amount < minTradeAmount) {
        errorMessage = '⚠ Minimum trade amount is $' + minTradeAmount;
        hasError = true;
    } else if (marginRequired > availableBalance) {
        errorMessage = '⚠ INSUFFICIENT BALANCE! Required: $' + marginRequired.toFixed(2) + ' | Available: $' + availableBalance.toFixed(2);
        hasError = true;
    } else if (amount > maxTradeAmount) {
        errorMessage = '⚠ Trade amount exceeds max at ' + leverage + 'x leverage. Max: $' + maxTradeAmount.toFixed(2);
        hasError = true;
    }
    
    if (balanceErrorDiv) {
        if (hasError) {
            balanceErrorDiv.textContent = errorMessage;
            balanceErrorDiv.style.display = 'block';
        } else {
            balanceErrorDiv.style.display = 'none';
        }
    }
    
    if (hasError) {
        if (buyBtn) { buyBtn.disabled = true; buyBtn.style.opacity = '0.5'; }
        if (sellBtn) { sellBtn.disabled = true; sellBtn.style.opacity = '0.5'; }
    } else {
        if (buyBtn) { buyBtn.disabled = false; buyBtn.style.opacity = '1'; }
        if (sellBtn) { sellBtn.disabled = false; sellBtn.style.opacity = '1'; }
    }
}

function showConfirmModal(action) {
    const amountInput = document.getElementById('tradeAmount');
    const leverage = parseFloat(document.getElementById('leverageValue').value) || 5;
    const amount = parseFloat(amountInput.value) || 0;
    const balanceErrorDiv = document.getElementById('balanceError');
    
    if (!amount || amount <= 0) {
        balanceErrorDiv.textContent = '⚠ Please enter a valid trade amount';
        balanceErrorDiv.style.display = 'block';
        return;
    }
    
    const availableBalance = walletBalance - marginUsed;
    const marketSelect = document.getElementById('assetName');
    const selectedOption = marketSelect.options[marketSelect.selectedIndex];
    const symbol = selectedOption ? selectedOption.getAttribute('data-symbol') : '';
    
    let estimatedPrice = 1;
    if (symbol.includes('BTC')) estimatedPrice = 50000;
    else if (symbol.includes('ETH')) estimatedPrice = 3000;
    else if (symbol.includes('GOLD')) estimatedPrice = 2000;
    else if (symbol.includes('OIL')) estimatedPrice = 100;
    
    const marginRequired = (amount * estimatedPrice) / leverage;
    
    if (marginRequired > availableBalance) {
        balanceErrorDiv.textContent = '⚠ Insufficient balance! Required: $' + marginRequired.toFixed(2) + ' | Available: $' + availableBalance.toFixed(2);
        balanceErrorDiv.style.display = 'block';
        return;
    }
    
    // Modal logic would go here
    balanceErrorDiv.style.display = 'none';
}

window.cryptoData = [];
window.stockData = [
    { symbol: 'NASDAQ', name: 'NASDAQ', value: 97.46, change: 2.10, changePercent: 2.20, open: 95.36, high: 97.72, low: 95 },
    { symbol: 'AAPL', name: 'APPLE STOCK', value: 270.97, change: -2.70, changePercent: -0.99, open: 272.86, high: 273.88, low: 270 },
    { symbol: 'GOOGL', name: 'GOOGL', value: 309.78, change: 2.62, changePercent: 0.89, open: 309.88, high: 310.13, low: 308 },
    { symbol: 'NVAX', name: 'NVAX NA', value: 6.89, change: 0.23, changePercent: 3.45, open: 6.71, high: 6.98, low: 6.80 },
    { symbol: 'AMZN', name: 'AMAZON', value: 228.43, change: 1.08, changePercent: 0.48, open: 228.61, high: 229.48, low: 227 },
    { symbol: 'FACEB', name: 'FACEBOOK', value: 1.54, change: 0.05, changePercent: 3.35, open: 1.50, high: 1.60, low: 1.50 },
    { symbol: 'TSLA', name: 'TESLA', value: 280.50, change: 5.50, changePercent: 2.00, open: 275.00, high: 285.00, low: 274.50 }
];

window.fetchCryptoData = function() {
    const url = '/api/crypto-prices';
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const cryptoList = [
                { symbol: 'BTC', name: 'Bitcoin', data: data['bitcoin'] },
                { symbol: 'ETH', name: 'Ethereum', data: data['ethereum'] },
                { symbol: 'USDT', name: 'Tether', data: data['tether'] },
                { symbol: 'BNB', name: 'Binance Coin', data: data['binancecoin'] },
                { symbol: 'XRP', name: 'Ripple', data: data['ripple'] },
                { symbol: 'SOL', name: 'Solana', data: data['solana'] },
                { symbol: 'ADA', name: 'Cardano', data: data['cardano'] },
                { symbol: 'DOT', name: 'Polkadot', data: data['polkadot'] }
            ];
            
            window.cryptoData = cryptoList.map(item => ({
                symbol: item.symbol,
                name: item.name,
                mktCap: item.data?.usd_market_cap || 0,
                fdMktCap: item.data?.usd_market_cap || 0,
                price: item.data?.usd || 0,
                availCoins: 'N/A',
                totalCoins: 'N/A'
            }));
            
            window.loadMarketDataNow();
            window.updateTicker(cryptoList);
        })
        .catch(err => {
            console.error('Error fetching crypto data:', err);
            window.loadMarketDataNow();
        });
};

window.updateTicker = function(cryptoList) {
    const tickerContent = document.getElementById('ticker-content');
    if (!tickerContent) return;
    
    let tickerItems = [];
    if (window.cryptoData && window.cryptoData.length > 0) {
        window.cryptoData.forEach(crypto => {
            if (crypto.price > 0) {
                const priceDisplay = crypto.price > 1 ? crypto.price.toFixed(2) : crypto.price.toFixed(6);
                tickerItems.push({
                    symbol: crypto.symbol,
                    price: priceDisplay
                });
            }
        });
    }
    
    // If no data yet, use placeholder values
    if (tickerItems.length === 0) {
        tickerItems = [
            { symbol: 'BTC', price: '0.00' },
            { symbol: 'ETH', price: '0.00' },
            { symbol: 'USDT', price: '0.00' },
            { symbol: 'BNB', price: '0.00' },
            { symbol: 'XRP', price: '0.00' },
            { symbol: 'SOL', price: '0.00' },
            { symbol: 'ADA', price: '0.00' },
            { symbol: 'DOT', price: '0.00' }
        ];
    }
    
    // Build HTML with ticker items
    let html = '';
    tickerItems.forEach(item => {
        html += '<span class="ticker-item" onclick="navigateToTrade(\'' + item.symbol + '\')" style="cursor: pointer;"><strong>' + item.symbol + ':</strong> $' + item.price + '</span>';
    });
    
    // Duplicate for continuous scroll effect
    html = html + html + html;
    tickerContent.innerHTML = html;
};

window.sortCryptoTable = function(column) {
    const tbody = document.getElementById('crypto-market-body');
    let rows = Array.from(tbody.querySelectorAll('tr'));
    let isAsc = !document.querySelector('#crypto-table th.sortable.active')?.dataset.asc;
    if (document.querySelector('#crypto-table th.sortable.active')) {
        document.querySelector('#crypto-table th.sortable.active').classList.remove('active');
    }
    rows.sort((a, b) => {
        let aVal = a.dataset[column] || (window.cryptoData.find(d => d.symbol === a.dataset.symbol)?.[column] ?? 0);
        let bVal = b.dataset[column] || (window.cryptoData.find(d => d.symbol === b.dataset.symbol)?.[column] ?? 0);
        aVal = isNaN(aVal) ? aVal : parseFloat(aVal);
        bVal = isNaN(bVal) ? bVal : parseFloat(bVal);
        return isAsc ? (aVal > bVal ? 1 : -1) : (aVal < bVal ? 1 : -1);
    });
    rows.forEach(row => tbody.appendChild(row));
    const th = event.target.closest('th');
    if (th) {
        th.classList.add('active');
        th.dataset.asc = isAsc;
    }
};

window.sortStockTable = function(column) {
    const tbody = document.getElementById('stock-market-body');
    let rows = Array.from(tbody.querySelectorAll('tr'));
    let isAsc = !document.querySelector('#stock-table th.sortable.active')?.dataset.asc;
    if (document.querySelector('#stock-table th.sortable.active')) {
        document.querySelector('#stock-table th.sortable.active').classList.remove('active');
    }
    rows.sort((a, b) => {
        let aVal = a.dataset[column] || (window.stockData.find(d => d.symbol === a.dataset.symbol)?.[column] ?? 0);
        let bVal = b.dataset[column] || (window.stockData.find(d => d.symbol === b.dataset.symbol)?.[column] ?? 0);
        aVal = isNaN(aVal) ? aVal : parseFloat(aVal);
        bVal = isNaN(bVal) ? bVal : parseFloat(bVal);
        return isAsc ? (aVal > bVal ? 1 : -1) : (aVal < bVal ? 1 : -1);
    });
    rows.forEach(row => tbody.appendChild(row));
    const th = event.target.closest('th');
    if (th) {
        th.classList.add('active');
        th.dataset.asc = isAsc;
    }
};

window.loadMarketDataNow = function() {
    const cryptoBody = document.getElementById('crypto-market-body');
    if (cryptoBody && window.cryptoData && window.cryptoData.length > 0) {
        let html = '';
        window.cryptoData.forEach(crypto => {
            if (crypto.price > 0) {
                let mktCapDisplay = crypto.mktCap >= 1e12 ? (crypto.mktCap / 1e12).toFixed(2) + 'T' : crypto.mktCap >= 1e9 ? (crypto.mktCap / 1e9).toFixed(2) + 'B' : crypto.mktCap >= 1e6 ? (crypto.mktCap / 1e6).toFixed(2) + 'M' : '0.00';
                let fdMktCapDisplay = crypto.fdMktCap >= 1e12 ? (crypto.fdMktCap / 1e12).toFixed(2) + 'T' : crypto.fdMktCap >= 1e9 ? (crypto.fdMktCap / 1e9).toFixed(2) + 'B' : crypto.fdMktCap >= 1e6 ? (crypto.fdMktCap / 1e6).toFixed(2) + 'M' : '0.00';
                html += '<tr data-symbol="' + crypto.symbol + '" data-name="' + crypto.name + '" data-price="' + crypto.price + '" data-mktCap="' + crypto.mktCap + '" data-fdMktCap="' + crypto.fdMktCap + '" onclick="navigateToTrade(\'' + crypto.symbol + '\')"><td class="market-symbol"><span style="display: flex; align-items: center; gap: 8px;"><span style="width: 24px; height: 24px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: var(--bg-dark);">' + crypto.symbol.charAt(0) + '</span><strong>' + crypto.symbol + '</strong></span></td><td>$' + mktCapDisplay + '</td><td>$' + fdMktCapDisplay + '</td><td class="positive">$' + crypto.price.toFixed(2) + '</td><td>' + crypto.availCoins + '</td><td>' + crypto.totalCoins + '</td></tr>';
            }
        });
        if (html) cryptoBody.innerHTML = html;
    }
    
    const stockBody = document.getElementById('stock-market-body');
    if (stockBody) {
        let html = '';
        window.stockData.forEach(stock => {
            const changeClass = stock.change < 0 ? 'negative' : 'positive';
            html += '<tr data-symbol="' + stock.symbol + '" data-name="' + stock.name + '" data-value="' + stock.value + '" data-change="' + stock.change + '" data-changePercent="' + stock.changePercent + '" onclick="navigateToTrade(\'' + stock.symbol + '\')"><td class="market-symbol"><span style="display: flex; align-items: center; gap: 8px;"><span style="width: 24px; height: 24px; background: var(--primary); border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: var(--bg-dark);">◆</span><strong>' + stock.name + '</strong></span></td><td>' + stock.value.toFixed(2) + '</td><td class="' + changeClass + '">' + (stock.change > 0 ? '+' : '') + stock.change.toFixed(2) + '</td><td class="' + changeClass + '">' + (stock.changePercent > 0 ? '+' : '') + stock.changePercent.toFixed(2) + '%</td><td>' + stock.open.toFixed(2) + '</td><td>' + stock.high.toFixed(2) + '</td><td>' + stock.low.toFixed(2) + '</td></tr>';
        });
        stockBody.innerHTML = html;
    }
};

// Fetch crypto data immediately and set up auto-refresh
window.fetchCryptoData = window.fetchCryptoData || function() {};
if (window.fetchCryptoData) {
    window.fetchCryptoData();
}

// Refresh every 30 seconds
setInterval(function() {
    if (window.fetchCryptoData) window.fetchCryptoData();
}, 30000);
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Dashboard';
include __DIR__ . '/../layouts/main.php';
?>
