<?php

class AdminController extends Controller
{
    private function guardAdmin()
    {
        if (!isset($_SESSION['quyennd']) || (int)$_SESSION['quyennd'] !== 1) {
            header('Location: index.php?action=taikhoan');
            exit;
        }
    }

    public function index()
    {
        $this->guardAdmin();

        if (isset($_POST['nutdx'])) {
            session_unset();
            header('Location: index.php');
            exit;
        }

        $store = new StoreModel();
        $stats = $store->getStats();
        $chartData = $store->getChartData();

        $this->renderLegacy('quantri', [
            'stats' => $stats,
            'chartData' => $chartData,
        ]);
    }

    public function products()
    {
        $this->guardAdmin();

        if (isset($_POST['nutdx'])) {
            session_unset();
            header('Location: index.php');
            exit;
        }

        $store = new StoreModel();
        $data = $store->categories();
        $data1 = $store->products();
        $data2 = null;

        if (isset($_GET['idloai'])) {
            $data2 = $store->productsByCategory($_GET['idloai']);
        }

        $this->renderLegacy('quanlysanpham', [
            'data' => $data,
            'data1' => $data1,
            'data2' => $data2,
        ]);
    }

    public function messages()
    {
        $this->guardAdmin();

        if (isset($_POST['nutdx'])) {
            session_unset();
            header('Location: index.php');
            exit;
        }

        $this->renderLegacy('quanlytinnhan', []);
    }

    public function create()
    {
        $this->guardAdmin();
        $store = new StoreModel();
        $data = $store->categories();
        $error = null;

        if (isset($_POST['them'])) {
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }

            $t = SecurityHelper::sanitize($_POST['tsp'] ?? '');
            $g = $_POST['gsp'] ?? 0;
            $m = SecurityHelper::sanitize($_POST['mtsp'] ?? '');
            $li = (int)($_POST['idlsp'] ?? 0);
            $n = $_POST['ngaynhap'] ?? date('Y-m-d');
            $slt = (int)($_POST['so_luong_ton'] ?? 0);

            if (empty($t)) {
                $error = 'Tên sản phẩm không được để trống.';
            } elseif (!is_numeric($g) || $g <= 0) {
                $error = 'Giá sản phẩm phải là số và lớn hơn 0.';
            } elseif ($g > 2000000000) {
                $error = 'Giá sản phẩm quá lớn (tối đa 2 tỷ).';
            } elseif ($li <= 0) {
                $error = 'Vui lòng chọn loại thương hiệu.';
            }

            $g = (float)$g;

            $l = 'anh/';
            if ($error === null && !empty($_FILES['lha']['name'])) {
                $target_dir = dirname(__DIR__, 2) . '/anh/';
                $fileName = basename($_FILES['lha']['name']);
                $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($imageFileType, $allowedTypes)) {
                    $error = 'Chỉ chấp nhận file ảnh: JPG, JPEG, PNG, GIF.';
                } elseif ($_FILES['lha']['size'] > 2 * 1024 * 1024) {
                    $error = 'Kích thước file quá lớn (tối đa 2MB).';
                } else {
                    $newFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
                    $target_file = $target_dir . $newFileName;
                    if (move_uploaded_file($_FILES['lha']['tmp_name'], $target_file)) {
                        $l = 'anh/' . $newFileName;
                    } else {
                        $error = 'Không thể upload ảnh. Vui lòng thử lại.';
                    }
                }
            }

