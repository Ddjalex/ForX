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
        <h3 class="card-title">Request Withdrawal</h3>
    </div>
    <div class="card-body">
        <div class="stats-grid" style="margin-bottom: 24px;">
            <div class="stat-card">
                <div class="stat-label">Available for Withdrawal</div>
                <div class="stat-value">$<?= number_format(($wallet['balance'] ?? 0) - ($wallet['margin_used'] ?? 0), 2) ?></div>
            </div>
        </div>

        <form method="POST" action="/wallet/withdraw">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            
            <div class="form-group">
                <label class="form-label" for="amount">Amount (USDT)</label>
                <input type="number" id="amount" name="amount" class="form-control" placeholder="Enter withdrawal amount" step="0.01" min="10" max="<?= ($wallet['balance'] ?? 0) - ($wallet['margin_used'] ?? 0) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Wallet Address (TRC20)</label>
                <input type="text" id="address" name="address" class="form-control" placeholder="Enter your USDT TRC20 wallet address" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Submit Withdrawal Request</button>
        </form>

        <div class="alert alert-warning mt-4">
            <strong>Note:</strong> Withdrawals are processed manually within 24-48 hours. A cooldown of 1 hour applies between withdrawal requests.
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Withdraw';
include __DIR__ . '/../layouts/main.php';
?>
