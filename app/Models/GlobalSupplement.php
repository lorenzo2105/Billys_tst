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

    /**
     * Get supplements for a specific product
     */
    public function getByProduct(int $productId): array
    {
        return $this->db->fetchAll(
            "SELECT gs.* 
             FROM {$this->table} gs
             INNER JOIN product_supplements ps ON ps.supplement_id = gs.id
             WHERE ps.product_id = :pid AND gs.is_active = 1
             ORDER BY gs.sort_order, gs.name",
            ['pid' => $productId]
        );
    }

    /**
     * Assign supplements to a product
     */
    public function assignToProduct(int $productId, array $supplementIds): void
    {
        // Remove existing assignments
        $this->db->execute(
            "DELETE FROM product_supplements WHERE product_id = :pid",
            ['pid' => $productId]
        );

        // Add new assignments
        foreach ($supplementIds as $suppId) {
            $this->db->execute(
                "INSERT INTO product_supplements (product_id, supplement_id) VALUES (:pid, :sid)",
                ['pid' => $productId, 'sid' => (int)$suppId]
            );
        }
    }

    /**
     * Get supplement IDs assigned to a product
     */
    public function getAssignedIds(int $productId): array
    {
        $rows = $this->db->fetchAll(
            "SELECT supplement_id FROM product_supplements WHERE product_id = :pid",
            ['pid' => $productId]
        );
        return array_column($rows, 'supplement_id');
    }
}
