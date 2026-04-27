<?php

class CheckoutController extends Controller
{
    // ----------------------------------------------------------------
    // Helper: gửi HTTP POST bằng cURL
    // Được tách thành private static để tránh fatal redeclare
    // khi method được gọi nhiều lần trong cùng một request.
    // ----------------------------------------------------------------
    private static function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data),
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        // Localhost sandbox: bỏ qua SSL (chỉ dùng cho môi trường test)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return json_encode(['message' => 'cURL Error: ' . $error]);
        }
        curl_close($ch);
        return $result;
    }

    public function index()
    {
        $this->renderLegacy('thanhtoan');
    }

    public function confirm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_dh = $_POST['ma_dh'] ?? '';
            $pttt  = $_POST['phuongthucthanhtoan'] ?? 'COD';

            if (isset($_SESSION[$ma_dh])) {
                if ($pttt === 'Momo') {
                    // FIX: lấy số tiền từ session (server-side), ép kiểu int (Momo yêu cầu số nguyên)
                    $amount = (int) $_SESSION[$ma_dh]['tongtien'];

                    $minAmount = defined('MOMO_MIN_AMOUNT') ? (int) MOMO_MIN_AMOUNT : 10000;
                    $maxAmount = defined('MOMO_MAX_AMOUNT') ? (int) MOMO_MAX_AMOUNT : 50000000;
                    if ($amount < $minAmount || $amount > $maxAmount) {
                        $formattedAmount = number_format($amount, 0, ',', '.');
                        $formattedMin    = number_format($minAmount, 0, ',', '.');
                        $formattedMax    = number_format($maxAmount, 0, ',', '.');
                        $error = "Momo chỉ hỗ trợ giao dịch từ {$formattedMin} đến {$formattedMax} VND. Đơn hiện tại: {$formattedAmount} VND.";
                        header('Location: index.php?action=thanhtoan&error=' . urlencode($error));
                        exit;
                    }

                    $this->confirm_momo($ma_dh, $amount);
                    exit;
                }

                $store = new StoreModel();
                $store->updatePaymentMethod($ma_dh, $pttt);

                $_SESSION['last_order']          = $_SESSION[$ma_dh];
                $_SESSION['last_order']['ma_dh']  = $ma_dh;
                $_SESSION['last_order']['pttt']   = $pttt;

                // ── Gửi email xác nhận đặt hàng ──
                $orderData = $_SESSION['last_order'];
                $toEmail   = $orderData['emailnn'] ?? '';
                $toName    = $orderData['tennn']   ?? '';
                if (!empty($toEmail)) {
                    MailHelper::sendOrderConfirmation($toEmail, $toName, $orderData);
                }

                // Dọn session mua hàng sau khi xác nhận thanh toán thành công
                unset($_SESSION['cart']);
                unset($_SESSION['discount']);
                unset($_SESSION[$ma_dh]);
                unset($_SESSION['ma_don_hang']);

                header('Location: index.php?action=muahangthanhcong');
                exit;
            }
        }
        header('Location: index.php');
        exit;
    }

    public function confirm_momo($ma_dh, $amount)
    {
        // Lấy credentials từ config (không hardcode nữa)
        $endpoint    = MOMO_ENDPOINT;
        $partnerCode = MOMO_PARTNER_CODE;
        $accessKey   = MOMO_ACCESS_KEY;
        $secretKey   = MOMO_SECRET_KEY;

        $orderInfo   = 'Thanh toán đơn hàng #' . $ma_dh;
        $orderId     = $ma_dh . '_' . time(); // Đảm bảo unique mỗi request

        $protocol    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                        || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $base_url    = $protocol . $_SERVER['HTTP_HOST']
                       . dirname($_SERVER['PHP_SELF']) . '/';

        $redirectUrl = $base_url . 'index.php?action=momo_post';
        $ipnUrl      = $base_url . 'index.php?action=momo_ipn';
        $extraData   = '';
        $requestId   = (string) time();
        $requestType = 'payWithATM';

        $rawHash = 'accessKey='   . $accessKey
                 . '&amount='     . $amount
                 . '&extraData='  . $extraData
                 . '&ipnUrl='     . $ipnUrl
                 . '&orderId='    . $orderId
                 . '&orderInfo='  . $orderInfo
                 . '&partnerCode='. $partnerCode
                 . '&redirectUrl='. $redirectUrl
                 . '&requestId='  . $requestId
                 . '&requestType='. $requestType;

        $signature = hash_hmac('sha256', $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => 'Test',
            'storeId'     => 'MomoTestStore',
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
        ];

        $result     = self::execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            header('Location: ' . $jsonResult['payUrl']);
        } else {
            echo '<h3>Lỗi kết nối Momo:</h3><pre>';
            print_r($jsonResult);
            echo "\n\nDữ liệu gửi đi:\n";
            print_r($data);
            echo '</pre>';
        }
        exit;
    }

    public function momo_post()
    {
        // ---------------------------------------------------------------
        // FIX: Verify HMAC-SHA256 signature trước xử lý bất kỳ dữ liệu nào.
        // Nếu không verify, kẻ xấu có thể giả mạo callback để “thành công”
        // mà không thanh toán thật.
        // ---------------------------------------------------------------
        if (!$this->verifyMomoSignature($_GET)) {
            http_response_code(400);
            header('Location: index.php?action=thanhtoan&error=Signature+kh%C3%B4ng+h%E1%BB%A3p+l%E1%BB%87.');
            exit;
        }

        if (isset($_GET['resultCode']) && (int) $_GET['resultCode'] === 0) {
            $customer_id    = $_SESSION['id_nd'] ?? null;
            $link_data_json = json_encode($_GET);

            $store = new StoreModel();
            $store->storeMomoInfo($customer_id, 1, $link_data_json);

            // Lấy lại ma_dh từ orderId (format: ma_dh_timestamp)
            $orderIdMomo = $_GET['orderId'] ?? '';
            $parts       = explode('_', $orderIdMomo);
            $ma_dh       = $parts[0];

            // Đồng bộ phương thức thanh toán vào DB để không bị giữ mặc định COD
            if (!empty($ma_dh)) {
                $store->updatePaymentMethod($ma_dh, 'Momo');
            }

            if (isset($_SESSION[$ma_dh])) {
                $_SESSION['last_order']         = $_SESSION[$ma_dh];
                $_SESSION['last_order']['ma_dh'] = $ma_dh;
                $_SESSION['last_order']['pttt']  = 'Momo';

                // ── Gửi email xác nhận đặt hàng (Momo) ──
                $orderData = $_SESSION['last_order'];
                $toEmail   = $orderData['emailnn'] ?? '';
                $toName    = $orderData['tennn']   ?? '';
                if (!empty($toEmail)) {
                    MailHelper::sendOrderConfirmation($toEmail, $toName, $orderData);
                }

                // Dọn session mua hàng sau khi thanh toán Momo thành công
                unset($_SESSION['cart']);
                unset($_SESSION['discount']);
                unset($_SESSION[$ma_dh]);
                unset($_SESSION['ma_don_hang']);
            }

            header('Location: index.php?action=muahangthanhcong');
            exit;
        }

        header('Location: index.php?action=thanhtoan&error=L%E1%BB%97i+trong+qu%C3%A1+tr%C3%ACnh+thanh+to%C3%A1n+Momo.');
        exit;
    }

    // -----------------------------------------------------------------
    // Verify HMAC-SHA256 signature của Momo callback/redirect
    // Tham khảo: https://developers.momo.vn/v3/docs/payment/api/wallet
    // -----------------------------------------------------------------
    private function verifyMomoSignature(array $params): bool
    {
        $secretKey = MOMO_SECRET_KEY;
        $accessKey = MOMO_ACCESS_KEY;

        // Lấy signature từ params
        $receivedSig = $params['signature'] ?? '';

        // Xây dựng lại rawHash theo đúng thứ tự Momo quy định cho redirect/IPN
        $rawHash = 'accessKey='    . $accessKey
                 . '&amount='      . ($params['amount']      ?? '')
                 . '&extraData='   . ($params['extraData']   ?? '')
                 . '&message='     . ($params['message']     ?? '')
                 . '&orderId='     . ($params['orderId']     ?? '')
                 . '&orderInfo='   . ($params['orderInfo']   ?? '')
                 . '&orderType='   . ($params['orderType']   ?? '')
                 . '&partnerCode=' . ($params['partnerCode'] ?? '')
                 . '&payType='     . ($params['payType']     ?? '')
                 . '&requestId='   . ($params['requestId']   ?? '')
                 . '&responseTime='. ($params['responseTime']?? '')
                 . '&resultCode='  . ($params['resultCode']  ?? '')
                 . '&transId='     . ($params['transId']     ?? '');

        $expectedSig = hash_hmac('sha256', $rawHash, $secretKey);

        return hash_equals($expectedSig, $receivedSig);
    }

    public function success()
    {
        $this->renderLegacy('success');
    }
}
