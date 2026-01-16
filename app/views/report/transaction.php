<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Báo cáo giao dịch kho</h3>
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
                                <th>Thời gian</th>
                                <th>Kho</th>
                                <th>Sản phẩm</th>
                                <th>Loại giao dịch</th>
                                <th class="text-end">Số lượng</th>
                                <th class="text-end">Tồn trước</th>
                                <th class="text-end">Tồn sau</th>
                                <th>Người tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transactions as $index => $trans): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($trans['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($trans['warehouse_name']) ?></td>
                                        <td><?= htmlspecialchars($trans['product_name']) ?></td>
                                        <td>
                                            <?php
                                            $types = [
                                                'import' => '<span class="badge bg-success">Nhập kho</span>',
                                                'export' => '<span class="badge bg-danger">Xuất kho</span>',
                                                'adjustment' => '<span class="badge bg-warning">Điều chỉnh</span>'
                                            ];
                                            echo $types[$trans['transaction_type']] ?? $trans['transaction_type'];
                                            ?>
                                        </td>
                                        <td class="text-end">
                                            <strong class="<?= $trans['quantity'] > 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= $trans['quantity'] > 0 ? '+' : '' ?><?= number_format($trans['quantity']) ?>
                                            </strong>
                                        </td>
                                        <td class="text-end"><?= number_format($trans['quantity_before']) ?></td>
                                        <td class="text-end"><strong><?= number_format($trans['quantity_after']) ?></strong></td>
                                        <td><?= htmlspecialchars($trans['created_by_name'] ?? '-') ?></td>
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
