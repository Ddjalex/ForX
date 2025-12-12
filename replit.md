# TradePro - Professional Trading Platform

## Overview
A full-featured trading platform built with PHP 8.2 featuring Forex and Crypto trading, user management, wallet system with manual approvals, and comprehensive admin controls. The UI is styled similar to Exness with a dark theme and yellow accents.

## Tech Stack
- **Backend**: PHP 8.2 (vanilla MVC architecture)
- **Database**: PostgreSQL
- **Frontend**: HTML, CSS (custom Exness-style), vanilla JavaScript
- **Charting**: Chart.js

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
/storage          - Uploads and logs
/cron             - Scheduled tasks
```

## Features
- **Authentication**: Register, login, logout, password reset
- **User Dashboard**: Balance overview, positions, transactions
- **Trading**: Market/Limit orders, leverage up to 500x, stop-loss, take-profit
- **Markets**: 8 Crypto pairs + 8 Forex pairs
- **Wallet**: Deposits (with TXID verification), withdrawals (manual approval)
- **Admin Panel**: User management, deposit/withdrawal approvals, market settings, audit logs

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
- Exness-style dark UI with yellow (#FFCF00) accent color
- Full MVC architecture with proper separation of concerns
- December 2024: Security enhancements:
  - Login rate limiting (5 attempts per 15 minutes)
  - Enhanced file upload validation (MIME type + extension checks, 5MB limit)
  - Admin middleware with database role verification
  - Withdrawal balance verification before approval
