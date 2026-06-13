<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/AdminLog.php';

function isAdminLoggedIn(): bool
{
    return isset($_SESSION['admin_id']);
}

function requireAdminLogin(): void
{
    if (isAdminLoggedIn()) {
        return;
    }

    header('Location: /admin/login');
    exit;
}

function currentAdmin(): ?array
{
    if (!isAdminLoggedIn()) {
        return null;
    }

    return [
        'id' => $_SESSION['admin_id'],
        'username' => $_SESSION['admin_username'] ?? '',
        'name' => $_SESSION['admin_name'] ?? '',
        'roles' => $_SESSION['admin_roles'] ?? [],
    ];
}

function hasAdminRole(string $role): bool
{
    $admin = currentAdmin();

    if ($admin === null) {
        return false;
    }

    return in_array($role, $admin['roles'], true);
}

function requireAdminRole(string $role): void
{
    requireAdminLogin();

    if (hasAdminRole($role)) {
        return;
    }

    $admin = currentAdmin();

    AdminLog::create(
        $admin !== null ? (int) $admin['id'] : null,
        'role_denied',
        'admin_page',
        null,
        $_SERVER['REMOTE_ADDR'] ?? null
    );

    http_response_code(403);
    echo '<h1>403 Forbidden</h1>';
    echo '<p>해당 역할로 접근할 수 없는 관리자 페이지입니다.</p>';
    exit;
}
