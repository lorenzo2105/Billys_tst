<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    protected string $table = 'categories';

    public function getActive(): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY sort_order ASC"
        );
    }

    public function getWithProductCount(): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, COUNT(p.id) as product_count
             FROM {$this->table} c
             LEFT JOIN products p ON p.category_id = c.id
             GROUP BY c.id
             ORDER BY c.sort_order ASC"
        );
    }
}
