<?php
$pageTitle = "Tài khoản của tôi - ChronoLux";
ob_start();
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.acc-wrap { font-family:'Inter',sans-serif; background:#f0f4f8; min-height:100vh; padding:40px 0 60px; }
.acc-container { max-width:1100px; margin:0 auto; padding:0 20px; display:grid; grid-template-columns:300px 1fr; gap:24px; }
/* SIDEBAR */
.acc-sidebar { background:linear-gradient(160deg,#0f2942 0%,#1a4a7a 100%); border-radius:20px; padding:36px 28px; color:#fff; box-shadow:0 20px 60px rgba(15,41,66,.35); position:sticky; top:20px; height:fit-content; }
.acc-avatar { width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#d4af37,#f0d060); display:flex; align-items:center; justify-content:center; font-size:32px; font-weight:800; color:#0f2942; margin:0 auto 16px; box-shadow:0 8px 24px rgba(212,175,55,.4); }
.acc-username { text-align:center; font-size:20px; font-weight:700; margin-bottom:4px; }
.acc-role { text-align:center; font-size:12px; font-weight:600; letter-spacing:1.5px; text-transform:uppercase; color:#d4af37; margin-bottom:28px; }
.acc-info-item { display:flex; align-items:center; gap:12px; padding:12px 16px; background:rgba(255,255,255,.08); border-radius:12px; margin-bottom:10px; font-size:14px; }
.acc-info-item i { width:18px; color:#d4af37; flex-shrink:0; }
.acc-info-item span { color:rgba(255,255,255,.85); word-break:break-all; }
.acc-divider { border:none; border-top:1px solid rgba(255,255,255,.12); margin:24px 0; }
.acc-stat-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:24px; }
.acc-stat { background:rgba(255,255,255,.08); border-radius:12px; padding:14px; text-align:center; }
.acc-stat-num { font-size:22px; font-weight:800; color:#d4af37; }
.acc-stat-lbl { font-size:11px; color:rgba(255,255,255,.6); margin-top:2px; }
.btn-logout { width:100%; padding:13px; background:rgba(229,62,62,.15); color:#fc8181; border:1px solid rgba(229,62,62,.3); border-radius:12px; font-weight:700; font-size:14px; cursor:pointer; transition:.2s; display:flex; align-items:center; justify-content:center; gap:8px; text-decoration:none; }
.btn-logout:hover { background:#e53e3e; color:#fff; border-color:#e53e3e; }
/* MAIN */
.acc-main {}
.acc-card { background:#fff; border-radius:20px; padding:32px; box-shadow:0 4px 24px rgba(0,0,0,.06); margin-bottom:24px; }
.acc-card-title { font-size:17px; font-weight:800; color:#1a2535; margin-bottom:24px; display:flex; align-items:center; gap:10px; padding-bottom:16px; border-bottom:2px solid #f0f4f8; }
.acc-card-title i { color:#d4af37; }
/* ORDER CARDS */
.order-item { border:1px solid #e8edf3; border-radius:14px; padding:20px; margin-bottom:12px; transition:.2s; position:relative; overflow:hidden; }
.order-item:hover { border-color:#1a4a7a; box-shadow:0 4px 20px rgba(26,74,122,.1); transform:translateY(-1px); }
.order-item-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
.order-code { font-weight:800; font-size:16px; color:#1a2535; }
.order-date { font-size:12px; color:#94a3b8; margin-top:2px; }
.order-price { font-size:18px; font-weight:800; color:#e53e3e; }
.order-item-bottom { display:flex; justify-content:space-between; align-items:center; }
.badge-st { padding:5px 14px; border-radius:20px; font-size:12px; font-weight:700; }
.badge-pending { background:#fef3c7; color:#92400e; }
.badge-success { background:#dcfce7; color:#166534; }
.badge-cancel  { background:#fee2e2; color:#991b1b; }
.order-actions { display:flex; gap:8px; }
.btn-view { padding:7px 16px; border-radius:8px; font-size:13px; font-weight:600; background:#f0f4f8; color:#1a4a7a; border:none; cursor:pointer; text-decoration:none; transition:.2s; }
.btn-view:hover { background:#1a4a7a; color:#fff; }
.btn-cancel { padding:7px 16px; border-radius:8px; font-size:13px; font-weight:600; background:#fee2e2; color:#e53e3e; border:none; cursor:pointer; transition:.2s; }
.btn-cancel:hover { background:#e53e3e; color:#fff; }
.empty-orders { text-align:center; padding:50px 20px; }
.empty-orders i { font-size:52px; color:#e2e8f0; margin-bottom:16px; }
.empty-orders h4 { color:#64748b; font-size:18px; margin-bottom:8px; }
.empty-orders p { color:#94a3b8; font-size:14px; margin-bottom:24px; }
.btn-shop { background:linear-gradient(135deg,#1a4a7a,#0f2942); color:#fff; padding:12px 32px; border-radius:10px; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:8px; }
.btn-shop:hover { opacity:.9; color:#fff; }
/* MODAL */
.modal-bg { position:fixed; inset:0; background:rgba(0,0,0,.6); backdrop-filter:blur(6px); z-index:9000; display:none; align-items:center; justify-content:center; padding:20px; }
.modal-bg.active { display:flex; }
.modal-box { background:#fff; border-radius:20px; width:100%; max-width:860px; max-height:90vh; overflow:hidden; display:flex; flex-direction:column; animation:slideUp .3s ease; }
.modal-header { background:linear-gradient(135deg,#0f2942,#1a4a7a); padding:28px 36px; display:flex; justify-content:space-between; align-items:center; flex-shrink:0; }
.modal-header h2 { color:#fff; font-size:20px; font-weight:800; margin:0; }
.modal-header p { color:rgba(255,255,255,.65); font-size:12px; margin:4px 0 0; letter-spacing:1px; text-transform:uppercase; }
.modal-close { background:rgba(255,255,255,.15); border:none; color:#fff; width:36px; height:36px; border-radius:50%; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center; }
.modal-close:hover { background:rgba(255,255,255,.3); }
.modal-body { padding:32px 36px; overflow-y:auto; }
.modal-footer { padding:20px 36px; border-top:1px solid #f0f4f8; display:flex; gap:12px; justify-content:flex-end; flex-shrink:0; }
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px; }
.info-box { background:#f8fafc; border-radius:12px; padding:20px; border:1px solid #e8edf3; }
.info-box-title { font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#94a3b8; margin-bottom:14px; display:flex; align-items:center; gap:6px; }
.info-box-row { margin-bottom:10px; }
.info-box-label { font-size:12px; color:#94a3b8; margin-bottom:2px; }
.info-box-val { font-size:14px; font-weight:600; color:#1a2535; }
.items-table { width:100%; border-collapse:collapse; }
.items-table th { font-size:11px; letter-spacing:1px; text-transform:uppercase; color:#94a3b8; padding:10px 0; border-bottom:2px solid #f0f4f8; font-weight:700; }
.items-table td { padding:14px 0; border-bottom:1px solid #f8fafc; vertical-align:middle; }
.item-img { width:50px; height:50px; object-fit:cover; border-radius:8px; border:1px solid #e8edf3; }
.total-box { background:#f8fafc; border-radius:12px; padding:20px 24px; margin-top:20px; }
.total-row { display:flex; justify-content:space-between; align-items:center; padding:6px 0; font-size:14px; color:#64748b; }
.total-final { border-top:2px solid #e8edf3; margin-top:8px; padding-top:14px; font-size:20px; font-weight:800; color:#e53e3e; }
@keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
@media(max-width:768px){.acc-container{grid-template-columns:1fr}.info-grid{grid-template-columns:1fr}}
</style>

<?php
$totalOrders = isset($orders) ? count($orders) : 0;
$successOrders = isset($orders) ? count(array_filter($orders, fn($o) => $o['trang_thai'] == 3)) : 0;
$username = $user['ten_nd'] ?? ($_SESSION['tennd'] ?? 'U');
$avatarLetter = mb_strtoupper(mb_substr($username, 0, 1));
$isAdmin = isset($user['quyen_nd']) && $user['quyen_nd'] == 1;
?>

<div class="acc-wrap">
<div class="acc-container">

    <!-- SIDEBAR -->
    <div class="acc-sidebar">
        <div class="acc-avatar"><?= $avatarLetter ?></div>
        <div class="acc-username"><?= htmlspecialchars($username) ?></div>
        <div class="acc-role"><?= $isAdmin ? '⚡ Quản trị viên' : '👤 Khách hàng' ?></div>

        <?php if (!empty($user['email_nd'])): ?>
        <div class="acc-info-item">
            <i class="fas fa-envelope"></i>
            <span><?= htmlspecialchars($user['email_nd']) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($user['ngayt_nd'])): ?>
        <div class="acc-info-item">
            <i class="fas fa-calendar-alt"></i>
            <span>Tham gia <?= date('d/m/Y', strtotime($user['ngayt_nd'])) ?></span>
        </div>
        <?php endif; ?>

        <hr class="acc-divider">
        <div class="acc-stat-row">
            <div class="acc-stat">
                <div class="acc-stat-num"><?= $totalOrders ?></div>
                <div class="acc-stat-lbl">Đơn hàng</div>
            </div>
            <div class="acc-stat">
                <div class="acc-stat-num"><?= $successOrders ?></div>
                <div class="acc-stat-lbl">Thành công</div>
            </div>
        </div>

        <form method="post" action="index.php?action=dangxuat">
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </button>
        </form>
    </div>

    <!-- MAIN -->
    <div class="acc-main">
        <div class="acc-card">
            <div class="acc-card-title">
                <i class="fas fa-shopping-bag"></i> Đơn hàng của tôi
                <span style="margin-left:auto;font-size:13px;font-weight:600;color:#94a3b8"><?= $totalOrders ?> đơn</span>
            </div>

            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order):
                    $st = $order['trang_thai'];
                    $badgeClass = '';
                    $badgeText  = '';
                    if ($st == 0) { $badgeClass = 'badge-cancel'; $badgeText = '✕ Đã hủy'; }
                    elseif ($st == 1) { $badgeClass = 'badge-pending'; $badgeText = '⏳ Chờ xử lý'; }
                    elseif ($st == 2) { $badgeClass = 'badge-pending'; $badgeText = '🚚 Đang giao'; }
                    elseif ($st == 3) { $badgeClass = 'badge-success'; $badgeText = '✓ Thành công'; }
                ?>
                <div class="order-item">
                    <div class="order-item-top">
                        <div>
                            <div class="order-code">#<?= $order['ma_dh'] ?></div>
                            <div class="order-date"><i class="far fa-clock" style="margin-right:4px"></i><?= date('H:i - d/m/Y', strtotime($order['ngay_dat'])) ?></div>
                        </div>
                        <div class="order-price"><?= number_format($order['tong_tien'], 0, ',', '.') ?>đ</div>
                    </div>
                    <div class="order-item-bottom">
                        <span class="badge-st <?= $badgeClass ?>"><?= $badgeText ?></span>
                        <div class="order-actions">
                            <a href="index.php?action=thongtintaikhoan&view_order=<?= $order['id_dh'] ?>#modalDetail" class="btn-view">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                            <?php if ($st == 1): ?>
                            <button class="btn-cancel" onclick="showCancel(<?= $order['id_dh'] ?>, '<?= $order['ma_dh'] ?>')">
                                <i class="fas fa-times"></i> Hủy
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-orders">
                    <i class="fas fa-box-open"></i>
                    <h4>Chưa có đơn hàng nào</h4>
                    <p>Hãy khám phá bộ sưu tập đồng hồ của chúng tôi</p>
                    <a href="index.php?action=sanpham" class="btn-shop"><i class="fas fa-shopping-bag"></i> Mua sắm ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>

<!-- ORDER DETAIL MODAL -->
<?php if (isset($_GET['view_order'])):
    $store2 = new StoreModel();
    $od = $store2->orderById((int)$_GET['view_order']);
    if ($od && $od['id_nd'] == $_SESSION['id_nd']):
        $stMap = [0=>'Đã hủy',1=>'Chờ xử lý',2=>'Đang giao hàng',3=>'Thành công'];
        $stBadge = [0=>'badge-cancel',1=>'badge-pending',2=>'badge-pending',3=>'badge-success'];
        $pmRaw = strtolower(trim($od['phuong_thuc_thanh_toan'] ?? 'cod'));
        $pmLabel = $pmRaw === 'momo' ? 'Ví MoMo' : ($pmRaw === 'vnpay' ? 'VNPay' : 'Tiền mặt (COD)');
?>
<div id="modalDetail" class="modal-bg active">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <p>Chi tiết đơn hàng</p>
                <h2>#<?= $od['ma_dh'] ?></h2>
            </div>
            <a href="index.php?action=thongtintaikhoan" class="modal-close"><i class="fas fa-times"></i></a>
        </div>
        <div class="modal-body">
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-box-title"><i class="fas fa-truck" style="color:#1a4a7a"></i> Giao hàng</div>
                    <div class="info-box-row"><div class="info-box-label">Người nhận</div><div class="info-box-val"><?= htmlspecialchars($od['ten_nguoinhan']) ?></div></div>
                    <div class="info-box-row"><div class="info-box-label">Số điện thoại</div><div class="info-box-val"><?= htmlspecialchars($od['sdt_nguoinhan']) ?></div></div>
                    <div class="info-box-row"><div class="info-box-label">Địa chỉ</div><div class="info-box-val"><?= htmlspecialchars($od['diachi_nguoinhan']) ?></div></div>
                </div>
                <div class="info-box">
                    <div class="info-box-title"><i class="fas fa-info-circle" style="color:#1a4a7a"></i> Trạng thái</div>
                    <div class="info-box-row"><div class="info-box-label">Tình trạng</div><div class="info-box-val"><span class="badge-st <?= $stBadge[$od['trang_thai']] ?>"><?= $stMap[$od['trang_thai']] ?></span></div></div>
                    <div class="info-box-row"><div class="info-box-label">Thanh toán</div><div class="info-box-val"><?= $pmLabel ?></div></div>
                    <div class="info-box-row"><div class="info-box-label">Ngày đặt</div><div class="info-box-val"><?= date('H:i - d/m/Y', strtotime($od['ngay_dat'])) ?></div></div>
                </div>
            </div>

            <table class="items-table">
                <thead><tr>
                    <th style="text-align:left">Sản phẩm</th>
                    <th style="text-align:center">SL</th>
                    <th style="text-align:right">Đơn giá</th>
                    <th style="text-align:right">Thành tiền</th>
                </tr></thead>
                <tbody>
                <?php foreach ($od['items'] as $it): ?>
                <tr>
                    <td><div style="display:flex;align-items:center;gap:12px">
                        <img src="<?= $it['hinhanh_sp'] ?>" class="item-img" alt="">
                        <div><div style="font-weight:600;font-size:14px;color:#1a2535"><?= htmlspecialchars($it['ten_sp']) ?></div><div style="font-size:12px;color:#94a3b8">Mã: #<?= $it['id_sp'] ?></div></div>
                    </div></td>
                    <td style="text-align:center;font-weight:700">×<?= $it['so_luong'] ?></td>
                    <td style="text-align:right;color:#64748b;font-size:14px"><?= number_format($it['gia_ban'],0,',','.') ?>đ</td>
                    <td style="text-align:right;font-weight:700;color:#1a2535"><?= number_format($it['gia_ban']*$it['so_luong'],0,',','.') ?>đ</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-box">
                <div class="total-row"><span>Tạm tính</span><span><?= number_format($od['tong_tien'],0,',','.') ?>đ</span></div>
                <?php if (!empty($od['giam_gia']) && $od['giam_gia'] > 0): ?>
                <div class="total-row"><span>Giảm giá</span><span style="color:#16a34a">-<?= number_format($od['giam_gia'],0,',','.') ?>đ</span></div>
                <?php endif; ?>
                <div class="total-row"><span>Vận chuyển</span><span style="color:#16a34a">Miễn phí</span></div>
                <div class="total-row total-final"><span>TỔNG CỘNG</span><span><?= number_format($od['tong_tien'],0,',','.') ?>đ</span></div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="index.php?action=thongtintaikhoan" style="padding:10px 24px;border-radius:10px;background:#f0f4f8;color:#475569;font-weight:600;text-decoration:none;font-size:14px">Đóng</a>
            <button onclick="window.print()" style="padding:10px 24px;border-radius:10px;background:linear-gradient(135deg,#1a4a7a,#0f2942);color:#fff;border:none;font-weight:700;font-size:14px;cursor:pointer"><i class="fas fa-print" style="margin-right:6px"></i>In hóa đơn</button>
        </div>
    </div>
</div>
<?php endif; endif; ?>

<!-- CANCEL MODAL -->
<div id="cancelModal" class="modal-bg">
    <div class="modal-box" style="max-width:480px">
        <div class="modal-header">
            <div><p>Xác nhận hành động</p><h2>Hủy đơn hàng</h2></div>
            <button class="modal-close" onclick="hideCancel()"><i class="fas fa-times"></i></button>
        </div>
        <form action="index.php?action=huydonhang" method="POST">
            <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
            <input type="hidden" name="id_dh" id="cancel_id">
            <div style="padding:28px 32px">
                <p style="font-size:15px;color:#475569;margin-bottom:20px">Bạn sắp hủy đơn hàng <strong id="cancel_ma" style="color:#e53e3e"></strong>. Hành động này không thể hoàn tác.</p>
                <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:8px">Lý do hủy *</label>
                <select name="ly_do_huy" required style="width:100%;padding:12px 16px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;color:#374151;background:#fff">
                    <option value="" disabled selected>-- Chọn lý do --</option>
                    <option>Thay đổi ý định</option>
                    <option>Tìm thấy giá tốt hơn ở nơi khác</option>
                    <option>Thời gian giao hàng quá lâu</option>
                    <option>Đặt nhầm sản phẩm</option>
                    <option>Không còn nhu cầu nữa</option>
                    <option>Khác</option>
                </select>
                <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:10px;padding:12px 16px;margin-top:16px;font-size:13px;color:#92400e;display:flex;align-items:center;gap:8px">
                    <i class="fas fa-exclamation-triangle"></i> Đơn hàng đã hủy không thể khôi phục lại.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="hideCancel()" style="padding:10px 24px;border-radius:10px;background:#f0f4f8;color:#475569;font-weight:600;border:none;cursor:pointer;font-size:14px">Bỏ qua</button>
                <button type="submit" style="padding:10px 24px;border-radius:10px;background:#e53e3e;color:#fff;font-weight:700;border:none;cursor:pointer;font-size:14px"><i class="fas fa-times" style="margin-right:6px"></i>Xác nhận hủy</button>
            </div>
        </form>
    </div>
</div>

<script>
function showCancel(id, ma) {
    document.getElementById('cancel_id').value = id;
    document.getElementById('cancel_ma').textContent = '#' + ma;
    document.getElementById('cancelModal').classList.add('active');
}
function hideCancel() {
    document.getElementById('cancelModal').classList.remove('active');
}
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) hideCancel();
});
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>