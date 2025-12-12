<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Market Settings</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Symbol</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Spread</th>
                    <th>Max Leverage</th>
                    <th>Min Trade</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($markets as $m): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($m['symbol']) ?></strong></td>
                        <td><?= htmlspecialchars($m['name']) ?></td>
                        <td><span class="badge badge-info"><?= ucfirst($m['type']) ?></span></td>
                        <td><?= number_format($m['spread'] * 100, 3) ?>%</td>
                        <td><?= $m['max_leverage'] ?>x</td>
                        <td><?= $m['min_trade_size'] ?></td>
                        <td><?= number_format($m['fee'] * 100, 2) ?>%</td>
                        <td>
                            <span class="badge badge-<?= $m['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= ucfirst($m['status']) ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-secondary btn-sm" onclick="editMarket(<?= htmlspecialchars(json_encode($m)) ?>)">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal-overlay" id="marketModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Market</h3>
            <button class="modal-close" onclick="hideMarketModal()">&times;</button>
        </div>
        <form method="POST" action="/admin/markets/update">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="market_id" id="marketId">
            
            <div class="form-group">
                <label class="form-label">Spread (%)</label>
                <input type="number" name="spread" id="marketSpread" class="form-control" step="0.0001" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Max Leverage</label>
                <input type="number" name="max_leverage" id="marketLeverage" class="form-control" min="1" max="500" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Min Trade Size</label>
                <input type="number" name="min_trade_size" id="marketMinTrade" class="form-control" step="0.0001" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Fee (%)</label>
                <input type="number" name="fee" id="marketFee" class="form-control" step="0.0001" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" id="marketStatus" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Update Market</button>
        </form>
    </div>
</div>

<script>
function editMarket(market) {
    document.getElementById('marketId').value = market.id;
    document.getElementById('marketSpread').value = market.spread;
    document.getElementById('marketLeverage').value = market.max_leverage;
    document.getElementById('marketMinTrade').value = market.min_trade_size;
    document.getElementById('marketFee').value = market.fee;
    document.getElementById('marketStatus').value = market.status;
    document.getElementById('marketModal').classList.add('active');
}

function hideMarketModal() {
    document.getElementById('marketModal').classList.remove('active');
}
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Market Management';
include __DIR__ . '/../layouts/admin.php';
?>
