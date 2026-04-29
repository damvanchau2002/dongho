<?php

class HomeController extends Controller
{
    public function index()
    {
        $store = new StoreModel();
        $data = $store->categories();
        
        $limit_new = 8;
        $page_new = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page_new < 1) $page_new = 1;
        
        $data1 = $store->products();
        $spnoibat = $store->featuredProducts();
        
        $spmoinhat = $store->newestProducts();
        $total_new = count($spmoinhat);
        $total_pages_new = ceil($total_new / $limit_new);
        if ($page_new > $total_pages_new && $total_pages_new > 0) $page_new = $total_pages_new;
        
        $offset_new = ($page_new - 1) * $limit_new;
        $paginated_spmoinhat = array_slice($spmoinhat, $offset_new, $limit_new);

        $flash_sale_products = $store->flashSaleProducts();
        $data2 = null;

        if (isset($_GET['idloai'])) {
            $data2 = $store->productsByCategory($_GET['idloai']);
        }

        require_once BASE_PATH . '/models/BannerModel.php';
        $bannerModel = new BannerModel();
        $activeBanners = $bannerModel->getActiveBanners();
        
        $banners = [
            'main' => [],
            'side1' => [],
            'side2' => []
        ];
        
        if (is_array($activeBanners)) {
            foreach ($activeBanners as $b) {
                if (isset($banners[$b['vi_tri']])) {
                    $banners[$b['vi_tri']][] = $b;
                }
            }
        }

        $this->renderLegacy('home', [
            'data' => $data,
            'data1' => $data1,
            'data2' => $data2,
            'spnoibat' => $spnoibat,
            'spmoinhat' => $spmoinhat,
            'paginated_spmoinhat' => $paginated_spmoinhat,
            'total_pages_new' => $total_pages_new,
            'flash_sale_products' => $flash_sale_products,
            'banners' => $banners,
            'page_new' => $page_new
        ]);
    }

    public function getProvinces() {
        header('Content-Type: application/json');
        $store = new StoreModel();
        $provinces = $store->getAllProvinces();
        echo json_encode($provinces);
        exit;
    }

    public function getWards() {
        header('Content-Type: application/json');
        $provinceCode = $_GET['province_code'] ?? '';
        $store = new StoreModel();
        $wards = $store->getWardsByProvince($provinceCode);
        echo json_encode($wards);
        exit;
    }

    public function toggleFavorite() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['id_nd'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện chức năng này!', 'require_login' => true]);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $id_sp = $input['id_sp'] ?? 0;
        
        if (!$id_sp) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $store = new StoreModel();
        $id_nd = $_SESSION['id_nd'];
        
        $is_favorite = $store->kiemTraYeuThich($id_nd, $id_sp);
        if ($is_favorite) {
            $store->xoaYeuThich($id_nd, $id_sp);
            echo json_encode(['success' => true, 'is_favorite' => false, 'message' => 'Đã bỏ thích sản phẩm']);
        } else {
            $store->themYeuThich($id_nd, $id_sp);
            echo json_encode(['success' => true, 'is_favorite' => true, 'message' => 'Đã thêm vào yêu thích']);
        }
        exit;
    }

    public function favoriteList() {
        if (!isset($_SESSION['id_nd'])) {
            header("Location: index.php?action=dangnhap");
            exit;
        }
        $store = new StoreModel();
        $data = $store->categories();
        $favorites = $store->layDanhSachYeuThich($_SESSION['id_nd']);
        
        $this->renderLegacy('sanpham_yeuthich', [
            'data' => $data,
            'favorites' => $favorites
        ]);
    }
}
