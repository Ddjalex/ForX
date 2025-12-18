<?php
ob_start();
?>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3 class="card-title">Platform Settings</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="/admin/settings">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            
            <h4 style="margin-bottom: 16px; color: var(--text-secondary);">Deposit Network Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Bitcoin (BTC) Wallet Address</label>
                <input type="text" name="deposit_btc_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_btc_address'] ?? '') ?>" placeholder="Enter Bitcoin wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Bitcoin Network Type</label>
                <select name="deposit_btc_network" class="form-control">
                    <option value="BTC" <?= ($settings['deposit_btc_network'] ?? '') === 'BTC' ? 'selected' : '' ?>>BTC (Native)</option>
                    <option value="BEP20" <?= ($settings['deposit_btc_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Ethereum (ETH) Wallet Address</label>
                <input type="text" name="deposit_eth_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_eth_address'] ?? '') ?>" placeholder="Enter Ethereum wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Ethereum Network Type</label>
                <select name="deposit_eth_network" class="form-control">
                    <option value="ERC20" <?= ($settings['deposit_eth_network'] ?? '') === 'ERC20' ? 'selected' : '' ?>>ERC20 (Ethereum)</option>
                    <option value="BEP20" <?= ($settings['deposit_eth_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                    <option value="ARB" <?= ($settings['deposit_eth_network'] ?? '') === 'ARB' ? 'selected' : '' ?>>Arbitrum</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">USDT Wallet Address</label>
                <input type="text" name="deposit_usdt_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_usdt_address'] ?? '') ?>" placeholder="Enter USDT wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">USDT Network Type</label>
                <select name="deposit_usdt_network" class="form-control">
                    <option value="TRC20" <?= ($settings['deposit_usdt_network'] ?? '') === 'TRC20' ? 'selected' : '' ?>>TRC20 (Tron)</option>
                    <option value="ERC20" <?= ($settings['deposit_usdt_network'] ?? '') === 'ERC20' ? 'selected' : '' ?>>ERC20 (Ethereum)</option>
                    <option value="BEP20" <?= ($settings['deposit_usdt_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Litecoin (LTC) Wallet Address</label>
                <input type="text" name="deposit_ltc_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_ltc_address'] ?? '') ?>" placeholder="Enter Litecoin wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Litecoin Network Type</label>
                <select name="deposit_ltc_network" class="form-control">
                    <option value="LTC" <?= ($settings['deposit_ltc_network'] ?? '') === 'LTC' ? 'selected' : '' ?>>LTC (Native)</option>
                    <option value="BEP20" <?= ($settings['deposit_ltc_network'] ?? '') === 'BEP20' ? 'selected' : '' ?>>BEP20 (BSC)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Solana (SOL) Wallet Address</label>
                <input type="text" name="deposit_sol_address" class="form-control" value="<?= htmlspecialchars($settings['deposit_sol_address'] ?? '') ?>" placeholder="Enter Solana wallet address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Solana Network Type</label>
                <select name="deposit_sol_network" class="form-control">
                    <option value="SOL" <?= ($settings['deposit_sol_network'] ?? '') === 'SOL' ? 'selected' : '' ?>>SOL (Native)</option>
                </select>
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Deposit Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Legacy Deposit Wallet Address (TRC20)</label>
                <input type="text" name="deposit_wallet" class="form-control" value="<?= htmlspecialchars($settings['deposit_wallet'] ?? '') ?>" placeholder="TRC20 Wallet Address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Minimum Deposit ($)</label>
                <input type="number" name="min_deposit" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['min_deposit'] ?? '10') ?>">
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Withdrawal Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Minimum Withdrawal ($)</label>
                <input type="number" name="min_withdrawal" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['min_withdrawal'] ?? '20') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Withdrawal Fee (%)</label>
                <input type="number" name="withdrawal_fee" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['withdrawal_fee'] ?? '1') ?>">
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Referral Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Referral Commission (%)</label>
                <input type="number" name="referral_commission" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['referral_commission'] ?? '5') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Minimum Deposit for Referral Reward ($)</label>
                <input type="number" name="referral_min_deposit" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['referral_min_deposit'] ?? '50') ?>">
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Risk Controls</h4>
            
            <div class="form-group">
                <label class="form-label">Global Max Leverage</label>
                <input type="number" name="global_max_leverage" class="form-control" value="<?= htmlspecialchars($settings['global_max_leverage'] ?? '100') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label">Max Position Size ($)</label>
                <input type="number" name="max_position_size" class="form-control" step="0.01" value="<?= htmlspecialchars($settings['max_position_size'] ?? '100000') ?>">
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Customer Support Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Tawk.to Property ID</label>
                <input type="text" name="tawkto_property_id" class="form-control" value="<?= htmlspecialchars($settings['tawkto_property_id'] ?? '') ?>" placeholder="e.g., 5f4bc7e47d0ed2170c4a68bc">
                <small class="form-text text-muted">Get this from Tawk.to Dashboard > Administration > Chat Widget</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tawk.to Widget ID</label>
                <input type="text" name="tawkto_widget_id" class="form-control" value="<?= htmlspecialchars($settings['tawkto_widget_id'] ?? '') ?>" placeholder="e.g., 1e2abc3d4">
                <small class="form-text text-muted">Found in the same widget code from Tawk.to Dashboard</small>
            </div>
            
            <h4 style="margin: 24px 0 16px; color: var(--text-secondary);">Profit Control Settings</h4>
            
            <div class="form-group">
                <label class="form-label">Profit Control Percentage (%)</label>
                <div class="slider-container" style="margin-top: 12px;">
                    <input type="range" name="profit_control_percent" class="profit-slider" id="profitSlider" min="-10" max="100" step="0.01" value="<?= htmlspecialchars($settings['profit_control_percent'] ?? '0') ?>">
                    <div class="slider-value-display" id="sliderValue"><?= htmlspecialchars($settings['profit_control_percent'] ?? '0') ?>%</div>
                </div>
                
                <div id="profitExplanation" style="margin-top: 12px; padding: 12px; border-radius: 8px; background: rgba(0,212,170,0.1); border: 1px solid rgba(0,212,170,0.3);">
                    <div style="font-weight: 600; margin-bottom: 8px; color: #00D4AA;">How it works:</div>
                    <div id="exampleCalc" style="font-size: 13px; color: var(--text-secondary);"></div>
                </div>
                
                <div style="margin-top: 16px; padding: 12px; border-radius: 8px; background: rgba(26,58,92,0.5); border: 1px solid rgba(255,255,255,0.1);">
                    <div style="font-weight: 600; margin-bottom: 8px; color: var(--text-primary);">Quick Guide:</div>
                    <ul style="font-size: 13px; color: var(--text-secondary); margin: 0; padding-left: 20px;">
                        <li><strong style="color: #00D4AA;">Positive % (1-100):</strong> Users profit MORE on wins, lose LESS on losses</li>
                        <li><strong style="color: #f59e0b;">0%:</strong> No adjustment - natural market results</li>
                        <li><strong style="color: #ef4444;">Negative % (-10 to 0):</strong> Users profit LESS on wins, lose MORE on losses</li>
                    </ul>
                </div>
            </div>
            
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('profitSlider');
                const display = document.getElementById('sliderValue');
                const exampleDiv = document.getElementById('exampleCalc');
                const explanationBox = document.getElementById('profitExplanation');
                
                function updateDisplay() {
                    const value = parseFloat(slider.value);
                    display.textContent = value.toFixed(2) + '%';
                    
                    // Update background gradient based on position
                    const percentage = (value + 10) / 110 * 100;
                    slider.style.background = `linear-gradient(to right, #00D4AA 0%, #00D4AA ${percentage}%, #1a3a5c ${percentage}%, #1a3a5c 100%)`;
                    
                    // Calculate examples with asymmetric formula
                    const winMultiplier = 1 + (value / 100);
                    const lossMultiplier = 1 - (value / 100);
                    const profit100 = (100 * winMultiplier).toFixed(2);
                    const loss100Raw = -100 * lossMultiplier;
                    const loss100Display = Math.abs(loss100Raw).toFixed(2);
                    
                    // Update explanation
                    let color = '#00D4AA';
                    let outcome = 'Users will PROFIT MORE';
                    if (value < 0) {
                        color = '#ef4444';
                        outcome = 'Users will PROFIT LESS';
                    } else if (value === 0) {
                        color = '#f59e0b';
                        outcome = 'Natural market results';
                    }
                    
                    explanationBox.style.borderColor = color;
                    explanationBox.style.background = `${color}15`;
                    
                    exampleDiv.innerHTML = `
                        <div style="margin-bottom: 6px;"><strong>Win Multiplier:</strong> × ${winMultiplier.toFixed(4)} | <strong>Loss Multiplier:</strong> × ${lossMultiplier.toFixed(4)}</div>
                        <div style="margin-bottom: 4px;">If user wins $100 → gets <strong style="color: ${parseFloat(profit100) >= 100 ? '#00D4AA' : '#ef4444'};">$${profit100}</strong></div>
                        <div>If user loses $100 → loses <strong style="color: ${parseFloat(loss100Display) <= 100 ? '#00D4AA' : '#ef4444'};">$${loss100Display}</strong></div>
                    `;
                }
                
                slider.addEventListener('input', updateDisplay);
                updateDisplay();
            });
            </script>
            
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
$pageTitle = 'Platform Settings';
include __DIR__ . '/../layouts/admin.php';
?>
