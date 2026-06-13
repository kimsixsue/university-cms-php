<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>관리자 로그인 - University CMS PHP</title>
</head>

<body>
    <h1>관리자 로그인</h1>

    <p>대학 부속홈페이지 통합관리 CMS 관리자 로그인 화면입니다.</p>

    <?php if (!empty($error)): ?>
        <p style="color: red;">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form action="/admin/login" method="post">
        <div>
            <label for="username">아이디</label><br>
            <input
                type="text"
                id="username"
                name="username"
                value="<?= htmlspecialchars($oldUsername ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required
                autocomplete="username">
        </div>

        <br>

        <div>
            <label for="password">비밀번호</label><br>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="current-password">
        </div>

        <br>

        <button type="submit">로그인</button>
    </form>

    <p>
        <a href="/">메인으로 돌아가기</a>
    </p>
</body>

</html>