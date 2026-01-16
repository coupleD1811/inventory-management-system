<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards mb-3">
    <!-- Statistics Cards -->
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Tổng số kho</div>
                </div>
                <div class="h1 mb-0"><?= $stats['total_warehouses'] ?></div>
                <div class="text-muted">Kho đang hoạt động</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Tổng sản phẩm</div>
                </div>
                <div class="h1 mb-0"><?= $stats['total_products'] ?></div>
                <div class="text-muted">Sản phẩm đang quản lý</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Cảnh báo tồn thấp</div>
                </div>
                <div class="h1 mb-0 text-warning"><?= $stats['low_stock_count'] ?></div>
                <div class="text-muted">Sản phẩm dưới mức tối thiểu</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Phiếu chờ duyệt</div>
                </div>
                <div class="h1 mb-0 text-info"><?= $stats['pending_imports'] + $stats['pending_exports'] ?></div>
                <div class="text-muted"><?= $stats['pending_imports'] ?> nhập, <?= $stats['pending_exports'] ?> xuất</div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <!-- Warehouse Overview -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tổng quan các kho</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($warehouses as $warehouse): ?>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ti ti-building-warehouse fs-1 text-primary me-2"></i>
                                        <div>
                                            <h4 class="mb-0"><?= htmlspecialchars($warehouse['name']) ?></h4>
                                            <small class="text-muted"><?= htmlspecialchars($warehouse['code']) ?></small>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Số loại SP:</span>
                                            <strong><?= $warehouse['total_products'] ?? 0 ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Tổng số lượng:</span>
                                            <strong><?= number_format($warehouse['total_quantity'] ?? 0) ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Sức chứa:</span>
                                            <strong><?= number_format($warehouse['capacity']) ?></strong>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="<?= BASE_URL ?>inventory?warehouse_id=<?= $warehouse['id'] ?>" class="btn btn-sm btn-primary w-100">
                                            <i class="ti ti-eye me-1"></i>Xem tồn kho
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mt-3">
    <!-- Low Stock Alerts -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-alert-triangle text-warning me-2"></i>
                    Cảnh báo tồn kho thấp
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Kho</th>
                                <th>Sản phẩm</th>
                                <th class="text-end">Tồn hiện tại</th>
                                <th class="text-end">Mức tối thiểu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($lowStockItems)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="ti ti-check fs-1 text-success"></i>
                                        <p>Không có cảnh báo</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($lowStockItems as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['warehouse_name']) ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($item['product_code']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($item['product_name']) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-warning"><?= number_format($item['quantity']) ?> <?= $item['unit'] ?></span>
                                        </td>
                                        <td class="text-end"><?= number_format($item['min_stock']) ?> <?= $item['unit'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Overstock Alerts -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-alert-circle text-info me-2"></i>
                    Cảnh báo tồn kho cao
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Kho</th>
                                <th>Sản phẩm</th>
                                <th class="text-end">Tồn hiện tại</th>
                                <th class="text-end">Mức tối đa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($overStockItems)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="ti ti-check fs-1 text-success"></i>
                                        <p>Không có cảnh báo</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($overStockItems as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['warehouse_name']) ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($item['product_code']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($item['product_name']) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-info"><?= number_format($item['quantity']) ?> <?= $item['unit'] ?></span>
                                        </td>
                                        <td class="text-end"><?= number_format($item['max_stock']) ?> <?= $item['unit'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mt-3">
    <!-- Recent Imports -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-arrow-down-circle text-success me-2"></i>
                    Nhập kho gần đây (7 ngày)
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Mã phiếu</th>
                                <th>Kho</th>
                                <th>Ngày nhập</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentImports)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Chưa có phiếu nhập</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentImports as $import): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= BASE_URL ?>import/detail/<?= $import['id'] ?>">
                                                <?= htmlspecialchars($import['code']) ?>
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars($import['warehouse_name']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($import['import_date'])) ?></td>
                                        <td>
                                            <?php if ($import['status'] === 'approved'): ?>
                                                <span class="badge bg-success">Đã duyệt</span>
                                            <?php elseif ($import['status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Chờ duyệt</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nháp</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Exports -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-arrow-up-circle text-danger me-2"></i>
                    Xuất kho gần đây (7 ngày)
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Mã phiếu</th>
                                <th>Kho</th>
                                <th>Ngày xuất</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentExports)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Chưa có phiếu xuất</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentExports as $export): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= BASE_URL ?>export/detail/<?= $export['id'] ?>">
                                                <?= htmlspecialchars($export['code']) ?>
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars($export['warehouse_name']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($export['export_date'])) ?></td>
                                        <td>
                                            <?php if ($export['status'] === 'approved'): ?>
                                                <span class="badge bg-success">Đã duyệt</span>
                                            <?php elseif ($export['status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Chờ duyệt</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nháp</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
