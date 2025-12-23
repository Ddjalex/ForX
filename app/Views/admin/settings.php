<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Platform Settings</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="/admin/settings">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            
            <h4 style="margin-bottom: 16px; color: var(--text-secondary);">Deposit Network Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Bitcoin (BTC) Wallet Address</label>
                <input type="text" name="deposit_btc_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_btc_address'] ?? '') ?>" placeholder="Enter Bitcoin wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Bitcoin Network Type</label>
                <select name="deposit_btc_network" class="form-control">
                    <option value="BTC" <?= ($settings['deposit_btc_network'] ?? '') === 'BTC' ? 'selected' : '' ?>>BTC (Native)</option>
                    <option value="BEP20" <?= ($settings['deposit_btc_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Ethereum (ETH) Wallet Address</label>
                <input type="text" name="deposit_eth_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_eth_address'] ?? '') ?>" placeholder="Enter Ethereum wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Ethereum Network Type</label>
                <select name="deposit_eth_network" class="form-control">
                    <option value="ERC20" <?= ($settings['deposit_eth_network'] ?? '') === 'ERC20' ? 'selected' : '' ?>>ERC20 (Ethereum)</option>
                    <option value="BEP20" <?= ($settings['deposit_eth_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                    <option value="ARB" <?= ($settings['deposit_eth_network'] ?? '') === 'ARB' ? 'selected' : '' ?>>Arbitrum</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">USDT Wallet Address</label>
                <input type="text" name="deposit_usdt_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_usdt_address'] ?? '') ?>" placeholder="Enter USDT wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">USDT Network Type</label>
                <select name="deposit_usdt_network" class="form-control">
                    <option value="TRC20" <?= ($settings['deposit_usdt_network'] ?? '') === 'TRC20' ? 'selected' : '' ?>>TRC20 (Tron)</option>
                    <option value="ERC20" <?= ($settings['deposit_usdt_network'] ?? '') === 'ERC20' ? 'selected' : '' ?>>ERC20 (Ethereum)</option>
                    <option value="BEP20" <?= ($settings['deposit_usdt_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Litecoin (LTC) Wallet Address</label>
                <input type="text" name="deposit_ltc_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_ltc_address'] ?? '') ?>" placeholder="Enter Litecoin wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Litecoin Network Type</label>
                <select name="deposit_ltc_network" class="form-control">
                    <option value="LTC" <?= ($settings['deposit_ltc_network'] ?? '') === 'LTC' ? 'selected' : '' ?>>LTC (Native)</option>
                    <option value="BEP20" <?= ($settings['deposit_ltc_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Solana (SOL) Wallet Address</label>
                <input type="text" name="deposit_sol_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_sol_address'] ?? '') ?>" placeholder="Enter Solana wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Solana Network Type</label>
                <select name="deposit_sol_network" class="form-control">
                    <option value="SOL" <?= ($settings['deposit_sol_network'] ?? '') === 'SOL' ? 'selected' : '' ?>>SOL (Native)</option>
                </select>
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Deposit Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Legacy Deposit Wallet Address (TRC20)</label>
                <input type="text" name="deposit_wallet" class="form-control" value="<?= htmlspecialchars($settings['deposit_wallet'] ?? '') ?>" placeholder="TRC20 Wallet Address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Minimum Deposit ($)</label>
                <input type="number" name="min_deposit" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['min_deposit'] ?? '10') ?>">
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Withdrawal Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Minimum Withdrawal ($)</label>
                <input type="number" name="min_withdrawal" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['min_withdrawal'] ?? '20') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Withdrawal Fee (%)</label>
                <input type="number" name="withdrawal_fee" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['withdrawal_fee'] ?? '1') ?>">
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Referral Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Referral Commission (%)</label>
                <input type="number" name="referral_commission" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['referral_commission'] ?? '5') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Minimum Deposit for Referral Reward ($)</label>
                <input type="number" name="referral_min_deposit" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['referral_min_deposit'] ?? '50') ?>">
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Risk Controls</h4>
            
            <div class="form-group">
                <label class="form-label">Global Max Leverage</label>
                <input type="number" name="global_max_leverage" class="form-control" value="<?= htmlspecialchars($settings['global_max_leverage'] ?? '100') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Max Position Size ($)</label>
                <input type="number" name="max_position_size" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['max_position_size'] ?? '100000') ?>">
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Customer Support Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Tawk.to Property ID</label>
                <input type="text" name="tawkto_property_id" class="form-control" value="<?= htmlspecialchars($settings['tawkto_property_id'] ?? '') ?>" placeholder="e.g., 5f4bc7e47d0ed2170c4a68bc">
                <small class="form-text text-muted">Get this from Tawk.to Dashboard > Administration > Chat Widget</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tawk.to Widget ID</label>
                <input type="text" name="tawkto_widget_id" class="form-control" value="<?= htmlspecialchars($settings['tawkto_widget_id'] ?? '') ?>" placeholder="e.g., 1e2abc3d4">
                <small class="form-text text-muted">Found in the same widget code from Tawk.to Dashboard</small>
            </div>
            
            
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

<div class="card" style="max-width: 800px; margin-top: 24px;">
    <div class="card-header">
        <h3 class="card-title">Change Admin Password</h3>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/admin/change-password">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="old_password" class="form-control" required placeholder="Enter your current password">
            </div>
            
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required placeholder="Enter new password" minlength="8">
                <small class="form-text text-muted">Password must be at least 8 characters</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="Confirm new password">
            </div>
            
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Platform Settings';
include __DIR__ . '/../layouts/admin.php';
?>
