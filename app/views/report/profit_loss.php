<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Báo cáo Lời/Lỗ</h3>
            </div>
            <div class="card-body">
                <!-- Form lọc -->
                <form method="GET" action="<?= BASE_URL ?>report/profitLoss" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="<?= $startDate ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="end_date" class="form-control" 
                               value="<?= $endDate ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kho</label>
                        <select name="warehouse_id" class="form-select">
                            <option value="">-- Tất cả kho --</option>
                            <?php foreach ($warehouses as $warehouse): ?>
                                <option value="<?= $warehouse['id'] ?>" 
                                    <?= $warehouseId == $warehouse['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($warehouse['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-2"></i>Lọc
                        </button>
                    </div>
                </form>

                <!-- Thống kê tổng hợp -->
                <?php if ($stats): ?>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="text-muted small">Tổng phiếu xuất</div>
                                    <div class="h2 mb-0"><?= number_format($stats['total_exports'] ?? 0) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="small">Doanh thu</div>
                                    <div class="h3 mb-0"><?= number_format($stats['total_revenue'] ?? 0) ?> đ</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="small">Giá vốn</div>
                                    <div class="h3 mb-0"><?= number_format($stats['total_cost'] ?? 0) ?> đ</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card <?= ($stats['total_profit'] ?? 0) >= 0 ? 'bg-success' : 'bg-danger' ?> text-white">
                                <div class="card-body">
                                    <div class="small">Lợi nhuận</div>
                                    <div class="h3 mb-0"><?= number_format($stats['total_profit'] ?? 0) ?> đ</div>
                                    <div class="small">
                                        Tỷ suất: <?= number_format($stats['avg_profit_margin'] ?? 0, 2) ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Bảng chi tiết -->
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Ngày xuất</th>
                                <th>Mã phiếu</th>
                                <th>Kho</th>
                                <th>Sản phẩm</th>
                                <th class="text-end">SL</th>
                                <th class="text-end">Giá vốn</th>
                                <th class="text-end">Giá bán</th>
                                <th class="text-end">Doanh thu</th>
                                <th class="text-end">Chi phí</th>
                                <th class="text-end">Lời/Lỗ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($details)): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="ti ti-file-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($details as $detail): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($detail['export_date'])) ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>export/detail/<?= $detail['id'] ?>">
                                                <?= htmlspecialchars($detail['code']) ?>
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars($detail['warehouse_name']) ?></td>
                                        <td>
                                            <div><?= htmlspecialchars($detail['product_name']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($detail['product_code']) ?></div>
                                        </td>
                                        <td class="text-end"><?= number_format($detail['quantity'] ?? 0, 2) ?></td>
                                        <td class="text-end"><?= number_format($detail['cost_price'] ?? 0) ?> đ</td>
                                        <td class="text-end"><?= number_format($detail['sell_price'] ?? 0) ?> đ</td>
                                        <td class="text-end"><?= number_format($detail['revenue'] ?? 0) ?> đ</td>
                                        <td class="text-end"><?= number_format($detail['total_cost'] ?? 0) ?> đ</td>
                                        <td class="text-end">
                                            <strong class="<?= ($detail['total_profit'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= number_format($detail['total_profit'] ?? 0) ?> đ
                                            </strong>
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
