<?php
$pageTitle = "Giỏ hàng - ChronoLux";
ob_start();

$isLoggedIn = isset($_SESSION['tennd']);

function generate_random_order_id() {
    $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $id = '';
    for ($i = 0; $i < 6; $i++) $id .= $chars[rand(0, strlen($chars)-1)];
    return $id;
}

if (!isset($_SESSION["cart"])) $_SESSION["cart"] = [];

if (!empty($_GET['action']) && !empty($_GET['task'])) {
    function update_cart($add = false) {
        foreach ($_POST['quantity'] as $id => $qty) {
            if ($qty == 0) unset($_SESSION["cart"][$id]);
            else $_SESSION["cart"][$id] = $add ? ($_SESSION["cart"][$id] + $qty) : $qty;
        }
    }
    switch ($_GET['task']) {
        case 'add':
            update_cart(true);
            header('Location: index.php?action=giohang'); exit;
        case 'delete':
            if (isset($_GET['id'])) unset($_SESSION["cart"][$_GET['id']]);
            header('Location: index.php?action=giohang'); exit;
        case 'submit':
            if (isset($_POST['update_click'])) {
                update_cart();
                header('Location: index.php?action=giohang'); exit;
            } elseif (isset($_POST['apply_coupon'])) {
                $code = trim($_POST['coupon_code'] ?? '');
                $store = new StoreModel();
                $discount = $store->getDiscountCode($code);
                if ($discount) { $_SESSION['discount'] = $discount; $_SESSION['success_msg'] = "Áp dụng mã thành công!"; }
                else { unset($_SESSION['discount']); $_SESSION['error_msg'] = "Mã không hợp lệ hoặc đã hết hạn."; }
                header('Location: index.php?action=giohang'); exit;
            } elseif (!empty($_POST['order_click'])) {
                if (isset($_POST['tennguoinhan'], $_POST['emailnguoinhan'], $_POST['sdtnguoinhan'], $_POST['diachinguoinhan'])) {
                    $ma = generate_random_order_id();
                    $_SESSION[$ma] = ['tennn'=>$_POST['tennguoinhan'],'emailnn'=>$_POST['emailnguoinhan'],'sdtnn'=>$_POST['sdtnguoinhan'],'diachinn'=>$_POST['diachinguoinhan'],'ghichunn'=>$_POST['ghichunguoinhan'] ?? '','tongtien'=>$_POST['totalmoney'],'giamgia'=>$_POST['discount_amount'] ?? 0];
                    $_SESSION['ma_don_hang'] = $ma;
                    header('Location: index.php?action=thanhtoan'); exit;
                }
            }
            break;
    }
}

$temp_money = 0; $discount_amount = 0; $total_money = 0;
if (isset($_SESSION['discount'], $_SESSION["cart"], $data_cart) && is_array($data_cart)) {
    foreach ($data_cart as $r) {
        $gia_ban = $r['gia_sp'];
        if (!empty($r['flash_sale_end']) && strtotime($r['flash_sale_end']) > time() && !empty($r['flash_sale_price'])) {
            $gia_ban = $r['flash_sale_price'];
        }
        $temp_money += $gia_ban * ($_SESSION["cart"][$r['id_sp']] ?? 0);
    }
    $d = $_SESSION['discount'];
    $discount_amount = $d['loai'] === 'percentage' ? $temp_money * ($d['gia_tri']/100) : $d['gia_tri'];
    $temp_money = 0; // Reset for display loop below
}

