<?php

class LiveChatController extends Controller
{
    private $db;

    public function __construct()
    {
        $baseModel = new BaseModel();
        $this->db = $baseModel->connect;
        
        // Cần đảm bảo session hoạt động cho authentication
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * API Gửi tin nhắn
     */
    public function sendMessage()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $data = json_decode(file_get_contents('php://input'), true);
        $message = isset($data['message']) ? trim($data['message']) : '';
        $targetUserId = isset($data['user_id']) ? (int)$data['user_id'] : 0; // Admin gửi thì cần biết gửi cho ai

        if (empty($message)) {
            echo json_encode(['success' => false, 'error' => 'Tin nhắn trống']);
            return;
        }

        // Kiểm tra quyền
        $isAdmin = isset($_SESSION['quyennd']) && $_SESSION['quyennd'] == 1;
        $isUser = isset($_SESSION['id_nd']);

        if (!$isAdmin && !$isUser) {
            echo json_encode(['success' => false, 'error' => 'Vui lòng đăng nhập để chat']);
            return;
        }

        // Kiểm tra xem admin đang chat từ bảng điều khiển hay từ giao diện người dùng
        $isAdminPanelRequest = $isAdmin && $targetUserId > 0;

        if ($isAdminPanelRequest) {
            $userId = $targetUserId;
            $senderType = 'admin';
        } else {
            $userId = isset($_SESSION['id_nd']) ? $_SESSION['id_nd'] : 0;
            $senderType = 'user';
        }

        if ($userId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Không xác định được người nhận']);
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO tin_nhan (user_id, sender_type, message) VALUES (?, ?, ?)");
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Lỗi prepare: ' . $this->db->error]);
            return;
        }
        
        $stmt->bind_param("iss", $userId, $senderType, $message);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Lỗi lưu tin nhắn: ' . $stmt->error]);
        }
        $stmt->close();
    }

    /**
     * API Lấy tin nhắn giữa admin và 1 user
     */
    public function getMessages()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $isAdmin = isset($_SESSION['quyennd']) && $_SESSION['quyennd'] == 1;
        $isUser = isset($_SESSION['id_nd']);

        if (!$isAdmin && !$isUser) {
            echo json_encode(['success' => false, 'error' => 'Vui lòng đăng nhập']);
            return;
        }

        // Lấy userId: Nếu là admin thì lấy từ GET, nếu là user thì lấy từ session
        $isAdminPanelRequest = $isAdmin && isset($_GET['user_id']) && (int)$_GET['user_id'] > 0;
        $userId = $isAdminPanelRequest ? (int)$_GET['user_id'] : (isset($_SESSION['id_nd']) ? $_SESSION['id_nd'] : 0);

        if ($userId <= 0) {
            echo json_encode(['success' => false, 'error' => 'Chưa chọn khách hàng']);
            return;
        }

        // Đánh dấu đã đọc nếu người nhận đang xem
        if ($isAdminPanelRequest) {
            $stmt = $this->db->prepare("UPDATE tin_nhan SET is_read = 1 WHERE user_id = ? AND sender_type = 'user' AND is_read = 0");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        } else {
            $stmt = $this->db->prepare("UPDATE tin_nhan SET is_read = 1 WHERE user_id = ? AND sender_type = 'admin' AND is_read = 0");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
        }

        $stmt = $this->db->prepare("SELECT id, sender_type, message, DATE_FORMAT(created_at, '%H:%i') as time FROM tin_nhan WHERE user_id = ? ORDER BY id ASC");
        if (!$stmt) {
            echo json_encode(['success' => false, 'error' => 'Lỗi prepare: ' . $this->db->error]);
            return;
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();

        $messages = [];
        while ($row = $res->fetch_assoc()) {
            $messages[] = $row;
        }

        echo json_encode(['success' => true, 'messages' => $messages]);
        $stmt->close();
    }

    /**
     * API (Dành cho Admin) Lấy danh sách khách hàng đang chat
     */
    public function getInboxUsers()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        $isAdmin = isset($_SESSION['quyennd']) && $_SESSION['quyennd'] == 1;
        if (!$isAdmin) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            return;
        }

        $sql = "
            SELECT u.id_nd, u.ten_nd, 
                   (SELECT message FROM tin_nhan t2 WHERE t2.user_id = u.id_nd ORDER BY t2.id DESC LIMIT 1) as last_message,
                   (SELECT created_at FROM tin_nhan t3 WHERE t3.user_id = u.id_nd ORDER BY t3.id DESC LIMIT 1) as last_time,
                   (SELECT COUNT(*) FROM tin_nhan t4 WHERE t4.user_id = u.id_nd AND t4.sender_type = 'user' AND t4.is_read = 0) as unread_count
            FROM quanlynguoidung u
            JOIN tin_nhan t ON u.id_nd = t.user_id
            GROUP BY u.id_nd
            ORDER BY last_time DESC
        ";

        $res = $this->db->query($sql);
        $users = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                // Xử lý thời gian cho đẹp
                $time = strtotime($row['last_time']);
                if (date('Y-m-d') == date('Y-m-d', $time)) {
                    $row['last_time_formatted'] = date('H:i', $time);
                } else {
                    $row['last_time_formatted'] = date('d/m/Y', $time);
                }
                $users[] = $row;
            }
        }

        echo json_encode(['success' => true, 'users' => $users]);
    }
}
