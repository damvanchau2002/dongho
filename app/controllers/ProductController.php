<?php

class ProductController extends Controller
{
    public function index()
    {
        $store = new StoreModel();
        $data = $store->categories();
        $data1 = $store->products();
        $data2 = null;

        if (isset($_GET['idloai'])) {
            $data2 = $store->productsByCategory($_GET['idloai']);
        }

        $this->renderLegacy('sanpham', [
            'data' => $data,
            'data1' => $data1,
            'data2' => $data2,
        ]);
    }

    public function detail()
    {
        // Handle comment submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_comment') {
            require_once BASE_PATH . '/models/CommentModel.php';
            $commentModel = new CommentModel();
            
            $id_sp = (int)($_POST['id_sp'] ?? 0);
            $sao_danh_gia = (int)($_POST['sao_danh_gia'] ?? 5);
            $tieu_de = trim($_POST['tieu_de'] ?? '');
            $noi_dung = trim($_POST['noi_dung'] ?? '');
            $ten_nguoidung = trim($_POST['ten_nguoidung'] ?? '');
            $email_nguoidung = trim($_POST['email_nguoidung'] ?? '');
            
            // Validation
            if (empty($id_sp) || empty($tieu_de) || empty($noi_dung) || empty($ten_nguoidung) || empty($email_nguoidung)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
                exit;
            }
            
            if ($sao_danh_gia < 1 || $sao_danh_gia > 5) {
                $sao_danh_gia = 5;
            }
            
            // Add comment
            if ($commentModel->addComment($id_sp, $ten_nguoidung, $email_nguoidung, $sao_danh_gia, $tieu_de, $noi_dung)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Bình luận đã được gửi']);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại']);
                exit;
            }
        }
        
        $store = new StoreModel();
        if (empty($_GET['id'])) {
            header('Location: index.php?action=sanpham');
            exit;
        }

        $proInfo = $store->productById($_GET['id']);
        if (!$proInfo) {
            $this->renderLegacy('404pages');
            return;
        }

        $spnn = $store->randomProducts(8);
        // Lấy thông tin user đang đăng nhập để truyền sang view
        $loggedIn        = isset($_SESSION['tennd']);
        $currentUserName  = '';
        $currentUserEmail = '';
        if ($loggedIn) {
            $currentUserName = $_SESSION['tennd'];
            if (!empty($_SESSION['id_nd'])) {
                $userRow = $store->getUserById((int)$_SESSION['id_nd']);
                $currentUserEmail = $userRow['email_nd'] ?? '';
            }
        }

        $this->renderLegacy('chitietsanpham', [
            'proInfo'          => $proInfo,
            'spnn'             => $spnn,
            'loggedIn'         => $loggedIn,
            'currentUserName'  => $currentUserName,
            'currentUserEmail' => $currentUserEmail,
        ]);
    }

    public function search()
    {
        $store = new StoreModel();
        $spnn = $store->randomProducts(8);
        $data_cart = [];
        $kw = '';

        if (!empty($_GET['keyword'])) {
            $kw = $_GET['keyword'];
            $data_cart = $store->searchProducts($kw);
        }

        $this->renderLegacy('ketquatimkiem', [
            'spnn' => $spnn,
            'data_cart' => $data_cart,
            'kw' => $kw,
            'conn' => getConnection(),
        ]);
    }
}
