<?php

class Agency extends Model
{
    protected $table = 'agencies';

    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name";
        return $this->query($sql);
    }

    public function search($keyword)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE code LIKE ? OR name LIKE ? OR representative LIKE ? OR phone LIKE ?
                ORDER BY name";
        $param = "%{$keyword}%";
        return $this->query($sql, [$param, $param, $param, $param]);
    }

    public function findByCode($code)
    {
        $sql = "SELECT * FROM {$this->table} WHERE code = ?";
        $result = $this->query($sql, [$code]);
        return $result ? $result[0] : null;
    }

    public function hasExports($id)
    {
        $sql = "SELECT COUNT(*) as count FROM exports WHERE agency_id = ?";
        $result = $this->query($sql, [$id]);
        return $result && $result[0]['count'] > 0;
    }

    public function getWithStats($id)
    {
        $sql = "SELECT a.*, 
                COUNT(DISTINCT e.id) as total_exports,
                SUM(e.final_amount) as total_revenue
                FROM {$this->table} a
                LEFT JOIN exports e ON a.id = e.agency_id AND e.status = 'approved'
                WHERE a.id = ?
                GROUP BY a.id";
        $result = $this->query($sql, [$id]);
        return $result ? $result[0] : null;
    }
}
