# 🚀 Leopard Store - Nâng Cấp Bảo Mật & Cấu Trúc Code

## 📋 Tóm tắt cải tiến

### ✅ Những gì đã được cải thiện:

#### 1. **Bảo Mật SQL Injection** 🔒
- ❌ **Trước**: Sử dụng string concatenation (`$sql = "... WHERE id=$id"`)
- ✅ **Sau**: Prepared Statements với bind_param
```php
// ❌ Cũ - Nguy hiểm
$sql = "SELECT * FROM user WHERE id='$id'";

// ✅ Mới - An toàn
$stmt = $connect->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
```

#### 2. **Password Security** 🔐
- ❌ **Trước**: Lưu password plaintext trong database
- ✅ **Sau**: Hash password với bcrypt (password_hash)
```php
// ❌ Cũ - Nguy hiểm
$sql = "INSERT INTO user (pass) VALUES ('$password')";

// ✅ Mới - An toàn
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$stmt->bind_param("s", $hashedPassword);

// Verify password
if (password_verify($inputPassword, $dbPassword)) {
    // Login thành công
}
```

#### 3. **Input Validation & Sanitization** ✔️
- Validate email format
- Validate username (3-50 chars, alphanumeric + underscore)
- Validate password strength
- Sanitize tất cả inputs trước khi xử lý

#### 4. **Code Organization** 📁
- Tạo `helpers/SecurityHelper.php` - Tập trung các hàm bảo mật
- Tạo `helpers/Logger.php` - Logging errors & activities
- Tạo `config/database.php` - Quản lý database config tập trung
- Refactor models & controllers để sử dụng helpers mới

#### 5. **Error Handling** 📝
- Thêm Logger class để ghi lại errors
- Tạo logs/ folder để lưu error logs
- Implement try-catch blocks

---

## 🛠️ Những file mới được tạo:

### 1. `helpers/SecurityHelper.php`
Các hàm bảo mật:
- `hashPassword($password)` - Hash password với bcrypt
- `verifyPassword($password, $hash)` - Verify password
- `sanitize($input)` - Sanitize input
- `validateEmail($email)` - Validate email format
- `validateUsername($username)` - Validate username
- `validatePassword($password)` - Validate password strength
- `validateInteger($value)` - Validate integer
- `generateToken($length)` - Generate random token
- `escapeLike($input)` - Escape SQL LIKE wildcards
- `validatePhoneVN($phone)` - Validate phone Vietnam
- `validateURL($url)` - Validate URL

### 2. `helpers/Logger.php`
Logging system:
- `Logger::error($message, $context)` - Log error
- `Logger::warning($message, $context)` - Log warning
- `Logger::info($message, $context)` - Log info
- `Logger::debug($message, $context)` - Log debug

### 3. `config/database.php`
Database configuration:
- Centralized database credentials
- Connection helper functions
- Environment variable support

---

## ⚡ Những file đã được cập nhật:

### 1. `models/BaseModel.php`
- Convert tất cả queries sang Prepared Statements
- Thêm validation cho integer inputs
- Sanitize search keywords

### 2. `controllers/HomeController.php`
- Thêm SecurityHelper require
- Update login logic để verify password hash
- Thêm input validation cho signup
- Thêm error handling

### 3. `app/controller/login_controller.php` (NEW)
- Prepared statements
- Password verification với hash
- Email validation
- Error handling

### 4. `app/controller/signup_controller.php` (NEW)
- Input validation
- Password hashing
- Email checking với prepared statement
- Detailed error messages

### 5. `app/model/signup_model.php` (UPDATED)
- Use SecurityHelper for validation
- Prepared statements
- Better error handling

---

## 📖 Hướng dẫn migration:

### Step 1: Migrate password hiện tại từ plaintext sang hash

**QUAN TRỌNG**: Chỉ run một lần!

1. Đảm bảo file `migrate_passwords.php` ở root folder
2. Truy cập: `http://localhost/Website/migrate_passwords.php`
3. Script sẽ automatically hash tất cả passwords trong database
4. Xóa file `migrate_passwords.php` sau khi hoàn thành
5. Xóa file `MIGRATION_COMPLETED` nếu muốn run lại

```
Before: matkhau_nd = "123456"
After:  matkhau_nd = "$2y$12$..." (bcrypt hash)
```

### Step 2: Test login/signup

Sau khi migration:
1. Thử login với old account (password plaintext sẽ được hash)
2. Thử signup account mới (password sẽ được hash)
3. Kiểm tra logs tại `logs/app-YYYY-MM-DD.log`

---

## 🧪 Testing các cải tiến:

