-- ============================================================
-- Billy's Fast Food - Migration 012: Drop product_supplements table
-- Les suppléments sont maintenant appliqués automatiquement à tous les burgers
-- ============================================================

-- Supprimer la table de liaison product_supplements
DROP TABLE IF EXISTS `product_supplements`;
