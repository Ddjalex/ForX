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
                <div class="step active" data-step="1">
                    <span>1</span>
                    <p>Personal Info</p>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="2">
                    <span>2</span>
                    <p>Documents</p>
                </div>
                <div class="step-line"></div>
                <div class="step" data-step="3">
                    <span>3</span>
                    <p>Submit</p>
                </div>
            </div>
        </div>

        <!-- Status Alerts -->
        <?php if ($kyc_status === 'approved'): ?>
            <div class="alert-card alert-success-enhanced">
                <div class="alert-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
                    </svg>
                </div>
                <div class="alert-content">
                    <h3>Verification Complete</h3>
                    <p>Your account is verified and ready to trade with full access to all features.</p>
                </div>
            </div>
        <?php elseif ($kyc_status === 'rejected'): ?>
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
    font-family: 'Georgia', serif;
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

.step span {
    font-size: 20px;
    display: block;
}

.step p {
    font-size: 11px;
    margin-top: 2px;
}

.step.active {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-light));
    color: var(--bg-primary);
    border-color: var(--accent-primary);
    box-shadow: 0 5px 15px rgba(0, 212, 170, 0.3);
}

.step-line {
    width: 50px;
    height: 2px;
    background: var(--border-color);
    position: relative;
    z-index: 1;
    margin: 0 -5px;
}

/* Alert Cards */
.alert-card {
    display: flex;
    gap: 15px;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    backdrop-filter: blur(10px);
    animation: slideDown 0.6s ease-out 0.1s both;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.alert-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.alert-success-enhanced {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.alert-success-enhanced .alert-icon {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.alert-success-enhanced .alert-content {
    color: #10b981;
}

.alert-danger-enhanced {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.alert-danger-enhanced .alert-icon {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.alert-danger-enhanced .alert-content {
    color: #ef4444;
}

.alert-warning-enhanced {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.alert-warning-enhanced .alert-icon {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.alert-warning-enhanced .alert-content {
    color: #f59e0b;
}

.alert-content h3 {
    margin: 0 0 5px;
    font-size: 16px;
    font-weight: 600;
}

.alert-content p {
    margin: 0 0 3px;
    font-size: 14px;
}

.alert-content small {
    font-size: 12px;
    opacity: 0.8;
}

/* Form Card */
.kyc-form-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.6s ease-out 0.2s both;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.section-title {
    font-size: 20px;
    margin-bottom: 20px;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Georgia', serif;
    font-weight: 600;
}

.title-icon {
    font-size: 24px;
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 40px;
    border-bottom: 1px solid var(--border-color);
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-label {
    margin-bottom: 25px;
}

.section-label h3 {
    font-size: 18px;
    margin: 0 0 8px;
    color: var(--text-primary);
    font-weight: 600;
    font-family: 'Georgia', serif;
}

.section-description {
    margin: 0;
    color: var(--text-secondary);
    font-size: 14px;
}

/* Form Group */
.form-group {
    margin-bottom: 20px;
    position: relative;
    z-index: auto;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    position: relative;
    z-index: auto;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}

.required {
    color: var(--danger);
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    z-index: 10;
}

.input-icon {
    position: absolute;
    left: 12px;
    color: var(--accent-primary);
    pointer-events: none;
}

.form-control {
    width: 100%;
    padding: 12px 12px 12px 40px;
    background: var(--bg-primary);
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 14px;
    transition: all 0.3s;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    background: rgba(0, 212, 170, 0.05);
    box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.1);
}

.form-control::placeholder {
    color: var(--text-secondary);
}

select.form-control {
    padding-right: 12px;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2300d4aa' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 35px;
    position: relative;
    z-index: 20;
}

select.form-control option {
    background: #1e3a5f;
    color: #ffffff;
    padding: 12px 10px;
    line-height: 1.5;
}

select.form-control option:checked {
    background: linear-gradient(#00d4aa, #00d4aa);
    background-color: #00d4aa;
    color: #000;
}

select.form-control option:hover {
    background: #2a4a7f;
    color: #ffffff;
}

/* Document Upload */
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin: 25px 0;
}

.document-upload-card {
    position: relative;
}

.upload-status-badge {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 28px;
    height: 28px;
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    z-index: 2;
    transition: all 0.3s;
}

.document-upload-card.uploaded .upload-status-badge {
    background: #10b981;
    border-color: #10b981;
    color: white;
}

.doc-label {
    display: block;
    margin-bottom: 12px;
    color: var(--text-primary);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: color 0.2s;
}

.doc-label:hover {
    color: var(--accent-primary);
}

.doc-label svg {
    color: var(--accent-primary);
    flex-shrink: 0;
}

.label-text {
    font-size: 15px;
    font-weight: 600;
}

.doc-label small {
    display: block;
    font-size: 12px;
    color: var(--text-secondary);
    font-weight: normal;
    margin-top: 2px;
}

.upload-box {
    border: 2px dashed var(--border-color);
    border-radius: 10px;
    padding: 30px 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: rgba(0, 212, 170, 0.02);
    position: relative;
    overflow: hidden;
}

.upload-box:hover {
    border-color: var(--accent-primary);
    background: rgba(0, 212, 170, 0.05);
    box-shadow: 0 5px 15px rgba(0, 212, 170, 0.1);
}

.upload-box.drag-over {
    border-color: var(--accent-primary);
    background: rgba(0, 212, 170, 0.1);
    transform: scale(1.02);
}

.upload-content {
    position: relative;
    z-index: 1;
}

.upload-box svg {
    color: var(--accent-primary);
    margin-bottom: 12px;
    transition: transform 0.3s;
}

.upload-box:hover svg {
    transform: scale(1.1);
}

.upload-box p {
    margin: 10px 0 5px;
    color: var(--text-primary);
    font-weight: 500;
    font-size: 14px;
}

.upload-box small {
    color: var(--text-secondary);
    font-size: 12px;
}

/* Requirements Section */
.requirements-section {
    background: rgba(0, 212, 170, 0.08);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 25px;
    margin: 30px 0;
}

.requirements-section h3 {
    margin: 0 0 20px;
    color: var(--accent-primary);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-family: 'Georgia', serif;
}

.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.requirement-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.req-icon {
    width: 28px;
    height: 28px;
    background: var(--accent-primary);
    color: var(--bg-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    flex-shrink: 0;
}

.req-text {
    flex: 1;
}

.req-text strong {
    display: block;
    color: var(--text-primary);
    font-size: 14px;
    margin-bottom: 3px;
}

.req-text p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 13px;
    line-height: 1.4;
}

/* Form Actions */
.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
    position: relative;
    z-index: 5;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    position: relative;
    z-index: 5;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-light));
    color: var(--bg-primary);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 212, 170, 0.3);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-lg {
    padding: 14px 32px;
    font-size: 16px;
}

.btn-submit {
    display: flex;
    width: 100%;
    justify-content: center;
    font-size: 16px;
    padding: 16px;
    box-sizing: border-box;
}

.form-note {
    text-align: center;
    color: var(--text-secondary);
    font-size: 13px;
    margin-top: 15px;
}

/* Responsive */
@media (max-width: 768px) {
    .kyc-form-card {
        padding: 25px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .documents-grid {
        grid-template-columns: 1fr;
    }

    .progress-steps {
        flex-wrap: wrap;
        gap: 10px;
    }

    .step-line {
        display: none;
    }

    .kyc-header-enhanced h1 {
        font-size: 28px;
    }

    .requirements-grid {
        grid-template-columns: 1fr;
    }
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setupFileUpload('frontIdUpload', 'front_id', 'frontIdStatus');
    setupFileUpload('backIdUpload', 'back_id', 'backIdStatus');
    setupFileUpload('facePhotoUpload', 'face_photo', 'facePhotoStatus');

    document.getElementById('kycForm').addEventListener('submit', submitKYC);
});

function setupFileUpload(uploadBoxId, inputName, statusBadgeId) {
    const uploadBox = document.getElementById(uploadBoxId);
    const input = uploadBox.querySelector(`input[name="${inputName}"]`);
    const statusBadge = document.getElementById(statusBadgeId);
    const card = uploadBox.closest('.document-upload-card');

    uploadBox.addEventListener('click', () => input.click());
    
    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.classList.add('drag-over');
    });
    
    uploadBox.addEventListener('dragleave', () => {
        uploadBox.classList.remove('drag-over');
    });
    
    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadBox.classList.remove('drag-over');
        if (e.dataTransfer.files.length > 0) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(e.dataTransfer.files[0]);
            input.files = dataTransfer.files;
            updateFileStatus(card, uploadBox, input.files[0].name, statusBadge);
        }
    });

    input.addEventListener('change', () => {
        if (input.files.length > 0) {
            updateFileStatus(card, uploadBox, input.files[0].name, statusBadge);
        }
    });
}

