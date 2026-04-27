<?php
/**
 * Installation & Health Check Script
 * Kiểm tra xem tất cả files đã được cài đặt đúng chưa
 * 
 * Cách dùng:
 * 1. Upload script này vào root folder
 * 2. Truy cập: http://localhost/Website/health_check.php
 * 3. Xem kết quả
 */

class HealthChecker {
    private $checks = [];
    private $allPassed = true;
    
    public function run() {
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Leopard Store - Health Check</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
                .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
                .check { margin: 15px 0; padding: 10px; border-left: 4px solid #ddd; }
                .check.pass { border-left-color: #28a745; background: #d4edda; }
                .check.fail { border-left-color: #dc3545; background: #f8d7da; }
                .check.warning { border-left-color: #ffc107; background: #fff3cd; }
                .status { font-weight: bold; }
                .pass .status { color: #28a745; }
                .fail .status { color: #dc3545; }
                .warning .status { color: #ffc107; }
                code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; }
                .summary { margin-top: 20px; padding: 15px; border-radius: 5px; font-size: 18px; }
                .summary.pass { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
                .summary.fail { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>🏥 Leopard Store - Health Check</h1>\n";
        
        // Check PHP version
        $this->checkPHPVersion();
        
        // Check required files
        $this->checkRequiredFiles();
        
        // Check directories
        $this->checkDirectories();
        
        // Check database connection
        $this->checkDatabase();
        
        // Check permissions
        $this->checkPermissions();
        
        // Display summary
        $this->displaySummary();
        
        echo "  </div>
            </body>
        </html>";
    }
    
    private function checkPHPVersion() {
        $required = '7.0.0';
        $current = phpversion();
        $passed = version_compare($current, $required, '>=');
        
        if ($passed) {
            $this->addCheck('PHP Version', "PHP $current ✓", true);
        } else {
            $this->addCheck('PHP Version', "PHP $current (Required: $required) ✗", false);
        }
    }
    
    private function checkRequiredFiles() {
        $requiredFiles = [
            'helpers/SecurityHelper.php',
            'helpers/Logger.php',
            'helpers/ProtectionHelper.php',
            'config/database.php',
            'models/BaseModel.php',
            'app/core/App.php',
            'app/core/Router.php',
            'app/core/Controller.php',
            'app/core/Database.php',
            'app/controllers/HomeController.php',
            'app/controllers/ProductController.php',
            'app/controllers/AuthController.php',
            'app/controllers/CartController.php',
            'app/controllers/CheckoutController.php',
            'app/controllers/AdminController.php',
            'app/models/StoreModel.php',
            'public/index.php',
            '.htaccess',
            'SECURITY_UPGRADE.md',
            'CHANGELOG.md',
            'EXAMPLES_USAGE.md',
        ];
        
        foreach ($requiredFiles as $file) {
            $path = dirname(__FILE__) . '/' . $file;
            $exists = file_exists($path);
            
            if ($exists) {
                $this->addCheck("File: $file", "Found ✓", true);
            } else {
                $this->addCheck("File: $file", "Not found ✗", false);
            }
        }
    }
    
    private function checkDirectories() {
        $directories = [
            'helpers',
            'config',
            'logs',
        ];
        
        foreach ($directories as $dir) {
            $path = dirname(__FILE__) . '/' . $dir;
            $exists = is_dir($path);
            
            if (!$exists) {
                @mkdir($path, 0755, true);
            }
            
            $readable = is_readable($path);
            $writable = is_writable($path);
            
            if ($readable && $writable) {
                $this->addCheck("Directory: $dir", "OK (r/w) ✓", true);
            } else if ($readable) {
                $this->addCheck("Directory: $dir", "Readable only (w/) ⚠", true, true);
            } else {
                $this->addCheck("Directory: $dir", "Not accessible ✗", false);
            }
        }
    }
    
    private function checkDatabase() {
        try {
            // Try to include config
            $configPath = dirname(__FILE__) . '/config/database.php';
            if (!file_exists($configPath)) {
                $this->addCheck('Database Config', 'Config file not found', false);
                return;
            }
            
            require_once $configPath;
            
            // Try connection
            $conn = new mysqli('localhost', 'root', '', 'leopard_store');
            if ($conn->connect_error) {
                $this->addCheck('Database Connection', 'Connection failed: ' . $conn->connect_error, false);
            } else {
                $this->addCheck('Database Connection', 'Connected to leopard_store ✓', true);
                $conn->close();
            }
        } catch (Exception $e) {
            $this->addCheck('Database Connection', 'Error: ' . $e->getMessage(), false);
        }
    }
    
    private function checkPermissions() {
        // Check SecurityHelper can be included
        $secFile = dirname(__FILE__) . '/helpers/SecurityHelper.php';
        if (file_exists($secFile)) {
            try {
                ob_start();
                require_once $secFile;
                ob_end_clean();
                
                if (class_exists('SecurityHelper')) {
                    $this->addCheck('SecurityHelper Class', 'Loaded successfully ✓', true);
                } else {
                    $this->addCheck('SecurityHelper Class', 'Class not found ✗', false);
                }
            } catch (Exception $e) {
                $this->addCheck('SecurityHelper Class', 'Error loading: ' . $e->getMessage(), false);
            }
        }
        
        // Check Logger can be included
        $logFile = dirname(__FILE__) . '/helpers/Logger.php';
        if (file_exists($logFile)) {
            try {
                ob_start();
                require_once $logFile;
                ob_end_clean();
                
                if (class_exists('Logger')) {
                    $this->addCheck('Logger Class', 'Loaded successfully ✓', true);
                } else {
                    $this->addCheck('Logger Class', 'Class not found ✗', false);
                }
            } catch (Exception $e) {
                $this->addCheck('Logger Class', 'Error loading: ' . $e->getMessage(), false);
            }
        }
    }
    
    private function addCheck($name, $message, $passed, $warning = false) {
        $status = $warning ? 'warning' : ($passed ? 'pass' : 'fail');
        $symbol = $warning ? '⚠' : ($passed ? '✓' : '✗');
        
        echo "<div class='check $status'>";
        echo "<span class='status'>[$symbol] $name</span><br>";
        echo "<span>$message</span>";
        echo "</div>\n";
        
        if (!$passed && !$warning) {
            $this->allPassed = false;
        }
    }
    
    private function displaySummary() {
        if ($this->allPassed) {
            echo "<div class='summary pass'>";
            echo "✓ <strong>All checks passed!</strong> Your installation is ready.";
            echo "</div>";
            
            echo "<div style='margin-top: 20px; padding: 15px; background: #e8f4f8; border-radius: 5px;'>";
            echo "<h3>Next Steps:</h3>";
            echo "<ol>";
            echo "<li>Run <code>migrate_passwords.php</code> to hash existing passwords</li>";
            echo "<li>Test login with existing accounts</li>";
            echo "<li>Test signup with new account</li>";
            echo "<li>Check <code>logs/app-*.log</code> for any errors</li>";
            echo "<li>Delete <code>health_check.php</code> after verification</li>";
            echo "</ol>";
            echo "</div>";
        } else {
            echo "<div class='summary fail'>";
            echo "✗ <strong>Some checks failed!</strong> Please fix the issues above.";
            echo "</div>";
            
            echo "<div style='margin-top: 20px; padding: 15px; background: #ffe8e8; border-radius: 5px;'>";
            echo "<h3>Troubleshooting:</h3>";
            echo "<ul>";
            echo "<li>Make sure all helper files are in <code>helpers/</code> folder</li>";
            echo "<li>Make sure <code>logs/</code> folder exists and is writable</li>";
            echo "<li>Check your database connection in <code>config/database.php</code></li>";
            echo "<li>Ensure PHP version is 7.0 or higher</li>";
            echo "</ul>";
            echo "</div>";
        }
    }
}

// Run health check
$checker = new HealthChecker();
$checker->run();

?>
