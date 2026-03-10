-- ============================================================
-- Billy's Fast Food - Migration 006: Create Settings Table
-- Table pour stocker les paramètres globaux de l'application
-- ============================================================

CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT NOT NULL,
    `setting_type` VARCHAR(50) DEFAULT 'string',
    `description` VARCHAR(255) DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer les paramètres par défaut pour les prix des burgers
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_type`, `description`) VALUES
('burger_price_simple', '0', 'decimal', 'Prix de base pour un burger simple (1 viande)'),
('burger_price_double', '360', 'decimal', 'Supplément pour un burger double (2 viandes)');
