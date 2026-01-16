# Sơ Đồ DFD và BFD - Hệ Thống Quản Lý Kho

## 1. Biểu Đồ Luồng Nghiệp Vụ (BFD - Business Flow Diagram)

### Sơ Đồ Tổng Quan Nghiệp Vụ
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
graph TD
    A[Đăng Nhập] --> B{Xác Thực}
    B -->|Thất Bại| A
    B -->|Thành Công| C[Dashboard]
    
    C --> D[Quản Lý Người Dùng & Phân Quyền]
    C --> E[Quản Lý Sản Phẩm]
    C --> F[Quản Lý Kho]
    C --> G[Nhập/Xuất Kho]
    C --> H[Kiểm Kê Kho]
    C --> I[Quản Lý Thẻ Kho]
    C --> J[Báo Cáo]
    C --> K[Đăng Xuất]
    
    D --> D1[Thêm/Sửa Người Dùng]
    D --> D2[Phân Quyền]
    
    E --> E1[Thêm/Sửa Sản Phẩm]
    E --> E2[Phân Loại Sản Phẩm]
    
    F --> F1[Kho Vật Tư]
    F --> F2[Kho Thành Phẩm]
    
    G --> G1[Tạo Phiếu Nhập]
    G --> G2[Tạo Phiếu Xuất]
    G --> G3[Duyệt Phiếu]
    
    H --> H1[Tạo Phiếu Kiểm Kê]
    H --> H2[Duyệt Kiểm Kê]
    
    I --> I1[Xem Thẻ Kho Theo Mặt Hàng]
    I --> I2[Theo Dõi Nhập/Xuất/Tồn]
    
    J --> J1[Báo Cáo Tồn Kho]
    J --> J2[Báo Cáo Nhập/Xuất]
```

### Luồng Nghiệp Vụ - Đăng Nhập & Phân Quyền
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
flowchart TD
    Start([Bắt Đầu]) --> Login[Nhập Username/Password]
    Login --> Validate{Xác Thực}
    Validate -->|Sai| Error[Thông Báo Lỗi]
    Error --> Login
    Validate -->|Đúng| CheckRole{Kiểm Tra Quyền}
    CheckRole -->|Admin| AdminDash[Dashboard Admin]
    CheckRole -->|Manager| ManagerDash[Dashboard Quản Lý]
    CheckRole -->|Staff| StaffDash[Dashboard Thủ Kho]
    
    AdminDash --> AllFunc[Toàn Bộ Chức Năng]
    ManagerDash --> MgrFunc[Duyệt Phiếu, Báo Cáo]
    StaffDash --> StaffFunc[Nhập/Xuất/Kiểm Kê]
    
    AllFunc --> Logout[Đăng Xuất]
    MgrFunc --> Logout
    StaffFunc --> Logout
    Logout --> End([Kết Thúc])
```

### Luồng Nghiệp Vụ - Nhập/Xuất Kho
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
flowchart TD
    Start([Bắt Đầu]) --> SelectType{Chọn Loại}
    SelectType -->|Nhập Kho| SelectWhIn[Chọn Kho Nhập]
    SelectType -->|Xuất Kho| SelectWhEx[Chọn Kho Xuất]
    
    SelectWhIn --> TypeIn{Loại Kho}
    SelectWhEx --> TypeEx{Loại Kho}
    
    TypeIn -->|Vật Tư| InMaterial[Nhập Kho Vật Tư]
    TypeIn -->|Thành Phẩm| InProduct[Nhập Kho Thành Phẩm]
    TypeEx -->|Vật Tư| ExMaterial[Xuất Kho Vật Tư]
    TypeEx -->|Thành Phẩm| ExProduct[Xuất Kho Thành Phẩm]
    
    InMaterial --> AddItems[Thêm Mặt Hàng]
    InProduct --> AddItems
    ExMaterial --> AddItems
    ExProduct --> AddItems
    
    AddItems --> InputQty[Nhập SL, Đơn Giá]
    InputQty --> CalcTotal[Tính Tổng Tiền]
    CalcTotal --> MoreItems{Thêm Mặt Hàng?}
    MoreItems -->|Có| AddItems
    MoreItems -->|Không| SaveDoc[Lưu Phiếu]
    
    SaveDoc --> Approve{Duyệt?}
    Approve -->|Không| Pending[Chờ Duyệt]
    Approve -->|Có| UpdateInv[Cập Nhật Tồn Kho]
    UpdateInv --> UpdateCard[Cập Nhật Thẻ Kho]
    UpdateCard --> LogTrans[Ghi Log]
    LogTrans --> End([Hoàn Thành])
    Pending --> End
