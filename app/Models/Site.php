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

    public static function find(int $id): ?array
    {
        $pdo = db();

        $stmt = $pdo->prepare(
            'SELECT
                id,
                site_code,
                name,
                description,
                status,
                created_at,
                updated_at
             FROM sites
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            'id' => $id,
        ]);

        $site = $stmt->fetch();

        return $site !== false ? $site : null;
    }

    public static function update(
        int $id,
        string $siteCode,
        string $name,
        ?string $description,
        string $status
    ): void {
        $pdo = db();

        $stmt = $pdo->prepare(
            'UPDATE sites
             SET
                site_code = :site_code,
                name = :name,
                description = :description,
                status = :status
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'site_code' => $siteCode,
            'name' => $name,
            'description' => $description,
            'status' => $status,
        ]);
    }

    public static function deactivate(int $id): void
    {
        $pdo = db();

        $stmt = $pdo->prepare(
            'UPDATE sites
             SET status = :status
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'status' => 'inactive',
        ]);
    }

    public static function activate(int $id): void
    {
        $pdo = db();

        $stmt = $pdo->prepare(
            'UPDATE sites
             SET status = :status
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'status' => 'active',
        ]);
    }
}
