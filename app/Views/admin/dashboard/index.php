<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>관리자 대시보드 - University CMS PHP</title>
</head>
<body>
    <h1>관리자 대시보드</h1>

    <p>
        <?= htmlspecialchars($_SESSION['admin_name'] ?? '관리자', ENT_QUOTES, 'UTF-8') ?>님, 로그인되었습니다.
    </p>

    <h2>로그인 정보</h2>

    <ul>
        <li>아이디: <?= htmlspecialchars($_SESSION['admin_username'] ?? '', ENT_QUOTES, 'UTF-8') ?></li>
        <li>역할: <?= htmlspecialchars(implode(', ', $_SESSION['admin_roles'] ?? []), ENT_QUOTES, 'UTF-8') ?></li>
    </ul>

    <p>
        추후 이 화면에 사이트 관리, 메뉴 관리, 페이지 관리, 게시판 관리 기능을 추가할 예정입니다.
    </p>
</body>
</html>