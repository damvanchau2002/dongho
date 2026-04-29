<?php
$pageTitle = "Thanh toán - ChronoLux";
$extraHead = '
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cinzel:wght@600;700&display=swap");
        
        .checkout-wrapper {
            font-family: "Inter", sans-serif;
            background: #f4f7f6;
            padding: 60px 20px;
            min-height: calc(100vh - 200px);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .checkout-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.06);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        .checkout-header {
            background: linear-gradient(135deg, #0f2942, #1a4a7a);
            padding: 30px;
            text-align: center;
            color: #fff;
        }
        
        .checkout-header h2 {
            font-family: "Cinzel", serif;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
            color: #d4af37;
        }
        
        .checkout-header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #cbd5e1;
        }
        
        .checkout-body {
            padding: 40px 30px;
        }
        
        .order-summary {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        
        .order-summary h3 {
            font-size: 16px;
            font-weight: 700;
            color: #0f2942;
            margin-top: 0;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #cbd5e1;
            font-size: 15px;
        }
        
        .summary-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .summary-item .label {
            color: #64748b;
            font-weight: 500;
        }
        
        .summary-item .value {
            color: #1e293b;
            font-weight: 600;
        }
        
        .summary-item.total {
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            border-bottom: none;
        }
        
        .summary-item.total .label {
            font-size: 18px;
            font-weight: 700;
            color: #0f2942;
        }
        
        .summary-item.total .value {
            font-size: 24px;
            font-weight: 800;
            color: #e53e3e;
        }
        
        .payment-methods {
            margin-bottom: 35px;
        }
        
        .payment-methods h3 {
            font-size: 16px;
            font-weight: 700;
            color: #0f2942;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .pm-option {
            position: relative;
            display: block;
            margin-bottom: 15px;
        }
        
        .pm-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        
        .pm-card {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .pm-card:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        
        .pm-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 15px;
            font-size: 20px;
            flex-shrink: 0;
        }
        
        .pm-icon.cod { background: #fef3c7; color: #d97706; }
        .pm-icon.momo { background: #fce7f3; color: #db2777; }
        .pm-icon.vnpay { background: #e0f2fe; color: #0284c7; }
        
        .pm-info {
            flex-grow: 1;
        }
        
        .pm-title {
            font-weight: 600;
            font-size: 15px;
            color: #1e293b;
            margin-bottom: 2px;
            display: block;
        }
        
        .pm-desc {
            font-size: 12px;
            color: #64748b;
            display: block;
        }
        
        .pm-check {
            width: 22px;
            height: 22px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 0.2s;
        }
        
        .pm-check::after {
            content: "";
            width: 10px;
            height: 10px;
            background: #d4af37;
            border-radius: 50%;
            opacity: 0;
            transform: scale(0);
            transition: 0.2s;
        }
        
        .pm-option input[type="radio"]:checked + .pm-card {
            border-color: #d4af37;
            background: #fffbeb;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.15);
        }
        
        .pm-option input[type="radio"]:checked + .pm-card .pm-check {
            border-color: #d4af37;
        }
        
        .pm-option input[type="radio"]:checked + .pm-card .pm-check::after {
            opacity: 1;
            transform: scale(1);
        }
        
        .btn-confirm {
            width: 100%;
            background: linear-gradient(135deg, #0f2942, #1a4a7a);
            color: #fff;
            border: none;
            padding: 16px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 20px rgba(15, 41, 66, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        
        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(15, 41, 66, 0.3);
        }
        
        .btn-back {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            margin-top: 20px;
            transition: 0.2s;
        }
        
        .btn-back:hover {
            color: #0f2942;
            text-decoration: none;
        }
        
        .alert-msg {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            color: #b91c1c;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-container {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            max-width: 500px;
            margin: 60px auto;
        }
        
        .error-container i {
            font-size: 60px;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
        .error-container h3 {
            font-size: 20px;
            color: #0f2942;
            margin-bottom: 10px;
        }
        
        .error-container p {
            color: #64748b;
            margin-bottom: 25px;
        }
    </style>
';
ob_start();
?>

    <?php
    if (isset($_SESSION['ma_don_hang'])) {
        $ma_don_hang = $_SESSION['ma_don_hang'];

        if (isset($_SESSION[$ma_don_hang]['tennn'], $_SESSION[$ma_don_hang]['emailnn'], $_SESSION[$ma_don_hang]['sdtnn'], $_SESSION[$ma_don_hang]['diachinn'], $_SESSION[$ma_don_hang]['ghichunn'])) {
            $tennn = $_SESSION[$ma_don_hang]['tennn'];
            $emailnn = $_SESSION[$ma_don_hang]['emailnn'];
            $sdtnn = $_SESSION[$ma_don_hang]['sdtnn'];
            $diachinn = $_SESSION[$ma_don_hang]['diachinn'];
            $ghichunn = $_SESSION[$ma_don_hang]['ghichunn'];
            $tongtien = $_SESSION[$ma_don_hang]['tongtien'];

            // Construct structured address if codes exist
            $store = new StoreModel();
            if (!empty($_SESSION[$ma_don_hang]['province_code']) && !empty($_SESSION[$ma_don_hang]['ward_code'])) {
                $p = $store->getProvinceByCode($_SESSION[$ma_don_hang]['province_code']);
                $w = $store->getWardByCode($_SESSION[$ma_don_hang]['ward_code']);
                if ($p && $w) {
                    $street = $_SESSION[$ma_don_hang]['street_part'] ?? '';
                    $diachinn = ($street ? $street . ', ' : '') . $w['name'] . ', ' . $p['name'];
                }
            }
    ?>

            <div class="checkout-wrapper">
                <div class="checkout-card">
                    <div class="checkout-header">
                        <h2>ChronoLux</h2>
                        <p>Bảo mật & an toàn - Hoàn tất thanh toán</p>
                    </div>
                    
                    <div class="checkout-body">
                        <?php if (!empty($_GET['error'])) { ?>
                            <div class="alert-msg">
                                <i class="fas fa-exclamation-circle"></i>
                                <span><?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        <?php } ?>
                        
                        <div class="order-summary">
                            <h3><i class="fas fa-receipt"></i> Tóm tắt đơn hàng</h3>
                            <div class="summary-item">
                                <span class="label">Mã đơn hàng</span>
                                <span class="value">#<?=$ma_don_hang?></span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Người nhận</span>
                                <span class="value"><?=$tennn?></span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Số điện thoại</span>
                                <span class="value"><?=$sdtnn?></span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Địa chỉ giao hàng</span>
                                <span class="value"><?=$diachinn?></span>
                            </div>
                            <div class="summary-item total">
                                <span class="label">Tổng thanh toán</span>
                                <span class="value"><?= number_format($tongtien, 0, ",", ".") ?> đ</span>
                            </div>
                        </div>
                        
                        <form action="index.php?action=xacnhanthanhtoan" method="post" onsubmit="return showLoading(this);">
                            <input type="hidden" name="ma_dh" value="<?=$ma_don_hang?>">
                            
                            <div class="payment-methods">
                                <h3><i class="fas fa-wallet"></i> Chọn phương thức thanh toán</h3>
                                
                                <label class="pm-option">
                                    <input type="radio" name="phuongthucthanhtoan" value="COD" checked>
                                    <div class="pm-card">
                                        <div class="pm-icon cod"><i class="fas fa-hand-holding-usd"></i></div>
                                        <div class="pm-info">
                                            <span class="pm-title">Thanh toán khi nhận hàng (COD)</span>
                                            <span class="pm-desc">Thanh toán bằng tiền mặt cho người giao hàng</span>
                                        </div>
                                        <div class="pm-check"></div>
                                    </div>
                                </label>
                                
                                <label class="pm-option">
                                    <input type="radio" name="phuongthucthanhtoan" value="Momo">
                                    <div class="pm-card">
                                        <div class="pm-icon momo"><i class="fas fa-qrcode"></i></div>
                                        <div class="pm-info">
                                            <span class="pm-title">Thanh toán qua Ví MoMo</span>
                                            <span class="pm-desc">Mở ứng dụng MoMo và quét mã QR</span>
                                        </div>
                                        <div class="pm-check"></div>
                                    </div>
                                </label>
                                
                                <label class="pm-option">
                                    <input type="radio" name="phuongthucthanhtoan" value="VNPay">
                                    <div class="pm-card">
                                        <div class="pm-icon vnpay"><i class="fas fa-credit-card"></i></div>
                                        <div class="pm-info">
                                            <span class="pm-title">Thanh toán qua VNPay</span>
                                            <span class="pm-desc">Hỗ trợ ATM, Internet Banking và thẻ tín dụng</span>
                                        </div>
                                        <div class="pm-check"></div>
                                    </div>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn-confirm">Xác Nhận Đặt Hàng <i class="fas fa-check-circle"></i></button>
                            <a href="index.php?action=giohang" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại giỏ hàng</a>
                        </form>

                        <script>
                        function showLoading(form) {
                            const btn = form.querySelector('.btn-confirm');
                            btn.disabled = true;
                            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                            btn.style.opacity = '0.8';
                            btn.style.cursor = 'not-allowed';
                            return true;
                        }
                        </script>
                    </div>
                </div>
            </div>

    <?php
        } else {
            echo '<div class="error-container">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <h3>Không tìm thấy thông tin</h3>
                    <p>Không có thông tin địa chỉ giao hàng cho mã đơn hàng: ' . htmlspecialchars($ma_don_hang) . '.</p>
                    <a href="index.php?action=giohang" class="btn-gold">Về giỏ hàng</a>
                  </div>';
        }
    } else {
        echo '<div class="error-container">
                <i class="fas fa-shopping-cart"></i>
                <h3>Đơn hàng không tồn tại</h3>
                <p>Không tìm thấy thông tin đơn hàng hiện tại của bạn.</p>
                <a href="index.php?action=giohang" class="btn-gold">Về giỏ hàng</a>
              </div>';
    }
    ?>
<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>