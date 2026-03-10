-- ============================================================
-- Billy's Fast Food - Migration 005: Convert EUR to XPF
-- Converts all prices from EUR to XPF (1 EUR = 119.33 XPF)
-- ============================================================

-- Convert product prices (EUR to XPF, rounded to nearest 10)
UPDATE `products` SET `price` = ROUND(price * 119.33 / 10) * 10;

-- Convert product option price modifiers (EUR to XPF, rounded to nearest 10)
UPDATE `product_options` SET `price_modifier` = ROUND(price_modifier * 119.33 / 10) * 10;
