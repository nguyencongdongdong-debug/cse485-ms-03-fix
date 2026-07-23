# cse485-ms-03 — OOP MiniShop và đăng nhập Session

## Thông tin bài tập

- Repo: `cse485-ms-03`
- Nội dung: OOP, POST, Session, login guard và lưu order thử
- Số sản phẩm: 8
- Tổng giá trị kho: `41380000`

## Cấu trúc dự án

```text
cse485-ms-03/
├── src/
│   ├── Category.php
│   └── Product.php
├── data.php
├── index.php
├── login.php
├── dashboard.php
├── logout.php
└── README.md
```

## Tài khoản đăng nhập

```text
Username: admin
Password: MiniShop@03
```

Mật khẩu được gửi bằng form `POST`, không xuất hiện trong URL.

## Cách chạy

1. Mở XAMPP.
2. Start Apache.
3. Giải nén thư mục vào:

```text
C:\xampp\htdocs\cse485-ms-03
```

4. Truy cập:

```text
http://localhost/cse485-ms-03/
```

## Chức năng

- Class `Category` có thuộc tính `id`, `name`.
- Method `Category::label()` trả về dạng `[id] name`.
- Class `Product` gom dữ liệu và hành vi của sản phẩm.
- `Product::lineTotal()` tính thành tiền.
- `Product::stockLevel()` xác định mức tồn.
- `Product::toArray()` chuyển object thành mảng để debug.
- Login bằng `POST`.
- Lưu trạng thái đăng nhập bằng Session.
- Chặn truy cập `dashboard.php` khi chưa đăng nhập.
- Thêm order thử vào `$_SESSION['orders']`.
- Order vẫn còn sau khi tải lại trang.
- Logout hủy Session và quay lại login.

## Kết quả chuẩn

Sau khi đăng nhập đúng:

```text
So san pham: 8
Tong gia tri kho: 41380000
```

Quy tắc tồn kho:

```text
qty >= 5  => Du
qty >= 2  => Sap het
con lai   => Can nhap
```

## Điểm OOP quan trọng

### Hướng cấu trúc

```php
lineTotal($product);
stockLevel($product);
```

Dữ liệu và hàm nằm tách rời nhau.

### Hướng đối tượng

```php
$product->lineTotal();
$product->stockLevel();
```

Object sản phẩm tự giữ dữ liệu và tự biết cách xử lý dữ liệu của mình.

## Class và object khác nhau thế nào?

- Class là bản thiết kế.
- Object là đối tượng cụ thể được tạo từ class.

Ví dụ:

```php
new Product('KB-01', 'Keychron K2', 1, 1890000, 3);
```

`Product` là class. Sản phẩm Keychron K2 được tạo ra là một object.

## Vì sao dùng Session?

Biến PHP thông thường chỉ tồn tại trong một request. Khi tải lại hoặc chuyển trang, biến đó không còn.

Session giúp lưu trạng thái giữa nhiều request, ví dụ:

- Người dùng đã đăng nhập.
- Username hiện tại.
- Danh sách order thử.

## KỊCH BẢN QUAY VIDEO NỘP BÀI

Nên quay liên tục một video, không cắt giữa các bước quan trọng.

### Chuẩn bị trước khi quay

1. Start Apache trong XAMPP.
2. Mở VS Code đúng thư mục `cse485-ms-03`.
3. Mở trình duyệt ở chế độ ẩn danh hoặc logout trước.
4. Bật OBS và camera khuôn mặt.
5. Phóng to trình duyệt và code để người xem đọc rõ.

### Trình tự quay đề xuất

#### Bước 1 — Chứng minh guard hoạt động

Nhập trực tiếp:

```text
http://localhost/cse485-ms-03/dashboard.php
```

Kết quả phải tự chuyển về `login.php`.

Nói:

> Dashboard có guard kiểm tra `$_SESSION['auth']`. Nếu chưa đăng nhập, hệ thống dùng `header('Location: login.php')` và `exit`.

#### Bước 2 — Thử sai mật khẩu

Nhập:

```text
Username: admin
Password: sai123
```

