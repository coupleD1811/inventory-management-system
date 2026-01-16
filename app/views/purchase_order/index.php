<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách đơn đặt hàng</h3>
                <div class="card-actions">
                    <?php if (Auth::hasPermission('purchase_order.create')): ?>
                        <a href="<?= BASE_URL ?>purchase_order/create" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Tạo đơn đặt hàng
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã hoặc nhà cung cấp..." value="<?= htmlspecialchars($search ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>Nháp</option>
                                <option value="sent" <?= ($status ?? '') === 'sent' ? 'selected' : '' ?>>Đã gửi</option>
                                <option value="confirmed" <?= ($status ?? '') === 'confirmed' ? 'selected' : '' ?>>Đã duyệt</option>
                                <option value="received" <?= ($status ?? '') === 'received' ? 'selected' : '' ?>>Đã nhận</option>
                                <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-2">
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
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($purchaseOrders)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="ti ti-file-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($purchaseOrders as $index => $po): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($po['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($po['supplier_name']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($po['order_date'])) ?></td>
                                        <td><?= $po['expected_delivery_date'] ? date('d/m/Y', strtotime($po['expected_delivery_date'])) : '-' ?></td>
                                        <td><strong><?= number_format($po['total_amount'], 0, ',', '.') ?> đ</strong></td>
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
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL ?>purchase_order/detail/<?= $po['id'] ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Xem">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <?php if (Auth::hasPermission('purchase_order.edit') && !in_array($po['status'], ['confirmed', 'received'])): ?>
                                                    <a href="<?= BASE_URL ?>purchase_order/edit/<?= $po['id'] ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Sửa">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::hasPermission('purchase_order.approve') && in_array($po['status'], ['draft', 'sent'])): ?>
                                                    <a href="<?= BASE_URL ?>purchase_order/approve/<?= $po['id'] ?>" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Duyệt"
                                                       onclick="return confirm('Bạn có chắc chắn muốn duyệt đơn đặt hàng này?')">
                                                        <i class="ti ti-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::hasPermission('purchase_order.delete') && !in_array($po['status'], ['confirmed', 'received'])): ?>
                                                    <a href="<?= BASE_URL ?>purchase_order/delete/<?= $po['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa đơn đặt hàng này?')">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
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
