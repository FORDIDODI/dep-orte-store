<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key_name', 'value', 'description'];

    public function getValue($key, $default = null)
    {
        $setting = $this->where('key_name', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    public function setValue($key, $value)
    {
        $setting = $this->where('key_name', $key)->first();
        
        if ($setting) {
            return $this->update($setting['id'], ['value' => $value]);
        } else {
            return $this->insert(['key_name' => $key, 'value' => $value]);
        }
    }
}