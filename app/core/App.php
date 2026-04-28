<?php

class App
{
    private $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        $this->router->get('/', ['HomeController', 'index']);
        $this->router->get('/home', ['HomeController', 'index']);
        $this->router->get('home', ['HomeController', 'index']);
        $this->router->get('/product', ['ProductController', 'index']);
        $this->router->get('/sanpham', ['ProductController', 'index']);
        $this->router->get('/chitietsanpham', ['ProductController', 'detail']);
        $this->router->post('/chitietsanpham', ['ProductController', 'detail']);
        $this->router->get('/ketquatimkiem', ['ProductController', 'search']);
        $this->router->get('/gioithieu', ['PageController', 'about']);
        $this->router->get('/lienhe', ['PageController', 'contact']);

        $this->router->get('/taikhoan', ['AuthController', 'account']);
        $this->router->post('/taikhoan', ['AuthController', 'account']);
        $this->router->get('/dangxuat', ['AuthController', 'logout']);
        $this->router->post('/dangxuat', ['AuthController', 'logout']);
        
        $this->router->get('/thongtintaikhoan', ['AuthController', 'profile']);
        $this->router->post('/thongtintaikhoan', ['AuthController', 'profile']);
        $this->router->post('/huydonhang', ['AuthController', 'cancelOrder']);

        $this->router->get('/giohang', ['CartController', 'index']);
        $this->router->post('/giohang', ['CartController', 'index']);
        $this->router->get('/thanhtoan', ['CheckoutController', 'index']);

        $this->router->get('/quantri', ['AdminController', 'index']);
        $this->router->post('/quantri', ['AdminController', 'index']);
        $this->router->get('/quanlysanpham', ['AdminController', 'products']);
        $this->router->post('/quanlysanpham', ['AdminController', 'products']);
        $this->router->get('/themsanpham', ['AdminController', 'create']);
        $this->router->post('/themsanpham', ['AdminController', 'create']);
        $this->router->get('/sua', ['AdminController', 'edit']);
        $this->router->post('/sua', ['AdminController', 'edit']);
        $this->router->get('/xoasanpham', ['AdminController', 'delete']);
        $this->router->post('/xoasanpham', ['AdminController', 'delete']);
        // FIX P2: Bỏ route /thanhtoan đăng ký 2 lần — giữ lại ở dưới (dòng 68)

        // Category routes
        $this->router->get('/quanlyloai', ['AdminController', 'categories']);
        $this->router->get('/themloai', ['AdminController', 'createCategory']);
        $this->router->post('/themloai', ['AdminController', 'createCategory']);
        $this->router->get('/sualoai', ['AdminController', 'editCategory']);
        $this->router->post('/sualoai', ['AdminController', 'editCategory']);
        $this->router->get('/xoaloai', ['AdminController', 'deleteCategory']);
        $this->router->post('/xoaloai', ['AdminController', 'deleteCategory']);

        // Order routes
        $this->router->get('/quanlydonhang', ['AdminController', 'orders']);
        $this->router->get('/chitietdonhang', ['AdminController', 'orderDetail']);
        $this->router->post('/capnhatdonhang', ['AdminController', 'updateOrder']);
        
        // Admin Messages
        $this->router->get('/quanlytinnhan', ['AdminController', 'messages']);

        // Chatbot Route
        $this->router->post('/api/chatbot', ['ChatbotController', 'ask']);

        // Live Chat Routes
        $this->router->post('/api/chat/send', ['LiveChatController', 'sendMessage']);
        $this->router->get('/api/chat/get', ['LiveChatController', 'getMessages']);
        $this->router->get('/api/chat/inbox', ['LiveChatController', 'getInboxUsers']);

        // User management routes
        $this->router->get('/quanlynguoidung', ['AdminController', 'users']);
        $this->router->post('/capnhatquyen', ['AdminController', 'updateUserRole']);
        $this->router->post('/xoanguoidung', ['AdminController', 'deleteUser']);

        // Discount routes
        $this->router->get('/quanlymagiamgia', ['AdminController', 'discounts']);
        $this->router->get('/themmagiamgia', ['AdminController', 'createDiscount']);
        $this->router->post('/themmagiamgia', ['AdminController', 'createDiscount']);
        $this->router->post('/xoamagiamgia', ['AdminController', 'deleteDiscount']);

        // Banner routes
        $this->router->get('/quanlybanner', ['AdminController', 'banners']);
        $this->router->get('/thembanner', ['AdminController', 'createBanner']);
        $this->router->post('/thembanner', ['AdminController', 'createBanner']);
        $this->router->get('/suabanner', ['AdminController', 'editBanner']);
        $this->router->post('/suabanner', ['AdminController', 'editBanner']);
          $this->router->post('/xoabanner', ['AdminController', 'deleteBanner']);
        $this->router->post('/xacnhanthanhtoan', ['CheckoutController', 'confirm']);
        $this->router->get('/muahangthanhcong', ['CheckoutController', 'success']);
        $this->router->post('/muahangthanhcong', ['CheckoutController', 'success']);
        
        // Location routes
        $this->router->get('/api/provinces', ['HomeController', 'getProvinces']);
        $this->router->get('/api/wards', ['HomeController', 'getWards']);
        
        // Momo callbacks
        $this->router->get('/momo_post', ['CheckoutController', 'momo_post']);
        $this->router->post('/momo_ipn', ['CheckoutController', 'momo_post']);

        // Shipper routes
        $this->router->get('/shipper', ['ShipperController', 'index']);
        $this->router->post('/shipper_accept_order', ['ShipperController', 'acceptOrder']);
        $this->router->post('/shipper_update_status', ['ShipperController', 'updateStatus']);
        $this->router->post('/shipper_update_location', ['ShipperController', 'updateLocation']);
        $this->router->get('/get_shipper_location', ['ShipperController', 'getLocation']);
        $this->router->get('/shipper_view_map', ['ShipperController', 'viewMap']);
    }

    public function run()
    {
        if (!empty($_GET['action'])) {
            $path = $_GET['action'];
        } elseif (!empty($_GET['url'])) {
            $path = $_GET['url'];
        } else {
            $path = '/';
        }

        $this->router->dispatch($_SERVER['REQUEST_METHOD'], $path);
    }
}
