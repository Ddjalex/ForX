<?php
ob_start();
$totalBalance = $wallet['balance'] ?? 0;
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="trade-action-bar">
    <a href="/dashboard/trades/history" class="action-btn trade-history">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        Trade History
    </a>
    <div class="action-buttons-right">
        <a href="/accounts" class="action-btn account">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Account
        </a>
        <a href="/wallet/deposit" class="action-btn deposit">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            Make Deposit $
        </a>
        <a href="/wallet/withdraw" class="action-btn withdraw">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 19 19 12"></polyline></svg>
            Withdraw Funds
        </a>
        <a href="mailto:support@tradeflowglobalex.com" class="action-btn mail">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            Mail Us
        </a>
        <a href="/accounts?tab=settings" class="action-btn settings">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            Settings
        </a>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="tradeConfirmModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeConfirmModal()"></div>
    <div class="modal-content">
        <div class="modal-icon">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                <circle cx="40" cy="40" r="36" stroke="#f5a623" stroke-width="3" fill="none"/>
                <text x="40" y="52" text-anchor="middle" fill="#f5a623" font-size="44" font-weight="bold">!</text>
            </svg>
        </div>
        <h2 id="confirmModalTitle">Confirm BUY Trade</h2>
        <p>Review your trade details before execution.</p>
        <div class="modal-guide">
            <ul>
                <li><strong>Amount:</strong> <span id="confirmAmountDisplay">$0.00</span></li>
                <li><strong>Leverage:</strong> <span id="confirmLeverageDisplay">1x</span></li>
                <li><strong>Duration:</strong> <span id="confirmDurationDisplay">1 min</span></li>
            </ul>
        </div>
        <div class="modal-buttons">
            <button type="button" class="btn btn-secondary" onclick="closeConfirmModal()">No, Cancel</button>
            <button type="button" id="confirmTradeBtn" class="btn btn-buy">Yes, BUY Now</button>
        </div>
    </div>
</div>

