<?php
/**
 * EXAMPLES - Cách sử dụng các helpers mới
 */

// ============================================================
// 1. SecurityHelper - Sanitize, Validate, Hash
// ============================================================

require_once 'helpers/SecurityHelper.php';

// Example: Validate email
$email = "user@example.com";
if (SecurityHelper::validateEmail($email)) {
    echo "Email hợp lệ";
} else {
    echo "Email không hợp lệ";
}

// Example: Hash password
$password = "MyPassword123!";
$hashedPassword = SecurityHelper::hashPassword($password);
// Lưu $hashedPassword vào database

// Example: Verify password
if (SecurityHelper::verifyPassword($password, $hashedPassword)) {
    echo "Password chính xác";
}

// Example: Sanitize input
$userInput = "<script>alert('xss')</script>";
$safe = SecurityHelper::sanitize($userInput);
// $safe = "&lt;script&gt;alert('xss')&lt;/script&gt;"

// Example: Validate username
if (SecurityHelper::validateUsername("john_doe")) {
    echo "Username hợp lệ";
}

// Example: Validate password strength
if (SecurityHelper::validatePassword("MyPassword123!")) {
    echo "Password đủ mạnh";
}

// ============================================================
// 2. Logger - Ghi log errors
// ============================================================

require_once 'helpers/Logger.php';

// Log error
Logger::error("Database connection failed", [
    'host' => 'localhost',
    'error' => 'Connection timeout'
]);

// Log warning
Logger::warning("Suspicious login attempt", [
    'ip' => $_SERVER['REMOTE_ADDR'],
    'email' => 'user@example.com'
]);

// Log info
Logger::info("User registered", [
    'username' => 'john_doe',
    'email' => 'john@example.com'
]);

// Log debug (chỉ nếu APP_DEBUG = true)
Logger::debug("User data", [
    'user_id' => 123,
    'name' => 'John Doe'
]);

// ============================================================
// 3. Database - Prepared Statements
// ============================================================

require_once 'config/database.php';

$conn = getConnection();

// Example: Prepared statement với bind_param
$email = "user@example.com";
$stmt = $conn->prepare("SELECT * FROM customer WHERE email = ?");
$stmt->bind_param("s", $email); // "s" = string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "User found: " . $user['name'];
}
$stmt->close();

// Example: Multiple parameters
$id = 1;
$name = "John";
$stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND name LIKE ?");
$stmt->bind_param("is", $id, $search_name); // "i" = integer, "s" = string
$search_name = "%$name%";
$stmt->execute();
$result = $stmt->get_result();

// ============================================================
// 4. CSRF Protection - Xác thực form
// ============================================================

require_once 'helpers/ProtectionHelper.php';

// Di form
?>

<form method="POST">
    <?php echo CSRFToken::field(); ?>
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!CSRFToken::verify($_POST['_csrf_token'] ?? '')) {
        die("CSRF token validation failed");
    }
    
    // Process form...
    $email = SecurityHelper::sanitize($_POST['email']);
    $password = $_POST['password'];
    
    // Login logic...
}
?>

<!-- ============================================================ -->
<!-- 5. Rate Limiting - Ngăn brute force -->
<!-- ============================================================ -->

<?php

// Check rate limit untuk login
$email = "user@example.com";
if (!RateLimit::isAllowed("login_$email", 5, 300)) {
    die("Quá nhiều lần cố gắng login. Vui lòng thử lại sau.");
}

// Kiểm tra lần thử còn lại
$remaining = RateLimit::getRemainingAttempts("login_$email", 5, 300);
echo "Lần thử còn lại: " . $remaining;

?>

<!-- ============================================================ -->
<!-- 6. Response Helper - JSON API -->
<!-- ============================================================ -->

<?php

// Success response
Response::success([
    'user_id' => 123,
    'name' => 'John Doe'
], "Login successful");

// Error response
Response::error("Invalid credentials", 401);

// Redirect
Response::redirect("index.php?action=home");

?>

<!-- ============================================================ -->
<!-- COMPLETE LOGIN EXAMPLE -->
<!-- ============================================================ -->

<?php

/**
 * Complete secure login example
 */

require_once 'config/database.php';
require_once 'helpers/SecurityHelper.php';
require_once 'helpers/Logger.php';
require_once 'helpers/ProtectionHelper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verify CSRF token
    if (!CSRFToken::verify($_POST['_csrf_token'] ?? '')) {
        Logger::warning("CSRF token validation failed", [
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);
        Response::error("Invalid request", 403);
    }
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // 2. Check rate limit
    if (!RateLimit::isAllowed("login_$email", 5, 300)) {
        Logger::warning("Login rate limit exceeded", [
            'email' => $email,
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);
        Response::error("Quá nhiều lần cố gắng. Vui lòng thử lại sau 5 phút", 429);
    }
    
    // 3. Validate input
    if (!SecurityHelper::validateEmail($email) || empty($password)) {
        Response::error("Email hoặc mật khẩu không hợp lệ");
    }
    
    // 4. Query database
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // 5. Verify password
        if (SecurityHelper::verifyPassword($password, $user['pass'])) {
            // 6. Check email verified
            if ($user['verify_status'] == 1) {
                // Login success
                $_SESSION['customer_id'] = $user['customer_id'];
                Logger::info("User login success", [
                    'customer_id' => $user['customer_id'],
                    'email' => $email
                ]);
                Response::success(null, "Login successful");
            } else {
                Response::error("Email chưa xác thực", 401);
            }
        } else {
            Response::error("Email hoặc mật khẩu không hợp lệ");
        }
    } else {
        Response::error("Email hoặc mật khẩu không hợp lệ");
    }
    
    $stmt->close();
    $conn->close();
}

?>