            if ($error === null) {
                $store->createProduct($t, $l, $g, $n, $li, $m, $slt);
                header('Location: index.php?action=quantri');
                exit;
            }
        }

        $this->renderLegacy('them', [
            'data' => $data,
            'error' => $error ?? null,
        ]);
    }

    public function edit()
    {
        $this->guardAdmin();
        if (!isset($_GET['id_sua'])) {
            header('Location: index.php?action=quantri');
            exit;
        }

        $store = new StoreModel();
        $data = $store->categories();
        $product = $store->productById($_GET['id_sua']);
        $data2 = [$product];
        $error = null;

        if (isset($_POST['sua'])) {
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }

            $t = SecurityHelper::sanitize($_POST['tsp'] ?? '');
            $g = $_POST['gsp'] ?? 0;
            $m = SecurityHelper::sanitize($_POST['mtsp'] ?? '');
            $li = (int)($_POST['idlsp'] ?? 0);
            $n = date('Y-m-d');
            $slt = (int)($_POST['so_luong_ton'] ?? 0);

            if (empty($t)) {
                $error = 'Tên sản phẩm không được để trống.';
            } elseif (!is_numeric($g) || $g <= 0) {
                $error = 'Giá sản phẩm phải là số và lớn hơn 0.';
            } elseif ($g > 2000000000) {
                $error = 'Giá sản phẩm quá lớn (tối đa 2 tỷ).';
            }

            $g = (float)$g;

            $l = $product['hinhanh_sp'];
            if ($error === null && !empty($_FILES['lha']['name'])) {
                $target_dir = dirname(__DIR__, 2) . '/anh/';
                $fileName = basename($_FILES['lha']['name']);
                $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($imageFileType, $allowedTypes)) {
                    $error = 'Chỉ chấp nhận file ảnh: JPG, JPEG, PNG, GIF.';
                } elseif ($_FILES['lha']['size'] > 2 * 1024 * 1024) {
                    $error = 'Kích thước file quá lớn (tối đa 2MB).';
                } else {
                    $newFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
                    $target_file = $target_dir . $newFileName;
                    if (move_uploaded_file($_FILES['lha']['tmp_name'], $target_file)) {
                        $l = 'anh/' . $newFileName;
                    } else {
                        $error = 'Không thể upload ảnh. Vui lòng thử lại.';
                    }
                }
            }

            // Flash Sale data
            $flash_price = isset($_POST['flash_sale_price']) && $_POST['flash_sale_price'] !== '' ? (float)$_POST['flash_sale_price'] : 0;
            $flash_end = !empty($_POST['flash_sale_end']) ? $_POST['flash_sale_end'] : null;

            if ($flash_price < 0 || $flash_price >= $g) {
                if ($flash_price != 0) {
                    $error = 'Giá Flash Sale phải nhỏ hơn giá gốc và lớn hơn 0.';
                }
            }

            if ($error === null) {
                $store->updateProduct($_GET['id_sua'], $t, $l, $g, $n, $li, $m, $slt);
                $store->updateFlashSale($_GET['id_sua'], $flash_price, $flash_end);
                header('Location: index.php?action=quantri');
                exit;
            }
        }

        $this->renderLegacy('sua', [
            'data' => $data,
            'data2' => $data2,
            'error' => $error ?? null,
        ]);
    }

    public function delete()
    {
        $this->guardAdmin();

        // FIX: Nhận từ POST (form), không dùng GET nữa
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }

        $id = (int)($_POST['id_xoa'] ?? 0);
        if ($id > 0) {
            $store = new StoreModel();
            $store->deleteProduct($id);
        }

        header('Location: index.php?action=quantri');
        exit;
    }

    // Category Management
    public function categories()
    {
        $this->guardAdmin();
        $store = new StoreModel();
        $data = $store->categories();
        
        $this->renderLegacy('quanlyloai', [
            'data' => $data
        ]);
    }

    public function createCategory()
    {
        $this->guardAdmin();
        $store = new StoreModel();
        $data = $store->categories();
        $error = null;

        if (isset($_POST['themloai'])) {
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }

            $name = SecurityHelper::sanitize($_POST['tenloai'] ?? '');
            if (empty($name)) {
                $error = 'Vui lòng nhập tên thương hiệu.';
            } else {
                $store->createCategory($name);
                header('Location: index.php?action=quanlyloai');
                exit;
            }
        }

        $this->renderLegacy('themloai', [
            'data' => $data,
            'error' => $error
        ]);
    }

    public function editCategory()
    {
        $this->guardAdmin();
        if (!isset($_GET['id'])) {
            header('Location: index.php?action=quanlyloai');
            exit;
        }

        $store = new StoreModel();
        $data = $store->categories();
        $category = $store->categoryById($_GET['id']);
        $error = null;

        if (isset($_POST['sualoai'])) {
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }

            $name = SecurityHelper::sanitize($_POST['tenloai'] ?? '');
            if (empty($name)) {
                $error = 'Vui lòng nhập tên thương hiệu.';
            } else {
                $store->updateCategory($_GET['id'], $name);
                header('Location: index.php?action=quanlyloai');
                exit;
            }
        }

        $this->renderLegacy('sualoai', [
            'data' => $data,
            'category' => $category,
            'error' => $error
        ]);
    }

    public function deleteCategory()
    {
        $this->guardAdmin();

        // FIX: Nhận từ POST + CSRF
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $store = new StoreModel();
            $store->deleteCategory($id);
        }
        header('Location: index.php?action=quanlyloai');
        exit;
    }

    // Order Management
    public function orders()
    {
        $this->guardAdmin();
        $store = new StoreModel();
        $data = $store->categories(); // For sidebar
        $orders = $store->allOrders();

        $this->renderLegacy('quanlydonhang', [
            'data' => $data,
            'orders' => $orders
        ]);
    }

    public function orderDetail()
    {
        $this->guardAdmin();
        if (!isset($_GET['id'])) {
            header('Location: index.php?action=quanlydonhang');
            exit;
        }

        $store = new StoreModel();
        $data = $store->categories(); // For sidebar
        $order = $store->orderById($_GET['id']);
        $shippers = $store->getShippers(); // Get list of shippers

        if (!$order) {
            header('Location: index.php?action=quanlydonhang');
            exit;
        }

        $this->renderLegacy('chitietdonhang', [
            'data' => $data,
            'order' => $order,
            'shippers' => $shippers
        ]);
    }

    public function updateOrder()
    {
        $this->guardAdmin();

        // FIX: Thêm CSRF check — cập nhật trạng thái đơn hàng phải có token
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }

        $id     = (int)($_POST['id_dh'] ?? 0);
        $status = (int)($_POST['trang_thai'] ?? 1);
        $id_shipper = (int)($_POST['id_shipper'] ?? 0);

        if ($id > 0) {
            $store = new StoreModel();
            
            // Assign shipper if status is being updated to 2 (Đang giao hàng)
            if ($status == 2 && $id_shipper > 0) {
                $store->assignShipper($id, $id_shipper);
            }
            
            // Lấy thông tin đơn hàng trước khi cập nhật để kiểm tra trạng thái cũ (nếu cần)
            $order = $store->orderById($id);
            
            if ($order && $order['trang_thai'] != $status) {
                $store->updateOrderStatus($id, $status);
                
                // Gửi email thông báo cập nhật trạng thái
                if (!empty($order['email_nguoinhan'])) {
                    require_once BASE_PATH . '/helpers/MailHelper.php';
                    // Đơn hàng vừa lấy ra chưa cập nhật trạng thái mới trong mảng, nên ta truyền status mới vào
                    MailHelper::sendStatusUpdate($order['email_nguoinhan'], $order['ten_nguoinhan'] ?? 'Khách hàng', $order, $status);
                }
            }
        }

        header('Location: index.php?action=quanlydonhang');
        exit;
    }

    public function discounts()
    {
        $this->guardAdmin();
        $store = new StoreModel();
        $discounts = $store->getAllDiscountCodes();

        $this->renderLegacy('quanlymagiamgia', [
            'discounts' => $discounts,
        ]);
    }

    public function createDiscount()
    {
        $this->guardAdmin();
        $error = null;

        if (isset($_POST['them'])) {
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }

            $code = SecurityHelper::sanitize($_POST['ma_code'] ?? '');
            $loai = $_POST['loai'] ?? 'fixed';
            $gia_tri = (float)($_POST['gia_tri'] ?? 0);
            $so_luong = (int)($_POST['so_luong'] ?? 0);
            $ngay_het_han = $_POST['ngay_het_han'] ?: null;

            if (empty($code)) {
                $error = 'Mã code không được để trống.';
            } elseif ($gia_tri <= 0) {
                $error = 'Giá trị giảm phải lớn hơn 0.';
            } elseif ($so_luong < 0) {
                $error = 'Số lượng không hợp lệ.';
            }

            if ($error === null) {
                $store = new StoreModel();
                $store->createDiscountCode($code, $loai, $gia_tri, $so_luong, $ngay_het_han);
                header('Location: index.php?action=quanlymagiamgia');
                exit;
            }
        }

        $this->renderLegacy('themmagiamgia', [
            'error' => $error,
        ]);
    }

    public function deleteDiscount()
    {
        $this->guardAdmin();

        // FIX: Nhận từ POST + CSRF
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $store = new StoreModel();
            $store->deleteDiscountCode($id);
        }
        header('Location: index.php?action=quanlymagiamgia');
        exit;
    }

    public function users()
    {
        $this->guardAdmin();
        $store = new StoreModel();
        $users = $store->getAllUsers();

        $this->renderLegacy('quanlynguoidung', [
            'users' => $users,
        ]);
    }

    public function updateUserRole()
    {
        $this->guardAdmin();

        // FIX: Thêm CSRF check cho việc phân quyền
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }

        $id   = (int)($_POST['id_nd']    ?? 0);
        $role = (int)($_POST['quyen_nd'] ?? 0);

        if ($id > 0) {
            // Không cho phép admin tự hạ quyền của chính mình
            if ($id == $_SESSION['id_nd'] && $role !== 1) {
                header('Location: index.php?action=quanlynguoidung&error=self_demotion');
                exit;
            }

            $store = new StoreModel();
            $store->updateUserRole($id, $role);
        }

        header('Location: index.php?action=quanlynguoidung');
        exit;
    }

    public function deleteUser()
    {
        $this->guardAdmin();

        // FIX: Nhận từ POST + CSRF
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }

        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            // Không cho phép admin tự xóa chính mình
            if ($id == $_SESSION['id_nd']) {
                header('Location: index.php?action=quanlynguoidung&error=self_deletion');
                exit;
            }

            $store = new StoreModel();
            $store->deleteUser($id);
        }

        header('Location: index.php?action=quanlynguoidung');
        exit;
    }

    // ==========================================
    // QUẢN LÝ BANNER
    // ==========================================
    public function banners()
    {
        $this->guardAdmin();
        require_once BASE_PATH . '/models/BannerModel.php';
        $bannerModel = new BannerModel();
        $danhsachbanner = $bannerModel->getAllBanners();
        require BASE_PATH . '/views/quanlybanner.php';
    }

    public function createBanner()
    {
        $this->guardAdmin();
        require_once BASE_PATH . '/models/BannerModel.php';
        $bannerModel = new BannerModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }
            $vi_tri = $_POST['vi_tri'] ?? 'main';
            $link = $_POST['link'] ?? '#';
            $trang_thai = (int)($_POST['trang_thai'] ?? 1);
            
            $hinh_anh = '';
            if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
                $target_dir = "anh/";
                $imageFileType = strtolower(pathinfo($_FILES["hinh_anh"]["name"], PATHINFO_EXTENSION));
                $newFileName = uniqid() . '.' . $imageFileType;
                $target_file = $target_dir . $newFileName;
                
                if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
                    $hinh_anh = 'anh/' . $newFileName;
                }
            }

            if (!$hinh_anh && isset($_POST['hinh_anh_url']) && !empty($_POST['hinh_anh_url'])) {
                $hinh_anh = $_POST['hinh_anh_url'];
            }

            if ($hinh_anh) {
                $bannerModel->insertBanner($hinh_anh, $link, $vi_tri, $trang_thai);
                header('Location: index.php?action=quanlybanner');
                exit;
            } else {
                $error = "Vui lòng chọn ảnh tải lên hoặc nhập URL ảnh!";
                require BASE_PATH . '/views/thembanner.php';
                return;
            }
        }
        require BASE_PATH . '/views/thembanner.php';
    }

    public function editBanner()
    {
        $this->guardAdmin();
        require_once BASE_PATH . '/models/BannerModel.php';
        $bannerModel = new BannerModel();

        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            header('Location: index.php?action=quanlybanner');
            exit;
        }

        $banner = $bannerModel->getBannerById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }
            $vi_tri = $_POST['vi_tri'] ?? 'main';
            $link = $_POST['link'] ?? '#';
            $trang_thai = (int)($_POST['trang_thai'] ?? 1);
            
            $hinh_anh = '';
            if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['error'] == 0) {
                $target_dir = "anh/";
                $imageFileType = strtolower(pathinfo($_FILES["hinh_anh"]["name"], PATHINFO_EXTENSION));
                $newFileName = uniqid() . '.' . $imageFileType;
                $target_file = $target_dir . $newFileName;
                
                if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'], $target_file)) {
                    $hinh_anh = 'anh/' . $newFileName;
                }
            } elseif (isset($_POST['hinh_anh_url']) && !empty($_POST['hinh_anh_url'])) {
                $hinh_anh = $_POST['hinh_anh_url'];
            }

            $bannerModel->updateBanner($id, $hinh_anh, $link, $vi_tri, $trang_thai);
            header('Location: index.php?action=quanlybanner');
            exit;
        }

        require BASE_PATH . '/views/suabanner.php';
    }

    public function deleteBanner()
    {
        $this->guardAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=quanlybanner');
            exit;
        }
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            require_once BASE_PATH . '/models/BannerModel.php';
            $bannerModel = new BannerModel();
            $bannerModel->deleteBanner($id);
        }
        header('Location: index.php?action=quanlybanner');
        exit;
    }
}
