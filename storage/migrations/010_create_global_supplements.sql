-- ============================================================
-- Billy's Fast Food - Migration 010: Global supplements system
-- Créer un système de suppléments globaux assignables aux produits
-- ============================================================

-- Table des suppléments globaux
CREATE TABLE IF NOT EXISTS `global_supplements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table de liaison produits <-> suppléments
CREATE TABLE IF NOT EXISTS `product_supplements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `supplement_id` INT UNSIGNED NOT NULL,
    UNIQUE KEY `unique_product_supplement` (`product_id`, `supplement_id`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`supplement_id`) REFERENCES `global_supplements`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrer les suppléments existants vers le système global
INSERT INTO `global_supplements` (`name`, `price`, `sort_order`)
SELECT DISTINCT `name`, `price_modifier`, 0
FROM `product_options`
WHERE `option_group` = 'supplements'
ORDER BY `name`;

-- Créer les liaisons produit-supplément depuis les options existantes
INSERT INTO `product_supplements` (`product_id`, `supplement_id`)
SELECT DISTINCT po.`product_id`, gs.`id`
FROM `product_options` po
INNER JOIN `global_supplements` gs ON gs.`name` = po.`name`
WHERE po.`option_group` = 'supplements';

-- Supprimer les anciennes options suppléments (maintenant gérées globalement)
DELETE FROM `product_options` WHERE `option_group` = 'supplements';
