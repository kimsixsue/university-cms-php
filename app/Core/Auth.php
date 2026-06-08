<?php

declare(strict_types=1);

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