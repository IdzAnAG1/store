SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS roles (
    role_id     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_name   VARCHAR(50) NOT NULL UNIQUE,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
    user_id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id            BIGINT UNSIGNED NULL,
    username           VARCHAR(50)  NOT NULL UNIQUE,
    email              VARCHAR(255) NOT NULL UNIQUE,
    password_hash      VARCHAR(255) NOT NULL,
    phone              VARCHAR(30),
    address            TEXT,
    registration_date  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_users_role (role_id),
    CONSTRAINT fk_users_role
    FOREIGN KEY (role_id)
    REFERENCES roles (role_id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categories (
    category_id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name       VARCHAR(150) NOT NULL,
    description         TEXT,
    parent_category_id  BIGINT UNSIGNED NULL,

    INDEX idx_categories_parent (parent_category_id),
    CONSTRAINT fk_categories_parent
    FOREIGN KEY (parent_category_id)
    REFERENCES categories (category_id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    product_id      BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(250) NOT NULL,
    description     TEXT,
    price           DECIMAL(12,2) NOT NULL,
    category_id     BIGINT UNSIGNED NULL,
    stock_quantity  BIGINT UNSIGNED NOT NULL DEFAULT 0,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_products_category (category_id),
    CONSTRAINT fk_products_category
    FOREIGN KEY (category_id)
    REFERENCES categories (category_id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS product_attributes (
    attribute_id    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id      BIGINT UNSIGNED NOT NULL,
    attribute_name  VARCHAR(150) NOT NULL,
    value           VARCHAR(250) NOT NULL,

    INDEX idx_product_attributes_product (product_id),
    CONSTRAINT fk_product_attributes_product
    FOREIGN KEY (product_id)
    REFERENCES products (product_id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    UNIQUE KEY uniq_product_attr (product_id, attribute_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
