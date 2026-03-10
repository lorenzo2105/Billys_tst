-- ============================================================
-- Billy's Fast Food - Database Schema
-- Run this file in phpMyAdmin or MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS `billys_fastfood`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `billys_fastfood`;

-- ── Users ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `address` TEXT DEFAULT NULL,
    `role` ENUM('admin', 'kitchen', 'client') NOT NULL DEFAULT 'client',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB;

-- ── Restaurants (Points de vente) ──────────────────────────
CREATE TABLE IF NOT EXISTS `restaurants` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `opening_hours` VARCHAR(100) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `image` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Categories ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Products ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `image` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('available', 'unavailable', 'out_of_stock') NOT NULL DEFAULT 'available',
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
    INDEX `idx_products_status` (`status`),
    INDEX `idx_products_category` (`category_id`)
) ENGINE=InnoDB;

-- ── Product availability per restaurant ─────────────────────
CREATE TABLE IF NOT EXISTS `product_restaurant` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `restaurant_id` INT UNSIGNED NOT NULL,
    `is_available` TINYINT(1) NOT NULL DEFAULT 1,
    `stock_status` ENUM('in_stock', 'low_stock', 'out_of_stock') NOT NULL DEFAULT 'in_stock',
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_product_restaurant` (`product_id`, `restaurant_id`)
) ENGINE=InnoDB;

-- ── Product Options (taille, suppléments, etc.) ─────────────
CREATE TABLE IF NOT EXISTS `product_options` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `price_modifier` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `option_group` VARCHAR(50) DEFAULT 'default',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_options_product` (`product_id`)
) ENGINE=InnoDB;

-- ── Orders ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `restaurant_id` INT UNSIGNED NOT NULL,
    `order_number` VARCHAR(20) NOT NULL UNIQUE,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(20) DEFAULT NULL,
    `customer_email` VARCHAR(150) DEFAULT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `tax` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('new', 'preparing', 'ready', 'completed', 'cancelled') NOT NULL DEFAULT 'new',
    `notes` TEXT DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants`(`id`) ON DELETE CASCADE,
    INDEX `idx_orders_status` (`status`),
    INDEX `idx_orders_restaurant` (`restaurant_id`),
    INDEX `idx_orders_user` (`user_id`),
    INDEX `idx_orders_number` (`order_number`)
) ENGINE=InnoDB;

-- ── Order Items ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
    `unit_price` DECIMAL(8,2) NOT NULL,
    `options_json` JSON DEFAULT NULL,
    `options_price` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `line_total` DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_items_order` (`order_id`)
) ENGINE=InnoDB;

-- ── Order Status History ────────────────────────────────────
CREATE TABLE IF NOT EXISTS `order_status_history` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `status` ENUM('new', 'preparing', 'ready', 'completed', 'cancelled') NOT NULL,
    `changed_by` INT UNSIGNED DEFAULT NULL,
    `note` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`changed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_status_order` (`order_id`)
) ENGINE=InnoDB;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin user (password: admin123)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Administrateur', 'admin@billys.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Kitchen user (password: kitchen123)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Cuisine', 'cuisine@billys.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kitchen');

-- Test client (password: client123)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Jean Dupont', 'client@billys.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client');

-- 3 Restaurants
INSERT INTO `restaurants` (`name`, `address`, `phone`, `opening_hours`, `is_active`) VALUES
("Billy's Centre-Ville", '12 Rue de la Paix, 75001 Paris', '01 23 45 67 89', '11h00 - 23h00', 1),
("Billy's Gare du Nord", '45 Boulevard de Magenta, 75010 Paris', '01 98 76 54 32', '10h00 - 00h00', 1),
("Billy's La Défense", '8 Parvis de la Défense, 92800 Puteaux', '01 55 66 77 88', '11h00 - 22h00', 1);

-- Categories
INSERT INTO `categories` (`name`, `slug`, `description`, `sort_order`, `is_active`) VALUES
('Burgers', 'burgers', 'Nos burgers signature préparés avec amour', 1, 1),
('Poulet', 'poulet', 'Poulet croustillant et tenders', 2, 1),
('Accompagnements', 'accompagnements', 'Frites, onion rings et plus', 3, 1),
('Boissons', 'boissons', 'Sodas, jus et milkshakes', 4, 1),
('Desserts', 'desserts', 'Pour finir en douceur', 5, 1),
('Menus', 'menus', 'Nos formules complètes', 6, 1);

