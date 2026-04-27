<?php

class CartController extends Controller
{
    private function generateOrderId()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 6;
        $orderId = '';
        for ($i = 0; $i < $length; $i++) {
            $orderId .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $orderId;
    }

    private function updateCart($add = false)
    {
        if (!isset($_POST['quantity']) || !is_array($_POST['quantity'])) {
            return;
        }

        foreach ($_POST['quantity'] as $id => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                unset($_SESSION['cart'][$id]);
                continue;
            }

            if ($add && isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id] += $qty;
            } else {
                $_SESSION['cart'][$id] = $qty;
            }
        }
    }

    public function index()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!empty($_GET['task'])) {
            switch ($_GET['task']) {
                case 'add':
                    $this->updateCart(true);
                    header('Location: index.php?action=giohang');
                    exit;

                case 'delete':
                    if (isset($_GET['id'])) {
                        unset($_SESSION['cart'][$_GET['id']]);
                    }
                    header('Location: index.php?action=giohang');
                    exit;

                case 'submit':
                    // CSRF check
                    if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                        die('CSRF token validation failed');
                    }

                    if (isset($_POST['update_click'])) {
                        $this->updateCart(false);
                        header('Location: index.php?action=giohang');
                        exit;
                    }

                    if (isset($_POST['apply_coupon'])) {
                        if (isset($_POST['coupon_code'])) {
                            $code = trim($_POST['coupon_code']);
                            $store = new StoreModel();
                            $discount = $store->getDiscountCode($code);
                            if ($discount) {
                                $_SESSION['discount'] = $discount;
                                $_SESSION['success_msg'] = "Áp dụng mã giảm giá thành công!";
                            } else {
                                unset($_SESSION['discount']);
                                $_SESSION['error_msg'] = "Mã giảm giá không hợp lệ hoặc đã hết hạn.";
                            }
                        }
                        header('Location: index.php?action=giohang');
                        exit;
                    }

                    if (isset($_POST['order_click'])) {
                        $orderId = $this->generateOrderId();
                        $store   = new StoreModel();

                        // -----------------------------------------------
                        // FIX: Tính lại tổng tiền phía SERVER từ giá DB.
                        // KHÔNG dùng $_POST['totalmoney'] vì client có thể
                        // tự ý sửa hidden input để giảm giá sản phẩm.
                        // -----------------------------------------------
                        $subtotal = 0;
                        $items    = [];
                        if (!empty($_SESSION['cart'])) {
                            $products = $store->productsByIdList(array_keys($_SESSION['cart']));
                            foreach ($products as $p) {
                                $qty       = (int) $_SESSION['cart'][$p['id_sp']];
                                
                                // Áp dụng giá Flash Sale nếu còn hiệu lực
                                $gia_ban = $p['gia_sp'];
                                if (!empty($p['flash_sale_end']) && strtotime($p['flash_sale_end']) > time() && !empty($p['flash_sale_price'])) {
                                    $gia_ban = $p['flash_sale_price'];
                                }

                                $subtotal += $gia_ban * $qty;
                                $items[]   = [
                                    'id_sp'    => $p['id_sp'],
                                    'so_luong' => $qty,
                                    'gia_ban'  => $gia_ban,
                                ];
                            }
                        }

                        // Tính giảm giá phía server (không tin POST)
                        $discountAmount = 0;
                        if (isset($_SESSION['discount'])) {
                            $d = $_SESSION['discount'];
                            if ($d['loai'] === 'percentage') {
                                $discountAmount = $subtotal * ($d['gia_tri'] / 100);
                            } else {
                                $discountAmount = (float) $d['gia_tri'];
                            }
                            // Giảm giá không vượt quá tổng tiền
                            $discountAmount = min($discountAmount, $subtotal);
                        }

                        $totalMoney = $subtotal - $discountAmount;

                        $orderData = [
                            'ma_dh'     => $orderId,
                            'id_nd'     => $_SESSION['id_nd'] ?? null,
                            'ten_nn'    => SecurityHelper::sanitize($_POST['tennguoinhan']   ?? ''),
                            'email_nn'  => SecurityHelper::sanitize($_POST['emailnguoinhan'] ?? ''),
                            'sdt_nn'    => SecurityHelper::sanitize($_POST['sdtnguoinhan']   ?? ''),
                            'diachi_nn' => SecurityHelper::sanitize($_POST['diachinguoinhan']?? ''),
                            'ghichu_nn' => SecurityHelper::sanitize($_POST['ghichunguoinhan']?? ''),
                            'tong_tien' => $totalMoney,
                            'giam_gia'  => $discountAmount,
                        ];

                        $dbOrderId = $store->createOrder($orderData, $items);

                        if ($dbOrderId) {
                            $_SESSION[$orderId] = [
                                'tennn'    => $orderData['ten_nn'],
                                'emailnn'  => $orderData['email_nn'],
                                'sdtnn'    => $orderData['sdt_nn'],
                                'diachinn' => $orderData['diachi_nn'],
                                'ghichunn' => $orderData['ghichu_nn'],
                                'tongtien' => $orderData['tong_tien'],
                            ];
                            $_SESSION['ma_don_hang'] = $orderId;
                            // Không xóa giỏ hàng tại đây.
                            // Chỉ xóa sau khi thanh toán thành công để người dùng
                            // quay lại bước giỏ hàng không bị mất dữ liệu.

                            header('Location: index.php?action=thanhtoan');
                            exit;
                        } else {
                            die('Lỗi khi lưu đơn hàng vào cơ sở dữ liệu.');
                        }
                    }
                    break;
            }
        }

        $data_cart = [];
        if (!empty($_SESSION['cart'])) {
            $store = new StoreModel();
            $data_cart = $store->productsByIdList(array_keys($_SESSION['cart']));
        }

        $this->renderLegacy('giohang', [
            'data_cart' => $data_cart,
        ]);
    }
}
