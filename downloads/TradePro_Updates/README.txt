=== TradePro Updates - Installation Guide ===

This folder contains the updated files for your TradePro trading platform.

FILES INCLUDED:
1. AdminController.php → Replace: app/Controllers/AdminController.php
2. web.php → Replace: routes/web.php
3. settings.php → Replace: app/Views/admin/settings.php
4. trade.php → Replace: app/Views/trading/trade.php

INSTALLATION STEPS:
1. Download all files from this folder
2. Connect to your cPanel using File Manager or FTP
3. Upload/Replace each file in its corresponding location
4. Clear any cache if necessary
5. Test the new features:
   - Admin Password Change: Go to Admin Settings page
   - Trade Warnings: Try entering invalid trade amounts

FEATURES ADDED:
✓ Admin password change functionality
✓ Enhanced trade validation with warning messages
✓ Real-time balance checking
✓ Insufficient balance alerts
✓ Button disable/enable based on validation

For detailed changes, see CHANGES_SUMMARY.md
