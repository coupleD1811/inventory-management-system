# Hệ thống Quản lý Kho (QLKHO-PHP-THUAN)

Hệ thống quản lý kho được xây dựng bằng **PHP thuần** với kiến trúc **MVC**, tương tự hệ thống QLNS.

## Tính năng chính

### 1. Quản lý danh mục
- ✅ Quản lý danh mục sản phẩm
- ✅ Quản lý sản phẩm
- ✅ Quản lý nhà cung cấp
- ✅ Quản lý kho hàng

### 2. Nhập xuất kho
- ✅ Tạo phiếu nhập kho
- ✅ Tạo phiếu xuất kho
- ✅ Duyệt phiếu nhập/xuất
- ✅ Theo dõi lịch sử giao dịch

### 3. Quản lý tồn kho
- ✅ Xem tồn kho theo kho
- ✅ Xem tồn kho theo sản phẩm
- ✅ Cảnh báo tồn kho tối thiểu/tối đa

### 4. Báo cáo thống kê
- ✅ Báo cáo tồn kho
- ✅ Báo cáo nhập kho
- ✅ Báo cáo xuất kho
- ✅ Báo cáo giao dịch

### 5. Quản lý người dùng & phân quyền
- ✅ Quản lý người dùng
- ✅ Quản lý vai trò
- ✅ Phân quyền chi tiết theo module

## Cấu trúc thư mục

```
qlkho-php-thuan/
├── app/
│   ├── controllers/      # Các controller
│   ├── models/          # Các model
│   ├── views/           # Các view
│   └── core/            # Auth class
├── config/              # Cấu hình
├── core/                # Core classes (MVC)
├── public/              # Public assets
│   ├── css/
│   ├── js/
│   └── index.php       # Entry point
├── qlkho_db.sql        # Database schema
└── README.md
```

## Cài đặt

### 1. Import database
```sql
- Mở phpMyAdmin hoặc MySQL client
- Import file qlkho_db.sql
- Database sẽ được tạo với tên: qlkho_db
```

### 2. Cấu hình database
Mở file `config/database.php` và điều chỉnh thông tin kết nối:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'qlkho_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Cấu hình URL
Mở file `config/config.php` và điều chỉnh BASE_URL:
```php
define('BASE_URL', 'http://localhost/qlkho-php-thuan/');
```

### 4. Khởi động server
Nếu sử dụng Laragon hoặc XAMPP:
- Đảm bảo Apache và MySQL đang chạy
- Truy cập: `http://localhost/qlkho-php-thuan/`

## Đăng nhập

**Tài khoản mặc định:**
- Username: `admin` - Password: `123123123` (Quản trị viên)
- Username: `manager` - Password: `123123123` (Quản lý kho)
- Username: `staff` - Password: `123123123` (Nhân viên kho)

## Yêu cầu hệ thống

- PHP >= 7.4
- MySQL >= 5.7
- Apache với mod_rewrite (hoặc nginx)
- Extension: PDO, PDO_MySQL

## Công nghệ sử dụng

- **Backend:** PHP thuần với kiến trúc MVC
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **CSS Framework:** Tabler UI
- **Icons:** Tabler Icons

## Tính năng bảo mật

- ✅ Password hashing với bcrypt
- ✅ PDO Prepared Statements (chống SQL Injection)
- ✅ Session management
- ✅ Role-based access control (RBAC)
- ✅ Permission-based authorization

## Hướng dẫn sử dụng

### Quản lý sản phẩm
1. Truy cập menu "Danh mục" → "Sản phẩm"
2. Click "Thêm sản phẩm" để thêm mới
3. Nhập thông tin sản phẩm và chọn danh mục
4. Click "Lưu" để hoàn tất

### Nhập kho
1. Truy cập menu "Nhập kho"
2. Click "Tạo phiếu nhập"
3. Chọn kho, nhà cung cấp và thêm sản phẩm
4. Click "Lưu" để tạo phiếu
5. Quản lý có thể duyệt phiếu nhập

### Xuất kho
1. Truy cập menu "Xuất kho"
2. Click "Tạo phiếu xuất"
3. Chọn kho, loại xuất và thêm sản phẩm
4. Click "Lưu" để tạo phiếu
5. Quản lý có thể duyệt phiếu xuất

### Xem tồn kho
1. Truy cập menu "Tồn kho"
2. Chọn kho để xem tồn kho
3. Hệ thống hiển thị số lượng tồn kho của từng sản phẩm

## Mở rộng

Để mở rộng thêm tính năng:

1. **Thêm Model:** Tạo file trong `app/models/` kế thừa từ `Model`
2. **Thêm Controller:** Tạo file trong `app/controllers/` kế thừa từ `Controller`
3. **Thêm View:** Tạo file trong `app/views/[controller]/`

## Liên hệ

Nếu có vấn đề hoặc câu hỏi, vui lòng liên hệ qua email hoặc tạo issue trên repository.
