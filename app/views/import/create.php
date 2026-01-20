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
                            <div class="mb-3" id="supplierField">
                                <label class="form-label">Nhà cung cấp</label>
                                <select name="supplier_id" id="supplierSelect" class="form-select">
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    <?php foreach ($suppliers as $sup): ?>
                                        <option value="<?= $sup['id'] ?>" <?= ($old['supplier_id'] ?? '') == $sup['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($sup['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3" id="workshopField" style="display: none;">
                                <label class="form-label">Phân xưởng</label>
                                <select name="workshop_id" id="workshopSelect" class="form-select">
                                    <option value="">-- Chọn phân xưởng --</option>
                                    <?php foreach ($workshops as $workshop): ?>
                                        <option value="<?= $workshop['id'] ?>" <?= ($old['workshop_id'] ?? '') == $workshop['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($workshop['name']) ?>
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
        const supplierField = document.getElementById('supplierField');
        const workshopField = document.getElementById('workshopField');
        const supplierSelect = document.getElementById('supplierSelect');
        const workshopSelect = document.getElementById('workshopSelect');

        function syncSourceField() {
            const selected = warehouseSelect.options[warehouseSelect.selectedIndex];
            const isFinished = selected && selected.dataset.code === 'KHO-TP';
            supplierField.style.display = isFinished ? 'none' : '';
            workshopField.style.display = isFinished ? '' : 'none';
            if (isFinished) {
                supplierSelect.value = '';
            } else {
                workshopSelect.value = '';
            }
        }

        warehouseSelect.addEventListener('change', syncSourceField);
        syncSourceField();
    })();
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
