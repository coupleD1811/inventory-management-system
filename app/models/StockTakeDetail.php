<?php

class StockTakeDetail extends Model
{
    protected $table = 'stock_take_details';

    public function getByStockTake($stockTakeId)
    {
        $sql = "SELECT std.*, p.name as product_name, p.code as product_code, p.unit
                FROM {$this->table} std
                LEFT JOIN products p ON std.product_id = p.id
                WHERE std.stock_take_id = ?
                ORDER BY std.id";
        return $this->query($sql, [$stockTakeId]);
    }

    public function deleteByStockTake($stockTakeId)
    {
        $sql = "DELETE FROM {$this->table} WHERE stock_take_id = ?";
        return $this->query($sql, [$stockTakeId]);
    }

    public function calculateVariance($systemQty, $actualQty)
    {
        return $actualQty - $systemQty;
    }
}
