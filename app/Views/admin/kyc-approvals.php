<?php
ob_start();
?>

<div class="admin-kyc-wrapper">
    <div class="admin-kyc-container">
        <!-- Enhanced Header -->
        <div class="admin-header-section">
            <div class="header-content">
                <div class="header-title">
                    <h1>KYC Verification Management</h1>
                    <p>Review and approve user identity verification documents</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Pending Review</p>
                        <h3 class="stat-number"><?= $pending_count ?></h3>
                    </div>
                </div>

                <div class="stat-card approved">
                    <div class="stat-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Approved</p>
                        <h3 class="stat-number"><?= $approved_count ?></h3>
                    </div>
                </div>

                <div class="stat-card rejected">
                    <div class="stat-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <p class="stat-label">Rejected</p>
                        <h3 class="stat-number"><?= $rejected_count ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Tabs -->
        <div class="kyc-tabs-section">
            <div class="tabs-wrapper">
                <button class="tab-btn active" onclick="filterKYC('pending')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Pending</span>
                    <span class="tab-badge"><?= $pending_count ?></span>
                </button>
                <button class="tab-btn" onclick="filterKYC('approved')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Approved</span>
                    <span class="tab-badge"><?= $approved_count ?></span>
                </button>
                <button class="tab-btn" onclick="filterKYC('rejected')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <span>Rejected</span>
                    <span class="tab-badge"><?= $rejected_count ?></span>
                </button>
            </div>
        </div>

        <!-- KYC List -->
        <div class="kyc-list">
            <?php if (empty($kyc_verifications)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <p class="empty-title">No KYC Verifications</p>
                    <p class="empty-desc">No users to review at the moment</p>
                </div>
            <?php else: ?>
                <?php foreach ($kyc_verifications as $kyc): ?>
                    <div class="kyc-card" data-status="<?= $kyc['status'] ?>">
                        <!-- Card Header -->
                        <div class="kyc-card-header">
                            <div class="user-info">
                                <div class="user-avatar">
                                    <?= substr($kyc['full_name'], 0, 1) ?>
                                </div>
                                <div class="user-details">
                                    <h3><?= htmlspecialchars($kyc['full_name']) ?></h3>
                                    <p class="user-email"><?= htmlspecialchars($kyc['user_email']) ?></p>
                                    <p class="id-info">
                                        <span class="id-type"><?= strtoupper(str_replace('_', ' ', $kyc['id_type'])) ?></span>
                                        <span class="id-separator">•</span>
                                        <span class="id-number"><?= htmlspecialchars($kyc['id_number']) ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="card-meta">
                                <div class="status-badge" data-status="<?= $kyc['status'] ?>">
                                    <?php if ($kyc['status'] === 'pending'): ?>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="12" r="1"></circle>
                                        </svg>
                                        <span>Pending Review</span>
                                    <?php elseif ($kyc['status'] === 'approved'): ?>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
                                        </svg>
                                        <span>Approved</span>
                                    <?php else: ?>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg>
                                        <span>Rejected</span>
                                    <?php endif; ?>
                                </div>
                                <div class="submission-date">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?= date('M d, Y', strtotime($kyc['created_at'])) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="kyc-documents-section">
                            <h4 class="section-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <polyline points="13 2 13 9 20 9"></polyline>
                                </svg>
                                Submitted Documents
                            </h4>
                            <div class="doc-grid">
                                <?php foreach ($kyc['documents'] as $doc): ?>
                                    <div class="doc-item">
                                        <div class="doc-preview">
                                            <?php
                                            $imagePath = strpos($doc['file_path'], 'http') === 0 ? $doc['file_path'] : $doc['file_path'];
                                            ?>
                                            <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= $doc['document_type'] ?>" onclick="showDocumentModal('<?= htmlspecialchars($imagePath) ?>', '<?= ucwords(str_replace('_', ' ', $doc['document_type'])) ?>')" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23333%22 width=%22400%22 height=%22300%22/%3E%3Ctext x=%22200%22 y=%22150%22 fill=%22white%22 text-anchor=%22middle%22 font-family=%22Arial%22%3EImage Not Found%3C/text%3E%3C/svg%3E'">
                                            <div class="doc-overlay">
                                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="doc-name"><?= ucwords(str_replace('_', ' ', $doc['document_type'])) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Action Section -->
                        <?php if ($kyc['status'] === 'pending'): ?>
                            <div class="kyc-actions-section">
                                <button class="btn btn-approve" onclick="approveKYC(<?= $kyc['id'] ?>)">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Approve
                                </button>
                                <button class="btn btn-reject" onclick="showRejectModal(<?= $kyc['id'] ?>)">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                    </svg>
                                    Reject
                                </button>
                            </div>
                        <?php elseif ($kyc['status'] === 'rejected'): ?>
                            <div class="rejection-reason-section">
                                <h4 class="section-title">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    Rejection Reason
                                </h4>
                                <p><?= htmlspecialchars($kyc['rejection_reason']) ?></p>
                            </div>
                        <?php elseif ($kyc['status'] === 'approved'): ?>
                            <div class="approval-info-section">
                                <h4 class="section-title">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Approved
                                </h4>
                                <p class="approval-date">Approved on <?= date('M d, Y \a\t H:i', strtotime($kyc['approved_at'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div id="documentModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeDocumentModal()"></div>
    <div class="modal-content">
        <button class="modal-close" onclick="closeDocumentModal()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <div class="modal-body">
            <p class="doc-title" id="docTitle"></p>
            <img id="modalImage" src="" alt="Document" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23333%22 width=%22400%22 height=%22300%22/%3E%3Ctext x=%22200%22 y=%22150%22 fill=%22white%22 text-anchor=%22middle%22 font-family=%22Arial%22%3EImage Not Found%3C/text%3E%3C/svg%3E'">
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeRejectModal()"></div>
    <div class="modal-content modal-form">
        <button class="modal-close" onclick="closeRejectModal()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <h2>Reject KYC Verification</h2>
        <p class="modal-description">Please provide a clear reason for rejecting this verification</p>
        <form id="rejectForm">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" id="rejectKycId" name="kyc_id">
            
            <div class="form-group">
                <label>Rejection Reason <span class="required">*</span></label>
                <textarea name="rejection_reason" class="form-control" rows="5" required placeholder="Explain why the documents were rejected. Be specific to help the user resubmit correctly."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-reject">Reject</button>
            </div>
        </form>
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

.admin-kyc-wrapper {
    background: linear-gradient(135deg, #0d1b2a 0%, #1a2f4a 100%);
    min-height: 100vh;
    padding: 20px;
}

.admin-kyc-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Header Section */
.admin-header-section {
    margin-bottom: 40px;
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

.header-content {
    margin-bottom: 25px;
}

.header-title h1 {
    font-size: 32px;
    margin: 0 0 8px;
    color: var(--text-primary);
    font-weight: 700;
    font-family: 'Georgia', serif;
}

.header-title p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 15px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
}

.stat-card {
    background: var(--bg-secondary);
    border: 1.5px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: var(--accent-primary);
    box-shadow: 0 10px 30px rgba(0, 212, 170, 0.1);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-card.pending .stat-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.stat-card.approved .stat-icon {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stat-card.rejected .stat-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.stat-content {
    flex: 1;
}

.stat-label {
    margin: 0;
    color: var(--text-secondary);
    font-size: 13px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    margin: 5px 0 0;
    font-size: 28px;
    font-weight: 700;
    color: var(--accent-primary);
}

/* Tabs Section */
.kyc-tabs-section {
    margin-bottom: 30px;
}

.tabs-wrapper {
    display: flex;
    gap: 10px;
    border-bottom: 2px solid var(--border-color);
    overflow-x: auto;
    padding-bottom: 0;
}

.tab-btn {
    padding: 14px 20px;
    background: none;
    border: none;
    color: var(--text-secondary);
    font-weight: 600;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    position: relative;
    margin-bottom: -2px;
}

.tab-btn svg {
    width: 18px;
    height: 18px;
}

.tab-badge {
    background: rgba(0, 212, 170, 0.15);
    color: var(--accent-primary);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
}

.tab-btn.active {
    color: var(--accent-primary);
    border-bottom-color: var(--accent-primary);
}

.tab-btn.active .tab-badge {
    background: var(--accent-primary);
    color: var(--bg-primary);
}

.tab-btn:hover {
    color: var(--accent-primary);
}

/* KYC List */
.kyc-list {
    display: grid;
    gap: 20px;
}

.kyc-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    overflow: hidden;
    animation: slideUp 0.6s ease-out;
    transition: all 0.3s;
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

.kyc-card:hover {
    border-color: rgba(0, 212, 170, 0.3);
    box-shadow: 0 15px 40px rgba(0, 212, 170, 0.08);
}

/* Card Header */
.kyc-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
}

.user-info {
    display: flex;
    gap: 16px;
    flex: 1;
}

.user-avatar {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-light));
    color: var(--bg-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 20px;
    flex-shrink: 0;
}

.user-details h3 {
    margin: 0 0 5px;
    color: var(--text-primary);
    font-size: 16px;
    font-weight: 600;
}

.user-email {
    margin: 0 0 4px;
    color: var(--accent-primary);
    font-size: 13px;
}

.id-info {
    margin: 0;
    color: var(--text-secondary);
    font-size: 12px;
    display: flex;
    gap: 6px;
    align-items: center;
}

.id-separator {
    opacity: 0.5;
}

.card-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
}

.status-badge[data-status="pending"] {
    background: rgba(245, 158, 11, 0.15);
    color: #f59e0b;
}

.status-badge[data-status="approved"] {
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
}

.status-badge[data-status="rejected"] {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

.submission-date {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-secondary);
    font-size: 12px;
}

/* Documents Section */
.kyc-documents-section {
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
}

.section-title {
    margin: 0 0 16px;
    color: var(--text-primary);
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-family: 'Georgia', serif;
}

.section-title svg {
    color: var(--accent-primary);
}

.doc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 15px;
}

.doc-item {
    text-align: center;
}

.doc-preview {
    position: relative;
    margin-bottom: 10px;
    border-radius: 10px;
    overflow: hidden;
    background: var(--bg-primary);
    aspect-ratio: 1;
    cursor: pointer;
    transition: all 0.3s;
}

.doc-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.doc-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.doc-overlay svg {
    color: white;
}

.doc-preview:hover img {
    transform: scale(1.1);
}

.doc-preview:hover .doc-overlay {
    opacity: 1;
}

.doc-name {
    margin: 0;
    color: var(--text-secondary);
    font-size: 12px;
    font-weight: 500;
}

/* Actions Section */
.kyc-actions-section {
    padding: 20px 24px;
    display: flex;
    gap: 12px;
    border-top: 1px solid var(--border-color);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
}

.btn-approve {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    flex: 1;
}

.btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
}

.btn-reject {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    flex: 1;
}

.btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
}

.btn-cancel {
    background: var(--border-color);
    color: var(--text-primary);
}

.btn-cancel:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Rejection & Approval Info */
.rejection-reason-section,
.approval-info-section {
    padding: 20px 24px;
    border-top: 1px solid var(--border-color);
}

.rejection-reason-section {
    background: rgba(239, 68, 68, 0.05);
}

.approval-info-section {
    background: rgba(16, 185, 129, 0.05);
}

.rejection-reason-section p,
.approval-info-section p {
    margin: 0;
    color: var(--text-primary);
    font-size: 14px;
    line-height: 1.6;
}

.approval-date {
    color: #10b981;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    color: var(--text-secondary);
    opacity: 0.3;
}

.empty-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 8px;
}

