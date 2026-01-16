<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tạo phiếu kiểm kê</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>stock_take" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>stock_take/create" id="stockTakeForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Kho kiểm kê</label>
                                <select name="warehouse_id" id="warehouseSelect" class="form-select" required>
                                    <option value="">-- Chọn kho --</option>
                                    <?php foreach ($warehouses as $wh): ?>
                                        <option value="<?= $wh['id'] ?>">
                                            <?= htmlspecialchars($wh['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày kiểm kê</label>
                                <input type="date" name="stock_take_date" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <div id="inventorySection" style="display: none;">
                        <hr>
                        <h4>Kiểm đếm sản phẩm</h4>
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            Nhập số lượng thực tế kiểm đếm được. Chênh lệch sẽ được tính tự động.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Đơn vị</th>
                                        <th class="text-end">Tồn kho hệ thống</th>
                                        <th class="text-end">Số lượng thực tế</th>
                                        <th class="text-end">Chênh lệch</th>
                                    </tr>
                                </thead>
                                <tbody id="inventoryTable">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Chọn kho để tải tồn kho</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-footer mt-3">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="ti ti-device-floppy me-2"></i>Lưu phiếu kiểm kê
                        </button>
                        <a href="<?= BASE_URL ?>stock_take" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('warehouseSelect').addEventListener('change', function() {
    const warehouseId = this.value;
    if (!warehouseId) {
        document.getElementById('inventorySection').style.display = 'none';
        document.getElementById('submitBtn').disabled = true;
        return;
    }

    // Load inventory for selected warehouse
    fetch('<?= BASE_URL ?>stock_take/getInventory/' + warehouseId)
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('inventoryTable');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Kho chưa có sản phẩm</td></tr>';
                document.getElementById('submitBtn').disabled = true;
            } else {
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.product_name}</td>
                        <td>${item.unit}</td>
                        <td class="text-end"><strong>${item.quantity}</strong></td>
                        <td>
                            <input type="hidden" name="products[${item.product_id}][product_id]" value="${item.product_id}">
                            <input type="hidden" name="products[${item.product_id}][system_quantity]" value="${item.quantity}">
                            <input type="number" name="products[${item.product_id}][actual_quantity]" 
                                   class="form-control text-end actual-qty" 
                                   data-system="${item.quantity}"
                                   data-product="${item.product_id}"
                                   value="${item.quantity}" 
                                   min="0" required>
                        </td>
                        <td class="text-end variance-cell" id="variance-${item.product_id}">0</td>
                    `;
                    tbody.appendChild(row);
                });
                
                document.getElementById('inventorySection').style.display = 'block';
                document.getElementById('submitBtn').disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải tồn kho');
        });
});

document.getElementById('inventoryTable').addEventListener('input', function(e) {
    if (e.target.classList.contains('actual-qty')) {
        const systemQty = parseFloat(e.target.dataset.system) || 0;
        const actualQty = parseFloat(e.target.value) || 0;
        const variance = actualQty - systemQty;
        const productId = e.target.dataset.product;
        const varianceCell = document.getElementById('variance-' + productId);
        
        varianceCell.textContent = variance > 0 ? '+' + variance : variance;
        
        if (variance > 0) {
            varianceCell.className = 'text-end text-success fw-bold';
        } else if (variance < 0) {
            varianceCell.className = 'text-end text-danger fw-bold';
        } else {
            varianceCell.className = 'text-end';
        }
    }
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
