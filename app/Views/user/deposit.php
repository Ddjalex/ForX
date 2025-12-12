<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h3 class="card-title">Make a Deposit</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-warning mb-4">
            <strong>Deposit Address:</strong><br>
            <code style="word-break: break-all;"><?= htmlspecialchars($depositWallet) ?></code>
        </div>
        
        <p style="color: var(--text-secondary); margin-bottom: 24px;">
            Send USDT (TRC20) to the address above, then submit the transaction details below for verification.
        </p>

        <form method="POST" action="/wallet/deposit" enctype="multipart/form-data">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            
            <div class="form-group">
                <label class="form-label" for="amount">Amount (USDT)</label>
                <input type="number" id="amount" name="amount" class="form-control" placeholder="Enter amount" step="0.01" min="10" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="txid">Transaction ID (TXID)</label>
                <input type="text" id="txid" name="txid" class="form-control" placeholder="Enter the blockchain transaction ID" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="proof">Proof Screenshot (Optional)</label>
                <input type="file" id="proof" name="proof" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Submit Deposit Request</button>
        </form>

        <p class="text-center mt-4" style="color: var(--text-muted); font-size: 13px;">
            Deposits are usually processed within 1-24 hours after confirmation.
        </p>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Deposit';
include __DIR__ . '/../layouts/main.php';
?>
