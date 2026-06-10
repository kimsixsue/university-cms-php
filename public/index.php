<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/Models/Admin.php';
require_once __DIR__ . '/../app/Models/AdminLog.php';
require_once __DIR__ . '/../app/Models/Site.php';
require_once __DIR__ . '/../app/Core/Auth.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

function clientIpAddress(): ?string
{
    return $_SERVER['REMOTE_ADDR'] ?? null;
}

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
        AdminLog::create(
            null,
            'login_failed',
            'admin',
            null,
            clientIpAddress()
        );

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
        AdminLog::create(
            null,
            'login_failed',
            'admin',
            null,
            clientIpAddress()
        );

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

    AdminLog::create(
        (int) $admin['id'],
        'login',
        'admin',
        (int) $admin['id'],
        clientIpAddress()
    );

    header('Location: /admin/dashboard');
    exit;
}

if ($path === '/admin/logout') {
    $admin = currentAdmin();

    if ($admin !== null) {
        AdminLog::create(
            (int) $admin['id'],
            'logout',
            'admin',
            (int) $admin['id'],
            clientIpAddress()
        );
    }

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

if ($path === '/admin/sites/create' && $method === 'GET') {
    requireAdminRole('super_admin');

    require __DIR__ . '/../app/Views/admin/sites/create.php';
    exit;
}

if ($path === '/admin/sites' && $method === 'POST') {
    requireAdminRole('super_admin');

    $siteCode = trim($_POST['site_code'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'active';

    if (!in_array($status, ['active', 'inactive'], true)) {
        $status = 'active';
    }

    $errors = [];

    if ($siteCode === '') {
        $errors[] = '사이트 코드를 입력해주세요.';
    } elseif (!preg_match('/^[a-z0-9-]+$/', $siteCode)) {
        $errors[] = '사이트 코드는 영문 소문자, 숫자, 하이픈(-)만 입력할 수 있습니다.';
    }

    if ($name === '') {
        $errors[] = '사이트명을 입력해주세요.';
    }

    if ($errors !== []) {
        $old = [
            'site_code' => $siteCode,
            'name' => $name,
            'description' => $description,
            'status' => $status,
        ];

        require __DIR__ . '/../app/Views/admin/sites/create.php';
        exit;
    }

    if ($description === '') {
        $description = null;
    }

    try {
        $siteId = Site::create($siteCode, $name, $description, $status);

        $admin = currentAdmin();

        AdminLog::create(
            $admin !== null ? (int) $admin['id'] : null,
            'site_created',
            'site',
            $siteId,
            $_SERVER['REMOTE_ADDR'] ?? null
        );
    } catch (PDOException $e) {
        $errors[] = '이미 사용 중인 사이트 코드입니다.';

        $old = [
            'site_code' => $siteCode,
            'name' => $name,
            'description' => $description ?? '',
            'status' => $status,
        ];

        require __DIR__ . '/../app/Views/admin/sites/create.php';
        exit;
    }

    header('Location: /admin/sites');
    exit;
}

if ($path === '/admin/sites' && $method === 'GET') {
    requireAdminRole('super_admin');

    $sites = Site::all();

    require __DIR__ . '/../app/Views/admin/sites/index.php';
    exit;
}

if ($path === '/admin/dashboard') {
    requireAdminRole('super_admin');

    require __DIR__ . '/../app/Views/admin/dashboard/index.php';
    exit;
}

http_response_code(404);
echo '<h1>404 Not Found</h1>';
echo '<p>요청한 페이지를 찾을 수 없습니다.</p>';