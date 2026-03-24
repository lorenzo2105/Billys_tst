-- ============================================================
-- Billy's Fast Food - Migration 011: Convert prices from EUR to XPF
-- Taux de conversion: 1 EUR = 119.33 XPF
-- ============================================================

-- Mettre à jour les prix des produits (price)
UPDATE `products` SET `price` = ROUND(`price` * 119.33, 0);

-- Mettre à jour les prix double des burgers (price_double)
UPDATE `products` SET `price_double` = ROUND(`price_double` * 119.33, 0) WHERE `price_double` IS NOT NULL;

-- Mettre à jour les prix des suppléments globaux
UPDATE `global_supplements` SET `price` = ROUND(`price` * 119.33, 0);

-- Mettre à jour les modificateurs de prix des options produits
UPDATE `product_options` SET `price_modifier` = ROUND(`price_modifier` * 119.33, 0);

-- Mettre à jour les prix unitaires dans les commandes existantes (si besoin)
UPDATE `order_items` SET `unit_price` = ROUND(`unit_price` * 119.33, 0);
UPDATE `order_items` SET `options_price` = ROUND(`options_price` * 119.33, 0);
UPDATE `order_items` SET `line_total` = ROUND(`line_total` * 119.33, 0);

-- Mettre à jour les totaux des commandes
UPDATE `orders` SET `total` = ROUND(`total` * 119.33, 0);
UPDATE `orders` SET `delivery_fee` = ROUND(`delivery_fee` * 119.33, 0) WHERE `delivery_fee` IS NOT NULL;
