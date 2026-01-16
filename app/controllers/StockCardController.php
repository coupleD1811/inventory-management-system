<?php

class StockCardController extends Controller
{
    private $transactionModel;
    private $warehouseModel;
    private $productModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->transactionModel = $this->model('Transaction');
        $this->warehouseModel = $this->model('Warehouse');
        $this->productModel = $this->model('Product');
    }

    public function index()
    {
        Auth::requirePermission('stock_card.view');

        $warehouseId = $_GET['warehouse_id'] ?? null;
        $productId = $_GET['product_id'] ?? null;
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;

        $transactions = [];
        $product = null;
        $warehouse = null;

        if ($warehouseId && $productId) {
            $transactions = $this->transactionModel->getStockCard(
                $warehouseId,
                $productId,
                $startDate,
                $endDate
            );

            $product = $this->productModel->find($productId);
            $warehouse = $this->warehouseModel->find($warehouseId);
        }

        $warehouses = $this->warehouseModel->getAllActive();
        $products = $this->productModel->getAllActive();

        $this->view('stockcard/index', [
            'title' => 'Thẻ kho',
            'transactions' => $transactions,
            'warehouses' => $warehouses,
            'products' => $products,
            'product' => $product,
            'warehouse' => $warehouse,
            'warehouseId' => $warehouseId,
            'productId' => $productId,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}
