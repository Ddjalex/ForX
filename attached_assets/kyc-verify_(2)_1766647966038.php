<?php
ob_start();
?>

<div class="kyc-wrapper">
    <div class="kyc-container">
        <!-- Enhanced Header -->
        <div class="kyc-header-enhanced">
            <div class="header-badge">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <h1>Identity Verification (KYC)</h1>
            <p class="subtitle">Secure your account and unlock full trading potential</p>
            
            <!-- Progress Steps -->
            <div class="progress-steps">
                <div class="step <?= ($kyc_status === 'approved' || $kyc_status === 'pending' || $kyc_status === 'rejected') ? 'active' : '' ?>" data-step="1">
                    <span>1</span>
                    <p>Personal Info</p>
                </div>
                <div class="step-line"></div>
                <div class="step <?= ($kyc_status === 'approved' || $kyc_status === 'pending') ? 'active' : '' ?>" data-step="2">
                    <span>2</span>
                    <p>Documents</p>
                </div>
                <div class="step-line"></div>
                <div class="step <?= ($kyc_status === 'approved') ? 'active' : '' ?>" data-step="3">
                    <span>3</span>
                    <p>Submit</p>
                </div>
            </div>
        </div>

        <!-- Status Alerts -->
        <?php if ($kyc_status === 'rejected'): ?>
            <div class="alert-card alert-danger-enhanced">
                <div class="alert-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"></path>
                    </svg>
                </div>
                <div class="alert-content">
                    <h3>Verification Rejected</h3>
                    <p><?= htmlspecialchars($kyc_rejection_reason) ?></p>
                    <small>Please review the requirements below and resubmit with correct documents.</small>
                </div>
            </div>
        <?php elseif ($kyc_status === 'pending'): ?>
            <div class="alert-card alert-warning-enhanced">
                <div class="alert-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="alert-content">
                    <h3>Under Review</h3>
                    <p>Your verification documents are being reviewed. We typically complete this within 24 hours.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <?php if ($kyc_status === 'approved'): ?>
            <div class="verification-complete-container" style="text-align: center; padding: 60px 20px; background: var(--bg-secondary); border-radius: 16px; border: 1px solid var(--accent-primary); margin-top: 30px;">
                <div class="verified-icon" style="font-size: 64px; color: var(--accent-primary); margin-bottom: 20px;">✔️</div>
                <h2 style="color: var(--text-primary); font-size: 28px; margin-bottom: 10px;">KYC VERIFIED ✔️</h2>
                <p style="color: var(--text-secondary); font-size: 16px;">Congratulations! Your identity has been successfully verified. You now have full access to all platform features.</p>
            </div>
        <?php else: ?>
            <div class="kyc-content">
                <div class="kyc-form-card">
                    <h2 class="section-title">
                        <span class="title-icon">📋</span>
                        Provide Your Information
                    </h2>
                    
                    <form id="kycForm" enctype="multipart/form-data">
                        <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">

                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <div class="section-label">
                                <h3>Step 1: Personal Details</h3>
                                <p class="section-description">Enter your information as it appears on your identification document</p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Full Name <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <input type="text" name="full_name" class="form-control" required placeholder="Enter your full name as on ID">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">ID Type <span class="required">*</span></label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                            <path d="M16 5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2"></path>
                                        </svg>
                                        <select name="id_type" class="form-control" required>
                                            <option value="">Select your ID type</option>
                                            <option value="national_id">National ID Card</option>
                                            <option value="passport">Passport</option>
                                            <option value="drivers_license">Driver's License</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">ID Number <span class="required">*</span></label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <input type="text" name="id_number" class="form-control" required placeholder="Enter your ID number">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Upload Section -->
                        <div class="form-section">
                            <div class="section-label">
                                <h3>Step 2: Upload Documents</h3>
                                <p class="section-description">Upload clear images of your identification and a recent selfie</p>
                            </div>

                            <div class="documents-grid">
                                <!-- Front ID Upload -->
                                <div class="document-upload-card">
                                    <div class="upload-status-badge" id="frontIdStatus">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="1"></circle>
                                        </svg>
                                    </div>
                                    <label class="doc-label">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                            <polyline points="13 2 13 9 20 9"></polyline>
                                        </svg>
                                        <span class="label-text">ID Front</span>
                                        <small>Clear photo of front side</small>
                                    </label>
                                    <div class="upload-box" id="frontIdUpload">
                                        <div class="upload-content">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="15"></line>
                                            </svg>
                                            <p>Click to upload or drag</p>
                                            <small>PNG, JPG (Max 5MB)</small>
                                        </div>
                                        <input type="file" name="front_id" accept="image/*" required style="display: none;">
                                    </div>
                                </div>

                                <!-- Back ID Upload -->
                                <div class="document-upload-card">
                                    <div class="upload-status-badge" id="backIdStatus">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="1"></circle>
                                        </svg>
                                    </div>
                                    <label class="doc-label">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                            <polyline points="13 2 13 9 20 9"></polyline>
                                        </svg>
                                        <span class="label-text">ID Back</span>
                                        <small>Clear photo of back side</small>
                                    </label>
                                    <div class="upload-box" id="backIdUpload">
                                        <div class="upload-content">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="15"></line>
                                            </svg>
                                            <p>Click to upload or drag</p>
                                            <small>PNG, JPG (Max 5MB)</small>
                                        </div>
                                        <input type="file" name="back_id" accept="image/*" required style="display: none;">
                                    </div>
                                </div>

                                <!-- Face Photo Upload -->
                                <div class="document-upload-card">
                                    <div class="upload-status-badge" id="facePhotoStatus">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="1"></circle>
                                        </svg>
                                    </div>
                                    <label class="doc-label">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <span class="label-text">Selfie</span>
                                        <small>Your face in current photo</small>
                                    </label>
                                    <div class="upload-box" id="facePhotoUpload">
                                        <div class="upload-content">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="15"></line>
                                            </svg>
                                            <p>Click to upload or drag</p>
                                            <small>PNG, JPG (Max 5MB)</small>
                                        </div>
                                        <input type="file" name="face_photo" accept="image/*" required style="display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Requirements Section -->
                        <div class="requirements-section">
                            <h3>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                                Document Requirements
                            </h3>
                            <div class="requirements-grid">
                                <div class="requirement-item">
                                    <div class="req-icon">✓</div>
                                    <div class="req-text">
                                        <strong>Clear Quality</strong>
                                        <p>Documents must be clear, readable, and in full view</p>
                                    </div>
                                </div>
                                <div class="requirement-item">
                                    <div class="req-icon">✓</div>
                                    <div class="req-text">
                                        <strong>Proper Lighting</strong>
                                        <p>Ensure good lighting with no glare or shadows</p>
                                    </div>
                                </div>
                                <div class="requirement-item">
                                    <div class="req-icon">✓</div>
                                    <div class="req-text">
                                        <strong>Face Visibility</strong>
                                        <p>Selfie must show your entire face clearly</p>
                                    </div>
                                </div>
                                <div class="requirement-item">
                                    <div class="req-icon">✓</div>
                                    <div class="req-text">
                                        <strong>Name Match</strong>
                                        <p>Information must match your registered name</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg btn-submit">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
                                </svg>
                                Submit Verification
                            </button>
                            <p class="form-note">Once submitted, our team will review your documents within 24 hours.</p>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
