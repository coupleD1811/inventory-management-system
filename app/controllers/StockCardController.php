<?php

class StockCardController extends Controller
{
    private $transactionModel;
    private $warehouseModel;
    private $productModel;
    private $inventoryModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->transactionModel = $this->model('Transaction');
        $this->warehouseModel = $this->model('Warehouse');
        $this->productModel = $this->model('Product');
        $this->inventoryModel = $this->model('Inventory');
    }

    public function index()
    {
        Auth::requirePermission('stock_card.view');

        $isStorekeeper = Auth::isStorekeeper();
        $warehouseId = $isStorekeeper ? Auth::getWarehouseId() : ($_GET['warehouse_id'] ?? null);
        $productId = $_GET['product_id'] ?? null;
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;

        $transactions = [];
        $product = null;
        $warehouse = null;
        $stockCards = [];

        if ($isStorekeeper) {
            $warehouse = $this->warehouseModel->find($warehouseId);
            $products = $this->productModel->getByWarehouse($warehouseId);
            foreach ($products as $prod) {
                $stockCards[] = [
                    'product' => $prod,
                    'transactions' => $this->transactionModel->getStockCard($warehouseId, $prod['id'])
                ];
            }
        } else {
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
        }

        $warehouses = $this->warehouseModel->getAllActive();
        $warehouseWarnings = $this->inventoryModel->getWarehouseWarnings();
        $warningSummary = $this->inventoryModel->getWarningSummary($isStorekeeper ? $warehouseId : null);
        if ($warehouseId) {
            $products = $products ?? $this->productModel->getByWarehouse($warehouseId);
            $productWarnings = $this->inventoryModel->getProductWarnings($warehouseId);
        } else {
            $products = $products ?? $this->productModel->getAllActive();
            $productWarnings = [];
        }

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
            'endDate' => $endDate,
            'warehouseWarnings' => $warehouseWarnings,
            'productWarnings' => $productWarnings,
            'warningSummary' => $warningSummary,
            'isStorekeeper' => $isStorekeeper,
            'stockCards' => $stockCards
        ]);
    }
}
