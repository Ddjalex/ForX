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
                <div class="wallet-info" style="background: var(--bg-tertiary); padding: 20px; border-radius: 12px; margin-bottom: 16px; text-align: center; border: 1px solid rgba(255,255,255,0.05);">
                    <p style="margin: 0 0 12px 0; color: var(--text-secondary); text-align: left; font-size: 14px;">Network: <strong style="color: var(--accent-primary);"><?= htmlspecialchars($network['network_type'] ?? '') ?></strong></p>
                    
                    <?php if (!empty($network['qr_code'])): ?>
                    <div style="margin: 15px auto; padding: 15px; background: #fff; border-radius: 12px; display: inline-block; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                        <?php 
                        $displayPath = $network['qr_code'];
                        // Ensure path starts with /uploads/
                        if (strpos($displayPath, '/public/') === 0) {
                            $displayPath = substr($displayPath, 7);
                        }
                        if (strpos($displayPath, 'uploads/') === 0) {
                            $displayPath = '/' . $displayPath;
                        }
                        ?>
                        <img src="<?= htmlspecialchars($displayPath) ?>?v=<?= time() ?>" alt="QR Code" style="width: 180px; height: 180px; display: block; object-fit: contain;">
                        <p style="font-size: 11px; color: #64748b; margin-top: 10px; font-weight: 600; text-transform: uppercase;">Scan to pay <?= htmlspecialchars($network['symbol'] ?? '') ?></p>
                    </div>
                    <?php endif; ?>

                    <div style="margin-top: 15px; background: rgba(0,0,0,0.2); padding: 12px; border-radius: 8px; border: 1px dashed rgba(255,255,255,0.1);">
                        <p style="margin: 0; word-break: break-all; font-family: 'Inter', monospace; color: #fff; font-size: 14px; letter-spacing: 0.5px;"><?= htmlspecialchars($network['wallet_address'] ?? '') ?></p>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 12px; width: 100%;" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($network['wallet_address'] ?? '') ?>'); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy Address', 2000);">Copy Address</button>
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
