<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>사이트 수정 - University CMS PHP</title>
</head>
<body>
    <h1>사이트 수정</h1>

    <p>
        대학 부속홈페이지 사이트 정보를 수정하는 관리자 화면입니다.
    </p>

    <?php if (!empty($errors)): ?>
        <div>
            <p>입력값을 확인해주세요.</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/admin/sites/update">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) $site['id'], ENT_QUOTES, 'UTF-8') ?>">

        <div>
            <label for="site_code">사이트 코드</label><br>
            <input
                type="text"
                id="site_code"
                name="site_code"
                value="<?= htmlspecialchars($site['site_code'], ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div>
            <label for="name">사이트명</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($site['name'], ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div>
            <label for="description">설명</label><br>
            <textarea id="description" name="description" rows="4" cols="50"><?= htmlspecialchars($site['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div>
            <label for="status">상태</label><br>
            <select id="status" name="status">
                <option value="active" <?= ($site['status'] === 'active') ? 'selected' : '' ?>>사용 중</option>
                <option value="inactive" <?= ($site['status'] === 'inactive') ? 'selected' : '' ?>>사용 중지</option>
            </select>
        </div>

        <p>
            <button type="submit">수정</button>
        </p>
    </form>

    <p>
        <a href="/admin/sites">사이트 목록으로 돌아가기</a>
    </p>

    <p>
        <a href="/admin/logout">로그아웃</a>
    </p>
</body>
</html>