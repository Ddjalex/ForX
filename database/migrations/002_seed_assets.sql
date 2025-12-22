-- Seed Asset Types
INSERT INTO asset_types (name, display_name, sort_order) VALUES 
('crypto', 'Crypto', 1),
('forex', 'Forex', 2),
('stock', 'Stock', 3)
ON CONFLICT (name) DO UPDATE SET display_name = EXCLUDED.display_name, sort_order = EXCLUDED.sort_order;

-- Delete existing markets to refresh
DELETE FROM prices WHERE market_id IN (SELECT id FROM markets);
DELETE FROM markets;

-- Crypto Assets (up to 32)
INSERT INTO markets (symbol, name, display_name, type, symbol_api, symbol_tradingview, spread, max_leverage, min_trade_size, fee, status, asset_type_id) VALUES 
('BTCUSDT', 'Bitcoin', 'BTC/USDT', 'crypto', 'BTCUSDT', 'BINANCE:BTCUSDT', 0.0005, 100, 0.001, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('ETHUSDT', 'Ethereum', 'ETH/USDT', 'crypto', 'ETHUSDT', 'BINANCE:ETHUSDT', 0.0005, 100, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('TRXUSDT', 'Tron', 'TRX/USDT', 'crypto', 'TRXUSDT', 'BINANCE:TRXUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('SOLUSDT', 'Solana', 'SOL/USDT', 'crypto', 'SOLUSDT', 'BINANCE:SOLUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('LTCUSDT', 'Litecoin', 'LTC/USDT', 'crypto', 'LTCUSDT', 'BINANCE:LTCUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('BNBUSDT', 'Binance Coin', 'BNB/USDT', 'crypto', 'BNBUSDT', 'BINANCE:BNBUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('LINKUSDT', 'Chainlink', 'LINK/USDT', 'crypto', 'LINKUSDT', 'BINANCE:LINKUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('ADAUSDT', 'Cardano', 'ADA/USDT', 'crypto', 'ADAUSDT', 'BINANCE:ADAUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('XRPUSDT', 'Ripple', 'XRP/USDT', 'crypto', 'XRPUSDT', 'BINANCE:XRPUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('DOGEUSDT', 'Dogecoin', 'DOGE/USDT', 'crypto', 'DOGEUSDT', 'BINANCE:DOGEUSDT', 0.001, 50, 10, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('AVAXUSDT', 'Avalanche', 'AVAX/USDT', 'crypto', 'AVAXUSDT', 'BINANCE:AVAXUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('DOTUSDT', 'Polkadot', 'DOT/USDT', 'crypto', 'DOTUSDT', 'BINANCE:DOTUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('MATICUSDT', 'Polygon', 'MATIC/USDT', 'crypto', 'MATICUSDT', 'BINANCE:MATICUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('UNIUSDT', 'Uniswap', 'UNI/USDT', 'crypto', 'UNIUSDT', 'BINANCE:UNIUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('BCHUSDT', 'Bitcoin Cash', 'BCH/USDT', 'crypto', 'BCHUSDT', 'BINANCE:BCHUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('ALGOUSDT', 'Algorand', 'ALGO/USDT', 'crypto', 'ALGOUSDT', 'BINANCE:ALGOUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('ATOMUSDT', 'Cosmos', 'ATOM/USDT', 'crypto', 'ATOMUSDT', 'BINANCE:ATOMUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('ICPUSDT', 'Internet Computer', 'ICP/USDT', 'crypto', 'ICPUSDT', 'BINANCE:ICPUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('FILUSDT', 'Filecoin', 'FIL/USDT', 'crypto', 'FILUSDT', 'BINANCE:FILUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('VETUSDT', 'VeChain', 'VET/USDT', 'crypto', 'VETUSDT', 'BINANCE:VETUSDT', 0.001, 50, 100, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('SANDUSDT', 'The Sandbox', 'SAND/USDT', 'crypto', 'SANDUSDT', 'BINANCE:SANDUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('MANAUSDT', 'Decentraland', 'MANA/USDT', 'crypto', 'MANAUSDT', 'BINANCE:MANAUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('HBARUSDT', 'Hedera', 'HBAR/USDT', 'crypto', 'HBARUSDT', 'BINANCE:HBARUSDT', 0.001, 50, 10, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('NEARUSDT', 'NEAR Protocol', 'NEAR/USDT', 'crypto', 'NEARUSDT', 'BINANCE:NEARUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('FTMUSDT', 'Fantom', 'FTM/USDT', 'crypto', 'FTMUSDT', 'BINANCE:FTMUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('EGLDUSDT', 'MultiversX', 'EGLD/USDT', 'crypto', 'EGLDUSDT', 'BINANCE:EGLDUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('THETAUSDT', 'Theta Network', 'THETA/USDT', 'crypto', 'THETAUSDT', 'BINANCE:THETAUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('AAVEUSDT', 'Aave', 'AAVE/USDT', 'crypto', 'AAVEUSDT', 'BINANCE:AAVEUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('EOSUSDT', 'EOS', 'EOS/USDT', 'crypto', 'EOSUSDT', 'BINANCE:EOSUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('AXSUSDT', 'Axie Infinity', 'AXS/USDT', 'crypto', 'AXSUSDT', 'BINANCE:AXSUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('FLOWUSDT', 'Flow', 'FLOW/USDT', 'crypto', 'FLOWUSDT', 'BINANCE:FLOWUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('KAVAUSDT', 'Kava', 'KAVA/USDT', 'crypto', 'KAVAUSDT', 'BINANCE:KAVAUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto'))
ON CONFLICT (symbol) DO UPDATE SET 
    display_name = EXCLUDED.display_name,
    symbol_api = EXCLUDED.symbol_api,
    symbol_tradingview = EXCLUDED.symbol_tradingview,
    asset_type_id = EXCLUDED.asset_type_id;

-- Forex Assets
INSERT INTO markets (symbol, name, display_name, type, symbol_api, symbol_tradingview, spread, max_leverage, min_trade_size, fee, status, asset_type_id) VALUES 
('GBPUSD', 'Pound vs US Dollar', 'GBP/USD', 'forex', 'GBPUSD', 'FX:GBPUSD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('EURUSD', 'Euro vs US Dollar', 'EUR/USD', 'forex', 'EURUSD', 'FX:EURUSD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('USDJPY', 'US Dollar vs Japanese Yen', 'USD/JPY', 'forex', 'USDJPY', 'FX:USDJPY', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('AUDUSD', 'Australian Dollar vs US Dollar', 'AUD/USD', 'forex', 'AUDUSD', 'FX:AUDUSD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex'))
ON CONFLICT (symbol) DO UPDATE SET 
    display_name = EXCLUDED.display_name,
    symbol_api = EXCLUDED.symbol_api,
    symbol_tradingview = EXCLUDED.symbol_tradingview,
    asset_type_id = EXCLUDED.asset_type_id;

-- Stock Assets
INSERT INTO markets (symbol, name, display_name, type, symbol_api, symbol_tradingview, spread, max_leverage, min_trade_size, fee, status, asset_type_id) VALUES 
('META', 'Meta Platforms', 'META', 'stock', 'META', 'NASDAQ:META', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('AAPL', 'Apple Inc', 'AAPL', 'stock', 'AAPL', 'NASDAQ:AAPL', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('TSLA', 'Tesla Inc', 'TSLA', 'stock', 'TSLA', 'NASDAQ:TSLA', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('NVDA', 'NVIDIA Corporation', 'NVDA', 'stock', 'NVDA', 'NASDAQ:NVDA', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock'))
ON CONFLICT (symbol) DO UPDATE SET 
    display_name = EXCLUDED.display_name,
    symbol_api = EXCLUDED.symbol_api,
    symbol_tradingview = EXCLUDED.symbol_tradingview,
    asset_type_id = EXCLUDED.asset_type_id;

-- Insert initial prices
INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 98000.00, 2.5, 99000.00, 97000.00, 50000000000 FROM markets
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;
