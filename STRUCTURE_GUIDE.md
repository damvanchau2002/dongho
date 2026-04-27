# 📁 CẤUTRÚC CODE CHUẨN - MVC + HELPERS

## ❌ HIỆN TẠI (Rải Rác)
```
Website/
├── index.php
├── style.css
├── app/                  # <-- Chứa logic & config
│   ├── admin/
│   ├── controller/       # <-- Controllers
│   ├── model/            # <-- Models
│   ├── views/            # <-- Views
│   ├── config.php
│   └── js/
├── controllers/          # <-- Controllers (lại)
├── models/               # <-- Models (lại)
├── views/                # <-- Views (lại)
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── utils/                # <-- Utilities rải rác
│   ├── connect_db.php
│   ├── header.php
│   └── ...
└── ...
```

### 🔴 Vấn đề:
- ❌ 2 folder controllers (app/controller + controllers)
- ❌ 2 folder models (app/model + models)
- ❌ 2 folder views (app/views + views)
- ❌ Config lẫn trong app/
- ❌ Utilities rải trong utils/
- ❌ Khó maintain & expand

---

## ✅ CẤU TRÚC CHUẨN (Tổ Chức Rõ Ràng)
```
Website/
│
├── config/                    # 📋 Configuration
│   ├── database.php          # Database config
│   ├── constants.php         # Global constants
│   └── app.php               # App config
│
├── public/                    # 🌐 Public files (web accessible)
│   ├── index.php             # Entry point
│   ├── .htaccess             # URL rewrite
│   ├── css/
│   │   ├── bootstrap.css
│   │   ├── bootstrap.min.css
│   │   └── custom/
│   │       ├── home.css
│   │       ├── product.css
│   │       └── ...
│   ├── js/
│   │   ├── bootstrap.js
│   │   ├── jquery.js
│   │   └── custom/
│   │       ├── script.js
│   │       └── ...
│   └── images/
│       ├── logo/
│       ├── products/
│       └── ...
│
├── src/                       # 💻 Source code
│   ├── Controllers/           # Request handlers
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── UserController.php
│   │   └── AdminController.php
│   │
│   ├── Models/                # Data access
│   │   ├── BaseModel.php
│   │   ├── Product.php
│   │   ├── User.php
│   │   ├── Cart.php
│   │   ├── Order.php
│   │   └── Comment.php
│   │
│   ├── Views/                 # Templates
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   ├── navbar.php
│   │   │   └── sidebar.php
│   │   ├── home/
│   │   │   └── index.php
│   │   ├── product/
│   │   │   ├── index.php
│   │   │   ├── detail.php
│   │   │   └── search.php
│   │   ├── cart/
│   │   │   └── index.php
│   │   ├── checkout/
│   │   │   └── index.php
│   │   ├── user/
│   │   │   ├── profile.php
│   │   │   ├── login.php
│   │   │   └── signup.php
│   │   ├── admin/
│   │   │   ├── dashboard.php
│   │   │   ├── products.php
│   │   │   └── orders.php
│   │   └── 404.php
│   │
│   ├── Helpers/               # Utility functions
│   │   ├── SecurityHelper.php     # Security
│   │   ├── Logger.php             # Logging
│   │   ├── ProtectionHelper.php   # CSRF, Rate Limit
│   │   ├── ValidationHelper.php   # Validation (new)
│   │   ├── FormHelper.php         # Form utilities (new)
│   │   └── StringHelper.php       # String utilities (new)
│   │
│   ├── Middleware/            # Request/Response middleware
│   │   ├── AuthMiddleware.php
│   │   ├── AdminMiddleware.php
│   │   └── ErrorHandler.php
│   │
│   └── Database/              # Database related
│       ├── Connection.php
│       └── Query.php
│
├── logs/                      # 📝 Application logs
│   ├── app-2026-04-27.log
│   └── error-2026-04-27.log
│
├── storage/                   # 📦 Storage
│   ├── uploads/
│   │   ├── products/
│   │   └── avatars/
│   └── cache/
│
├── tests/                     # 🧪 Unit tests (future)
│   ├── Unit/
│   └── Feature/
│
├── vendor/                    # 📚 Composer packages
│
├── .env                       # 🔐 Environment (don't commit)
├── .env.example              # 📖 Environment template
├── .gitignore                # 🚫 Git ignore
├── composer.json             # 📦 Dependencies
└── README.md                 # 📄 Documentation
```

---

## 🔄 MIGRATION MAP (File Di Chuyển)

