-- ============================================================
-- Billy's Fast Food - Migration 004: New Menu Structure
-- Adds new categories and products based on menu images
-- ============================================================

-- ── New Categories ──────────────────────────────────────────
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_active`, `sort_order`) VALUES
(5, 'Sandwichs', 'sandwichs', 'Nos sandwichs chauds', 1, 30),
(6, 'Accompagnements', 'accompagnements', 'Frites, extras et plus', 1, 40),
(7, 'Boissons', 'boissons', 'Boissons fraîches', 1, 50),
(8, 'Desserts', 'desserts', 'Desserts gourmands', 1, 60),
(9, 'Kids Menu', 'kids-menu', 'Menu enfant', 1, 70);

-- ── Update existing Burgers ─────────────────────────────────
UPDATE `categories` SET `sort_order` = 10 WHERE `id` = 1;
UPDATE `categories` SET `name` = 'Accompagnements', `slug` = 'accompagnements-frites', `sort_order` = 41 WHERE `id` = 2;

-- ── New Burger Products ─────────────────────────────────────
-- Keep existing burgers (1-5) and add new ones
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `status`, `is_featured`, `sort_order`) VALUES
(20, 1, 'Cheeseburger Bacon', 'cheeseburger-bacon', 'Burger avec bacon croustillant', 8.90, NULL, 'available', 0, 30),
(21, 1, 'Brooklyn Rocks', 'brooklyn-rocks', 'Le burger signature de Brooklyn', 10.90, NULL, 'available', 1, 40),
(22, 1, 'Cheesy Cheddar', 'cheesy-cheddar', 'Burger au cheddar fondant', 9.90, NULL, 'available', 0, 50),
(23, 1, 'Cheese Steak', 'cheese-steak', 'Steak haché et fromage fondu', 10.50, NULL, 'available', 0, 60),
(24, 1, 'Wild Mushroom', 'wild-mushroom', 'Burger aux champignons sauvages', 9.90, NULL, 'available', 0, 70),
(25, 1, 'Billy Chicken', 'billy-chicken', 'Burger poulet croustillant', 9.50, NULL, 'available', 0, 80),
(26, 1, 'Balance Chicken', 'balance-chicken', 'Burger poulet équilibré', 9.50, NULL, 'available', 0, 90);

-- ── Sandwichs ───────────────────────────────────────────────
INSERT INTO `products` (`category_id`, `name`, `slug`, `description`, `price`, `image`, `status`, `is_featured`, `sort_order`) VALUES
(5, 'Chicken Panini', 'chicken-panini', 'Panini au poulet grillé', 7.90, NULL, 'available', 0, 10),
(5, 'Steak Panini', 'steak-panini', 'Panini au steak', 8.50, NULL, 'available', 0, 20),
(5, 'Philly Cheese Steak', 'philly-cheese-steak', 'Le classique de Philadelphie', 9.90, NULL, 'available', 1, 30),
(5, 'Chicken Quesadilla', 'chicken-quesadilla', 'Quesadilla au poulet', 8.90, NULL, 'available', 0, 40),
(5, 'Beef Quesadilla', 'beef-quesadilla', 'Quesadilla au bœuf', 9.50, NULL, 'available', 0, 50);

-- ── Accompagnements ─────────────────────────────────────────
INSERT INTO `products` (`category_id`, `name`, `slug`, `description`, `price`, `image`, `status`, `is_featured`, `sort_order`) VALUES
(6, 'Cheesy Box', 'cheesy-box', 'Box de fromage fondu', 5.90, NULL, 'available', 0, 10),
(6, 'Beesy Fries', 'beesy-fries', 'Frites spéciales', 4.50, NULL, 'available', 0, 20),
(6, 'Super Fries', 'super-fries', 'Frites XXL', 3.90, NULL, 'available', 0, 30),
(6, 'Cheese Fries', 'cheese-fries', 'Frites au fromage', 4.90, NULL, 'available', 1, 40),
(6, 'Sweet Potato Fries', 'sweet-potato-fries', 'Frites de patate douce', 4.90, NULL, 'available', 0, 50);

-- ── Boissons ────────────────────────────────────────────────
INSERT INTO `products` (`category_id`, `name`, `slug`, `description`, `price`, `image`, `status`, `is_featured`, `sort_order`) VALUES
(7, 'Coca-Cola 33cl', 'coca-cola-33', 'Coca-Cola canette', 2.50, NULL, 'available', 0, 10),
(7, 'Coca-Cola 50cl', 'coca-cola-50', 'Coca-Cola bouteille', 3.50, NULL, 'available', 0, 20),
(7, 'Sprite 33cl', 'sprite-33', 'Sprite canette', 2.50, NULL, 'available', 0, 30),
(7, 'Fanta 33cl', 'fanta-33', 'Fanta canette', 2.50, NULL, 'available', 0, 40),
(7, 'Eau 50cl', 'eau-50', 'Eau minérale', 2.00, NULL, 'available', 0, 50);

-- ── Desserts ────────────────────────────────────────────────
INSERT INTO `products` (`category_id`, `name`, `slug`, `description`, `price`, `image`, `status`, `is_featured`, `sort_order`) VALUES
(8, 'Brownie', 'brownie', 'Brownie au chocolat', 3.90, NULL, 'available', 0, 10),
(8, 'Cookie', 'cookie', 'Cookie maison', 2.90, NULL, 'available', 0, 20),
(8, 'Cheesecake', 'cheesecake', 'Cheesecake New York', 4.90, NULL, 'available', 0, 30);

-- ── Kids Menu ───────────────────────────────────────────────
INSERT INTO `products` (`category_id`, `name`, `slug`, `description`, `price`, `image`, `status`, `is_featured`, `sort_order`) VALUES
(9, 'Kids Burger', 'kids-burger', 'Mini burger + frites + boisson', 6.90, NULL, 'available', 0, 10),
(9, 'Kids Nuggets', 'kids-nuggets', 'Nuggets + frites + boisson', 6.50, NULL, 'available', 0, 20);

-- ── Add products to restaurant 1 ────────────────────────────
INSERT INTO `product_restaurant` (`product_id`, `restaurant_id`, `is_available`, `stock_status`)
SELECT id, 1, 1, 'in_stock' FROM products WHERE id >= 20;

-- ── Add viande options to new burgers ───────────────────────
INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`, `option_type`, `is_active`)
SELECT id, 'Simple (1 steak)', 0.00, 'viande', 'radio', 1 FROM products WHERE id IN (20,21,22,23,24);

INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`, `option_type`, `is_active`)
SELECT id, 'Double (2 steaks)', 3.00, 'viande', 'radio', 1 FROM products WHERE id IN (20,21,22,23,24);

INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`, `option_type`, `is_active`)
SELECT id, 'Simple (1 filet)', 0.00, 'viande', 'radio', 1 FROM products WHERE id IN (25,26);

INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`, `option_type`, `is_active`)
SELECT id, 'Double (2 filets)', 3.00, 'viande', 'radio', 1 FROM products WHERE id IN (25,26);

-- ── Add taille_menu options to new burgers ──────────────────
INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`, `option_type`, `is_active`)
SELECT id, 'Burger seul', 0.00, 'taille_menu', 'radio', 1 FROM products WHERE id BETWEEN 20 AND 26;

INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`, `option_type`, `is_active`)
SELECT id, 'Menu M (Frites M + Boisson 33cl)', 3.50, 'taille_menu', 'radio', 1 FROM products WHERE id BETWEEN 20 AND 26;

INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`, `option_type`, `is_active`)
SELECT id, 'Menu L (Frites L + Boisson 50cl)', 5.50, 'taille_menu', 'radio', 1 FROM products WHERE id BETWEEN 20 AND 26;
