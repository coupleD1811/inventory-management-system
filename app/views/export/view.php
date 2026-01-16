<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chi tiết phiếu xuất #<?= htmlspecialchars($export['code']) ?></h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>export" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Mã phiếu:</strong></td>
                                <td><?= htmlspecialchars($export['code']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kho xuất:</strong></td>
                                <td><?= htmlspecialchars($export['warehouse_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Khách hàng:</strong></td>
                                <td><?= htmlspecialchars($export['customer_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Loại xuất:</strong></td>
                                <td>
                                    <?php
                                    $types = [
                                        'sale' => 'Bán hàng',
                                        'internal' => 'Nội bộ',
                                        'damaged' => 'Hư hỏng',
                                        'other' => 'Khác'
                                    ];
                                    echo $types[$export['export_type']] ?? $export['export_type'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Ngày xuất:</strong></td>
                                <td><?= date('d/m/Y', strtotime($export['export_date'])) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Trạng thái:</strong></td>
                                <td>
                                    <?php if ($export['status'] === 'pending'): ?>
                                        <span class="badge bg-warning">Chờ duyệt</span>
                                    <?php elseif ($export['status'] === 'approved'): ?>
                                        <span class="badge bg-success">Đã duyệt</span>
                                    <?php elseif ($export['status'] === 'cancelled'): ?>
                                        <span class="badge bg-danger">Đã từ chối</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nháp</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tổng tiền:</strong></td>
                                <td><strong class="text-primary"><?= number_format($export['total_amount']) ?> đ</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Người tạo:</strong></td>
                                <td><?= htmlspecialchars($export['created_by_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Ngày tạo:</strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($export['created_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($export['notes']): ?>
                    <div class="mb-4">
                        <strong>Ghi chú:</strong>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($export['notes'])) ?></p>
                    </div>
                <?php endif; ?>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Danh sách sản phẩm</h4>
                    <?php if ($export['status'] !== 'approved' && Auth::hasPermission('export.edit')): ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="ti ti-plus me-2"></i>Thêm sản phẩm
                        </button>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã SP</th>
                                <th>Tên sản phẩm</th>
                                <th>ĐVT</th>
                                <th class="text-end">Số lượng</th>
                                <th class="text-end">Giá vốn</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                                <th class="text-end">Lời/Lỗ</th>
                                <?php if ($export['status'] !== 'approved' && Auth::hasPermission('export.edit')): ?>
                                    <th class="w-1"></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($details)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="ti ti-package-off fs-1 mb-2"></i>
                                        <p>Chưa có sản phẩm nào</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($details as $index => $detail): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($detail['product_code']) ?></td>
                                        <td><?= htmlspecialchars($detail['product_name']) ?></td>
                                        <td><?= htmlspecialchars($detail['unit']) ?></td>
                                        <td class="text-end"><?= number_format($detail['quantity'], 2) ?></td>
                                        <td class="text-end"><?= number_format($detail['cost_price'] ?? 0) ?> đ</td>
                                        <td class="text-end"><?= number_format($detail['unit_price']) ?> đ</td>
                                        <td class="text-end"><strong><?= number_format($detail['total_price'] ?? $detail['amount']) ?> đ</strong></td>
                                        <td class="text-end">
                                            <?php 
                                            $profit = $detail['profit'] ?? 0;
                                            $profitClass = $profit >= 0 ? 'text-success' : 'text-danger';
                                            ?>
                                            <strong class="<?= $profitClass ?>">
                                                <?= number_format($profit) ?> đ
                                            </strong>
                                        </td>
                                        <?php if ($export['status'] !== 'approved' && Auth::hasPermission('export.edit')): ?>
                                            <td>
                                                <a href="<?= BASE_URL ?>export/removeProduct/<?= $export['id'] ?>/<?= $detail['id'] ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!in_array($export['status'], ['approved', 'cancelled'], true) && !empty($details) && Auth::hasPermission('export.approve')): ?>
                    <div class="mt-4 d-flex gap-2">
                        <a href="<?= BASE_URL ?>export/approve/<?= $export['id'] ?>" 
                           class="btn btn-success"
                           onclick="return confirm('Bạn có chắc chắn muốn duyệt phiếu xuất này? Tồn kho sẽ được cập nhật tự động.')">
                            <i class="ti ti-check me-2"></i>Duyệt phiếu xuất
                        </a>
                        <a href="<?= BASE_URL ?>export/reject/<?= $export['id'] ?>" 
                           class="btn btn-outline-danger"
                           onclick="return confirm('Bạn có chắc chắn muốn từ chối phiếu xuất này?')">
                            <i class="ti ti-x me-2"></i>Từ chối
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- Add Multiple Products Modal -->
<div class="modal modal-blur fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>export/addProduct/<?= $export['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="product-rows">
                        <div class="product-row mb-3 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="form-label required">Sản phẩm</label>
                                    <select name="products[0][product_id]" class="form-select" required>
                                        <option value="">-- Chọn sản phẩm --</option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?= $product['id'] ?>">
                                                <?= htmlspecialchars($product['code']) ?> - <?= htmlspecialchars($product['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Số lượng</label>
                                    <input type="number" name="products[0][quantity]" class="form-control" step="0.01" min="0.01" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Đơn giá</label>
                                    <input type="number" name="products[0][unit_price]" class="form-control" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-icon remove-row" disabled>
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary" id="add-more-products">
                        <i class="ti ti-plus me-2"></i>Thêm sản phẩm khác
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu tất cả</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let productRowIndex = 1;

document.getElementById('add-more-products').addEventListener('click', function() {
    const container = document.getElementById('product-rows');
    const firstRow = document.querySelector('.product-row');
    const newRow = firstRow.cloneNode(true);
    
    // Update name attributes
    newRow.querySelectorAll('select, input').forEach(field => {
        const name = field.getAttribute('name');
        field.setAttribute('name', name.replace('[0]', `[${productRowIndex}]`));
        if (field.tagName === 'SELECT') {
            field.selectedIndex = 0;
        } else {
            field.value = '';
        }
    });
    
    // Enable remove button
    newRow.querySelector('.remove-row').disabled = false;
    
    container.appendChild(newRow);
    productRowIndex++;
    
    updateRemoveButtons();
});

document.getElementById('product-rows').addEventListener('click', function(e) {
    if (e.target.closest('.remove-row')) {
        e.target.closest('.product-row').remove();
        updateRemoveButtons();
    }
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.product-row');
    rows.forEach((row, index) => {
        const btn = row.querySelector('.remove-row');
        btn.disabled = rows.length === 1;
    });
}
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