### Test 1: SQL Injection Protection
```php
// Cố gắng SQL injection - sẽ FAIL vì prepared statements
GET /index.php?action=chitietsanpham&idloai=1' OR '1'='1
// Kết quả: Prepared statement sẽ treat nó như string, không execute query
```

### Test 2: Password Hashing
```php
// Login với account cũ
Email: user@example.com
Password: 123456
// Kết quả: Password sẽ được verify lại hash từ database

// Inspect database
SELECT * FROM customer WHERE email='user@example.com';
// matkhau_nd = $2y$12$... (hash, không phải plaintext)
```

### Test 3: Input Validation
```php
// Signup với invalid data
Name: "ab" (< 3 chars) -> Error: "Tên phải từ 3-50 ký tự"
Email: "invalid" -> Error: "Email không hợp lệ"
Password: "123" (< 6 chars) -> Error: "Mật khẩu phải từ 6 ký tự trở lên"
```

---

## 📊 Database Schema Updates:

### Hiện tại - Plaintext:
```sql
ALTER TABLE quanlynguoidung MODIFY matkhau_nd VARCHAR(255);
ALTER TABLE customer MODIFY pass VARCHAR(255);
```

### Sau migration - Hashed:
```
matkhau_nd VARCHAR(255) -- Now stores bcrypt hash
pass VARCHAR(255)       -- Now stores bcrypt hash
```

Password hash format:
- `$2y$` = bcrypt algorithm
- `12` = cost factor (tùy chỉnh tại SecurityHelper::hashPassword)
- Tiếp theo = salt + hash (61 ký tự tổng cộng)

---

## 🎯 Việc tiếp theo - Phase 2 (2-3 tuần):

### Priority 1: Fix SQL Injection trong các controller còn lại
- [ ] Refactor `app/controller/addCart_ctl.php`
- [ ] Refactor `app/controller/checkout_ctl.php`
- [ ] Refactor `app/controller/comment_ctl.php`
- [ ] Refactor tất cả CRUD controllers

### Priority 2: Refactor app/model
- [ ] Update tất cả models trong `app/model/` sang prepared statements
- [ ] Consolidate duplicated models

### Priority 3: Thêm CSRF Protection
- [ ] Implement CSRF tokens
- [ ] Validate tokens trên POST requests

### Priority 4: Thêm Rate Limiting
- [ ] Limit login attempts
- [ ] Prevent brute force attacks

### Priority 5: Thêm API Authentication
- [ ] JWT tokens cho API endpoints
- [ ] Session management improvements

---

## 📝 Environment Variables (Optional - Production):

Tạo `.env` file để không hardcode credentials:

```ini
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME_MAIN=leopard_store
DB_NAME_SECONDARY=db_bookstore

APP_DEBUG=false
APP_URL=https://leopardstore.com

MAIL_FROM=noreply@leopardstore.com
```

Sau đó cập nhật `config/database.php` để dùng `.env`:

```php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__));
$dotenv->load();
```

---

## ✅ Checklist Implementation:

- [x] Create SecurityHelper.php
- [x] Create Logger.php
- [x] Update BaseModel.php - Prepared Statements
- [x] Update HomeController.php - Password hashing
- [x] Update login_controller.php - Validation
- [x] Update signup_controller.php - Validation
- [x] Update signup_model.php - Prepared Statements
- [x] Create migrate_passwords.php script
- [x] Create config/database.php
- [ ] Update remaining controllers
- [ ] Update remaining models
- [ ] Add CSRF protection
- [ ] Add rate limiting
- [ ] Add JWT authentication
- [ ] Deploy to production

---

## 🆘 Troubleshooting:

### Error: "Call to undefined function SecurityHelper"
**Solution**: Đảm bảo có `require_once 'helpers/SecurityHelper.php'` ở đầu file

### Error: "Connection failed"
**Solution**: Check database credentials tại `config/database.php`

### Login không hoạt động sau migration
**Solution**: Chạy `migrate_passwords.php` để hash tất cả passwords

### Cannot create logs directory
**Solution**: Tạo thư mục `logs/` manually: `mkdir logs && chmod 755 logs`

---

## 📚 Tài liệu tham khảo:

- [PHP password_hash()](https://www.php.net/manual/en/function.password-hash.php)
- [MySQLi Prepared Statements](https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)
- [OWASP SQL Injection](https://owasp.org/www-community/attacks/SQL_Injection)
- [OWASP Password Storage Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html)

---

## 💬 Support:

Nếu có vấn đề gì, check:
1. Logs tại `logs/app-YYYY-MM-DD.log`
2. Database connection
3. File permissions
4. PHP version (>=7.0 để dùng password_hash)

---

**Last Updated**: 2026-04-27
**Version**: 1.0 (Security Upgrade Phase 1)
