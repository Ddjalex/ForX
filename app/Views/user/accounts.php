<?php
$title = 'Account Settings - Trade Flow Globalex';
ob_start();

$tab = $_GET['tab'] ?? 'profile';

$totalDeposits = 0;
$totalWithdrawals = 0;
$totalEarnings = 0;
$totalBalance = $wallet['balance'] ?? 0;
$totalReferral = 0;
$totalBonus = $wallet['bonus'] ?? 0;

foreach ($deposits ?? [] as $d) {
    if ($d['status'] === 'approved') {
        $totalDeposits += $d['amount'];
    }
}
foreach ($withdrawals ?? [] as $w) {
    if ($w['status'] === 'approved' || $w['status'] === 'paid') {
        $totalWithdrawals += $w['amount'];
    }
}
$totalEarnings = $wallet['profit'] ?? 0;
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="profile-header">
    <div class="profile-avatar">
        <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
    </div>
    <div class="profile-info">
        <div class="verification-status <?= ($user['kyc_status'] ?? 'pending') === 'approved' ? 'verified' : 'unverified' ?>">
            <?= ($user['kyc_status'] ?? 'pending') === 'approved' ? 'VERIFIED' : 'NOT VERIFIED' ?>
        </div>
        <h2><?= htmlspecialchars($user['name'] ?? 'User') ?></h2>
        <p><?= htmlspecialchars($user['email'] ?? '') ?></p>
    </div>
    <div class="profile-stats">
        <div class="profile-stat">
            <div class="value">$<?= number_format($totalEarnings, 2) ?></div>
            <div class="label">Profit</div>
        </div>
        <div class="profile-stat">
            <div class="value">$<?= number_format($totalBalance, 2) ?></div>
            <div class="label">Total Balance</div>
        </div>
        <div class="profile-stat">
            <div class="value">$<?= number_format($totalBonus, 2) ?></div>
            <div class="label">Total Bonus</div>
        </div>
    </div>
</div>

<div class="action-buttons" style="justify-content: flex-start; margin-bottom: 20px;">
    <a href="/accounts" class="action-btn account">Account</a>
    <a href="/wallet/deposit" class="action-btn deposit">Make Deposit $</a>
    <a href="/wallet/withdraw" class="action-btn withdraw">Withdraw Funds</a>
    <a href="mailto:support@tradeflowglobalex.com" class="action-btn mail">Mail Us</a>
    <a href="/accounts?tab=settings" class="action-btn settings">Settings</a>
</div>

<div class="card">
    <div class="profile-tabs">
        <a href="/accounts?tab=profile" class="profile-tab <?= $tab === 'profile' ? 'active' : '' ?>">Personal Profile</a>
        <a href="/accounts?tab=records" class="profile-tab <?= $tab === 'records' ? 'active' : '' ?>">Account Records</a>
        <a href="/accounts?tab=settings" class="profile-tab <?= $tab === 'settings' ? 'active' : '' ?>">Account Settings</a>
    </div>

    <div class="card-body">
        <?php if ($tab === 'profile'): ?>
            <h3 style="margin-bottom: 20px;">Personal Profile Info</h3>
            
            <form method="POST" action="/accounts/update-profile" class="profile-form">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['country'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">State/Province</label>
                    <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($user['state'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Postal/Zip Code</label>
                    <input type="text" name="zip" class="form-control" value="<?= htmlspecialchars($user['zip'] ?? '') ?>">
                </div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3" placeholder="Type here"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>
                
                <div style="grid-column: span 2;">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>

            <div style="margin-top: 30px;">
                <p style="color: var(--text-secondary); margin-bottom: 10px;">Account Status</p>
                <div class="verification-status <?= ($user['email_verified'] ?? false) ? 'verified' : 'unverified' ?>" style="display: inline-block;">
                    <?= ($user['email_verified'] ?? false) ? 'VERIFIED' : 'NOT VERIFIED' ?>
                </div>
                <p style="color: var(--text-secondary); margin-top: 15px;">Account Type</p>
                <p style="color: var(--primary);"><?= htmlspecialchars($user['account_type'] ?? 'CryptoCurrency Investment') ?></p>
            </div>

        <?php elseif ($tab === 'records'): ?>
            <h3 style="margin-bottom: 20px;">Account Records</h3>
            
            <table class="table account-records">
                <tbody>
                    <tr>
                        <td>Total Investment</td>
                        <td>$<?= number_format($totalDeposits, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Total Earnings</td>
                        <td>$<?= number_format($totalEarnings, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Total Balance</td>
                        <td>$<?= number_format($totalBalance, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Total Referral</td>
                        <td>$<?= number_format($totalReferral, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Total Bonus</td>
                        <td>$<?= number_format($totalBonus, 2) ?></td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 30px;">
                <a href="/positions" class="btn btn-primary">View Transactions</a>
                <a href="/positions?tab=history" class="btn btn-secondary" style="margin-left: 10px;">View Trade History</a>
            </div>

            <div style="margin-top: 30px;">
                <label class="form-label">Your Referral Link</label>
                <div class="referral-box">
                    <input type="text" value="<?= htmlspecialchars('https://tradeflowglobalex.com/ref/' . ($user['username'] ?? $user['id'])) ?>" readonly id="referralLink">
                    <button type="button" class="btn btn-primary btn-sm" onclick="copyReferral()">Copy Referral Link</button>
                </div>
            </div>

        <?php elseif ($tab === 'settings'): ?>
            <div class="grid-2">
                <div>
                    <h3 style="margin-bottom: 20px;">CHANGE PASSWORD</h3>
                    <form method="POST" action="/accounts/change-password">
                        <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Old Password</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Rewrite New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        
                        <button type="button" class="btn btn-secondary" onclick="this.form.reset()">clear</button>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>

                <div>
                    <h3 style="margin-bottom: 20px;">CHANGE PROFILE IMAGE</h3>
                    <form method="POST" action="/accounts/update-avatar" enctype="multipart/form-data">
                        <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                        
                        <div style="width: 150px; height: 150px; background: var(--bg-input); border: 2px dashed var(--border-color); border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; justify-content: center;">
                            <?php if (!empty($user['avatar'])): ?>
                                <img src="<?= htmlspecialchars($user['avatar']) ?>" style="max-width: 100%; max-height: 100%; border-radius: 6px;">
                            <?php else: ?>
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="opacity: 0.3;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Change Profile Image</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function copyReferral() {
    const input = document.getElementById('referralLink');
    input.select();
    document.execCommand('copy');
    alert('Referral link copied to clipboard!');
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Account Settings | Profile';
include ROOT_PATH . '/app/Views/layouts/main.php';
?>