Bấm đăng nhập.

Kết quả: ở lại trang login và hiện thông báo lỗi.

Nói:

> Form dùng method POST nên mật khẩu không xuất hiện trên URL.

#### Bước 3 — Đăng nhập đúng

Nhập:

```text
Username: admin
Password: MiniShop@03
```

Kết quả: chuyển đến dashboard.

Phải quay rõ:

- Có 8 sản phẩm.
- Tổng giá trị kho là `41380000`.
- Cột thành tiền gọi method `lineTotal()`.
- Cột mức tồn gọi method `stockLevel()`.

#### Bước 4 — Tạo hai order Session

Ví dụ:

```text
Order 1: KB-01, số lượng 2
Order 2: MS-03, số lượng 5
```

Sau mỗi lần thêm, chỉ ra danh sách order phía dưới.

#### Bước 5 — Nhấn F5

Nhấn F5 hoặc tải lại trang.

Kết quả: hai order vẫn còn.

Nói:

> Order vẫn tồn tại vì được lưu trong `$_SESSION['orders']`. Biến PHP thông thường sẽ mất sau mỗi request.

#### Bước 6 — Zoom code OOP

Mở file:

```text
src/Product.php
```

Zoom vào:

```php
public function lineTotal(): int
{
    return $this->price * $this->qty;
}
```

Tiếp tục chỉ vào:

```php
public function stockLevel(): string
```

Nói:

> Ở Phiếu 02 em dùng `lineTotal($p)`. Sang OOP, dữ liệu và hành vi được gom trong class nên em gọi `$product->lineTotal()`.

#### Bước 7 — Zoom session_start và guard

Mở `dashboard.php`, chỉ rõ:

```php
session_start();

if (empty($_SESSION['auth'])) {
    header('Location: login.php');
    exit;
}
```

Mở `login.php`, chỉ rõ chỗ lưu:

```php
$_SESSION['auth'] = true;
$_SESSION['username'] = 'admin';
```

#### Bước 8 — Logout và kiểm tra lại guard

Bấm `Dang xuat`.

Sau đó nhập lại:

```text
http://localhost/cse485-ms-03/dashboard.php
```

Kết quả: bị chuyển về login.

Nói:

> Logout đã hủy Session nên dashboard không còn cho phép truy cập.

## Câu trả lời thuyết trình mẫu

### 1. Hướng cấu trúc và OOP khác nhau thế nào?

> Ở hướng cấu trúc, dữ liệu sản phẩm được lưu trong mảng và logic nằm trong các hàm rời, ví dụ `lineTotal($p)`. Ở OOP, dữ liệu và hành vi được gom trong class Product, vì vậy object có thể tự tính bằng `$p->lineTotal()`. Cách OOP giúp quy tắc nghiệp vụ nằm đúng trong đối tượng liên quan.

### 2. Class và object khác nhau thế nào?

> Class là bản thiết kế mô tả một Product có SKU, tên, danh mục, giá và số lượng. Object là một sản phẩm cụ thể được tạo từ class đó, ví dụ object Keychron K2 hoặc LG UltraFine.

### 3. Vì sao dùng Session?

> Mỗi request PHP chạy độc lập nên biến thường bị mất khi chuyển trang hoặc tải lại. Session giúp giữ trạng thái đăng nhập và danh sách order qua nhiều request cho đến khi người dùng logout hoặc Session hết hạn.

## Checklist trước khi nộp

```text
[ ] Có src/Category.php
[ ] Có src/Product.php
[ ] Có đủ 8 object Product
[ ] Login sai hiện lỗi
[ ] Login đúng vào dashboard
[ ] Chưa login không vào được dashboard
[ ] Tổng kho đúng 41380000
[ ] View dùng $product->lineTotal()
[ ] View dùng $product->stockLevel()
[ ] Thêm được 2 order
[ ] F5 order vẫn còn
[ ] Logout xong dashboard bị chặn
[ ] Video có camera khuôn mặt
[ ] Video zoom method lineTotal()
[ ] Video zoom session_start()
```
