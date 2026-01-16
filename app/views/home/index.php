<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Statistics Cards -->
<div class="row row-deck row-cards mb-3">
    <!-- Tổng sản phẩm -->
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Tổng sản phẩm</div>
                </div>
                <div class="h1 mb-0"><?= number_format($totalProducts) ?></div>
                <div class="text-muted mt-1">
                    <i class="ti ti-box me-1"></i>Đang quản lý
                </div>
            </div>
        </div>
    </div>

    <!-- Nhập kho tháng này -->
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Nhập kho (<?= $currentMonth ?>)</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2"><?= number_format($importStats['approved_imports'] ?? 0) ?></div>
                    <div class="me-auto">
                        <span class="text-muted">/ <?= number_format($importStats['total_imports'] ?? 0) ?> phiếu</span>
                    </div>
                </div>
                <div class="text-success mt-1">
                    <i class="ti ti-arrow-down-circle me-1"></i><?= number_format($importStats['total_import_value'] ?? 0) ?> đ
                </div>
            </div>
        </div>
    </div>

    <!-- Xuất kho tháng này -->
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Xuất kho (<?= $currentMonth ?>)</div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2"><?= number_format($exportStats['approved_exports'] ?? 0) ?></div>
                    <div class="me-auto">
                        <span class="text-muted">/ <?= number_format($exportStats['total_exports'] ?? 0) ?> phiếu</span>
                    </div>
                </div>
                <div class="text-warning mt-1">
                    <i class="ti ti-arrow-up-circle me-1"></i><?= number_format($exportStats['total_export_value'] ?? 0) ?> đ
                </div>
            </div>
        </div>
    </div>

    <!-- Lợi nhuận tháng này -->
    <?php if ($profitStats): ?>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Lợi nhuận (<?= $currentMonth ?>)</div>
                    </div>
                    <div class="h1 mb-0 <?= (($profitStats['profit'] ?? 0) >= 0) ? 'text-success' : 'text-danger' ?>">
                        <?= number_format($profitStats['profit'] ?? 0) ?> đ
                    </div>
                    <div class="text-muted mt-1">
                        <?php 
                        $revenue = $profitStats['revenue'] ?? 0;
                        $profit = $profitStats['profit'] ?? 0;
                        $margin = $revenue > 0 ? ($profit / $revenue * 100) : 0;
                        ?>
                        <i class="ti ti-chart-line me-1"></i>Tỷ suất: <?= number_format($margin, 1) ?>%
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader">Phiếu chờ duyệt</div>
                    </div>
                    <div class="h1 mb-0"><?= $pendingImports + $pendingExports ?></div>
                    <div class="text-muted mt-1">
                        <i class="ti ti-clock me-1"></i><?= $pendingImports ?> nhập, <?= $pendingExports ?> xuất
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="row row-deck row-cards">
    <!-- Cảnh báo tồn kho thấp -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-alert-triangle text-warning me-2"></i>Cảnh báo tồn kho thấp
                </h3>
            </div>
            <div class="card-body p-0">
                <?php if (empty($lowStockProducts)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-check-circle fs-1 text-success mb-2"></i>
                        <p>Tất cả sản phẩm đều đủ tồn kho</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Kho</th>
                                    <th class="text-end">Tồn kho</th>
                                    <th class="text-end">Tối thiểu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockProducts as $item): ?>
                                    <tr>
                                        <td>
                                            <div><?= htmlspecialchars($item['name']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($item['code']) ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($item['warehouse_name']) ?></td>
                                        <td class="text-end">
                                            <span class="badge bg-danger"><?= number_format($item['quantity'], 2) ?></span>
                                        </td>
                                        <td class="text-end text-muted"><?= number_format($item['min_stock'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top sản phẩm bán chạy -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ti ti-trending-up text-success me-2"></i>Top sản phẩm xuất nhiều (<?= $currentMonth ?>)
                </h3>
            </div>
            <div class="card-body p-0">
                <?php if (empty($topProducts)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-package-off fs-1 mb-2"></i>
                        <p>Chưa có dữ liệu xuất kho tháng này</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Số lượng</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topProducts as $index => $product): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">#<?= $index + 1 ?></span>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($product['name']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($product['code']) ?></div>
                                        </td>
                                        <td class="text-end">
                                            <strong><?= number_format($product['total_quantity'], 2) ?></strong> <?= htmlspecialchars($product['unit']) ?>
                                        </td>
                                        <td class="text-end text-success">
                                            <strong><?= number_format($product['total_revenue']) ?> đ</strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<?php if ($pendingImports > 0 || $pendingExports > 0): ?>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-auto">
                            <h3 class="mb-1">
                                <i class="ti ti-bell-ringing text-warning me-2"></i>Có phiếu chờ duyệt
                            </h3>
                            <p class="text-muted mb-0">
                                <?php if ($pendingImports > 0): ?>
                                    <strong><?= $pendingImports ?></strong> phiếu nhập
                                <?php endif; ?>
                                <?php if ($pendingImports > 0 && $pendingExports > 0): ?>
                                    và
                                <?php endif; ?>
                                <?php if ($pendingExports > 0): ?>
                                    <strong><?= $pendingExports ?></strong> phiếu xuất
                                <?php endif; ?>
                                đang chờ duyệt
                            </p>
                        </div>
                        <div>
                            <?php if ($pendingImports > 0 && Auth::hasPermission('import.approve')): ?>
                                <a href="<?= BASE_URL ?>import" class="btn btn-success me-2">
                                    <i class="ti ti-arrow-down-circle me-2"></i>Duyệt phiếu nhập
                                </a>
                            <?php endif; ?>
                            <?php if ($pendingExports > 0 && Auth::hasPermission('export.approve')): ?>
                                <a href="<?= BASE_URL ?>export" class="btn btn-warning">
                                    <i class="ti ti-arrow-up-circle me-2"></i>Duyệt phiếu xuất
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>
