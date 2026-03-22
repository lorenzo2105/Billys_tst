-- ============================================================
-- Billy's Fast Food - Migration 003: Add burger price columns
-- Ajouter les colonnes price_simple et price_double à products
-- ============================================================

USE `billys_fastfood`;

-- Ajouter les colonnes si elles n'existent pas
ALTER TABLE `products` 
ADD COLUMN IF NOT EXISTS `price_simple` DECIMAL(8,2) DEFAULT NULL AFTER `price`,
ADD COLUMN IF NOT EXISTS `price_double` DECIMAL(8,2) DEFAULT NULL AFTER `price_simple`;

-- Note: Les valeurs seront NULL par défaut
-- Vous pouvez définir les prix burger via le formulaire d'édition de produit
