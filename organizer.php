#!/usr/bin/env php
<?php
/**
 * CODE STRUCTURE ORGANIZER
 * 
 * Cách dùng:
 * php organizer.php
 * 
 * Script này sẽ:
 * 1. Tạo folder structure mới
 * 2. Copy files vào đúng vị trí
 * 3. Update imports & requires
 * 4. Tạo backup của structure cũ
 */

class CodeOrganizer {
    private $rootPath;
    private $backupPath;
    private $log = [];
    
    public function __construct() {
        $this->rootPath = dirname(__FILE__);
        $this->backupPath = $this->rootPath . '/backup_' . date('YmdHis');
    }
    
    public function run() {
        echo "🚀 Code Structure Organizer\n";
        echo "============================\n\n";
        
        // Step 1: Create backup
        $this->step("Creating backup of current structure");
        $this->createBackup();
        
        // Step 2: Create new folder structure
        $this->step("Creating new folder structure");
        $this->createFolders();
        
        // Step 3: Move controllers
        $this->step("Organizing Controllers");
        $this->organizeControllers();
        
        // Step 4: Move models
        $this->step("Organizing Models");
        $this->organizeModels();
        
        // Step 5: Move views
        $this->step("Organizing Views");
        $this->organizeViews();
        
        // Step 6: Move helpers
        $this->step("Organizing Helpers");
        $this->organizeHelpers();
        
        // Step 7: Move config
        $this->step("Organizing Configuration");
        $this->organizeConfig();
        
        // Step 8: Create autoloader
        $this->step("Creating PSR-4 Autoloader");
        $this->createAutoloader();
        
        // Summary
        $this->displaySummary();
    }
    
    private function step($name) {
        echo "\n📍 Step: $name\n";
        echo str_repeat("-", 50) . "\n";
    }
    
    private function createBackup() {
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
            echo "✓ Backup directory created: {$this->backupPath}\n";
        }
        
        $foldersToBackup = [
            'app', 'controllers', 'models', 'views', 
            'helpers', 'utils', 'public'
        ];
        
