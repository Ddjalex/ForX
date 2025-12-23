// Real-time cryptocurrency ticker prices from CoinGecko
(function() {
    const priceMap = {
        'btc-price': { crypto: 'bitcoin', decimals: 2 },
        'eth-price': { crypto: 'ethereum', decimals: 2 },
        'usdt-price': { crypto: 'tether', decimals: 2 },
        'bnb-price': { crypto: 'binancecoin', decimals: 2 },
        'xrp-price': { crypto: 'ripple', decimals: 4 },
        'sol-price': { crypto: 'solana', decimals: 2 },
        'ada-price': { crypto: 'cardano', decimals: 4 },
        'dot-price': { crypto: 'polkadot', decimals: 2 }
    };

    async function updateTickerPrices() {
        try {
            const ids = Object.values(priceMap).map(p => p.crypto).join(',');
            const response = await fetch(`https://api.coingecko.com/api/v3/simple/price?ids=${ids}&vs_currencies=usd`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`API error: ${response.status}`);
            }

            const data = await response.json();

            // Update all price elements
            for (const [elementId, config] of Object.entries(priceMap)) {
                const element = document.getElementById(elementId);
                if (element && data[config.crypto]) {
                    const price = data[config.crypto].usd;
                    const formattedPrice = '$' + price.toLocaleString('en-US', {
                        minimumFractionDigits: config.decimals,
                        maximumFractionDigits: config.decimals
                    });
                    element.textContent = formattedPrice;
                    element.style.color = '#00D4AA';
                }
            }
        } catch (error) {
            console.error('Error fetching crypto prices from CoinGecko:', error);
        }
    }

    function initTicker() {
        // First update immediately
        updateTickerPrices();
        
        // Then update every 60 seconds
        setInterval(updateTickerPrices, 60000);
    }

    // Execute when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTicker);
    } else {
        // DOM is already loaded
        setTimeout(initTicker, 100);
    }

    // Also expose to window for manual updates
    window.updateTickerPrices = updateTickerPrices;
})();
