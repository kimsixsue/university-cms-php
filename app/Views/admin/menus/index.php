<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>메뉴 관리 - University CMS PHP</title>
</head>

<body>
    <h1>메뉴 관리</h1>

    <p>
        사이트별 메뉴 목록을 확인하는 관리자 화면입니다.
    </p>

    <p>
        <a href="/admin/menus/create?site_id=<?= htmlspecialchars((string) $selectedSiteId, ENT_QUOTES, 'UTF-8') ?>">메뉴 등록</a>
    </p>

    <form method="get" action="/admin/menus">
        <label for="site_id">사이트 선택</label>
        <select id="site_id" name="site_id">
            <?php foreach ($sites as $site): ?>
                <option
                    value="<?= htmlspecialchars((string) $site['id'], ENT_QUOTES, 'UTF-8') ?>"
                    <?= (int) $site['id'] === $selectedSiteId ? 'selected' : '' ?>>
                    <?= htmlspecialchars($site['name'], ENT_QUOTES, 'UTF-8') ?>
                    (<?= htmlspecialchars($site['site_code'], ENT_QUOTES, 'UTF-8') ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">조회</button>
    </form>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>상위 메뉴</th>
                <th>메뉴명</th>
                <th>메뉴 유형</th>
                <th>대상 ID</th>
                <th>링크 URL</th>
                <th>정렬 순서</th>
                <th>노출 여부</th>
                <th>생성일시</th>
                <th>관리</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($menus === []): ?>
                <tr>
                    <td colspan="10">등록된 메뉴가 없습니다.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($menus as $menu): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $menu['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if ($menu['parent_id'] === null): ?>
                            최상위 메뉴
                        <?php elseif (($menu['parent_name'] ?? '') !== ''): ?>
                            <?= htmlspecialchars($menu['parent_name'], ENT_QUOTES, 'UTF-8') ?>
                            (ID: <?= htmlspecialchars((string) $menu['parent_id'], ENT_QUOTES, 'UTF-8') ?>)
                        <?php else: ?>
                            알 수 없는 상위 메뉴
                            (ID: <?= htmlspecialchars((string) $menu['parent_id'], ENT_QUOTES, 'UTF-8') ?>)
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($menu['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($menu['menu_type'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) ($menu['target_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($menu['link_url'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) $menu['sort_order'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $menu['is_visible'] === 1 ? '노출' : '숨김' ?></td>
                    <td><?= htmlspecialchars($menu['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="/admin/menus/edit?id=<?= htmlspecialchars((string) $menu['id'], ENT_QUOTES, 'UTF-8') ?>">수정</a>

                        <?php if ((int) $menu['is_visible'] === 1): ?>
                            <form method="post" action="/admin/menus/visibility" style="display: inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars((string) $menu['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="is_visible" value="0">
                                <button type="submit">숨김 처리</button>
                            </form>
                        <?php else: ?>
                            <form method="post" action="/admin/menus/visibility" style="display: inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars((string) $menu['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="is_visible" value="1">
                                <button type="submit">노출 처리</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>
        <a href="/admin/dashboard">관리자 대시보드로 돌아가기</a>
    </p>

    <p>
        <a href="/admin/logout">로그아웃</a>
    </p>
</body>

</html>