-- Products
INSERT INTO `products` (`category_id`, `name`, `slug`, `description`, `price`, `status`, `is_featured`, `sort_order`) VALUES
(1, 'Classic Burger', 'classic-burger', 'Steak haché 150g, salade, tomate, oignon, sauce maison', 8.90, 'available', 1, 1),
(1, 'Cheese Burger', 'cheese-burger', 'Steak haché 150g, double cheddar fondu, cornichons, sauce Billy', 9.90, 'available', 1, 2),
(1, 'Double Smash', 'double-smash', 'Double steak smashé, cheddar, oignons caramélisés, sauce fumée', 12.90, 'available', 1, 3),
(1, 'Chicken Burger', 'chicken-burger', 'Filet de poulet pané croustillant, salade, mayo épicée', 10.50, 'available', 0, 4),
(1, 'Veggie Burger', 'veggie-burger', 'Steak végétal, avocat, roquette, sauce yaourt', 10.90, 'available', 0, 5),
(2, 'Tenders x5', 'tenders-5', '5 tenders de poulet croustillants avec sauce au choix', 7.90, 'available', 1, 1),
(2, 'Tenders x10', 'tenders-10', '10 tenders de poulet avec 2 sauces au choix', 13.90, 'available', 0, 2),
(2, 'Wings x6', 'wings-6', '6 ailes de poulet marinées, cuites au four', 8.50, 'available', 0, 3),
(3, 'Frites Classiques', 'frites-classiques', 'Frites dorées et croustillantes', 3.50, 'available', 0, 1),
(3, 'Frites Loaded', 'frites-loaded', 'Frites garnies cheddar, bacon, oignons frits', 6.90, 'available', 1, 2),
(3, 'Onion Rings', 'onion-rings', 'Rondelles d''oignon panées et croustillantes', 4.50, 'available', 0, 3),
(3, 'Coleslaw', 'coleslaw', 'Salade de chou maison crémeuse', 3.00, 'available', 0, 4),
(4, 'Coca-Cola 33cl', 'coca-cola-33', 'Coca-Cola classique', 2.50, 'available', 0, 1),
(4, 'Sprite 33cl', 'sprite-33', 'Sprite citron', 2.50, 'available', 0, 2),
(4, 'Eau Minérale 50cl', 'eau-minerale-50', 'Eau minérale naturelle', 1.90, 'available', 0, 3),
(4, 'Milkshake Vanille', 'milkshake-vanille', 'Milkshake crémeux à la vanille', 5.50, 'available', 1, 4),
(4, 'Milkshake Chocolat', 'milkshake-chocolat', 'Milkshake onctueux au chocolat', 5.50, 'available', 0, 5),
(5, 'Cookie Chocolat', 'cookie-chocolat', 'Cookie géant aux pépites de chocolat', 2.90, 'available', 0, 1),
(5, 'Brownie', 'brownie', 'Brownie fondant au chocolat noir', 3.50, 'available', 1, 2),
(5, 'Sundae Caramel', 'sundae-caramel', 'Glace vanille, sauce caramel, chantilly', 4.90, 'available', 0, 3),
(6, 'Menu Classic', 'menu-classic', 'Burger Classic + Frites + Boisson 33cl', 12.90, 'available', 1, 1),
(6, 'Menu Cheese', 'menu-cheese', 'Cheese Burger + Frites + Boisson 33cl', 13.90, 'available', 1, 2),
(6, 'Menu Double Smash', 'menu-double-smash', 'Double Smash + Frites Loaded + Boisson 33cl', 17.90, 'available', 0, 3);

-- Product-Restaurant availability (all products available in all 3 restaurants)
INSERT INTO `product_restaurant` (`product_id`, `restaurant_id`, `is_available`, `stock_status`)
SELECT p.id, r.id, 1, 'in_stock'
FROM `products` p
CROSS JOIN `restaurants` r;

-- Product Options
INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`) VALUES
(1, 'Supplément Bacon', 1.50, 'supplements'),
(1, 'Supplément Oeuf', 1.00, 'supplements'),
(1, 'Supplément Cheddar', 1.00, 'supplements'),
(2, 'Supplément Bacon', 1.50, 'supplements'),
(2, 'Double Steak', 3.00, 'supplements'),
(3, 'Supplément Jalapeños', 0.80, 'supplements'),
(6, 'Sauce BBQ', 0.00, 'sauces'),
(6, 'Sauce Ranch', 0.00, 'sauces'),
(6, 'Sauce Sweet Chili', 0.00, 'sauces'),
(9, 'Petite', -0.50, 'taille'),
(9, 'Grande', 1.50, 'taille'),
(16, 'Taille M', 0.00, 'taille'),
(16, 'Taille L', 1.50, 'taille');
