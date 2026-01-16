<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chi tiết phiếu kiểm kê: <?= htmlspecialchars($stockTake['code']) ?></h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>stock_take" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Mã phiếu:</th>
                                <td><strong><?= htmlspecialchars($stockTake['code']) ?></strong></td>
                            </tr>
                            <tr>
                                <th>Kho:</th>
                                <td><?= htmlspecialchars($stockTake['warehouse_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Ngày kiểm kê:</th>
                                <td><?= date('d/m/Y', strtotime($stockTake['stock_take_date'])) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Người tạo:</th>
                                <td><?= htmlspecialchars($stockTake['created_by_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Người duyệt:</th>
                                <td><?= htmlspecialchars($stockTake['approved_by_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    <?php
                                    $statusBadges = [
                                        'draft' => '<span class="badge bg-secondary">Nháp</span>',
                                        'in_progress' => '<span class="badge bg-info">Đang kiểm kê</span>',
                                        'completed' => '<span class="badge bg-success">Hoàn thành</span>',
                                        'cancelled' => '<span class="badge bg-danger">Đã hủy</span>'
                                    ];
                                    echo $statusBadges[$stockTake['status']] ?? '';
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if (!empty($stockTake['notes'])): ?>
                    <div class="alert alert-info">
                        <strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($stockTake['notes'])) ?>
                    </div>
                <?php endif; ?>

                <h4 class="mt-4">Kết quả kiểm kê</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Sản phẩm</th>
                                <th>Đơn vị</th>
                                <th class="text-end">Tồn kho hệ thống</th>
                                <th class="text-end">Số lượng thực tế</th>
                                <th class="text-end">Chênh lệch</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($details)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Chưa có dữ liệu kiểm kê</td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                $totalVariance = 0;
                                foreach ($details as $index => $detail): 
                                    $totalVariance += $detail['variance'];
                                ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($detail['product_name']) ?></td>
                                        <td><?= htmlspecialchars($detail['unit']) ?></td>
                                        <td class="text-end"><?= number_format($detail['system_quantity']) ?></td>
                                        <td class="text-end"><strong><?= number_format($detail['actual_quantity']) ?></strong></td>
                                        <td class="text-end">
                                            <?php if ($detail['variance'] > 0): ?>
                                                <span class="text-success fw-bold">+<?= number_format($detail['variance']) ?></span>
                                            <?php elseif ($detail['variance'] < 0): ?>
                                                <span class="text-danger fw-bold"><?= number_format($detail['variance']) ?></span>
                                            <?php else: ?>
                                                <span>0</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="table-active">
                                    <td colspan="5" class="text-end"><strong>Tổng chênh lệch:</strong></td>
                                    <td class="text-end">
                                        <strong class="<?= $totalVariance > 0 ? 'text-success' : ($totalVariance < 0 ? 'text-danger' : '') ?>">
                                            <?= $totalVariance > 0 ? '+' : '' ?><?= number_format($totalVariance) ?>
                                        </strong>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($stockTake['status'] === 'completed'): ?>
                    <div class="alert alert-success mt-3">
                        <i class="ti ti-check me-2"></i>
                        Phiếu kiểm kê đã được duyệt và tồn kho đã được điều chỉnh.
                    </div>
                <?php endif; ?>

                <div class="mt-3">
                    <?php if (Auth::hasPermission('stock_take.approve') && $stockTake['status'] !== 'completed'): ?>
                        <a href="<?= BASE_URL ?>stock_take/approve/<?= $stockTake['id'] ?>" 
                           class="btn btn-success"
                           onclick="return confirm('Bạn có chắc chắn muốn duyệt phiếu kiểm kê này?\n\nTồn kho sẽ được điều chỉnh theo số lượng thực tế.')">
                            <i class="ti ti-check me-2"></i>Duyệt phiếu kiểm kê
                        </a>
                    <?php endif; ?>
                    <?php if (Auth::hasPermission('stock_take.edit') && $stockTake['status'] !== 'completed'): ?>
                        <a href="<?= BASE_URL ?>stock_take/edit/<?= $stockTake['id'] ?>" class="btn btn-primary">
                            <i class="ti ti-edit me-2"></i>Sửa
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>stock_take" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
