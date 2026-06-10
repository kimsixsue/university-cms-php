<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>사이트 등록 - University CMS PHP</title>
</head>
<body>
    <h1>사이트 등록</h1>

    <p>
        대학 부속홈페이지 사이트 정보를 등록하는 관리자 화면입니다.
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

    <form method="post" action="/admin/sites">
        <div>
            <label for="site_code">사이트 코드</label><br>
            <input
                type="text"
                id="site_code"
                name="site_code"
                value="<?= htmlspecialchars($old['site_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div>
            <label for="name">사이트명</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div>
            <label for="description">설명</label><br>
            <textarea id="description" name="description" rows="4" cols="50"><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div>
            <label for="status">상태</label><br>
            <select id="status" name="status">
                <option value="active" <?= (($old['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>사용 중</option>
                <option value="inactive" <?= (($old['status'] ?? 'active') === 'inactive') ? 'selected' : '' ?>>사용 중지</option>
            </select>
        </div>

        <p>
            <button type="submit">등록</button>
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