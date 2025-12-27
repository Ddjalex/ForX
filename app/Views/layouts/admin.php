<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?> - Alpha Core Markets</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            border-right: 1px solid rgba(255,255,255,0.1);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: transform 0.3s ease;
        }
        
        .admin-sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .admin-sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .admin-sidebar-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-primary), #059669);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .admin-sidebar-logo h1 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }
        
        .admin-sidebar-logo small {
            display: block;
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }
        
        .admin-nav-section {
            padding: 16px 0;
        }
        
        .admin-nav-title {
            padding: 8px 20px;
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .admin-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .admin-nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .admin-nav-item a:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }
        
        .admin-nav-item a.active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--accent-primary);
            border-left-color: var(--accent-primary);
        }
        
        .admin-nav-item a svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        .admin-nav-item.back-link a {
            color: #64748b;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 10px;
            padding-top: 20px;
        }
        
        .admin-nav-item.back-link a:hover {
            color: var(--accent-primary);
        }
        
        .admin-main {
            flex: 1;
            margin-left: 260px;
            min-height: 100vh;
            background: #0f172a;
        }
        
        .admin-header {
            background: #1e293b;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .admin-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .admin-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 8px;
        }
        
        .admin-header h1 {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
            margin: 0;
        }
        
        .admin-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .admin-content {
            padding: 24px;
        }
        
        .admin-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 99;
        }
        
        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.open {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .admin-menu-toggle {
                display: block;
            }
            
            .admin-overlay.open {
                display: block;
            }
        }
        
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        .modal-overlay.active {
            display: flex;
        }
        
        .modal {
            background: #1e293b;
            border-radius: 16px;
            padding: 24px;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin: 0;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: #64748b;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .modal-close:hover {
            color: #fff;
        }
        
        :root {
            --accent-primary: #10b981;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-overlay" id="adminOverlay" onclick="toggleSidebar()"></div>
        
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <div class="admin-sidebar-logo">
                    <div class="admin-sidebar-logo-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"><polyline points="22,7 13.5,15.5 8.5,10.5 2,17"></polyline><polyline points="16,7 22,7 22,13"></polyline></svg>
                    </div>
                    <div>
                        <h1>Alpha Core Markets</h1>
                        <small>Admin Panel</small>
                    </div>
                </div>
            </div>
            
            <nav class="admin-nav-section">
                <div class="admin-nav-title">Overview</div>
                <ul class="admin-nav-list">
                    <li class="admin-nav-item">
                        <a href="/admin" class="<?= ($_SERVER['REQUEST_URI'] === '/admin') ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            Dashboard
                        </a>
                    </li>
                </ul>
            </nav>
            
            <nav class="admin-nav-section">
                <div class="admin-nav-title">Management</div>
                <ul class="admin-nav-list">
                    <li class="admin-nav-item">
                        <a href="/admin/users" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/users') === 0) ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            Users
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="/admin/deposits" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/deposits') === 0) ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                            Deposits
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="/admin/withdrawals" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/withdrawals') === 0) ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 19 19 12"></polyline></svg>
                            Withdrawals
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="/admin/kyc-approvals" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/kyc-approvals') === 0) ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            KYC Approvals
                        </a>
                    </li>
                </ul>
            </nav>
            
            <nav class="admin-nav-section">
                <div class="admin-nav-title">Trading</div>
                <ul class="admin-nav-list">
                    <li class="admin-nav-item">
                        <a href="/admin/markets" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/markets') === 0) ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22,7 13.5,15.5 8.5,10.5 2,17"></polyline><polyline points="16,7 22,7 22,13"></polyline></svg>
                            Markets
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="/admin/positions" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/positions') === 0) ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                            Positions
                        </a>
                    </li>
                </ul>
            </nav>
            
            <nav class="admin-nav-section">
                <div class="admin-nav-title">System</div>
                <ul class="admin-nav-list">
                    <li class="admin-nav-item">
                        <a href="/admin/settings" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/settings') === 0) ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            Settings
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="/admin/audit-logs" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/audit-logs') === 0) ? 'active' : '' ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                            Audit Logs
                        </a>
                    </li>
                </ul>
            </nav>
            
            <nav class="admin-nav-section" style="margin-top: auto;">
                <ul class="admin-nav-list">
                    <li class="admin-nav-item back-link">
                        <a href="/dashboard">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                            Back to User Panel
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="admin-menu-toggle" onclick="toggleSidebar()">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </button>
                    <h1><?= $pageTitle ?? 'Admin Dashboard' ?></h1>
                </div>
                <div class="admin-header-actions">
                    <a href="/admin/settings" class="btn btn-primary btn-sm" style="background: var(--accent-primary); border-color: var(--accent-primary); color: #000;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        Settings
                    </a>
                    <a href="/logout" class="btn btn-secondary btn-sm">Logout</a>
                </div>
            </header>

            <div class="admin-content">
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>

    <script src="/assets/js/app.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('adminOverlay').classList.toggle('open');
        }
    </script>
</body>
</html>
