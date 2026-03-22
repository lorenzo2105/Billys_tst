-- ============================================================
-- Billy's Fast Food - FIX ALL: Corriger toutes les colonnes/tables manquantes
-- Exécutez ce fichier si vous avez des erreurs de colonnes ou tables manquantes
-- ============================================================

USE `billys_fastfood`;

-- ══════════════════════════════════════════════════════════════
-- 1. AJOUTER LES COLONNES PRIX BURGERS (price_simple, price_double)
-- ══════════════════════════════════════════════════════════════

-- Vérifier et ajouter price_simple
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'billys_fastfood' 
  AND TABLE_NAME = 'products' 
  AND COLUMN_NAME = 'price_simple';

SET @query = IF(@col_exists = 0, 
    'ALTER TABLE `products` ADD COLUMN `price_simple` DECIMAL(8,2) DEFAULT NULL AFTER `price`',
    'SELECT "Column price_simple already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Vérifier et ajouter price_double
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'billys_fastfood' 
  AND TABLE_NAME = 'products' 
  AND COLUMN_NAME = 'price_double';

SET @query = IF(@col_exists = 0, 
    'ALTER TABLE `products` ADD COLUMN `price_double` DECIMAL(8,2) DEFAULT NULL AFTER `price_simple`',
    'SELECT "Column price_double already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ══════════════════════════════════════════════════════════════
-- 2. CRÉER LES TABLES SUPPLÉMENTS GLOBAUX
-- ══════════════════════════════════════════════════════════════

CREATE TABLE IF NOT EXISTS `global_supplements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `product_supplements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `supplement_id` INT UNSIGNED NOT NULL,
    UNIQUE KEY `unique_product_supplement` (`product_id`, `supplement_id`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`supplement_id`) REFERENCES `global_supplements`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ══════════════════════════════════════════════════════════════
-- 3. INSÉRER LES SUPPLÉMENTS PAR DÉFAUT (si la table est vide)
-- ══════════════════════════════════════════════════════════════

INSERT IGNORE INTO `global_supplements` (`id`, `name`, `price`, `is_active`, `sort_order`) VALUES
(1, 'Fromage Cheddar', 0.50, 1, 1),
(2, 'Bacon', 1.00, 1, 2),
(3, 'Œuf', 0.80, 1, 3),
(4, 'Avocat', 1.20, 1, 4),
(5, 'Jalapeños', 0.50, 1, 5),
(6, 'Oignons frits', 0.60, 1, 6),
(7, 'Champignons', 0.70, 1, 7),
(8, 'Cornichons', 0.30, 1, 8);

-- ══════════════════════════════════════════════════════════════
-- ✅ TERMINÉ - Toutes les corrections ont été appliquées
-- ══════════════════════════════════════════════════════════════
