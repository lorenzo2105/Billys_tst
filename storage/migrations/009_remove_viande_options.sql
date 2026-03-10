-- ============================================================
-- Billy's Fast Food - Migration 009: Remove viande options
-- Supprimer toutes les options viande de la base de données
-- ============================================================

DELETE FROM `product_options` WHERE `option_group` = 'viande';
