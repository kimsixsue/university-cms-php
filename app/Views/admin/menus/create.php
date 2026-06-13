<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>메뉴 등록 - University CMS PHP</title>
</head>

<body>
    <h1>메뉴 등록</h1>

    <p>
        사이트별 메뉴를 등록하는 화면입니다.
    </p>

    <?php if (($errors ?? []) !== []): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="/admin/menus">
        <div>
            <label for="site_id">사이트</label>
            <select id="site_id" name="site_id" required>
                <?php foreach ($sites as $site): ?>
                    <option
                        value="<?= htmlspecialchars((string) $site['id'], ENT_QUOTES, 'UTF-8') ?>"
                        <?= (int) $site['id'] === (int) ($old['site_id'] ?? 0) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($site['name'], ENT_QUOTES, 'UTF-8') ?>
                        (<?= htmlspecialchars($site['site_code'], ENT_QUOTES, 'UTF-8') ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="parent_id">상위 메뉴 ID</label>
            <input
                type="number"
                id="parent_id"
                name="parent_id"
                min="1"
                value="<?= htmlspecialchars((string) ($old['parent_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <p>최상위 메뉴로 등록하려면 비워둡니다.</p>
        </div>

        <div>
            <label for="name">메뉴명</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                required>
        </div>

        <div>
            <label for="menu_type">메뉴 유형</label>
            <select id="menu_type" name="menu_type" required>
                <option value="page" <?= ($old['menu_type'] ?? 'page') === 'page' ? 'selected' : '' ?>>일반 페이지</option>
                <option value="board" <?= ($old['menu_type'] ?? 'page') === 'board' ? 'selected' : '' ?>>게시판</option>
                <option value="link" <?= ($old['menu_type'] ?? 'page') === 'link' ? 'selected' : '' ?>>외부 링크</option>
            </select>
        </div>

        <div>
            <label for="target_id">대상 ID</label>
            <input
                type="number"
                id="target_id"
                name="target_id"
                min="1"
                value="<?= htmlspecialchars((string) ($old['target_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <p>일반 페이지 또는 게시판과 연결할 때 사용할 예정입니다. 지금은 비워둘 수 있습니다.</p>
        </div>

        <div>
            <label for="link_url">링크 URL</label>
            <input
                type="text"
                id="link_url"
                name="link_url"
                value="<?= htmlspecialchars($old['link_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <p>메뉴 유형이 외부 링크일 때 사용할 예정입니다.</p>
        </div>

        <div>
            <label for="sort_order">정렬 순서</label>
            <input
                type="number"
                id="sort_order"
                name="sort_order"
                min="0"
                value="<?= htmlspecialchars((string) ($old['sort_order'] ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                required>
        </div>

        <div>
            <label for="is_visible">노출 여부</label>
            <select id="is_visible" name="is_visible" required>
                <option value="1" <?= ($old['is_visible'] ?? '1') === '1' ? 'selected' : '' ?>>노출</option>
                <option value="0" <?= ($old['is_visible'] ?? '1') === '0' ? 'selected' : '' ?>>숨김</option>
            </select>
        </div>

        <button type="submit">등록</button>
    </form>

    <p>
        <a href="/admin/menus">메뉴 관리로 돌아가기</a>
    </p>

    <p>
        <a href="/admin/dashboard">관리자 대시보드로 돌아가기</a>
    </p>

    <p>
        <a href="/admin/logout">로그아웃</a>
    </p>
</body>

</html>