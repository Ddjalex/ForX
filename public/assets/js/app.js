// Sidebar translations - only sidebar content changes, not the whole page
const sidebarTranslations = {
    en: {
        account_settings: 'Account Settings',
        deposit: 'Deposit',
        withdraw: 'Withdraw',
        trade: 'Trade',
        copy_experts: 'Copy Experts',
        news: 'News',
        nfts: 'NFTs',
        signals: 'Signals',
        loans: 'Crypto Loans',
        trade_history: 'Trade History',
        transactions: 'Transactions',
        referrals: 'Referrals',
        logout: 'Logout',
        live_analysis: 'Live Analysis',
        forex_analysis: 'Forex Analysis',
        crypto_analysis: 'Crypto Analysis',
        stock_analysis: 'Stock Analysis',
        verified: 'Verified',
        not_verified: 'Not Verified'
    },
    es: {
        account_settings: 'Configuración de Cuenta',
        deposit: 'Depositar',
        withdraw: 'Retirar',
        trade: 'Operar',
        copy_experts: 'Copiar Expertos',
        news: 'Noticias',
        nfts: 'NFTs',
        signals: 'Señales',
        loans: 'Préstamos Cripto',
        trade_history: 'Historial de Operaciones',
        transactions: 'Transacciones',
        referrals: 'Referidos',
        logout: 'Cerrar Sesión',
        live_analysis: 'Análisis en Vivo',
        forex_analysis: 'Análisis de Forex',
        crypto_analysis: 'Análisis de Cripto',
        stock_analysis: 'Análisis de Acciones',
        verified: 'Verificado',
        not_verified: 'No Verificado'
    },
    fr: {
        account_settings: 'Paramètres de Compte',
        deposit: 'Dépôt',
        withdraw: 'Retrait',
        trade: 'Trader',
        copy_experts: 'Copier Experts',
        news: 'Actualités',
        nfts: 'NFTs',
        signals: 'Signaux',
        loans: 'Prêts Crypto',
        trade_history: 'Historique des Transactions',
        transactions: 'Transactions',
        referrals: 'Parrainages',
        logout: 'Déconnexion',
        live_analysis: 'Analyse en Direct',
        forex_analysis: 'Analyse Forex',
        crypto_analysis: 'Analyse Crypto',
        stock_analysis: 'Analyse Boursière',
        verified: 'Vérifié',
        not_verified: 'Non Vérifié'
    },
    de: {
        account_settings: 'Kontoeinstellungen',
        deposit: 'Einzahlung',
        withdraw: 'Auszahlung',
        trade: 'Handel',
        copy_experts: 'Experten Kopieren',
        news: 'Nachrichten',
        nfts: 'NFTs',
        signals: 'Signale',
        loans: 'Krypto-Kredite',
        trade_history: 'Handelsverlauf',
        transactions: 'Transaktionen',
        referrals: 'Empfehlungen',
        logout: 'Abmelden',
        live_analysis: 'Live-Analyse',
        forex_analysis: 'Forex-Analyse',
        crypto_analysis: 'Krypto-Analyse',
        stock_analysis: 'Aktienanalyse',
        verified: 'Verifiziert',
        not_verified: 'Nicht Verifiziert'
    },
    zh: {
        account_settings: '账户设置',
        deposit: '存款',
        withdraw: '提款',
        trade: '交易',
        copy_experts: '复制专家',
        news: '新闻',
        nfts: 'NFTs',
        signals: '信号',
        loans: '加密贷款',
        trade_history: '交易历史',
        transactions: '交易',
        referrals: '推荐',
        logout: '登出',
        live_analysis: '实时分析',
        forex_analysis: '外汇分析',
        crypto_analysis: '加密分析',
        stock_analysis: '股票分析',
        verified: '已验证',
        not_verified: '未验证'
    },
    ar: {
        account_settings: 'إعدادات الحساب',
        deposit: 'إيداع',
        withdraw: 'سحب',
        trade: 'التداول',
        copy_experts: 'نسخ الخبراء',
        news: 'أخبار',
        nfts: 'NFTs',
        signals: 'الإشارات',
        loans: 'قروض العملات المشفرة',
        trade_history: 'سجل التداول',
        transactions: 'المعاملات',
        referrals: 'الإحالات',
        logout: 'تسجيل الخروج',
        live_analysis: 'التحليل المباشر',
        forex_analysis: 'تحليل الفوركس',
        crypto_analysis: 'تحليل العملات المشفرة',
        stock_analysis: 'تحليل الأسهم',
        verified: 'موثق',
        not_verified: 'غير موثق'
    },
    pt: {
        account_settings: 'Configurações da Conta',
        deposit: 'Depósito',
        withdraw: 'Saque',
        trade: 'Negociar',
        copy_experts: 'Copiar Especialistas',
        news: 'Notícias',
        nfts: 'NFTs',
        signals: 'Sinais',
        loans: 'Empréstimos Cripto',
        trade_history: 'Histórico de Negociação',
        transactions: 'Transações',
        referrals: 'Indicações',
        logout: 'Sair',
        live_analysis: 'Análise ao Vivo',
        forex_analysis: 'Análise de Forex',
        crypto_analysis: 'Análise de Cripto',
        stock_analysis: 'Análise de Ações',
        verified: 'Verificado',
        not_verified: 'Não Verificado'
    },
    ru: {
        account_settings: 'Настройки учетной записи',
        deposit: 'Депозит',
        withdraw: 'Вывод',
        trade: 'Торговля',
        copy_experts: 'Копировать Экспертов',
        news: 'Новости',
        nfts: 'NFTs',
        signals: 'Сигналы',
        loans: 'Крипто-кредиты',
        trade_history: 'История торговли',
        transactions: 'Транзакции',
        referrals: 'Рефералы',
        logout: 'Выход',
        live_analysis: 'Живой Анализ',
        forex_analysis: 'Анализ Forex',
        crypto_analysis: 'Анализ Крипто',
        stock_analysis: 'Анализ Акций',
        verified: 'Проверено',
        not_verified: 'Не проверено'
    }
};

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
        { code: 'zh', name: '中文' },
        { code: 'ar', name: 'العربية' },
        { code: 'pt', name: 'Português' },
        { code: 'ru', name: 'Русский' }
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
    // Save language to localStorage and cookie
    localStorage.setItem('language', lang);
    document.cookie = "language=" + lang + ";path=/;max-age=" + (365 * 24 * 60 * 60);
    
    // Update language selector button text
    const langSpan = document.querySelector('.language-selector span');
    if (langSpan) {
        langSpan.textContent = lang.toUpperCase();
    }
    
    // Update sidebar translations only
    updateSidebarLanguage(lang);
}