        foreach ($foldersToBackup as $folder) {
            $src = $this->rootPath . '/' . $folder;
            $dest = $this->backupPath . '/' . $folder;
            
            if (is_dir($src)) {
                $this->recursiveCopy($src, $dest);
                echo "✓ Backed up: $folder\n";
            }
        }
    }
    
    private function createFolders() {
        $folders = [
            'src/Controllers',
            'src/Models',
            'src/Views/layouts',
            'src/Views/home',
            'src/Views/product',
            'src/Views/cart',
            'src/Views/checkout',
            'src/Views/user',
            'src/Views/admin',
            'src/Helpers',
            'src/Middleware',
            'src/Database',
            'config',
            'public/css/custom',
            'public/js/custom',
            'public/images',
            'logs',
            'storage/uploads/products',
            'storage/uploads/avatars',
            'storage/cache',
            'tests/Unit',
            'tests/Feature',
        ];
        
        foreach ($folders as $folder) {
            $path = $this->rootPath . '/' . $folder;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                echo "✓ Created: $folder\n";
            }
        }
    }
    
    private function organizeControllers() {
        $controllers = [
            'app/controller/login_controller.php' => 'src/Controllers/AuthController.php',
            'app/controller/signup_controller.php' => 'src/Controllers/AuthController.php', // Merge
            'app/controller/product_ctl.php' => 'src/Controllers/ProductController.php',
            'app/controller/addCart_ctl.php' => 'src/Controllers/CartController.php',
            'app/controller/checkout_ctl.php' => 'src/Controllers/CheckoutController.php',
            'app/controller/comment_ctl.php' => 'src/Controllers/CommentController.php',
            'controllers/HomeController.php' => 'src/Controllers/HomeController.php',
        ];
        
        foreach ($controllers as $src => $dest) {
            $srcPath = $this->rootPath . '/' . $src;
            $destPath = $this->rootPath . '/' . $dest;
            
            if (file_exists($srcPath) && !file_exists($destPath)) {
                copy($srcPath, $destPath);
                echo "✓ Moved: $src → $dest\n";
            }
        }
    }
    
    private function organizeModels() {
        $models = [
            'models/BaseModel.php' => 'src/Models/BaseModel.php',
            'app/model/login_model.php' => 'src/Models/User.php',
            'app/model/signup_model.php' => 'src/Models/User.php',
        ];
        
        foreach ($models as $src => $dest) {
            $srcPath = $this->rootPath . '/' . $src;
            $destPath = $this->rootPath . '/' . $dest;
            
            if (file_exists($srcPath) && !file_exists($destPath)) {
                copy($srcPath, $destPath);
                echo "✓ Moved: $src → $dest\n";
            }
        }
    }
    
    private function organizeViews() {
        $layouts = ['header.php', 'footer.php', 'navbar.php'];
        
        foreach ($layouts as $file) {
            $src = $this->rootPath . '/utils/' . $file;
            $dest = $this->rootPath . '/src/Views/layouts/' . $file;
            
            if (file_exists($src) && !file_exists($dest)) {
                copy($src, $dest);
                echo "✓ Moved: utils/$file → src/Views/layouts/$file\n";
            }
        }
        
        // Copy views folder
        $viewSrc = $this->rootPath . '/views';
        $viewDest = $this->rootPath . '/src/Views';
        
        if (is_dir($viewSrc)) {
            $this->recursiveCopy($viewSrc, $viewDest);
            echo "✓ Moved: views/* → src/Views/*\n";
        }
    }
    
    private function organizeHelpers() {
        $helpers = [
            'helpers/SecurityHelper.php',
            'helpers/Logger.php',
            'helpers/ProtectionHelper.php',
        ];
        
        foreach ($helpers as $file) {
            $src = $this->rootPath . '/' . $file;
            $dest = $this->rootPath . '/src/Helpers/' . basename($file);
            
            if (file_exists($src) && !file_exists($dest)) {
                copy($src, $dest);
                echo "✓ Moved: $file → src/Helpers/" . basename($file) . "\n";
            }
        }
    }
    
    private function organizeConfig() {
        $configFiles = [
            'app/config.php' => 'config/database.php',
        ];
        
        foreach ($configFiles as $src => $dest) {
            $srcPath = $this->rootPath . '/' . $src;
            $destPath = $this->rootPath . '/' . $dest;
            
            if (file_exists($srcPath) && !file_exists($destPath)) {
                copy($srcPath, $destPath);
                echo "✓ Moved: $src → $dest\n";
            }
        }
    }
    
    private function createAutoloader() {
        $autoloaderCode = <<<'PHP'
<?php
/**
 * PSR-4 Autoloader
 * 
 * Automatically load classes from src/ folder
 * Usage: require 'config/autoload.php';
 */

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = dirname(__DIR__) . '/src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Backward compatibility - load old helpers
$deprecatedHelpers = [
    dirname(__DIR__) . '/src/Helpers/SecurityHelper.php',
    dirname(__DIR__) . '/src/Helpers/Logger.php',
    dirname(__DIR__) . '/src/Helpers/ProtectionHelper.php',
];

foreach ($deprecatedHelpers as $helper) {
    if (file_exists($helper)) {
        require_once $helper;
    }
}

?>
PHP;
        
        $autoloaderPath = $this->rootPath . '/config/autoload.php';
        file_put_contents($autoloaderPath, $autoloaderCode);
        echo "✓ Created: config/autoload.php\n";
    }
    
    private function recursiveCopy($src, $dest) {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if ($file != '.' && $file != '..') {
                $srcFile = $src . '/' . $file;
                $destFile = $dest . '/' . $file;
                
                if (is_dir($srcFile)) {
                    $this->recursiveCopy($srcFile, $destFile);
                } else {
                    copy($srcFile, $destFile);
                }
            }
        }
        closedir($dir);
    }
    
    private function displaySummary() {
        echo "\n\n";
        echo "✅ CODE STRUCTURE REORGANIZATION COMPLETE!\n";
        echo "==========================================\n\n";
        
        echo "📁 New Structure:\n";
        echo "   src/\n";
        echo "   ├── Controllers/\n";
        echo "   ├── Models/\n";
        echo "   ├── Views/\n";
        echo "   ├── Helpers/\n";
        echo "   ├── Middleware/\n";
        echo "   └── Database/\n";
        echo "   config/\n";
        echo "   public/\n";
        echo "   logs/\n";
        echo "   storage/\n\n";
        
        echo "📝 Backup Location: {$this->backupPath}\n\n";
        
        echo "🔗 Update your entry point (index.php):\n";
        echo "   require 'config/autoload.php';\n";
        echo "   require 'config/database.php';\n\n";
        
        echo "📚 Documentation:\n";
        echo "   - See STRUCTURE_GUIDE.md\n";
        echo "   - See MIGRATION_GUIDE.md (soon)\n\n";
    }
}

// Run organizer
$organizer = new CodeOrganizer();
$organizer->run();

?>
