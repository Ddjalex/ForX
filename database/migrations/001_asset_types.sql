-- Asset Types table
CREATE TABLE IF NOT EXISTS asset_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    status VARCHAR(20) DEFAULT 'active',
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add new columns to markets table for TradingView integration
ALTER TABLE markets ADD COLUMN IF NOT EXISTS display_name VARCHAR(255);
ALTER TABLE markets ADD COLUMN IF NOT EXISTS symbol_api VARCHAR(100);
ALTER TABLE markets ADD COLUMN IF NOT EXISTS symbol_tradingview VARCHAR(100);
ALTER TABLE markets ADD COLUMN IF NOT EXISTS asset_type_id INTEGER REFERENCES asset_types(id);

-- Create index for faster lookups
CREATE INDEX IF NOT EXISTS idx_markets_asset_type ON markets(asset_type_id);
CREATE INDEX IF NOT EXISTS idx_markets_type ON markets(type);
