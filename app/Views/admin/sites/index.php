<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>사이트 관리 - University CMS PHP</title>
</head>

<body>
    <h1>사이트 관리</h1>

    <p>
        대학 부속홈페이지 사이트 목록을 확인하는 관리자 화면입니다.
    </p>

    <p>
        <a href="/admin/sites/create">사이트 등록</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>사이트 코드</th>
                <th>사이트명</th>
                <th>설명</th>
                <th>상태</th>
                <th>생성일시</th>
                <th>수정일시</th>
                <th>관리</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($sites === []): ?>
                <tr>
                    <td colspan="8">등록된 사이트가 없습니다.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($sites as $site): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $site['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($site['site_code'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($site['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($site['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($site['status'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($site['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($site['updated_at'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="/admin/sites/edit?id=<?= htmlspecialchars((string) $site['id'], ENT_QUOTES, 'UTF-8') ?>">수정</a>

                        <?php if ($site['status'] === 'active'): ?>
                            <form method="post" action="/admin/sites/deactivate" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars((string) $site['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit">사용 중지</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($site['status'] === 'inactive'): ?>
                            <form method="post" action="/admin/sites/activate" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars((string) $site['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit">사용 재개</button>
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