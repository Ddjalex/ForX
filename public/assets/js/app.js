document.addEventListener('DOMContentLoaded', function() {
    setInterval(updatePrices, 30000);
    setInterval(updatePositions, 10000);
    setInterval(checkExpiredPositions, 5000);
    checkExpiredPositions();
    
    initSidebar();
});

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
