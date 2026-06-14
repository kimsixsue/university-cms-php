<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>관리자 작업 로그 - University CMS PHP</title>
</head>

<body>
    <h1>관리자 작업 로그</h1>

    <p>
        관리자 로그인, 로그아웃, 역할 제한 실패, 사이트/메뉴 관리 작업 기록을 확인하는 화면입니다.
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>관리자</th>
                <th>작업</th>
                <th>대상 유형</th>
                <th>대상 ID</th>
                <th>IP 주소</th>
                <th>기록일시</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($logs === []): ?>
                <tr>
                    <td colspan="7">기록된 관리자 작업 로그가 없습니다.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $log['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if ($log['admin_id'] === null): ?>
                            알 수 없음
                        <?php else: ?>
                            <?= htmlspecialchars($log['admin_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            (<?= htmlspecialchars($log['admin_username'] ?? '', ENT_QUOTES, 'UTF-8') ?>)
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($log['action'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($log['target_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars((string) ($log['target_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($log['ip_address'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($log['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
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