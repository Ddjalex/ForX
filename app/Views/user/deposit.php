<?php
ob_start();
if (!isset($settings)) $settings = [];
if (!isset($networks)) $networks = [];
if (!isset($success)) $success = '';
if (!isset($error)) $error = '';
if (!isset($csrf_token)) $csrf_token = '';

$minDeposit = $settings['min_deposit'] ?? '10';
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="deposit-grid">
    <div class="deposit-section">
        <div class="card-header">
            <h3 class="card-title">Deposit Using Cryptocurrency</h3>
        </div>

        <?php if (!empty($networks)): ?>
            <?php foreach ($networks as $network): ?>
            <div class="deposit-method">
                <h4><?= htmlspecialchars($network['name'] ?? 'Crypto') ?> (<?= htmlspecialchars($network['symbol'] ?? '') ?>) Deposit</h4>
                <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
                <div class="wallet-info" style="background: var(--bg-tertiary); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                    <p style="margin: 0 0 8px 0; color: var(--text-secondary);">Network: <strong style="color: var(--accent-primary);"><?= htmlspecialchars($network['network_type'] ?? '') ?></strong></p>
                    <p style="margin: 0; word-break: break-all; font-family: monospace; color: var(--text-primary);"><?= htmlspecialchars($network['wallet_address'] ?? '') ?></p>
                    <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 8px;" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($network['wallet_address'] ?? '') ?>'); this.textContent='Copied!';">Copy Address</button>
                </div>
                <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                    <input type="hidden" name="method" value="<?= htmlspecialchars(strtolower($network['symbol'] ?? '')) ?>">
                    <div class="form-group">
                        <label>Amount (USD)</label>
                        <input type="number" name="amount" class="form-control" placeholder="Min: <?= htmlspecialchars($minDeposit) ?>" step="0.01" min="<?= htmlspecialchars($minDeposit) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Transaction ID (TXID)</label>
                        <input type="text" name="txid" class="form-control" placeholder="Enter TXID" required>
                    </div>
                    <div class="form-group">
                        <label>Payment Proof (Image)</label>
                        <input type="file" name="proof" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Deposit</button>
                </form>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="deposit-method">
                <p style="text-align: center; color: var(--text-secondary);">No deposit methods have been configured yet. Please contact support.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="deposit-section">
        <div class="card-header">
            <h3 class="card-title">Other Deposit Options</h3>
        </div>

        <div class="deposit-method">
            <h4>Request other available Deposit Method</h4>
            <p>Once payment is made using this method you are to send your payment proof to our support mail <a href="mailto:<?= htmlspecialchars($settings['support_email'] ?? 'support@tradeflowglobalex.com') ?>"><?= htmlspecialchars($settings['support_email'] ?? 'support@tradeflowglobalex.com') ?></a></p>
            <p>Once requested, you will receive the payment details via our support mail.</p>
            <form method="POST" action="/wallet/deposit">
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <input type="hidden" name="method" value="other">
                <button type="submit" class="btn btn-secondary">Request Details</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Make Deposit';
include __DIR__ . '/../layouts/main.php';
?>