<div class="trading-layout">
    <div class="trading-panel">
        <div class="panel-header">
            <span class="panel-title">Live Trading</span>
            <div class="balance-display">
                <span class="balance-label">Balance:</span>
                <span class="balance-value">$<?= number_format(($wallet['balance'] ?? 0) - ($wallet['margin_used'] ?? 0), 2) ?></span>
            </div>
        </div>
        
        <div style="background: #0f1822; border-bottom: 1px solid #2d3a4f; padding: 12px; margin-bottom: 16px; border-radius: 4px;">
            <div style="font-size: 11px; color: #8899a6; text-transform: uppercase; font-weight: 600; margin-bottom: 8px;">Current Market Info</div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; font-size: 12px;">
                <div>
                    <span style="color: #8899a6;">Asset Type:</span>
                    <div style="color: #e0e0e0; font-weight: 600;" id="currentAssetType"><?= htmlspecialchars($market['asset_type'] ?? $market['type'] ?? 'Crypto') ?></div>
                </div>
                <div>
                    <span style="color: #8899a6;">Asset Name:</span>
                    <div style="color: #e0e0e0; font-weight: 600;" id="currentAssetName"><?= htmlspecialchars($market['display_name'] ?? $market['symbol']) ?></div>
                </div>
                <div>
                    <span style="color: #8899a6;">Current Price:</span>
                    <div style="color: #10B981; font-weight: 700;" id="currentPrice">$<?= number_format($market['price'] ?? 0, 2) ?></div>
                </div>
            </div>
        </div>

        <form method="POST" action="/dashboard/trade/order" id="tradeForm">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="side" id="orderSide" value="buy">
            <input type="hidden" name="order_type" id="orderType" value="market">

            <div class="form-group">
                <label class="form-label">Asset Type</label>
                <select class="form-control" name="asset_type" id="assetType" onchange="filterAssetNamesByType(); updateMarketInfo();">
                    <?php
                    $assetTypesByGroup = [];
                    foreach ($allMarkets ?? [] as $m) {
                        $type = $m['asset_type'] ?? $m['type'] ?? 'Other';
                        if (!isset($assetTypesByGroup[$type])) {
                            $assetTypesByGroup[$type] = [];
                        }
                        $assetTypesByGroup[$type][] = $m;
                    }
                    foreach ($assetTypesByGroup as $type => $markets):
                    ?>
                        <option value="<?= htmlspecialchars($type) ?>" 
                            data-type="<?= htmlspecialchars($type) ?>"
                            <?= ($market['asset_type'] ?? $market['type'] ?? '') === $type ? 'selected' : '' ?>>
                            <?= htmlspecialchars(ucfirst($type)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Asset Name</label>
                <select class="form-control" name="market_id" id="assetName" onchange="updateMarketInfo(); updateTradingViewChart();">
                    <?php foreach ($allMarkets ?? [] as $m): ?>
                        <option value="<?= $m['id'] ?>" 
                            data-type="<?= htmlspecialchars($m['asset_type'] ?? $m['type']) ?>"
                            data-symbol="<?= htmlspecialchars($m['symbol']) ?>"
                            data-display="<?= htmlspecialchars($m['display_name'] ?? $m['symbol']) ?>"
                            data-price="<?= $m['price'] ?? 0 ?>"
                            data-tradingview="<?= htmlspecialchars($m['symbol_tradingview'] ?? '') ?>"
                            <?= $m['id'] == $market['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['display_name'] ?? $m['symbol']) ?> (<?= htmlspecialchars($m['symbol_tradingview'] ?? $m['symbol']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Leverage</label>
                <div class="leverage-grid">
                    <?php 
                    $leverages = [5, 2, 3, 4, 10, 25, 30, 40, 50, 100];
                    foreach ($leverages as $lev): 
                    ?>
                        <button type="button" class="leverage-btn <?= $lev === 5 ? 'active' : '' ?>" data-leverage="<?= $lev ?>" onclick="setLeverage(<?= $lev ?>)"><?= $lev ?>x</button>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="leverage" id="leverageValue" value="5">
            </div>

            <div class="leverage-slider-container">
                <input type="range" class="leverage-slider" id="leverageSlider" min="0.1" max="100" step="0.1" value="5" oninput="setLeverageFromSlider(this.value)">
                <span class="leverage-value-display" id="leverageDisplay">5x</span>
            </div>

            <div class="form-group">
                <label class="form-label">Trade Duration (in minutes)</label>
                <select class="form-control" name="duration" id="tradeDuration">
                    <option value="1">1 Minute</option>
                    <option value="5">5 Minutes</option>
                    <option value="15">15 Minutes</option>
                    <option value="30">30 Minutes</option>
                    <option value="60">1 Hour</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Amount</label>
                <input type="number" name="amount" id="tradeAmount" class="form-control" step="0.01" min="10" placeholder="Enter trade amount" required oninput="validateTradeAmount()">
                <div id="balanceError" style="color: #ff4757; font-size: 13px; margin-top: 8px; font-weight: 500; display: none; padding: 10px; background: rgba(255, 71, 87, 0.1); border-radius: 6px; border: 1px solid rgba(255, 71, 87, 0.2);"></div>
                <div id="maxAmount" style="color: #00d4ff; font-size: 12px; margin-top: 5px; opacity: 0.8; display: none;"></div>
            </div>

            <div class="input-row">
                <div class="form-group">
                    <label class="form-label">Take Profit :</label>
                    <input type="number" name="take_profit" id="takeProfit" class="form-control" step="0.01" value="0.00" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Stop Loss :</label>
                    <input type="number" name="stop_loss" id="stopLoss" class="form-control" step="0.01" value="0.00" placeholder="0.00">
                </div>
            </div>

            <div class="trade-buttons">
                <button type="button" class="trade-btn buy" onclick="showConfirmModal('buy')">Buy</button>
                <button type="button" class="trade-btn sell" onclick="showConfirmModal('sell')">Sell</button>
            </div>
        </form>
    </div>

    <div>
        <div class="chart-container">
            <div class="chart-header">
                <div class="chart-symbol">
                    <span>Live Trading Chart</span>
                </div>
                <div class="chart-controls">
                    <button class="chart-control-btn" onclick="changeInterval('1')">1m</button>
                    <button class="chart-control-btn active" onclick="changeInterval('30')">30m</button>
                    <button class="chart-control-btn" onclick="changeInterval('60')">1h</button>
                </div>
            </div>
            <div class="tradingview-widget-container">
                <div id="tradingview_chart"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://s3.tradingview.com/tv.js"></script>
<script>
let tvChart = null;
let currentSymbol = 'BINANCE:BTCUSDT';

document.addEventListener('DOMContentLoaded', function() {
    initTradingViewChart();
    updateMarketInfo();
    filterAssetNamesByType();
});

function filterAssetNamesByType() {
    const assetTypeSelect = document.getElementById('assetType');
    const assetNameSelect = document.getElementById('assetName');
    const selectedType = assetTypeSelect.value;
    
    const options = assetNameSelect.querySelectorAll('option');
    let firstVisibleOption = null;
    
    options.forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
            return;
        }
        const optionType = option.getAttribute('data-type');
        if (optionType === selectedType) {
            option.style.display = 'block';
            if (!firstVisibleOption) firstVisibleOption = option;
        } else {
            option.style.display = 'none';
        }
    });
    
    if (firstVisibleOption && assetNameSelect.value === '') {
        assetNameSelect.value = firstVisibleOption.value;
    }
}

