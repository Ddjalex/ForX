<?php
ob_start();
?>

<div class="kyc-container">
    <div class="kyc-header">
        <h1>Identity Verification (KYC)</h1>
        <p>Complete verification to unlock full trading features</p>
    </div>

    <?php if ($kyc_status === 'approved'): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;">
            ✓ Your account is verified. You have full access to all features.
        </div>
    <?php elseif ($kyc_status === 'rejected'): ?>
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            ✗ Your verification was rejected: <?= htmlspecialchars($kyc_rejection_reason) ?>
            <br><small>Please resubmit with correct documents</small>
        </div>
    <?php elseif ($kyc_status === 'pending'): ?>
        <div class="alert alert-warning" style="margin-bottom: 20px;">
            ⏳ Your verification is pending. Admin will review within 24 hours.
        </div>
    <?php endif; ?>

    <div class="kyc-content">
        <div class="kyc-form-section">
            <h2>Upload Your Documents</h2>
            <form id="kycForm" enctype="multipart/form-data">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">

                <div class="form-group">
                    <label>Full Name (as on ID)</label>
                    <input type="text" name="full_name" class="form-control" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label>ID Type</label>
                    <select name="id_type" class="form-control" required>
                        <option value="">Select ID type</option>
                        <option value="national_id">National ID</option>
                        <option value="passport">Passport</option>
                        <option value="drivers_license">Driver's License</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>ID Number</label>
                    <input type="text" name="id_number" class="form-control" required placeholder="Enter ID number">
                </div>

                <div class="documents-grid">
                    <div class="document-upload">
                        <label>ID Front Side</label>
                        <div class="upload-box" id="frontIdUpload">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <p>Click to upload or drag</p>
                            <small>PNG, JPG (Max 5MB)</small>
                            <input type="file" name="front_id" accept="image/*" required style="display: none;">
                        </div>
                    </div>

                    <div class="document-upload">
                        <label>ID Back Side</label>
                        <div class="upload-box" id="backIdUpload">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <p>Click to upload or drag</p>
                            <small>PNG, JPG (Max 5MB)</small>
                            <input type="file" name="back_id" accept="image/*" required style="display: none;">
                        </div>
                    </div>

                    <div class="document-upload">
                        <label>Face Photo (Selfie)</label>
                        <div class="upload-box" id="facePhotoUpload">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <p>Click to upload or drag</p>
                            <small>PNG, JPG (Max 5MB)</small>
                            <input type="file" name="face_photo" accept="image/*" required style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="kyc-info">
                    <h3>Requirements:</h3>
                    <ul>
                        <li>All documents must be clear and readable</li>
                        <li>Face photo must show your entire face clearly</li>
                        <li>Ensure proper lighting and no shadows</li>
                        <li>Documents must match your registered name</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 20px;">
                    Submit Verification
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.kyc-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}

.kyc-header {
    text-align: center;
    margin-bottom: 40px;
}

.kyc-header h1 {
    font-size: 32px;
    margin-bottom: 10px;
    color: var(--text-primary);
}

.kyc-header p {
    color: var(--text-secondary);
    font-size: 16px;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    font-weight: 500;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.alert-danger {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.alert-warning {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.kyc-form-section {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 30px;
}

.kyc-form-section h2 {
    margin-bottom: 25px;
    color: var(--text-primary);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-primary);
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    color: var(--text-primary);
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.1);
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.document-upload label {
    display: block;
    margin-bottom: 12px;
    font-weight: 600;
    color: var(--text-primary);
}

.upload-box {
    border: 2px dashed var(--border-color);
    border-radius: 8px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: rgba(0, 212, 170, 0.02);
}

.upload-box:hover {
    border-color: var(--accent-primary);
    background: rgba(0, 212, 170, 0.05);
}

.upload-box svg {
    color: var(--accent-primary);
    margin-bottom: 10px;
}

.upload-box p {
    margin: 10px 0 5px;
    color: var(--text-primary);
    font-weight: 500;
}

.upload-box small {
    color: var(--text-secondary);
    font-size: 12px;
}

.kyc-info {
    background: rgba(0, 212, 170, 0.05);
    border: 1px solid rgba(0, 212, 170, 0.2);
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}

.kyc-info h3 {
    margin-bottom: 15px;
    color: var(--accent-primary);
}

.kyc-info ul {
    list-style: none;
    padding: 0;
}

.kyc-info li {
    padding: 8px 0;
    padding-left: 25px;
    position: relative;
    color: var(--text-secondary);
}

.kyc-info li:before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--accent-primary);
    font-weight: bold;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.btn-primary {
    background: var(--accent-primary);
    color: #000;
}

.btn-primary:hover {
    background: #00e6b8;
    transform: translateY(-2px);
}

.btn-lg {
    padding: 14px 32px;
    font-size: 16px;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setupFileUpload('frontIdUpload', 'front_id');
    setupFileUpload('backIdUpload', 'back_id');
    setupFileUpload('facePhotoUpload', 'face_photo');

    document.getElementById('kycForm').addEventListener('submit', submitKYC);
});

function setupFileUpload(uploadBoxId, inputName) {
    const uploadBox = document.getElementById(uploadBoxId);
    const input = uploadBox.querySelector(`input[name="${inputName}"]`);

    uploadBox.addEventListener('click', () => input.click());
    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = 'var(--accent-primary)';
    });
    uploadBox.addEventListener('dragleave', () => {
        uploadBox.style.borderColor = 'var(--border-color)';
    });
    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = 'var(--border-color)';
        if (e.dataTransfer.files.length > 0) {
            input.files = e.dataTransfer.files;
            updateFileName(uploadBox, e.dataTransfer.files[0].name);
        }
    });

    input.addEventListener('change', () => {
        if (input.files.length > 0) {
            updateFileName(uploadBox, input.files[0].name);
        }
    });
}

function updateFileName(uploadBox, filename) {
    const p = uploadBox.querySelector('p');
    p.textContent = filename;
}

async function submitKYC(e) {
    e.preventDefault();

    const form = document.getElementById('kycForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('/kyc/submit', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showNotification('Verification submitted successfully! Admin will review within 24 hours.', 'success');
            setTimeout(() => window.location.reload(), 2000);
        } else {
            showNotification(data.error || 'Submission failed', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    }
}

function showNotification(message, type = 'info') {
    const notif = document.getElementById('systemNotification') || document.createElement('div');
    notif.id = 'systemNotification';
    notif.className = `notification ${type}`;
    notif.innerHTML = `
        <div class="notification-content">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;
    notif.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#dc3545' : '#3b82f6'};
        color: white;
        padding: 15px 20px;
        border-radius: 6px;
        z-index: 10000;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    `;
    document.body.appendChild(notif);
    setTimeout(() => notif.remove(), 5000);
}
</script>
