<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="users-stats">
    <div class="user-stat-card">
        <div class="stat-icon users-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
        </div>
        <div class="stat-info">
            <span class="stat-number"><?= count($users) ?></span>
            <span class="stat-label">Total Users</span>
        </div>
    </div>
    <div class="user-stat-card">
        <div class="stat-icon verified-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="stat-info">
            <span class="stat-number"><?= count(array_filter($users, fn($u) => $u['email_verified'])) ?></span>
            <span class="stat-label">Verified Users</span>
        </div>
    </div>
    <div class="user-stat-card">
        <div class="stat-icon pending-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
        </div>
        <div class="stat-info">
            <span class="stat-number"><?= count(array_filter($users, fn($u) => !$u['email_verified'])) ?></span>
            <span class="stat-label">Not Verified</span>
        </div>
    </div>
    <div class="user-stat-card">
        <div class="stat-icon active-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
        </div>
        <div class="stat-info">
            <span class="stat-number"><?= count(array_filter($users, fn($u) => $u['status'] === 'active')) ?></span>
            <span class="stat-label">Active Users</span>
        </div>
    </div>
</div>

<div class="users-filter-bar">
    <div class="search-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <input type="text" id="userSearch" placeholder="Search users by name or email..." onkeyup="filterUsers()">
    </div>
    <div class="filter-buttons">
        <button class="filter-btn active" onclick="filterByStatus('all', this)">All</button>
        <button class="filter-btn" onclick="filterByStatus('verified', this)">Verified</button>
        <button class="filter-btn" onclick="filterByStatus('not-verified', this)">Not Verified</button>
        <button class="filter-btn" onclick="filterByStatus('suspended', this)">Suspended</button>
    </div>
</div>

<div class="users-grid" id="usersGrid">
    <?php foreach ($users as $u): 
        $isVerified = $u['email_verified'];
        $isActive = $u['status'] === 'active';
        $kycStatus = $u['kyc_status'] ?? 'pending';
    ?>
    <div class="user-card" 
         data-name="<?= strtolower(htmlspecialchars($u['name'])) ?>" 
         data-email="<?= strtolower(htmlspecialchars($u['email'])) ?>"
         data-verified="<?= $isVerified ? 'verified' : 'not-verified' ?>"
         data-status="<?= $u['status'] ?>">
        <div class="user-card-header">
            <div class="user-avatar">
                <?= strtoupper(substr($u['name'], 0, 2)) ?>
                <span class="status-dot <?= $isActive ? 'online' : 'offline' ?>"></span>
            </div>
            <div class="user-info">
                <h4 class="user-name"><?= htmlspecialchars($u['name']) ?></h4>
                <p class="user-email"><?= htmlspecialchars($u['email']) ?></p>
            </div>
            <div class="user-badges">
                <?php if ($isVerified): ?>
                    <span class="badge-verified">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01" fill="none" stroke="currentColor" stroke-width="3"></polyline></svg>
                        Verified
                    </span>
                <?php else: ?>
                    <span class="badge-not-verified">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        Not Verified
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="user-details-grid">
            <div class="detail-item">
                <span class="detail-label">Balance</span>
                <span class="detail-value balance">$<?= number_format($u['balance'] ?? 0, 2) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Margin Used</span>
                <span class="detail-value">$<?= number_format($u['margin_used'] ?? 0, 2) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Role</span>
                <span class="detail-value">
                    <span class="role-badge role-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Account Status</span>
                <span class="detail-value">
                    <span class="status-badge status-<?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">KYC Status</span>
                <span class="detail-value">
                    <span class="kyc-badge kyc-<?= $kycStatus ?>"><?= ucfirst($kycStatus) ?></span>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Phone</span>
                <span class="detail-value"><?= htmlspecialchars($u['phone'] ?? 'Not provided') ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Joined</span>
                <span class="detail-value"><?= date('M d, Y', strtotime($u['created_at'])) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Last Login</span>
                <span class="detail-value"><?= $u['last_login'] ? date('M d, Y H:i', strtotime($u['last_login'])) : 'Never' ?></span>
            </div>
        </div>

        <div class="user-card-actions">
            <button class="action-btn view-btn" onclick="showUserDetails(<?= htmlspecialchars(json_encode($u)) ?>)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                View Details
            </button>
            <button class="action-btn balance-btn" onclick="showBalanceModal(<?= $u['id'] ?>, <?= $u['balance'] ?? 0 ?>)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                Edit Balance
            </button>
            <?php if (!$isVerified): ?>
            <form method="POST" action="/admin/users/verify" style="display: inline;">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                <button type="submit" class="action-btn verify-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    Verify User
                </button>
            </form>
            <?php endif; ?>
            <form method="POST" action="/admin/users/update" style="display: inline;">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                <?php if ($isActive): ?>
                    <button type="submit" name="action" value="suspend" class="action-btn suspend-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                        Suspend
                    </button>
                <?php else: ?>
                    <button type="submit" name="action" value="activate" class="action-btn activate-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        Activate
                    </button>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal-overlay" id="balanceModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit User Balance</h3>
            <button class="modal-close" onclick="hideBalanceModal()">&times;</button>
        </div>
        <form method="POST" action="/admin/users/balance">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="user_id" id="balanceUserId">
            
            <div class="form-group">
                <label class="form-label">New Balance ($)</label>
                <input type="number" name="balance" id="balanceInput" class="form-control" step="0.01" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Reason (for audit)</label>
                <input type="text" name="reason" class="form-control" placeholder="Reason for balance change" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Update Balance</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="userDetailsModal">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title">User Details</h3>
            <button class="modal-close" onclick="hideUserDetails()">&times;</button>
        </div>
        <div class="user-details-content" id="userDetailsContent">
        </div>
    </div>
