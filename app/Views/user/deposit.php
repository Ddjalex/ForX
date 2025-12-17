<?php
ob_start();
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
            <h3 class="card-title">Deposit Using Bitcoin/Ethereum</h3>
        </div>

        <div class="deposit-method">
            <h4>Bitcoin Deposit Method</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <p>On confirmation, our system will automatically convert your Bitcoin to live value of Dollars. Ensure that you deposit the actual Bitcoin to the address specified on the payment Page.</p>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="bitcoin">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="10" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)">
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Make Deposit</button>
            </form>
        </div>

        <div class="deposit-method">
            <h4>Ethereum Deposit Method</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <p>On confirmation, our system will automatically convert your Ethereum to live value of Dollars. Ensure that you deposit the actual Ethereum to the address specified on the payment Page.</p>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="ethereum">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="10" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)">
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Make Deposit</button>
            </form>
        </div>

        <div class="deposit-method">
            <h4>Litecoin Deposit Method</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <p>On confirmation, our system will automatically convert your Litecoin to live value of Dollars. Ensure that you deposit the actual Litecoin to the address specified on the payment Page.</p>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="litecoin">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="10" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)">
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Make Deposit</button>
            </form>
        </div>

        <div class="deposit-method">
            <h4>USDT Deposit Method</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <p>On confirmation, our system will automatically convert your USDT to live value of Dollars. Ensure that you deposit the actual USDT to the address specified on the payment Page.</p>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="usdt">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="10" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)">
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Make Deposit</button>
            </form>
        </div>

        <div class="deposit-method">
            <h4>Solana Deposit Method</h4>
            <p class="warning">Please make sure you upload your payment proof for quick payment verification</p>
            <p>On confirmation, our system will automatically convert your Solana to live value of Dollars. Ensure that you deposit the actual Solana to the address specified on the payment Page.</p>
            <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="method" value="solana">
                <div class="form-group">
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount in USD" step="0.01" min="10" required>
                </div>
                <div class="form-group">
                    <input type="text" name="txid" class="form-control" placeholder="Transaction ID (TXID)">
                </div>
                <div class="form-group">
                    <input type="file" name="proof" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Make Deposit</button>
            </form>
        </div>
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