function updateSidebarLanguage(lang) {
    // Only update sidebar if language is supported
    if (!sidebarTranslations[lang]) {
        lang = 'en';
    }
    
    const translations = sidebarTranslations[lang];
    
    // Update all sidebar menu items
    document.querySelectorAll('.sidebar-nav a').forEach(link => {
        const key = link.getAttribute('data-i18n');
        if (key && translations[key]) {
            // Get the SVG (keep it) and replace only the text
            const svg = link.querySelector('svg');
            if (svg) {
                link.textContent = '';
                link.appendChild(svg);
                link.appendChild(document.createTextNode(' ' + translations[key]));
            } else {
                link.textContent = translations[key];
            }
        }
    });
    
    // Update Live Analysis header
    const liveAnalysisHeader = document.querySelector('.sidebar-dropdown-header span');
    if (liveAnalysisHeader) {
        liveAnalysisHeader.textContent = translations['live_analysis'] || 'Live Analysis';
    }
    
    // Update Live Analysis submenu items
    document.querySelectorAll('.sidebar-dropdown-content a').forEach(link => {
        const key = link.getAttribute('data-i18n');
        if (key && translations[key]) {
            link.textContent = translations[key];
        }
    });
    
    // Update user status
    const userStatus = document.querySelector('.sidebar-user-status');
    if (userStatus) {
        const statusKey = userStatus.getAttribute('data-i18n');
        if (statusKey && translations[statusKey]) {
            userStatus.textContent = translations[statusKey];
        }
    }
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
