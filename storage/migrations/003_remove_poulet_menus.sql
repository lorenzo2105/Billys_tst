-- ============================================================
-- Billy's Fast Food - Migration 003: Remove Poulet & Menus
-- Removes old categories and their products
-- ============================================================

-- Delete products in Poulet category (id=3)
DELETE FROM product_options WHERE product_id IN (SELECT id FROM products WHERE category_id = 3);
DELETE FROM product_restaurant WHERE product_id IN (SELECT id FROM products WHERE category_id = 3);
DELETE FROM products WHERE category_id = 3;

-- Delete products in Menus category (id=4)
DELETE FROM product_options WHERE product_id IN (SELECT id FROM products WHERE category_id = 4);
DELETE FROM product_restaurant WHERE product_id IN (SELECT id FROM products WHERE category_id = 4);
DELETE FROM products WHERE category_id = 4;

-- Delete the categories
DELETE FROM categories WHERE id IN (3, 4);
