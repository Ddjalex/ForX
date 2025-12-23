document.addEventListener('DOMContentLoaded', function() {
    setInterval(updatePrices, 30000);
    setInterval(updatePositions, 10000);
    setInterval(checkExpiredPositions, 5000);
    setInterval(updateWalletBalance, 5000);
    checkExpiredPositions();
    updateWalletBalance();
    
    initSidebar();
    initLanguageToggle();
});

function initLanguageToggle() {
    const languageSelector = document.querySelector('.language-selector');
    if (!languageSelector) return;
    
    const languages = [
        { code: 'en', name: 'English' },
        { code: 'es', name: 'Español' },
        { code: 'fr', name: 'Français' },
        { code: 'de', name: 'Deutsch' },
        { code: 'it', name: 'Italiano' },
        { code: 'pt', name: 'Português' },
        { code: 'nl', name: 'Nederlands' },
        { code: 'pl', name: 'Polski' },
        { code: 'ru', name: 'Русский' },
        { code: 'uk', name: 'Українська' },
        { code: 'tr', name: 'Türkçe' },
        { code: 'ar', name: 'العربية' },
        { code: 'zh', name: '中文' },
        { code: 'ja', name: '日本語' },
        { code: 'ko', name: '한국어' },
        { code: 'th', name: 'ไทย' },
        { code: 'vi', name: 'Tiếng Việt' },
        { code: 'id', name: 'Bahasa Indonesia' },
        { code: 'hi', name: 'हिन्दी' },
        { code: 'bn', name: 'বাংলা' },
        { code: 'sv', name: 'Svenska' },
        { code: 'da', name: 'Dansk' },
        { code: 'no', name: 'Norsk' },
        { code: 'fi', name: 'Suomi' },
        { code: 'el', name: 'Ελληνικά' },
        { code: 'he', name: 'עברית' },
        { code: 'cs', name: 'Čeština' },
        { code: 'hu', name: 'Magyar' },
        { code: 'ro', name: 'Română' },
        { code: 'sk', name: 'Slovenčina' },
        { code: 'am', name: 'አማርኛ' }
    ];
    
    // Create dropdown on init
    const menu = document.createElement('div');
    menu.className = 'language-dropdown';
    menu.innerHTML = languages.map(lang => 
        `<div class="language-option" data-lang="${lang.code}">${lang.name}</div>`
    ).join('');
    
    languageSelector.appendChild(menu);
    
    menu.querySelectorAll('.language-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            const lang = this.getAttribute('data-lang');
            setLanguage(lang);
        });
    });
    
    languageSelector.addEventListener('click', function(e) {
        e.stopPropagation();
        this.classList.toggle('open');
    });
    
    document.addEventListener('click', function() {
        languageSelector.classList.remove('open');
    });
}

function setLanguage(lang) {
    // Save language to cookie first
    document.cookie = "language=" + lang + ";path=/;max-age=" + (365 * 24 * 60 * 60);
    localStorage.setItem('language', lang);
    
    // Clear cache and reload page to show new language
    window.location.href = window.location.pathname + '?lang=' + lang + '&t=' + Date.now();
}


function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const liveAnalysisToggle = document.getElementById('liveAnalysisToggle');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (sidebarClose && sidebar) {
        sidebarClose.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    if (sidebarOverlay && sidebar) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    if (liveAnalysisToggle) {
        liveAnalysisToggle.addEventListener('click', function() {
            const dropdown = this.closest('.sidebar-dropdown');
            dropdown.classList.toggle('open');
        });
    }
}

async function updatePrices() {
    try {
        const response = await fetch('/api/prices');
        const data = await response.json();
        
        if (data.success && data.data) {
            data.data.forEach(price => {
                const cards = document.querySelectorAll(`[data-market="${price.symbol}"]`);
                cards.forEach(card => {
                    const priceEl = card.querySelector('.market-price');
                    const changeEl = card.querySelector('.market-change');
                    
                    if (priceEl) priceEl.textContent = '$' + parseFloat(price.price).toFixed(2);
                    if (changeEl) {
                        const change = parseFloat(price.change_24h);
                        changeEl.textContent = (change >= 0 ? '+' : '') + change.toFixed(2) + '%';
                        changeEl.className = 'market-change ' + (change >= 0 ? 'positive' : 'negative');
                    }
                });
            });
        }
    } catch (e) {
        console.log('Price update failed:', e);
    }
}

