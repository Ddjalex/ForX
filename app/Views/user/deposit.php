<?php
ob_start();
$btcAddress = $settings['deposit_btc_address'] ?? '';
$btcNetwork = $settings['deposit_btc_network'] ?? 'BTC';
$ethAddress = $settings['deposit_eth_address'] ?? '';
$ethNetwork = $settings['deposit_eth_network'] ?? 'ERC20';
$usdtAddress = $settings['deposit_usdt_address'] ?? '';
$usdtNetwork = $settings['deposit_usdt_network'] ?? 'TRC20';
$ltcAddress = $settings['deposit_ltc_address'] ?? '';
$ltcNetwork = $settings['deposit_ltc_network'] ?? 'LTC';
$solAddress = $settings['deposit_sol_address'] ?? '';
$solNetwork = $settings['deposit_sol_network'] ?? 'SOL';
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
        <div class="card-header collapsible">
            <h3 class="card-title">Deposit Using Cryptocurrency</h3>
        </div>

        <?php if (!empty($btcAddress)): ?>
        <div class="deposit-method">
            <h4>Bitcoin (BTC) Deposit</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <div class="wallet-info" style="background: var(--bg-tertiary); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                <p style="margin: 0 0 8px 0; color: var(--text-secondary);">Network: <strong style="color: var(--accent-primary);"><?= htmlspecialchars($btcNetwork) ?></strong></p>
                <p style="margin: 0; word-break: break-all; font-family: monospace; color: var(--text-primary);"><?= htmlspecialchars($btcAddress) ?></p>
                <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 8px;" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($btcAddress) ?>'); this.textContent='Copied!';">Copy Address</button>
            </div>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="bitcoin">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="<?= htmlspecialchars($minDeposit) ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)" required>
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Submit Deposit</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if (!empty($ethAddress)): ?>
        <div class="deposit-method">
            <h4>Ethereum (ETH) Deposit</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <div class="wallet-info" style="background: var(--bg-tertiary); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                <p style="margin: 0 0 8px 0; color: var(--text-secondary);">Network: <strong style="color: var(--accent-primary);"><?= htmlspecialchars($ethNetwork) ?></strong></p>
                <p style="margin: 0; word-break: break-all; font-family: monospace; color: var(--text-primary);"><?= htmlspecialchars($ethAddress) ?></p>
                <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 8px;" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($ethAddress) ?>'); this.textContent='Copied!';">Copy Address</button>
            </div>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="ethereum">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="<?= htmlspecialchars($minDeposit) ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)" required>
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Submit Deposit</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if (!empty($ltcAddress)): ?>
        <div class="deposit-method">
            <h4>Litecoin (LTC) Deposit</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <div class="wallet-info" style="background: var(--bg-tertiary); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                <p style="margin: 0 0 8px 0; color: var(--text-secondary);">Network: <strong style="color: var(--accent-primary);"><?= htmlspecialchars($ltcNetwork) ?></strong></p>
                <p style="margin: 0; word-break: break-all; font-family: monospace; color: var(--text-primary);"><?= htmlspecialchars($ltcAddress) ?></p>
                <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 8px;" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($ltcAddress) ?>'); this.textContent='Copied!';">Copy Address</button>
            </div>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="litecoin">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="<?= htmlspecialchars($minDeposit) ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)" required>
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Submit Deposit</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if (!empty($usdtAddress)): ?>
        <div class="deposit-method">
            <h4>USDT Deposit</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <div class="wallet-info" style="background: var(--bg-tertiary); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                <p style="margin: 0 0 8px 0; color: var(--text-secondary);">Network: <strong style="color: var(--accent-primary);"><?= htmlspecialchars($usdtNetwork) ?></strong></p>
                <p style="margin: 0; word-break: break-all; font-family: monospace; color: var(--text-primary);"><?= htmlspecialchars($usdtAddress) ?></p>
                <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 8px;" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($usdtAddress) ?>'); this.textContent='Copied!';">Copy Address</button>
            </div>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="usdt">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="<?= htmlspecialchars($minDeposit) ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)" required>
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Submit Deposit</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if (!empty($solAddress)): ?>
        <div class="deposit-method">
            <h4>Solana (SOL) Deposit</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <div class="wallet-info" style="background: var(--bg-tertiary); padding: 12px; border-radius: 8px; margin-bottom: 16px;">
                <p style="margin: 0 0 8px 0; color: var(--text-secondary);">Network: <strong style="color: var(--accent-primary);"><?= htmlspecialchars($solNetwork) ?></strong></p>
                <p style="margin: 0; word-break: break-all; font-family: monospace; color: var(--text-primary);"><?= htmlspecialchars($solAddress) ?></p>
                <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 8px;" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($solAddress) ?>'); this.textContent='Copied!';">Copy Address</button>
            </div>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="solana">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="<?= htmlspecialchars($minDeposit) ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)" required>
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Submit Deposit</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if (empty($btcAddress) && empty($ethAddress) && empty($ltcAddress) && empty($usdtAddress) && empty($solAddress)): ?>
        <div class="deposit-method">
            <p style="text-align: center; color: var(--text-secondary);">No deposit methods have been configured yet. Please contact support.</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="deposit-section">
        <div class="card-header collapsible">
            <h3 class="card-title">Other Deposit Options</h3>
        </div>

        <div class="deposit-method">
            <h4>Request other available Deposit Method</h4>
            <p>Once payment is made using this method you are to send your payment proof to our support mail <a href="mailto:support@tradeflowglobalex.com">support@tradeflowglobalex.com</a></p>
            <p>Once requested, you will receive the payment details via our support mail....</p>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="other">
                <button type="submit" class="btn btn-secondary">Processed</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Make Deposit';
include __DIR__ . '/../layouts/main.php';
?>
