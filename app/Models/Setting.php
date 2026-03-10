<?php
namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    protected string $table = 'settings';

    /**
     * Get a setting value by key
     */
    public function get(string $key, $default = null)
    {
        $setting = $this->db->fetch(
            "SELECT setting_value, setting_type FROM {$this->table} WHERE setting_key = :key",
            ['key' => $key]
        );

        if (!$setting) {
            return $default;
        }

        // Cast value based on type
        return $this->castValue($setting['setting_value'], $setting['setting_type']);
    }

    /**
     * Set a setting value
     */
    public function set(string $key, $value, string $type = 'string'): bool
    {
        $exists = $this->db->fetch(
            "SELECT id FROM {$this->table} WHERE setting_key = :key",
            ['key' => $key]
        );

        if ($exists) {
            return $this->db->execute(
                "UPDATE {$this->table} SET setting_value = :value, setting_type = :type WHERE setting_key = :key",
                ['key' => $key, 'value' => (string)$value, 'type' => $type]
            );
        } else {
            return $this->db->execute(
                "INSERT INTO {$this->table} (setting_key, setting_value, setting_type) VALUES (:key, :value, :type)",
                ['key' => $key, 'value' => (string)$value, 'type' => $type]
            );
        }
    }

    /**
     * Get all settings
     */
    public function getAll(): array
    {
        $settings = $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY setting_key");
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = [
                'value' => $this->castValue($setting['setting_value'], $setting['setting_type']),
                'type' => $setting['setting_type'],
                'description' => $setting['description']
            ];
        }
        
        return $result;
    }

    /**
     * Cast value based on type
     */
    private function castValue($value, string $type)
    {
        switch ($type) {
            case 'integer':
                return (int)$value;
            case 'decimal':
            case 'float':
                return (float)$value;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
}
