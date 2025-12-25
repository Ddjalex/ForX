<div class="settings-container">
    <div class="settings-sidebar">
        <div class="settings-nav">
            <button class="nav-item active" onclick="switchSection('deposit-networks')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"></path><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"></path><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"></path></svg>
                Deposit Networks
            </button>
            <button class="nav-item" onclick="switchSection('general-settings')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                General Settings
            </button>
            <button class="nav-item" onclick="switchSection('profit-control')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                Profit Control
            </button>
            <button class="nav-item" onclick="switchSection('support-settings')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                Customer Support
            </button>
            <button class="nav-item" onclick="switchSection('security-settings')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Security
            </button>
        </div>
    </div>

    <div class="settings-content">
        <form method="POST" action="/admin/settings" id="settingsForm">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">

            <!-- Section: Deposit Networks -->
            <div id="section-deposit-networks" class="settings-section active">
                <div class="section-header">
                    <h3 class="section-title">Deposit Network Settings</h3>
                    <p class="section-desc">Manage your crypto wallet addresses and network types for deposits.</p>
                </div>

                <div class="settings-grid">
                    <div class="settings-card">
                        <div class="card-title-sm">Bitcoin (BTC)</div>
                        <div class="form-group">
                            <label class="form-label">Wallet Address</label>
                            <input type="text" name="deposit_btc_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_btc_address'] ?? '') ?>" placeholder="bc1q...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Network Type</label>
                            <select name="deposit_btc_network" class="form-control">
                                <option value="BTC" <?= ($settings['deposit_btc_network'] ?? '') === 'BTC' ? 'selected' : '' ?>>BTC (Native)</option>
                                <option value="BEP20" <?= ($settings['deposit_btc_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                            </select>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="card-title-sm">Ethereum (ETH)</div>
                        <div class="form-group">
                            <label class="form-label">Wallet Address</label>
                            <input type="text" name="deposit_eth_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_eth_address'] ?? '') ?>" placeholder="0x...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Network Type</label>
                            <select name="deposit_eth_network" class="form-control">
                                <option value="ERC20" <?= ($settings['deposit_eth_network'] ?? '') === 'ERC20' ? 'selected' : '' ?>>ERC20 (Ethereum)</option>
                                <option value="BEP20" <?= ($settings['deposit_eth_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                                <option value="ARB" <?= ($settings['deposit_eth_network'] ?? '') === 'ARB' ? 'selected' : '' ?>>Arbitrum</option>
                            </select>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="card-title-sm">USDT (Tether)</div>
                        <div class="form-group">
                            <label class="form-label">Wallet Address</label>
                            <input type="text" name="deposit_usdt_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_usdt_address'] ?? '') ?>" placeholder="TRX/0x...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Network Type</label>
                            <select name="deposit_usdt_network" class="form-control">
                                <option value="TRC20" <?= ($settings['deposit_usdt_network'] ?? '') === 'TRC20' ? 'selected' : '' ?>>TRC20 (Tron)</option>
                                <option value="ERC20" <?= ($settings['deposit_usdt_network'] ?? '') === 'ERC20' ? 'selected' : '' ?>>ERC20 (Ethereum)</option>
                                <option value="BEP20" <?= ($settings['deposit_usdt_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                            </select>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="card-title-sm">Litecoin (LTC)</div>
                        <div class="form-group">
                            <label class="form-label">Wallet Address</label>
                            <input type="text" name="deposit_ltc_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_ltc_address'] ?? '') ?>" placeholder="LTC address">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Network Type</label>
                            <select name="deposit_ltc_network" class="form-control">
                                <option value="LTC" <?= ($settings['deposit_ltc_network'] ?? '') === 'LTC' ? 'selected' : '' ?>>LTC (Native)</option>
                                <option value="BEP20" <?= ($settings['deposit_ltc_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                            </select>
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="card-title-sm">Solana (SOL)</div>
                        <div class="form-group">
                            <label class="form-label">Wallet Address</label>
                            <input type="text" name="deposit_sol_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_sol_address'] ?? '') ?>" placeholder="SOL address">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Network Type</label>
                            <select name="deposit_sol_network" class="form-control">
                                <option value="SOL" <?= ($settings['deposit_sol_network'] ?? '') === 'SOL' ? 'selected' : '' ?>>SOL (Native)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: General Settings -->
            <div id="section-general-settings" class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">General Platform Settings</h3>
                    <p class="section-desc">Configure deposits, withdrawals, and referral commissions.</p>
                </div>

                <div class="settings-grid">
                    <div class="settings-card">
                        <div class="card-title-sm">Limits & Fees</div>
                        <div class="form-group">
                            <label class="form-label">Minimum Deposit ($)</label>
                            <input type="number" name="min_deposit" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['min_deposit'] ?? '10') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Minimum Withdrawal ($)</label>
                            <input type="number" name="min_withdrawal" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['min_withdrawal'] ?? '20') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Withdrawal Fee (%)</label>
                            <input type="number" name="withdrawal_fee" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['withdrawal_fee'] ?? '1') ?>">
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="card-title-sm">Referral Program</div>
                        <div class="form-group">
                            <label class="form-label">Referral Commission (%)</label>
                            <input type="number" name="referral_commission" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['referral_commission'] ?? '5') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Min. Deposit for Reward ($)</label>
                            <input type="number" name="referral_min_deposit" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['referral_min_deposit'] ?? '50') ?>">
                        </div>
                    </div>

                    <div class="settings-card">
                        <div class="card-title-sm">Risk Control</div>
                        <div class="form-group">
                            <label class="form-label">Global Max Leverage</label>
                            <input type="number" name="global_max_leverage" class="form-control" value="<?= htmlspecialchars($settings['global_max_leverage'] ?? '100') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max Position Size ($)</label>
                            <input type="number" name="max_position_size" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['max_position_size'] ?? '100000') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Profit Control -->
            <div id="section-profit-control" class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">Profit Control Settings</h3>
                    <p class="section-desc">Adjust global profit/loss multipliers for users.</p>
                </div>

                <div class="settings-card" style="max-width: 600px;">
                    <div class="form-group">
                        <label class="form-label">Adjustment Percentage (%)</label>
                        <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 24px;">
                            <input type="range" id="profitControlSlider" name="profit_control_percent" class="form-control" style="flex: 1; height: 6px; cursor: pointer;" min="-10" max="100" step="1" value="<?= htmlspecialchars($settings['profit_control_percent'] ?? '0') ?>" oninput="updateProfitCalculations()">
                            <div id="profitControlValue" style="min-width: 100px; padding: 10px; background: var(--accent-primary); border-radius: 8px; color: #000; font-weight: 700; text-align: center; font-size: 16px;">0.00%</div>
                        </div>
                    </div>

                    <div style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 12px; padding: 20px;">
                        <h4 style="margin: 0 0 16px 0; color: #fff; font-size: 15px; display: flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-primary)" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                            Multiplier Analysis
                        </h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <div style="color: #8899a6; font-size: 12px; text-transform: uppercase; margin-bottom: 4px;">Win Multiplier</div>
                                <div id="winMultiplier" style="font-size: 20px; font-weight: 700; color: #fff;">× 1.0000</div>
                                <div style="color: #10B981; font-size: 13px; margin-top: 4px;">Example: <span id="winExample">$100.00</span></div>
                            </div>
                            <div>
                                <div style="color: #8899a6; font-size: 12px; text-transform: uppercase; margin-bottom: 4px;">Loss Multiplier</div>
                                <div id="lossMultiplier" style="font-size: 20px; font-weight: 700; color: #fff;">× 1.0000</div>
                                <div style="color: #FF4757; font-size: 13px; margin-top: 4px;">Example: <span id="lossExample">$100.00</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Support Settings -->
            <div id="section-support-settings" class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">Customer Support Settings</h3>
                    <p class="section-desc">Integrate live chat and support features.</p>
                </div>

                <div class="settings-card" style="max-width: 500px;">
                    <div class="form-group">
                        <label class="form-label">Tawk.to Property ID</label>
                        <input type="text" name="tawkto_property_id" class="form-control" value="<?= htmlspecialchars($settings['tawkto_property_id'] ?? '') ?>" placeholder="5f4bc...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tawk.to Widget ID</label>
                        <input type="text" name="tawkto_widget_id" class="form-control" value="<?= htmlspecialchars($settings['tawkto_widget_id'] ?? '') ?>" placeholder="1e2ab...">
                    </div>
                    <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 8px; padding: 12px; margin-top: 16px; font-size: 13px; color: #94a3b8;">
                        Find these values in your Tawk.to Dashboard under Administration > Chat Widget.
                    </div>
                </div>
            </div>

            <!-- Section: Security -->
            <div id="section-security-settings" class="settings-section">
                <div class="section-header">
                    <h3 class="section-title">Admin Security</h3>
                    <p class="section-desc">Change your administrative password.</p>
                </div>

                <div class="settings-card" style="max-width: 500px;">
                    <div id="passwordError" class="alert alert-danger" style="display: none;"></div>
                    <div id="passwordSuccess" class="alert alert-success" style="display: none;"></div>
                    
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" id="old_password" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" id="new_password" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" id="confirm_password" class="form-control" placeholder="••••••••">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="changePassword()">Update Password</button>
                </div>
            </div>

            <div class="settings-footer">
                <button type="submit" class="btn btn-primary btn-lg">Save All Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
.settings-container {
    display: flex;
    gap: 32px;
    align-items: flex-start;
}

.settings-sidebar {
    width: 280px;
    background: #1e293b;
    border-radius: 16px;
    padding: 16px;
    border: 1px solid rgba(255,255,255,0.05);
    position: sticky;
    top: 24px;
}

.settings-nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: transparent;
    border: none;
    color: #94a3b8;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
    text-align: left;
}

.nav-item:hover {
    background: rgba(255,255,255,0.05);
    color: #fff;
}

.nav-item.active {
    background: var(--accent-primary);
    color: #000;
}

.settings-content {
    flex: 1;
}

.settings-section {
    display: none;
    animation: fadeIn 0.3s ease-out;
}

.settings-section.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.section-header {
    margin-bottom: 32px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 8px;
}

.section-desc {
    color: #64748b;
    font-size: 15px;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

.settings-card {
    background: #1e293b;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgba(255,255,255,0.05);
}

.card-title-sm {
    font-size: 13px;
    font-weight: 700;
    color: var(--accent-primary);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
}

.settings-footer {
    margin-top: 40px;
    padding-top: 24px;
    border-top: 1px solid rgba(255,255,255,0.05);
}

@media (max-width: 1024px) {
    .settings-container {
        flex-direction: column;
    }
    .settings-sidebar {
        width: 100%;
        position: static;
    }
    .settings-nav {
        flex-direction: row;
        overflow-x: auto;
        padding-bottom: 8px;
    }
    .nav-item {
        white-space: nowrap;
        width: auto;
    }
}
</style>

<script>
function switchSection(sectionId) {
    // Update nav buttons
    document.querySelectorAll('.nav-item').forEach(btn => {
        btn.classList.remove('active');
        if (btn.textContent.trim().toLowerCase().includes(sectionId.replace('-', ' '))) {
            btn.classList.add('active');
        }
    });

    // Handle manual active mapping
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => item.classList.remove('active'));
    event.currentTarget.classList.add('active');

    // Update sections
    document.querySelectorAll('.settings-section').forEach(sec => {
        sec.classList.remove('active');
    });
    document.getElementById('section-' + sectionId).classList.add('active');
}

function updateProfitCalculations() {
    const slider = document.getElementById('profitControlSlider');
    const percent = parseFloat(slider.value);
    
    // Update display value
    document.getElementById('profitControlValue').textContent = (percent >= 0 ? '+' : '') + percent.toFixed(2) + '%';
    
    // Calculate multipliers
    const winMultiplier = 1 + (percent / 100);
    const lossMultiplier = 1 + (percent / 100);
    
    // Update multiplier displays
    document.getElementById('winMultiplier').textContent = '× ' + winMultiplier.toFixed(4);
    document.getElementById('lossMultiplier').textContent = '× ' + lossMultiplier.toFixed(4);
    
    // Update examples
    const winExample = 100 * winMultiplier;
    const lossExample = 100 * lossMultiplier;
    
    document.getElementById('winExample').textContent = '$' + winExample.toFixed(2);
    document.getElementById('lossExample').textContent = '$' + lossExample.toFixed(2);
    
    // Color code the value box
    const valueBox = document.getElementById('profitControlValue');
    if (percent > 0) {
        valueBox.style.background = '#10B981';
        valueBox.style.color = '#fff';
    } else if (percent < 0) {
        valueBox.style.background = '#FF4757';
        valueBox.style.color = '#fff';
    } else {
        valueBox.style.background = 'var(--accent-primary)';
        valueBox.style.color = '#000';
    }
}

async function changePassword() {
    const oldPass = document.getElementById('old_password').value;
    const newPass = document.getElementById('new_password').value;
    const confPass = document.getElementById('confirm_password').value;
    const errorEl = document.getElementById('passwordError');
    const successEl = document.getElementById('passwordSuccess');

    errorEl.style.display = 'none';
    successEl.style.display = 'none';

    if (!oldPass || !newPass || !confPass) {
        errorEl.textContent = 'Please fill in all fields';
        errorEl.style.display = 'block';
        return;
    }

    if (newPass !== confPass) {
        errorEl.textContent = 'New passwords do not match';
        errorEl.style.display = 'block';
        return;
    }

    try {
        const response = await fetch('/admin/change-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `_csrf_token=<?= $csrf_token ?>&old_password=${encodeURIComponent(oldPass)}&new_password=${encodeURIComponent(newPass)}&confirm_password=${encodeURIComponent(confPass)}`
        });

        if (response.ok) {
            successEl.textContent = 'Password updated successfully';
            successEl.style.display = 'block';
            document.getElementById('old_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('confirm_password').value = '';
        } else {
            const text = await response.text();
            errorEl.textContent = 'Failed to update password. Please check your current password.';
            errorEl.style.display = 'block';
        }
    } catch (e) {
        errorEl.textContent = 'An error occurred. Please try again.';
        errorEl.style.display = 'block';
    }
}

// Initialize on page load
window.addEventListener('DOMContentLoaded', updateProfitCalculations);
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Platform Settings';
include __DIR__ . '/../layouts/admin.php';
?>

