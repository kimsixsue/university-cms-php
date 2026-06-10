<?php

declare(strict_types=1);

require_once __DIR__ . '/../Config/database.php';

class Site
{
    public static function all(): array
    {
        $pdo = db();

        $stmt = $pdo->query(
            'SELECT
                id,
                site_code,
                name,
                description,
                status,
                created_at,
                updated_at
             FROM sites
             ORDER BY id DESC'
        );

        return $stmt->fetchAll();
    }
}