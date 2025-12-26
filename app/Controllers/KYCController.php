<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;
use App\Services\Session;
use App\Services\NotificationService;

class KYCController
{
    public function verify(): void
    {
        $user = Auth::user();
        
        // Check if already verified
        $kyc = Database::fetch("SELECT * FROM kyc_verifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1", [$user['id']]);
        
        echo Router::render('user/kyc-verify', [
            'csrf_token' => Session::generateCsrfToken(),
            'kyc_status' => $kyc ? $kyc['status'] : null,
            'kyc_rejection_reason' => $kyc ? $kyc['rejection_reason'] : null,
        ]);
    }

    public function submitVerification(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Router::json(['success' => false, 'error' => 'CSRF token invalid'], 403);
            return;
        }

        $user = Auth::user();
        
        // Check if already verified
        $existingKyc = Database::fetch("SELECT * FROM kyc_verifications WHERE user_id = ? AND status = 'approved'", [$user['id']]);
        if ($existingKyc) {
            Router::json(['success' => false, 'error' => 'Already verified'], 400);
            return;
        }

        // Validate input
        $fullName = trim($_POST['full_name'] ?? '');
        $idType = trim($_POST['id_type'] ?? ''); // national_id, passport
        $idNumber = trim($_POST['id_number'] ?? '');

        if (empty($fullName) || empty($idType) || empty($idNumber)) {
            Router::json(['success' => false, 'error' => 'All fields required'], 400);
            return;
        }

        // Create upload directory
        $uploadDir = PUBLIC_PATH . '/uploads/kyc/' . $user['id'];
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Handle file uploads
        $frontId = null;
        $backId = null;
        $facePhoto = null;

        // Upload front ID
        if (isset($_FILES['front_id']) && $_FILES['front_id']['error'] === UPLOAD_ERR_OK) {
            $filename = 'front_' . time() . '.' . pathinfo($_FILES['front_id']['name'], PATHINFO_EXTENSION);
            $filepath = $uploadDir . '/' . $filename;
            if (move_uploaded_file($_FILES['front_id']['tmp_name'], $filepath)) {
                $frontId = '/uploads/kyc/' . $user['id'] . '/' . $filename;
            }
        }

        // Upload back ID
        if (isset($_FILES['back_id']) && $_FILES['back_id']['error'] === UPLOAD_ERR_OK) {
            $filename = 'back_' . time() . '.' . pathinfo($_FILES['back_id']['name'], PATHINFO_EXTENSION);
            $filepath = $uploadDir . '/' . $filename;
            if (move_uploaded_file($_FILES['back_id']['tmp_name'], $filepath)) {
                $backId = '/uploads/kyc/' . $user['id'] . '/' . $filename;
            }
        }

        // Upload face photo
        if (isset($_FILES['face_photo']) && $_FILES['face_photo']['error'] === UPLOAD_ERR_OK) {
            $filename = 'face_' . time() . '.' . pathinfo($_FILES['face_photo']['name'], PATHINFO_EXTENSION);
            $filepath = $uploadDir . '/' . $filename;
            if (move_uploaded_file($_FILES['face_photo']['tmp_name'], $filepath)) {
                $facePhoto = '/uploads/kyc/' . $user['id'] . '/' . $filename;
            }
        }

        if (!$frontId || !$backId || !$facePhoto) {
            Router::json(['success' => false, 'error' => 'All documents required'], 400);
            return;
        }

        // Create KYC verification record
        $kycId = Database::insert('kyc_verifications', [
            'user_id' => $user['id'],
            'full_name' => $fullName,
            'id_type' => $idType,
            'id_number' => $idNumber,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Store documents
        Database::insert('kyc_documents', [
            'kyc_id' => $kycId,
            'document_type' => 'front_id',
            'file_path' => $frontId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        Database::insert('kyc_documents', [
            'kyc_id' => $kycId,
            'document_type' => 'back_id',
            'file_path' => $backId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        Database::insert('kyc_documents', [
            'kyc_id' => $kycId,
            'document_type' => 'face_photo',
            'file_path' => $facePhoto,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Send notification
        NotificationService::create($user['id'], 'KYC Verification Submitted', 'Your KYC verification has been submitted. Admin will review and approve within 24 hours.', 'kyc', $kycId);

        Router::json(['success' => true, 'message' => 'Verification submitted successfully']);
    }

    public function approveKyc($kycId): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Router::json(['success' => false, 'error' => 'CSRF token invalid'], 403);
            return;
        }

        $admin = Auth::user();
        if (!$admin || !in_array($admin['role'], ['admin', 'moderator'])) {
            Router::json(['success' => false, 'error' => 'Unauthorized'], 403);
            return;
        }

        $kyc = Database::fetch("SELECT * FROM kyc_verifications WHERE id = ?", [$kycId]);
        if (!$kyc) {
            Router::json(['success' => false, 'error' => 'KYC not found'], 404);
            return;
        }

        // Update KYC status
        Database::update('kyc_verifications', [
            'status' => 'approved',
            'approved_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$kycId]);

        // Update user verification
        Database::update('users', [
            'kyc_verified' => true,
            'kyc_verified_at' => date('Y-m-d H:i:s')
        ], 'id = ?', [$kyc['user_id']]);

        // Send notification
        NotificationService::create($kyc['user_id'], 'KYC Approved', 'Your KYC verification has been approved! You can now access all features.', 'kyc', $kycId);

        Router::json(['success' => true, 'message' => 'KYC approved successfully']);
    }

    public function rejectKyc($kycId): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Router::json(['success' => false, 'error' => 'CSRF token invalid'], 403);
            return;
        }

        $admin = Auth::user();
        if (!$admin || !in_array($admin['role'], ['admin', 'moderator'])) {
            Router::json(['success' => false, 'error' => 'Unauthorized'], 403);
            return;
        }

        $kyc = Database::fetch("SELECT * FROM kyc_verifications WHERE id = ?", [$kycId]);
        if (!$kyc) {
            Router::json(['success' => false, 'error' => 'KYC not found'], 404);
            return;
        }

        $reason = trim($_POST['rejection_reason'] ?? '');
        if (empty($reason)) {
            Router::json(['success' => false, 'error' => 'Rejection reason required'], 400);
            return;
        }

        // Update KYC status
        Database::update('kyc_verifications', [
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$kycId]);

        // Send notification
        NotificationService::create($kyc['user_id'], 'KYC Rejected', 'Your KYC verification was rejected. Reason: ' . $reason . '. Please resubmit with correct documents.', 'kyc', $kycId);

        Router::json(['success' => true, 'message' => 'KYC rejected']);
    }
}
