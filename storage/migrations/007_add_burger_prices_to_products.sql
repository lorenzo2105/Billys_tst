-- ============================================================
-- Billy's Fast Food - Migration 007: Add burger price fields
-- Ajouter les colonnes price_simple et price_double à products
-- ============================================================

ALTER TABLE `products` 
ADD COLUMN `price_simple` DECIMAL(10,2) DEFAULT NULL AFTER `price`,
ADD COLUMN `price_double` DECIMAL(10,2) DEFAULT NULL AFTER `price_simple`;

-- Mettre à jour les burgers existants avec les prix par défaut
UPDATE `products` 
SET `price_simple` = 0, 
    `price_double` = 360
WHERE `category_id` IN (SELECT `id` FROM `categories` WHERE `slug` = 'burgers');