* {
    box-sizing: border-box;
}

:root {
    --bg-primary: #0d1b2a;
    --bg-secondary: #1e3a5f;
    --bg-tertiary: #2a4a7f;
    --text-primary: #ffffff;
    --text-secondary: #a0aec0;
    --accent-primary: #00d4aa;
    --accent-light: #26f0d9;
    --border-color: #2d5a8c;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
}

.kyc-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #0d1b2a 0%, #1a2f4a 100%);
    padding: 20px;
}

.kyc-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Enhanced Header */
.kyc-header-enhanced {
    text-align: center;
    margin-bottom: 50px;
    animation: slideDown 0.6s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.header-badge {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-light));
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--bg-primary);
    box-shadow: 0 10px 30px rgba(0, 212, 170, 0.3);
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.kyc-header-enhanced h1 {
    font-size: 36px;
    margin: 0 0 10px;
    color: var(--text-primary);
    font-weight: 700;
}

.subtitle {
    font-size: 16px;
    color: var(--text-secondary);
    margin-bottom: 30px;
}

/* Progress Steps */
.progress-steps {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0;
    margin-top: 40px;
}

.step {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    color: var(--text-secondary);
    font-weight: 600;
    transition: all 0.3s;
    position: relative;
    z-index: 2;
}

.step.active {
    background: var(--accent-primary);
    border-color: var(--accent-primary);
    color: var(--bg-primary);
    box-shadow: 0 0 20px rgba(0, 212, 170, 0.4);
}

