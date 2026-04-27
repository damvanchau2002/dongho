# 📚 HƯỚNG DẪN REORGANIZE CODE STRUCTURE

## 🎯 MỤC ĐÍCH

Chuyển từ cấu trúc rải rác sang cấu trúc MVC chuẩn, professional.

---

## ⚡ QUICK START (5 phút)

### Step 1: Chạy organizer script
```bash
cd c:\laragon\www\Website
php organizer.php
```

**Script sẽ:**
✓ Tạo folder structure mới
✓ Copy files vào đúng vị trí
✓ Tạo autoloader
✓ Backup cấu trúc cũ

### Step 2: Update index.php
```php
<?php
define('APP_ROOT', dirname(__FILE__));

// Load autoloader
require APP_ROOT . '/config/autoload.php';
require APP_ROOT . '/config/database.php';

// Use new structure
use App\Controllers\HomeController;

$controller = new HomeController();
$controller->handle($_GET['action'] ?? 'home');
?>
```

### Step 3: Test website
- Truy cập: http://localhost/Website
- Verify hoạt động bình thường
- Check logs có lỗi không

### Step 4: Cleanup (optional)
```bash
# Xóa old folders khi chắc chắn
rm -r app/controller
rm -r app/model
rm -r app/views
rm -r controllers
rm -r models
rm -r views
rm -r utils
```

---

## 📁 MIGRATION DETAILS

### Controllers

#### BEFORE:
```
app/controller/login_controller.php
app/controller/signup_controller.php
controllers/HomeController.php
```

#### AFTER:
```
src/Controllers/AuthController.php
src/Controllers/HomeController.php
```

**Update require:**
```php
// OLD
require 'app/controller/login_controller.php';

// NEW
require 'src/Controllers/AuthController.php';
// OR use autoloader
use App\Controllers\AuthController;
```

---

### Models

#### BEFORE:
```
models/BaseModel.php
app/model/login_model.php
app/model/signup_model.php
```

#### AFTER:
```
src/Models/BaseModel.php
src/Models/User.php (combined)
```

**Update require:**
```php
// OLD
require 'models/BaseModel.php';

// NEW
require 'src/Models/BaseModel.php';
// OR
use App\Models\BaseModel;
```

---

### Views

#### BEFORE:
```
views/home.php
views/product/detail.php
app/views/checkout.php
utils/header.php
utils/footer.php
```

#### AFTER:
```
src/Views/home/index.php
src/Views/product/detail.php
src/Views/checkout/index.php
src/Views/layouts/header.php
src/Views/layouts/footer.php
```

**Update include:**
```php
// OLD
include 'views/home.php';
include 'utils/header.php';

// NEW
include 'src/Views/home/index.php';
include 'src/Views/layouts/header.php';
// OR use constant
include VIEW_PATH . '/home/index.php';
```

---

### Helpers

#### BEFORE:
```
helpers/SecurityHelper.php
helpers/Logger.php
helpers/ProtectionHelper.php
```

#### AFTER:
```
src/Helpers/SecurityHelper.php
src/Helpers/Logger.php
src/Helpers/ProtectionHelper.php
```

**No code change needed** - Autoloader handles it!

---

### Configuration

#### BEFORE:
```
app/config.php
utils/connect_db.php
```

#### AFTER:
```
config/database.php
config/autoload.php
config/constants.php (NEW)
```

**Update require:**
```php
// OLD
require 'app/config.php';

// NEW
require 'config/database.php';
require 'config/autoload.php';
```

---

## 🔧 IMPLEMENTATION STEPS

### Phase 1: Preparation
- [ ] Backup current database
- [ ] Backup current code (organizer.php does this)
- [ ] Test website thoroughly

### Phase 2: Reorganization
- [ ] Run organizer.php script
- [ ] Verify all files moved correctly
- [ ] Update index.php with new structure
- [ ] Update all require/include statements
- [ ] Update autoloader includes

### Phase 3: Testing
- [ ] Test homepage works
- [ ] Test product page
- [ ] Test cart functionality
- [ ] Test login/signup
- [ ] Test checkout
- [ ] Check logs for errors

### Phase 4: Cleanup
- [ ] Delete old folder structures
- [ ] Delete organizer.php
- [ ] Final testing
- [ ] Deploy to production

---

## 📋 CHECKLIST

