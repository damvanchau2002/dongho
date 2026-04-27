<?php

/**
 * Helper giao tiếp với Google Gemini API
 */
class GeminiHelper
{
    private $apiKey;
    private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = defined('GEMINI_API_KEY') ? GEMINI_API_KEY : '';
    }

    /**
     * Gửi câu hỏi tới Gemini kèm lịch sử chat
     *
     * @param string $systemPrompt Ngữ cảnh/vai trò của AI
     * @param array  $chatHistory  Mảng lịch sử chat dạng [['role' => 'user'/'model', 'content' => 'text']]
     * @return string Trả về nội dung trả lời từ AI
     */
    public function askGemini($systemPrompt, $chatHistory)
    {
        if (empty($this->apiKey)) {
            return "Xin lỗi, tôi chưa được cấu hình API Key để hoạt động. Vui lòng liên hệ quản trị viên cập nhật cấu hình GEMINI_API_KEY trong file database.php.";
        }

        $url = $this->apiUrl . '?key=' . $this->apiKey;

        $contents = [];
        foreach ($chatHistory as $msg) {
            $contents[] = [
                'role' => $msg['role'],
                'parts' => [
                    ['text' => $msg['content']]
                ]
            ];
        }

        // Cấu trúc payload theo format của Gemini 1.5
        $data = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemPrompt]
                ]
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 800,
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Nếu chạy trên localhost Laragon có thể bị lỗi SSL, bỏ qua kiểm tra SSL tạm thời
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return "Hệ thống AI đang bảo trì, vui lòng thử lại sau. Lỗi kết nối: " . $error;
        }

        $result = json_decode($response, true);

        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        // Nếu có lỗi trả về từ API Google
        if (isset($result['error'])) {
            $errorMsg = $result['error']['message'] ?? '';
            // Nếu lỗi do quá tải (high demand, quota, v.v...)
            if (stripos($errorMsg, 'high demand') !== false || stripos($errorMsg, 'overloaded') !== false || stripos($errorMsg, 'quota') !== false) {
                return "Dạ hiện tại hệ thống tư vấn đang có quá nhiều khách hàng truy cập cùng lúc nên hơi quá tải. Anh/chị vui lòng đợi một chút rồi nhắn lại giúp em nha! 🕒";
            }
            return "Dạ hệ thống AI đang gặp chút sự cố kỹ thuật tạm thời. Anh/chị vui lòng thử lại sau ít phút nhé! 🙏";
        }

        return "Xin lỗi anh/chị, em chưa nhận được thông tin đầy đủ để phản hồi. Anh/chị có thể nói rõ hơn không ạ?";
    }
}
