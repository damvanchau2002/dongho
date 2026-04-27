<?php
/**
 * Security Helper - Xử lý bảo mật, validation và sanitization
 */

class SecurityHelper {
    
    /**
     * Hash password an toàn với bcrypt
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Verify password với hash
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Sanitize input string
     */
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate email format
     */
    public static function validateEmail($email) {
        $email = self::sanitize($email);
        if (empty($email) || strlen($email) > 255) {
            return false;
        }
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate username (3-50 characters, alphanumeric + underscore)
     */
    public static function validateUsername($username) {
        $username = self::sanitize($username);
        if (empty($username) || strlen($username) < 3 || strlen($username) > 50) {
            return false;
        }
        return preg_match('/^[a-zA-Z0-9_]+$/', $username) === 1;
    }
    
    /**
     * Validate password (min 8 chars, must have letter, number, special char)
     */
    public static function validatePassword($password) {
        if (empty($password) || strlen($password) < 8 || strlen($password) > 255) {
            return false;
        }
        // At least 1 uppercase, 1 lowercase, 1 number, 1 special char
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password) === 1;
    }
    
    /**
     * Validate password strength (optional - less strict)
     */
    public static function validatePasswordSimple($password) {
        return !empty($password) && strlen($password) >= 6 && strlen($password) <= 255;
    }
    
    /**
     * Validate integer
     */
    public static function validateInteger($value) {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
    /**
     * Generate secure random token
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Check if string is JSON
     */
    public static function isJSON($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * CSRF: Tạo token
     */
    public static function csrfToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = self::generateToken(64);
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * CSRF: Verify token
     */
    public static function verifyCsrf($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Sanitize SQL wildcard characters
     */
    public static function escapeLike($input) {
        return str_replace(['%', '_'], ['\%', '\_'], $input);
    }

    /**
     * Validate phone number (Vietnam format)
     */
    public static function validatePhoneVN($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return preg_match('/^(0|84)[0-9]{9}$/', $phone) === 1;
    }
    
    /**
     * Validate URL
     */
    public static function validateURL($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
?>