```

### Luồng Nghiệp Vụ - Kiểm Kê Kho
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
flowchart TD
    Start([Bắt Đầu]) --> SelectWh[Chọn Kho]
    SelectWh --> WhType{Loại Kho}
    WhType -->|Vật Tư| LoadMat[Load Tồn Kho Vật Tư]
    WhType -->|Thành Phẩm| LoadProd[Load Tồn Kho Thành Phẩm]
    
    LoadMat --> Count[Kiểm Đếm Thực Tế]
    LoadProd --> Count
    
    Count --> InputActual[Nhập SL Thực Tế]
    InputActual --> CalcVar[Tính Chênh Lệch]
    CalcVar --> ShowVar{Hiển Thị}
    ShowVar -->|Thừa| Green[Xanh: +X]
    ShowVar -->|Thiếu| Red[Đỏ: -X]
    ShowVar -->|Bằng| Black[Đen: 0]
    
    Green --> Save[Lưu Phiếu Kiểm Kê]
    Red --> Save
    Black --> Save
    
    Save --> Approve{Duyệt?}
    Approve -->|Không| Draft[Nháp]
    Approve -->|Có| Adjust[Điều Chỉnh Tồn Kho]
    Adjust --> UpdateCard[Cập Nhật Thẻ Kho]
    UpdateCard --> Log[Ghi Log]
    Log --> End([Hoàn Thành])
    Draft --> End
```

### Luồng Nghiệp Vụ - Quản Lý Thẻ Kho
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
flowchart TD
    Start([Bắt Đầu]) --> SelectItem[Chọn Mặt Hàng]
    SelectItem --> SelectWh[Chọn Kho]
    SelectWh --> SelectPeriod[Chọn Khoảng Thời Gian]
    
    SelectPeriod --> LoadCard[Load Thẻ Kho]
    LoadCard --> ShowData[Hiển Thị Dữ Liệu]
    
    ShowData --> ShowDate[Ngày Ghi]
    ShowData --> ShowIn[Số Nhập]
    ShowData --> ShowOut[Số Xuất]
    ShowData --> ShowBal[Số Tồn]
    ShowData --> ShowRef[Chứng Từ]
    
    ShowRef --> ViewDoc{Xem Chi Tiết?}
    ViewDoc -->|Phiếu Nhập| OpenIn[Mở Phiếu Nhập]
    ViewDoc -->|Phiếu Xuất| OpenEx[Mở Phiếu Xuất]
    ViewDoc -->|Kiểm Kê| OpenST[Mở Phiếu Kiểm Kê]
    ViewDoc -->|Không| Export{Xuất Báo Cáo?}
    
    OpenIn --> Export
    OpenEx --> Export
    OpenST --> Export
    
    Export -->|Có| ExportFile[Xuất Excel/PDF]
    Export -->|Không| End([Kết Thúc])
    ExportFile --> End
```

## 2. Sơ Đồ Luồng Dữ Liệu (DFD - Data Flow Diagram)

### Ký Hiệu Chuẩn DFD

#### Tác nhân ngoài (External Entities)
- **E1**: Phòng cung ứng
- **E2**: Phòng kinh doanh
- **E3**: Ban giám đốc

#### Tiến trình (Processes)
- **P1**: Tiếp nhận & xử lý phiếu nhập kho
- **P2**: Tiếp nhận & xử lý phiếu xuất kho
- **P3**: Cập nhật tồn kho & thẻ kho
- **P4**: Kiểm kê kho
- **P5**: Lập báo cáo kho

#### Kho dữ liệu (Data Stores)
- **D1**: Danh mục hàng hóa
- **D2**: Danh mục kho
- **D3**: Phiếu nhập kho
- **D4**: Phiếu xuất kho
- **D5**: Thẻ kho
- **D6**: Dữ liệu kiểm kê
- **D7**: Báo cáo kho

### DFD Mức 0 - Sơ Đồ Ngữ Cảnh
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
graph LR
    E1[E1<br/>Phòng Cung Ứng] -->|Phiếu nhập kho<br/>nguyên liệu, nhiên liệu| System((HỆ THỐNG<br/>QUẢN LÝ KHO))
    E2[E2<br/>Phòng Kinh Doanh] -->|Phiếu nhập/xuất kho<br/>thành phẩm| System
    E3[E3<br/>Ban Giám Đốc] -->|Yêu cầu báo cáo| System
    
    System -->|Xác nhận nhập kho| E1
    System -->|Xác nhận xuất kho| E2
    System -->|Báo cáo kho định kỳ| E3
```

