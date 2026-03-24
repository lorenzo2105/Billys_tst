<?php
namespace App\Models;

use App\Core\Model;

class GlobalSupplement extends Model
{
    protected string $table = 'global_supplements';

    /**
     * Get all active supplements
     */
    public function getActive(): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY sort_order, name"
        );
    }

    /**
     * Get all supplements (active and inactive)
     */
    public function getAll(): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} ORDER BY sort_order, name"
        );
    }

}
