<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Chi tiết phiếu nhập #<?= htmlspecialchars($import['code']) ?></h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>import" class="btn btn-secondary">
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
                                <td><?= htmlspecialchars($import['code']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kho nhập:</strong></td>
                                <td><?= htmlspecialchars($import['warehouse_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nhà cung cấp:</strong></td>
                                <td><?= htmlspecialchars($import['supplier_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Ngày nhập:</strong></td>
                                <td><?= date('d/m/Y', strtotime($import['import_date'])) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Trạng thái:</strong></td>
                                <td>
                                    <?php if ($import['status'] === 'pending'): ?>
                                        <span class="badge bg-warning">Chờ duyệt</span>
                                    <?php elseif ($import['status'] === 'approved'): ?>
                                        <span class="badge bg-success">Đã duyệt</span>
                                    <?php elseif ($import['status'] === 'cancelled'): ?>
                                        <span class="badge bg-danger">Đã từ chối</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nháp</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if ($import['status'] === 'cancelled' && !empty($import['reject_reason'])): ?>
                                <tr>
                                    <td><strong>Lý do từ chối:</strong></td>
                                    <td><?= nl2br(htmlspecialchars($import['reject_reason'])) ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td><strong>Tổng tiền:</strong></td>
                                <td><strong class="text-primary"><?= number_format($import['total_amount'] ?? 0) ?> đ</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Người tạo:</strong></td>
                                <td><?= htmlspecialchars($import['created_by_name'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Ngày tạo:</strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($import['created_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($import['notes']): ?>
                    <div class="mb-4">
                        <strong>Ghi chú:</strong>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($import['notes'])) ?></p>
                    </div>
                <?php endif; ?>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Danh sách sản phẩm</h4>
                    <?php if ($import['status'] !== 'approved' && Auth::hasPermission('import.edit')): ?>
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
                                <th>Loại mặt hàng</th>
                                <th>ĐVT</th>
                                <th class="text-end">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                                <?php if ($import['status'] !== 'approved' && Auth::hasPermission('import.edit')): ?>
                                    <th class="w-1"></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($details)): ?>
                                <tr>
                                    <td colspan="<?= ($import['status'] !== 'approved' && Auth::hasPermission('import.edit')) ? 9 : 8 ?>" class="text-center text-muted">
                                        <i class="ti ti-package-off fs-1 mb-2"></i>
                                        <p>Chưa có sản phẩm nào</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($details as $index => $detail): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($detail['product_code']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($detail['product_name']) ?>
                                        <?php if (($detailWarnings[$detail['id']] ?? null) === 'near'): ?>
                                            <i class="ti ti-alert-triangle text-warning ms-1" title="Gần vượt mức tồn kho"></i>
                                        <?php elseif (($detailWarnings[$detail['id']] ?? null) === 'over'): ?>
                                            <i class="ti ti-alert-triangle text-danger ms-1" title="Vượt mức tồn kho"></i>
                                        <?php endif; ?>
                                    </td>
                                        <td><?= htmlspecialchars($detail['product_type_name'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($detail['unit']) ?></td>
                                        <td class="text-end"><?= number_format($detail['quantity'], 2) ?></td>
                                        <td class="text-end"><?= number_format($detail['unit_price']) ?> đ</td>
                                        <td class="text-end"><strong><?= number_format($detail['total_price'] ?? ($detail['amount'] ?? 0)) ?> đ</strong></td>
                                        <?php if ($import['status'] !== 'approved' && Auth::hasPermission('import.edit')): ?>
                                            <td>
                                                <a href="<?= BASE_URL ?>import/removeProduct/<?= $import['id'] ?>/<?= $detail['id'] ?>" 
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

                <?php if (!in_array($import['status'], ['approved', 'cancelled'], true) && !empty($details) && Auth::hasPermission('import.approve')): ?>
                    <div class="mt-4 d-flex gap-2">
                        <a href="<?= BASE_URL ?>import/approve/<?= $import['id'] ?>" 
                           class="btn btn-success"
                           onclick="return confirm('Bạn có chắc chắn muốn duyệt phiếu nhập này? Tồn kho sẽ được cập nhật tự động.')">
                            <i class="ti ti-check me-2"></i>Duyệt phiếu nhập
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectImportModal">
                            <i class="ti ti-x me-2"></i>Từ chối
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Reject Import Modal -->
<div class="modal modal-blur fade" id="rejectImportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>import/reject/<?= $import['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Lý do từ chối phiếu nhập</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label required">Lý do từ chối</label>
                    <textarea name="reject_reason" class="form-control" rows="4" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Add Multiple Products Modal -->
<div class="modal modal-blur fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="<?= BASE_URL ?>import/addProduct/<?= $import['id'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="product-rows">
                        <div class="product-row mb-3 p-3 border rounded">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label required">Sản phẩm</label>
                                    <select name="products[0][product_id]" class="form-select product-select" required>
                                        <option value="">-- Chọn sản phẩm --</option>
                                        <?php foreach ($products as $product): ?>
                                            <option value="<?= $product['id'] ?>">
                                                <?= htmlspecialchars($product['code']) ?> - <?= htmlspecialchars($product['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label required">Loại mặt hàng</label>
                                    <select name="products[0][product_type_id]" class="form-select product-type-select" required>
                                        <option value="">-- Chọn loại --</option>
                                        <?php foreach ($productTypes as $type): ?>
                                            <option value="<?= $type['id'] ?>" data-product-id="<?= htmlspecialchars($type['product_id'] ?? '') ?>">
                                                <?= htmlspecialchars($type['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Số lượng</label>
                                    <input type="number" name="products[0][quantity]" class="form-control" step="0.01" min="0.01" required>
                                </div>
                                <div class="col-md-2">
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

function filterTypeOptions(row) {
    const productSelect = row.querySelector('.product-select');
    const typeSelect = row.querySelector('.product-type-select');
    if (!productSelect || !typeSelect) {
        return;
    }
    const productId = productSelect.value;
    Array.from(typeSelect.options).forEach(option => {
        if (!option.value) {
            option.hidden = false;
            option.disabled = false;
            return;
        }
        const match = option.dataset.productId === productId;
        option.hidden = !match;
        option.disabled = !match;
    });
}

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
    
    filterTypeOptions(newRow);
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

document.getElementById('product-rows').addEventListener('change', function(e) {
    if (!e.target.classList.contains('product-select')) {
        return;
    }
    const row = e.target.closest('.product-row');
    const typeSelect = row.querySelector('.product-type-select');
    if (typeSelect) {
        typeSelect.value = '';
    }
    filterTypeOptions(row);
});

document.querySelectorAll('.product-row').forEach(filterTypeOptions);
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>

