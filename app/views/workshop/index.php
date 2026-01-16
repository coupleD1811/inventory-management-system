<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách phân xưởng</h3>
                <div class="card-actions">
                    <?php if (Auth::hasPermission('workshop.create')): ?>
                        <a href="<?= BASE_URL ?>workshop/create" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Thêm phân xưởng
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search ?? '') ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i> Tìm kiếm
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã phân xưởng</th>
                                <th>Tên phân xưởng</th>
                                <th>Vị trí</th>
                                <th>Người quản lý</th>
                                <th>Điện thoại</th>
                                <th>Trạng thái</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($workshops)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($workshops as $index => $workshop): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($workshop['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($workshop['name']) ?></td>
                                        <td><?= htmlspecialchars($workshop['location'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($workshop['manager_name'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($workshop['phone'] ?? '-') ?></td>
                                        <td>
                                            <?php if ($workshop['status'] === 'active'): ?>
                                                <span class="badge bg-success">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Không hoạt động</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if (Auth::hasPermission('workshop.edit')): ?>
                                                    <a href="<?= BASE_URL ?>workshop/edit/<?= $workshop['id'] ?>" class="btn btn-sm btn-primary" title="Sửa">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::hasPermission('workshop.delete')): ?>
                                                    <a href="<?= BASE_URL ?>workshop/delete/<?= $workshop['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa phân xưởng này?')">
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
