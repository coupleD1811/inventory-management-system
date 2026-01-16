<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm nhà cung cấp mới</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>supplier" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>supplier/create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Mã nhà cung cấp</label>
                                <input type="text" name="code" class="form-control" 
                                       placeholder="VD: NCC001" 
                                       value="<?= htmlspecialchars($old['code'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên nhà cung cấp</label>
                                <input type="text" name="name" class="form-control" 
                                       placeholder="VD: Công ty TNHH ABC" 
                                       value="<?= htmlspecialchars($old['name'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Người liên hệ</label>
                                <input type="text" name="contact_person" class="form-control" 
                                       placeholder="VD: Nguyễn Văn A" 
                                       value="<?= htmlspecialchars($old['contact_person'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" name="phone" class="form-control" 
                                       placeholder="VD: 0901234567" 
                                       value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       placeholder="VD: contact@example.com" 
                                       value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mã số thuế</label>
                                <input type="text" name="tax_code" class="form-control" 
                                       placeholder="VD: 0123456789" 
                                       value="<?= htmlspecialchars($old['tax_code'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="address" class="form-control" rows="2" 
                                  placeholder="Địa chỉ nhà cung cấp..."><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Ghi chú thêm..."><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= ($old['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="inactive" <?= ($old['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu
                        </button>
                        <a href="<?= BASE_URL ?>supplier" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
