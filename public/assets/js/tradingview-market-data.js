const cryptoData = [
    { symbol: 'BTC', name: 'Bitcoin', mktCap: '$1.2T', fdMktCap: '$1.18T', price: '$104,256', availCoins: '21M', totalCoins: '21M' },
    { symbol: 'ETH', name: 'Ethereum', mktCap: '$478B', fdMktCap: '$465B', price: '$3,892', availCoins: '120M', totalCoins: '120M' },
    { symbol: '#TRUMP', name: 'Trump', mktCap: '967.2M', fdMktCap: '4.93B', price: '4.934', availCoins: '200M', totalCoins: '1E' },
    { symbol: 'finch', name: 'Finch Token', mktCap: '211.29M', fdMktCap: '226.04M', price: '0.15069114', availCoins: '1.4B', totalCoins: '1.5E' },
    { symbol: 'ACryptoS', name: 'Akropolis Synths', mktCap: '13.38M', fdMktCap: '26.71M', price: '0.0002983', availCoins: '44.86B', totalCoins: '89.54E' },
    { symbol: 'AI', name: 'AI Protocol', mktCap: '16.67M', fdMktCap: '36.78M', price: '0.03673376', availCoins: '453.31M', totalCoins: '1E' },
    { symbol: 'API3', name: 'API3', mktCap: '36.47M', fdMktCap: '66.19M', price: '0.422000', availCoins: '86.42M', totalCoins: '156.84M' },
    { symbol: 'ARPA', name: 'ARPA Chain', mktCap: '18.69M', fdMktCap: '24.59M', price: '0.01229653', availCoins: '1.52B', totalCoins: '2E' }
];

const stockData = [
    { symbol: 'NASDAQ', name: 'NASDAQ', value: '97.46', change: '2.10', changePercent: '2.20%', open: '95.36', high: '97.72', low: '95' },
    { symbol: 'AAPL', name: 'APPLE STOCK', value: '270.97', change: '-2.70', changePercent: '-0.99%', open: '272.86', high: '273.88', low: '270' },
    { symbol: 'GOOGL', name: 'GOOGL', value: '309.78', change: '2.62', changePercent: '0.89%', open: '309.88', high: '310.13', low: '308' },
    { symbol: 'NVAX', name: 'NVAX NA', value: '6.89', change: '0.23', changePercent: '3.45%', open: '6.71', high: '6.98', low: '6.80' },
    { symbol: 'AMZN', name: 'AMAZON', value: '228.43', change: '1.08', changePercent: '0.48%', open: '228.61', high: '229.48', low: '227' },
    { symbol: 'FACEB', name: 'FACEBOOK', value: '1.54', change: '0.05', changePercent: '3.35%', open: '1.50', high: '1.60', low: '1.50' },
    { symbol: 'TSLA', name: 'TESLA', value: '280.50', change: '5.50', changePercent: '2.00%', open: '275.00', high: '285.00', low: '274.50' }
];

function loadCryptoMarketData() {
    const tbody = document.getElementById('crypto-market-body');
    if (!tbody) return;

    let html = '';
    cryptoData.forEach(crypto => {
        html += `<tr onclick="navigateToTrade('${crypto.symbol}')">
            <td class="market-symbol"><span style="display: flex; align-items: center; gap: 8px;"><span style="width: 24px; height: 24px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: var(--bg-dark);">${crypto.symbol.charAt(0)}</span><strong>${crypto.symbol}</strong></span></td>
            <td>${crypto.mktCap}</td>
            <td>${crypto.fdMktCap}</td>
            <td class="positive">${crypto.price}</td>
            <td>${crypto.availCoins}</td>
            <td>${crypto.totalCoins}</td>
        </tr>`;
    });
    tbody.innerHTML = html;
}

function loadStockMarketData() {
    const tbody = document.getElementById('stock-market-body');
    if (!tbody) return;

    let html = '';
    stockData.forEach(stock => {
        const isPositive = !stock.change.startsWith('-') && stock.change !== 'N/A';
        const changeClass = isPositive ? 'positive' : stock.change === 'N/A' ? '' : 'negative';
        
        html += `<tr onclick="navigateToTrade('${stock.symbol}')">
            <td class="market-symbol"><span style="display: flex; align-items: center; gap: 8px;"><span style="width: 24px; height: 24px; background: var(--primary); border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: var(--bg-dark);">◆</span><strong>${stock.name}</strong></span></td>
            <td>${stock.value}</td>
            <td class="${changeClass}">${stock.change}</td>
            <td class="${changeClass}">${stock.changePercent}</td>
            <td>${stock.open}</td>
            <td>${stock.high}</td>
            <td>${stock.low}</td>
        </tr>`;
    });
    tbody.innerHTML = html;
}

function navigateToTrade(symbol) {
    const assetNameSelect = document.getElementById('assetName');
    if (assetNameSelect) {
        const option = Array.from(assetNameSelect.options).find(opt => 
            opt.getAttribute('data-symbol') === symbol
        );
        if (option) {
            assetNameSelect.value = option.value;
            assetNameSelect.dispatchEvent(new Event('change'));
            const element = document.getElementById('assetName');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    }
}

function initMarketData() {
    loadCryptoMarketData();
    loadStockMarketData();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMarketData);
} else {
    initMarketData();
}

setInterval(() => {
    loadCryptoMarketData();
    loadStockMarketData();
}, 30000);

window.updateMarketData = function() {
    loadCryptoMarketData();
    loadStockMarketData();
};
