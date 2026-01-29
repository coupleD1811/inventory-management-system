<?php

class ProductType extends Model
{
    protected $table = 'product_types';

    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY name";
        return $this->query($sql);
    }

    public function findByName($name)
    {
        $sql = "SELECT * FROM {$this->table} WHERE name = ?";
        $result = $this->query($sql, [$name]);
        return $result ? $result[0] : null;
    }

    public function getOrCreate($name)
    {
        $name = trim($name);
        if ($name === '') {
            return null;
        }

        $existing = $this->findByName($name);
        if ($existing) {
            return $existing['id'];
        }

        return $this->create(['name' => $name]);
    }
}