### Folder Creation
- [ ] src/Controllers
- [ ] src/Models
- [ ] src/Views (with subfolders)
- [ ] src/Helpers
- [ ] src/Middleware
- [ ] src/Database
- [ ] config
- [ ] logs
- [ ] storage

### File Migration
- [ ] Controllers moved
- [ ] Models moved
- [ ] Views moved & organized
- [ ] Helpers moved
- [ ] Config moved & consolidated

### Code Updates
- [ ] index.php updated
- [ ] Autoloader created
- [ ] require/include paths updated
- [ ] Namespaces added (optional)
- [ ] Imports added

### Testing
- [ ] Homepage loads
- [ ] Products display
- [ ] Cart works
- [ ] Login works
- [ ] Signup works
- [ ] Admin panel works
- [ ] Logs clean

### Cleanup
- [ ] Old controllers deleted
- [ ] Old models deleted
- [ ] Old views deleted
- [ ] Old utils cleaned up
- [ ] Backup verified

---

## 🚨 TROUBLESHOOTING

### Error: "Class not found"
```php
// Make sure autoloader is loaded first
require 'config/autoload.php';

// Then use classes
use App\Controllers\HomeController;
$controller = new HomeController();
```

### Error: "Cannot find file"
Check path is correct:
```php
// OLD PATH
include 'views/home.php';

// NEW PATH
include 'src/Views/home/index.php';

// OR use constant
define('VIEW_PATH', dirname(__FILE__) . '/src/Views');
include VIEW_PATH . '/home/index.php';
```

### Website shows blank page
Check:
1. Error logs: `logs/app-YYYY-MM-DD.log`
2. PHP errors: Enable error reporting
3. Database connection: Test config/database.php
4. Autoloader: Verify files exist

### Old code still running
Make sure:
1. Updated index.php (entry point)
2. Old folders deleted
3. Requires updated
4. Cache cleared (if using)

---

## 📝 NEW FILE STRUCTURE EXAMPLES

### src/Controllers/HomeController.php
```php
<?php
namespace App\Controllers;

use App\Models\BaseModel;

class HomeController {
    private $model;
    
    public function __construct() {
        $this->model = new BaseModel();
    }
    
    public function handle($action = 'home') {
        switch($action) {
            case 'home':
                $this->home();
                break;
            // ...
        }
    }
    
    private function home() {
        include VIEW_PATH . '/home/index.php';
    }
}
?>
```

### src/Views/layouts/header.php
```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?? 'Leopard Store'; ?></title>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/custom/style.css">
</head>
<body>
    <nav class="navbar">
        <!-- Navigation -->
    </nav>
```

### config/constants.php
```php
<?php
define('APP_ROOT', dirname(dirname(__FILE__)));
define('SRC_ROOT', APP_ROOT . '/src');
define('VIEW_PATH', SRC_ROOT . '/Views');
define('APP_NAME', 'Leopard Store');
define('APP_DEBUG', true);
?>
```

### config/autoload.php
```php
<?php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_ROOT . '/src/';
    
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

// Load helpers
require APP_ROOT . '/src/Helpers/SecurityHelper.php';
require APP_ROOT . '/src/Helpers/Logger.php';
require APP_ROOT . '/src/Helpers/ProtectionHelper.php';
?>
```

---

## ✅ VERIFICATION

After reorganization, verify:

```bash
# Check all files in new location
ls src/Controllers/
ls src/Models/
ls src/Views/
ls src/Helpers/
ls config/

# Test website
http://localhost/Website/

# Check logs
tail logs/app-*.log

# Verify database
mysql> SELECT * FROM quanlynguoidung LIMIT 1;
```

---

## 🎉 BENEFITS AFTER REORGANIZATION

✅ **Clear Structure** - Easy to find anything
✅ **Professional** - Industry standard layout
✅ **Scalable** - Ready for growth
✅ **Maintainable** - Easy to update
✅ **Testable** - Clear dependencies
✅ **Framework Ready** - Compatible with Laravel, Slim
✅ **Team Friendly** - Everyone understands layout
✅ **Future Proof** - PSR-4 compatible

---

## 📚 DOCUMENTATION

Related files:
- STRUCTURE_GUIDE.md - Structure overview
- SECURITY_UPGRADE.md - Security improvements
- EXAMPLES_USAGE.md - Code examples
- organizer.php - Automation script

---

**Version: 1.0**
**Created: 2026-04-27**
**Status: Ready for Implementation**
