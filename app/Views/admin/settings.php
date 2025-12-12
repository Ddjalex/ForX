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
            
            <h4 style="margin-bottom: 16px; color: var(--text-secondary);">Deposit Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Deposit Wallet Address</label>
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
            
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Platform Settings';
include __DIR__ . '/../layouts/admin.php';
?>
