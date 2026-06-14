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
                menus.id,
                menus.site_id,
                menus.parent_id,
                parent_menus.name AS parent_name,
                menus.name,
                menus.menu_type,
                menus.target_id,
                menus.link_url,
                menus.sort_order,
                menus.is_visible,
                menus.created_at,
                menus.updated_at
             FROM menus
             LEFT JOIN menus AS parent_menus
                ON parent_menus.id = menus.parent_id
                AND parent_menus.site_id = menus.site_id
             WHERE menus.site_id = :site_id
             ORDER BY menus.sort_order ASC, menus.id ASC'
        );

        $stmt->execute([
            'site_id' => $siteId,
        ]);

        return $stmt->fetchAll();
    }

    public static function treeBySite(int $siteId): array
    {
        $menus = self::allBySite($siteId);

        return self::buildTree($menus);
    }

    private static function buildTree(array $menus): array
    {
        $childrenByParent = [];

        foreach ($menus as $menu) {
            $parentId = $menu['parent_id'] === null ? 0 : (int) $menu['parent_id'];
            $childrenByParent[$parentId][] = $menu;
        }

        $tree = [];
        $visited = [];

        foreach ($childrenByParent[0] ?? [] as $menu) {
            self::appendMenuToTree($menu, $childrenByParent, $tree, $visited, 0);
        }

        foreach ($menus as $menu) {
            $menuId = (int) $menu['id'];

            if (!isset($visited[$menuId])) {
                self::appendMenuToTree($menu, $childrenByParent, $tree, $visited, 0);
            }
        }

        return $tree;
    }

    private static function appendMenuToTree(
        array $menu,
        array $childrenByParent,
        array &$tree,
        array &$visited,
        int $depth
    ): void {
        $menuId = (int) $menu['id'];

        if (isset($visited[$menuId])) {
            return;
        }

        $visited[$menuId] = true;
        $menu['depth'] = $depth;

        $tree[] = $menu;

        foreach ($childrenByParent[$menuId] ?? [] as $childMenu) {
            self::appendMenuToTree($childMenu, $childrenByParent, $tree, $visited, $depth + 1);
        }
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

    public static function findBySite(int $id, int $siteId): ?array
    {
        $menu = self::find($id);

        if ($menu === null) {
            return null;
        }

        if ((int) $menu['site_id'] !== $siteId) {
            return null;
        }

        return $menu;
    }

    public static function update(
        int $id,
        int $siteId,
        ?int $parentId,
        string $name,
        string $menuType,
        ?int $targetId,
        ?string $linkUrl,
        int $sortOrder,
        bool $isVisible
    ): void {
        $pdo = db();

        $stmt = $pdo->prepare(
            'UPDATE menus
             SET
                site_id = :site_id,
                parent_id = :parent_id,
                name = :name,
                menu_type = :menu_type,
                target_id = :target_id,
                link_url = :link_url,
                sort_order = :sort_order,
                is_visible = :is_visible
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'site_id' => $siteId,
            'parent_id' => $parentId,
            'name' => $name,
            'menu_type' => $menuType,
            'target_id' => $targetId,
            'link_url' => $linkUrl,
            'sort_order' => $sortOrder,
            'is_visible' => $isVisible ? 1 : 0,
        ]);
    }

    public static function setVisibility(int $id, bool $isVisible): void
    {
        $pdo = db();

        $stmt = $pdo->prepare(
            'UPDATE menus
             SET is_visible = :is_visible
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'is_visible' => $isVisible ? 1 : 0,
        ]);
    }
}