$pendingOrderId = $_SESSION['ma_don_hang'] ?? null;
$pendingInfo = ($pendingOrderId && isset($_SESSION[$pendingOrderId])) ? $_SESSION[$pendingOrderId] : [];
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.cart-page { font-family:'Inter',sans-serif; background:#f0f4f8; min-height:100vh; padding:40px 0 80px; }
.cart-wrap { max-width:1160px; margin:0 auto; padding:0 20px; }
.cart-header { margin-bottom:28px; }
.cart-header h1 { font-size:26px; font-weight:800; color:#1a2535; margin:0; display:flex; align-items:center; gap:12px; }
.cart-header h1 i { color:#d4af37; }
.cart-grid { display:grid; grid-template-columns:1fr 380px; gap:24px; align-items:start; }

/* CART TABLE */
.cart-card { background:#fff; border-radius:20px; box-shadow:0 4px 24px rgba(0,0,0,.06); overflow:hidden; }
.cart-card-head { padding:20px 28px; border-bottom:1px solid #f0f4f8; display:flex; justify-content:space-between; align-items:center; }
.cart-card-head span { font-size:14px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; }
.cart-item { display:grid; grid-template-columns:auto 1fr auto auto auto; align-items:center; gap:16px; padding:20px 28px; border-bottom:1px solid #f8fafc; transition:.2s; }
.cart-item:last-child { border-bottom:none; }
.cart-item:hover { background:#fafbfc; }
.cart-item-img { width:72px; height:72px; object-fit:cover; border-radius:12px; border:1px solid #e8edf3; flex-shrink:0; }
.cart-item-info h4 { font-size:15px; font-weight:700; color:#1a2535; margin:0 0 4px; }
.cart-item-info p { font-size:13px; color:#94a3b8; margin:0; }
.cart-item-price { font-size:15px; font-weight:700; color:#1a2535; white-space:nowrap; min-width:100px; text-align:right; }
.cart-item-subtotal { font-size:16px; font-weight:800; color:#e53e3e; white-space:nowrap; min-width:110px; text-align:right; }
.qty-box { display:flex; align-items:center; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; }
.qty-btn { width:32px; height:36px; background:#f8fafc; border:none; cursor:pointer; font-size:16px; color:#475569; transition:.15s; display:flex; align-items:center; justify-content:center; }
.qty-btn:hover { background:#e2e8f0; }
.qty-input { width:44px; height:36px; border:none; border-left:1px solid #e2e8f0; border-right:1px solid #e2e8f0; text-align:center; font-size:15px; font-weight:700; color:#1a2535; -moz-appearance:textfield; }
.qty-input::-webkit-outer-spin-button,.qty-input::-webkit-inner-spin-button{-webkit-appearance:none}
.btn-remove { width:32px; height:32px; background:#fee2e2; border:none; border-radius:8px; cursor:pointer; color:#e53e3e; display:flex; align-items:center; justify-content:center; transition:.2s; font-size:13px; flex-shrink:0; text-decoration:none; }
.btn-remove:hover { background:#e53e3e; color:#fff; }
.cart-footer { padding:16px 28px; display:flex; justify-content:space-between; align-items:center; border-top:1px solid #f0f4f8; }
.btn-continue { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:#f0f4f8; color:#475569; border-radius:10px; font-size:13px; font-weight:700; text-decoration:none; border:none; cursor:pointer; transition:.2s; }
.btn-continue:hover { background:#e2e8f0; color:#1a2535; }
.btn-update { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:linear-gradient(135deg,#1a4a7a,#0f2942); color:#fff; border-radius:10px; font-size:13px; font-weight:700; border:none; cursor:pointer; transition:.2s; }
.btn-update:hover { opacity:.9; }

/* EMPTY */
.cart-empty { padding:60px 28px; text-align:center; }
.cart-empty i { font-size:60px; color:#e2e8f0; margin-bottom:20px; }
.cart-empty h3 { font-size:20px; font-weight:700; color:#64748b; margin-bottom:8px; }
.cart-empty p { color:#94a3b8; font-size:14px; margin-bottom:28px; }
.btn-shop { display:inline-flex; align-items:center; gap:8px; padding:13px 32px; background:linear-gradient(135deg,#d4af37,#b8962e); color:#fff; border-radius:12px; font-weight:700; text-decoration:none; transition:.2s; }
.btn-shop:hover { opacity:.9; color:#fff; }

/* RIGHT PANEL */
.panel-card { background:#fff; border-radius:20px; box-shadow:0 4px 24px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.panel-head { padding:18px 24px; border-bottom:1px solid #f0f4f8; font-size:13px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; display:flex; align-items:center; gap:8px; }
.panel-head i { color:#d4af37; }
.panel-body { padding:20px 24px; }

/* SHIPPING FORM */
.form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.form-group { margin-bottom:14px; }
.form-label { display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px; }
.form-input { width:100%; padding:11px 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:14px; color:#1a2535; font-family:inherit; transition:.2s; box-sizing:border-box; }
.form-input:focus { outline:none; border-color:#1a4a7a; box-shadow:0 0 0 3px rgba(26,74,122,.1); }
.form-input::placeholder { color:#cbd5e1; }
.login-notice { background:#f0f8ff; border:1px solid #bfdbfe; border-radius:10px; padding:10px 14px; font-size:13px; color:#1e40af; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.login-notice a { color:#1a4a7a; font-weight:700; text-decoration:none; }

/* COUPON */
.coupon-row { display:flex; gap:8px; }
.coupon-input { flex:1; padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:14px; font-family:inherit; transition:.2s; }
.coupon-input:focus { outline:none; border-color:#d4af37; }
.btn-coupon { padding:10px 18px; background:linear-gradient(135deg,#d4af37,#b8962e); color:#fff; border:none; border-radius:10px; font-weight:700; font-size:13px; cursor:pointer; white-space:nowrap; }
.alert-ok { background:#dcfce7; border:1px solid #86efac; color:#166534; border-radius:8px; padding:10px 14px; font-size:13px; margin-top:10px; display:flex; align-items:center; gap:6px; }
.alert-err { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; border-radius:8px; padding:10px 14px; font-size:13px; margin-top:10px; display:flex; align-items:center; gap:6px; }

/* SUMMARY */
.summary-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #f8fafc; font-size:14px; }
.summary-row:last-of-type { border-bottom:none; }
.summary-label { color:#64748b; font-weight:500; }
.summary-val { font-weight:700; color:#1a2535; }
.summary-discount { color:#16a34a; }
.summary-total { display:flex; justify-content:space-between; align-items:center; padding:16px 0 0; margin-top:8px; border-top:2px solid #f0f4f8; }
.summary-total span { font-size:15px; font-weight:800; color:#1a2535; }
.summary-total strong { font-size:24px; font-weight:800; color:#e53e3e; }
.btn-checkout { width:100%; padding:16px; background:linear-gradient(135deg,#0f2942,#1a4a7a); color:#fff; border:none; border-radius:14px; font-size:16px; font-weight:800; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:10px; margin-top:16px; transition:.2s; font-family:inherit; }
.btn-checkout:hover { background:linear-gradient(135deg,#1a4a7a,#2563a8); transform:translateY(-1px); box-shadow:0 8px 24px rgba(15,41,66,.3); }
.btn-checkout:disabled,.btn-checkout.disabled { background:#94a3b8; cursor:not-allowed; transform:none; box-shadow:none; }
.secure-note { text-align:center; font-size:12px; color:#94a3b8; margin-top:12px; display:flex; align-items:center; justify-content:center; gap:6px; }

@media(max-width:900px){.cart-grid{grid-template-columns:1fr}.form-row-2{grid-template-columns:1fr}}
@media(max-width:640px){.cart-item{grid-template-columns:auto 1fr auto;}.cart-item-price,.cart-item-subtotal{display:none}}
</style>

<div class="cart-page">
<div class="cart-wrap">

    <div class="cart-header">
        <h1><i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn</h1>
    </div>

    <form action="index.php?action=giohang&task=submit" method="POST" id="cartForm">
    <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
    <input type="hidden" name="discount_amount" value="<?= $discount_amount ?>">

    <div class="cart-grid">
        <!-- LEFT: CART ITEMS -->
        <div>
            <div class="cart-card">
                <?php if (!isset($data_cart) || empty($data_cart)): ?>
                    <div class="cart-empty">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Giỏ hàng trống</h3>
                        <p>Hãy khám phá bộ sưu tập đồng hồ của chúng tôi</p>
                        <a href="index.php?action=sanpham" class="btn-shop"><i class="fas fa-compass"></i> Mua sắm ngay</a>
                    </div>
                <?php else: ?>
                    <div class="cart-card-head">
                        <span><i class="fas fa-box" style="color:#d4af37;margin-right:6px"></i> Sản phẩm</span>
                        <span><?= count($data_cart) ?> mặt hàng</span>
                    </div>

                    <?php foreach ($data_cart as $row):
                        $gia_ban = $row['gia_sp'];
                        $isFlashSale = false;
                        if (!empty($row['flash_sale_end']) && strtotime($row['flash_sale_end']) > time() && !empty($row['flash_sale_price'])) {
                            $gia_ban = $row['flash_sale_price'];
                            $isFlashSale = true;
                        }
                        $lineTotal = $gia_ban * ($_SESSION["cart"][$row['id_sp']] ?? 0);
                        $temp_money += $lineTotal;
                    ?>
                    <div class="cart-item">
                        <a href="index.php?action=giohang&task=delete&id=<?= $row['id_sp'] ?>" class="btn-remove" title="Xóa">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        <img src="<?= $row['hinhanh_sp'] ?>" alt="<?= htmlspecialchars($row['ten_sp']) ?>" class="cart-item-img">
                        <div class="cart-item-info">
                            <h4><?= htmlspecialchars($row['ten_sp']) ?></h4>
                            <p>Mã SP: #<?= $row['id_sp'] ?></p>
                            <?php if($isFlashSale): ?>
                                <span style="display:inline-block; margin-top:4px; font-size:11px; font-weight:700; color:#fff; background:#ee4d2d; padding:2px 6px; border-radius:4px;"><i class="fas fa-bolt"></i> Flash Sale</span>
                            <?php endif; ?>
                        </div>
                        <div class="cart-item-price">
                            <?php if($isFlashSale): ?>
                                <div style="text-decoration:line-through; color:#94a3b8; font-size:12px; font-weight:400;"><?= number_format($row['gia_sp'], 0, ',', '.') ?>đ</div>
                            <?php endif; ?>
                            <?= number_format($gia_ban, 0, ',', '.') ?>đ
                        </div>
                        <div class="qty-box">
                            <button type="button" class="qty-btn" onclick="changeQty(<?= $row['id_sp'] ?>,-1)">−</button>
                            <input class="qty-input" type="number" min="0" name="quantity[<?= $row['id_sp'] ?>]" value="<?= $_SESSION["cart"][$row['id_sp']] ?? 1 ?>" id="qty_<?= $row['id_sp'] ?>">
                            <button type="button" class="qty-btn" onclick="changeQty(<?= $row['id_sp'] ?>,1)">+</button>
                        </div>
                        <div class="cart-item-subtotal"><?= number_format($lineTotal, 0, ',', '.') ?>đ</div>
                    </div>
                    <?php endforeach;
                    $total_money = $temp_money - $discount_amount;
                    if ($total_money < 0) $total_money = 0;
                    ?>

                    <div class="cart-footer">
                        <a href="index.php?action=sanpham" class="btn-continue"><i class="fas fa-arrow-left"></i> Tiếp tục mua</a>
                        <button type="submit" name="update_click" class="btn-update" formnovalidate><i class="fas fa-sync-alt"></i> Cập nhật giỏ</button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- SHIPPING INFO -->
            <div class="cart-card" style="margin-top:20px">
                <div class="panel-head"><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</div>
                <div class="panel-body">
                    <?php if (!$isLoggedIn): ?>
                    <div class="login-notice"><i class="fas fa-info-circle"></i> Đã có tài khoản? <a href="index.php?action=taikhoan">Đăng nhập</a> để điền nhanh hơn.</div>
                    <?php endif; ?>

                    <?php
                    $tenNN    = htmlspecialchars($pendingInfo['tennn'] ?? '', ENT_QUOTES);
                    $emailNN  = htmlspecialchars($pendingInfo['emailnn'] ?? '', ENT_QUOTES);
                    $sdtNN    = htmlspecialchars($pendingInfo['sdtnn'] ?? '', ENT_QUOTES);
                    $diachiNN = htmlspecialchars($pendingInfo['diachinn'] ?? '', ENT_QUOTES);
                    $ghichuNN = htmlspecialchars($pendingInfo['ghichunn'] ?? '', ENT_QUOTES);
                    ?>
                    <div class="form-group">
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" class="form-input" name="tennguoinhan" placeholder="Nguyễn Văn A" value="<?= $tenNN ?>" required>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-input" name="emailnguoinhan" placeholder="email@example.com" value="<?= $emailNN ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số điện thoại *</label>
                            <input type="text" class="form-input" name="sdtnguoinhan" placeholder="0901 234 567" value="<?= $sdtNN ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Địa chỉ nhận hàng *</label>
                        <input type="text" class="form-input" name="diachinguoinhan" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/TP" value="<?= $diachiNN ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label">Ghi chú (tuỳ chọn)</label>
                        <textarea name="ghichunguoinhan" class="form-input" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."><?= $ghichuNN ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: SUMMARY -->
        <div>
            <!-- COUPON -->
            <div class="panel-card">
                <div class="panel-head"><i class="fas fa-tag"></i> Mã giảm giá</div>
                <div class="panel-body">
                    <div class="coupon-row">
                        <input type="text" class="coupon-input" name="coupon_code" placeholder="Nhập mã code..." value="<?= isset($_SESSION['discount']) ? htmlspecialchars($_SESSION['discount']['ma_code']) : '' ?>">
                        <button type="submit" name="apply_coupon" class="btn-coupon" formnovalidate>Áp dụng</button>
                    </div>
                    <?php if (isset($_SESSION['success_msg'])): ?>
                    <div class="alert-ok"><i class="fas fa-check-circle"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error_msg'])): ?>
                    <div class="alert-err"><i class="fas fa-times-circle"></i> <?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ORDER SUMMARY -->
            <div class="panel-card">
                <div class="panel-head"><i class="fas fa-receipt"></i> Tóm tắt đơn hàng</div>
                <div class="panel-body">
                    <div class="summary-row">
                        <span class="summary-label">Tạm tính</span>
                        <span class="summary-val"><?= number_format($temp_money, 0, ',', '.') ?>đ</span>
                    </div>
                    <?php if ($discount_amount > 0): ?>
                    <div class="summary-row">
                        <span class="summary-label">Giảm giá <span style="font-size:11px;color:#94a3b8">(<?= htmlspecialchars($_SESSION['discount']['ma_code']) ?>)</span></span>
                        <span class="summary-val summary-discount">−<?= number_format($discount_amount, 0, ',', '.') ?>đ</span>
                    </div>
                    <?php endif; ?>
                    <div class="summary-row">
                        <span class="summary-label">Phí vận chuyển</span>
                        <span class="summary-val" style="color:#16a34a">Miễn phí</span>
                    </div>
                    <div class="summary-total">
                        <span>Tổng cộng</span>
                        <strong><?= number_format($total_money, 0, ',', '.') ?>đ</strong>
                    </div>

                    <input type="hidden" name="totalmoney" value="<?= $total_money ?>">
                    <button type="submit" name="order_click" value="1" class="btn-checkout<?= !isset($data_cart) ? ' disabled' : '' ?>" <?= !isset($data_cart) ? 'disabled' : '' ?>>
                        <i class="fas fa-lock"></i> Đặt hàng & Thanh toán
                    </button>
                    <div class="secure-note"><i class="fas fa-shield-alt"></i> Thanh toán bảo mật & mã hoá SSL</div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
</div>

<script>
function changeQty(id, delta) {
    const input = document.getElementById('qty_' + id);
    const val = parseInt(input.value) + delta;
    input.value = val < 0 ? 0 : val;
}
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>