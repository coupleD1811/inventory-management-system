<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tạo đơn đặt hàng</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>purchase_order" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>purchase_order/create" id="purchaseOrderForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Nhà cung cấp</label>
                                <select name="supplier_id" class="form-select" required>
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    <?php foreach ($suppliers as $sup): ?>
                                        <option value="<?= $sup['id'] ?>" <?= ($old['supplier_id'] ?? '') == $sup['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($sup['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Ngày đặt</label>
                                <input type="date" name="order_date" class="form-control" value="<?= htmlspecialchars($old['order_date'] ?? date('Y-m-d')) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Ngày giao dự kiến</label>
                                <input type="date" name="expected_delivery_date" class="form-control" value="<?= htmlspecialchars($old['expected_delivery_date'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                    </div>

                    <hr>
                    <h4>Sản phẩm đặt hàng</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="productTable">
                            <thead>
                                <tr>
                                    <th width="40%">Sản phẩm</th>
                                    <th width="15%">Số lượng</th>
                                    <th width="20%">Đơn giá</th>
                                    <th width="20%">Thành tiền</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="productRows">
                                <tr class="product-row">
                                    <td>
                                        <select name="products[0][product_id]" class="form-select product-select" required>
                                            <option value="">-- Chọn sản phẩm --</option>
                                            <?php foreach ($products as $prod): ?>
                                                <option value="<?= $prod['id'] ?>" data-unit="<?= htmlspecialchars($prod['unit']) ?>">
                                                    <?= htmlspecialchars($prod['name']) ?> (<?= htmlspecialchars($prod['unit']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input type="number" name="products[0][quantity]" class="form-control quantity-input" min="1" required></td>
                                    <td><input type="number" name="products[0][unit_price]" class="form-control price-input" min="0" step="0.01" required></td>
                                    <td><input type="text" class="form-control total-display" readonly></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove-row" disabled><i class="ti ti-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-success" id="addRow">
                        <i class="ti ti-plus me-2"></i>Thêm sản phẩm
                    </button>

                    <div class="row mt-3">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4>Tổng tiền: <span id="grandTotal">0</span> đ</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu đơn đặt hàng
                        </button>
                        <a href="<?= BASE_URL ?>purchase_order" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let rowIndex = 1;

document.getElementById('addRow').addEventListener('click', function() {
    const tbody = document.getElementById('productRows');
    const newRow = tbody.querySelector('.product-row').cloneNode(true);
    
    // Update name attributes
    newRow.querySelectorAll('select, input').forEach(el => {
        if (el.name) {
            el.name = el.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
        }
        if (el.tagName === 'INPUT') el.value = '';
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
    });
    
    newRow.querySelector('.remove-row').disabled = false;
    tbody.appendChild(newRow);
    rowIndex++;
    updateRemoveButtons();
});

document.getElementById('productRows').addEventListener('click', function(e) {
    if (e.target.closest('.remove-row')) {
        e.target.closest('.product-row').remove();
        updateRemoveButtons();
        calculateTotal();
    }
});

document.getElementById('productRows').addEventListener('input', function(e) {
    if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
        const row = e.target.closest('.product-row');
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = qty * price;
        row.querySelector('.total-display').value = total.toLocaleString('vi-VN');
        calculateTotal();
    }
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.product-row');
    rows.forEach((row, index) => {
        row.querySelector('.remove-row').disabled = rows.length === 1;
    });
}

function calculateTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.product-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        grandTotal += qty * price;
    });
    document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('vi-VN');
}
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