.step span {
    font-size: 20px;
}

.step p {
    position: absolute;
    top: 70px;
    white-space: nowrap;
    font-size: 14px;
    color: var(--text-secondary);
}

.step.active p {
    color: var(--accent-primary);
    font-weight: 700;
}

.step-line {
    width: 100px;
    height: 2px;
    background: var(--border-color);
    margin: 0 -5px;
    position: relative;
    z-index: 1;
}

/* Alert Cards */
.alert-card {
    display: flex;
    align-items: center;
    padding: 20px 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    animation: fadeIn 0.5s ease-out;
}

.alert-success-enhanced {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid var(--success);
    color: var(--success);
}

.alert-danger-enhanced {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid var(--danger);
    color: var(--danger);
}

.alert-warning-enhanced {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid var(--warning);
    color: var(--warning);
}

.alert-icon {
    margin-right: 20px;
}

.alert-content h3 {
    margin: 0 0 5px;
    font-size: 18px;
    font-weight: 600;
}

.alert-content p {
    margin: 0;
    opacity: 0.9;
}

/* KYC Form Card */
.kyc-form-card {
    background: var(--bg-secondary);
    border-radius: 20px;
    padding: 40px;
    border: 1px solid var(--border-color);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
}

.section-title {
    font-size: 24px;
    color: var(--text-primary);
    margin-bottom: 40px;
    display: flex;
    align-items: center;
}

.title-icon {
    margin-right: 15px;
    font-size: 28px;
}

.form-section {
    margin-bottom: 50px;
}

.section-label {
    margin-bottom: 25px;
    border-left: 4px solid var(--accent-primary);
    padding-left: 15px;
}

.section-label h3 {
    font-size: 20px;
    margin: 0 0 5px;
    color: var(--text-primary);
}

.section-description {
    color: var(--text-secondary);
    font-size: 14px;
    margin: 0;
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    display: block;
    margin-bottom: 10px;
    color: var(--text-primary);
    font-size: 14px;
    font-weight: 500;
}

.required {
    color: var(--danger);
}

.input-wrapper {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--accent-primary);
}

.form-control {
    width: 100%;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 15px 15px 15px 45px;
    color: var(--text-primary);
    font-size: 15px;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: var(--accent-primary);
    box-shadow: 0 0 15px rgba(0, 212, 170, 0.1);
    outline: none;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Documents Grid */
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.document-upload-card {
    background: var(--bg-tertiary);
    border-radius: 15px;
    padding: 25px;
    position: relative;
    border: 1px solid var(--border-color);
    transition: all 0.3s;
}

.document-upload-card:hover {
    transform: translateY(-5px);
    border-color: var(--accent-primary);
}

.upload-status-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--bg-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
}

.upload-status-badge.completed {
    background: var(--success);
    color: white;
}

.doc-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
    text-align: center;
}

