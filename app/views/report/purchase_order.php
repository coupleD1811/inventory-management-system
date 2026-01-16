<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards mb-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Tổng đơn</div>
                <div class="h1 mb-0"><?= number_format($stats['total_orders'] ?? 0) ?></div>
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
                <div class="h1 mb-0 text-success"><?= number_format($stats['confirmed_orders'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Đã nhận</div>
                <div class="h1 mb-0 text-info"><?= number_format($stats['received_orders'] ?? 0) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Báo cáo đơn đặt hàng</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
                        </div>
                        <div class="col-md-4">
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
                                <th>Mã đơn</th>
                                <th>Nhà cung cấp</th>
                                <th>Ngày đặt</th>
                                <th>Ngày giao dự kiến</th>
                                <th class="text-end">Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($purchaseOrders)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($purchaseOrders as $index => $po): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($po['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($po['supplier_name']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($po['order_date'])) ?></td>
                                        <td><?= $po['expected_delivery_date'] ? date('d/m/Y', strtotime($po['expected_delivery_date'])) : '-' ?></td>
                                        <td class="text-end"><strong><?= number_format($po['total_amount'], 0, ',', '.') ?> đ</strong></td>
                                        <td>
                                            <?php
                                            $statusBadges = [
                                                'draft' => '<span class="badge bg-secondary">Nháp</span>',
                                                'sent' => '<span class="badge bg-info">Đã gửi</span>',
                                                'confirmed' => '<span class="badge bg-success">Đã duyệt</span>',
                                                'received' => '<span class="badge bg-primary">Đã nhận</span>',
                                                'cancelled' => '<span class="badge bg-danger">Đã hủy</span>'
                                            ];
                                            echo $statusBadges[$po['status']] ?? '';
                                            ?>
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
