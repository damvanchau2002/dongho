<?php

class ChatbotController extends Controller
{
    public function ask()
    {
        // Nhận dữ liệu JSON từ frontend
        $data = json_decode(file_get_contents('php://input'), true);
        $userMessage = isset($data['message']) ? trim($data['message']) : '';

        if (empty($userMessage)) {
            echo json_encode(['reply' => 'Vui lòng nhập tin nhắn.']);
            exit;
        }

        // Khởi tạo hoặc lấy lịch sử chat từ Session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['chat_history'])) {
            $_SESSION['chat_history'] = [];
        }

        // 1. Lấy danh sách sản phẩm làm ngữ cảnh (Context)
        $store = new StoreModel();
        $products = $store->products();
        
        $context = "DANH MỤC SẢN PHẨM HIỆN CÓ:\n";
        if (is_array($products)) {
            foreach ($products as $p) {
                $link = "index.php?action=chitietsanpham&id=" . $p['id_sp'];
                $context .= "- Tên: " . $p['ten_sp'] . " | Giá: " . number_format($p['gia_sp'], 0, ',', '.') . " VNĐ | Link: [" . $p['ten_sp'] . "](" . $link . ") | Hãng: " . $p['ten_loaisp'] . " | Mô tả ngắn: " . mb_substr($p['mota_sp'], 0, 50) . "...\n";
            }
        }
        
        $userName = isset($_SESSION['tennd']) ? $_SESSION['tennd'] : 'Quý khách';

        $systemPrompt = "Bạn là trợ lý ảo cao cấp của cửa hàng đồng hồ chính hãng ChronoLux. Khách hàng hiện tại tên là: $userName.
Vai trò của bạn là tư vấn viên chuyên nghiệp, lịch sự, hiểu biết sâu rộng về đồng hồ cao cấp.

[QUY TẮC QUAN TRỌNG]:
1. Chỉ tư vấn và bán các sản phẩm có trong DANH MỤC SẢN PHẨM HIỆN CÓ bên dưới. Tuyệt đối không bịa đặt sản phẩm hoặc giá cả.
2. Khi tư vấn một sản phẩm cụ thể, LUÔN LUÔN chèn đường link (đã được cung cấp ở cột Link) để khách hàng có thể bấm vào xem chi tiết ngay lập tức. Cú pháp: [Tên Sản Phẩm](Link).
3. Trả lời ngắn gọn, súc tích (dưới 4-5 câu), dùng format Markdown để làm nổi bật thông tin quan trọng.
4. Luôn chào hoặc xưng hô với khách hàng bằng tên của họ (Anh/chị $userName) để tạo sự thân thiện, cá nhân hóa.
5. Thêm 1-2 emoji phù hợp để câu chat sinh động nhưng không lạm dụng.
6. Hướng dẫn khách click vào link bạn cung cấp để xem chi tiết hoặc thêm vào giỏ hàng.

[THÔNG TIN CHUNG CỦA CHRONOLUX]:
- Bảo hành: Chính hãng 5 năm cho mọi dòng đồng hồ.
- Giao hàng: Miễn phí vận chuyển toàn quốc (Freeship), nhận hàng từ 1-3 ngày.
- Thanh toán: Nhận tiền mặt khi giao hàng (COD).
- Đổi trả: Trong vòng 7 ngày nếu lỗi từ nhà sản xuất.

" . $context;

        // Lưu tin nhắn của người dùng vào lịch sử
        $_SESSION['chat_history'][] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        // Giới hạn bộ nhớ chat: Chỉ giữ lại 10 tin nhắn gần nhất để tránh tràn token
        if (count($_SESSION['chat_history']) > 10) {
            $_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -10);
        }

        // 2. Gửi tới Gemini kèm lịch sử chat
        $gemini = new GeminiHelper();
        $reply = $gemini->askGemini($systemPrompt, $_SESSION['chat_history']);

        // Lưu câu trả lời của AI vào lịch sử
        $_SESSION['chat_history'][] = [
            'role' => 'model',
            'content' => $reply
        ];

        // 3. Trả về kết quả
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['reply' => $reply]);
        exit;
    }
}
