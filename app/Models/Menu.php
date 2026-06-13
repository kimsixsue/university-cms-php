<?php

declare(strict_types=1);

require_once __DIR__ . '/../Config/database.php';

class Menu
{
    public static function allBySite(int $siteId): array
    {
        $pdo = db();

        $stmt = $pdo->prepare(
            'SELECT
                id,
                site_id,
                parent_id,
                name,
                menu_type,
                target_id,
                link_url,
                sort_order,
                is_visible,
                created_at,
                updated_at
             FROM menus
             WHERE site_id = :site_id
             ORDER BY sort_order ASC, id ASC'
        );

        $stmt->execute([
            'site_id' => $siteId,
        ]);

        return $stmt->fetchAll();
    }

    public static function create(
        int $siteId,
        ?int $parentId,
        string $name,
        string $menuType,
        ?int $targetId,
        ?string $linkUrl,
        int $sortOrder,
        bool $isVisible
    ): int {
        $pdo = db();

        $stmt = $pdo->prepare(
            'INSERT INTO menus (
                site_id,
                parent_id,
                name,
                menu_type,
                target_id,
                link_url,
                sort_order,
                is_visible
             ) VALUES (
                :site_id,
                :parent_id,
                :name,
                :menu_type,
                :target_id,
                :link_url,
                :sort_order,
                :is_visible
             )'
        );

        $stmt->execute([
            'site_id' => $siteId,
            'parent_id' => $parentId,
            'name' => $name,
            'menu_type' => $menuType,
            'target_id' => $targetId,
            'link_url' => $linkUrl,
            'sort_order' => $sortOrder,
            'is_visible' => $isVisible ? 1 : 0,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function find(int $id): ?array
    {
        $pdo = db();

        $stmt = $pdo->prepare(
            'SELECT
                id,
                site_id,
                parent_id,
                name,
                menu_type,
                target_id,
                link_url,
                sort_order,
                is_visible,
                created_at,
                updated_at
             FROM menus
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            'id' => $id,
        ]);

        $menu = $stmt->fetch();

        return $menu === false ? null : $menu;
    }
}