### DFD Mức 1 - Chi Tiết Các Chức Năng
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
flowchart TD
    %% External Entities
    E1[E1: Phòng Cung Ứng]
    E2[E2: Phòng Kinh Doanh]
    E3[E3: Ban Giám Đốc]
    
    %% Processes
    P1[P1<br/>Tiếp nhận & xử lý<br/>phiếu nhập kho]
    P2[P2<br/>Tiếp nhận & xử lý<br/>phiếu xuất kho]
    P3[P3<br/>Cập nhật tồn kho<br/>& thẻ kho]
    P4[P4<br/>Kiểm kê kho]
    P5[P5<br/>Lập báo cáo kho]
    
    %% Data Stores
    D1[(D1: Danh mục<br/>hàng hóa)]
    D2[(D2: Danh mục kho)]
    D3[(D3: Phiếu nhập kho)]
    D4[(D4: Phiếu xuất kho)]
    D5[(D5: Thẻ kho)]
    D6[(D6: Dữ liệu kiểm kê)]
    D7[(D7: Báo cáo kho)]
    
    %% Luồng NHẬP KHO
    E1 -->|Phiếu nhập kho<br/>nguyên liệu, nhiên liệu| P1
    E2 -->|Phiếu nhập kho<br/>thành phẩm| P1
    P1 -->|Phiếu đã xác nhận| D3
    P1 -->|Thông tin nhập kho<br/>mã hàng, số lượng| P3
    
    %% Luồng XUẤT KHO
    E1 -->|Phiếu xuất kho<br/>nguyên liệu, nhiên liệu| P2
    E2 -->|Phiếu xuất kho<br/>thành phẩm| P2
    P2 -->|Phiếu đã xác nhận| D4
    P2 -->|Thông tin xuất kho<br/>mã hàng, số lượng| P3
    
    %% Luồng CẬP NHẬT TỒN KHO & THẺ KHO
    P3 <-->|Thông tin nhập-xuất-tồn<br/>theo mặt hàng| D5
    P3 <-->|Thông tin kho<br/>mã kho, loại kho| D2
    P3 -->|Cập nhật số lượng tồn| D1
    D1 -->|Thông tin hàng hóa| P3
    
    %% Luồng KIỂM KÊ
    P4 <-->|Tồn kho theo hệ thống| D5
    P4 -->|Biên bản kiểm kê<br/>chênh lệch| D6
    P4 -->|Kết quả kiểm kê<br/>để điều chỉnh| P3
    
    %% Luồng BÁO CÁO
    D1 -->|Thông tin hàng hóa| P5
    D3 -->|Phiếu nhập kho| P5
    D4 -->|Phiếu xuất kho| P5
    D5 -->|Thẻ kho, tồn kho| P5
    D6 -->|Dữ liệu kiểm kê| P5
    P5 -->|Báo cáo tồn kho<br/>biến động kho| D7
    P5 -->|Báo cáo kho định kỳ| E3
