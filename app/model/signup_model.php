<?php
require_once dirname(__FILE__) . '/../../helpers/SecurityHelper.php';

/**
 * Validate signup data
 */
function checkValid($name, $email, $pass, $cpass, $conn){
    $errors = [];
    
    // Validate name
    if (!SecurityHelper::validateUsername($name)) {
        $errors[] = "Tên phải từ 3-50 ký tự.";
    }
    
    // Validate email
    if (!SecurityHelper::validateEmail($email)) {
        $errors[] = "Email không hợp lệ.";
    }
    
    // Validate password match
    if ($pass !== $cpass) {
        $errors[] = "Các mật khẩu đã nhập không khớp.";
    }
    
    // Validate password strength
    if (!SecurityHelper::validatePasswordSimple($pass)) {
        $errors[] = "Mật khẩu phải từ 6 ký tự trở lên.";
    }
    
    // Check email exists - sử dụng prepared statement
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT customer_id FROM customer WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $errors[] = "Email đã được đăng kí.";
            }
            $stmt->close();
        }
    }
    
    // Return error message nếu có
    return !empty($errors) ? implode("\n", $errors) : "";
}

?>