.empty-desc {
    color: var(--text-secondary);
    margin: 0;
    font-size: 14px;
}

/* Modals */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    cursor: pointer;
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    background: var(--bg-secondary);
    border-radius: 16px;
    padding: 40px;
    max-width: 700px;
    width: 95%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.4s ease-out;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
    border: 1px solid var(--border-color);
}

.modal-form {
    max-width: 500px;
}

.modal-content h2 {
    margin: 0 0 8px;
    color: var(--text-primary);
    font-size: 22px;
    font-family: 'Georgia', serif;
}

.modal-description {
    margin: 0 0 25px;
    color: var(--text-secondary);
    font-size: 14px;
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 8px;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    background: rgba(0, 212, 170, 0.1);
    border-color: var(--accent-primary);
    color: var(--accent-primary);
}

.modal-body {
    text-align: center;
}

.doc-title {
    margin: 0 0 20px;
    color: var(--text-secondary);
    font-size: 14px;
    font-weight: 500;
    text-transform: uppercase;
}

#modalImage {
    width: 100%;
    border-radius: 12px;
    max-height: 70vh;
    object-fit: contain;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}

.required {
    color: var(--danger);
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    background: var(--bg-primary);
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-primary);
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    background: rgba(0, 212, 170, 0.02);
    box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.1);
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}

