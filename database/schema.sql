SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS admins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_roles (
    admin_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (admin_id, role_id),
    CONSTRAINT fk_admin_roles_admin
        FOREIGN KEY (admin_id) REFERENCES admins(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_admin_roles_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id BIGINT UNSIGNED NULL,
    action VARCHAR(50) NOT NULL,
    target_type VARCHAR(50) NULL,
    target_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_admin_logs_admin
        FOREIGN KEY (admin_id) REFERENCES admins(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sites (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    site_code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS menus (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    site_id BIGINT UNSIGNED NOT NULL,
    parent_id BIGINT UNSIGNED NULL,
    name VARCHAR(100) NOT NULL,
    menu_type ENUM('page', 'board', 'link') NOT NULL DEFAULT 'page',
    target_id BIGINT UNSIGNED NULL,
    link_url VARCHAR(255) NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    is_visible TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_menus_site_id (site_id),
    INDEX idx_menus_parent_id (parent_id),
    CONSTRAINT fk_menus_site
        FOREIGN KEY (site_id) REFERENCES sites(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_menus_parent
        FOREIGN KEY (parent_id) REFERENCES menus(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (name, description)
VALUES
    ('super_admin', '최고관리자'),
    ('site_admin', '사이트관리자'),
    ('content_admin', '콘텐츠관리자')
ON DUPLICATE KEY UPDATE
    description = VALUES(description);

INSERT INTO admins (username, password_hash, name, email, status)
VALUES (
    'admin',
    '$2y$10$D7qxiOUOWZjiGgESEkkU.uZ7d0oXxM9WmfS4lbTGcE3ZT.flYqn9K',
    '기본 관리자',
    'admin@example.com',
    'active'
)
ON DUPLICATE KEY UPDATE
    password_hash = VALUES(password_hash),
    name = VALUES(name),
    email = VALUES(email),
    status = VALUES(status);

INSERT INTO admin_roles (admin_id, role_id)
SELECT a.id, r.id
FROM admins a
JOIN roles r ON r.name = 'super_admin'
WHERE a.username = 'admin'
ON DUPLICATE KEY UPDATE
    role_id = VALUES(role_id);

INSERT INTO sites (site_code, name, description, status)
VALUES
    ('main', '대표 홈페이지', '대학 대표 홈페이지입니다.', 'active'),
    ('admission', '입학처', '입학 안내 콘텐츠를 관리하는 부속 홈페이지입니다.', 'active'),
    ('library', '도서관', '도서관 공지사항과 자료 안내를 관리하는 부속 홈페이지입니다.', 'inactive')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    description = VALUES(description),
    status = VALUES(status);