```

### Mô Tả Chi Tiết Dòng Dữ Liệu

#### 2.1. Luồng NHẬP KHO
| Từ | Đến | Dữ liệu |
|---|---|---|
| E1 | P1 | Phiếu nhập kho (nguyên liệu, nhiên liệu, phụ tùng) |
| E2 | P1 | Phiếu nhập kho thành phẩm |
| P1 | D3 | Phiếu nhập kho đã được kiểm tra và xác nhận |
| P1 | P3 | Thông tin nhập kho (mã hàng, số lượng, kho nhập) |
| P3 | D5 | Cập nhật thẻ kho (nhập) |
| P3 | D1 | Cập nhật số lượng tồn hàng hóa |

#### 2.2. Luồng XUẤT KHO
| Từ | Đến | Dữ liệu |
|---|---|---|
| E1 | P2 | Phiếu xuất kho (nguyên liệu, nhiên liệu, phụ tùng) |
| E2 | P2 | Phiếu xuất kho thành phẩm |
| P2 | D4 | Phiếu xuất kho đã được xác nhận |
| P2 | P3 | Thông tin xuất kho (mã hàng, số lượng, kho xuất) |
| P3 | D5 | Cập nhật thẻ kho (xuất) |
| P3 | D1 | Giảm số lượng tồn hàng hóa |

#### 2.3. Luồng TỒN KHO & THẺ KHO
| Từ | Đến | Dữ liệu |
|---|---|---|
| P3 | D5 | Thông tin nhập – xuất – tồn theo từng mặt hàng |
| D5 | P3 | Dữ liệu thẻ kho hiện tại |
| P3 | D2 | Thông tin kho (mã kho, loại kho) |
| D2 | P3 | Danh mục kho |

#### 2.4. Luồng KIỂM KÊ KHO
| Từ | Đến | Dữ liệu |
|---|---|---|
| D5 | P4 | Tồn kho theo hệ thống |
| P4 | D6 | Biên bản kiểm kê, chênh lệch |
| P4 | P3 | Kết quả kiểm kê để điều chỉnh tồn kho (nếu có) |

#### 2.5. Luồng BÁO CÁO KHO
| Từ | Đến | Dữ liệu |
|---|---|---|
| D1 | P5 | Thông tin hàng hóa |
| D3 | P5 | Phiếu nhập kho |
| D4 | P5 | Phiếu xuất kho |
| D5 | P5 | Thẻ kho / tồn kho |
| D6 | P5 | Dữ liệu kiểm kê |
| P5 | D7 | Báo cáo tồn kho, báo cáo biến động kho |
| P5 | E3 | Báo cáo kho định kỳ |
```

### DFD Mức 2 - Chi Tiết Kiểm Kê Kho (Process P4)
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
flowchart TD
    User[Thủ Kho] -->|Chọn kho kiểm kê| P41[P4.1<br/>Chọn Kho<br/>Kiểm Kê]
    D2[(D2: Danh mục kho)] -->|Danh sách kho| P41
    
    P41 -->|Mã kho| P42[P4.2<br/>Load Tồn Kho<br/>Hệ Thống]
    D5[(D5: Thẻ kho)] -->|Tồn kho theo hệ thống| P42
    D1[(D1: Danh mục<br/>hàng hóa)] -->|Thông tin hàng hóa| P42
    
    P42 -->|Danh sách tồn kho| P43[P4.3<br/>Nhập SL<br/>Thực Tế]
    User -->|Số lượng đếm thực tế| P43
    
    P43 -->|Dữ liệu kiểm kê| P44[P4.4<br/>Tính<br/>Chênh Lệch]
    P44 -->|Variance = Actual - System| P45[P4.5<br/>Hiển Thị<br/>Kết Quả]
    
    P45 -->|Lưu biên bản kiểm kê| D6[(D6: Dữ liệu<br/>kiểm kê)]
    
    User -->|Duyệt kiểm kê| P46[P4.6<br/>Duyệt<br/>Kiểm Kê]
    D6 -->|Đọc biên bản| P46
    
    P46 -->|Kết quả điều chỉnh| P3[P3<br/>Cập nhật tồn kho<br/>& thẻ kho]
    P46 -->|Xác nhận hoàn thành| User
