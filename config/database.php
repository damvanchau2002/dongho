<?php
/**
 * Database Configuration - Tập trung quản lý credentials
 * BẢO MẬT: Trong production, sử dụng environment variables thay vì hardcode
 */

// Database Configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME_MAIN', getenv('DB_NAME_MAIN') ?: 'leopard_store');
define('DB_NAME_SECONDARY', getenv('DB_NAME_SECONDARY') ?: 'db_bookstore');

// Momo Payment Gateway (sandbox)
// BẢO MẬT: Thay bằng keys thật + dùng env vars trong production
define('MOMO_PARTNER_CODE',  getenv('MOMO_PARTNER_CODE')  ?: 'MOMOBKUN20180529');
define('MOMO_ACCESS_KEY',   getenv('MOMO_ACCESS_KEY')    ?: 'klm05TvNBzhg7h7j');
define('MOMO_SECRET_KEY',   getenv('MOMO_SECRET_KEY')    ?: 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
define('MOMO_ENDPOINT',     getenv('MOMO_ENDPOINT')      ?: 'https://test-payment.momo.vn/v2/gateway/api/create');
define('MOMO_MIN_AMOUNT',   (int) (getenv('MOMO_MIN_AMOUNT') ?: 10000));
define('MOMO_MAX_AMOUNT',   (int) (getenv('MOMO_MAX_AMOUNT') ?: 50000000));

// Application Settings
define('APP_NAME', 'ChronoLux Watch Store');
define('APP_URL', 'http://localhost/Website');
define('APP_DEBUG', true); // Thay false trong production

// Session Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('SESSION_NAME', 'leopard_store_session');

// Password Configuration
define('PASSWORD_MIN_LENGTH', 6);
define('PASSWORD_HASH_COST', 12); // Bcrypt cost factor

// Email Configuration - Gmail SMTP
// MAIL_FROM phải là địa chỉ Gmail dùng để gửi (tài khoản tạo App Password)
define('MAIL_FROM',         getenv('MAIL_FROM')         ?: 'damvanchau2002@gmail.com');
define('MAIL_FROM_NAME',    getenv('MAIL_FROM_NAME')    ?: 'ChronoLux Watch Store');
define('MAIL_APP_PASSWORD', getenv('MAIL_APP_PASSWORD') ?: 'hjbuvmsgikkituiw');

// ==========================================
// 4. API & BÊN THỨ BA (THÊM MỚI)
// ==========================================

// Google Gemini API Key cho Chatbot AI tư vấn
// Lấy key tại: https://aistudio.google.com/app/apikey
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: 'AIzaSyBqE6ynvArmj9Mwq98Z0dNV0JGDsyRWOJc');

/**
 * Establish database connection
 */
function getConnection() {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME_MAIN);
        
        if ($conn->connect_error) {
            error_log("Database connection failed: " . $conn->connect_error);
            die("Unable to connect to database.");
        }
        
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

/**
 * Disconnect database
 */
function closeConnection($conn = null) {
    if ($conn === null) {
        $conn = getConnection();
    }
    $conn->close();
}

?>
