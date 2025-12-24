# Alpha Core Markets - Professional Trading Platform

## Overview
A full-featured trading platform built with PHP 8.2 featuring Forex, Crypto, Stocks, and Indices trading, copy trading, market news, NFT marketplace, trading signals, crypto loans, referral program, and live market analysis. The UI is styled with a dark blue theme (#0a1628) with cyan/turquoise (#00D4AA) gradient accents using the Inter font family.

## Tech Stack
- **Backend**: PHP 8.2 (vanilla MVC architecture)
- **Database**: PostgreSQL
- **Frontend**: HTML, CSS (custom dark theme), vanilla JavaScript
- **Charting**: TradingView Widget

## Project Structure
```
/public           - Web root (index.php entry point)
  /assets         - CSS, JS, images
/app
  /Controllers    - Request handlers
  /Models         - Database models
  /Services       - Core services (Auth, Database, Router, Session)
  /Middleware     - Auth, Admin, CSRF middleware
  /Views          - PHP templates
/routes           - Route definitions
/config           - Database and app configuration
/database         - SQL schema and seed files
/storage          - Uploads, avatars, and logs
/cron             - Scheduled tasks
```

## Features
- **Authentication**: Register, login, logout, password reset, email verification
- **Email Verification**: 6-digit code sent via Brevo API during registration
- **User Dashboard**: 
  - Market ticker (S&P 500, Nasdaq, EUR/USD, BTC/USD)
  - Stats cards (Balance, Profit, Bonus, Account Status, Trades, Win/Loss Ratio)
  - Open positions and recent transactions
  - Quick action buttons
- **Trading**: 
  - TradingView live charts
  - Leverage selection grid (1x to 100x)
  - Market/Limit orders with stop-loss and take-profit
  - Trade duration options
  - Open/Closed trades tabs
- **Wallet**: 
  - Multiple deposit methods (Bitcoin, Ethereum, USDT, Solana, Litecoin)
  - Withdrawals with method selection
  - Manual approval system
- **Account Settings**:
  - Personal profile management
  - Account records (investments, earnings, referrals)
  - Password change functionality
  - Avatar upload
- **Admin Panel**: User management, deposit/withdrawal approvals, market settings, audit logs
- **News**: Real-time market news from Finnhub API with category filtering
- **NFT Marketplace**: Top NFT collections from CoinGecko API with floor prices and volume
- **Trading Signals**: Technical analysis signals from TAAPI.IO (RSI, MACD, EMA, Bollinger Bands)
- **Crypto Loans**: Collateralized loan system with multiple plans and interest rates
- **Referral Program**: Multi-tier referral system with earnings tracking and bonuses
- **Live Analysis**: Combined view of trading signals and market news
- **UI Enhancements**:
  - Investment notification popups
  - WhatsApp chat button
  - Language selector
  - Responsive sidebar navigation

## Default Credentials
- **Admin**: admin@tradepro.com / password
- **Demo User**: demo@tradepro.com / password

## Running the Application
The PHP built-in server runs on port 5000:
```bash
php -S 0.0.0.0:5000 -t public
```

## Database
PostgreSQL with the following main tables:
- users, wallets, deposits, withdrawals
- markets, prices, prices_history
- orders, positions, trades
- audit_logs, login_logs, settings
- loan_plans, loans
- referral_links, referral_earnings
- kyc_verifications, kyc_documents

## KYC Deployment Instructions

### Local Testing (Replit)
Routes are working correctly:
- `GET /kyc/verify` → User KYC verification form
- `POST /kyc/submit` → Submit verification documents
- `GET /admin/kyc` → Admin approval panel
- `POST /admin/kyc/approve/{id}` → Approve KYC
- `POST /admin/kyc/reject/{id}` → Reject KYC

Test: Login to dashboard → Click "Verify Account" in sidebar

### cPanel Production (alphacoremarkets.com)

**Step 1: Upload Files**
- Copy `public/.htaccess` to `public_html/.htaccess`
- Copy all app files to `public_html/` (maintaining directory structure)

**Step 2: Update index.php**
In `public_html/index.php`, change line 10:
```php
FROM: define('ROOT_PATH', dirname(__DIR__));
TO:   define('ROOT_PATH', __DIR__);
```

**Step 3: Create KYC Directories**
```bash
mkdir -p public_html/uploads/kyc
chmod 755 public_html/uploads/kyc
chmod 644 public_html/.htaccess
```

**Step 4: Database Migration (phpMyAdmin)**
```sql
CREATE TABLE IF NOT EXISTS kyc_verifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    full_name VARCHAR(255) NOT NULL,
    id_type VARCHAR(50) NOT NULL,
    id_number VARCHAR(100) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    rejection_reason TEXT,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS kyc_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kyc_id INT NOT NULL,
    document_type VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kyc_id) REFERENCES kyc_verifications(id) ON DELETE CASCADE,
    INDEX idx_kyc_id (kyc_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE users ADD COLUMN IF NOT EXISTS kyc_verified BOOLEAN DEFAULT FALSE;
ALTER TABLE users ADD COLUMN IF NOT EXISTS kyc_verified_at TIMESTAMP NULL;
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_kyc_verified (kyc_verified);
```

**Step 5: Test URLs**
- `alphacoremarkets.com/kyc/verify` → Should redirect to login
- `alphacoremarkets.com/admin/kyc` → Should redirect to login
- After login: user sees "Verify Account" in sidebar, admin sees "KYC Approvals"

**Troubleshooting 404 errors:**
1. Verify `.htaccess` is in `public_html/` (not nested)
2. Ask hosting provider to enable Apache `mod_rewrite`
3. Check `.htaccess` permissions: `chmod 644 public_html/.htaccess`
4. Verify `index.php` ROOT_PATH points to correct directory

## Recent Changes (Completed)
- **December 24, 2024: KYC Verification System - FULLY OPERATIONAL** ✅
  - Complete Know-Your-Customer identity verification with document upload
  - User workflow: `/kyc/verify` page for uploading ID (front/back), passport, and face photo
  - Admin workflow: `/admin/kyc` page for reviewing and approving/rejecting submissions
  - Verification modal auto-shows on dashboard for unverified users
  - Dashboard status card displays actual KYC status (Verified/Not Verified)
  - Access control: Unverified users cannot access trading, wallet, or loans - must complete KYC first
  - Real-time notifications when KYC is approved or rejected
  - Database tables: `kyc_verifications`, `kyc_documents`
  - Sidebar links added: "Verify Account" (user), "KYC Approvals" (admin)
  - `.htaccess` configured for cPanel Apache routing
  - Routes: GET `/kyc/verify`, POST `/kyc/submit`, GET `/admin/kyc`, POST `/admin/kyc/approve/{id}`, POST `/admin/kyc/reject/{id}`
  - **Deployment**: Copy `.htaccess` to cPanel `public_html/`, update `index.php` ROOT_PATH for cPanel
- **December 23, 2024: Referral System - FULLY OPERATIONAL** ✅
  - Auto-generates referral codes for every user on registration
  - Tracks referral clicks when users visit referral links
  - Automatically creates "pending" referral earnings when referred user registers
  - Auto-approves referral bonuses when referred user's deposit is approved
  - Bonus automatically added to referrer's wallet balance
  - Commission rate: 5% with $50 minimum deposit requirement
  - Features: Total Referrals count, Link Clicks tracking, Total Earnings & Pending display
  - **Production-Ready**: All files uploaded to cPanel at alphacoremarkets.com
- December 2024: Email Auto-Fill After Verification
  - Login form auto-fills email after email verification
  - Improved user experience for returning users
- December 2024: New Features Implementation:
  - Added NewsService with Finnhub API integration for market news
  - Added NFTService with CoinGecko API for NFT marketplace data
  - Added SignalService with TAAPI.IO for trading signals
  - Added LoanService for crypto collateralized loans
  - Added ReferralService with multi-tier referral system
  - Created FeatureController with routes: /news, /nfts, /signals, /loans, /referrals, /live-analysis
  - Added loan_plans and loans database tables
  - Security: CSRF validation on loan applications, sanitized referral link hosts
- December 2024: Real-Time Market Data Integration:
  - Replaced Binance API (blocked from server location) with CoinGecko API for crypto prices
  - Added exchangerate-api.com integration for real forex rates
  - Added coingecko_id column to markets table for crypto asset mapping
  - Implemented price caching (10s for crypto, 60s for forex) to respect API rate limits
  - New API endpoints: GET /api/prices/refresh, GET /api/prices/live/{symbol}
  - Prices now match real market data from major trading platforms
  - Price history is properly recorded for all asset types
- December 2024: Automated Asset Sync System:
  - AssetSyncService now auto-syncs 30 crypto assets from Binance API with TLS verification
  - Includes 20 major forex pairs and 30 US stocks with proper TradingView symbols
  - Crypto symbols stored as API format (BTCUSDT) with display_name (USDT/BTC) for UI
  - syncAllAssets() automatically cleans legacy slash-formatted records and deduplicates
  - Fallback list ensures sync works even when Binance API is unreachable
  - Run `php public/sync-assets.php` to refresh assets from live market sources
- December 2024: Assets Module Implementation:
  - Added asset_types table with 3 categories: Crypto, Forex, Stock
  - Enhanced markets table with display_name, symbol_api, symbol_tradingview columns
  - Seeded 45 assets: 15 crypto (Binance), 16 forex, 14 stocks with proper TradingView symbols
  - Created AssetService for asset management and real-time price fetching
  - New API endpoints: GET /api/asset-types, GET /api/assets?type={type}
  - Trading form now has dynamic Asset Type/Name dropdowns that filter assets
  - TradingView charts auto-update based on selected asset (BINANCE:BTCUSDT, FX:EURUSD, NASDAQ:AAPL)
  - Server-side entry price fetching from database for BUY/SELL trades
  - Real-time price fetch from Binance API for crypto assets
- December 2024: Customer Support Chat & Profit Control Enhancements:
  - Added Tawk.to customer support chat widget integration (configurable in Admin Settings)
  - Implemented asymmetric profit control formula for fair win/loss adjustments
  - Admin can set profit control from -10% to +100%
  - Positive % = users profit MORE on wins, lose LESS on losses
  - Negative % = users profit LESS on wins, lose MORE on losses
  - Enhanced admin UI with live example calculations and quick guide
  - Auto-redirect to transactions panel when trades expire
  - Smooth scroll and auto-switch to "Closed" tab after trade closes
- December 2024: Trade Auto-Close and Security Improvements:
  - Added secure `/api/positions/close-expired` endpoint with AuthMiddleware protection
  - Implemented automatic position closing when trade duration countdown expires
  - Positions are scoped per authenticated user to prevent cross-account manipulation
  - Added `checkExpiredPositions()` function in app.js that polls every 5 seconds
  - Fixed trade confirmation modal - proper button ordering (Cancel left, Yes right)
  - Enhanced button styling with improved colors and hover states
- December 2024: Fixed trading and security issues:
  - Fixed buy/sell error caused by database columns mismatch in ApiController
  - Added duration and expires_at columns to positions table for trade timing
  - Fixed database schema to match all controller operations
  - Removed admin panel link from user sidebar for improved security (admins can access via /admin directly)
- December 2024: Initial build with complete trading platform
- December 2024: Security enhancements (rate limiting, file validation, admin middleware)
- December 2024: Email verification for registration
- December 2024: UI/UX Redesign:
  - New dark blue theme matching TradeFlow Globalex design
  - Market ticker bar with live price updates
  - Enhanced dashboard with comprehensive stats cards
  - TradingView chart integration
  - Leverage selection grid (1x-100x)
  - Multiple crypto deposit methods
  - Account settings with profile/records/settings tabs
  - Investment notification popups
  - WhatsApp chat integration
  - Rebranded to "TradeFlow Globalex"
- December 2024: Enhanced Real-Time Market Data & Admin Controls:
  - TradingView Ticker Tape widget with real-time scrolling market data (S&P 500, Nasdaq, EUR/USD, BTC/USD, ETH/USD, Gold, GBP/USD, USD/JPY)
  - Admin dashboard quick actions for deposit/withdrawal approvals with reject modal
  - Admin navigation bar for easy access to all management sections
  - Recent users display on admin dashboard
  - Clickable stat cards for quick navigation
- December 2024: Dashboard Market Data Panels:
  - Added Cryptocurrency Market panel with TradingView crypto screener widget
  - Added Stock Market Data panel with TradingView hotlists widget
  - Live real-time market data for cryptocurrencies and US stocks
  - Brevo API integration for email verification (BREVO_API_KEY, BREVO_SENDER_EMAIL secrets configured)
