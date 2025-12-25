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
                        <h3 class="stat-number"><?= $pending_count ?? 0 ?></h3>
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
                        <h3 class="stat-number"><?= $approved_count ?? 0 ?></h3>
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
                        <h3 class="stat-number"><?= $rejected_count ?? 0 ?></h3>
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
                    <span class="tab-badge"><?= $pending_count ?? 0 ?></span>
                </button>
                <button class="tab-btn" onclick="filterKYC('approved')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Approved</span>
                    <span class="tab-badge"><?= $approved_count ?? 0 ?></span>
                </button>
                <button class="tab-btn" onclick="filterKYC('rejected')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <span>Rejected</span>
                    <span class="tab-badge"><?= $rejected_count ?? 0 ?></span>
                </button>
            </div>
        </div>

        <!-- KYC List -->
        <div class="kyc-list">
            <?php if (empty($kyc_verifications ?? [])): ?>
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
                <?php foreach (($kyc_verifications ?? []) as $kyc): ?>
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
                                        <div class="doc-preview" style="aspect-ratio: auto; height: auto; min-height: 400px;">
                                            <?php
                                            $imagePath = $doc['file_path'];
                                            if (strpos($imagePath, 'http') !== 0) {
                                                // Ensure the path starts with /uploads and handle ROOT_PATH issues
                                                if (strpos($imagePath, '/public/') === 0) {
                                                    $imagePath = substr($imagePath, 7);
                                                } elseif (strpos($imagePath, 'public/') === 0) {
                                                    $imagePath = substr($imagePath, 6);
                                                }
                                                
                                                if (strpos($imagePath, '/') !== 0) {
                                                    $imagePath = '/' . $imagePath;
                                                }
                                            }
                                            ?>
                                            <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= $doc['document_type'] ?>" 
                                                 style="object-fit: contain; width: 100%; height: 100%;"
                                                 onclick="showDocumentModal('<?= htmlspecialchars($imagePath) ?>', '<?= ucwords(str_replace('_', ' ', $doc['document_type'])) ?>')" 
                                                 onerror="this.src='https://ui-avatars.com/api/?name=DOC&background=333&color=fff&size=400'">
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
            <div class="modal-image-wrapper">
                <img id="modalImage" src="" alt="Document" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23333%22 width=%22400%22 height=%22300%22/%3E%3Ctext x=%22200%22 y=%22150%22 fill=%22white%22 text-anchor=%22middle%22 font-family=%22Arial%22%3EImage Not Found%3C/text%3E%3C/svg%3E'">
            </div>
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
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?? '' ?>">
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
* { box-sizing: border-box; }
:root {
    --bg-primary: #0a1628;
    --bg-secondary: #112236;
    --bg-tertiary: #1a2f4a;
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
    background: var(--bg-primary);
    min-height: 100vh;
    padding: 30px;
    font-family: 'Inter', sans-serif;
}

