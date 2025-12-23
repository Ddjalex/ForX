document.addEventListener('DOMContentLoaded', function() {
    initMarketTicker();
    initMarketClicks();
    updateMarketPrices();
    setInterval(updateMarketPrices, 5000);
});

function initMarketTicker() {
    const ticker = document.querySelector('.pro-ticker-track');
    if (!ticker) return;
    
    const items = document.querySelectorAll('.ticker-item');
    items.forEach(item => {
        item.addEventListener('click', handleTickerClick);
        item.style.cursor = 'pointer';
    });
}

function handleTickerClick(e) {
    const symbol = e.currentTarget.getAttribute('data-symbol');
    if (!symbol) return;
    
    const assetNameSelect = document.getElementById('assetName');
    if (assetNameSelect) {
        const option = Array.from(assetNameSelect.options).find(opt => 
            opt.getAttribute('data-symbol') === symbol
        );
        if (option) {
            assetNameSelect.value = option.value;
            assetNameSelect.dispatchEvent(new Event('change'));
            document.getElementById('assetName').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
}

function initMarketClicks() {
    const marketItems = document.querySelectorAll('.market-item');
    marketItems.forEach(item => {
        item.addEventListener('click', handleMarketItemClick);
    });
}

function handleMarketItemClick(e) {
    const href = e.currentTarget.getAttribute('href');
    if (href) {
        window.location.href = href;
    }
}

function updateMarketPrices() {
    const cryptoPrices = {
        'BTC': { price: 104256.78, change: 3.45 },
        'ETH': { price: 3892.45, change: 2.18 },
        'XRP': { price: 2.3456, change: 5.67 },
        'SOL': { price: 218.92, change: -1.23 },
        'BNB': { price: 712.34, change: 0.89 },
        'ADA': { price: 1.0234, change: 4.12 }
    };
    
    const forexPrices = {
        'EURUSD': { price: 1.0523, change: -0.15 },
        'GBPUSD': { price: 1.2678, change: 0.23 },
        'USDJPY': { price: 153.45, change: 0.42 },
        'AUDUSD': { price: 0.6342, change: -0.31 },
        'USDCAD': { price: 1.4234, change: 0.18 },
        'USDCHF': { price: 0.8923, change: -0.08 }
    };
    
    const allPrices = { ...cryptoPrices, ...forexPrices };
    
    for (const [symbol, data] of Object.entries(allPrices)) {
        updateTickerPrice(symbol, data);
    }
}

function updateTickerPrice(symbol, data) {
    const priceElement = document.getElementById(`price-${symbol.toLowerCase()}`);
    const changeElement = document.getElementById(`change-${symbol.toLowerCase()}`);
    
    if (priceElement) {
        priceElement.textContent = formatPrice(data.price, symbol);
        priceElement.style.animation = 'price-pulse 0.5s ease';
        setTimeout(() => {
            priceElement.style.animation = '';
        }, 500);
    }
    
    if (changeElement) {
        changeElement.textContent = (data.change >= 0 ? '+' : '') + data.change.toFixed(2) + '%';
        changeElement.className = 'ticker-change ' + (data.change >= 0 ? 'positive' : 'negative');
    }
}

function formatPrice(price, symbol) {
    if (symbol.includes('USD') || symbol === 'EURUSD' || symbol === 'GBPUSD') {
        return price.toFixed(4);
    }
    if (symbol === 'BTC' || symbol === 'ETH') {
        return '$' + price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    return '$' + price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 8 });
}

function initTradingViewChart() {
    const container = document.getElementById('tradingview_chart');
    if (!container) return;
    
    const selectedOption = document.querySelector('#assetName option:checked');
    const symbol = selectedOption ? selectedOption.getAttribute('data-tradingview') || selectedOption.getAttribute('data-symbol') : 'AAPL';
    
    const script = document.createElement('script');
    script.src = 'https://s3.tradingview.com/tv.js';
    script.async = true;
    script.onload = function() {
        if (typeof TradingView !== 'undefined') {
            new TradingView.widget({
                autosize: true,
                symbol: symbol,
                interval: '1H',
                timezone: 'Etc/UTC',
                theme: 'dark',
                style: '1',
                locale: 'en',
                toolbar_bg: 'rgba(255, 0, 0, 0)',
                enable_publishing: false,
                allow_symbol_change: true,
                container_id: 'tradingview_chart',
                hide_top_toolbar: false,
                hide_legend: false
            });
        }
    };
    container.appendChild(script);
}

document.addEventListener('change', function(e) {
    if (e.target.id === 'assetName') {
        initTradingViewChart();
    }
});

document.addEventListener('DOMContentLoaded', initTradingViewChart);