```


## 3. Sơ Đồ ERD - Mối Quan Hệ Dữ Liệu
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
erDiagram
    users ||--o{ imports : "tạo"
    users ||--o{ exports : "tạo"
    users ||--o{ purchase_orders : "tạo"
    users ||--o{ stock_takes : "tạo"
    
    categories ||--o{ products : "thuộc"
    
    suppliers ||--o{ imports : "cung_cấp"
    suppliers ||--o{ purchase_orders : "nhận_đơn"
    
    warehouses ||--o{ imports : "nhập_vào"
    warehouses ||--o{ exports : "xuất_từ"
    warehouses ||--o{ inventory : "chứa"
    warehouses ||--o{ stock_takes : "kiểm_kê"
    
    products ||--o{ import_details : "được_nhập"
    products ||--o{ export_details : "được_xuất"
    products ||--o{ inventory : "tồn_kho"
    products ||--o{ purchase_order_details : "đặt_hàng"
    products ||--o{ stock_take_details : "kiểm_kê"
    
    imports ||--o{ import_details : "có"
    exports ||--o{ export_details : "có"
    purchase_orders ||--o{ purchase_order_details : "có"
    stock_takes ||--o{ stock_take_details : "có"
    
    warehouses ||--o{ inventory_transactions : "giao_dịch"
    products ||--o{ inventory_transactions : "liên_quan"
    users ||--o{ inventory_transactions : "thực_hiện"
    
    users {
        int id PK
        string username UK
        string password
        int role_id FK
        datetime created_at
    }
    
    products {
        int id PK
        string code UK
        string name
        int category_id FK
        string unit
        decimal min_stock
        decimal max_stock
        string status
        datetime created_at
    }
    
    categories {
        int id PK
        string code UK
        string name
        text description
        datetime created_at
    }
    
    warehouses {
        int id PK
        string code UK
        string name
        string address
        int manager_id FK
        decimal capacity
        string status
        datetime created_at
    }
    
    workshops {
        int id PK
        string code UK
        string name
        string location
        string manager_name
        string phone
        string status
        datetime created_at
    }
    
    suppliers {
        int id PK
        string code UK
        string name
        string contact_person
        string phone
        string email
        text address
        string status
        datetime created_at
    }
    
    purchase_orders {
        int id PK
        string code UK
        int supplier_id FK
        date order_date
        date expected_delivery_date
        decimal total_amount
        string status
        int created_by FK
        int approved_by FK
        datetime created_at
    }
    
    purchase_order_details {
        int id PK
        int purchase_order_id FK
        int product_id FK
        int quantity
        decimal unit_price
        decimal total_price
        int received_quantity
        datetime created_at
    }
    
    imports {
        int id PK
        string code UK
        int warehouse_id FK
        int supplier_id FK
        date import_date
        decimal total_amount
        string status
        int created_by FK
        datetime created_at
    }
    
    import_details {
        int id PK
        int import_id FK
        int product_id FK
        int quantity
        decimal unit_price
        decimal total_price
        datetime created_at
    }
    
    exports {
        int id PK
        string code UK
        int warehouse_id FK
        date export_date
        string export_type
        string customer_name
        decimal total_amount
        string status
        int created_by FK
        datetime created_at
    }
    
    export_details {
        int id PK
        int export_id FK
        int product_id FK
        int quantity
        decimal unit_price
        decimal total_price
        datetime created_at
    }
    
    stock_takes {
        int id PK
        string code UK
        int warehouse_id FK
        date stock_take_date
        string status
        int created_by FK
        int approved_by FK
        datetime created_at
    }
    
    stock_take_details {
        int id PK
        int stock_take_id FK
        int product_id FK
        int system_quantity
        int actual_quantity
        int variance
        datetime created_at
    }
    
    inventory {
        int id PK
        int warehouse_id FK
        int product_id FK
        int quantity
        datetime last_updated
    }
    
    inventory_transactions {
        int id PK
        int warehouse_id FK
        int product_id FK
        string transaction_type
        int quantity
        int quantity_before
        int quantity_after
        string reference_type
        int reference_id
        int created_by FK
        datetime created_at
    }
```

## 4. Sơ Đồ Kiến Trúc Hệ Thống

