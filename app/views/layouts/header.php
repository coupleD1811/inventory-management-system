<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hệ thống quản lý kho' ?></title>

    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>

<body>
    <div class="page">
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="<?= BASE_URL ?>">
                        <i class="ti ti-package me-2"></i>
                        <span>QLKHO</span>
                    </a>
                </h1>

                <div class="navbar-nav flex-row d-lg-none">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
                            <span class="avatar avatar-sm">
                                <i class="ti ti-user"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="<?= BASE_URL ?>auth/logout" class="dropdown-item">
                                <i class="ti ti-logout me-2"></i>Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>

                <?php
                require_once '../app/models/Inventory.php';
                require_once '../app/models/Warehouse.php';
                ?>
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <?php
                        $inventoryModel = new Inventory();
                        $warningWarehouseId = Auth::isStorekeeper() ? Auth::getWarehouseId() : null;
                        $warningSummary = $inventoryModel->getWarningSummary($warningWarehouseId);
                        $warningIcon = $warningSummary === 'over'
                            ? '<i class="ti ti-alert-triangle text-danger ms-1"></i>'
                            : ($warningSummary === 'near'
                                ? '<i class="ti ti-alert-triangle text-warning ms-1"></i>'
                                : '');
                        ?>
                        <?php if (Auth::isAdmin()): ?>
                            <?php if (Auth::hasPermission('role.view')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>role">
                                        <span class="nav-link-icon d-lg-inline-block">
                                            <i class="ti ti-shield"></i>
                                        </span>
                                        <span class="nav-link-title">Phân quyền</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (Auth::hasPermission('user.view')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>user">
                                        <span class="nav-link-icon d-lg-inline-block">
                                            <i class="ti ti-users"></i>
                                        </span>
                                        <span class="nav-link-title">Người dùng</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>home">
                                <span class="nav-link-icon d-lg-inline-block">
                                    <i class="ti ti-home"></i>
                                </span>
                                <span class="nav-link-title">Trang chủ</span>
                            </a>
                        </li>

                        <?php if (Auth::hasPermission('product.view')): ?>
                            <?php if (Auth::isStorekeeper()): ?>
                                <?php
                                $warehouseModel = new Warehouse();
                                $userWarehouse = $warehouseModel->find(Auth::getWarehouseId());
                                ?>
                                <?php if ($userWarehouse): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= BASE_URL ?>product?warehouse_code=<?= htmlspecialchars($userWarehouse['code']) ?>">
                                            <span class="nav-link-icon d-lg-inline-block">
                                                <i class="ti ti-building-warehouse"></i>
                                            </span>
                                            <span class="nav-link-title"><?= htmlspecialchars($userWarehouse['name']) ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>product?warehouse_code=KHO-NNL">
                                        <span class="nav-link-icon d-lg-inline-block">
                                            <i class="ti ti-flame"></i>
                                        </span>
                                        <span class="nav-link-title">Kho nhiên liệu</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>product?warehouse_code=KHO-NL">
                                        <span class="nav-link-icon d-lg-inline-block">
                                            <i class="ti ti-leaf"></i>
                                        </span>
                                        <span class="nav-link-title">Kho nguyên liệu</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>product?warehouse_code=KHO-PT">
                                        <span class="nav-link-icon d-lg-inline-block">
                                            <i class="ti ti-settings"></i>
                                        </span>
                                        <span class="nav-link-title">Kho phụ tùng</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>product?warehouse_code=KHO-TP">
                                        <span class="nav-link-icon d-lg-inline-block">
                                            <i class="ti ti-package"></i>
                                        </span>
                                        <span class="nav-link-title">Kho thành phẩm</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (Auth::hasPermission('supplier.view')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>supplier">
                                    <span class="nav-link-icon d-lg-inline-block">
                                        <i class="ti ti-truck"></i>
                                    </span>
                                    <span class="nav-link-title">Nhà cung cấp</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (Auth::hasPermission('workshop.view')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>workshop">
                                    <span class="nav-link-icon d-lg-inline-block">
                                        <i class="ti ti-building"></i>
                                    </span>
                                    <span class="nav-link-title">Phân xưởng</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (Auth::hasPermission('import.view')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>import">
                                    <span class="nav-link-icon d-lg-inline-block">
                                        <i class="ti ti-arrow-down-circle"></i>
                                    </span>
                                    <span class="nav-link-title">Nhập kho</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (Auth::hasPermission('export.view')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>export">
                                    <span class="nav-link-icon d-lg-inline-block">
                                        <i class="ti ti-arrow-up-circle"></i>
                                    </span>
                                    <span class="nav-link-title">Xuất kho</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (Auth::hasPermission('inventory.view')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>inventory">
                                    <span class="nav-link-icon d-lg-inline-block">
                                        <i class="ti ti-clipboard-list"></i>
                                    </span>
                                    <span class="nav-link-title">Tồn kho</span>
                                </a>
                            </li>
                        <?php endif; ?>


                        <?php if (Auth::isStorekeeper() && Auth::hasPermission('stock_card.view')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= BASE_URL ?>stock_card">
                                    <span class="nav-link-icon d-lg-inline-block">
                                        <i class="ti ti-file-text"></i>
                                    </span>
                                    <span class="nav-link-title">Thẻ kho <?= $warningIcon ?></span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (!Auth::isStorekeeper() && (Auth::hasPermission('report.view') || Auth::hasPermission('inventory.report') || Auth::hasPermission('report.profit_loss') || Auth::hasPermission('stock_card.view'))): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-reports" data-bs-toggle="dropdown" role="button">
                                    <span class="nav-link-icon d-lg-inline-block">
                                        <i class="ti ti-chart-bar"></i>
                                    </span>
                                    <span class="nav-link-title">Báo cáo <?= $warningIcon ?></span>
                                </a>
                                <div class="dropdown-menu">
                                    <?php if (Auth::hasPermission('inventory.report')): ?>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>report/inventory">
                                            <i class="ti ti-report me-2"></i>Báo cáo tồn kho
                                        </a>
                                    <?php endif; ?>
                                    <?php if (Auth::hasPermission('report.view')): ?>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>report/import">
                                            <i class="ti ti-report-analytics me-2"></i>Báo cáo nhập kho
                                        </a>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>report/export">
                                            <i class="ti ti-report-money me-2"></i>Báo cáo xuất kho
                                        </a>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>report/movement">
                                            <i class="ti ti-table-options me-2"></i>Báo cáo nhập-xuất-tồn
                                        </a>
                                    <?php endif; ?>
                                    <?php if (Auth::hasPermission('report.profit_loss')): ?>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>report/profitLoss">
                                            <i class="ti ti-chart-line me-2"></i>Báo cáo Lời/Lỗ
                                        </a>
                                    <?php endif; ?>
                                    <?php if (Auth::hasPermission('stock_card.view')): ?>
                                        <a class="dropdown-item" href="<?= BASE_URL ?>stock_card">
                                            <i class="ti ti-file-text me-2"></i>Thẻ kho <?= $warningIcon ?>
                                        </a>
                                    <?php endif; ?>
                                    <!-- BÁO CÁO GIAO DỊCH ĐÃ BỊ TẮT
                                    <a class="dropdown-item" href="<?= BASE_URL ?>report/transaction">
                                        <i class="ti ti-list me-2"></i>Báo cáo giao dịch
                                    </a>
                                    -->
                                </div>
                            </li>
                        <?php endif; ?>

                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Page wrapper -->
        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title"><?= $title ?? 'Trang chủ' ?></h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <span class="d-none d-sm-inline">
                                    <a href="#" class="btn">
                                        <i class="ti ti-user me-2"></i>
                                        <?= $_SESSION['full_name'] ?? $_SESSION['username'] ?>
                                    </a>
                                </span>
                                <a href="<?= BASE_URL ?>auth/logout" class="btn btn-danger">
                                    <i class="ti ti-logout me-2"></i>
                                    Đăng xuất
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>
                                    <i class="ti ti-check alert-icon"></i>
                                </div>
                                <div>
                                    <?= $_SESSION['success'] ?>
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div>
                                    <i class="ti ti-alert-circle alert-icon"></i>
                                </div>
                                <div>
                                    <?= $_SESSION['error'] ?>
                                </div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