function updateMarketInfo() {
    const assetNameSelect = document.getElementById('assetName');
    const selectedOption = assetNameSelect.options[assetNameSelect.selectedIndex];
    
    if (!selectedOption) return;
    
    const assetType = selectedOption.getAttribute('data-type');
    const assetName = selectedOption.getAttribute('data-display');
    const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    
    document.getElementById('currentAssetType').textContent = assetType || 'Unknown';
    document.getElementById('currentAssetName').textContent = assetName || 'Unknown';
    document.getElementById('currentPrice').textContent = '$' + price.toFixed(2);
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

function updateTradingViewChart() {
    const assetNameSelect = document.getElementById('assetName');
    const selectedOption = assetNameSelect.options[assetNameSelect.selectedIndex];
    const tradingViewSymbol = selectedOption.getAttribute('data-tradingview');
    
    if (tradingViewSymbol) {
        currentSymbol = tradingViewSymbol;
    }
    
    const container = document.getElementById('tradingview_chart');
    if (container) {
        container.innerHTML = '';
    }
    tvChart = null;
    
    setTimeout(createTradingViewWidget, 100);
}

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
    if (symbol && symbol.includes('BTC')) estimatedPrice = 50000;
    else if (symbol && symbol.includes('ETH')) estimatedPrice = 3000;
    else if (symbol && symbol.includes('GOLD')) estimatedPrice = 2000;
    else if (symbol && symbol.includes('OIL')) estimatedPrice = 100;
    
    const marginRequired = (amount * estimatedPrice) / leverage;
    
    if (marginRequired > availableBalance) {
        balanceErrorDiv.textContent = '⚠ Insufficient balance! Required: $' + marginRequired.toFixed(2) + ' | Available: $' + availableBalance.toFixed(2);
        balanceErrorDiv.style.display = 'block';
        return;
    }
    
    document.getElementById('confirmModalTitle').textContent = 'Confirm ' + action.toUpperCase() + ' Trade';
    document.getElementById('confirmAmountDisplay').textContent = '$' + amount.toFixed(2);
    document.getElementById('confirmLeverageDisplay').textContent = leverage + 'x';
    document.getElementById('confirmDurationDisplay').textContent = document.getElementById('tradeDuration').options[document.getElementById('tradeDuration').selectedIndex].text;
    
    document.getElementById('tradeConfirmModal').style.display = 'flex';
    
    document.getElementById('confirmTradeBtn').onclick = function() {
        document.getElementById('orderSide').value = action;
        document.getElementById('tradeForm').submit();
    };
    
    balanceErrorDiv.style.display = 'none';
}

