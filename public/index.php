<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/Models/Admin.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($path === '/') {
    echo '<h1>University CMS PHP</h1>';
    echo '<p>PHP·MySQL 기반 대학 부속홈페이지 통합관리 CMS 개발환경이 실행 중입니다.</p>';
    echo '<ul>';
    echo '<li><a href="/admin/login">관리자 로그인</a></li>';
    echo '</ul>';
    exit;
}

if ($path === '/admin/login' && $method === 'GET') {
    $error = $_SESSION['login_error'] ?? null;
    $oldUsername = $_SESSION['old_username'] ?? '';

    unset($_SESSION['login_error'], $_SESSION['old_username']);

    require __DIR__ . '/../app/Views/admin/auth/login.php';
    exit;
}

if ($path === '/admin/login' && $method === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $_SESSION['login_error'] = '아이디와 비밀번호를 입력해주세요.';
        $_SESSION['old_username'] = $username;

        header('Location: /admin/login');
        exit;
    }

    $admin = Admin::findByUsername($username);

    if (
        $admin === null
        || $admin['status'] !== 'active'
        || !password_verify($password, $admin['password_hash'])
    ) {
        $_SESSION['login_error'] = '아이디 또는 비밀번호가 올바르지 않습니다.';
        $_SESSION['old_username'] = $username;

        header('Location: /admin/login');
        exit;
    }

    $roles = Admin::findRoleNamesByAdminId((int) $admin['id']);

    session_regenerate_id(true);

    $_SESSION['admin_id'] = (int) $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_roles'] = $roles;

    header('Location: /admin/dashboard');
    exit;
}

if ($path === '/admin/logout') {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    header('Location: /admin/login');
    exit;
}

if ($path === '/admin/dashboard') {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /admin/login');
        exit;
    }

    require __DIR__ . '/../app/Views/admin/dashboard/index.php';
    exit;
}

http_response_code(404);
echo '<h1>404 Not Found</h1>';
echo '<p>요청한 페이지를 찾을 수 없습니다.</p>';