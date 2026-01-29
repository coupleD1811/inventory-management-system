<?php

class ImportDetail extends Model
{
    protected $table = 'import_details';

    public function getByImport($importId)
    {
        $sql = "SELECT id.*, p.code as product_code, p.name as product_name, p.unit,
                       p.min_stock, p.max_stock, pt.name as product_type_name
                FROM {$this->table} id
                INNER JOIN products p ON id.product_id = p.id
                LEFT JOIN product_types pt ON p.product_type_id = pt.id
                WHERE id.import_id = ?
                ORDER BY id.id";
        return $this->query($sql, [$importId]);
    }

    public function deleteByImport($importId)
    {
        $sql = "DELETE FROM {$this->table} WHERE import_id = ?";
        return $this->execute($sql, [$importId]);
    }

    public function getTotalAmount($importId)
    {
        $sql = "SELECT SUM(total_price) as total FROM {$this->table} WHERE import_id = ?";
        $result = $this->query($sql, [$importId]);
        return $result ? ($result[0]['total'] ?? 0) : 0;
    }
}
