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

    public function vongquay() {
        if (!isset($_SESSION['id_nd'])) {
            header("Location: index.php?action=dangnhap&return_url=" . urlencode('index.php?action=vongquay'));
            exit;
        }
        $store = new StoreModel();
        $data = $store->categories();
        $id_nd = $_SESSION['id_nd'];
        $canSpin = $store->kiemTraLuotQuay($id_nd);
        $lichSu = $store->layLichSuVongQuay($id_nd);

        $this->renderLegacy('vongquay', [
            'data'    => $data,
            'canSpin' => $canSpin,
            'lichSu'  => $lichSu,
        ]);
    }

    public function spinWheel() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id_nd'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập!']);
            exit;
        }

        $store = new StoreModel();
        $id_nd = $_SESSION['id_nd'];

        if (!$store->kiemTraLuotQuay($id_nd)) {
            echo json_encode(['success' => false, 'message' => 'Bạn đã quay hôm nay rồi! Hãy quay lại vào ngày mai nhé 😊']);
            exit;
        }

        // 6 ô: index, label, loai, gia_tri, xac_suat
        $prizes = [
            ['index' => 0, 'label' => 'Chúc bạn may mắn lần sau', 'loai' => null,         'gia_tri' => 0,      'xac_suat' => 40],
            ['index' => 1, 'label' => 'Giảm 5%',                   'loai' => 'percentage', 'gia_tri' => 5,      'xac_suat' => 28],
            ['index' => 2, 'label' => 'Giảm 10%',                  'loai' => 'percentage', 'gia_tri' => 10,     'xac_suat' => 17],
            ['index' => 3, 'label' => 'Giảm 50.000đ',              'loai' => 'fixed',      'gia_tri' => 50000,  'xac_suat' => 10],
            ['index' => 4, 'label' => 'Giảm 15%',                  'loai' => 'percentage', 'gia_tri' => 15,     'xac_suat' => 4],
            ['index' => 5, 'label' => 'Giảm 100.000đ',             'loai' => 'fixed',      'gia_tri' => 100000, 'xac_suat' => 1],
        ];

        $rand = rand(1, 100);
        $cumulative = 0;
        $selected = $prizes[0];
        foreach ($prizes as $prize) {
            $cumulative += $prize['xac_suat'];
            if ($rand <= $cumulative) {
                $selected = $prize;
                break;
            }
        }

        $ma_code = null;
        if ($selected['loai'] !== null) {
            $ma_code = $store->taoMaGiamGiaTuDong($selected['loai'], $selected['gia_tri']);
        }

        $store->luuKetQuaVongQuay($id_nd, $selected['label'], $ma_code);

        echo json_encode([
            'success'     => true,
            'prize_index' => $selected['index'],
            'prize_label' => $selected['label'],
            'ma_code'     => $ma_code,
        ]);
        exit;
    }
}
