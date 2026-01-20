<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tạo phiếu nhập kho</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>import" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>import/create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Kho nhập</label>
                                <select name="warehouse_id" id="warehouseSelect" class="form-select" required>
                                    <option value="">-- Chọn kho --</option>
                                    <?php foreach ($warehouses as $wh): ?>
                                        <option value="<?= $wh['id'] ?>" data-code="<?= htmlspecialchars($wh['code']) ?>" <?= ($old['warehouse_id'] ?? '') == $wh['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($wh['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" id="supplierLabel">Nhà cung cấp</label>
                                <select name="supplier_id" id="supplierSelect" class="form-select">
                                    <option value="" id="supplierPlaceholder">-- Chọn nhà cung cấp --</option>
                                    <?php foreach ($suppliers as $sup): ?>
                                        <option value="<?= $sup['id'] ?>" <?= ($old['supplier_id'] ?? '') == $sup['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($sup['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày nhập</label>
                                <input type="date" name="import_date" class="form-control" 
                                       value="<?= htmlspecialchars($old['import_date'] ?? date('Y-m-d')) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Lưu ý:</strong> Mã phiếu sẽ được tự động tạo theo định dạng PN-YYYYMMDD-XXX. Sau khi tạo phiếu, bạn cần thêm chi tiết sản phẩm và duyệt phiếu để cập nhật tồn kho.
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu phiếu nhập
                        </button>
                        <a href="<?= BASE_URL ?>import" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const warehouseSelect = document.getElementById('warehouseSelect');
        const supplierLabel = document.getElementById('supplierLabel');
        const supplierPlaceholder = document.getElementById('supplierPlaceholder');

        function syncSupplierLabel() {
            const selected = warehouseSelect.options[warehouseSelect.selectedIndex];
            const isFinished = selected && selected.dataset.code === 'KHO-TP';
            supplierLabel.textContent = isFinished ? 'Phân xưởng' : 'Nhà cung cấp';
            supplierPlaceholder.textContent = isFinished ? '-- Chọn phân xưởng --' : '-- Chọn nhà cung cấp --';
        }

        warehouseSelect.addEventListener('change', syncSupplierLabel);
        syncSupplierLabel();
    })();
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
