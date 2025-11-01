-- Заполнение таблиц языком SQL

START TRANSACTION;

INSERT INTO roles (role_name) VALUES ('client')
    ON DUPLICATE KEY UPDATE role_id = LAST_INSERT_ID(role_id);
SET @role_client := LAST_INSERT_ID();

INSERT INTO roles (role_name) VALUES ('admin')
    ON DUPLICATE KEY UPDATE role_id = LAST_INSERT_ID(role_id);
SET @role_admin := LAST_INSERT_ID();

INSERT INTO roles (role_name) VALUES ('manager')
    ON DUPLICATE KEY UPDATE role_id = LAST_INSERT_ID(role_id);
SET @role_manager := LAST_INSERT_ID();

INSERT INTO users (role_id, username, email, password_hash, phone, address)
VALUES (@role_client,  'demo',   'demo@example.com',   '$2y$10$demo_hash_1', '70000000001', 'Demo street, 1')
    ON DUPLICATE KEY UPDATE user_id = LAST_INSERT_ID(user_id);
SET @user_demo := LAST_INSERT_ID();

INSERT INTO users (role_id, username, email, password_hash, phone, address)
VALUES (@role_admin,   'admin',  'admin@example.com',  '$2y$10$demo_hash_2', '70000000002', 'Admin ave, 2')
    ON DUPLICATE KEY UPDATE user_id = LAST_INSERT_ID(user_id);
SET @user_admin := LAST_INSERT_ID();

INSERT INTO users (role_id, username, email, password_hash, phone, address)
VALUES (@role_manager,'manager','manager@example.com','$2y$10$demo_hash_3', '70000000003', 'Manager blvd, 3')
    ON DUPLICATE KEY UPDATE user_id = LAST_INSERT_ID(user_id);
SET @user_manager := LAST_INSERT_ID();


INSERT INTO categories (category_name, description, parent_category_id)
SELECT 'Electronics', 'Electronic goods', NULL
    WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name='Electronics');

SELECT category_id INTO @cat_electronics
FROM categories WHERE category_name='Electronics' LIMIT 1;

INSERT INTO categories (category_name, description, parent_category_id)
SELECT 'Phones', 'Smartphones and accessories', @cat_electronics
    WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name='Phones');

SELECT category_id INTO @cat_phones
FROM categories WHERE category_name='Phones' LIMIT 1;

INSERT INTO categories (category_name, description, parent_category_id)
SELECT 'Laptops', 'Laptops and notebooks', @cat_electronics
    WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name='Laptops');

SELECT category_id INTO @cat_laptops
FROM categories WHERE category_name='Laptops' LIMIT 1;

INSERT INTO products (name, description, price, category_id, stock_quantity)
SELECT 'iPhone 14', 'Apple smartphone', 799.00, @cat_phones, 10
    WHERE NOT EXISTS (SELECT 1 FROM products WHERE name='iPhone 14');

SELECT product_id INTO @prod_iphone
FROM products WHERE name='iPhone 14' LIMIT 1;

INSERT INTO products (name, description, price, category_id, stock_quantity)
SELECT 'Galaxy S23', 'Samsung smartphone', 749.00, @cat_phones, 15
    WHERE NOT EXISTS (SELECT 1 FROM products WHERE name='Galaxy S23');

SELECT product_id INTO @prod_galaxy
FROM products WHERE name='Galaxy S23' LIMIT 1;

INSERT INTO products (name, description, price, category_id, stock_quantity)
SELECT 'Dell XPS 13', 'Ultrabook', 1199.00, @cat_laptops, 5
    WHERE NOT EXISTS (SELECT 1 FROM products WHERE name='Dell XPS 13');

SELECT product_id INTO @prod_xps
FROM products WHERE name='Dell XPS 13' LIMIT 1;

INSERT INTO product_attributes (product_id, attribute_name, value)
VALUES (@prod_iphone, 'color', 'black')
    ON DUPLICATE KEY UPDATE attribute_id = LAST_INSERT_ID(attribute_id);

INSERT INTO product_attributes (product_id, attribute_name, value)
VALUES (@prod_galaxy, 'color', 'white')
    ON DUPLICATE KEY UPDATE attribute_id = LAST_INSERT_ID(attribute_id);

INSERT INTO product_attributes (product_id, attribute_name, value)
VALUES (@prod_xps, 'ram', '16GB')
    ON DUPLICATE KEY UPDATE attribute_id = LAST_INSERT_ID(attribute_id);

COMMIT;
