document.addEventListener('DOMContentLoaded', function() {
    // Initialize balance from DOM on page load
    initializeBalance();
    
    setInterval(updatePrices, 30000);
    setInterval(updatePositions, 10000);
    setInterval(checkExpiredPositions, 5000);
    setInterval(updateWalletBalance, 5000);
    setInterval(startPositionCountdowns, 1000); // Update countdowns every second
    
    checkExpiredPositions();
    updateWalletBalance();
    startPositionCountdowns();
    
    initSidebar();
    initLanguageToggle();
});

function initializeBalance() {
    const balanceText = document.querySelector('.balance-value');
    if (balanceText && balanceText.textContent) {
        const cleanBalance = balanceText.textContent
            .replace(/^\$/, '')
            .replace(/,/g, '')
            .trim();
        const parsed = parseFloat(cleanBalance);
        if (!isNaN(parsed) && parsed > 0) {
            window.currentBalance = parsed;
        }
    }
}

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

/**
 * Countdown Timer for Open Positions
 * Updates remaining time for each active trade
 */
function startPositionCountdowns() {
    try {
        const rows = document.querySelectorAll('[data-position][data-expires]');
        
        rows.forEach(row => {
            const expiresAt = row.getAttribute('data-expires');
            if (!expiresAt) return;
            
            const expiryTime = new Date(expiresAt).getTime();
            const now = new Date().getTime();
            const timeLeft = expiryTime - now;
            
            // Find or create countdown element
            let countdownEl = row.querySelector('.position-countdown');
            if (!countdownEl) {
                countdownEl = document.createElement('span');
                countdownEl.className = 'position-countdown';
                countdownEl.style.cssText = `
                    padding: 4px 8px;
                    background: #ff9800;
                    color: white;
                    border-radius: 4px;
                    font-size: 12px;
                    font-weight: 600;
                    display: inline-block;
                `;
                const firstCell = row.querySelector('td');
                if (firstCell) {
                    firstCell.appendChild(countdownEl);
                }
            }
            
            if (timeLeft > 0) {
                const minutes = Math.floor(timeLeft / 60000);
                const seconds = Math.floor((timeLeft % 60000) / 1000);
                countdownEl.textContent = `⏱ ${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                // Change color as time runs out
                if (minutes === 0 && seconds < 30) {
                    countdownEl.style.background = '#FF4757'; // Red
                } else if (minutes < 1) {
                    countdownEl.style.background = '#ff9800'; // Orange
                } else {
                    countdownEl.style.background = '#10B981'; // Green
                }
            } else {
                countdownEl.textContent = '⏱ 0:00 EXPIRED';
                countdownEl.style.background = '#FF4757';
            }
        });
    } catch (e) {
        console.log('Position countdown update failed:', e);
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
            // Automatically redirect to trade panel without showing countdown notification
            window.location.href = '/dashboard/trade';
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
    const display = document.getElementById('leverageDisplay');
    
    if (leverageInput) leverageInput.value = leverage;
    if (slider) slider.value = leverage;
    if (display) display.textContent = leverage + 'x';
    
    document.querySelectorAll('.leverage-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-leverage') == leverage) {
            btn.classList.add('active');
        }
    });
    
    // Re-validate trade amount with new leverage
    if (typeof validateTradeAmount === 'function') {
        validateTradeAmount();
    }
}

function updateLeverageFromSlider(value) {
    const leverageInput = document.getElementById('leverageValue') || document.getElementById('leverageInput');
    if (leverageInput) {
        leverageInput.value = value;
        // Trigger input event for any listeners
        leverageInput.dispatchEvent(new Event('input'));
    }
    
    const sliderValueDisplay = document.querySelector('.leverage-slider-container .slider-value') || document.querySelector('.leverage-amount');
    if (sliderValueDisplay) sliderValueDisplay.textContent = value + 'x';

    document.querySelectorAll('.leverage-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-leverage') == value) {
            btn.classList.add('active');
        }
    });
    
    // Re-validate trade amount with new leverage
    if (typeof validateTradeAmount === 'function') {
        validateTradeAmount();
    }
}

function updateMarketInfo() {
    const assetTypeSelect = document.getElementById('assetType');
    const assetNameSelect = document.getElementById('assetName');
    
    const displayAssetType = document.getElementById('dashboardCurrentAssetType');
    const displayAssetName = document.getElementById('dashboardCurrentAssetName');
    const displayPrice = document.getElementById('dashboardCurrentPrice');
    
    if (assetTypeSelect && displayAssetType) {
        const typeOption = assetTypeSelect.options[assetTypeSelect.selectedIndex];
        displayAssetType.textContent = typeOption ? typeOption.text : assetTypeSelect.value;
    }
    
    if (assetNameSelect && displayAssetName) {
        const selectedOption = assetNameSelect.options[assetNameSelect.selectedIndex];
        if (selectedOption) {
            displayAssetName.textContent = selectedOption.getAttribute('data-display') || selectedOption.text;
            if (displayPrice) {
                const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
                displayPrice.textContent = '$' + price.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }
        }
    }
}

function updateAssetNames() {
    const assetTypeEl = document.getElementById('assetType');
    const assetNameSelect = document.getElementById('assetName');
    
    if (!assetTypeEl || !assetNameSelect) return;
    
    const assetType = assetTypeEl.value;
    
    // Hide all options first
    let firstMatch = null;
    Array.from(assetNameSelect.options).forEach(option => {
        const isMatch = option.getAttribute('data-type') === assetType;
        option.style.display = isMatch ? 'block' : 'none';
        if (isMatch && !firstMatch) firstMatch = option;
    });
    
    // Select first visible option
    if (firstMatch) {
        assetNameSelect.value = firstMatch.value;
        updateMarketInfo();
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
            const totalBalanceValue = document.getElementById('dashboardTotalBalanceValue');
            const liveTradeBalanceValue = document.getElementById('dashboardLiveTradeBalanceValue');

            const available = data.data.available || 0;
            const formatted = '$' + formatNumber(available, 2);

            if (balanceValueEl) {
                if (!window.balanceIsHidden) balanceValueEl.textContent = formatted;
                balanceValueEl.dataset.originalValue = formatted;
            }
            if (totalBalanceValue) {
                if (!window.balanceIsHidden) totalBalanceValue.textContent = formatted;
                totalBalanceValue.dataset.originalValue = formatted;
            }
            if (liveTradeBalanceValue) {
                if (!window.balanceIsHidden) liveTradeBalanceValue.textContent = formatted;
                liveTradeBalanceValue.dataset.originalValue = formatted;
            }
        }
    } catch (e) {
        console.log('Wallet balance update failed:', e);
    }
}

window.toggleBalanceVisibility = function(context) {
    const totalBalanceValue = document.getElementById('dashboardTotalBalanceValue');
    const liveTradeBalanceValue = document.getElementById('dashboardLiveTradeBalanceValue');
    const balanceValueEl = document.querySelector('.balance-value');
    const dashboardEye = document.getElementById('dashboardBalanceEyeIcon');
    const liveTradeEye = document.getElementById('liveTradeBalanceEyeIcon');

    // Toggle hidden state globally
    window.balanceIsHidden = !window.balanceIsHidden;

    const elements = [
        { el: totalBalanceValue, icon: dashboardEye },
        { el: liveTradeBalanceValue, icon: liveTradeEye },
        { el: balanceValueEl, icon: null }
    ];

    elements.forEach(item => {
        if (!item.el) return;

        if (window.balanceIsHidden) {
            // Save current value if not already saved
            if (!item.el.dataset.originalValue) {
                item.el.dataset.originalValue = item.el.textContent;
            }
            item.el.textContent = '***';
            if (item.icon) {
                item.icon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
            }
        } else {
            // Restore original value
            if (item.el.dataset.originalValue) {
                item.el.textContent = item.el.dataset.originalValue;
            }
            if (item.icon) {
                item.icon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            }
        }
    });

    // Save preference
    localStorage.setItem('hideBalance', window.balanceIsHidden);
}

// Global variable to track visibility
window.balanceIsHidden = localStorage.getItem('hideBalance') === 'true';

// Auto-apply on load
document.addEventListener('DOMContentLoaded', function() {
    if (window.balanceIsHidden) {
        // We set to false momentarily so toggleBalanceVisibility turns it back to true and applies styles
        window.balanceIsHidden = false; 
        toggleBalanceVisibility();
    }
});


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
