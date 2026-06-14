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

    public static function all(int $limit = 100): array
    {
        $pdo = db();

        $stmt = $pdo->prepare(
            'SELECT
                admin_logs.id,
                admin_logs.admin_id,
                admins.username AS admin_username,
                admins.name AS admin_name,
                admin_logs.action,
                admin_logs.target_type,
                admin_logs.target_id,
                admin_logs.ip_address,
                admin_logs.created_at
             FROM admin_logs
             LEFT JOIN admins
                ON admins.id = admin_logs.admin_id
             ORDER BY admin_logs.id DESC
             LIMIT :limit'
        );

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