function updateFileStatus(card, uploadBox, filename, statusBadge) {
    const content = uploadBox.querySelector('.upload-content');
    content.innerHTML = `
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #10b981; margin-bottom: 8px;">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        <p style="color: #10b981; font-weight: 600;">${filename}</p>
    `;
    card.classList.add('uploaded');
    statusBadge.innerHTML = `
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
        </svg>
    `;
}

async function submitKYC(e) {
    e.preventDefault();

    const form = document.getElementById('kycForm');
    const formData = new FormData(form);
    const submitBtn = form.querySelector('.btn-submit');
    const originalText = submitBtn.innerHTML;

    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="1"></circle>
            </svg>
            Submitting...
        `;

        const response = await fetch('/kyc/submit', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        let data;
        const text = await response.text();
        
        // Clean up text in case there's accidental whitespace or PHP warnings
        const cleanText = text.trim();
        
        try {
            data = JSON.parse(cleanText);
        } catch (e) {
            console.error('Server response:', text);
            // Handle cases where PHP might output extra whitespace/warnings
            if (cleanText.indexOf('"success":true') !== -1) {
                data = { success: true };
            } else {
                throw new Error('Invalid server response');
            }
        }

        if (data.success) {
            showNotification('Verification submitted successfully! Admin will review within 24 hours.', 'success');
            setTimeout(() => {
                window.location.href = '/kyc/verify';
            }, 2000);
        } else {
            showNotification(data.error || 'Submission failed', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

function showNotification(message, type = 'info') {
    const existingNotif = document.getElementById('systemNotification');
    if (existingNotif) existingNotif.remove();

    const notif = document.createElement('div');
    notif.id = 'systemNotification';
    notif.className = `notification ${type}`;
    
    const bgColor = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
    
    notif.innerHTML = `
        <div class="notification-content" style="
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        ">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                font-size: 20px;
                padding: 0;
            ">&times;</button>
        </div>
    `;
    
    notif.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${bgColor};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 10000;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        animation: slideInRight 0.3s ease-out;
    `;
    
    document.body.appendChild(notif);
    setTimeout(() => {
        notif.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => notif.remove(), 300);
    }, 5000);
}

const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
`;
document.head.appendChild(style);
</script>
