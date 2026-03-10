<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class ProductOption extends Model
{
    protected string $table = 'product_options';

    public function getByProduct(int $productId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE product_id = :pid AND is_active = 1 ORDER BY option_group, name",
            ['pid' => $productId]
        );
    }

    public function getGroupedByProduct(int $productId): array
    {
        $options = $this->getByProduct($productId);
        $grouped = [];
        foreach ($options as $option) {
            $grouped[$option['option_group']][] = $option;
        }
        return $grouped;
    }
}
