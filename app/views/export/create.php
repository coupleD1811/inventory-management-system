<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tạo phiếu xuất kho</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>export" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>export/create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Kho xuất</label>
                                <select name="warehouse_id" class="form-select" required>
                                    <option value="">-- Chọn kho --</option>
                                    <?php foreach ($warehouses as $wh): ?>
                                        <option value="<?= $wh['id'] ?>" <?= ($old['warehouse_id'] ?? '') == $wh['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($wh['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Khách hàng/Đơn vị nhận</label>
                                <input type="text" name="customer_name" class="form-control" 
                                       placeholder="VD: Công ty ABC" 
                                       value="<?= htmlspecialchars($old['customer_name'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Loại xuất</label>
                                <select name="export_type" class="form-select">
                                    <option value="sale" <?= ($old['export_type'] ?? 'sale') === 'sale' ? 'selected' : '' ?>>Bán hàng</option>
                                    <option value="internal" <?= ($old['export_type'] ?? '') === 'internal' ? 'selected' : '' ?>>Nội bộ</option>
                                    <option value="damaged" <?= ($old['export_type'] ?? '') === 'damaged' ? 'selected' : '' ?>>Hư hỏng</option>
                                    <option value="other" <?= ($old['export_type'] ?? '') === 'other' ? 'selected' : '' ?>>Khác</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Ngày xuất</label>
                                <input type="date" name="export_date" class="form-control" 
                                       value="<?= htmlspecialchars($old['export_date'] ?? date('Y-m-d')) ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes" class="form-control" rows="1"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Lưu ý:</strong> Mã phiếu sẽ được tự động tạo theo định dạng PX-YYYYMMDD-XXX. Sau khi tạo phiếu, bạn cần thêm chi tiết sản phẩm và duyệt phiếu để cập nhật tồn kho.
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu phiếu xuất
                        </button>
                        <a href="<?= BASE_URL ?>export" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