async function updatePositions() {
    try {
        const response = await fetch('/api/positions');
        const data = await response.json();
        
        if (data.success && data.data) {
            let totalPnL = 0;
            
            data.data.forEach(position => {
                totalPnL += position.unrealized_pnl;
                
                const row = document.querySelector(`[data-position="${position.id}"]`);
                if (row) {
                    const pnlEl = row.querySelector('.pnl');
                    if (pnlEl) {
                        const pnl = parseFloat(position.unrealized_pnl);
                        pnlEl.textContent = (pnl >= 0 ? '+' : '') + '$' + pnl.toFixed(2);
                        pnlEl.className = 'pnl ' + (pnl >= 0 ? 'positive' : 'negative');
                    }
                }
            });
            
            const totalPnLEl = document.getElementById('totalPnL');
            if (totalPnLEl) {
                totalPnLEl.textContent = (totalPnL >= 0 ? '+' : '') + '$' + totalPnL.toFixed(2);
                totalPnLEl.className = 'stat-value ' + (totalPnL >= 0 ? 'positive' : 'negative');
            }
        }
    } catch (e) {
        console.log('Position update failed:', e);
    }
}

function formatNumber(num, decimals = 2) {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(num);
}

function formatCurrency(num) {
    return '$' + formatNumber(num);
}

