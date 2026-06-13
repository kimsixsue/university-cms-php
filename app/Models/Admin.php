<?php

declare(strict_types=1);

require_once __DIR__ . '/../Config/database.php';

class Admin
{
    public static function findByUsername(string $username): ?array
    {
        $pdo = db();

        $stmt = $pdo->prepare(
            'SELECT id, username, password_hash, name, email, status
             FROM admins
             WHERE username = :username
             LIMIT 1'
        );

        $stmt->execute([
            'username' => $username,
        ]);

        $admin = $stmt->fetch();

        if ($admin === false) {
            return null;
        }

        return $admin;
    }

    public static function findRoleNamesByAdminId(int $adminId): array
    {
        $pdo = db();

        $stmt = $pdo->prepare(
            'SELECT r.name
             FROM admin_roles ar
             JOIN roles r ON r.id = ar.role_id
             WHERE ar.admin_id = :admin_id
             ORDER BY r.id ASC'
        );

        $stmt->execute([
            'admin_id' => $adminId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
