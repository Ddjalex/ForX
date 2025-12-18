-- Seed Asset Types
INSERT INTO asset_types (name, display_name, sort_order) VALUES 
('crypto', 'Crypto', 1),
('forex', 'Forex', 2),
('stock', 'Stock', 3)
ON CONFLICT (name) DO UPDATE SET display_name = EXCLUDED.display_name, sort_order = EXCLUDED.sort_order;

-- Delete existing markets to refresh
DELETE FROM prices WHERE market_id IN (SELECT id FROM markets);
DELETE FROM markets;

-- Crypto Assets (display as USDT/XXX format)
INSERT INTO markets (symbol, name, display_name, type, symbol_api, symbol_tradingview, spread, max_leverage, min_trade_size, fee, status, asset_type_id) VALUES 
('BTCUSDT', 'Bitcoin', 'USDT/BTC', 'crypto', 'BTCUSDT', 'BINANCE:BTCUSDT', 0.0005, 100, 0.001, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('ETHUSDT', 'Ethereum', 'USDT/ETH', 'crypto', 'ETHUSDT', 'BINANCE:ETHUSDT', 0.0005, 100, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('TRXUSDT', 'Tron', 'USDT/TRX', 'crypto', 'TRXUSDT', 'BINANCE:TRXUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('SOLUSDT', 'Solana', 'USDT/SOL', 'crypto', 'SOLUSDT', 'BINANCE:SOLUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('LTCUSDT', 'Litecoin', 'USDT/LTC', 'crypto', 'LTCUSDT', 'BINANCE:LTCUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('BNBUSDT', 'Binance Coin', 'USDT/BNB', 'crypto', 'BNBUSDT', 'BINANCE:BNBUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('LINKUSDT', 'Chainlink', 'USDT/LINK', 'crypto', 'LINKUSDT', 'BINANCE:LINKUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('FTTUSDT', 'FTX Token', 'USDT/FTT', 'crypto', 'FTTUSDT', 'BINANCE:FTTUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('SHIBUSDT', 'Shiba Inu', 'USDT/SHIB', 'crypto', 'SHIBUSDT', 'BINANCE:SHIBUSDT', 0.001, 50, 1000000, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('ETCUSDT', 'Ethereum Classic', 'USDT/ETC', 'crypto', 'ETCUSDT', 'BINANCE:ETCUSDT', 0.001, 50, 0.1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('TFUELUSDT', 'Theta Fuel', 'USDT/TFUEL', 'crypto', 'TFUELUSDT', 'BINANCE:TFUELUSDT', 0.001, 50, 10, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('ADAUSDT', 'Cardano', 'USDT/ADA', 'crypto', 'ADAUSDT', 'BINANCE:ADAUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('VETUSDT', 'VeChain', 'USDT/VET', 'crypto', 'VETUSDT', 'BINANCE:VETUSDT', 0.001, 50, 100, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('XRPUSDT', 'Ripple', 'USDT/XRP', 'crypto', 'XRPUSDT', 'BINANCE:XRPUSDT', 0.001, 50, 1, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto')),
('DOGEUSDT', 'Dogecoin', 'USDT/DOGE', 'crypto', 'DOGEUSDT', 'BINANCE:DOGEUSDT', 0.001, 50, 10, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'crypto'))
ON CONFLICT (symbol) DO UPDATE SET 
    display_name = EXCLUDED.display_name,
    symbol_api = EXCLUDED.symbol_api,
    symbol_tradingview = EXCLUDED.symbol_tradingview,
    asset_type_id = EXCLUDED.asset_type_id;

-- Forex Assets (with full name descriptions)
INSERT INTO markets (symbol, name, display_name, type, symbol_api, symbol_tradingview, spread, max_leverage, min_trade_size, fee, status, asset_type_id) VALUES 
('GBPUSD', 'Pound vs US Dollar', 'GBPUSD, Pound vs US Dollar', 'forex', 'GBPUSD', 'FX:GBPUSD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('EURAUD', 'Euro vs Australian Dollar', 'EURAUD, Euro vs Australian Dollar', 'forex', 'EURAUD', 'FX:EURAUD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('EURCHF', 'Euro vs Swiss Franc', 'EURCHF, Euro vs Swiss Franc', 'forex', 'EURCHF', 'FX:EURCHF', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('EURGBP', 'Euro vs Great Britain', 'EURGBP, Euro vs Great Britain', 'forex', 'EURGBP', 'FX:EURGBP', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('GBPCAD', 'Great Britain Pound vs Canadian Dollar', 'GBPCAD, Great Britain Pound vs Canadian Dollar', 'forex', 'GBPCAD', 'FX:GBPCAD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('NZDUSD', 'New Zealand vs US Dollar', 'NZDUSD, New Zealand vs US Dollar', 'forex', 'NZDUSD', 'FX:NZDUSD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('EURNZD', 'Euro vs New Zealand', 'EURNZD, Euro vs New Zealand', 'forex', 'EURNZD', 'FX:EURNZD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('CADJPY', 'Canadian Dollar vs Japanese Yen', 'CADJPY, Canadian Dollar vs Japanise Yen', 'forex', 'CADJPY', 'FX:CADJPY', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('AUDJPY', 'Australian Dollar vs Japanese Yen', 'AUDJPY, Australian Dollar vs Japanise Yen', 'forex', 'AUDJPY', 'FX:AUDJPY', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('USDCHF', 'US Dollar vs Swiss Franc', 'USDCHF, US Dollar vs Swiss Franc', 'forex', 'USDCHF', 'FX:USDCHF', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('GBPAUD', 'Great Britain Pound vs Australian Dollar', 'GBPAUD, Great Britain Pound vs Australian Dollar', 'forex', 'GBPAUD', 'FX:GBPAUD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('USDTRY', 'US Dollar vs Turkish New Lira', 'USDTRY, US Dollar vs Turkish New Lira', 'forex', 'USDTRY', 'FX:USDTRY', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('USDTHB', 'US Dollar vs Thai Baht', 'USD vs THB', 'forex', 'USDTHB', 'FX:USDTHB', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('AUDUSD', 'Australian Dollar vs US Dollar', 'AUD vs USD', 'forex', 'AUDUSD', 'FX:AUDUSD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('EURUSD', 'Euro vs US Dollar', 'EURUSD, Euro vs US Dollar', 'forex', 'EURUSD', 'FX:EURUSD', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex')),
('USDJPY', 'US Dollar vs Japanese Yen', 'USDJPY, US Dollar vs Japanese Yen', 'forex', 'USDJPY', 'FX:USDJPY', 0.0001, 500, 0.01, 0.0001, 'active', (SELECT id FROM asset_types WHERE name = 'forex'))
ON CONFLICT (symbol) DO UPDATE SET 
    display_name = EXCLUDED.display_name,
    symbol_api = EXCLUDED.symbol_api,
    symbol_tradingview = EXCLUDED.symbol_tradingview,
    asset_type_id = EXCLUDED.asset_type_id;

-- Stock Assets
INSERT INTO markets (symbol, name, display_name, type, symbol_api, symbol_tradingview, spread, max_leverage, min_trade_size, fee, status, asset_type_id) VALUES 
('META', 'Meta Platforms', 'FACEBOOK INC', 'stock', 'META', 'NASDAQ:META', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('BA', 'Boeing Company', 'BOEING CO', 'stock', 'BA', 'NYSE:BA', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('AAPL', 'Apple Inc', 'APPLE INC', 'stock', 'AAPL', 'NASDAQ:AAPL', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('AMZN', 'Amazon.com Inc', 'AMAZON COM INC', 'stock', 'AMZN', 'NASDAQ:AMZN', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('MSFT', 'Microsoft Corporation', 'MICROSOFT CORP', 'stock', 'MSFT', 'NASDAQ:MSFT', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('NFLX', 'Netflix Inc', 'NETFLIX INC', 'stock', 'NFLX', 'NASDAQ:NFLX', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('MU', 'Micron Technology', 'MICRON TECHNOLOGY INC', 'stock', 'MU', 'NASDAQ:MU', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('NVDA', 'NVIDIA Corporation', 'NVIDIA CORP', 'stock', 'NVDA', 'NASDAQ:NVDA', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('CGC', 'Canopy Growth Corp', 'CANOPY GROWTH CORP', 'stock', 'CGC', 'NASDAQ:CGC', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('TSLA', 'Tesla Inc', 'TESLA INC', 'stock', 'TSLA', 'NASDAQ:TSLA', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('TWTR', 'Twitter Inc', 'TWITTER INC', 'stock', 'TWTR', 'NYSE:TWTR', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('SBER', 'Sberbank of Russia', 'SBERBANK RUSSIA', 'stock', 'SBER', 'MOEX:SBER', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('CRON', 'Cronos Group Inc', 'CRONOS GROUP INC', 'stock', 'CRON', 'NASDAQ:CRON', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock')),
('GOOGL', 'Alphabet Inc', 'ALPHABET INC', 'stock', 'GOOGL', 'NASDAQ:GOOGL', 0.001, 20, 0.01, 0.001, 'active', (SELECT id FROM asset_types WHERE name = 'stock'))
ON CONFLICT (symbol) DO UPDATE SET 
    display_name = EXCLUDED.display_name,
    symbol_api = EXCLUDED.symbol_api,
    symbol_tradingview = EXCLUDED.symbol_tradingview,
    asset_type_id = EXCLUDED.asset_type_id;

-- Insert initial prices for new crypto assets
INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 97500.00, 2.45, 98200.00, 95800.00, 28500000000 FROM markets WHERE symbol = 'BTCUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 3650.00, 1.82, 3720.00, 3580.00, 15200000000 FROM markets WHERE symbol = 'ETHUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.25, 1.20, 0.26, 0.24, 500000000 FROM markets WHERE symbol = 'TRXUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 225.00, 3.25, 232.00, 218.00, 4200000000 FROM markets WHERE symbol = 'SOLUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 98.50, 1.50, 100.00, 97.00, 800000000 FROM markets WHERE symbol = 'LTCUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 685.00, 0.95, 695.00, 678.00, 1850000000 FROM markets WHERE symbol = 'BNBUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 25.50, 2.10, 26.00, 24.80, 650000000 FROM markets WHERE symbol = 'LINKUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.50, -5.00, 2.00, 1.20, 50000000 FROM markets WHERE symbol = 'FTTUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.000025, 3.50, 0.000026, 0.000024, 300000000 FROM markets WHERE symbol = 'SHIBUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 32.00, 1.80, 33.00, 31.50, 400000000 FROM markets WHERE symbol = 'ETCUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.08, 2.00, 0.09, 0.07, 50000000 FROM markets WHERE symbol = 'TFUELUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.05, 1.25, 1.08, 1.02, 850000000 FROM markets WHERE symbol = 'ADAUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.045, 1.50, 0.047, 0.044, 100000000 FROM markets WHERE symbol = 'VETUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 2.35, -0.85, 2.42, 2.28, 3800000000 FROM markets WHERE symbol = 'XRPUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.42, 4.50, 0.44, 0.39, 2100000000 FROM markets WHERE symbol = 'DOGEUSDT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

-- Forex prices
INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.2645, -0.08, 1.2680, 1.2610, 420000000000 FROM markets WHERE symbol = 'GBPUSD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.6550, 0.15, 1.6600, 1.6500, 100000000000 FROM markets WHERE symbol = 'EURAUD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.9350, 0.05, 0.9380, 0.9320, 80000000000 FROM markets WHERE symbol = 'EURCHF'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.8295, 0.18, 0.8320, 0.8270, 95000000000 FROM markets WHERE symbol = 'EURGBP'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.7850, 0.10, 1.7900, 1.7800, 60000000000 FROM markets WHERE symbol = 'GBPCAD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.5685, -0.22, 0.5720, 0.5660, 85000000000 FROM markets WHERE symbol = 'NZDUSD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.8420, 0.08, 1.8480, 1.8360, 40000000000 FROM markets WHERE symbol = 'EURNZD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 110.50, 0.12, 111.00, 110.00, 50000000000 FROM markets WHERE symbol = 'CADJPY'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 99.80, 0.20, 100.20, 99.40, 45000000000 FROM markets WHERE symbol = 'AUDJPY'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.8925, 0.05, 0.8950, 0.8900, 120000000000 FROM markets WHERE symbol = 'USDCHF'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.9250, 0.12, 1.9300, 1.9200, 30000000000 FROM markets WHERE symbol = 'GBPAUD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 34.50, 0.50, 35.00, 34.00, 20000000000 FROM markets WHERE symbol = 'USDTRY'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 35.80, 0.08, 36.00, 35.60, 15000000000 FROM markets WHERE symbol = 'USDTHB'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.6385, -0.15, 0.6420, 0.6360, 180000000000 FROM markets WHERE symbol = 'AUDUSD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.0485, 0.12, 1.0520, 1.0455, 850000000000 FROM markets WHERE symbol = 'EURUSD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 154.85, 0.25, 155.20, 154.50, 380000000000 FROM markets WHERE symbol = 'USDJPY'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

-- Stock prices
INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 580.00, 1.25, 585.00, 575.00, 15000000 FROM markets WHERE symbol = 'META'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 178.00, -0.50, 180.00, 176.00, 8000000 FROM markets WHERE symbol = 'BA'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 195.00, 0.80, 197.00, 193.00, 45000000 FROM markets WHERE symbol = 'AAPL'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 225.00, 1.50, 228.00, 222.00, 35000000 FROM markets WHERE symbol = 'AMZN'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 445.00, 0.90, 448.00, 442.00, 20000000 FROM markets WHERE symbol = 'MSFT'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 920.00, 2.10, 935.00, 910.00, 5000000 FROM markets WHERE symbol = 'NFLX'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 108.00, 1.80, 110.00, 106.00, 25000000 FROM markets WHERE symbol = 'MU'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 138.00, 2.50, 140.00, 135.00, 50000000 FROM markets WHERE symbol = 'NVDA'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 4.50, -1.20, 4.80, 4.30, 3000000 FROM markets WHERE symbol = 'CGC'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 245.00, 1.80, 250.00, 240.00, 100000000 FROM markets WHERE symbol = 'TSLA'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 54.00, 0.00, 55.00, 53.00, 2000000 FROM markets WHERE symbol = 'TWTR'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 280.00, 0.50, 285.00, 275.00, 1000000 FROM markets WHERE symbol = 'SBER'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 2.20, -0.80, 2.40, 2.10, 1500000 FROM markets WHERE symbol = 'CRON'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 178.00, 0.95, 180.00, 176.00, 25000000 FROM markets WHERE symbol = 'GOOGL'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price;
