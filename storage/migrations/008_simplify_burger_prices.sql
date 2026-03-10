-- ============================================================
-- Billy's Fast Food - Migration 008: Simplify burger prices
-- Supprimer price_simple, price devient le prix simple
-- ============================================================

-- Supprimer la colonne price_simple (on utilise price comme prix simple)
ALTER TABLE `products` DROP COLUMN `price_simple`;
