<?php require_once '../app/views/layouts/header.php'; ?>

<style>
    select option.warning-near { color: #f1b000; }
    select option.warning-over { color: #d63939; }
</style>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thẻ kho - Lịch sử giao dịch</h3>
            </div>
            <div class="card-body">
                <?php if ($isStorekeeper): ?>
                    <div class="alert alert-info mb-4">
                        <i class="ti ti-info-circle me-2"></i>
                        Hiển thị thẻ kho cho: <strong><?= htmlspecialchars($warehouse['name'] ?? '') ?></strong>
                    </div>
                <?php else: ?>
                <form method="GET" class="mb-4" id="stockcardFilterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label required">Kho</label>
                                <select name="warehouse_id" id="warehouseSelect" class="form-select" required>
                                    <option value="">-- Chọn kho --</option>
                                    <?php foreach ($warehouses as $wh): ?>
                                        <?php
                                        $warning = $warehouseWarnings[$wh['id']] ?? null;
                                        $suffix = $warning === 'over' ? ' (!!)' : ($warning === 'near' ? ' (!)' : '');
                                        $warningClass = $warning === 'over' ? 'warning-over' : ($warning === 'near' ? 'warning-near' : '');
                                        ?>
                                        <option value="<?= $wh['id'] ?>" class="<?= $warningClass ?>" <?= $warehouseId == $wh['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($wh['name']) ?><?= $suffix ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label required">Sản phẩm</label>
                                <select name="product_id" id="productSelect" class="form-select" required>
                                    <option value="">-- Chọn sản phẩm --</option>
                                    <?php foreach ($products as $prod): ?>
                                        <?php
                                        $warning = $productWarnings[$prod['id']] ?? null;
                                        $suffix = $warning === 'over' ? ' (!!)' : ($warning === 'near' ? ' (!)' : '');
                                        $warningClass = $warning === 'over' ? 'warning-over' : ($warning === 'near' ? 'warning-near' : '');
                                        ?>
                                        <option value="<?= $prod['id'] ?>" class="<?= $warningClass ?>" <?= $productId == $prod['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($prod['code']) ?> - <?= htmlspecialchars($prod['name']) ?><?= $suffix ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Từ ngày</label>
                                <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Đến ngày</label>
                                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-search me-2"></i>Xem thẻ kho
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <script>
                    (function () {
                        const form = document.getElementById('stockcardFilterForm');
                        const warehouseSelect = document.getElementById('warehouseSelect');
                        const productSelect = document.getElementById('productSelect');

                        warehouseSelect.addEventListener('change', function () {
                            productSelect.value = '';
                            form.submit();
                        });
                    })();
                </script>
                <?php endif; ?>

                <?php if ($isStorekeeper): ?>
                    <?php foreach ($stockCards as $card): ?>
                        <?php
                        $cardProduct = $card['product'];
                        $cardTransactions = $card['transactions'];
                        $warning = $productWarnings[$cardProduct['id']] ?? null;
                        ?>
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="mb-0">
                                        <?= htmlspecialchars($cardProduct['code']) ?> - <?= htmlspecialchars($cardProduct['name']) ?>
                                        <?php if ($warning === 'near'): ?>
                                            <i class="ti ti-alert-triangle text-warning ms-1" title="Gần vượt mức tồn kho"></i>
                                        <?php elseif ($warning === 'over'): ?>
                                            <i class="ti ti-alert-triangle text-danger ms-1" title="Vượt mức tồn kho"></i>
                                        <?php endif; ?>
                                    </h4>
                                    <button onclick="window.print()" class="btn btn-success">
                                        <i class="ti ti-file-export me-2"></i>Xuất báo cáo (In/PDF)
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table table-bordered">
                                        <thead>
                                            <tr class="bg-primary text-white">
                                                <th>STT</th>
                                                <th>Ngày</th>
                                                <th>Loại</th>
                                                <th>Số chứng từ</th>
                                                <th class="text-end">Số lượng nhập</th>
                                                <th class="text-end">Số lượng xuất</th>
                                                <th class="text-end">Tồn kho</th>
                                                <th>Người thực hiện</th>
                                                <th>Ghi chú</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($cardTransactions)): ?>
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">
                                                        <i class="ti ti-file-off fs-1 mb-2"></i>
                                                        <p>Không có giao dịch nào</p>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($cardTransactions as $index => $trans): ?>
                                                    <tr>
                                                        <td><?= $index + 1 ?></td>
                                                        <td><?= date('d/m/Y H:i', strtotime($trans['transaction_date'])) ?></td>
                                                        <td>
                                                            <?php if ($trans['transaction_type'] === 'import'): ?>
                                                                <span class="badge bg-success">Nhập</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger">Xuất</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?= BASE_URL ?><?= $trans['reference_type'] ?>/detail/<?= $trans['reference_id'] ?>">
                                                                <?= htmlspecialchars($trans['reference_code']) ?>
                                                            </a>
                                                        </td>
                                                        <td class="text-end">
                                                            <?php if ($trans['transaction_type'] === 'import'): ?>
                                                                <strong class="text-success">+<?= number_format($trans['quantity'], 2) ?></strong>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-end">
                                                            <?php if ($trans['transaction_type'] === 'export'): ?>
                                                                <strong class="text-danger">-<?= number_format($trans['quantity'], 2) ?></strong>
                                                            <?php else: ?>
                                                                -
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-end">
                                                            <strong><?= number_format($trans['balance_after'], 2) ?></strong>
                                                        </td>
                                                        <td><?= htmlspecialchars($trans['created_by_name'] ?? '-') ?></td>
                                                        <td><?= htmlspecialchars($trans['notes'] ?? '-') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($product && $warehouse): ?>
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="mb-3">Thông tin sản phẩm</h4>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td width="150"><strong>Mã sản phẩm:</strong></td>
                                            <td><?= htmlspecialchars($product['code']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tên sản phẩm:</strong></td>
                                            <td><?= htmlspecialchars($product['name']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Đơn vị tính:</strong></td>
                                            <td><?= htmlspecialchars($product['unit']) ?></td>
                                        </tr>
                                        <?php if (!empty($product['description'])): ?>
                                        <tr>
                                            <td><strong>Mẫu mã/Mô tả:</strong></td>
                                            <td><?= htmlspecialchars($product['description']) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td><strong>Kho:</strong></td>
                                            <td><?= htmlspecialchars($warehouse['name']) ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="mb-3">Mức dự trữ & Giá</h4>
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td width="180"><strong>Đơn giá gần nhất:</strong></td>
                                            <td>
                                                <?php if (!empty($transactions)): ?>
                                                    <?php 
                                                    // Get latest import transaction to show price
                                                    $latestImport = null;
                                                    foreach (array_reverse($transactions) as $t) {
                                                        if ($t['transaction_type'] === 'import') {
                                                            $latestImport = $t;
                                                            break;
                                                        }
                                                    }
                                                    ?>
                                                    <?php if ($latestImport && isset($latestImport['unit_price'])): ?>
                                                        <strong class="text-primary"><?= number_format($latestImport['unit_price']) ?> đ</strong>
                                                    <?php else: ?>
                                                        <span class="text-muted">Chưa có</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Chưa có</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tồn kho tối thiểu:</strong></td>
                                            <td><span class="badge bg-warning"><?= number_format($product['min_stock']) ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tồn kho tối đa:</strong></td>
                                            <td><span class="badge bg-info"><?= number_format($product['max_stock']) ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tồn hiện tại:</strong></td>
                                            <td>
                                                <?php if (!empty($transactions)): ?>
                                                    <?php 
                                                    $currentStock = end($transactions)['balance_after'];
                                                    $badgeClass = 'bg-success';
                                                    $warning = '';
                                                    
                                                    if ($currentStock < $product['min_stock']) {
                                                        $badgeClass = 'bg-danger';
                                                        $warning = ' ⚠️ Thấp hơn mức tối thiểu!';
                                                    } elseif ($currentStock > $product['max_stock']) {
                                                        $badgeClass = 'bg-warning';
                                                        $warning = ' ⚠️ Vượt mức tối đa!';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $badgeClass ?> fs-4">
                                                        <?= number_format($currentStock, 2) ?>
                                                    </span>
                                                    <?php if ($warning): ?>
                                                        <span class="text-<?= $badgeClass === 'bg-danger' ? 'danger' : 'warning' ?> ms-2">
                                                            <strong><?= $warning ?></strong>
                                                        </span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">0</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Lịch sử giao dịch</h4>
                        <button onclick="window.print()" class="btn btn-success">
                            <i class="ti ti-file-export me-2"></i>Xuất báo cáo (In/PDF)
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-bordered">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th>STT</th>
                                    <th>Ngày</th>
                                    <th>Loại</th>
                                    <th>Số chứng từ</th>
                                    <th class="text-end">Số lượng nhập</th>
                                    <th class="text-end">Số lượng xuất</th>
                                    <th class="text-end">Tồn kho</th>
                                    <th>Người thực hiện</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($transactions)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            <i class="ti ti-file-off fs-1 mb-2"></i>
                                            <p>Không có giao dịch nào</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $index => $trans): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($trans['transaction_date'])) ?></td>
                                            <td>
                                                <?php if ($trans['transaction_type'] === 'import'): ?>
                                                    <span class="badge bg-success">Nhập</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Xuất</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= BASE_URL ?><?= $trans['reference_type'] ?>/detail/<?= $trans['reference_id'] ?>">
                                                    <?= htmlspecialchars($trans['reference_code']) ?>
                                                </a>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($trans['transaction_type'] === 'import'): ?>
                                                    <strong class="text-success">+<?= number_format($trans['quantity'], 2) ?></strong>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($trans['transaction_type'] === 'export'): ?>
                                                    <strong class="text-danger">-<?= number_format($trans['quantity'], 2) ?></strong>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <strong><?= number_format($trans['balance_after'], 2) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($trans['created_by_name'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($trans['notes'] ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        Vui lòng chọn kho và sản phẩm để xem thẻ kho
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
