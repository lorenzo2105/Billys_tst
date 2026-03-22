-- ============================================================
-- Billy's Fast Food - Migration 002: Global Supplements
-- Ajouter le système de suppléments globaux
-- ============================================================

USE `billys_fastfood`;

-- Table des suppléments globaux
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

-- Table de liaison produits <-> suppléments
CREATE TABLE IF NOT EXISTS `product_supplements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT UNSIGNED NOT NULL,
    `supplement_id` INT UNSIGNED NOT NULL,
    UNIQUE KEY `unique_product_supplement` (`product_id`, `supplement_id`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`supplement_id`) REFERENCES `global_supplements`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed global supplements
INSERT INTO `global_supplements` (`name`, `price`, `is_active`, `sort_order`) VALUES
('Fromage Cheddar', 0.50, 1, 1),
('Bacon', 1.00, 1, 2),
('Œuf', 0.80, 1, 3),
('Avocat', 1.20, 1, 4),
('Jalapeños', 0.50, 1, 5),
('Oignons frits', 0.60, 1, 6),
('Champignons', 0.70, 1, 7),
('Cornichons', 0.30, 1, 8);
