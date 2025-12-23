// Real-time cryptocurrency ticker prices from CoinGecko
async function updateTickerPrices() {
    try {
        const response = await fetch('https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum,tether,binancecoin,ripple,solana,cardano,polkadot&vs_currencies=usd');
        const data = await response.json();
        
        // Update ticker prices
        const updates = {
            'btc-price': { crypto: 'bitcoin', decimals: 2 },
            'eth-price': { crypto: 'ethereum', decimals: 2 },
            'usdt-price': { crypto: 'tether', decimals: 2 },
            'bnb-price': { crypto: 'binancecoin', decimals: 2 },
            'xrp-price': { crypto: 'ripple', decimals: 4 },
            'sol-price': { crypto: 'solana', decimals: 2 },
            'ada-price': { crypto: 'cardano', decimals: 4 },
            'dot-price': { crypto: 'polkadot', decimals: 2 }
        };
        
        for (const [elementId, config] of Object.entries(updates)) {
            const element = document.getElementById(elementId);
            if (element && data[config.crypto]) {
                const price = data[config.crypto].usd;
                element.textContent = '$' + price.toLocaleString('en-US', {
                    minimumFractionDigits: config.decimals,
                    maximumFractionDigits: config.decimals
                });
                element.parentElement.style.color = '#00D4AA';
            }
        }
    } catch (error) {
        console.error('Error updating ticker prices:', error);
    }
}

// Call on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        updateTickerPrices();
        setInterval(updateTickerPrices, 60000);
    });
} else {
    updateTickerPrices();
    setInterval(updateTickerPrices, 60000);
}
