<?php
/**
 * Logger - Xử lý logging errors và activities
 */

class Logger {
    
    private static $logDir = null;
    
    public static function init($logDirectory = null) {
        if ($logDirectory === null) {
            $logDirectory = dirname(__FILE__) . '/../logs';
        }
        
        self::$logDir = $logDirectory;
        
        // Tạo thư mục logs nếu chưa tồn tại
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }
    }
    
    /**
     * Log error
     */
    public static function error($message, $context = []) {
        self::log('ERROR', $message, $context);
    }
    
    /**
     * Log warning
     */
    public static function warning($message, $context = []) {
        self::log('WARNING', $message, $context);
    }
    
    /**
     * Log info
     */
    public static function info($message, $context = []) {
        self::log('INFO', $message, $context);
    }
    
    /**
     * Log debug
     */
    public static function debug($message, $context = []) {
        if (defined('APP_DEBUG') && APP_DEBUG) {
            self::log('DEBUG', $message, $context);
        }
    }
    
    /**
     * Write log
     */
    private static function log($level, $message, $context = []) {
        if (self::$logDir === null) {
            self::init();
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logFile = self::$logDir . '/app-' . date('Y-m-d') . '.log';
        
        // Format log entry
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context) : '';
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextStr}\n";
        
        // Write to file
        file_put_contents($logFile, $logEntry, FILE_APPEND);
        
        // Rotate log files if too large (> 10MB)
        if (filesize($logFile) > 10 * 1024 * 1024) {
            $backupFile = $logFile . '.' . date('YmdHis');
            rename($logFile, $backupFile);
        }
    }
}

// Initialize logger
Logger::init();

?>
