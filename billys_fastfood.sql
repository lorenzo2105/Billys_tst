-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 21 mars 2026 à 23:32
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `billys_fastfood`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Burgers', 'burgers', 'Nos burgers signature préparés avec amour', NULL, 1, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(2, 'Poulet', 'poulet', 'Poulet croustillant et tenders', NULL, 2, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(3, 'Accompagnements', 'accompagnements', 'Frites, onion rings et plus', NULL, 3, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(4, 'Boissons', 'boissons', 'Sodas, jus et milkshakes', NULL, 4, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(5, 'Desserts', 'desserts', 'Pour finir en douceur', NULL, 5, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(6, 'Menus', 'menus', 'Nos formules complètes', NULL, 6, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46');

-- --------------------------------------------------------

--
-- Structure de la table `global_supplements`
--

DROP TABLE IF EXISTS `global_supplements`;
CREATE TABLE IF NOT EXISTS `global_supplements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `global_supplements`
--

INSERT INTO `global_supplements` (`id`, `name`, `price`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Fromage Cheddar', 0.50, 1, 1, '2026-03-21 23:22:40', '2026-03-21 23:22:40'),
(2, 'Bacon', 1.00, 1, 2, '2026-03-21 23:22:40', '2026-03-21 23:22:40'),
(3, 'Œuf', 0.80, 1, 3, '2026-03-21 23:22:40', '2026-03-21 23:22:40'),
(4, 'Avocat', 1.20, 1, 4, '2026-03-21 23:22:40', '2026-03-21 23:22:40'),
(5, 'Jalapeños', 0.50, 1, 5, '2026-03-21 23:22:40', '2026-03-21 23:22:40'),
(6, 'Oignons frits', 0.60, 1, 6, '2026-03-21 23:22:40', '2026-03-21 23:22:40'),
(7, 'Champignons', 0.70, 1, 7, '2026-03-21 23:22:40', '2026-03-21 23:22:40'),
(8, 'Cornichons', 0.30, 1, 8, '2026-03-21 23:22:40', '2026-03-21 23:22:40');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `restaurant_id` int UNSIGNED NOT NULL,
  `order_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('new','preparing','ready','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_restaurant` (`restaurant_id`),
  KEY `idx_orders_user` (`user_id`),
  KEY `idx_orders_number` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `product_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `unit_price` decimal(8,2) NOT NULL,
  `options_json` json DEFAULT NULL,
  `options_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `line_total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `idx_items_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
CREATE TABLE IF NOT EXISTS `order_status_history` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `status` enum('new','preparing','ready','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` int UNSIGNED DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `changed_by` (`changed_by`),
  KEY `idx_status_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `price_simple` decimal(8,2) DEFAULT NULL,
  `price_double` decimal(8,2) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','unavailable','out_of_stock') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_products_status` (`status`),
  KEY `idx_products_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `status`, `is_featured`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Classic Burger', 'classic-burger', 'Steak haché 150g, salade, tomate, oignon, sauce maison', 8.90, NULL, 'available', 1, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(2, 1, 'Cheese Burger', 'cheese-burger', 'Steak haché 150g, double cheddar fondu, cornichons, sauce Billy', 9.90, NULL, 'available', 1, 2, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(3, 1, 'Double Smash', 'double-smash', 'Double steak smashé, cheddar, oignons caramélisés, sauce fumée', 12.90, NULL, 'available', 1, 3, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(4, 1, 'Chicken Burger', 'chicken-burger', 'Filet de poulet pané croustillant, salade, mayo épicée', 10.50, NULL, 'available', 0, 4, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(5, 1, 'Veggie Burger', 'veggie-burger', 'Steak végétal, avocat, roquette, sauce yaourt', 10.90, NULL, 'available', 0, 5, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(6, 2, 'Tenders x5', 'tenders-5', '5 tenders de poulet croustillants avec sauce au choix', 7.90, NULL, 'available', 1, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(7, 2, 'Tenders x10', 'tenders-10', '10 tenders de poulet avec 2 sauces au choix', 13.90, NULL, 'available', 0, 2, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(8, 2, 'Wings x6', 'wings-6', '6 ailes de poulet marinées, cuites au four', 8.50, NULL, 'available', 0, 3, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(9, 3, 'Frites Classiques', 'frites-classiques', 'Frites dorées et croustillantes', 3.50, NULL, 'available', 0, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(10, 3, 'Frites Loaded', 'frites-loaded', 'Frites garnies cheddar, bacon, oignons frits', 6.90, NULL, 'available', 1, 2, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(11, 3, 'Onion Rings', 'onion-rings', 'Rondelles d\'oignon panées et croustillantes', 4.50, NULL, 'available', 0, 3, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(12, 3, 'Coleslaw', 'coleslaw', 'Salade de chou maison crémeuse', 3.00, NULL, 'available', 0, 4, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(13, 4, 'Coca-Cola 33cl', 'coca-cola-33', 'Coca-Cola classique', 2.50, NULL, 'available', 0, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(14, 4, 'Sprite 33cl', 'sprite-33', 'Sprite citron', 2.50, NULL, 'available', 0, 2, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(15, 4, 'Eau Minérale 50cl', 'eau-minerale-50', 'Eau minérale naturelle', 1.90, NULL, 'available', 0, 3, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(16, 4, 'Milkshake Vanille', 'milkshake-vanille', 'Milkshake crémeux à la vanille', 5.50, NULL, 'available', 1, 4, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(17, 4, 'Milkshake Chocolat', 'milkshake-chocolat', 'Milkshake onctueux au chocolat', 5.50, NULL, 'available', 0, 5, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(18, 5, 'Cookie Chocolat', 'cookie-chocolat', 'Cookie géant aux pépites de chocolat', 2.90, NULL, 'available', 0, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(19, 5, 'Brownie', 'brownie', 'Brownie fondant au chocolat noir', 3.50, NULL, 'available', 1, 2, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(20, 5, 'Sundae Caramel', 'sundae-caramel', 'Glace vanille, sauce caramel, chantilly', 4.90, NULL, 'available', 0, 3, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(21, 6, 'Menu Classic', 'menu-classic', 'Burger Classic + Frites + Boisson 33cl', 12.90, NULL, 'available', 1, 1, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(22, 6, 'Menu Cheese', 'menu-cheese', 'Cheese Burger + Frites + Boisson 33cl', 13.90, NULL, 'available', 1, 2, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(23, 6, 'Menu Double Smash', 'menu-double-smash', 'Double Smash + Frites Loaded + Boisson 33cl', 17.90, NULL, 'available', 0, 3, '2026-03-02 22:44:46', '2026-03-02 22:44:46');

-- --------------------------------------------------------

--
-- Structure de la table `product_options`
--

DROP TABLE IF EXISTS `product_options`;
CREATE TABLE IF NOT EXISTS `product_options` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_modifier` decimal(8,2) NOT NULL DEFAULT '0.00',
  `option_group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'default',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_options_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product_options`
--

INSERT INTO `product_options` (`id`, `product_id`, `name`, `price_modifier`, `option_group`, `is_active`) VALUES
(1, 1, 'Supplément Bacon', 1.50, 'supplements', 1),
(2, 1, 'Supplément Oeuf', 1.00, 'supplements', 1),
(3, 1, 'Supplément Cheddar', 1.00, 'supplements', 1),
(4, 2, 'Supplément Bacon', 1.50, 'supplements', 1),
(5, 2, 'Double Steak', 3.00, 'supplements', 1),
(6, 3, 'Supplément Jalapeños', 0.80, 'supplements', 1),
(7, 6, 'Sauce BBQ', 0.00, 'sauces', 1),
(8, 6, 'Sauce Ranch', 0.00, 'sauces', 1),
(9, 6, 'Sauce Sweet Chili', 0.00, 'sauces', 1),
(10, 9, 'Petite', -0.50, 'taille', 1),
(11, 9, 'Grande', 1.50, 'taille', 1),
(12, 16, 'Taille M', 0.00, 'taille', 1),
(13, 16, 'Taille L', 1.50, 'taille', 1);

-- --------------------------------------------------------

--
-- Structure de la table `product_restaurant`
--

DROP TABLE IF EXISTS `product_restaurant`;
CREATE TABLE IF NOT EXISTS `product_restaurant` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `restaurant_id` int UNSIGNED NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `stock_status` enum('in_stock','low_stock','out_of_stock') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_stock',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_product_restaurant` (`product_id`,`restaurant_id`),
  KEY `restaurant_id` (`restaurant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product_restaurant`
--

INSERT INTO `product_restaurant` (`id`, `product_id`, `restaurant_id`, `is_available`, `stock_status`) VALUES
(1, 1, 3, 1, 'in_stock'),
(2, 1, 2, 1, 'in_stock'),
(3, 1, 1, 1, 'in_stock'),
(4, 2, 3, 1, 'in_stock'),
(5, 2, 2, 1, 'in_stock'),
(6, 2, 1, 1, 'in_stock'),
(7, 3, 3, 1, 'in_stock'),
(8, 3, 2, 1, 'in_stock'),
(9, 3, 1, 1, 'in_stock'),
(10, 4, 3, 1, 'in_stock'),
(11, 4, 2, 1, 'in_stock'),
(12, 4, 1, 1, 'in_stock'),
(13, 5, 3, 1, 'in_stock'),
(14, 5, 2, 1, 'in_stock'),
(15, 5, 1, 1, 'in_stock'),
(16, 6, 3, 1, 'in_stock'),
(17, 6, 2, 1, 'in_stock'),
(18, 6, 1, 1, 'in_stock'),
(19, 7, 3, 1, 'in_stock'),
(20, 7, 2, 1, 'in_stock'),
(21, 7, 1, 1, 'in_stock'),
(22, 8, 3, 1, 'in_stock'),
(23, 8, 2, 1, 'in_stock'),
(24, 8, 1, 1, 'in_stock'),
(25, 9, 3, 1, 'in_stock'),
(26, 9, 2, 1, 'in_stock'),
(27, 9, 1, 1, 'in_stock'),
(28, 10, 3, 1, 'in_stock'),
(29, 10, 2, 1, 'in_stock'),
(30, 10, 1, 1, 'in_stock'),
(31, 11, 3, 1, 'in_stock'),
(32, 11, 2, 1, 'in_stock'),
(33, 11, 1, 1, 'in_stock'),
(34, 12, 3, 1, 'in_stock'),
(35, 12, 2, 1, 'in_stock'),
(36, 12, 1, 1, 'in_stock'),
(37, 13, 3, 1, 'in_stock'),
(38, 13, 2, 1, 'in_stock'),
(39, 13, 1, 1, 'in_stock'),
(40, 14, 3, 1, 'in_stock'),
(41, 14, 2, 1, 'in_stock'),
(42, 14, 1, 1, 'in_stock'),
(43, 15, 3, 1, 'in_stock'),
(44, 15, 2, 1, 'in_stock'),
(45, 15, 1, 1, 'in_stock'),
(46, 16, 3, 1, 'in_stock'),
(47, 16, 2, 1, 'in_stock'),
(48, 16, 1, 1, 'in_stock'),
(49, 17, 3, 1, 'in_stock'),
(50, 17, 2, 1, 'in_stock'),
(51, 17, 1, 1, 'in_stock'),
(52, 18, 3, 1, 'in_stock'),
(53, 18, 2, 1, 'in_stock'),
(54, 18, 1, 1, 'in_stock'),
(55, 19, 3, 1, 'in_stock'),
(56, 19, 2, 1, 'in_stock'),
(57, 19, 1, 1, 'in_stock'),
(58, 20, 3, 1, 'in_stock'),
(59, 20, 2, 1, 'in_stock'),
(60, 20, 1, 1, 'in_stock'),
(61, 21, 3, 1, 'in_stock'),
(62, 21, 2, 1, 'in_stock'),
(63, 21, 1, 1, 'in_stock'),
(64, 22, 3, 1, 'in_stock'),
(65, 22, 2, 1, 'in_stock'),
(66, 22, 1, 1, 'in_stock'),
(67, 23, 3, 1, 'in_stock'),
(68, 23, 2, 1, 'in_stock'),
(69, 23, 1, 1, 'in_stock');

-- --------------------------------------------------------

--
-- Structure de la table `product_supplements`
--

DROP TABLE IF EXISTS `product_supplements`;
CREATE TABLE IF NOT EXISTS `product_supplements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `supplement_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_product_supplement` (`product_id`,`supplement_id`),
  KEY `supplement_id` (`supplement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `restaurants`
--

DROP TABLE IF EXISTS `restaurants`;
CREATE TABLE IF NOT EXISTS `restaurants` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_hours` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `address`, `phone`, `opening_hours`, `is_active`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Billy\'s Centre-Ville', '12 Rue de la Paix, 75001 Paris', '01 23 45 67 89', '11h00 - 23h00', 1, NULL, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(2, 'Billy\'s Gare du Nord', '45 Boulevard de Magenta, 75010 Paris', '01 98 76 54 32', '10h00 - 00h00', 1, NULL, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(3, 'Billy\'s La Défense', '8 Parvis de la Défense, 92800 Puteaux', '01 55 66 77 88', '11h00 - 22h00', 1, NULL, '2026-03-02 22:44:46', '2026-03-02 22:44:46'),
(4, 'Billy\'s Centre-Ville', '12 Rue de la Paix, 75001 Paris', '01 23 45 67 89', '11h00 - 23h00', 1, NULL, '2026-03-22 10:22:40', '2026-03-22 10:22:40'),
(5, 'Billy\'s Gare du Nord', '45 Boulevard de Magenta, 75010 Paris', '01 98 76 54 32', '10h00 - 00h00', 1, NULL, '2026-03-22 10:22:40', '2026-03-22 10:22:40'),
(6, 'Billy\'s La Défense', '8 Parvis de la Défense, 92800 Puteaux', '01 55 66 77 88', '11h00 - 22h00', 1, NULL, '2026-03-22 10:22:40', '2026-03-22 10:22:40');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `role` enum('admin','kitchen','client') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur', 'admin@billys.com', '$2y$10$ugAYQMypM9eSYSc575FwMulQwpnCX1eXArAdJi/kKv.Hn7akCM4d.', NULL, NULL, 'admin', 1, '2026-03-02 22:44:46', '2026-03-22 10:22:40'),
(2, 'Cuisine', 'cuisine@billys.com', '$2y$10$ugAYQMypM9eSYSc575FwMulQwpnCX1eXArAdJi/kKv.Hn7akCM4d.', NULL, NULL, 'kitchen', 1, '2026-03-02 22:44:46', '2026-03-22 10:22:40'),
(3, 'Jean Dupont', 'client@billys.com', '$2y$10$ugAYQMypM9eSYSc575FwMulQwpnCX1eXArAdJi/kKv.Hn7akCM4d.', NULL, NULL, 'client', 1, '2026-03-02 22:44:46', '2026-03-22 10:22:40');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_status_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `product_options`
--
ALTER TABLE `product_options`
  ADD CONSTRAINT `product_options_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `product_restaurant`
--
ALTER TABLE `product_restaurant`
  ADD CONSTRAINT `product_restaurant_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_restaurant_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `product_supplements`
--
ALTER TABLE `product_supplements`
  ADD CONSTRAINT `product_supplements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_supplements_ibfk_2` FOREIGN KEY (`supplement_id`) REFERENCES `global_supplements` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
