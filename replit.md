# Trade Flow Globalex - Professional Trading Platform

## Overview
A full-featured trading platform built with PHP 8.2 featuring Forex, Crypto, Stocks, and Indices trading, user management, wallet system with manual approvals, and comprehensive admin controls. The UI is styled with a dark blue theme (#0a1628) matching the TradeFlow Globalex design with teal (#00D4AA) accent colors.

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

## Recent Changes
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
