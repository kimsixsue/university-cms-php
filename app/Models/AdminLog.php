<?php

declare(strict_types=1);

require_once __DIR__ . '/../Config/database.php';

class AdminLog
{
    public static function create(
        ?int $adminId,
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?string $ipAddress = null
    ): void {
        $pdo = db();

        $stmt = $pdo->prepare(
            'INSERT INTO admin_logs (
                admin_id,
                action,
                target_type,
                target_id,
                ip_address
            ) VALUES (
                :admin_id,
                :action,
                :target_type,
                :target_id,
                :ip_address
            )'
        );

        $stmt->execute([
            'admin_id' => $adminId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'ip_address' => $ipAddress,
        ]);
    }
}
