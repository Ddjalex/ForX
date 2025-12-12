-- Seed data for TradePro

-- Admin user (password: admin123)
INSERT INTO users (name, email, password, role, status, email_verified) VALUES 
('Admin', 'admin@tradepro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin', 'active', true),
('Demo User', 'demo@tradepro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', true)
ON CONFLICT (email) DO NOTHING;

-- Create wallets for users
INSERT INTO wallets (user_id, balance, margin_used)
SELECT id, 10000.00, 0 FROM users WHERE email = 'admin@tradepro.com'
ON CONFLICT DO NOTHING;

INSERT INTO wallets (user_id, balance, margin_used)
SELECT id, 5000.00, 0 FROM users WHERE email = 'demo@tradepro.com'
ON CONFLICT DO NOTHING;

-- Crypto markets
INSERT INTO markets (symbol, name, type, spread, max_leverage, min_trade_size, fee, status) VALUES 
('BTC/USD', 'Bitcoin', 'crypto', 0.0005, 100, 0.001, 0.001, 'active'),
('ETH/USD', 'Ethereum', 'crypto', 0.0005, 100, 0.01, 0.001, 'active'),
('BNB/USD', 'Binance Coin', 'crypto', 0.001, 50, 0.1, 0.001, 'active'),
('SOL/USD', 'Solana', 'crypto', 0.001, 50, 0.1, 0.001, 'active'),
('XRP/USD', 'Ripple', 'crypto', 0.001, 50, 1, 0.001, 'active'),
('ADA/USD', 'Cardano', 'crypto', 0.001, 50, 1, 0.001, 'active'),
('DOGE/USD', 'Dogecoin', 'crypto', 0.001, 50, 10, 0.001, 'active'),
('AVAX/USD', 'Avalanche', 'crypto', 0.001, 50, 0.1, 0.001, 'active')
ON CONFLICT (symbol) DO NOTHING;

-- Forex markets
INSERT INTO markets (symbol, name, type, spread, max_leverage, min_trade_size, fee, status) VALUES 
('EUR/USD', 'Euro/US Dollar', 'forex', 0.0001, 500, 0.01, 0.0001, 'active'),
('GBP/USD', 'British Pound/US Dollar', 'forex', 0.0001, 500, 0.01, 0.0001, 'active'),
('USD/JPY', 'US Dollar/Japanese Yen', 'forex', 0.0001, 500, 0.01, 0.0001, 'active'),
('AUD/USD', 'Australian Dollar/US Dollar', 'forex', 0.0001, 500, 0.01, 0.0001, 'active'),
('USD/CAD', 'US Dollar/Canadian Dollar', 'forex', 0.0001, 500, 0.01, 0.0001, 'active'),
('USD/CHF', 'US Dollar/Swiss Franc', 'forex', 0.0001, 500, 0.01, 0.0001, 'active'),
('NZD/USD', 'New Zealand Dollar/US Dollar', 'forex', 0.0001, 500, 0.01, 0.0001, 'active'),
('EUR/GBP', 'Euro/British Pound', 'forex', 0.0001, 500, 0.01, 0.0001, 'active')
ON CONFLICT (symbol) DO NOTHING;

-- Initial prices for crypto
INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 97500.00, 2.45, 98200.00, 95800.00, 28500000000 FROM markets WHERE symbol = 'BTC/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 3650.00, 1.82, 3720.00, 3580.00, 15200000000 FROM markets WHERE symbol = 'ETH/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 685.00, 0.95, 695.00, 678.00, 1850000000 FROM markets WHERE symbol = 'BNB/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 225.00, 3.25, 232.00, 218.00, 4200000000 FROM markets WHERE symbol = 'SOL/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 2.35, -0.85, 2.42, 2.28, 3800000000 FROM markets WHERE symbol = 'XRP/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.05, 1.25, 1.08, 1.02, 850000000 FROM markets WHERE symbol = 'ADA/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.42, 4.50, 0.44, 0.39, 2100000000 FROM markets WHERE symbol = 'DOGE/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 48.50, 2.10, 49.80, 47.20, 520000000 FROM markets WHERE symbol = 'AVAX/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

-- Initial prices for forex
INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.0485, 0.12, 1.0520, 1.0455, 850000000000 FROM markets WHERE symbol = 'EUR/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.2645, -0.08, 1.2680, 1.2610, 420000000000 FROM markets WHERE symbol = 'GBP/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 154.85, 0.25, 155.20, 154.50, 380000000000 FROM markets WHERE symbol = 'USD/JPY'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.6385, -0.15, 0.6420, 0.6360, 180000000000 FROM markets WHERE symbol = 'AUD/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 1.4285, 0.08, 1.4310, 1.4250, 150000000000 FROM markets WHERE symbol = 'USD/CAD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.8925, 0.05, 0.8950, 0.8900, 120000000000 FROM markets WHERE symbol = 'USD/CHF'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.5685, -0.22, 0.5720, 0.5660, 85000000000 FROM markets WHERE symbol = 'NZD/USD'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

INSERT INTO prices (market_id, price, change_24h, high_24h, low_24h, volume_24h)
SELECT id, 0.8295, 0.18, 0.8320, 0.8270, 95000000000 FROM markets WHERE symbol = 'EUR/GBP'
ON CONFLICT (market_id) DO UPDATE SET price = EXCLUDED.price, change_24h = EXCLUDED.change_24h;

-- Default settings
INSERT INTO settings (key, value) VALUES 
('deposit_wallet', 'TRC20: TYourDepositWalletAddressHere'),
('min_deposit', '10'),
('min_withdrawal', '20'),
('withdrawal_fee', '1'),
('referral_commission', '5'),
('referral_min_deposit', '50'),
('global_max_leverage', '100'),
('max_position_size', '100000')
ON CONFLICT (key) DO NOTHING;