### 4.1. Kiến Trúc MVC Tổng Quan
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
graph TB
    subgraph Client["🌐 Client Layer"]
        Browser["Web Browser"]
    end
    
    subgraph Application["📱 Application Layer - MVC"]
        Router["Router<br/>.htaccess"]
        
        subgraph Controllers["Controllers"]
            ProdCtrl["ProductController"]
            WhCtrl["WarehouseController"]
            ImpCtrl["ImportController"]
            ExpCtrl["ExportController"]
            POCtrl["PurchaseOrderController"]
            STCtrl["StockTakeController"]
        end
        
        subgraph Models["Models"]
            ProdModel["Product"]
            WhModel["Warehouse"]
            ImpModel["Import"]
            ExpModel["Export"]
            POModel["PurchaseOrder"]
            STModel["StockTake"]
            InvModel["Inventory"]
        end
        
        subgraph Views["Views"]
            ProdViews["product/*"]
            WhViews["warehouse/*"]
            ImpViews["import/*"]
            ExpViews["export/*"]
            POViews["purchase_order/*"]
            STViews["stock_take/*"]
        end
    end
    
    subgraph Core["⚙️ Core Classes"]
        BaseCtrl["Controller<br/>(Base)"]
        BaseModel["Model<br/>(Base)"]
        DB["Database<br/>(PDO)"]
        Auth["Auth<br/>(RBAC)"]
    end
    
    subgraph Data["💾 Data Layer"]
        MySQL[("MySQL<br/>16 Tables")]
    end
    
    Browser -->|HTTP Request| Router
    Router -->|Route| Controllers
    Controllers -->|Extends| BaseCtrl
    Controllers -->|Use| Models
    Controllers -->|Render| Views
    Models -->|Extends| BaseModel
    Models -->|Query| DB
    DB -->|Connect| MySQL
    Controllers -->|Check| Auth
```

### 4.2. RBAC - Phân Quyền
```mermaid
%%{init: {'theme':'default', 'themeVariables': { 'primaryColor':'#fff','primaryTextColor':'#000','primaryBorderColor':'#000','lineColor':'#000','secondaryColor':'#f4f4f4','tertiaryColor':'#fff'}}}%%
graph TB
    subgraph Users["👥 Users"]
        Admin["Admin"]
        Manager["Manager"]
        Staff["Warehouse Staff"]
    end
    
    subgraph Roles["🎭 Roles"]
        RoleAdmin["admin"]
        RoleManager["manager"]
        RoleStaff["warehouse_staff"]
    end
    
    subgraph Permissions["🔐 Permissions"]
        P1["product.*"]
        P2["warehouse.*"]
        P3["import.*"]
        P4["export.*"]
        P5["purchase_order.*"]
        P6["stock_take.*"]
        P7["report.*"]
    end
    
    Admin --> RoleAdmin
    Manager --> RoleManager
    Staff --> RoleStaff
    
    RoleAdmin --> P1 & P2 & P3 & P4 & P5 & P6 & P7
    RoleManager --> P1 & P2 & P3 & P4 & P5 & P6 & P7
    RoleStaff --> P3 & P4 & P6
