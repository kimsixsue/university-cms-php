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
}