.doc-label svg {
    color: var(--accent-primary);
    margin-bottom: 10px;
}

.label-text {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
}

.doc-label small {
    color: var(--text-secondary);
    font-size: 12px;
}

.upload-box {
    border: 2px dashed var(--border-color);
    border-radius: 12px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: var(--bg-primary);
}

.upload-box:hover {
    border-color: var(--accent-primary);
    background: rgba(0, 212, 170, 0.05);
}

.upload-box.has-file {
    border-style: solid;
    border-color: var(--success);
}

.upload-content svg {
    color: var(--text-secondary);
    margin-bottom: 10px;
}

.upload-content p {
    margin: 0;
    font-size: 14px;
    color: var(--text-primary);
}

.upload-content small {
    color: var(--text-secondary);
    font-size: 11px;
}

/* Requirements Section */
.requirements-section {
    background: rgba(0, 212, 170, 0.05);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 40px;
    border: 1px solid rgba(0, 212, 170, 0.2);
}

.requirements-section h3 {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--accent-primary);
    font-size: 18px;
    margin: 0 0 25px;
}

.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.requirement-item {
    display: flex;
    gap: 12px;
}

.req-icon {
    width: 22px;
    height: 22px;
    background: var(--success);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    flex-shrink: 0;
}

.req-text strong {
    display: block;
    color: var(--text-primary);
    font-size: 14px;
    margin-bottom: 3px;
}

.req-text p {
    margin: 0;
    font-size: 12px;
    color: var(--text-secondary);
    line-height: 1.4;
}

/* Form Actions */
.form-actions {
    text-align: center;
}

.btn-submit {
    min-width: 250px;
    height: 55px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-weight: 600;
    font-size: 16px;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-light));
    border: none;
    border-radius: 12px;
    color: var(--bg-primary);
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 10px 20px rgba(0, 212, 170, 0.2);
}

.btn-submit:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(0, 212, 170, 0.3);
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.form-note {
    margin-top: 20px;
    color: var(--text-secondary);
    font-size: 13px;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .kyc-form-card {
        padding: 25px;
    }
    
    .progress-steps {
        gap: 0;
    }
    
    .step-line {
        width: 60px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kycForm = document.getElementById('kycForm');
    if (!kycForm) return;

    // File Upload Handling
    const uploadBoxes = ['frontIdUpload', 'backIdUpload', 'facePhotoUpload'];
    
    uploadBoxes.forEach(boxId => {
        const box = document.getElementById(boxId);
        const input = box.querySelector('input[type="file"]');
        const statusBadge = document.getElementById(boxId.replace('Upload', 'Status'));

        box.addEventListener('click', () => input.click());

        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                box.classList.add('has-file');
                box.querySelector('p').textContent = fileName;
                statusBadge.classList.add('completed');
                statusBadge.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>';
            }
        });

        // Drag and Drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            box.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        box.addEventListener('drop', e => {
            const dt = e.dataTransfer;
            const files = dt.files;
            input.files = files;
            const event = new Event('change');
            input.dispatchEvent(event);
        });
    });

    // Form Submission
    kycForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('.btn-submit');
        const originalText = submitBtn.innerHTML;
        const formData = new FormData(this);

        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span> Submitting...';

            const response = await fetch('/kyc/submit', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const text = await response.text();
            const cleanText = text.trim().toLowerCase();
            
            if (cleanText.indexOf('success') !== -1) {
                showNotification('Verification submitted successfully!', 'success');
                setTimeout(() => {
                    window.location.href = '/kyc/verify';
                }, 2000);
            } else {
                showNotification('Submission failed. Please check inputs.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    function showNotification(message, type = 'info') {
        const notif = document.createElement('div');
        notif.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            z-index: 10000;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: slideIn 0.3s ease-out;
            font-weight: 600;
        `;
        notif.textContent = message;
        document.body.appendChild(notif);
        setTimeout(() => notif.remove(), 4000);
    }
});
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/dashboard.php';
?>