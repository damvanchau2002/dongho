<?php
$pageTitle  = "Đặt hàng thành công - ChronoLux";
$extraHead  = '
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cinzel:wght@600;700&display=swap");
        
        .success-wrapper {
            font-family: "Inter", sans-serif;
            background: #f4f7f6;
            padding: 80px 20px;
            min-height: calc(100vh - 200px);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .success-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            max-width: 650px;
            width: 100%;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            text-align: center;
            padding: 50px 40px;
        }
        
        .success-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 45px;
            margin: 0 auto 25px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        
        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .success-card h2 {
            font-family: "Cinzel", serif;
            font-size: 32px;
            font-weight: 700;
            color: #0f2942;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }
        
        .success-card p.subtitle {
            color: #64748b;
            font-size: 16px;
            margin-bottom: 35px;
            line-height: 1.6;
        }
        
        .order-details-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 30px;
            border: 1px dashed #cbd5e1;
            text-align: left;
            margin-bottom: 40px;
        }
        
        .order-details-box h3 {
            font-size: 18px;
            font-weight: 700;
            color: #0f2942;
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 15px;
        }
        
        .detail-row:last-child {
            margin-bottom: 0;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
        }
        
        .detail-row .label {
            color: #64748b;
            font-weight: 500;
        }
        
        .detail-row .value {
            color: #1e293b;
            font-weight: 600;
        }
        
        .detail-row.total .label {
            font-size: 18px;
            font-weight: 700;
            color: #0f2942;
        }
        
        .detail-row.total .value {
            font-size: 24px;
            font-weight: 800;
            color: #e53e3e;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .btn-main {
            background: linear-gradient(135deg, #0f2942, #1a4a7a);
            color: #fff;
            padding: 14px 30px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(15, 41, 66, 0.15);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(15, 41, 66, 0.25);
            color: #fff;
            text-decoration: none;
        }
        
        .btn-outline {
            background: #fff;
            color: #0f2942;
            border: 2px solid #e2e8f0;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-outline:hover {
            border-color: #0f2942;
            background: #f8fafc;
            color: #0f2942;
            text-decoration: none;
        }
        
        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }
            .success-card {
                padding: 40px 20px;
            }
        }
    </style>
';
ob_start();
?>

    <div class="success-wrapper">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2>Đặt Hàng Thành Công!</h2>
            <p class="subtitle">Cảm ơn bạn đã lựa chọn ChronoLux. Đơn hàng của bạn đã được tiếp nhận và đang trong quá trình xử lý.</p>
            
            <?php if (isset($_SESSION['last_order'])): 
                $order = $_SESSION['last_order'];
            ?>
                <div class="order-details-box">
                    <h3><i class="fas fa-box-open text-muted"></i> Chi tiết đơn hàng</h3>
                    <div class="detail-row">
                        <span class="label">Mã đơn hàng:</span>
                        <span class="value">#<?= htmlspecialchars($order['ma_dh']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Người nhận:</span>
                        <span class="value"><?= htmlspecialchars($order['tennn']) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Email liên hệ:</span>
                        <span class="value"><?= htmlspecialchars($order['emailnn']) ?></span>
                    </div>
                    <div class="detail-row total">
                        <span class="label">Tổng thanh toán:</span>
                        <span class="value"><?= number_format($order['tongtien'], 0, ",", ".") ?> đ</span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="index.php" class="btn-main">
                    <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                </a>
                <a href="index.php?action=thongtintaikhoan#don-hang" class="btn-outline">
                    <i class="fas fa-clipboard-list"></i> Theo dõi đơn hàng
                </a>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>
