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

    public static function create(
        string $siteCode,
        string $name,
        ?string $description,
        string $status
    ): int {
        $pdo = db();

        $stmt = $pdo->prepare(
            'INSERT INTO sites (
                site_code,
                name,
                description,
                status
             ) VALUES (
                :site_code,
                :name,
                :description,
                :status
             )'
        );

        $stmt->execute([
            'site_code' => $siteCode,
            'name' => $name,
            'description' => $description,
            'status' => $status,
        ]);

        return (int) $pdo->lastInsertId();
    }
}