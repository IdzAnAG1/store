SELECT
    u.user_id,
    u.username,
    u.email,
    r.role_name
FROM users AS u
         JOIN roles AS r
              ON r.role_id = u.role_id
WHERE r.role_name IN ('client','manager')
  AND u.username LIKE 'd%';

SELECT p.product_id,p.name,c.category_name,p.price,pa.value AS color
FROM products p
         JOIN categories c ON c.category_id=p.category_id
         JOIN product_attributes pa ON pa.product_id=p.product_id AND pa.attribute_name='color'
WHERE pa.value='black' AND p.price>=500;

SELECT c.category_id,c.category_name,
       COUNT(p.product_id) AS products_count,
       COALESCE(SUM(p.stock_quantity),0) AS total_stock
FROM categories c
         LEFT JOIN products p ON p.category_id=c.category_id
GROUP BY c.category_id,c.category_name
HAVING COUNT(p.product_id)>=1 AND COALESCE(SUM(p.stock_quantity),0)>0;

/*
Вывод в консоли
---
user_id username        email                   role_name
1       demo            demo@example.com        client
---

---
product_id      name            category_name   price   color
1               iPhone 14       Phones          799.00  black
---

---
category_id     category_name   products_count  total_stock
2               Phones                  2           25
3               Laptops                 1           5
---
*/




