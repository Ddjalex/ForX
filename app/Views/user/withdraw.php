<?php
ob_start();
$availableBalance = ($wallet['balance'] ?? 0) - ($wallet['margin_used'] ?? 0);
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Payment Details</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/wallet/withdraw" class="withdrawal-form">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                
                <div class="form-group">
                    <label class="form-label">Withdrawal Type</label>
                    <select name="method" class="form-control" required>
                        <option value="">-- select withdrawal method --</option>
                        <option value="solana">Solana</option>
                        <option value="usdt">USDT</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="litecoin">Litecoin</option>
                        <option value="ethereum">Ethereum</option>
                        <option value="bitcoin">Bitcoin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Amount (USD)</label>
                    <input type="number" name="amount" class="form-control" placeholder="Enter withdrawal amount" step="0.01" min="10" max="<?= $availableBalance ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Wallet Address / Bank Details</label>
                    <input type="text" name="address" class="form-control" placeholder="Enter your wallet address or bank account details" required>
                </div>

                <button type="submit" class="btn btn-primary">Request Withdrawal</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Withdrawing Funds - How Does It Work?</h3>
        </div>
        <div class="card-body">
            <p style="color: var(--text-secondary); margin-bottom: 15px;">
                At our platform, we have designed our withdrawal process to be as easy and secured as our funding process. To begin the withdrawal process first fill your account information then select your preferred withdrawal method and then type in the amount you want to withdraw, verify your identity by uploading a valid ID, click "Request Withdrawal".
            </p>

            <h4 style="margin-top: 20px; margin-bottom: 10px;">What Methods Are There For Withdrawal Of Funds?</h4>
            <p style="color: var(--text-secondary); margin-bottom: 15px;">
                We provide provide better withdrawal methods like (Bitcoin, PayPal, Bank Transfer and lot more).
            </p>

            <h4 style="margin-top: 20px; margin-bottom: 10px;">Must Withdrawal Requests Only Be Made At Certain Times?</h4>
            <p style="color: var(--text-secondary); margin-bottom: 15px;">
                Requests for withdrawals can be made at any time via our platform. The requests will be processed immediately, and during the relevant financial institutions' business hours.
            </p>

            <h4 style="margin-top: 20px; margin-bottom: 10px;">Is There A Withdrawal Limit?</h4>
            <p style="color: var(--text-secondary); margin-bottom: 15px;">
                Withdrawals are capped at the amount of funds that are currently in the account.
            </p>

            <h4 style="margin-top: 20px; margin-bottom: 10px;">How Long Does It Take To Get My Money?</h4>
            <p style="color: var(--text-secondary);">
                Withdrawal requests are addressed and handled as quickly as possible.
            </p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Withdraw Funds';
include __DIR__ . '/../layouts/main.php';
?>
