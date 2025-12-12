<?php

namespace App\Services;

class AuditLog
{
    public static function log(string $action, string $entity, ?int $entityId = null, ?array $details = null, ?int $userId = null): void
    {
        Database::insert('audit_logs', [
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
            'details' => $details ? json_encode($details) : null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
