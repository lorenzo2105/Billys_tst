-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 24 mars 2026 à 05:49
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

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
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(1, 'Burgers', 'burgers-4370', '', NULL, 1, 1, '2026-03-02 22:44:46', '2026-03-24 14:30:48'),
(3, 'Cheesy Box', 'cheesy-box-ce81', '', NULL, 3, 1, '2026-03-02 22:44:46', '2026-03-24 14:30:43'),
(4, 'Boissons', 'boissons-5b12', '', NULL, 4, 1, '2026-03-02 22:44:46', '2026-03-24 14:30:53'),
(5, 'Desserts', 'desserts-8a80', '', NULL, 5, 1, '2026-03-02 22:44:46', '2026-03-24 14:30:55');

-- --------------------------------------------------------

--
-- Structure de la table `global_supplements`
--

DROP TABLE IF EXISTS `global_supplements`;
CREATE TABLE IF NOT EXISTS `global_supplements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `global_supplements`
--

INSERT INTO `global_supplements` (`id`, `name`, `price`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Fromage Cheddar', 200.00, 1, 1, '2026-03-21 23:22:40', '2026-03-24 03:35:23'),
(2, 'Poitrine fumée', 250.00, 1, 2, '2026-03-21 23:22:40', '2026-03-24 03:35:13'),
(3, 'Œuf', 200.00, 1, 3, '2026-03-21 23:22:40', '2026-03-24 03:35:27'),
(4, 'Frite', 450.00, 1, 4, '2026-03-21 23:22:40', '2026-03-24 03:35:37'),
(5, 'Patate douce', 600.00, 1, 5, '2026-03-21 23:22:40', '2026-03-24 03:35:46'),
(6, 'Oignons rings', 600.00, 1, 6, '2026-03-21 23:22:40', '2026-03-24 03:34:29'),
(7, 'Poulet frit', 400.00, 1, 7, '2026-03-21 23:22:40', '2026-03-24 03:34:18'),
(8, 'Steack Haché', 350.00, 1, 8, '2026-03-21 23:22:40', '2026-03-24 03:34:56'),
(17, 'Mozarella Sticks', 600.00, 1, 0, '2026-03-24 03:36:02', '2026-03-24 03:36:02'),
(18, 'Sauces', 200.00, 1, 0, '2026-03-24 03:36:10', '2026-03-24 03:36:10');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED DEFAULT NULL,
  `restaurant_id` int UNSIGNED NOT NULL,
  `order_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('new','preparing','ready','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_restaurant` (`restaurant_id`),
  KEY `idx_orders_user` (`user_id`),
  KEY `idx_orders_number` (`order_number`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `restaurant_id`, `order_number`, `customer_name`, `customer_phone`, `customer_email`, `subtotal`, `tax`, `total`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'BF-3A1ECD-240425', 'Lorenzo', '', 'admin@billys.com', 3500.00, 350.00, 3850.00, 'completed', '', '2026-03-24 15:25:55', '2026-03-24 15:28:46'),
(2, 1, 2, 'BF-86B661-240429', 'Bob', '522205', 'admin@billys.com', 1600.00, 160.00, 1760.00, 'completed', '', '2026-03-24 15:29:12', '2026-03-24 15:35:59'),
(3, 1, 3, '2026-003', 'Lorenzo', '', 'admin@billys.com', 4200.00, 420.00, 4620.00, 'completed', '', '2026-03-24 15:35:25', '2026-03-24 15:35:56');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `product_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `unit_price` decimal(8,2) NOT NULL,
  `options_json` json DEFAULT NULL,
  `options_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `line_total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `idx_items_order` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `unit_price`, `options_json`, `options_price`, `line_total`) VALUES
(1, 1, 47, 'Smashburger', 1, 1050.00, '[\"Double 🥩🥩\", \"Menu M\"]', 800.00, 1850.00),
(2, 1, 67, 'Barbebill', 1, 1150.00, '[\"Simple 🥩\", \"Menu M\"]', 500.00, 1650.00),
(3, 2, 53, 'CheesyBox Fried Chicken', 1, 1600.00, '[]', 0.00, 1600.00),
(4, 3, 58, '50cl', 1, 450.00, '[]', 0.00, 450.00),
(5, 3, 47, 'Smashburger', 1, 1050.00, '[\"Simple 🥩\", \"Menu M\"]', 500.00, 1550.00),
(6, 3, 50, 'Big Bacon', 1, 1200.00, '[\"Double 🥩🥩\", \"Menu L\"]', 1000.00, 2200.00);

-- --------------------------------------------------------

--
-- Structure de la table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
CREATE TABLE IF NOT EXISTS `order_status_history` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int UNSIGNED NOT NULL,
  `status` enum('new','preparing','ready','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` int UNSIGNED DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `changed_by` (`changed_by`),
  KEY `idx_status_order` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_status_history`
--

INSERT INTO `order_status_history` (`id`, `order_id`, `status`, `changed_by`, `note`, `created_at`) VALUES
(1, 1, 'new', 1, NULL, '2026-03-24 15:25:55'),
(2, 1, 'preparing', 1, NULL, '2026-03-24 15:27:41'),
(3, 1, 'ready', 1, NULL, '2026-03-24 15:28:37'),
(4, 1, 'completed', 1, NULL, '2026-03-24 15:28:46'),
(5, 2, 'new', 1, NULL, '2026-03-24 15:29:12'),
(6, 3, 'new', 1, NULL, '2026-03-24 15:35:25'),
(7, 2, 'preparing', 1, NULL, '2026-03-24 15:35:45'),
(8, 3, 'preparing', 1, NULL, '2026-03-24 15:35:49'),
(9, 3, 'ready', 1, NULL, '2026-03-24 15:35:56'),
(10, 3, 'completed', 1, NULL, '2026-03-24 15:35:56'),
(11, 2, 'ready', 1, NULL, '2026-03-24 15:35:58'),
(12, 2, 'completed', 1, NULL, '2026-03-24 15:35:59');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `price_simple` decimal(8,2) DEFAULT NULL,
  `price_double` decimal(8,2) DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','unavailable','out_of_stock') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_products_status` (`status`),
  KEY `idx_products_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `price_simple`, `price_double`, `image`, `status`, `is_featured`, `sort_order`, `created_at`, `updated_at`) VALUES
(47, 1, 'Smashburger', 'smashburger-083e', 'Boeuf, Cheddar, Oignons, Secret sauce', 1050.00, NULL, 1350.00, NULL, 'available', 0, 0, '2026-03-24 14:25:43', '2026-03-24 14:49:37'),
(48, 1, 'Spyci Beef', 'spyci-beef-51ac', 'Boeuf, Cheddar, Pickles, Salade, Tomates, Oignons, Sauce spicy', 1100.00, NULL, 1400.00, NULL, 'available', 0, 0, '2026-03-24 14:26:47', '2026-03-24 14:51:03'),
(49, 1, 'Cheeseburger', 'cheeseburger-6c2a', 'Boeuf, Cheddar, Pikcles, Oignons, Ketchup, Moutarde', 1050.00, NULL, 1350.00, NULL, 'available', 0, 0, '2026-03-24 14:27:32', '2026-03-24 16:13:54'),
(50, 1, 'Big Bacon', 'big-bacon-5412', 'Boeuf, Poitrine fumée, Cheddar, Pikcles, Oignons confits, Ketchup, Moutard', 1200.00, NULL, 1500.00, NULL, 'available', 0, 0, '2026-03-24 14:28:25', '2026-03-24 14:51:16'),
(51, 1, 'Big Bill', 'big-bill-d281', 'Boeuf, Cheddar, Pickels, Salade, Tomates, Oignons, Secret sauce', 1150.00, NULL, 1450.00, NULL, 'available', 0, 0, '2026-03-24 14:29:09', '2026-03-24 14:57:52'),
(52, 1, 'Porkybill', 'porkybill-2157', 'Boeuf, Cheddar, Poitrine Fumée, Pickles, Oignons, Secret sauce', 1700.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:30:03', '2026-03-24 14:51:25'),
(53, 3, 'CheesyBox Fried Chicken', 'cheesybox-fried-chicken-8ee1', 'Frites, Fromage, Poulet frit, Sauces', 1600.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:31:51', '2026-03-24 14:32:38'),
(54, 3, 'CheesyBox Boeuf', 'cheesybox-boeuf-eedf', 'Frites, Fromage, Boeuf haché, Sauces', 1500.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:32:22', '2026-03-24 14:32:22'),
(55, 3, 'Cheesy Fries', 'cheesy-fries-4120', 'Frites, Fromage', 750.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:33:21', '2026-03-24 14:33:21'),
(56, 3, 'Cheesy Fries Bacon', 'cheesy-fries-bacon-6524', 'Frites, Fromage, Poitrine Fumée', 900.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:33:43', '2026-03-24 14:33:43'),
(57, 4, '25cl', '25cl-ddee', 'Coca, Sprite, Fanta, ORO, Pepsi', 300.00, NULL, NULL, 'assets/uploads/img_69c210e5ad937.png', 'available', 0, 0, '2026-03-24 14:36:36', '2026-03-24 15:19:49'),
(58, 4, '50cl', '50cl-0695', 'Coca, Sprite, Fanta, ORO, Pepsi', 450.00, NULL, NULL, 'assets/uploads/img_69c210de60407.png', 'available', 0, 0, '2026-03-24 14:36:45', '2026-03-24 15:19:42'),
(59, 4, '1L5', '1l5-f1c5', 'Coca, Sprite, Fanta, ORO, Pepsi', 600.00, NULL, NULL, 'assets/uploads/img_69c210d78ee47.png', 'available', 0, 0, '2026-03-24 14:37:00', '2026-03-24 15:19:35'),
(60, 4, 'Joker', 'joker-74f4', '', 300.00, NULL, NULL, 'assets/uploads/img_69c210cfc56da.png', 'available', 0, 0, '2026-03-24 14:37:13', '2026-03-24 15:19:27'),
(61, 4, 'Perrier (33cl)', 'perrier-33cl-0790', '', 300.00, NULL, NULL, 'assets/uploads/img_69c20f7300440.jpg', 'available', 0, 0, '2026-03-24 14:37:32', '2026-03-24 15:13:39'),
(62, 4, 'Eau (50cl)', 'eau-50cl-9a88', '', 250.00, NULL, NULL, 'assets/uploads/img_69c2118579721.jpg', 'available', 0, 0, '2026-03-24 14:37:51', '2026-03-24 15:22:29'),
(63, 4, 'Granité', 'granite-1255', '', 400.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:38:01', '2026-03-24 14:38:01'),
(64, 4, 'Thé Glacé', 'the-glace-0196', '', 400.00, NULL, NULL, 'assets/uploads/img_69c211432feb6.png', 'available', 0, 0, '2026-03-24 14:38:14', '2026-03-24 15:21:23'),
(65, 5, 'Milk Shake', 'milk-shake-3d01', '', 600.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:38:28', '2026-03-24 14:38:28'),
(66, 5, 'Glace', 'glace-bb89', '', 400.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:38:40', '2026-03-24 14:38:40'),
(67, 1, 'Barbebill', 'barbebill-35a7', 'Boeuf, Cheddar, Salade, Tomates, Oignons frits, BBA, Mayo', 1150.00, NULL, 1450.00, NULL, 'available', 0, 0, '2026-03-24 14:40:24', '2026-03-24 14:51:31'),
(68, 1, 'Crazy Bill', 'crazy-bill-3bfa', 'Boeuf, Cheddar, Poitrine Fumée, Pickles, Oignons confits, Ketchup, Moutarde', 2990.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:41:20', '2026-03-24 14:51:41'),
(69, 1, 'Chicken Bacon', 'chicken-bacon-748c', 'Poulet frit, Cheddar, Poitrine fumée, Salade, Tomates, Oignons confits, BBQ, Mayo', 1450.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:42:19', '2026-03-24 14:57:13'),
(70, 1, 'Chicken Boss', 'chicken-boss-37b5', 'Poulet frit, Cheddar, Pickles, Salade, Tomates, Oignons, Secret sauce', 1350.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:43:03', '2026-03-24 14:57:03'),
(71, 1, 'Spicy Chicken', 'spicy-chicken-40c0', 'Poulet frit, Cheddar, Pickles, Salade, Tomates, Oignons, Sauce spicy', 1300.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:43:59', '2026-03-24 14:57:19'),
(72, 1, 'Cheesysteak', 'cheesysteak-7a28', 'Boeuf, Cheddar, Oignons, Cheesy sauce,', 900.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:44:33', '2026-03-24 14:57:29'),
(73, 1, 'Wrap Boeuf', 'wrap-boeuf-4892', 'Boeuf, Cheddar, Poitrine fumée, Salade, Tomates, Oignons, BBQ, Secret sauce', 990.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:45:30', '2026-03-24 14:52:04'),
(74, 1, 'Wrap poulet', 'wrap-poulet-e197', 'Poulet frit, Cheddar, Poitrine fumée, Salade, Tomates, Oignons, BBQ, Secret sauce', 990.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:46:20', '2026-03-24 14:51:57'),
(75, 1, 'Salade cesar', 'salade-cesar-e3b8', 'Salade, Tomates, Oignons, Poulet frit, Poitrine fumée, Oeufs, Parmesan, Sauce secret', 1400.00, NULL, NULL, NULL, 'available', 0, 0, '2026-03-24 14:47:13', '2026-03-24 14:51:51'),
(79, 1, 'Big Jam', 'big-jam-5e2c', '', 1300.00, NULL, 1550.00, NULL, 'available', 1, 0, '2026-03-24 16:14:57', '2026-03-24 16:14:57');

-- --------------------------------------------------------

--
-- Structure de la table `product_options`
--

DROP TABLE IF EXISTS `product_options`;
CREATE TABLE IF NOT EXISTS `product_options` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_modifier` decimal(8,2) NOT NULL DEFAULT '0.00',
  `option_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'default',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_options_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product_options`
--

INSERT INTO `product_options` (`id`, `product_id`, `name`, `price_modifier`, `option_group`, `is_active`) VALUES
(39, 47, 'Menu M', 500.00, 'taille_menu', 1),
(40, 47, 'Menu L', 700.00, 'taille_menu', 1),
(41, 48, 'Menu M', 500.00, 'taille_menu', 1),
(42, 48, 'Menu L', 700.00, 'taille_menu', 1),
(43, 49, 'Menu M', 500.00, 'taille_menu', 1),
(44, 49, 'Menu L', 700.00, 'taille_menu', 1),
(45, 50, 'Menu M', 500.00, 'taille_menu', 1),
(46, 50, 'Menu L', 700.00, 'taille_menu', 1),
(47, 52, 'Menu M', 500.00, 'taille_menu', 1),
(48, 52, 'Menu L', 700.00, 'taille_menu', 1),
(49, 67, 'Menu M', 500.00, 'taille_menu', 1),
(50, 67, 'Menu L', 700.00, 'taille_menu', 1),
(51, 68, 'Menu M', 500.00, 'taille_menu', 1),
(52, 68, 'Menu L', 700.00, 'taille_menu', 1),
(53, 75, 'Menu M', 500.00, 'taille_menu', 1),
(54, 75, 'Menu L', 700.00, 'taille_menu', 1),
(55, 74, 'Menu M', 500.00, 'taille_menu', 1),
(56, 74, 'Menu L', 700.00, 'taille_menu', 1),
(57, 73, 'Menu M', 500.00, 'taille_menu', 1),
(58, 73, 'Menu L', 700.00, 'taille_menu', 1),
(63, 70, 'Menu M', 500.00, 'taille_menu', 1),
(64, 70, 'Menu L', 700.00, 'taille_menu', 1),
(65, 69, 'Menu M', 500.00, 'taille_menu', 1),
(66, 69, 'Menu L', 700.00, 'taille_menu', 1),
(67, 71, 'Menu M', 500.00, 'taille_menu', 1),
(68, 71, 'Menu L', 700.00, 'taille_menu', 1),
(69, 72, 'Menu M', 500.00, 'taille_menu', 1),
(70, 72, 'Menu L', 700.00, 'taille_menu', 1),
(71, 51, 'Menu M', 500.00, 'taille_menu', 1),
(72, 51, 'Menu L', 700.00, 'taille_menu', 1),
(73, 79, 'Menu M', 500.00, 'taille_menu', 1),
(74, 79, 'Menu L', 700.00, 'taille_menu', 1);

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
  `stock_status` enum('in_stock','low_stock','out_of_stock') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_stock',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_product_restaurant` (`product_id`,`restaurant_id`),
  KEY `restaurant_id` (`restaurant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=425 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product_restaurant`
--

INSERT INTO `product_restaurant` (`id`, `product_id`, `restaurant_id`, `is_available`, `stock_status`) VALUES
(218, 47, 1, 1, 'in_stock'),
(219, 47, 2, 1, 'in_stock'),
(220, 47, 3, 1, 'in_stock'),
(221, 48, 1, 1, 'in_stock'),
(222, 48, 2, 1, 'in_stock'),
(223, 48, 3, 1, 'in_stock'),
(224, 49, 1, 1, 'in_stock'),
(225, 49, 2, 0, 'in_stock'),
(226, 49, 3, 1, 'in_stock'),
(227, 50, 1, 1, 'in_stock'),
(228, 50, 2, 1, 'in_stock'),
(229, 50, 3, 1, 'in_stock'),
(230, 51, 1, 1, 'in_stock'),
(231, 51, 2, 1, 'in_stock'),
(232, 51, 3, 1, 'in_stock'),
(233, 52, 1, 1, 'in_stock'),
(234, 52, 2, 1, 'in_stock'),
(235, 52, 3, 1, 'in_stock'),
(236, 53, 1, 1, 'in_stock'),
(237, 53, 2, 1, 'in_stock'),
(238, 53, 3, 1, 'in_stock'),
(239, 54, 1, 1, 'in_stock'),
(240, 54, 2, 1, 'in_stock'),
(241, 54, 3, 1, 'in_stock'),
(245, 55, 1, 1, 'in_stock'),
(246, 55, 2, 1, 'in_stock'),
(247, 55, 3, 1, 'in_stock'),
(248, 56, 1, 1, 'in_stock'),
(249, 56, 2, 1, 'in_stock'),
(250, 56, 3, 1, 'in_stock'),
(251, 57, 1, 1, 'in_stock'),
(252, 57, 2, 1, 'in_stock'),
(253, 57, 3, 1, 'in_stock'),
(254, 58, 1, 1, 'in_stock'),
(255, 58, 2, 1, 'in_stock'),
(256, 58, 3, 1, 'in_stock'),
(257, 59, 1, 1, 'in_stock'),
(258, 59, 2, 1, 'in_stock'),
(259, 59, 3, 1, 'in_stock'),
(260, 60, 1, 1, 'in_stock'),
(261, 60, 2, 1, 'in_stock'),
(262, 60, 3, 1, 'in_stock'),
(263, 61, 1, 1, 'in_stock'),
(264, 61, 2, 1, 'in_stock'),
(265, 61, 3, 1, 'in_stock'),
(266, 62, 1, 1, 'in_stock'),
(267, 62, 2, 1, 'in_stock'),
(268, 62, 3, 1, 'in_stock'),
(269, 63, 1, 1, 'in_stock'),
(270, 63, 2, 1, 'in_stock'),
(271, 63, 3, 1, 'in_stock'),
(272, 64, 1, 1, 'in_stock'),
(273, 64, 2, 1, 'in_stock'),
(274, 64, 3, 1, 'in_stock'),
(275, 65, 1, 1, 'in_stock'),
(276, 65, 2, 1, 'in_stock'),
(277, 65, 3, 1, 'in_stock'),
(278, 66, 1, 1, 'in_stock'),
(279, 66, 2, 1, 'in_stock'),
(280, 66, 3, 1, 'in_stock'),
(281, 67, 1, 1, 'in_stock'),
(282, 67, 2, 1, 'in_stock'),
(283, 67, 3, 1, 'in_stock'),
(284, 68, 1, 1, 'in_stock'),
(285, 68, 2, 1, 'in_stock'),
(286, 68, 3, 1, 'in_stock'),
(287, 69, 1, 1, 'in_stock'),
(288, 69, 2, 1, 'in_stock'),
(289, 69, 3, 1, 'in_stock'),
(290, 70, 1, 1, 'in_stock'),
(291, 70, 2, 1, 'in_stock'),
(292, 70, 3, 1, 'in_stock'),
(293, 71, 1, 1, 'in_stock'),
(294, 71, 2, 1, 'in_stock'),
(295, 71, 3, 1, 'in_stock'),
(296, 72, 1, 1, 'in_stock'),
(297, 72, 2, 1, 'in_stock'),
(298, 72, 3, 1, 'in_stock'),
(299, 73, 1, 1, 'in_stock'),
(300, 73, 2, 1, 'in_stock'),
(301, 73, 3, 1, 'in_stock'),
(302, 74, 1, 1, 'in_stock'),
(303, 74, 2, 1, 'in_stock'),
(304, 74, 3, 1, 'in_stock'),
(305, 75, 1, 1, 'in_stock'),
(306, 75, 2, 1, 'in_stock'),
(307, 75, 3, 1, 'in_stock'),
(422, 79, 1, 1, 'in_stock'),
(423, 79, 2, 1, 'in_stock'),
(424, 79, 3, 1, 'in_stock');

-- --------------------------------------------------------

--
-- Structure de la table `restaurants`
--

DROP TABLE IF EXISTS `restaurants`;
CREATE TABLE IF NOT EXISTS `restaurants` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_hours` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `address`, `phone`, `opening_hours`, `is_active`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Billy\'s Centre-Ville', '36 Av. du Maréchal Foch, Nouméa 98800, Nouvelle-Calédonie', '', '11h00 - 23h00', 1, NULL, '2026-03-02 22:44:46', '2026-03-24 16:12:25'),
(2, 'Billy\'s Dumbéa Mall', 'complexe Green Retail (Dumbéa Mall, Bd Du Rail Calédonien, Dumbéa, Nouvelle-Calédonie', '', '10h00 - 00h00', 1, NULL, '2026-03-02 22:44:46', '2026-03-24 16:12:43'),
(3, 'Billy\'s Paita', '28 Ets Morcellement Martin, Paita 98890, Nouvelle-Calédonie', '', '11h00 - 22h00', 1, NULL, '2026-03-02 22:44:46', '2026-03-24 16:13:04');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `role` enum('admin','kitchen','client') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