```

## 5. Mô Tả Các Quy Trình Chính

### 5.1. Quy Trình Nhập Kho
1. **Input**: Kho nhập (vật tư/thành phẩm), nhà cung cấp, sản phẩm, số lượng, đơn giá
2. **Process**:
   - Thủ kho tạo phiếu nhập với nhiều mặt hàng
   - Validate kho và nhà cung cấp
   - Tính tổng tiền
   - Lưu imports (phiếu nhập - chứng từ pháp lý) và import_details
   - **Khi duyệt**: 
     - Cập nhật inventory (tăng số lượng)
     - Cập nhật warehouse_cards (ghi nhập theo ngày)
     - Ghi log vào inventory_transactions
3. **Output**: Phiếu nhập được duyệt, tồn kho tăng, thẻ kho được cập nhật
4. **Data Store**: imports, import_details, inventory, warehouse_cards, inventory_transactions

### 5.2. Quy Trình Xuất Kho
1. **Input**: Kho xuất (vật tư/thành phẩm), khách hàng/phân xưởng, sản phẩm, số lượng
2. **Process**:
   - Thủ kho tạo phiếu xuất với nhiều mặt hàng
   - Validate kho và tồn kho đủ
   - Tính tổng tiền
   - Lưu exports (phiếu xuất - chứng từ pháp lý) và export_details
   - **Khi duyệt**: 
     - Cập nhật inventory (giảm số lượng)
     - Cập nhật warehouse_cards (ghi xuất theo ngày)
     - Ghi log vào inventory_transactions
3. **Output**: Phiếu xuất được duyệt, tồn kho giảm, thẻ kho được cập nhật
4. **Data Store**: exports, export_details, inventory, warehouse_cards, inventory_transactions

### 5.3. Quy Trình Kiểm Kê
1. **Input**: Kho kiểm kê (vật tư/thành phẩm), số lượng thực tế
2. **Process**:
   - Thủ kho chọn kho và load tồn kho hệ thống từ inventory
   - Kiểm đếm và nhập số lượng thực tế
   - Tính variance = actual - system
   - Hiển thị màu: xanh (+), đỏ (-), đen (0)
   - Lưu stock_takes và stock_take_details
   - **Khi duyệt**: 
     - Điều chỉnh inventory theo actual
     - Cập nhật warehouse_cards (ghi điều chỉnh)
     - Ghi log điều chỉnh vào inventory_transactions
3. **Output**: Tồn kho được điều chỉnh, thẻ kho được cập nhật
4. **Data Store**: stock_takes, stock_take_details, inventory, warehouse_cards, inventory_transactions

### 5.4. Quản Lý Thẻ Kho

#### Mục đích
Thẻ kho là công cụ nội bộ để thủ kho theo dõi chi tiết tình hình nhập – xuất – tồn của từng mặt hàng tại từng kho.

#### Thông tin trên Thẻ Kho

**A. Thông tin mặt hàng**:
- Tên vật tư/hàng hóa
- Mã hàng
- Đơn vị tính
- Mẫu mã (nếu có)

**B. Thông tin quản lý**:
- Đơn giá (giá nhập gần nhất hoặc giá bình quân)
- Mức dự trữ tối thiểu
- Mức dự trữ tối đa
- Kho lưu trữ

**C. Thông tin giao dịch** (theo từng dòng):
- Ngày phát sinh nghiệp vụ
- Số chứng từ (mã phiếu nhập/xuất/kiểm kê)
- Loại chứng từ (Phiếu nhập/Phiếu xuất/Biên bản kiểm kê)
- Số lượng nhập
- Số lượng xuất
- Số lượng tồn (sau giao dịch)
- Ghi chú (nếu có)

#### Quy trình

1. **Input**: Mặt hàng, kho, khoảng thời gian
2. **Process**:
   - Thủ kho chọn mặt hàng và kho cần xem
   - Hệ thống tổng hợp từ các phiếu nhập/xuất/kiểm kê đã duyệt
   - Hiển thị theo thứ tự thời gian:
     - Ngày ghi
     - Số chứng từ (có link đến phiếu gốc)
     - Số nhập
     - Số xuất
     - Số tồn (tính tự động)
   - Cho phép xem chi tiết phiếu nhập/xuất/kiểm kê từ thẻ kho
   - Xuất báo cáo thẻ kho (Excel/PDF)
   - Cảnh báo khi tồn kho < mức tối thiểu hoặc > mức tối đa
3. **Output**: Báo cáo thẻ kho theo mặt hàng
4. **Data Store**: warehouse_cards (inventory_transactions), imports, exports, stock_takes

#### Ví dụ Thẻ Kho

```
THẺ KHO
─────────────────────────────────────────────────────
Tên hàng: Thép tròn D10          Mã hàng: VT001
Đơn vị tính: Kg                  Đơn giá: 15,000 đ/kg
Mức tồn tối thiểu: 500 kg        Mức tồn tối đa: 2,000 kg
Kho: Kho vật tư A
─────────────────────────────────────────────────────
Ngày    | Chứng từ  | Loại    | Nhập  | Xuất  | Tồn
─────────────────────────────────────────────────────
01/01   | Tồn đầu   | -       | -     | -     | 800
05/01   | PN001     | Nhập    | 500   | -     | 1,300
10/01   | PX001     | Xuất    | -     | 300   | 1,000
15/01   | PN002     | Nhập    | 700   | -     | 1,700
20/01   | PX002     | Xuất    | -     | 400   | 1,300
25/01   | KK001     | Kiểm kê | -     | 50    | 1,250 (Điều chỉnh: -50)
─────────────────────────────────────────────────────
```

---

**Ghi chú**: 
- **Phiếu nhập/xuất**: Chứng từ mang tính pháp lý, mỗi phiếu có thể gồm nhiều mặt hàng
- **Thẻ kho**: Công cụ nội bộ để thủ kho theo dõi nhập–xuất–tồn theo từng mặt hàng, được tổng hợp từ phiếu nhập/xuất và ghi theo từng ngày
- **Kho vật tư**: Phục vụ cung ứng/sản xuất
- **Kho thành phẩm**: Phục vụ kinh doanh/bán hàng
- Tất cả sơ đồ được vẽ bằng Mermaid và có thể render trong VS Code hoặc GitHub.