### Controllers
```
app/controller/login_controller.php          → src/Controllers/AuthController.php
app/controller/signup_controller.php         → src/Controllers/AuthController.php
app/controller/product_ctl.php               → src/Controllers/ProductController.php
app/controller/addCart_ctl.php               → src/Controllers/CartController.php
app/controller/checkout_ctl.php              → src/Controllers/CheckoutController.php
app/controller/comment_ctl.php               → src/Controllers/CommentController.php
app/controller/ctmAccount_ctl.php            → src/Controllers/UserController.php
controllers/HomeController.php               → src/Controllers/HomeController.php
```

### Models
```
app/model/login_model.php                    → src/Models/User.php
app/model/signup_model.php                   → src/Models/User.php
models/BaseModel.php                         → src/Models/BaseModel.php
```

### Views
```
app/views/*                                  → src/Views/*
views/*                                      → src/Views/*
utils/header.php                             → src/Views/layouts/header.php
utils/footer.php                             → src/Views/layouts/footer.php
utils/navbar.php                             → src/Views/layouts/navbar.php
```

### Helpers
```
helpers/SecurityHelper.php                   → src/Helpers/SecurityHelper.php
helpers/Logger.php                           → src/Helpers/Logger.php
helpers/ProtectionHelper.php                 → src/Helpers/ProtectionHelper.php
```

### Config
```
app/config.php                               → config/database.php
utils/connect_db.php                         → config/database.php
```

### Public (Static Files)
```
public/css/* → public/css/*
public/js/*  → public/js/*
public/images/* → public/images/*
```

---

## 📊 FOLDER STRUCTURE COMPARISON

| Aspect | Before | After |
|--------|--------|-------|
| Controllers | 2 locations | 1 location (src/Controllers) |
| Models | 2 locations | 1 location (src/Models) |
| Views | 2 locations | 1 location (src/Views) |
| Config | In app/ | Separated (config/) |
| Helpers | helpers/ | src/Helpers/ |
| Clarity | ⭐⭐ | ⭐⭐⭐⭐⭐ |
| Maintenance | 🔴 Hard | 🟢 Easy |

---

## 🎯 BENEFITS OF NEW STRUCTURE

✅ **One place for each type of file** (DRY principle)
✅ **Easy to navigate** - Find anything quickly
✅ **Professional structure** - Industry standard
✅ **Scalable** - Easy to add new features
✅ **Clean separation** - Logic vs Views vs Config
✅ **Easy to maintain** - Clear responsibility
✅ **Ready for framework migration** - Compatible with Laravel, Slim
✅ **Autoloader friendly** - PSR-4 compatible
✅ **Easy to test** - Clear dependencies
✅ **Better for team** - Everyone knows structure

---

## 📦 HOW FILES ARE ORGANIZED

### By Type (Current - Bad)
```
Views here → Views there → Views everywhere
Models here → Models there → Models scattered
Controllers everywhere
```

### By Domain (New - Good)
```
User Domain:
- src/Controllers/AuthController.php
- src/Models/User.php
- src/Views/layouts/header.php
- src/Helpers/ValidationHelper.php

Product Domain:
- src/Controllers/ProductController.php
- src/Models/Product.php
- src/Views/product/*
- src/Helpers/...

Cart Domain:
- src/Controllers/CartController.php
- src/Models/Cart.php
- src/Views/cart/*
```

Easy to find everything related to one feature!

---

## 🔗 ENTRY POINT

**Before:**
```php
// index.php (confusing - what does it do?)
require 'controllers/HomeController.php';
$ctrl = new SanphamController();
```

**After:**
```php
// public/index.php (clear - entry point)
define('APP_ROOT', dirname(__DIR__));
require APP_ROOT . '/config/database.php';
require APP_ROOT . '/src/Controllers/HomeController.php';

$controller = new HomeController();
$controller->handle($_GET['action'] ?? 'home');
```

---

## 📝 CONFIGURATION FILES

### config/constants.php
```php
define('APP_NAME', 'Leopard Store');
define('APP_VERSION', '1.0.0');
define('APP_DEBUG', true);
define('APP_ROOT', dirname(__DIR__));
define('SRC_ROOT', APP_ROOT . '/src');
define('VIEW_PATH', SRC_ROOT . '/Views');
```

### config/database.php
```php
return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'name' => getenv('DB_NAME') ?: 'leopard_store',
];
```

### .env
```env
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=leopard_store
APP_DEBUG=true
```

---

## 🚀 AUTOLOADER

Add to config/autoload.php:

```php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_ROOT . '/src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});
```

Usage:
```php
use App\Controllers\ProductController;
use App\Models\Product;
use App\Helpers\SecurityHelper;

$controller = new ProductController();
$product = new Product();
```

---

## ✨ NEXT STEP: IMPLEMENTATION

Bạn đã sẵn sàng để tôi:
1. Tạo folder structure mới
2. Di chuyển/organizer files
3. Update autoloader
4. Cleanup cấu trúc cũ
5. Tạo documentation

**Ready? Proceed with restructuring? (Y/N)**
