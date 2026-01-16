<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm đại lý mới</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>agency" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>agency/create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Mã đại lý</label>
                                <input type="text" name="code" class="form-control" 
                                       placeholder="VD: DL001" 
                                       value="<?= htmlspecialchars($old['code'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên đại lý</label>
                                <input type="text" name="name" class="form-control" 
                                       placeholder="VD: Đại lý Miền Bắc" 
                                       value="<?= htmlspecialchars($old['name'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Số hợp đồng</label>
                                <input type="text" name="contract_number" class="form-control" 
                                       placeholder="VD: HD001/2024" 
                                       value="<?= htmlspecialchars($old['contract_number'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày ký hợp đồng</label>
                                <input type="date" name="contract_date" class="form-control" 
                                       value="<?= htmlspecialchars($old['contract_date'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Người đại diện</label>
                                <input type="text" name="representative" class="form-control" 
                                       placeholder="VD: Nguyễn Văn A" 
                                       value="<?= htmlspecialchars($old['representative'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">CMND/CCCD</label>
                                <input type="text" name="id_card" class="form-control" 
                                       placeholder="VD: 001234567890" 
                                       value="<?= htmlspecialchars($old['id_card'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" name="phone" class="form-control" 
                                       placeholder="VD: 0901234567" 
                                       value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       placeholder="VD: daily@example.com" 
                                       value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Mã số thuế</label>
                                <input type="text" name="tax_code" class="form-control" 
                                       placeholder="VD: 0123456789" 
                                       value="<?= htmlspecialchars($old['tax_code'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chiết khấu mặc định (%)</label>
                        <input type="number" name="discount_percent" class="form-control" 
                               step="0.01" min="0" max="100" 
                               value="<?= htmlspecialchars($old['discount_percent'] ?? '0') ?>">
                        <small class="form-hint">Chiết khấu áp dụng khi thanh toán ngay</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu đại lý
                        </button>
                        <a href="<?= BASE_URL ?>agency" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
