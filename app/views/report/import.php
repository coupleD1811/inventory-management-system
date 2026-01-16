<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards mb-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Tổng số phiếu</div>
                <div class="h1 mb-0"><?= number_format($stats['total_imports'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Tổng giá trị</div>
                <div class="h1 mb-0 text-primary"><?= number_format($stats['total_value'] ?? 0, 0, ',', '.') ?> đ</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Đã duyệt</div>
                <div class="h1 mb-0 text-success"><?= number_format($stats['approved_imports'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Nhà cung cấp</div>
                <div class="h1 mb-0"><?= number_format($stats['total_suppliers'] ?? 0) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Báo cáo nhập kho</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kho</label>
                            <select name="warehouse_id" class="form-select">
                                <option value="">-- Tất cả kho --</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <option value="<?= $wh['id'] ?>" <?= ($warehouseId == $wh['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($wh['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter"></i> Lọc
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã phiếu</th>
                                <th>Kho</th>
                                <th>Nhà cung cấp</th>
                                <th>Ngày nhập</th>
                                <th class="text-end">Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($imports)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($imports as $index => $import): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($import['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($import['warehouse_name']) ?></td>
                                        <td><?= htmlspecialchars($import['supplier_name'] ?? '-') ?></td>
                                        <td><?= date('d/m/Y', strtotime($import['import_date'])) ?></td>
                                        <td class="text-end"><strong><?= number_format($import['total_amount'], 0, ',', '.') ?> đ</strong></td>
                                        <td>
                                            <?php if ($import['status'] === 'approved'): ?>
                                                <span class="badge bg-success">Đã duyệt</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Chờ duyệt</span>
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
