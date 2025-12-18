document.addEventListener('DOMContentLoaded', function() {
    setInterval(updatePrices, 30000);
    setInterval(updatePositions, 10000);
    setInterval(checkExpiredPositions, 5000);
    checkExpiredPositions();
    
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
        { code: 'sk', name: 'Slovenčina' }
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
        const data = await response.json();
        
        if (data.success && data.closed_count > 0) {
            console.log(`Auto-closed ${data.closed_count} expired position(s)`);
            
            // Show notification to user
            if (data.positions && data.positions.length > 0) {
                const firstSymbol = data.positions[0].symbol;
                showTradeNotification(`${data.closed_count} trade(s) closed! Redirecting to view results...`, 'success');
                
                // Redirect to trade page to show transaction history after 2 seconds
                setTimeout(() => {
                    window.location.href = `/dashboard/trade/${firstSymbol}`;
                }, 2000);
            }
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
    document.getElementById('leverageValue').value = leverage;
    document.getElementById('leverageSlider').value = leverage;
    document.querySelectorAll('.leverage-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
}

function updateLeverageFromSlider(value) {
    document.getElementById('leverageValue').value = value;
    document.querySelectorAll('.leverage-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    const targetBtn = document.querySelector(`[data-leverage="${value}"]`);
    if (targetBtn) targetBtn.classList.add('active');
}

function updateAssetNames() {
    const assetType = document.getElementById('assetType').value;
    const assetNameSelect = document.getElementById('assetName');
    
    // Hide all options first
    Array.from(assetNameSelect.options).forEach(option => {
        option.style.display = option.getAttribute('data-type') === assetType ? 'block' : 'none';
    });
    
    // Select first visible option
    const firstVisible = Array.from(assetNameSelect.options).find(option => option.style.display !== 'none');
    if (firstVisible) {
        assetNameSelect.value = firstVisible.value;
        updateTradingViewChart();
    }
}

function showConfirmModal(side) {
    const modal = document.getElementById('tradeConfirmModal');
    const title = document.getElementById('confirmModalTitle');
    const confirmBtn = document.getElementById('confirmTradeBtn');
    
    title.textContent = `Confirm ${side.toUpperCase()} Trade`;
    confirmBtn.className = `btn btn-${side}`;
    confirmBtn.textContent = `Yes, ${side.toUpperCase()} Now`;
    
    document.getElementById('orderSide').value = side;
    document.getElementById('orderType').value = 'market';
    
    modal.style.display = 'flex';
}

function closeConfirmModal() {
    const modal = document.getElementById('tradeConfirmModal');
    modal.style.display = 'none';
}

document.getElementById('confirmTradeBtn')?.addEventListener('click', function() {
    document.getElementById('tradeForm').submit();
});
