# ChronoLux - Website Kinh Doanh Đồng Hồ Cao Cấp

ChronoLux là một nền tảng thương mại điện tử chuyên cung cấp các dòng đồng hồ cao cấp từ các thương hiệu nổi tiếng thế giới. Dự án được xây dựng trên nền tảng PHP theo mô hình MVC (Model-View-Controller) tùy chỉnh, mang lại sự linh hoạt và khả năng mở rộng cao.

## 🚀 Công Nghệ Sử Dụng

- **Ngôn ngữ:** PHP (phiên bản >= 7.4)
- **Cơ sở dữ liệu:** MySQL
- **Mô hình kiến trúc:** MVC (Model-View-Controller)
- **Frontend:** HTML5, CSS3, JavaScript (JQuery)
- **Tích hợp:**
    - Thanh toán trực tuyến: Momo
    - Trí tuệ nhân tạo: Google Gemini AI (Chatbot)
    - Giao tiếp: Live Chat trực tiếp giữa người dùng và Admin

## ✨ Tính Năng Chính

### 👤 Dành cho Khách hàng
- **Trang chủ:** Banner động, danh sách sản phẩm nổi bật, sản phẩm mới nhất (có phân trang).
- **Tìm kiếm & Lọc:** Tìm kiếm sản phẩm theo tên, phân loại theo danh mục.
- **Chi tiết sản phẩm:** Xem thông tin chi tiết, đánh giá và sản phẩm gợi ý.
- **Giỏ hàng & Thanh toán:** Thêm sản phẩm, cập nhật số lượng, thanh toán qua Momo hoặc COD.
- **Tài khoản:** Đăng ký/Đăng nhập (hỗ trợ Google Login), quản lý thông tin cá nhân, lịch sử đơn hàng.
- **Hỗ trợ:** Chatbot AI (Gemini) và Live Chat với quản trị viên.

### 🛡️ Dành cho Quản trị viên (Admin)
- **Tổng quan:** Thống kê doanh thu, đơn hàng, người dùng.
- **Quản lý Sản phẩm/Danh mục:** Thêm, sửa, xóa sản phẩm và các loại sản phẩm.
- **Quản lý Đơn hàng:** Cập nhật trạng thái đơn hàng, xem chi tiết giao dịch.
- **Quản lý Người dùng:** Phân quyền (User/Admin/Shipper), quản lý thông tin khách hàng.
- **Tiện ích:** Quản lý Banner, Mã giảm giá, Tin nhắn Live Chat.

### 🚚 Dành cho Người giao hàng (Shipper)
- Xem danh sách đơn hàng cần giao.
- Tiếp nhận đơn hàng và cập nhật trạng thái vận chuyển.
- Theo dõi vị trí (giả lập).

## 📁 Cấu Trúc Thư Mục

```text
/Website
├── app/
│   ├── controllers/  # Xử lý logic nghiệp vụ
│   ├── core/         # Các thành phần cốt lõi (App, Router, Database)
│   ├── models/       # Xử lý dữ liệu (StoreModel, BannerModel, ...)
├── config/           # Cấu hình hệ thống (Database, API Keys)
├── helpers/          # Các lớp hỗ trợ (Mail, Security, Gemini)
├── public/           # Điểm vào ứng dụng (index.php, css, js, images)
├── views/            # Giao diện người dùng (PHP/HTML)
├── .htaccess         # Cấu hình URL thân thiện
└── index.php         # Entry point chính
```

## 🛠️ Hướng Dẫn Cài Đặt

1. **Yêu cầu:** Đã cài đặt Laragon, XAMPP hoặc môi trường PHP/MySQL tương đương.
2. **Clone dự án:** Tải mã nguồn về thư mục `www` (Laragon) hoặc `htdocs` (XAMPP).
3. **Cơ sở dữ liệu:**
    - Tạo database mới tên là `leopard_store`.
    - Import file `leopard_store.sql` hoặc `db_bookstore (5).sql` vào database.
4. **Cấu hình:**
    - Chỉnh sửa thông tin kết nối trong `config/database.php`.
5. **Truy cập:** Mở trình duyệt và truy cập `http://localhost/Website`.

## 📝 Giấy Phép

Dự án này được phát hành dưới giấy phép [MIT](LICENSE).

---
*Phát triển bởi ChronoLux Team.*
