<?php
$title = 'Trading Terminal - TradePro';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/lightweight-charts@4.1.0/dist/lightweight-charts.standalone.production.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, sans-serif; background: #0d0d0d; color: #fff; overflow: hidden; }
        
        .terminal { display: grid; grid-template-columns: 280px 1fr 320px; grid-template-rows: 50px 1fr 40px; height: 100vh; }
        
        .header { grid-column: 1 / -1; background: #1a1a1a; display: flex; align-items: center; padding: 0 15px; border-bottom: 1px solid #2a2a2a; }
        .logo { color: #f5a623; font-weight: 700; font-size: 18px; margin-right: 30px; }
        .market-tabs { display: flex; gap: 5px; flex: 1; overflow-x: auto; }
        .market-tab { display: flex; align-items: center; gap: 8px; padding: 8px 15px; background: #252525; border-radius: 6px; cursor: pointer; white-space: nowrap; }
        .market-tab.active { background: #333; border: 1px solid #444; }
        .market-tab .symbol { font-weight: 600; }
        .market-tab .indicator { width: 8px; height: 20px; border-radius: 2px; }
        .market-tab .indicator.up { background: linear-gradient(to top, #22c55e, #4ade80); }
        .market-tab .indicator.down { background: linear-gradient(to top, #ef4444, #f87171); }
        .add-tab { padding: 8px 12px; background: #252525; border-radius: 6px; cursor: pointer; color: #888; }
        .header-right { display: flex; align-items: center; gap: 15px; margin-left: auto; }
        .account-info { display: flex; align-items: center; gap: 10px; background: #252525; padding: 6px 12px; border-radius: 6px; }
        .account-badge { background: #22c55e; color: #000; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .account-balance { font-weight: 600; }
        
        .sidebar { background: #141414; border-right: 1px solid #2a2a2a; display: flex; flex-direction: column; }
        .sidebar-header { padding: 15px; border-bottom: 1px solid #2a2a2a; display: flex; align-items: center; justify-content: space-between; }
        .sidebar-header h3 { font-size: 14px; font-weight: 500; }
        .search-box { padding: 10px 15px; }
        .search-input { width: 100%; background: #1e1e1e; border: 1px solid #333; border-radius: 6px; padding: 10px 12px; color: #fff; }
        .search-input::placeholder { color: #666; }
        .instruments-list { flex: 1; overflow-y: auto; }
        .instrument { display: grid; grid-template-columns: 30px 1fr 70px 50px; align-items: center; padding: 12px 15px; border-bottom: 1px solid #1e1e1e; cursor: pointer; }
        .instrument:hover { background: #1a1a1a; }
        .instrument.active { background: #1e1e1e; border-left: 2px solid #f5a623; }
        .instrument-icon { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; }
        .instrument-icon.crypto { background: #f7931a; color: #fff; }
        .instrument-icon.forex { background: #4a5568; color: #fff; }
        .instrument-name { font-weight: 500; font-size: 13px; }
        .instrument-signal { display: flex; gap: 2px; }
        .signal-bar { width: 3px; height: 12px; background: #333; border-radius: 1px; }
        .signal-bar.active { background: #ef4444; }
        .instrument-price { text-align: right; font-size: 13px; }
        .instrument-change { text-align: right; font-size: 12px; }
        .instrument-change.up { color: #22c55e; }
        .instrument-change.down { color: #ef4444; }
        
        .chart-area { background: #0d0d0d; display: flex; flex-direction: column; }
        .chart-header { padding: 10px 15px; border-bottom: 1px solid #2a2a2a; display: flex; align-items: center; gap: 15px; }
        .chart-symbol { font-size: 14px; }
        .chart-symbol span { color: #888; }
        .chart-ohlc { display: flex; gap: 15px; font-size: 12px; color: #888; }
        .chart-ohlc span { color: #22c55e; }
        .chart-tools { display: flex; gap: 5px; margin-left: auto; }
        .chart-tools button { background: #1e1e1e; border: none; color: #888; padding: 6px 10px; border-radius: 4px; cursor: pointer; }
        .chart-tools button:hover { background: #252525; color: #fff; }
        .timeframes { display: flex; gap: 5px; }
        .timeframe { background: transparent; border: none; color: #888; padding: 6px 10px; cursor: pointer; border-radius: 4px; }
        .timeframe.active { background: #252525; color: #fff; }
        .chart-container { flex: 1; position: relative; }
        #chart { width: 100%; height: 100%; }
        
        .positions-panel { border-top: 1px solid #2a2a2a; }
        .positions-tabs { display: flex; padding: 10px 15px; gap: 20px; border-bottom: 1px solid #2a2a2a; }
        .positions-tab { background: none; border: none; color: #888; cursor: pointer; font-size: 13px; padding: 5px 0; }
        .positions-tab.active { color: #fff; border-bottom: 2px solid #f5a623; }
        .positions-content { padding: 20px; text-align: center; color: #666; }
        .positions-empty { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 30px; }
        .positions-empty i { font-size: 32px; color: #444; }
        
        .order-panel { background: #141414; border-left: 1px solid #2a2a2a; display: flex; flex-direction: column; }
        .order-header { padding: 15px; border-bottom: 1px solid #2a2a2a; display: flex; align-items: center; justify-content: space-between; }
        .order-symbol { font-weight: 600; }
        .order-types { display: flex; padding: 15px; gap: 10px; }
        .order-type { flex: 1; padding: 10px; text-align: center; background: #1e1e1e; border: 1px solid #333; border-radius: 6px; cursor: pointer; }
        .order-type.active { border-color: #f5a623; }
        .order-prices { display: flex; padding: 0 15px 15px; gap: 10px; }
        .price-box { flex: 1; text-align: center; padding: 15px; border-radius: 8px; }
        .price-box.sell { background: linear-gradient(135deg, #7f1d1d, #991b1b); }
        .price-box.buy { background: linear-gradient(135deg, #14532d, #166534); }
        .price-label { font-size: 11px; color: rgba(255,255,255,0.7); margin-bottom: 5px; }
        .price-value { font-size: 20px; font-weight: 700; }
        .price-change { font-size: 11px; opacity: 0.8; margin-top: 3px; }
        .order-form { flex: 1; padding: 15px; overflow-y: auto; }
        .form-row { margin-bottom: 15px; }
        .form-label { font-size: 12px; color: #888; margin-bottom: 6px; display: block; }
        .input-group { display: flex; align-items: center; background: #1e1e1e; border: 1px solid #333; border-radius: 6px; }
        .input-group input { flex: 1; background: transparent; border: none; color: #fff; padding: 12px; }
        .input-group .unit { padding: 0 12px; color: #888; }
        .input-group button { background: transparent; border: none; color: #888; padding: 12px; cursor: pointer; }
        .input-group button:hover { color: #fff; }
        .order-submit { padding: 15px; border-top: 1px solid #2a2a2a; }
        .submit-btn { width: 100%; padding: 15px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .submit-btn.buy { background: #22c55e; color: #fff; }
        .submit-btn.sell { background: #ef4444; color: #fff; }
        
        .footer { grid-column: 1 / -1; background: #1a1a1a; display: flex; align-items: center; padding: 0 15px; gap: 30px; font-size: 13px; border-top: 1px solid #2a2a2a; }
        .footer-item { display: flex; gap: 8px; }
        .footer-label { color: #888; }
        .footer-value { font-weight: 500; }
        
        .back-link { color: #888; text-decoration: none; margin-right: 20px; }
        .back-link:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="terminal">
        <header class="header">
            <a href="/accounts" class="back-link"><i class="fas fa-arrow-left"></i></a>
            <div class="logo">TradePro</div>
            <div class="market-tabs" id="marketTabs">
                <div class="market-tab active" data-symbol="XAU/USD">
                    <div class="indicator up"></div>
                    <span class="symbol">XAU/USD</span>
                </div>
                <div class="market-tab" data-symbol="BTC/USD">
                    <i class="fab fa-bitcoin" style="color: #f7931a;"></i>
                    <span class="symbol">BTC</span>
                </div>
            </div>
            <div class="add-tab"><i class="fas fa-plus"></i></div>
            <div class="header-right">
                <div class="account-info">
                    <span class="account-badge"><?= ucfirst($account['account_type']) ?></span>
                    <span><?= $account['account_name'] ?></span>
                    <span class="account-balance"><?= number_format($account['balance'], 2) ?> <?= $account['currency'] ?></span>
                </div>
                <a href="/accounts" class="btn btn-outline btn-sm" style="background:#252525;border:1px solid #444;padding:8px 15px;border-radius:6px;color:#fff;text-decoration:none;">
                    <i class="fas fa-wallet"></i> Deposit
                </a>
            </div>
        </header>
        
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>INSTRUMENTS</h3>
                <button style="background:none;border:none;color:#888;cursor:pointer;"><i class="fas fa-ellipsis-v"></i></button>
            </div>
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Search" id="instrumentSearch">
            </div>
            <div class="instruments-list" id="instrumentsList">
                <?php foreach ($markets as $market): ?>
                <div class="instrument" data-symbol="<?= $market['symbol'] ?>" data-price="<?= $market['price'] ?? 0 ?>">
                    <div class="instrument-icon <?= $market['type'] ?>">
                        <?php if ($market['type'] === 'crypto'): ?>
                            <i class="fab fa-bitcoin"></i>
                        <?php else: ?>
                            $
                        <?php endif; ?>
                    </div>
                    <div class="instrument-name"><?= $market['symbol'] ?></div>
                    <div class="instrument-price"><?= number_format($market['price'] ?? 0, $market['type'] === 'forex' ? 5 : 2) ?></div>
                    <div class="instrument-change <?= ($market['change_24h'] ?? 0) >= 0 ? 'up' : 'down' ?>">
                        <?= ($market['change_24h'] ?? 0) >= 0 ? '+' : '' ?><?= number_format($market['change_24h'] ?? 0, 2) ?>%
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </aside>
        
        <main class="chart-area">
            <div class="chart-header">
                <div class="chart-symbol" id="chartSymbol">Gold vs US Dollar <span>• 1</span></div>
                <div class="chart-ohlc">
                    O<span id="ohlcOpen">4,335.744</span>
                    H<span id="ohlcHigh">4,336.639</span>
                    L<span id="ohlcLow">4,335.528</span>
                    C<span id="ohlcClose">4,335.946</span>
                </div>
                <div class="timeframes">
                    <button class="timeframe">1m</button>
                    <button class="timeframe active">5m</button>
                    <button class="timeframe">15m</button>
                    <button class="timeframe">1h</button>
                    <button class="timeframe">4h</button>
                    <button class="timeframe">1D</button>
                </div>
                <div class="chart-tools">
                    <button title="Crosshair"><i class="fas fa-crosshairs"></i></button>
                    <button title="Drawing"><i class="fas fa-pencil-alt"></i></button>
                    <button title="Indicators"><i class="fas fa-chart-bar"></i></button>
                    <button title="Settings"><i class="fas fa-cog"></i></button>
                </div>
            </div>
            <div class="chart-container">
                <div id="chart"></div>
            </div>
            <div class="positions-panel">
                <div class="positions-tabs">
                    <button class="positions-tab active">Open</button>
                    <button class="positions-tab">Pending</button>
                    <button class="positions-tab">Closed</button>
                </div>
                <div class="positions-content">
                    <div class="positions-empty">
                        <i class="fas fa-inbox"></i>
                        <span>No open positions</span>
                    </div>
                </div>
            </div>
        </main>
        
        <aside class="order-panel">
            <div class="order-header">
                <span class="order-symbol" id="orderSymbol">XAU/USD</span>
                <button style="background:none;border:none;color:#888;"><i class="fas fa-chevron-down"></i></button>
            </div>
            <div class="order-types">
                <div class="order-type active">Market</div>
                <div class="order-type">Pending</div>
            </div>
            <div class="order-prices">
                <div class="price-box sell">
                    <div class="price-label">Sell</div>
                    <div class="price-value" id="sellPrice">4,335.54</div>
                    <div class="price-change">0.16 USD</div>
                </div>
                <div class="price-box buy">
                    <div class="price-label">Buy</div>
                    <div class="price-value" id="buyPrice">4,335.70</div>
                    <div class="price-change">28%</div>
                </div>
            </div>
            <div class="order-form">
                <div class="form-row">
                    <label class="form-label">Volume</label>
                    <div class="input-group">
                        <button onclick="adjustVolume(-0.01)">-</button>
                        <input type="number" id="volume" value="0.01" step="0.01" min="0.01">
                        <span class="unit">Lots</span>
                        <button onclick="adjustVolume(0.01)">+</button>
                    </div>
                </div>
                <div class="form-row">
                    <label class="form-label">Take Profit</label>
                    <div class="input-group">
                        <input type="text" id="takeProfit" placeholder="Not set">
                        <span class="unit">Price</span>
                        <button><i class="fas fa-minus"></i></button>
                        <button><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="form-row">
                    <label class="form-label">Stop Loss</label>
                    <div class="input-group">
                        <input type="text" id="stopLoss" placeholder="Not set">
                        <span class="unit">Price</span>
                        <button><i class="fas fa-minus"></i></button>
                        <button><i class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
            <div class="order-submit">
                <button class="submit-btn buy" id="submitOrder">Buy XAU/USD</button>
            </div>
        </aside>
        
        <footer class="footer">
            <div class="footer-item">
                <span class="footer-label">Equity:</span>
                <span class="footer-value"><?= number_format($account['equity'], 2) ?> <?= $account['currency'] ?></span>
            </div>
            <div class="footer-item">
                <span class="footer-label">Free Margin:</span>
                <span class="footer-value"><?= number_format($account['free_margin'], 2) ?> <?= $account['currency'] ?></span>
            </div>
            <div class="footer-item">
                <span class="footer-label">Balance:</span>
                <span class="footer-value"><?= number_format($account['balance'], 2) ?> <?= $account['currency'] ?></span>
            </div>
            <div class="footer-item">
                <span class="footer-label">Margin:</span>
                <span class="footer-value"><?= number_format($account['margin_used'], 2) ?> <?= $account['currency'] ?></span>
            </div>
        </footer>
    </div>

    <script>
        const chart = LightweightCharts.createChart(document.getElementById('chart'), {
            layout: {
                background: { color: '#0d0d0d' },
                textColor: '#888',
            },
            grid: {
                vertLines: { color: '#1e1e1e' },
                horzLines: { color: '#1e1e1e' },
            },
            crosshair: {
                mode: LightweightCharts.CrosshairMode.Normal,
            },
            rightPriceScale: {
                borderColor: '#2a2a2a',
            },
            timeScale: {
                borderColor: '#2a2a2a',
                timeVisible: true,
            },
        });

        const candlestickSeries = chart.addCandlestickSeries({
            upColor: '#22c55e',
            downColor: '#ef4444',
            borderDownColor: '#ef4444',
            borderUpColor: '#22c55e',
            wickDownColor: '#ef4444',
            wickUpColor: '#22c55e',
        });

        function generateCandleData() {
            const data = [];
            let time = Math.floor(Date.now() / 1000) - 86400;
            let price = 4335;
            
            for (let i = 0; i < 200; i++) {
                const open = price + (Math.random() - 0.5) * 5;
                const close = open + (Math.random() - 0.5) * 8;
                const high = Math.max(open, close) + Math.random() * 3;
                const low = Math.min(open, close) - Math.random() * 3;
                
                data.push({ time, open, high, low, close });
                time += 300;
                price = close;
            }
            return data;
        }

        candlestickSeries.setData(generateCandleData());
        chart.timeScale().fitContent();

        window.addEventListener('resize', () => {
            chart.applyOptions({ width: document.getElementById('chart').offsetWidth });
        });

        function adjustVolume(delta) {
            const input = document.getElementById('volume');
            let val = parseFloat(input.value) + delta;
            if (val < 0.01) val = 0.01;
            input.value = val.toFixed(2);
        }

        document.querySelectorAll('.instrument').forEach(el => {
            el.addEventListener('click', function() {
                document.querySelectorAll('.instrument').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                const symbol = this.dataset.symbol;
                document.getElementById('orderSymbol').textContent = symbol;
                document.getElementById('submitOrder').textContent = 'Buy ' + symbol;
            });
        });

        document.querySelectorAll('.order-type').forEach(el => {
            el.addEventListener('click', function() {
                document.querySelectorAll('.order-type').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelectorAll('.timeframe').forEach(el => {
            el.addEventListener('click', function() {
                document.querySelectorAll('.timeframe').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.getElementById('instrumentSearch').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.instrument').forEach(el => {
                const symbol = el.dataset.symbol.toLowerCase();
                el.style.display = symbol.includes(query) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
