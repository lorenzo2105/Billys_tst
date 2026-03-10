-- ============================================================
-- Billy's Fast Food - Migration 002: Menu Options
-- Adds option_type (radio/checkbox) to product_options
-- Adds viande + taille_menu options to burger products
-- Run this after 001_create_database.sql
-- ============================================================

-- Add option_type column
ALTER TABLE `product_options`
    ADD COLUMN `option_type` ENUM('checkbox', 'radio') NOT NULL DEFAULT 'checkbox' AFTER `option_group`;

-- Update existing 'taille' group options to radio (only one size selectable)
UPDATE `product_options` SET `option_type` = 'radio' WHERE `option_group` = 'taille';

-- ── Viande options (radio) for all burgers (products 1-5) ────
INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`, `option_type`, `is_active`) VALUES
(1, 'Simple (1 steak)',   0.00, 'viande', 'radio', 1),
(1, 'Double (2 steaks)',  3.00, 'viande', 'radio', 1),
(2, 'Simple (1 steak)',   0.00, 'viande', 'radio', 1),
(2, 'Double (2 steaks)',  3.00, 'viande', 'radio', 1),
(3, 'Simple (1 steak)',   0.00, 'viande', 'radio', 1),
(3, 'Double (2 steaks)',  3.00, 'viande', 'radio', 1),
(4, 'Simple (1 filet)',   0.00, 'viande', 'radio', 1),
(4, 'Double (2 filets)',  3.00, 'viande', 'radio', 1),
(5, 'Simple (1 steak végétal)',  0.00, 'viande', 'radio', 1),
(5, 'Double (2 steaks végétaux)', 3.00, 'viande', 'radio', 1);

-- ── Taille du menu options (radio) for all burgers (products 1-5) ──
INSERT INTO `product_options` (`product_id`, `name`, `price_modifier`, `option_group`, `option_type`, `is_active`) VALUES
(1, 'Burger seul',                        0.00, 'taille_menu', 'radio', 1),
(1, 'Menu M (Frites M + Boisson 33cl)',   3.50, 'taille_menu', 'radio', 1),
(1, 'Menu L (Frites L + Boisson 50cl)',   5.50, 'taille_menu', 'radio', 1),
(2, 'Burger seul',                        0.00, 'taille_menu', 'radio', 1),
(2, 'Menu M (Frites M + Boisson 33cl)',   3.50, 'taille_menu', 'radio', 1),
(2, 'Menu L (Frites L + Boisson 50cl)',   5.50, 'taille_menu', 'radio', 1),
(3, 'Burger seul',                        0.00, 'taille_menu', 'radio', 1),
(3, 'Menu M (Frites M + Boisson 33cl)',   3.50, 'taille_menu', 'radio', 1),
(3, 'Menu L (Frites L + Boisson 50cl)',   5.50, 'taille_menu', 'radio', 1),
(4, 'Burger seul',                        0.00, 'taille_menu', 'radio', 1),
(4, 'Menu M (Frites M + Boisson 33cl)',   3.50, 'taille_menu', 'radio', 1),
(4, 'Menu L (Frites L + Boisson 50cl)',   5.50, 'taille_menu', 'radio', 1),
(5, 'Burger seul',                        0.00, 'taille_menu', 'radio', 1),
(5, 'Menu M (Frites M + Boisson 33cl)',   3.50, 'taille_menu', 'radio', 1),
(5, 'Menu L (Frites L + Boisson 50cl)',   5.50, 'taille_menu', 'radio', 1);
