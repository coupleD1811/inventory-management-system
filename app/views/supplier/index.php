<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách nhà cung cấp</h3>
                <div class="card-actions">
                    <?php if (Auth::hasPermission('supplier.create')): ?>
                        <a href="<?= BASE_URL ?>supplier/create" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Thêm nhà cung cấp
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã, tên hoặc người liên hệ..." value="<?= htmlspecialchars($search ?? '') ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i> Tìm kiếm
                        </button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= BASE_URL ?>supplier" class="btn btn-secondary">
                                <i class="ti ti-x"></i> Xóa
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã NCC</th>
                                <th>Tên nhà cung cấp</th>
                                <th>Người liên hệ</th>
                                <th>Điện thoại</th>
                                <th>Email</th>
                                <th>Trạng thái</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($suppliers)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="ti ti-truck-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($suppliers as $index => $supplier): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($supplier['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($supplier['name']) ?></td>
                                        <td><?= htmlspecialchars($supplier['contact_person'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($supplier['phone'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($supplier['email'] ?? '-') ?></td>
                                        <td>
                                            <?php if ($supplier['status'] === 'active'): ?>
                                                <span class="badge bg-success">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Không hoạt động</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if (Auth::hasPermission('supplier.edit')): ?>
                                                    <a href="<?= BASE_URL ?>supplier/edit/<?= $supplier['id'] ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Sửa">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::hasPermission('supplier.delete')): ?>
                                                    <a href="<?= BASE_URL ?>supplier/delete/<?= $supplier['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?')">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