</div>

<style>
.users-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.user-stat-card {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid rgba(255,255,255,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.user-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.users-icon { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.verified-icon { background: linear-gradient(135deg, #10b981, #059669); }
.pending-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
.active-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #fff;
}

.stat-label {
    font-size: 13px;
    color: #94a3b8;
}

.users-filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap;
}

.search-box {
    display: flex;
    align-items: center;
    background: #1e293b;
    border-radius: 12px;
    padding: 12px 16px;
    gap: 10px;
    flex: 1;
    max-width: 400px;
    border: 1px solid rgba(255,255,255,0.1);
}

.search-box svg {
    color: #64748b;
}

.search-box input {
    background: transparent;
    border: none;
    color: #fff;
    font-size: 14px;
    width: 100%;
    outline: none;
}

.search-box input::placeholder {
    color: #64748b;
}

.filter-buttons {
    display: flex;
    gap: 8px;
}

.filter-btn {
    background: #1e293b;
    border: 1px solid rgba(255,255,255,0.1);
    color: #94a3b8;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-btn:hover {
    background: #334155;
    color: #fff;
}

.filter-btn.active {
    background: var(--accent-primary);
    color: #000;
    border-color: var(--accent-primary);
}

.users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 20px;
}

.user-card {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgba(255,255,255,0.1);
    transition: all 0.3s;
}

.user-card:hover {
    border-color: var(--accent-primary);
    box-shadow: 0 8px 30px rgba(16, 185, 129, 0.15);
}

.user-card.hidden {
    display: none;
}

.user-card-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.user-avatar {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--accent-primary), #059669);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 700;
    color: #000;
    position: relative;
}

.status-dot {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid #0f172a;
}

.status-dot.online { background: #10b981; }
.status-dot.offline { background: #ef4444; }

.user-info {
    flex: 1;
}

.user-name {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    margin: 0 0 4px 0;
}

.user-email {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

.user-badges {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.badge-verified {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}

.badge-not-verified {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}

.user-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}

.detail-item {
    background: rgba(0,0,0,0.2);
    padding: 12px;
    border-radius: 10px;
}

.detail-label {
    display: block;
    font-size: 11px;
    color: #64748b;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-size: 14px;
    font-weight: 500;
    color: #e2e8f0;
}

.detail-value.balance {
    color: #10b981;
    font-weight: 700;
}

.role-badge {
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.role-user { background: #334155; color: #94a3b8; }
.role-admin { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
.role-superadmin { background: rgba(139, 92, 246, 0.2); color: #8b5cf6; }

.status-badge {
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.status-active { background: rgba(16, 185, 129, 0.2); color: #10b981; }
.status-suspended { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

.kyc-badge {
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.kyc-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
.kyc-verified { background: rgba(16, 185, 129, 0.2); color: #10b981; }
.kyc-rejected { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

.user-card-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.view-btn {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.view-btn:hover { background: rgba(59, 130, 246, 0.3); }

.balance-btn {
    background: rgba(139, 92, 246, 0.2);
    color: #8b5cf6;
}

.balance-btn:hover { background: rgba(139, 92, 246, 0.3); }

.verify-btn {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.verify-btn:hover { background: rgba(16, 185, 129, 0.3); }

.suspend-btn {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.suspend-btn:hover { background: rgba(239, 68, 68, 0.3); }

.activate-btn {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.activate-btn:hover { background: rgba(16, 185, 129, 0.3); }

.modal-lg {
    max-width: 700px !important;
}

.user-details-content {
    padding: 20px 0;
}

.user-full-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.detail-section {
    background: rgba(0,0,0,0.2);
    padding: 16px;
    border-radius: 12px;
}

.detail-section-title {
    font-size: 12px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-section-value {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
}

@media (max-width: 768px) {
    .users-grid {
        grid-template-columns: 1fr;
    }
    
    .users-filter-bar {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box {
        max-width: 100%;
    }
    
    .filter-buttons {
        flex-wrap: wrap;
    }
    
    .user-details-grid {
        grid-template-columns: 1fr;
    }
    
    .user-full-details {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function showBalanceModal(userId, currentBalance) {
    document.getElementById('balanceUserId').value = userId;
    document.getElementById('balanceInput').value = currentBalance;
    document.getElementById('balanceModal').classList.add('active');
}

function hideBalanceModal() {
    document.getElementById('balanceModal').classList.remove('active');
}

function showUserDetails(user) {
    const content = document.getElementById('userDetailsContent');
    const isVerified = user.email_verified;
    const lastLogin = user.last_login ? new Date(user.last_login).toLocaleString() : 'Never';
    const createdAt = new Date(user.created_at).toLocaleString();
    
    content.innerHTML = `
        <div class="user-full-details">
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Full Name
                </div>
                <div class="detail-section-value">${user.name}</div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Email Address
                </div>
                <div class="detail-section-value">${user.email}</div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    Phone Number
                </div>
                <div class="detail-section-value">${user.phone || 'Not provided'}</div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    Account Balance
                </div>
                <div class="detail-section-value" style="color: #10b981;">$${parseFloat(user.balance || 0).toFixed(2)}</div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Margin Used
                </div>
                <div class="detail-section-value">$${parseFloat(user.margin_used || 0).toFixed(2)}</div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    User Role
                </div>
                <div class="detail-section-value">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    Email Verification
                </div>
                <div class="detail-section-value">
                    <span style="color: ${isVerified ? '#10b981' : '#ef4444'};">${isVerified ? 'Verified' : 'Not Verified'}</span>
                </div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                    Account Status
                </div>
                <div class="detail-section-value">
                    <span style="color: ${user.status === 'active' ? '#10b981' : '#ef4444'};">${user.status.charAt(0).toUpperCase() + user.status.slice(1)}</span>
                </div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Registration Date
                </div>
                <div class="detail-section-value">${createdAt}</div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    Last Login
                </div>
                <div class="detail-section-value">${lastLogin}</div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    KYC Status
                </div>
                <div class="detail-section-value">${(user.kyc_status || 'pending').charAt(0).toUpperCase() + (user.kyc_status || 'pending').slice(1)}</div>
            </div>
            <div class="detail-section">
                <div class="detail-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    2FA Enabled
                </div>
                <div class="detail-section-value">${user.two_factor_enabled ? 'Yes' : 'No'}</div>
            </div>
        </div>
    `;
    
    document.getElementById('userDetailsModal').classList.add('active');
}

function hideUserDetails() {
    document.getElementById('userDetailsModal').classList.remove('active');
}

function filterUsers() {
    const searchTerm = document.getElementById('userSearch').value.toLowerCase();
    const cards = document.querySelectorAll('.user-card');
    
    cards.forEach(card => {
        const name = card.dataset.name;
        const email = card.dataset.email;
        
        if (name.includes(searchTerm) || email.includes(searchTerm)) {
            card.classList.remove('search-hidden');
        } else {
            card.classList.add('search-hidden');
        }
        
        updateCardVisibility(card);
    });
}

let currentFilter = 'all';

function filterByStatus(status, btn) {
    currentFilter = status;
    
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    const cards = document.querySelectorAll('.user-card');
    
    cards.forEach(card => {
        const verified = card.dataset.verified;
        const accountStatus = card.dataset.status;
        
        let matches = true;
        
        if (status === 'verified') {
            matches = verified === 'verified';
        } else if (status === 'not-verified') {
            matches = verified === 'not-verified';
        } else if (status === 'suspended') {
            matches = accountStatus === 'suspended';
        }
        
        if (matches) {
            card.classList.remove('filter-hidden');
        } else {
            card.classList.add('filter-hidden');
        }
        
        updateCardVisibility(card);
    });
}

function updateCardVisibility(card) {
    const searchHidden = card.classList.contains('search-hidden');
    const filterHidden = card.classList.contains('filter-hidden');
    
    if (searchHidden || filterHidden) {
        card.classList.add('hidden');
    } else {
        card.classList.remove('hidden');
    }
}

document.getElementById('balanceModal').addEventListener('click', function(e) {
    if (e.target === this) hideBalanceModal();
});

document.getElementById('userDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) hideUserDetails();
});
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'User Management';
include __DIR__ . '/../layouts/admin.php';
?>