.admin-header-section { margin-bottom: 40px; }
.header-title h1 { font-size: 28px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
.header-title p { color: var(--text-secondary); font-size: 15px; }

.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-top: 30px; }
.stat-card { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; display: flex; align-items: center; gap: 20px; transition: 0.3s; }
.stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
.pending .stat-icon { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
.approved .stat-icon { background: rgba(16, 185, 129, 0.1); color: var(--success); }
.rejected .stat-icon { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
.stat-number { font-size: 32px; font-weight: 700; color: var(--text-primary); margin-top: 4px; }
.stat-label { font-size: 13px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; }

.tabs-wrapper { display: flex; gap: 15px; border-bottom: 1px solid var(--border-color); margin-bottom: 30px; padding-bottom: 15px; }
.tab-btn { background: none; border: none; color: var(--text-secondary); font-weight: 600; padding: 10px 20px; border-radius: 8px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 8px; }
.tab-btn.active { background: rgba(0, 212, 170, 0.1); color: var(--accent-primary); }
.tab-badge { background: rgba(255,255,255,0.1); padding: 2px 8px; border-radius: 10px; font-size: 12px; }

.kyc-card { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 16px; margin-bottom: 25px; overflow: hidden; }
.kyc-card-header { padding: 24px; display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02); }
.user-info { display: flex; gap: 16px; align-items: center; }
.user-avatar { width: 50px; height: 50px; border-radius: 50%; background: var(--accent-primary); color: var(--bg-primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; }
.user-details h3 { font-size: 18px; margin-bottom: 4px; color: var(--text-primary); }
.user-email { color: var(--accent-primary); font-size: 14px; }

.kyc-documents-section { padding: 24px; border-top: 1px solid var(--border-color); }
.doc-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; }
.doc-preview { position: relative; border-radius: 12px; overflow: hidden; aspect-ratio: 3/2; background: #000; border: 1px solid var(--border-color); box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
.doc-preview img { width: 100%; height: 100%; object-fit: contain; transition: 0.5s; }
.doc-preview:hover img { transform: scale(1.05); }
.doc-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; color: #fff; }
.doc-preview:hover .doc-overlay { opacity: 1; }
.doc-name { margin-top: 12px; font-weight: 600; color: var(--text-primary); text-align: center; }

.kyc-actions-section { padding: 20px 24px; background: rgba(0,0,0,0.1); display: flex; gap: 15px; border-top: 1px solid var(--border-color); }
.btn { padding: 12px 28px; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; display: flex; align-items: center; gap: 10px; transition: 0.3s; }
.btn-approve { background: var(--success); color: #fff; }
.btn-reject { background: var(--danger); color: #fff; }
.btn:hover { opacity: 0.9; transform: translateY(-2px); }

.modal { position: fixed; inset: 0; z-index: 10000; display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.9); backdrop-filter: blur(5px); }
.modal-content { position: relative; background: var(--bg-secondary); border-radius: 20px; width: 100%; max-width: 1100px; max-height: 95vh; overflow: hidden; border: 1px solid var(--border-color); }
.modal-body { padding: 30px; display: flex; flex-direction: column; height: 100%; }
.modal-image-wrapper { background: #000; border-radius: 12px; overflow: hidden; flex: 1; display: flex; align-items: center; justify-content: center; min-height: 60vh; }
.modal-image-wrapper img { max-width: 100%; max-height: 80vh; object-fit: contain; }
.modal-close { position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.1); border: none; color: #fff; padding: 8px; border-radius: 50%; cursor: pointer; transition: 0.3s; z-index: 10; }
.modal-close:hover { background: var(--danger); }
.doc-title { font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 20px; text-align: center; }
</style>

<script>
function filterKYC(status) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');
    document.querySelectorAll('.kyc-card').forEach(card => {
        card.style.display = (status === 'all' || card.dataset.status === status) ? 'block' : 'none';
    });
}

function showDocumentModal(imagePath, docTitle) {
    const modal = document.getElementById('documentModal');
    const img = document.getElementById('modalImage');
    const title = document.getElementById('docTitle');
    img.src = imagePath;
    title.textContent = docTitle;
    modal.style.display = 'flex';
}

function closeDocumentModal() { document.getElementById('documentModal').style.display = 'none'; }
function showRejectModal(kycId) { document.getElementById('rejectKycId').value = kycId; document.getElementById('rejectModal').style.display = 'flex'; }
function closeRejectModal() { document.getElementById('rejectModal').style.display = 'none'; }

async function approveKYC(kycId) {
    if (!confirm('Approve this verification?')) return;
    try {
        const formData = new FormData();
        formData.append('_csrf_token', '<?= $csrf_token ?? '' ?>');
        
        const res = await fetch(`/admin/kyc/approve/${kycId}`, { 
            method: 'POST', 
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' } 
        });
        const text = await res.text();
        if (text.toLowerCase().includes('success')) {
            showNotification('Approved!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else { showNotification('Failed to approve', 'error'); }
    } catch (e) { showNotification('Error occurred', 'error'); }
}

document.getElementById('rejectForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const kycId = document.getElementById('rejectKycId').value;
    try {
        const res = await fetch(`/admin/kyc/reject/${kycId}`, { method: 'POST', body: new FormData(this), headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const text = await res.text();
        if (text.toLowerCase().includes('success')) {
            showNotification('Rejected!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else { showNotification('Failed to reject', 'error'); }
    } catch (e) { showNotification('Error occurred', 'error'); }
});

function showNotification(msg, type) {
    const n = document.createElement('div');
    n.style.cssText = `position:fixed;top:20px;right:20px;background:${type==='success'?'#10b981':'#ef4444'};color:#fff;padding:15px 25px;border-radius:10px;z-index:100000;font-weight:600;box-shadow:0 10px 30px rgba(0,0,0,0.3);`;
    n.textContent = msg;
    document.body.appendChild(n);
    setTimeout(() => n.remove(), 3000);
}
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/app/Views/layouts/dashboard.php';
?>