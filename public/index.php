<?php

declare(strict_types=1);

session_start();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

if ($path === '/') {
    echo '<h1>University CMS PHP</h1>';
    echo '<p>PHP·MySQL 기반 대학 부속홈페이지 통합관리 CMS 개발환경이 실행 중입니다.</p>';
    echo '<ul>';
    echo '<li><a href="/admin/login">관리자 로그인</a></li>';
    echo '</ul>';
    exit;
}

if ($path === '/admin/login') {
    require __DIR__ . '/../app/Views/admin/auth/login.php';
    exit;
}

http_response_code(404);
echo '<h1>404 Not Found</h1>';
echo '<p>요청한 페이지를 찾을 수 없습니다.</p>';