function closeConfirmModal() {
    document.getElementById('tradeConfirmModal').style.display = 'none';
}

function validateTradeAmount() {
    const amountInput = document.getElementById('tradeAmount');
    const leverage = parseFloat(document.getElementById('leverageValue').value) || 5;
    const amount = parseFloat(amountInput.value) || 0;
    const balanceErrorDiv = document.getElementById('balanceError');
    const maxAmountDiv = document.getElementById('maxAmount');
    const buyBtn = document.querySelector('.trade-btn.buy');
    const sellBtn = document.querySelector('.trade-btn.sell');
    
    if (amount <= 0) {
        balanceErrorDiv.style.display = 'none';
        maxAmountDiv.style.display = 'none';
        buyBtn.disabled = true;
        sellBtn.disabled = true;
        buyBtn.style.opacity = '0.5';
        sellBtn.style.opacity = '0.5';
        return;
    }
    
    const marketSelect = document.getElementById('assetName');
    const selectedOption = marketSelect.options[marketSelect.selectedIndex];
    const symbol = selectedOption ? selectedOption.getAttribute('data-symbol') : '';
    
    const availableBalance = walletBalance - marginUsed;
    const maxTradeAmount = availableBalance * leverage;
    const minTradeAmount = 10;
    
    let estimatedPrice = 1;
    if (symbol.includes('BTC')) estimatedPrice = 50000;
    else if (symbol.includes('ETH')) estimatedPrice = 3000;
    else if (symbol.includes('GOLD')) estimatedPrice = 2000;
    else if (symbol.includes('OIL')) estimatedPrice = 100;
    
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
    
    if (hasError) {
        balanceErrorDiv.textContent = errorMessage;
        balanceErrorDiv.style.display = 'block';
        buyBtn.disabled = true;
        sellBtn.disabled = true;
        buyBtn.style.opacity = '0.5';
        sellBtn.style.opacity = '0.5';
        maxAmountDiv.style.display = 'block';
        maxAmountDiv.textContent = '💡 Max trade amount: $' + maxTradeAmount.toFixed(2) + ' at ' + leverage + 'x leverage';
    } else {
        balanceErrorDiv.style.display = 'none';
        buyBtn.disabled = false;
        sellBtn.disabled = false;
        buyBtn.style.opacity = '1';
        sellBtn.style.opacity = '1';
        maxAmountDiv.style.display = 'block';
        maxAmountDiv.textContent = '✓ Max available: $' + maxTradeAmount.toFixed(2) + ' at ' + leverage + 'x leverage | Fee: ~$' + (amount * 0.001).toFixed(2);
    }
}

setInterval(updateCountdowns, 1000);
setInterval(closeExpiredPosition, 5000);
updateCountdowns();

document.querySelectorAll('.close-position-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const positionId = this.dataset.positionId;
        if (confirm('Are you sure you want to close this position?')) {
            fetch('/api/positions/close', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'position_id=' + positionId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Position closed. PnL: $' + data.pnl.toFixed(2));
                    window.location.reload();
                } else {
                    alert(data.error || 'Failed to close position');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Error closing position');
            });
        }
    });
});
</script>

[REST OF FILE - TRANSACTIONS SECTION REMAINS THE SAME - COPY FROM ORIGINAL FILE LINES 287-1146]

<?php
$content = ob_get_clean();
$pageTitle = 'Trading';
include __DIR__ . '/../layouts/main.php';
?>