async function checkExpiredPositions() {
    try {
        const response = await fetch('/api/positions/close-expired', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        
        let data = { success: true, closed_count: 0, positions: [] };
        
        if (response.ok) {
            try {
                data = await response.json();
            } catch (parseError) {
                console.log('JSON parse error:', parseError);
            }
        }
        
        if (data.success && data.closed_count > 0 && data.positions && data.positions.length > 0) {
            let countdown = 10;
            const notification = document.createElement('div');
            notification.className = 'alert alert-success';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                padding: 16px 20px;
                background: #00C853;
                color: white;
                border-radius: 8px;
                font-weight: 500;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            `;
            notification.textContent = `${data.closed_count} trade(s) closed! Redirecting in ${countdown}s...`;
            document.body.appendChild(notification);
            
            const countdownInterval = setInterval(() => {
                countdown--;
                notification.textContent = `${data.closed_count} trade(s) closed! Redirecting in ${countdown}s...`;
                
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = '/dashboard/trades/history';
                }
            }, 1000);
        }
    } catch (e) {
        console.log('Expired positions check failed:', e);
    }
}

function showTradeNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        padding: 16px 20px;
        background: ${type === 'success' ? '#00C853' : '#3742FA'};
        color: white;
        border-radius: 8px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        animation: slideIn 0.3s ease-out;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// TradingView Chart functions are now implemented in trade.php
// These functions are kept for compatibility with other pages

function setLeverage(leverage) {
    const leverageInput = document.getElementById('leverageValue') || document.getElementById('leverageInput');
    const slider = document.getElementById('leverageSlider');
    
    if (leverageInput) leverageInput.value = leverage;
    if (slider) slider.value = leverage;
    
    document.querySelectorAll('.leverage-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    if (event && event.target) event.target.classList.add('active');
    
    // Re-validate trade amount with new leverage
    if (typeof validateTradeAmount === 'function') {
        validateTradeAmount();
    }
}

function updateLeverageFromSlider(value) {
    const leverageInput = document.getElementById('leverageValue') || document.getElementById('leverageInput');
    if (leverageInput) leverageInput.value = value;
    
    document.querySelectorAll('.leverage-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const targetBtn = document.querySelector(`[data-leverage="${value}"]`);
    if (targetBtn) targetBtn.classList.add('active');
    
    // Re-validate trade amount with new leverage
    if (typeof validateTradeAmount === 'function') {
        validateTradeAmount();
    }
}

function updateAssetNames() {
    const assetTypeEl = document.getElementById('assetType');
    const assetNameSelect = document.getElementById('assetName');
    
    if (!assetTypeEl || !assetNameSelect) return;
    
    const assetType = assetTypeEl.value;
    
    // Hide all options first
    Array.from(assetNameSelect.options).forEach(option => {
        option.style.display = option.getAttribute('data-type') === assetType ? 'block' : 'none';
    });
    
    // Select first visible option
    const firstVisible = Array.from(assetNameSelect.options).find(option => option.style.display !== 'none');
    if (firstVisible) {
        assetNameSelect.value = firstVisible.value;
        if (typeof updateTradingViewChart === 'function') {
            updateTradingViewChart();
        }
    }
}

function showConfirmModal(side) {
    const modal = document.getElementById('tradeConfirmModal');
    const confirmBtn = document.getElementById('confirmTradeBtn');
    const sideText = document.getElementById('confirmSideText');
    const orderSide = document.getElementById('orderSide');
    
    // UI elements for guide
    const confirmAmountDisplay = document.getElementById('confirmAmountDisplay');
    const confirmLeverageDisplay = document.getElementById('confirmLeverageDisplay');
    const confirmDurationDisplay = document.getElementById('confirmDurationDisplay');
    
    // Form values
    const amount = document.getElementById('tradeAmount').value;
    const leverage = document.getElementById('leverageValue').value;
    const duration = document.getElementById('tradeDuration').value;
    
    if (!modal || !confirmBtn) return;
    
    const sideUpper = side.toUpperCase();
    
    if (sideText) sideText.textContent = sideUpper;
    confirmBtn.className = `trade-confirm-btn confirm-${side}`;
    confirmBtn.textContent = `Yes, ${sideUpper} Now`;
    
    if (orderSide) orderSide.value = side;
    
    // Update guide display
    if (confirmAmountDisplay) confirmAmountDisplay.textContent = '$' + parseFloat(amount || 0).toFixed(2);
    if (confirmLeverageDisplay) confirmLeverageDisplay.textContent = leverage + 'x';
    if (confirmDurationDisplay) confirmDurationDisplay.textContent = duration + ' min';
    
    modal.style.display = 'flex';
}

async function updateWalletBalance() {
    try {
        const response = await fetch('/api/wallet');
        if (!response.ok) return;
        
        const data = await response.json();
        if (data.success && data.data) {
            // Store current balance in multiple places for validation
            window.currentBalance = data.data.available || 0;
            window.currentWalletData = data.data;
            
            // Update balance display in the panel
            const balanceValueEl = document.querySelector('.balance-value');
            if (balanceValueEl) {
                const available = data.data.available || 0;
                balanceValueEl.textContent = '$' + formatNumber(available, 2);
            }
            
            // Check if balance is low and show warning
            if (data.data.available < 100) {
                showLowBalanceWarning(data.data.available);
            }
        }
    } catch (e) {
        console.log('Wallet balance update failed:', e);
    }
}

function showLowBalanceWarning(balance) {
    const existingWarning = document.getElementById('lowBalanceWarning');
    if (existingWarning) return;
    
    const warning = document.createElement('div');
    warning.id = 'lowBalanceWarning';
    warning.className = 'alert alert-warning';
    warning.style.cssText = `
        position: sticky;
        top: 10px;
        z-index: 100;
        padding: 12px 16px;
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: white;
        border-radius: 8px;
        border-left: 4px solid #ff6f00;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(255, 152, 0, 0.3);
    `;
    warning.innerHTML = `
        <strong>⚠️ Low Balance Warning</strong><br>
        Your available balance is only <strong>$${balance.toFixed(2)}</strong>. 
        <a href="/wallet/deposit" style="color: white; text-decoration: underline; font-weight: bold;">Make a deposit</a>
    `;
    
    const tradingPanel = document.querySelector('.trading-panel');
    if (tradingPanel && !tradingPanel.querySelector('#lowBalanceWarning')) {
        tradingPanel.insertBefore(warning, tradingPanel.firstChild);
    }
}

function validateTradeAmount() {
    const amountInput = document.getElementById('tradeAmount');
    const errorDiv = document.getElementById('balanceError');
    const maxAmountDiv = document.getElementById('maxAmount');
    const leverageInput = document.getElementById('leverageValue');
    
    if (!amountInput || !errorDiv) return;
    
    const amount = parseFloat(amountInput.value) || 0;
    const leverage = parseInt(leverageInput?.value) || 1;
    
    // Try to get balance from multiple sources
    let availableBalance = 0;
    
    // Priority 1: Use window.currentBalance (set by API)
    if (window.currentBalance && window.currentBalance > 0) {
        availableBalance = window.currentBalance;
    } 
    // Priority 2: Get from wallet data API
    else if (window.currentWalletData && window.currentWalletData.available) {
        availableBalance = window.currentWalletData.available;
    }
    // Priority 3: Parse from DOM element
    else {
        const balanceText = document.querySelector('.balance-value');
        if (balanceText && balanceText.textContent) {
            const cleanBalance = balanceText.textContent
                .replace(/^\$/, '')
                .replace(/,/g, '')
                .trim();
            const parsed = parseFloat(cleanBalance);
            if (!isNaN(parsed) && parsed > 0) {
                availableBalance = parsed;
            }
        }
    }
    
    const marginRequired = amount / leverage;
    const maxTradeable = availableBalance * leverage;
    
    // Show max amount helper
    if (maxAmountDiv && availableBalance > 0) {
        maxAmountDiv.textContent = `Max tradeable amount: $${maxTradeable.toFixed(2)} (at ${leverage}x leverage)`;
        maxAmountDiv.style.display = 'block';
    }
    
    // Validate balance
    if (amount > 0 && marginRequired > availableBalance) {
        errorDiv.textContent = `❌ Insufficient Balance! Required Margin: $${marginRequired.toFixed(2)} | Available: $${availableBalance.toFixed(2)}`;
        errorDiv.className = 'balance-error-display';
        errorDiv.style.cssText = `
            color: #ff4757;
            font-size: 13px;
            margin-top: 8px;
            font-weight: 500;
            display: block;
            padding: 10px;
            background: rgba(255, 71, 87, 0.15);
            border-radius: 6px;
            border-left: 3px solid #ff4757;
        `;
        amountInput.style.borderColor = '#ff4757';
        amountInput.style.boxShadow = '0 0 0 2px rgba(255, 71, 87, 0.1)';
    } else if (amount > 0) {
        const remainingBalance = availableBalance - marginRequired;
        errorDiv.innerHTML = `✓ Sufficient balance. Remaining after trade: $${remainingBalance.toFixed(2)}`;
        errorDiv.style.cssText = `
            color: #00d4aa;
            font-size: 13px;
            margin-top: 8px;
            font-weight: 500;
            display: block;
            padding: 10px;
            background: rgba(0, 212, 170, 0.1);
            border-radius: 6px;
            border-left: 3px solid #00d4aa;
        `;
        amountInput.style.borderColor = '#00d4aa';
        amountInput.style.boxShadow = '0 0 0 2px rgba(0, 212, 170, 0.1)';
    } else {
        errorDiv.style.display = 'none';
        amountInput.style.borderColor = '';
        amountInput.style.boxShadow = '';
    }
}

function closeConfirmModal() {
    const modal = document.getElementById('tradeConfirmModal');
    if (modal) modal.style.display = 'none';
}

const confirmTradeBtn = document.getElementById('confirmTradeBtn');
if (confirmTradeBtn) {
    confirmTradeBtn.addEventListener('click', function() {
        const tradeForm = document.getElementById('tradeForm');
        if (tradeForm) tradeForm.submit();
    });
}
