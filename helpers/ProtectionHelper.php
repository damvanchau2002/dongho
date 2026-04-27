<?php
/**
 * CSRF Protection Helper
 */

class CSRFToken {
    
    const SESSION_KEY = '_csrf_token';
    const TOKEN_LENGTH = 32;
    
    /**
     * Generate CSRF token
     */
    public static function generate() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(self::TOKEN_LENGTH / 2));
        }
        
        return $_SESSION[self::SESSION_KEY];
    }
    
    /**
     * Get token
     */
    public static function getToken() {
        return self::generate();
    }
    
    /**
     * Verify token
     */
    public static function verify($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION[self::SESSION_KEY]) && 
               hash_equals($_SESSION[self::SESSION_KEY], $token);
    }
    
    /**
     * Get HTML input field
     */
    public static function field() {
        $token = self::getToken();
        return '<input type="hidden" name="' . self::SESSION_KEY . '" value="' . htmlspecialchars($token) . '">';
    }
}

/**
 * Rate Limiting Helper
 */
class RateLimit {
    
    /**
     * Check if action is rate limited
     * @param string $action - Action identifier (e.g., "login_user@example.com")
     * @param int $maxAttempts - Maximum attempts allowed
     * @param int $windowSeconds - Time window in seconds
     * @return bool - true if allowed, false if rate limited
     */
    public static function isAllowed($action, $maxAttempts = 5, $windowSeconds = 300) {
        $key = 'rate_limit_' . md5($action);
        $sessionKey = '_rate_limits';
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION[$sessionKey])) {
            $_SESSION[$sessionKey] = [];
        }
        
        $now = time();
        
        // Cleanup old entries
        if (isset($_SESSION[$sessionKey][$key])) {
            $_SESSION[$sessionKey][$key] = array_filter(
                $_SESSION[$sessionKey][$key],
                function($timestamp) use ($now, $windowSeconds) {
                    return $timestamp > ($now - $windowSeconds);
                }
            );
        }
        
        // Count recent attempts
        $attempts = isset($_SESSION[$sessionKey][$key]) ? count($_SESSION[$sessionKey][$key]) : 0;
        
        if ($attempts >= $maxAttempts) {
            return false;
        }
        
        // Record this attempt
        if (!isset($_SESSION[$sessionKey][$key])) {
            $_SESSION[$sessionKey][$key] = [];
        }
        $_SESSION[$sessionKey][$key][] = $now;
        
        return true;
    }
    
    /**
     * Get remaining attempts
     */
    public static function getRemainingAttempts($action, $maxAttempts = 5, $windowSeconds = 300) {
        $key = 'rate_limit_' . md5($action);
        $sessionKey = '_rate_limits';
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION[$sessionKey][$key])) {
            return $maxAttempts;
        }
        
        $now = time();
        $attempts = array_filter(
            $_SESSION[$sessionKey][$key],
            function($timestamp) use ($now, $windowSeconds) {
                return $timestamp > ($now - $windowSeconds);
            }
        );
        
        return max(0, $maxAttempts - count($attempts));
    }
}

/**
 * Response Helper
 */
class Response {
    
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_ERROR = 500;
    
    /**
     * Send JSON response
     */
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Send success response
     */
    public static function success($data = null, $message = "Success") {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], self::HTTP_OK);
    }
    
    /**
     * Send error response
     */
    public static function error($message = "Error", $statusCode = 400, $errors = null) {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
    
    /**
     * Send redirect
     */
    public static function redirect($url, $statusCode = 302) {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
}

?>
