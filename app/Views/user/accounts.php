<?php
$title = 'My Accounts - TradePro';
ob_start();
?>

<div class="accounts-page">
    <div class="page-header">
        <h1>My accounts</h1>
        <button class="btn btn-outline" onclick="openCreateModal()">
            <i class="fas fa-plus"></i> Open account
        </button>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="tabs-container">
        <div class="tabs">
            <a href="/accounts?tab=real" class="tab <?= $tab === 'real' ? 'active' : '' ?>">Real</a>
            <a href="/accounts?tab=demo" class="tab <?= $tab === 'demo' ? 'active' : '' ?>">Demo</a>
            <a href="/accounts?tab=archived" class="tab <?= $tab === 'archived' ? 'active' : '' ?>">Archived</a>
        </div>

        <div class="tab-controls">
            <select class="sort-select" onchange="location.href='/accounts?tab=<?= $tab ?>&sort=' + this.value">
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest</option>
            </select>
            <div class="view-toggle">
                <button class="view-btn active" title="List view"><i class="fas fa-list"></i></button>
                <button class="view-btn" title="Grid view"><i class="fas fa-th"></i></button>
            </div>
        </div>
    </div>

    <div class="accounts-list">
        <?php if (empty($accounts)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <p>No <?= $tab === 'archived' ? 'archived' : 'active' ?> accounts</p>
                <?php if ($tab !== 'archived'): ?>
                    <button class="btn btn-primary" onclick="openCreateModal('<?= $tab ?>')">Open account</button>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($accounts as $account): ?>
                <div class="account-card">
                    <div class="account-info">
                        <div class="account-badges">
                            <span class="badge badge-<?= $account['account_type'] ?>"><?= ucfirst($account['account_type']) ?></span>
                            <span class="badge badge-platform"><?= $account['platform'] ?></span>
                            <span class="badge badge-name"><?= htmlspecialchars($account['account_name']) ?></span>
                        </div>
                        <div class="account-number"># <?= $account['account_number'] ?></div>
                    </div>
                    <div class="account-balance">
                        <span class="balance-amount"><?= number_format($account['balance'], 2) ?></span>
                        <span class="balance-currency"><?= $account['currency'] ?></span>
                    </div>
                    <div class="account-actions">
                        <?php if ($account['status'] === 'active'): ?>
                            <a href="/accounts/trade?id=<?= $account['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-chart-line"></i> Trade
                            </a>
                            <?php if ($account['account_type'] === 'demo'): ?>
                                <button class="btn btn-outline btn-sm" onclick="openBalanceModal(<?= $account['id'] ?>, <?= $account['balance'] ?>)">
                                    <i class="fas fa-clock"></i> Set Balance
                                </button>
                            <?php endif; ?>
                            <div class="dropdown">
                                <button class="btn btn-icon"><i class="fas fa-ellipsis-v"></i></button>
                                <div class="dropdown-menu">
                                    <a href="/accounts/archive?id=<?= $account['id'] ?>" class="dropdown-item">Archive</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="/accounts/restore?id=<?= $account['id'] ?>" class="btn btn-outline btn-sm">Restore</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Open New Account</h3>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form action="/accounts/create" method="POST">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <div class="modal-body">
                <div class="form-group">
                    <label>Account Type</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="account_type" value="real" id="typeReal">
                            <span>Real</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="account_type" value="demo" id="typeDemo" checked>
                            <span>Demo</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Account Name</label>
                    <select name="account_name" class="form-control">
                        <option value="Standard">Standard</option>
                        <option value="Pro">Pro</option>
                        <option value="Raw Spread">Raw Spread</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Leverage</label>
                    <select name="leverage" class="form-control">
                        <option value="50">1:50</option>
                        <option value="100" selected>1:100</option>
                        <option value="200">1:200</option>
                        <option value="500">1:500</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeCreateModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
    </div>
</div>

<div id="balanceModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Set Demo Balance</h3>
            <button class="modal-close" onclick="closeBalanceModal()">&times;</button>
        </div>
        <form action="/accounts/set-balance" method="POST">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="account_id" id="balanceAccountId">
            <div class="modal-body">
                <div class="form-group">
                    <label>New Balance (USD)</label>
                    <input type="number" name="balance" id="balanceInput" class="form-control" min="0" max="10000000" step="0.01">
                </div>
                <div class="balance-presets">
                    <button type="button" class="preset-btn" onclick="setPresetBalance(10000)">$10,000</button>
                    <button type="button" class="preset-btn" onclick="setPresetBalance(50000)">$50,000</button>
                    <button type="button" class="preset-btn" onclick="setPresetBalance(100000)">$100,000</button>
                    <button type="button" class="preset-btn" onclick="setPresetBalance(500000)">$500,000</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeBalanceModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Balance</button>
            </div>
        </form>
    </div>
</div>

<style>
.accounts-page { padding: 20px; max-width: 1200px; margin: 0 auto; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.page-header h1 { font-size: 28px; font-weight: 600; }

.tabs-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 15px; }
.tabs { display: flex; gap: 20px; }
.tab { padding: 10px 5px; color: #888; text-decoration: none; border-bottom: 2px solid transparent; }
.tab.active { color: #fff; border-bottom-color: #f5a623; }
.tab:hover { color: #fff; }

.tab-controls { display: flex; gap: 15px; align-items: center; }
.sort-select { background: #2a2a2a; border: 1px solid #444; border-radius: 6px; color: #fff; padding: 8px 12px; }
.view-toggle { display: flex; gap: 5px; }
.view-btn { background: #2a2a2a; border: 1px solid #444; color: #888; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
.view-btn.active { color: #fff; background: #333; }

.accounts-list { display: flex; flex-direction: column; gap: 15px; }

.account-card { background: #1e1e1e; border-radius: 10px; padding: 20px 25px; display: flex; align-items: center; justify-content: space-between; }
.account-info { display: flex; flex-direction: column; gap: 8px; }
.account-badges { display: flex; gap: 10px; }
.badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 500; }
.badge-demo { background: #2a4a3a; color: #4ade80; }
.badge-real { background: #4a3a2a; color: #f5a623; }
.badge-platform { background: #333; color: #888; }
.badge-name { background: #333; color: #fff; }
.account-number { color: #888; font-size: 14px; }

.account-balance { text-align: center; }
.balance-amount { font-size: 28px; font-weight: 700; color: #fff; }
.balance-currency { color: #888; font-size: 14px; margin-left: 5px; }

.account-actions { display: flex; gap: 10px; align-items: center; }

.empty-state { text-align: center; padding: 60px 20px; background: #1e1e1e; border-radius: 10px; }
.empty-icon { font-size: 48px; color: #f5a623; margin-bottom: 15px; }
.empty-state p { color: #888; margin-bottom: 20px; }

.modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; }
.modal.show { display: flex; }
.modal-content { background: #1e1e1e; border-radius: 12px; width: 100%; max-width: 450px; }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #333; }
.modal-header h3 { margin: 0; }
.modal-close { background: none; border: none; color: #888; font-size: 24px; cursor: pointer; }
.modal-body { padding: 20px; }
.modal-footer { padding: 15px 20px; border-top: 1px solid #333; display: flex; gap: 10px; justify-content: flex-end; }

.radio-group { display: flex; gap: 20px; }
.radio-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.radio-label input { accent-color: #f5a623; }

.balance-presets { display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap; }
.preset-btn { background: #2a2a2a; border: 1px solid #444; color: #fff; padding: 8px 16px; border-radius: 6px; cursor: pointer; }
.preset-btn:hover { background: #333; border-color: #f5a623; }

.dropdown { position: relative; }
.dropdown-menu { display: none; position: absolute; right: 0; top: 100%; background: #2a2a2a; border-radius: 8px; min-width: 150px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 10; }
.dropdown:hover .dropdown-menu { display: block; }
.dropdown-item { display: block; padding: 10px 15px; color: #fff; text-decoration: none; }
.dropdown-item:hover { background: #333; }

.btn-icon { background: transparent; border: 1px solid #444; color: #888; width: 36px; height: 36px; border-radius: 6px; cursor: pointer; }
.btn-icon:hover { background: #333; color: #fff; }
</style>

<script>
function openCreateModal(type = 'demo') {
    document.getElementById('createModal').classList.add('show');
    if (type === 'real') {
        document.getElementById('typeReal').checked = true;
    } else {
        document.getElementById('typeDemo').checked = true;
    }
}

function closeCreateModal() {
    document.getElementById('createModal').classList.remove('show');
}

function openBalanceModal(accountId, currentBalance) {
    document.getElementById('balanceModal').classList.add('show');
    document.getElementById('balanceAccountId').value = accountId;
    document.getElementById('balanceInput').value = currentBalance;
}

function closeBalanceModal() {
    document.getElementById('balanceModal').classList.remove('show');
}

function setPresetBalance(amount) {
    document.getElementById('balanceInput').value = amount;
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/app/Views/layouts/main.php';
?>
