<?php
/**
 * MailHelper - Gửi email xác nhận đặt hàng qua Gmail SMTP
 * Sử dụng PHPMailer (không cần Composer, đã tích hợp thủ công)
 */

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
    /**
     * Gửi email xác nhận đặt hàng thành công qua Gmail SMTP
     *
     * @param string $toEmail   Email người nhận
     * @param string $toName    Tên người nhận
     * @param array  $order     Thông tin đơn hàng
     * @return bool
     */
    public static function sendOrderConfirmation(string $toEmail, string $toName, array $order): bool
    {
        $mail = new PHPMailer(true);

        try {
            // ── Cấu hình SMTP Gmail ──
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = defined('MAIL_FROM') ? MAIL_FROM : 'damvanchau2002@gmail.com';
            $mail->Password   = defined('MAIL_APP_PASSWORD') ? MAIL_APP_PASSWORD : '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // ── Người gửi / người nhận ──
            $fromEmail = defined('MAIL_FROM')      ? MAIL_FROM      : 'noreply@chronolux.vn';
            $fromName  = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'ChronoLux Watch Store';

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->addReplyTo($fromEmail, $fromName);

            // ── Nội dung email ──
            $mail->isHTML(true);
            $mail->Subject = '✅ Xác nhận đặt hàng thành công #' . ($order['ma_dh'] ?? '') . ' - ChronoLux';
            $mail->Body    = self::buildEmailBody($order);
            $mail->AltBody = self::buildPlainText($order);

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log('MailHelper Error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Gửi email cập nhật trạng thái đơn hàng
     *
     * @param string $toEmail   Email người nhận
     * @param string $toName    Tên người nhận
     * @param array  $order     Thông tin đơn hàng
     * @param int    $newStatus Trạng thái mới
     * @return bool
     */
    public static function sendStatusUpdate(string $toEmail, string $toName, array $order, int $newStatus): bool
    {
        $mail = new PHPMailer(true);

        try {
            // ── Cấu hình SMTP Gmail ──
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = defined('MAIL_FROM') ? MAIL_FROM : 'damvanchau2002@gmail.com';
            $mail->Password   = defined('MAIL_APP_PASSWORD') ? MAIL_APP_PASSWORD : '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // ── Người gửi / người nhận ──
            $fromEmail = defined('MAIL_FROM')      ? MAIL_FROM      : 'noreply@chronolux.vn';
            $fromName  = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'ChronoLux Watch Store';

            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->addReplyTo($fromEmail, $fromName);

            $statusText = '';
            $icon = '🔄';
            switch ($newStatus) {
                case 0: $statusText = 'Đã hủy'; $icon = '❌'; break;
                case 1: $statusText = 'Chờ xử lý'; $icon = '⏳'; break;
                case 2: $statusText = 'Đang giao hàng'; $icon = '🚚'; break;
                case 3: $statusText = 'Hoàn thành'; $icon = '✅'; break;
                default: $statusText = 'Cập nhật trạng thái'; break;
            }

            // ── Nội dung email ──
            $mail->isHTML(true);
            $mail->Subject = $icon . ' Cập nhật trạng thái đơn hàng #' . ($order['ma_dh'] ?? '') . ' - ' . $statusText;
            $mail->Body    = self::buildStatusEmailBody($order, $statusText, $newStatus);
            $mail->AltBody = "Đơn hàng #" . ($order['ma_dh'] ?? '') . " của bạn đã được cập nhật trạng thái: " . $statusText;

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log('MailHelper Error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    // ── Xây dựng nội dung HTML email ──
    private static function buildEmailBody(array $order): string
    {
        $appUrl   = defined('APP_URL') ? APP_URL : 'http://localhost/Website';
        $tongtien = number_format($order['tongtien'] ?? 0, 0, ',', '.');
        $ma_dh    = htmlspecialchars($order['ma_dh']    ?? '');
        $tennn    = htmlspecialchars($order['tennn']    ?? '');
        $emailnn  = htmlspecialchars($order['emailnn']  ?? '');
        $sdtnn    = htmlspecialchars($order['sdtnn']    ?? '');
        $diachinn = htmlspecialchars($order['diachinn'] ?? '');
        $pttt     = htmlspecialchars($order['pttt']     ?? 'COD');

        return <<<HTML
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f7f6;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7f6;padding:40px 20px;">
  <tr><td align="center">
    <table width="620" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.08);border:1px solid #e2e8f0;max-width:100%;">

      <!-- Header -->
      <tr>
        <td style="background:linear-gradient(135deg,#0f2942,#1a4a7a);padding:35px 40px;text-align:center;">
          <p style="margin:0 0 5px;font-size:12px;color:#94a3b8;letter-spacing:2px;text-transform:uppercase;">ĐỒNG HỒ CAO CẤP</p>
          <h1 style="margin:0;font-size:32px;font-weight:800;color:#d4af37;letter-spacing:2px;font-family:Georgia,serif;">CHRONOLUX</h1>
        </td>
      </tr>

      <!-- Success -->
      <tr>
        <td style="padding:40px 40px 20px;text-align:center;">
          <div style="width:80px;height:80px;background:linear-gradient(135deg,#10b981,#059669);border-radius:50%;margin:0 auto 20px;line-height:80px;font-size:40px;color:#fff;">&#10003;</div>
          <h2 style="margin:0 0 10px;font-size:26px;font-weight:800;color:#0f2942;">Đặt hàng thành công!</h2>
          <p style="margin:0;font-size:15px;color:#64748b;line-height:1.6;">Cảm ơn <strong>{$tennn}</strong>! Đơn hàng của bạn đã được tiếp nhận và đang xử lý.</p>
        </td>
      </tr>

      <!-- Order Details -->
      <tr>
        <td style="padding:10px 40px 30px;">
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border-radius:14px;border:1px dashed #cbd5e1;">
            <tr><td style="padding:18px 25px;border-bottom:1px solid #e2e8f0;">
              <strong style="font-size:14px;color:#0f2942;text-transform:uppercase;letter-spacing:0.5px;">📋 Chi tiết đơn hàng</strong>
            </td></tr>
            <tr><td style="padding:20px 25px;">
              <table width="100%" cellpadding="8" cellspacing="0" style="font-size:14px;">
                <tr><td style="color:#64748b;width:45%;">Mã đơn hàng:</td><td style="color:#1e293b;font-weight:700;text-align:right;"><strong>#{$ma_dh}</strong></td></tr>
                <tr style="border-top:1px dashed #e2e8f0;"><td style="color:#64748b;">Người nhận:</td><td style="color:#1e293b;font-weight:600;text-align:right;">{$tennn}</td></tr>
                <tr style="border-top:1px dashed #e2e8f0;"><td style="color:#64748b;">Số điện thoại:</td><td style="color:#1e293b;font-weight:600;text-align:right;">{$sdtnn}</td></tr>
                <tr style="border-top:1px dashed #e2e8f0;"><td style="color:#64748b;">Địa chỉ giao:</td><td style="color:#1e293b;font-weight:600;text-align:right;">{$diachinn}</td></tr>
                <tr style="border-top:1px dashed #e2e8f0;"><td style="color:#64748b;">Thanh toán:</td><td style="color:#1e293b;font-weight:600;text-align:right;">{$pttt}</td></tr>
                <tr style="border-top:2px solid #e2e8f0;">
                  <td style="color:#0f2942;font-size:17px;font-weight:700;padding-top:14px;">Tổng thanh toán:</td>
                  <td style="color:#e53e3e;font-size:22px;font-weight:800;text-align:right;padding-top:14px;">{$tongtien} đ</td>
                </tr>
              </table>
            </td></tr>
          </table>
        </td>
      </tr>

      <!-- CTA -->
      <tr>
        <td style="padding:0 40px 40px;text-align:center;">
          <p style="margin:0 0 25px;font-size:14px;color:#64748b;line-height:1.7;">
            Chúng tôi sẽ liên hệ qua <strong>{$sdtnn}</strong> để xác nhận và thông báo lịch giao hàng.<br>Nếu cần hỗ trợ, vui lòng reply email này.
          </p>
          <a href="{$appUrl}/index.php?action=thongtintaikhoan#don-hang"
             style="display:inline-block;background:linear-gradient(135deg,#0f2942,#1a4a7a);color:#fff;text-decoration:none;padding:14px 32px;border-radius:30px;font-size:15px;font-weight:700;">
            Theo dõi đơn hàng &rarr;
          </a>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#0f1e30;padding:25px 40px;text-align:center;">
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.8;">
            &copy; 2026 ChronoLux Watch Store. Tất cả quyền được bảo lưu.<br>
            Email này được gửi tự động, vui lòng không reply trực tiếp.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
HTML;
    }

    // ── Xây dựng nội dung HTML email cập nhật trạng thái ──
    private static function buildStatusEmailBody(array $order, string $statusText, int $newStatus): string
    {
        $appUrl   = defined('APP_URL') ? APP_URL : 'http://localhost/Website';
        
        // Hỗ trợ cả key session (khi mới đặt hàng) và key DB (khi truy vấn orderById)
        $tongtien_val = $order['tong_tien'] ?? $order['tongtien'] ?? 0;
        $tongtien = number_format($tongtien_val, 0, ',', '.');
        
        $ma_dh    = htmlspecialchars($order['ma_dh'] ?? '');
        $tennn    = htmlspecialchars($order['ten_nguoinhan'] ?? $order['tennn'] ?? '');
        
        $statusColor = '#0f2942';
        $iconHtml = '';
        if ($newStatus == 0) {
            $statusColor = '#e53e3e';
            $iconHtml = '<div style="width:80px;height:80px;background:linear-gradient(135deg,#ef4444,#dc2626);border-radius:50%;margin:0 auto 20px;line-height:80px;font-size:40px;color:#fff;">&#10007;</div>';
        } elseif ($newStatus == 1) {
            $statusColor = '#f59e0b';
            $iconHtml = '<div style="width:80px;height:80px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:50%;margin:0 auto 20px;line-height:80px;font-size:40px;color:#fff;">&#8987;</div>';
        } elseif ($newStatus == 2) {
            $statusColor = '#3b82f6';
            $iconHtml = '<div style="width:80px;height:80px;background:linear-gradient(135deg,#3b82f6,#2563eb);border-radius:50%;margin:0 auto 20px;line-height:80px;font-size:40px;color:#fff;">&#128666;</div>';
        } elseif ($newStatus == 3) {
            $statusColor = '#10b981';
            $iconHtml = '<div style="width:80px;height:80px;background:linear-gradient(135deg,#10b981,#059669);border-radius:50%;margin:0 auto 20px;line-height:80px;font-size:40px;color:#fff;">&#10003;</div>';
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f7f6;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7f6;padding:40px 20px;">
  <tr><td align="center">
    <table width="620" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.08);border:1px solid #e2e8f0;max-width:100%;">

      <!-- Header -->
      <tr>
        <td style="background:linear-gradient(135deg,#0f2942,#1a4a7a);padding:35px 40px;text-align:center;">
          <p style="margin:0 0 5px;font-size:12px;color:#94a3b8;letter-spacing:2px;text-transform:uppercase;">ĐỒNG HỒ CAO CẤP</p>
          <h1 style="margin:0;font-size:32px;font-weight:800;color:#d4af37;letter-spacing:2px;font-family:Georgia,serif;">CHRONOLUX</h1>
        </td>
      </tr>

      <!-- Status -->
      <tr>
        <td style="padding:40px 40px 20px;text-align:center;">
          {$iconHtml}
          <h2 style="margin:0 0 10px;font-size:26px;font-weight:800;color:{$statusColor};">Trạng thái: {$statusText}</h2>
          <p style="margin:0;font-size:15px;color:#64748b;line-height:1.6;">Xin chào <strong>{$tennn}</strong>! Đơn hàng <strong style="color:#0f2942;">#{$ma_dh}</strong> của bạn đã được cập nhật trạng thái mới.</p>
        </td>
      </tr>

      <!-- CTA -->
      <tr>
        <td style="padding:0 40px 40px;text-align:center;">
          <a href="{$appUrl}/index.php?action=thongtintaikhoan#don-hang"
             style="display:inline-block;background:linear-gradient(135deg,#0f2942,#1a4a7a);color:#fff;text-decoration:none;padding:14px 32px;border-radius:30px;font-size:15px;font-weight:700;">
            Kiểm tra đơn hàng &rarr;
          </a>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#0f1e30;padding:25px 40px;text-align:center;">
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.8;">
            &copy; 2026 ChronoLux Watch Store. Tất cả quyền được bảo lưu.<br>
            Email này được gửi tự động, vui lòng không reply trực tiếp.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
HTML;
    }

    // ── Nội dung plain text (fallback) ──
    private static function buildPlainText(array $order): string
    {
        $tongtien = number_format($order['tongtien'] ?? 0, 0, ',', '.');
        return "Xác nhận đặt hàng thành công - ChronoLux\n"
             . "==========================================\n"
             . "Mã đơn hàng : #" . ($order['ma_dh']    ?? '') . "\n"
             . "Người nhận  : "  . ($order['tennn']    ?? '') . "\n"
             . "SĐT         : "  . ($order['sdtnn']    ?? '') . "\n"
             . "Địa chỉ     : "  . ($order['diachinn'] ?? '') . "\n"
             . "Thanh toán  : "  . ($order['pttt']     ?? 'COD') . "\n"
             . "Tổng tiền   : "  . $tongtien . " đ\n"
             . "==========================================\n"
             . "Cảm ơn bạn đã mua sắm tại ChronoLux!";
    }
}