/* Responsive */
@media (max-width: 768px) {
    .kyc-card-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .card-meta {
        align-items: flex-start;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .doc-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .kyc-actions-section {
        flex-direction: column;
    }

    .modal-content {
        padding: 30px 20px;
    }
}
</style>

<script>
function filterKYC(status) {
    const cards = document.querySelectorAll('.kyc-card');
    const buttons = document.querySelectorAll('.tab-btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.closest('.tab-btn').classList.add('active');

    cards.forEach(card => {
        if (card.dataset.status === status) {
            card.style.display = '';
            setTimeout(() => card.style.opacity = '1', 10);
        } else {
            card.style.display = 'none';
        }
    });
}

function showDocumentModal(imagePath, docTitle = 'Document') {
    const modal = document.getElementById('documentModal');
    const img = document.getElementById('modalImage');
    const title = document.getElementById('docTitle');
    img.src = imagePath;
    title.textContent = docTitle;
    modal.style.display = 'flex';
}

function closeDocumentModal() {
    document.getElementById('documentModal').style.display = 'none';
}

function showRejectModal(kycId) {
    document.getElementById('rejectKycId').value = kycId;
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

async function approveKYC(kycId) {
    if (!confirm('Are you sure you want to approve this KYC?')) return;

    try {
        const response = await fetch(`/admin/kyc/approve/${kycId}`, {
            method: 'POST'
        });

        const data = await response.json();

        if (data.success) {
            showNotification('KYC approved successfully', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.error || 'Failed to approve KYC', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    }
}

document.getElementById('rejectForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const kycId = document.getElementById('rejectKycId').value;
    const formData = new FormData(e.target);
    const submitBtn = e.target.querySelector('[type="submit"]');
    const originalText = submitBtn.innerHTML;

    try {
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Rejecting...';

        const response = await fetch(`/admin/kyc/reject/${kycId}`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showNotification('KYC rejected successfully', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.error || 'Failed to reject KYC', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

function showNotification(message, type = 'info') {
    const existingNotif = document.getElementById('adminNotification');
    if (existingNotif) existingNotif.remove();

    const notif = document.createElement('div');
    notif.id = 'adminNotification';
    
    const bgColor = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
    
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
        font-weight: 600;
    `;
    notif.textContent = message;
    
    document.body.appendChild(notif);
    setTimeout(() => {
        notif.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => notif.remove(), 300);
    }, 4000);
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
