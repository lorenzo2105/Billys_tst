<?php
/**
 * Helper functions for Billy's Fast Food
 */

if (!function_exists('formatPrice')) {
    /**
     * Format price in XPF (Franc Pacifique)
     * @param float $price Price to format
     * @return string Formatted price with XPF suffix
     */
    function formatPrice(float $price): string
    {
        return number_format($price, 0, ',', ' ') . ' XPF';
    }
}
