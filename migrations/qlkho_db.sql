-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th1 15, 2026 lúc 04:14 AM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlkho_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `agencies`
--

CREATE TABLE `agencies` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `contract_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_date` date DEFAULT NULL,
  `representative` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_card` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CMND/CCCD',
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_percent` decimal(5,2) DEFAULT '0.00' COMMENT 'Chiết khấu mặc định (%)',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `agencies`
--

INSERT INTO `agencies` (`id`, `code`, `name`, `address`, `contract_number`, `contract_date`, `representative`, `id_card`, `phone`, `email`, `tax_code`, `discount_percent`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'DL001', 'Đại lý Miền Bắc', 'Hà Nội', 'HD001/2024', '2024-01-15', 'Nguyễn Văn A', '001234567890', '0901234567', 'dlmienbac@example.com', NULL, 5.00, 'active', NULL, '2026-01-12 04:30:07', '2026-01-12 04:30:07'),
(2, 'DL002', 'Đại lý Miền Nam', 'TP.HCM', 'HD002/2024', '2024-02-20', 'Trần Thị B', '001234567891', '0902234567', 'dlmiennam@example.com', NULL, 3.00, 'active', NULL, '2026-01-12 04:30:07', '2026-01-12 04:30:07'),
(3, 'DL003', 'Đại lý Miền Trung', 'Đà Nẵng', 'HD003/2024', '2024-03-10', 'Lê Văn C', '001234567892', '0903234567', 'dlmientrung@example.com', NULL, 4.00, 'active', NULL, '2026-01-12 04:30:07', '2026-01-12 04:30:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `code`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'DM001', 'Điện tử', 'Sản phẩm điện tử', 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(2, 'DM002', 'Văn phòng phẩm', 'Đồ dùng văn phòng', 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(3, 'DM003', 'Thực phẩm', 'Thực phẩm khô', 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(4, 'DM004', 'Gia dụng', 'Đồ gia dụng', 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(5, 'DM-NL', 'Nguyên liệu', 'Nguyên liệu đầu vào sản xuất', 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(6, 'DM-NNL', 'Nhiên liệu', 'Nhiên liệu phục vụ sản xuất', 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(7, 'DM-PT', 'Phụ tùng', 'Phụ tùng máy móc thiết bị', 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(8, 'DM-TP', 'Thành phẩm', 'Sản phẩm bánh kẹo hoàn thiện', 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exports`
--

CREATE TABLE `exports` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int NOT NULL,
  `export_date` date NOT NULL,
  `export_type` enum('sale','internal','damaged','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'sale',
  `customer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agency_id` int DEFAULT NULL,
  `customer_phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT '0.00',
  `payment_status` enum('unpaid','paid','partial') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'unpaid',
  `discount_percent` decimal(5,2) DEFAULT '0.00',
  `discount_amount` decimal(15,2) DEFAULT '0.00',
  `final_amount` decimal(15,2) DEFAULT '0.00',
  `status` enum('draft','pending','approved','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `created_by` int DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `export_details`
--

CREATE TABLE `export_details` (
  `id` int NOT NULL,
  `export_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `cost_price` decimal(15,2) DEFAULT '0.00' COMMENT 'Giá vốn',
  `total_price` decimal(15,2) NOT NULL,
  `profit` decimal(15,2) DEFAULT '0.00' COMMENT 'Lợi nhuận',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `imports`
--

CREATE TABLE `imports` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int NOT NULL,
  `supplier_id` int DEFAULT NULL,
  `import_date` date NOT NULL,
  `total_amount` decimal(15,2) DEFAULT '0.00',
  `status` enum('draft','pending','approved','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `created_by` int DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `imports`
--

INSERT INTO `imports` (`id`, `code`, `warehouse_id`, `supplier_id`, `import_date`, `total_amount`, `status`, `created_by`, `approved_by`, `approved_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'PN-20260115-001', 7, 2, '2026-01-15', 2400000.00, 'approved', 1, 1, '2026-01-15 10:59:57', '', '2026-01-15 03:55:48', '2026-01-15 03:59:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `import_details`
--

CREATE TABLE `import_details` (
  `id` int NOT NULL,
  `import_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `import_details`
--

INSERT INTO `import_details` (`id`, `import_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 16, 1, 200000.00, 200000.00, NULL, '2026-01-15 03:56:15', '2026-01-15 03:56:15'),
(2, 1, 17, 1, 200000.00, 200000.00, NULL, '2026-01-15 03:56:15', '2026-01-15 03:56:15'),
(3, 1, 16, 20, 100000.00, 2000000.00, NULL, '2026-01-15 03:59:20', '2026-01-15 03:59:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory`
--

CREATE TABLE `inventory` (
  `id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '0',
  `avg_import_price` decimal(15,2) DEFAULT '0.00' COMMENT 'Giá nhập trung bình',
  `last_import_date` date DEFAULT NULL,
  `last_export_date` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `inventory`
--

INSERT INTO `inventory` (`id`, `warehouse_id`, `product_id`, `quantity`, `avg_import_price`, `last_import_date`, `last_export_date`, `updated_at`) VALUES
(1, 4, 7, 500, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(2, 4, 8, 800, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(3, 4, 9, 50, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(4, 4, 10, 2000, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(5, 5, 11, 20, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(6, 5, 12, 500, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(7, 6, 13, 50, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(8, 6, 14, 10, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(9, 6, 15, 100, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(10, 7, 16, 500, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(11, 7, 17, 1000, 0.00, NULL, NULL, '2026-01-12 06:05:36'),
(12, 7, 18, 750, 0.00, NULL, NULL, '2026-01-12 06:05:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `product_id` int NOT NULL,
  `transaction_type` enum('import','export','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` enum('import','export','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int DEFAULT NULL,
  `quantity` int NOT NULL COMMENT 'Số lượng (+/-)',
  `quantity_before` int DEFAULT '0',
  `quantity_after` int DEFAULT '0',
  `unit_price` decimal(15,2) DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `module`, `description`, `created_at`, `updated_at`) VALUES
(5, 'product.view', 'Xem sản phẩm', 'product', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(6, 'product.create', 'Thêm sản phẩm', 'product', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(7, 'product.edit', 'Sửa sản phẩm', 'product', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(8, 'product.delete', 'Xóa sản phẩm', 'product', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(9, 'supplier.view', 'Xem nhà cung cấp', 'supplier', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(10, 'supplier.create', 'Thêm nhà cung cấp', 'supplier', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(11, 'supplier.edit', 'Sửa nhà cung cấp', 'supplier', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(12, 'supplier.delete', 'Xóa nhà cung cấp', 'supplier', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(13, 'warehouse.view', 'Xem kho', 'warehouse', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(14, 'warehouse.create', 'Thêm kho', 'warehouse', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(15, 'warehouse.edit', 'Sửa kho', 'warehouse', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(16, 'warehouse.delete', 'Xóa kho', 'warehouse', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(17, 'import.view', 'Xem phiếu nhập', 'import', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(18, 'import.create', 'Tạo phiếu nhập', 'import', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(19, 'import.edit', 'Sửa phiếu nhập', 'import', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(20, 'import.delete', 'Xóa phiếu nhập', 'import', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(21, 'import.approve', 'Duyệt phiếu nhập', 'import', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(22, 'export.view', 'Xem phiếu xuất', 'export', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(23, 'export.create', 'Tạo phiếu xuất', 'export', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(24, 'export.edit', 'Sửa phiếu xuất', 'export', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(25, 'export.delete', 'Xóa phiếu xuất', 'export', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(26, 'export.approve', 'Duyệt phiếu xuất', 'export', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(27, 'inventory.view', 'Xem tồn kho', 'inventory', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(28, 'inventory.report', 'Báo cáo tồn kho', 'inventory', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(29, 'report.view', 'Xem báo cáo', 'report', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(30, 'report.export', 'Xuất báo cáo', 'report', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(31, 'user.view', 'Xem người dùng', 'user', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(32, 'user.create', 'Thêm người dùng', 'user', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(33, 'user.edit', 'Sửa người dùng', 'user', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(34, 'user.delete', 'Xóa người dùng', 'user', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(35, 'role.view', 'Xem vai trò', 'role', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(36, 'role.create', 'Thêm vai trò', 'role', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(37, 'role.edit', 'Sửa vai trò', 'role', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(38, 'role.delete', 'Xóa vai trò', 'role', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(39, 'agency.view', 'Xem đại lý', 'agency', NULL, '2026-01-12 04:30:07', '2026-01-12 04:30:07'),
(40, 'agency.create', 'Thêm đại lý', 'agency', NULL, '2026-01-12 04:30:07', '2026-01-12 04:30:07'),
(41, 'agency.edit', 'Sửa đại lý', 'agency', NULL, '2026-01-12 04:30:07', '2026-01-12 04:30:07'),
(42, 'agency.delete', 'Xóa đại lý', 'agency', NULL, '2026-01-12 04:30:07', '2026-01-12 04:30:07'),
(48, 'workshop.view', 'Xem phân xưởng', 'workshop', NULL, '2026-01-12 07:46:20', '2026-01-12 07:46:20'),
(49, 'workshop.create', 'Thêm phân xưởng', 'workshop', NULL, '2026-01-12 07:46:20', '2026-01-12 07:46:20'),
(50, 'workshop.edit', 'Sửa phân xưởng', 'workshop', NULL, '2026-01-12 07:46:20', '2026-01-12 07:46:20'),
(51, 'workshop.delete', 'Xóa phân xưởng', 'workshop', NULL, '2026-01-12 07:46:20', '2026-01-12 07:46:20'),
(57, 'report.profit_loss', 'Xem báo cáo lời/lỗ', 'report', 'Xem báo cáo lời lỗ theo sản phẩm', '2026-01-15 03:37:26', '2026-01-15 03:37:26'),
(63, 'stock_card.view', 'Xem thẻ kho', 'stock_card', 'Xem thẻ kho và lịch sử giao dịch', '2026-01-15 04:12:27', '2026-01-15 04:12:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int NOT NULL,
  `unit` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'cái' COMMENT 'Đơn vị tính',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_stock` int DEFAULT '0' COMMENT 'Tồn kho tối thiểu',
  `max_stock` int DEFAULT '0' COMMENT 'Tồn kho tối đa',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `code`, `name`, `warehouse_id`, `unit`, `description`, `image`, `min_stock`, `max_stock`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SP001', 'Laptop Dell Inspiron 15', 7, 'cái', 'Laptop Dell Inspiron 15 3000 Series', NULL, 10, 100, 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(2, 'SP002', 'Chuột Logitech M185', 7, 'cái', 'Chuột không dây Logitech M185', NULL, 20, 200, 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(3, 'SP003', 'Bàn phím Logitech K120', 7, 'cái', 'Bàn phím có dây Logitech K120', NULL, 15, 150, 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(4, 'SP004', 'Bút bi Thiên Long TL-027', 7, 'cây', 'Bút bi Thiên Long TL-027', NULL, 100, 1000, 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(5, 'SP005', 'Giấy A4 Double A', 7, 'ream', 'Giấy A4 Double A 70gsm', NULL, 50, 500, 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(6, 'SP006', 'Mì gói Hảo Hảo', 7, 'thùng', 'Mì gói Hảo Hảo 30 gói/thùng', NULL, 20, 200, 'active', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(7, 'NL-001', 'Đường trắng', 4, 'kg', 'Đường tinh luyện trắng', NULL, 100, 1000, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(8, 'NL-002', 'Bột mì', 4, 'kg', 'Bột mì đa dụng', NULL, 200, 2000, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(9, 'NL-003', 'Hương liệu vani', 4, 'lít', 'Hương liệu tổng hợp', NULL, 10, 100, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(10, 'NL-004', 'Bao bì carton', 4, 'cái', 'Hộp carton đựng sản phẩm', NULL, 500, 5000, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(11, 'NNL-001', 'Than đá', 5, 'tấn', 'Than đá công nghiệp', NULL, 5, 50, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(12, 'NNL-002', 'Dầu diesel', 5, 'lít', 'Dầu diesel cho máy phát điện', NULL, 100, 1000, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(13, 'PT-001', 'Băng tải', 6, 'mét', 'Băng tải cao su', NULL, 10, 100, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(14, 'PT-002', 'Motor điện 3HP', 6, 'cái', 'Motor điện công suất 3HP', NULL, 2, 20, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(15, 'PT-003', 'Vòng bi SKF', 6, 'cái', 'Vòng bi chất lượng cao', NULL, 20, 200, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(16, 'TP-001', 'Bánh quy bơ', 7, 'hộp', 'Bánh quy bơ hộp 200g', NULL, 100, 1000, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(17, 'TP-002', 'Kẹo sữa', 7, 'hộp', 'Kẹo sữa hộp 500g', NULL, 200, 2000, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(18, 'TP-003', 'Bánh chocolate', 7, 'hộp', 'Bánh chocolate hộp 300g', NULL, 150, 1500, 'active', '2026-01-12 06:05:36', '2026-01-12 06:05:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` int NOT NULL,
  `order_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT '0.00',
  `status` enum('draft','sent','confirmed','received','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `created_by` int DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `purchase_order_details`
--

CREATE TABLE `purchase_order_details` (
  `id` int NOT NULL,
  `purchase_order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `received_quantity` int DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Quản trị viên', 'Toàn quyền quản trị hệ thống', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(2, 'manager', 'Quản lý kho', 'Quản lý kho hàng và nhân viên', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(3, 'warehouse_staff', 'Thủ kho', 'Nhập xuất kho', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(4, 'accountant', 'Kế toán', 'Xem báo cáo và thống kê', '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(5, 'viewer', 'BGD', 'Chỉ xem thông tin', '2026-01-12 03:04:33', '2026-01-12 03:04:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int NOT NULL,
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `created_at`) VALUES
(43, 2, 5, '2026-01-12 03:04:33'),
(44, 2, 6, '2026-01-12 03:04:33'),
(45, 2, 7, '2026-01-12 03:04:33'),
(46, 2, 8, '2026-01-12 03:04:33'),
(47, 2, 9, '2026-01-12 03:04:33'),
(48, 2, 10, '2026-01-12 03:04:33'),
(49, 2, 11, '2026-01-12 03:04:33'),
(50, 2, 12, '2026-01-12 03:04:33'),
(51, 2, 13, '2026-01-12 03:04:33'),
(52, 2, 14, '2026-01-12 03:04:33'),
(53, 2, 15, '2026-01-12 03:04:33'),
(54, 2, 16, '2026-01-12 03:04:33'),
(55, 2, 17, '2026-01-12 03:04:33'),
(56, 2, 18, '2026-01-12 03:04:33'),
(57, 2, 19, '2026-01-12 03:04:33'),
(58, 2, 20, '2026-01-12 03:04:33'),
(60, 2, 22, '2026-01-12 03:04:33'),
(61, 2, 23, '2026-01-12 03:04:33'),
(62, 2, 24, '2026-01-12 03:04:33'),
(63, 2, 25, '2026-01-12 03:04:33'),
(65, 2, 27, '2026-01-12 03:04:33'),
(66, 2, 28, '2026-01-12 03:04:33'),
(67, 2, 29, '2026-01-12 03:04:33'),
(68, 2, 30, '2026-01-12 03:04:33'),
(70, 3, 5, '2026-01-12 03:04:33'),
(71, 3, 9, '2026-01-12 03:04:33'),
(72, 3, 13, '2026-01-12 03:04:33'),
(73, 3, 17, '2026-01-12 03:04:33'),
(74, 3, 18, '2026-01-12 03:04:33'),
(75, 3, 22, '2026-01-12 03:04:33'),
(76, 3, 23, '2026-01-12 03:04:33'),
(77, 3, 27, '2026-01-12 03:04:33'),
(79, 4, 5, '2026-01-12 03:04:33'),
(80, 4, 9, '2026-01-12 03:04:33'),
(81, 4, 13, '2026-01-12 03:04:33'),
(82, 4, 17, '2026-01-12 03:04:33'),
(83, 4, 22, '2026-01-12 03:04:33'),
(84, 4, 27, '2026-01-12 03:04:33'),
(85, 4, 28, '2026-01-12 03:04:33'),
(86, 4, 29, '2026-01-12 03:04:33'),
(87, 4, 30, '2026-01-12 03:04:33'),
(88, 5, 28, '2026-01-12 03:04:33'),
(99, 1, 5, '2026-01-12 03:56:16'),
(100, 1, 6, '2026-01-12 03:56:16'),
(101, 1, 7, '2026-01-12 03:56:16'),
(102, 1, 8, '2026-01-12 03:56:16'),
(103, 1, 9, '2026-01-12 03:56:16'),
(104, 1, 10, '2026-01-12 03:56:16'),
(105, 1, 11, '2026-01-12 03:56:16'),
(106, 1, 12, '2026-01-12 03:56:16'),
(107, 1, 13, '2026-01-12 03:56:16'),
(108, 1, 14, '2026-01-12 03:56:16'),
(109, 1, 15, '2026-01-12 03:56:16'),
(110, 1, 16, '2026-01-12 03:56:16'),
(111, 1, 17, '2026-01-12 03:56:16'),
(112, 1, 18, '2026-01-12 03:56:16'),
(113, 1, 19, '2026-01-12 03:56:16'),
(114, 1, 20, '2026-01-12 03:56:16'),
(115, 1, 21, '2026-01-12 03:56:16'),
(116, 1, 22, '2026-01-12 03:56:16'),
(117, 1, 23, '2026-01-12 03:56:16'),
(118, 1, 24, '2026-01-12 03:56:16'),
(119, 1, 25, '2026-01-12 03:56:16'),
(120, 1, 26, '2026-01-12 03:56:16'),
(121, 1, 27, '2026-01-12 03:56:16'),
(122, 1, 28, '2026-01-12 03:56:16'),
(123, 1, 29, '2026-01-12 03:56:16'),
(124, 1, 30, '2026-01-12 03:56:16'),
(125, 1, 31, '2026-01-12 03:56:16'),
(126, 1, 32, '2026-01-12 03:56:16'),
(127, 1, 33, '2026-01-12 03:56:16'),
(128, 1, 34, '2026-01-12 03:56:16'),
(129, 1, 35, '2026-01-12 03:56:16'),
(130, 1, 36, '2026-01-12 03:56:16'),
(131, 1, 37, '2026-01-12 03:56:16'),
(132, 1, 38, '2026-01-12 03:56:16'),
(133, 1, 39, '2026-01-12 04:30:07'),
(134, 1, 40, '2026-01-12 04:30:07'),
(135, 1, 41, '2026-01-12 04:30:07'),
(136, 1, 42, '2026-01-12 04:30:07'),
(140, 2, 39, '2026-01-12 04:30:07'),
(141, 2, 40, '2026-01-12 04:30:07'),
(142, 2, 41, '2026-01-12 04:30:07'),
(148, 1, 48, '2026-01-12 07:46:58'),
(149, 1, 49, '2026-01-12 07:46:58'),
(150, 1, 50, '2026-01-12 07:46:58'),
(151, 1, 51, '2026-01-12 07:46:58'),
(162, 1, 48, '2026-01-12 07:46:58'),
(163, 1, 49, '2026-01-12 07:46:58'),
(164, 1, 50, '2026-01-12 07:46:58'),
(165, 1, 51, '2026-01-12 07:46:58'),
(173, 3, 48, '2026-01-12 07:46:58'),
(176, 3, 21, '2026-01-15 03:37:26'),
(177, 3, 26, '2026-01-15 03:37:26'),
(178, 1, 57, '2026-01-15 03:37:26'),
(179, 2, 57, '2026-01-15 03:37:26'),
(180, 4, 57, '2026-01-15 03:37:26'),
(195, 1, 63, '2026-01-15 04:12:27'),
(196, 2, 63, '2026-01-15 04:12:27'),
(197, 3, 63, '2026-01-15 04:12:27'),
(198, 4, 63, '2026-01-15 04:12:27'),
(212, 2, 42, '2026-01-15 04:12:40'),
(213, 2, 48, '2026-01-15 04:12:40'),
(214, 2, 49, '2026-01-15 04:12:40'),
(215, 2, 50, '2026-01-15 04:12:40'),
(216, 2, 51, '2026-01-15 04:12:40');
-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stock_takes`
--

CREATE TABLE `stock_takes` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int NOT NULL,
  `stock_take_date` date NOT NULL,
  `status` enum('draft','in_progress','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `created_by` int DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stock_take_details`
--

CREATE TABLE `stock_take_details` (
  `id` int NOT NULL,
  `stock_take_id` int NOT NULL,
  `product_id` int NOT NULL,
  `system_quantity` int NOT NULL COMMENT 'Số lượng trên hệ thống',
  `actual_quantity` int NOT NULL COMMENT 'Số lượng thực tế kiểm đếm',
  `variance` int NOT NULL COMMENT 'Chênh lệch (actual - system)',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tax_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `suppliers`
--

INSERT INTO `suppliers` (`id`, `code`, `name`, `contact_person`, `phone`, `email`, `address`, `tax_code`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'NCC001', 'Công ty TNHH Điện Tử ABC', 'Nguyễn Văn A', '0901111111', 'abc@example.com', '123 Nguyễn Huệ, Q1, TP.HCM', '0123456789', 'active', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(2, 'NCC002', 'Công ty CP Văn Phòng Phẩm XYZ', 'Trần Thị B', '0902222222', 'xyz@example.com', '456 Lê Lợi, Q1, TP.HCM', '0987654321', 'active', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(3, 'NCC003', 'Công ty TNHH Thực Phẩm DEF', 'Lê Văn C', '0903333333', 'def@example.com', '789 Trần Hưng Đạo, Q5, TP.HCM', '0111222333', 'active', NULL, '2026-01-12 03:04:33', '2026-01-12 03:04:33'),
(4, 'NCC-NL', 'Công ty Nguyên liệu Việt', 'Nguyễn Văn A', '0901111111', 'nguyenlieu@vn.com', 'Hà Nội', '0123456789', 'active', 'Chuyên cung cấp nguyên liệu thực phẩm', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(5, 'NCC-NNL', 'Công ty Nhiên liệu Petro', 'Trần Văn B', '0902222222', 'petro@vn.com', 'TP.HCM', '0123456790', 'active', 'Chuyên cung cấp nhiên liệu công nghiệp', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(6, 'NCC-PT', 'Công ty Phụ tùng Cơ khí', 'Lê Văn C', '0903333333', 'cokhi@vn.com', 'Đà Nẵng', '0123456791', 'active', 'Chuyên cung cấp phụ tùng máy móc', '2026-01-12 06:05:36', '2026-01-12 06:05:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `warehouse_id` int NOT NULL,
  `product_id` int NOT NULL,
  `transaction_type` enum('import','export') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` enum('import','export') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` int NOT NULL,
  `reference_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance_before` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance_after` decimal(10,2) NOT NULL DEFAULT '0.00',
  `transaction_date` datetime NOT NULL,
  `created_by` int DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `transactions`
--

INSERT INTO `transactions` (`id`, `warehouse_id`, `product_id`, `transaction_type`, `reference_type`, `reference_id`, `reference_code`, `quantity`, `balance_before`, `balance_after`, `transaction_date`, `created_by`, `notes`, `created_at`) VALUES
(1, 2, 3, 'import', 'import', 1, 'PN-20260115-001', 1.00, 0.00, 1.00, '2026-01-15 10:59:57', 1, NULL, '2026-01-15 03:59:57'),
(2, 2, 13, 'import', 'import', 1, 'PN-20260115-001', 1.00, 0.00, 1.00, '2026-01-15 10:59:57', 1, NULL, '2026-01-15 03:59:57'),
(3, 2, 3, 'import', 'import', 1, 'PN-20260115-001', 20.00, 1.00, 21.00, '2026-01-15 10:59:57', 1, NULL, '2026-01-15 03:59:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  `warehouse_id` int DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `role_id`, `warehouse_id`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$DZYUrFbet6HOfvU8Sr6HaOnNIEoZD1EVKuRe9/YfdqFax2qzBIPpK', 'Administrator', 'admin@gmail.com', '0901234567', 1, NULL, 'active', '2026-01-15 11:13:02', '2026-01-12 03:04:33', '2026-01-15 04:13:02'),
(2, 'manager', '$2y$10$1BsHasDrsLIMSghEa.cmRuGVxh/.V7dX2bBUIJdpryZ5Fha7jGyXG', 'Đào Văn Hùng (QL)', 'manager@qlkho.com', '0902234567', 2, NULL, 'active', NULL, '2026-01-12 03:04:33', '2026-01-12 06:22:12'),
(3, 'staff', '$2y$10$1BsHasDrsLIMSghEa.cmRuGVxh/.V7dX2bBUIJdpryZ5Fha7jGyXG', 'Nguyễn Văn Tú (NV-KHO1)', 'staff@qlkho.com', '0903234567', 3, NULL, 'active', NULL, '2026-01-12 03:04:33', '2026-01-12 06:22:23'),
(4, 'lamnh', '$2y$10$8c.qHg.ALPXMjobtUxvdJOEMaeCc1FcMxgBiytG4EH8hlmk9yhQqi', 'Nguyễn Hữu Lâm', 'lamnh@gmail.com', '0987654321', 2, NULL, 'active', NULL, '2026-01-12 08:55:22', '2026-01-12 08:55:22'),
(5, 'thukhonhienlieu', '$2y$12$CCOy4pBwfiJzeM2jlswElO774qLSsHh7T7JEcU7auMIagPlhgsSqa', 'Thủ kho nhiên liệu', 'thukhonhienlieu@qlkho.com', '0904111111', 3, 5, 'active', NULL, '2026-01-12 09:10:00', '2026-01-12 09:10:00'),
(6, 'thukhonguyenlieu', '$2y$12$CCOy4pBwfiJzeM2jlswElO774qLSsHh7T7JEcU7auMIagPlhgsSqa', 'Thủ kho nguyên liệu', 'thukhonguyenlieu@qlkho.com', '0904222222', 3, 4, 'active', NULL, '2026-01-12 09:10:00', '2026-01-12 09:10:00'),
(7, 'thukhoputung', '$2y$12$CCOy4pBwfiJzeM2jlswElO774qLSsHh7T7JEcU7auMIagPlhgsSqa', 'Thủ kho phụ tùng', 'thukhoputung@qlkho.com', '0904333333', 3, 6, 'active', NULL, '2026-01-12 09:10:00', '2026-01-12 09:10:00'),
(8, 'thukhothanhpham', '$2y$12$CCOy4pBwfiJzeM2jlswElO774qLSsHh7T7JEcU7auMIagPlhgsSqa', 'Thủ kho thành phẩm', 'thukhothanhpham@qlkho.com', '0904444445', 3, 7, 'active', NULL, '2026-01-12 09:10:00', '2026-01-12 09:10:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `manager_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manager_id` int DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` decimal(15,2) DEFAULT NULL COMMENT 'Sức chứa (m2)',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `warehouses`
--

INSERT INTO `warehouses` (`id`, `code`, `name`, `location`, `address`, `manager_name`, `manager_id`, `phone`, `capacity`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(4, 'KHO-NL', 'Kho nguyên liệu', NULL, NULL, 'Nguyễn Văn A', NULL, NULL, 1000.00, 'active', 'Lưu trữ các loại nguyên liệu đầu vào như đường, bột, hương liệu, bao bì', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(5, 'KHO-NNL', 'Kho nhiên liệu', NULL, NULL, 'Trần Văn B', NULL, NULL, 500.00, 'active', 'Lưu trữ các loại nhiên liệu phục vụ sản xuất như than, dầu', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(6, 'KHO-PT', 'Kho phụ tùng', NULL, NULL, 'Lê Thị C', NULL, NULL, 300.00, 'active', 'Lưu trữ các chi tiết, linh kiện máy móc dùng cho việc sửa chữa và thay thế', '2026-01-12 06:05:36', '2026-01-12 06:05:36'),
(7, 'KHO-TP', 'Kho thành phẩm', NULL, NULL, 'Phạm Văn D', NULL, NULL, 2000.00, 'active', 'Lưu trữ các sản phẩm bánh kẹo đã hoàn thành và sẵn sàng tiêu thụ', '2026-01-12 06:05:36', '2026-01-12 06:05:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `workshops`
--

CREATE TABLE `workshops` (
  `id` int NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `manager_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `workshops`
--

INSERT INTO `workshops` (`id`, `code`, `name`, `location`, `manager_name`, `phone`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'PX001', 'Phân xưởng sản xuất 1', 'Khu A, Nhà máy chính', 'Nguyễn Văn Sản', '0907777777', 'active', NULL, '2026-01-12 07:43:37', '2026-01-12 07:43:37'),
(2, 'PX002', 'Phân xưởng lắp ráp', 'Khu B, Nhà máy chính', 'Trần Thị Lắp', '0908888888', 'active', NULL, '2026-01-12 07:43:37', '2026-01-12 07:43:37'),
(3, 'PX003', 'Phân xưởng đóng gói', 'Khu C, Nhà máy chính', 'Lê Văn Gói', '0909999999', 'active', NULL, '2026-01-12 07:43:37', '2026-01-12 07:43:37');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `agencies`
--
ALTER TABLE `agencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `exports`
--
ALTER TABLE `exports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `warehouse_id` (`warehouse_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Chỉ mục cho bảng `export_details`
--
ALTER TABLE `export_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `export_id` (`export_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `imports`
--
ALTER TABLE `imports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `warehouse_id` (`warehouse_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Chỉ mục cho bảng `import_details`
--
ALTER TABLE `import_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `import_id` (`import_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouse_product` (`warehouse_id`,`product_id`),
  ADD KEY `warehouse_id` (`warehouse_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_id` (`warehouse_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Chỉ mục cho bảng `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Chỉ mục cho bảng `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Chỉ mục cho bảng `stock_takes`
--
ALTER TABLE `stock_takes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `warehouse_id` (`warehouse_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Chỉ mục cho bảng `stock_take_details`
--
ALTER TABLE `stock_take_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_take_id` (`stock_take_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_warehouse` (`warehouse_id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_date` (`transaction_date`),
  ADD KEY `idx_reference` (`reference_type`,`reference_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `idx_warehouse` (`warehouse_id`);

--
-- Chỉ mục cho bảng `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_manager` (`manager_id`);

--
-- Chỉ mục cho bảng `workshops`
--
ALTER TABLE `workshops`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `agencies`
--
ALTER TABLE `agencies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `exports`
--
ALTER TABLE `exports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `export_details`
--
ALTER TABLE `export_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `imports`
--
ALTER TABLE `imports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `import_details`
--
ALTER TABLE `import_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT cho bảng `stock_takes`
--
ALTER TABLE `stock_takes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `stock_take_details`
--
ALTER TABLE `stock_take_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `workshops`
--
ALTER TABLE `workshops`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
