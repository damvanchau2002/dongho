<?php

class AuthController extends Controller
{
    public function account()
    {
        $store = new StoreModel();

        if (isset($_POST['nutdangnhap'])) {
            // CSRF check
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }

            $username = SecurityHelper::sanitize($_POST['tdn'] ?? '');
            $password = $_POST['mk'] ?? '';

            if (!empty($username) && !empty($password)) {
                $userData = $store->loginUser($username);
                if ($userData !== 0) {
                    $stored = $userData[0]['matkhau_nd'];
                    $isValid = SecurityHelper::verifyPassword($password, $stored);

                    // Hỗ trợ dữ liệu cũ lưu plaintext
                    if (!$isValid && hash_equals((string)$stored, (string)$password)) {
                        $isValid = true;
                        $newHash = SecurityHelper::hashPassword($password);
                        if (!empty($userData[0]['id_nd'])) {
                            $store->updateUserPassword((int)$userData[0]['id_nd'], $newHash);
                        }
                    }

                    if ($isValid) {
                        $_SESSION['tennd'] = $username;
                        $_SESSION['id_nd'] = $userData[0]['id_nd'];
                        $_SESSION['quyennd'] = $userData[0]['quyen_nd'];
                        header('Location: index.php');
                        exit;
                    }
                }
                echo "<script>alert('Đăng nhập thất bại.')</script>";
            }
        }

        if (isset($_POST['google_credential'])) {
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }

            $credential = $_POST['google_credential'];
            $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $credential;
            
            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'GET',
                    'ignore_errors' => true
                ]
            ];
            $context  = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);
            
            if ($response) {
                $payload = json_decode($response, true);
                
                if (isset($payload['email']) && isset($payload['aud'])) {
                    // Check aud matches client id
                    if ($payload['aud'] === '103291429470-2pcdu1gciuj8gtot37urae3t1nta587c.apps.googleusercontent.com') {
                        $email = $payload['email'];
                        $name = $payload['name'] ?? 'Google User';
                        
                        $userData = $store->getUserByEmail($email);
                        if ($userData !== 0 && !empty($userData)) {
                            // User exists, log them in
                            $_SESSION['tennd'] = $userData[0]['ten_nd'];
                            $_SESSION['id_nd'] = $userData[0]['id_nd'];
                            $_SESSION['quyennd'] = $userData[0]['quyen_nd'];
                            $_SESSION['success'] = "Đăng nhập bằng Google thành công!";
                            header('Location: index.php');
                            exit;
                        } else {
                            // Register new user
                            $randomPassword = bin2hex(random_bytes(8));
                            $passwordHash = SecurityHelper::hashPassword($randomPassword);
                            $baseUsername = preg_replace('/[^a-zA-Z0-9]/', '', strtolower(explode('@', $email)[0]));
                            $username = substr($baseUsername, 0, 15) . rand(100, 999);
                            
                            $store->registerUser($username, $email, $passwordHash);
                            
                            $newUserData = $store->getUserByEmail($email);
                            if ($newUserData !== 0 && !empty($newUserData)) {
                                $_SESSION['tennd'] = $newUserData[0]['ten_nd'];
                                $_SESSION['id_nd'] = $newUserData[0]['id_nd'];
                                $_SESSION['quyennd'] = $newUserData[0]['quyen_nd'];
                                $_SESSION['success'] = "Đăng ký và Đăng nhập Google thành công!";
                                header('Location: index.php');
                                exit;
                            } else {
                                $_SESSION['error'] = 'Lỗi tạo tài khoản Google!';
                            }
                        }
                    } else {
                        $_SESSION['error'] = 'Client ID không hợp lệ!';
                    }
                } else {
                    $_SESSION['error'] = 'Không thể xác thực token từ Google!';
                }
            } else {
                $_SESSION['error'] = 'Lỗi kết nối đến Google!';
            }
        }

        if (isset($_POST['nutdangky'])) {
            // CSRF check
            if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
                die('CSRF token validation failed');
            }

            $username = SecurityHelper::sanitize($_POST['tendk'] ?? '');
            $email = SecurityHelper::sanitize($_POST['emaildk'] ?? '');
            $password = $_POST['mkdk'] ?? '';

            if (
                SecurityHelper::validateUsername($username)
                && SecurityHelper::validateEmail($email)
                && SecurityHelper::validatePasswordSimple($password)
            ) {
                $hashed = SecurityHelper::hashPassword($password);
                if ($store->registerUser($username, $email, $hashed)) {
                    // FIX: lưu id_nd vào session sau đăng ký
                    // để trang thongtintaikhoan load được lịch sử đơn hàng
                    $newUser = $store->loginUser($username);
                    $_SESSION['tennd']   = $username;
                    $_SESSION['quyennd'] = 2;
                    if (!empty($newUser[0]['id_nd'])) {
                        $_SESSION['id_nd'] = $newUser[0]['id_nd'];
                    }
                    header('Location: index.php');
                    exit;
                }
            } else {
                echo "<script>alert('Thông tin đăng ký không hợp lệ.')</script>";
            }
        }

        $this->renderLegacy('taikhoan');
    }

    public function profile()
    {
        if (!isset($_SESSION['tennd'])) {
            header('Location: index.php?action=taikhoan');
            exit;
        }
        
        $store = new StoreModel();
        $userData = $store->loginUser($_SESSION['tennd']);
        
        // Cập nhật lại id_nd vào session nếu bị mất
        if (!isset($_SESSION['id_nd']) && isset($userData[0]['id_nd'])) {
            $_SESSION['id_nd'] = $userData[0]['id_nd'];
        }

        $orders = [];
        if (isset($_SESSION['id_nd'])) {
            $orders = $store->userOrders($_SESSION['id_nd']);
        }
        
        // Truyền dữ liệu sang view thông qua hàm renderLegacy
        $this->renderLegacy('thongtintaikhoan', [
            'user' => $userData[0],
            'orders' => $orders
        ]);
    }

    public function cancelOrder()
    {
        if (!isset($_SESSION['tennd'])) {
            header('Location: index.php?action=taikhoan');
            exit;
        }

        // FIX: Thêm CSRF check — form hủy đơn hàng phải có csrf_token
        if (!SecurityHelper::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF token validation failed');
        }

        $id     = (int)($_POST['id_dh'] ?? 0);
        $reason = SecurityHelper::sanitize($_POST['ly_do_huy'] ?? 'Khác');

        if ($id > 0) {
            $store = new StoreModel();
            $order = $store->orderById($id);
            
            // Chỉ cho phép hủy đơn hàng của chính mình và đang ở trạng thái xử lý (1)
            if ($order && $order['id_nd'] == $_SESSION['id_nd'] && $order['trang_thai'] == 1) {
                $store->cancelOrder($id, $reason);
            }
        }

        header('Location: index.php?action=thongtintaikhoan');
        exit;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /');
        exit;
    }
}
