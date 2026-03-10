<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Restaurant extends Model
{
    protected string $table = 'restaurants';

    public function getActive(): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY id ASC"
        );
    }
}
