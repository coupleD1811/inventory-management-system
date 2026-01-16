<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chi tiết đơn đặt hàng: <?= htmlspecialchars($purchaseOrder['code']) ?></h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>purchase_order" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Mã đơn:</th>
                                <td><strong><?= htmlspecialchars($purchaseOrder['code']) ?></strong></td>
                            </tr>
                            <tr>
                                <th>Nhà cung cấp:</th>
                                <td><?= htmlspecialchars($purchaseOrder['supplier_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Người liên hệ:</th>
                                <td><?= htmlspecialchars($purchaseOrder['contact_person'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Điện thoại:</th>
                                <td><?= htmlspecialchars($purchaseOrder['supplier_phone'] ?? '-') ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Ngày đặt:</th>
                                <td><?= date('d/m/Y', strtotime($purchaseOrder['order_date'])) ?></td>
                            </tr>
                            <tr>
                                <th>Ngày giao dự kiến:</th>
                                <td><?= $purchaseOrder['expected_delivery_date'] ? date('d/m/Y', strtotime($purchaseOrder['expected_delivery_date'])) : '-' ?></td>
                            </tr>
                            <tr>
                                <th>Người tạo:</th>
                                <td><?= htmlspecialchars($purchaseOrder['created_by_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    <?php
                                    $statusBadges = [
                                        'draft' => '<span class="badge bg-secondary">Nháp</span>',
                                        'sent' => '<span class="badge bg-info">Đã gửi</span>',
                                        'confirmed' => '<span class="badge bg-success">Đã duyệt</span>',
                                        'received' => '<span class="badge bg-primary">Đã nhận</span>',
                                        'cancelled' => '<span class="badge bg-danger">Đã hủy</span>'
                                    ];
                                    echo $statusBadges[$purchaseOrder['status']] ?? '';
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if (!empty($purchaseOrder['notes'])): ?>
                    <div class="alert alert-info">
                        <strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($purchaseOrder['notes'])) ?>
                    </div>
                <?php endif; ?>

                <h4 class="mt-4">Chi tiết sản phẩm</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Sản phẩm</th>
                                <th>Đơn vị</th>
                                <th class="text-end">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($details)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Chưa có sản phẩm</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($details as $index => $detail): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($detail['product_name']) ?></td>
                                        <td><?= htmlspecialchars($detail['unit']) ?></td>
                                        <td class="text-end"><?= number_format($detail['quantity']) ?></td>
                                        <td class="text-end"><?= number_format($detail['unit_price'], 0, ',', '.') ?> đ</td>
                                        <td class="text-end"><strong><?= number_format($detail['total_price'], 0, ',', '.') ?> đ</strong></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-active">
                                    <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td class="text-end"><strong class="text-primary"><?= number_format($purchaseOrder['total_amount'], 0, ',', '.') ?> đ</strong></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php if (Auth::hasPermission('purchase_order.approve') && in_array($purchaseOrder['status'], ['draft', 'sent'])): ?>
                        <a href="<?= BASE_URL ?>purchase_order/approve/<?= $purchaseOrder['id'] ?>" 
                           class="btn btn-success"
                           onclick="return confirm('Bạn có chắc chắn muốn duyệt đơn đặt hàng này?')">
                            <i class="ti ti-check me-2"></i>Duyệt đơn
                        </a>
                    <?php endif; ?>
                    <?php if (Auth::hasPermission('purchase_order.edit') && !in_array($purchaseOrder['status'], ['confirmed', 'received'])): ?>
                        <a href="<?= BASE_URL ?>purchase_order/edit/<?= $purchaseOrder['id'] ?>" class="btn btn-primary">
                            <i class="ti ti-edit me-2"></i>Sửa
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>purchase_order" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
