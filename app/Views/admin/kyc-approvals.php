<?php
ob_start();
?>

<div class="admin-kyc-section">
    <div class="page-header">
        <h1>KYC Verification Approvals</h1>
        <p>Review and approve user identity verifications</p>
    </div>

    <div class="tabs">
        <button class="tab-btn active" onclick="filterKYC('pending')">Pending (<?= $pending_count ?>)</button>
        <button class="tab-btn" onclick="filterKYC('approved')">Approved (<?= $approved_count ?>)</button>
        <button class="tab-btn" onclick="filterKYC('rejected')">Rejected (<?= $rejected_count ?>)</button>
    </div>

    <div class="kyc-list">
        <?php if (empty($kyc_verifications)): ?>
            <div class="empty-state">
                <p>No KYC verifications found</p>
            </div>
        <?php else: ?>
            <?php foreach ($kyc_verifications as $kyc): ?>
                <div class="kyc-card" data-status="<?= $kyc['status'] ?>">
                    <div class="kyc-header-info">
                        <div>
                            <h3><?= htmlspecialchars($kyc['full_name']) ?></h3>
                            <p class="user-email"><?= htmlspecialchars($kyc['user_email']) ?></p>
                            <p class="id-info"><?= strtoupper($kyc['id_type']) ?> • <?= htmlspecialchars($kyc['id_number']) ?></p>
                        </div>
                        <div class="status-badge" style="background: <?php 
                            if ($kyc['status'] === 'pending') echo 'rgba(255, 193, 7, 0.2); color: #ffc107;';
                            elseif ($kyc['status'] === 'approved') echo 'rgba(16, 185, 129, 0.2); color: #10b981;';
                            else echo 'rgba(220, 53, 69, 0.2); color: #dc3545;';
                        ?>">
                            <?= ucfirst($kyc['status']) ?>
                        </div>
                    </div>

                    <div class="kyc-documents">
                        <h4>Documents</h4>
                        <div class="doc-grid">
                            <?php foreach ($kyc['documents'] as $doc): ?>
                                <div class="doc-item">
                                    <div class="doc-preview">
                                        <img src="<?= htmlspecialchars($doc['file_path']) ?>" alt="<?= $doc['document_type'] ?>" onclick="showDocumentModal('<?= htmlspecialchars($doc['file_path']) ?>')">
                                    </div>
                                    <p><?= ucwords(str_replace('_', ' ', $doc['document_type'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if ($kyc['status'] === 'pending'): ?>
                        <div class="kyc-actions">
                            <button class="btn btn-success" onclick="approveKYC(<?= $kyc['id'] ?>)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Approve
                            </button>
                            <button class="btn btn-danger" onclick="showRejectModal(<?= $kyc['id'] ?>)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Reject
                            </button>
                        </div>
                    <?php elseif ($kyc['status'] === 'rejected'): ?>
                        <div class="rejection-reason">
                            <strong>Rejection Reason:</strong>
                            <p><?= htmlspecialchars($kyc['rejection_reason']) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="kyc-meta">
                        <span>Submitted: <?= date('M d, Y H:i', strtotime($kyc['created_at'])) ?></span>
                        <?php if ($kyc['status'] === 'approved'): ?>
                            <span>Approved: <?= date('M d, Y H:i', strtotime($kyc['approved_at'])) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Document Modal -->
<div id="documentModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeDocumentModal()"></div>
    <div class="modal-content">
        <button class="modal-close" onclick="closeDocumentModal()">&times;</button>
        <img id="modalImage" src="" alt="Document">
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal" style="display: none;">
    <div class="modal-overlay" onclick="closeRejectModal()"></div>
    <div class="modal-content" style="max-width: 500px;">
        <h2>Reject KYC Verification</h2>
        <form id="rejectForm">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" id="rejectKycId" name="kyc_id">
            
            <div class="form-group">
                <label>Rejection Reason</label>
                <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Explain why the documents were rejected..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Reject</button>
            </div>
        </form>
    </div>
</div>

<style>
.admin-kyc-section {
    padding: 20px;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 28px;
    margin-bottom: 5px;
}

.page-header p {
    color: var(--text-secondary);
}

.tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
}

.tab-btn {
    padding: 12px 20px;
    background: none;
    border: none;
    color: var(--text-secondary);
    font-weight: 500;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
}

.tab-btn.active {
    color: var(--accent-primary);
    border-bottom-color: var(--accent-primary);
}

.kyc-list {
    display: grid;
    gap: 20px;
}

.kyc-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
}

.kyc-header-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.kyc-header-info h3 {
    margin: 0 0 5px;
    color: var(--text-primary);
}

.user-email {
    color: var(--accent-primary);
    font-size: 14px;
    margin: 3px 0;
}

.id-info {
    color: var(--text-secondary);
    font-size: 13px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px;
}

.kyc-documents {
    margin: 20px 0;
}

.kyc-documents h4 {
    margin-bottom: 15px;
    color: var(--text-primary);
}

.doc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.doc-item {
    text-align: center;
}

.doc-preview {
    margin-bottom: 10px;
    cursor: pointer;
    border-radius: 8px;
    overflow: hidden;
    background: var(--bg-primary);
}

.doc-preview img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    transition: transform 0.2s;
}

.doc-preview img:hover {
    transform: scale(1.05);
}

.doc-item p {
    font-size: 12px;
    color: var(--text-secondary);
}

.kyc-actions {
    display: flex;
    gap: 10px;
    margin: 20px 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-secondary {
    background: var(--border-color);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background: rgba(0, 0, 0, 0.2);
}

.rejection-reason {
    background: rgba(220, 53, 69, 0.1);
    border: 1px solid rgba(220, 53, 69, 0.2);
    padding: 15px;
    border-radius: 6px;
    margin: 20px 0;
}

.rejection-reason strong {
    color: #dc3545;
    display: block;
    margin-bottom: 8px;
}

.rejection-reason p {
    margin: 0;
    color: var(--text-primary);
}

.kyc-meta {
    display: flex;
    gap: 20px;
    padding-top: 15px;
    border-top: 1px solid var(--border-color);
    font-size: 13px;
    color: var(--text-secondary);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
}

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
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    cursor: pointer;
}

.modal-content {
    position: relative;
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 30px;
    max-width: 800px;
    width: 95%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 28px;
    color: var(--text-secondary);
    cursor: pointer;
}

#modalImage {
    width: 100%;
    border-radius: 8px;
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
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
}

.modal-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}
</style>

<script>
function filterKYC(status) {
    const cards = document.querySelectorAll('.kyc-card');
    const buttons = document.querySelectorAll('.tab-btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');

    cards.forEach(card => {
        if (card.dataset.status === status) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function showDocumentModal(imagePath) {
    const modal = document.getElementById('documentModal');
    const img = document.getElementById('modalImage');
    img.src = imagePath;
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
            showAlert('KYC approved successfully', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.error || 'Failed to approve KYC', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred', 'error');
    }
}

document.getElementById('rejectForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const kycId = document.getElementById('rejectKycId').value;
    const formData = new FormData(e.target);

    try {
        const response = await fetch(`/admin/kyc/reject/${kycId}`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showAlert('KYC rejected', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.error || 'Failed to reject KYC', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred', 'error');
    }
});

function showAlert(message, type) {
    const alert = document.createElement('div');
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        background: ${type === 'success' ? '#10b981' : '#dc3545'};
    `;
    alert.textContent = message;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 4000);
}
</script>
