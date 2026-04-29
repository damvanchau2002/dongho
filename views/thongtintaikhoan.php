<?php
$pageTitle = "Tài khoản của tôi - ChronoLux";
ob_start();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Leaflet CSS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

<style>
    .leaflet-routing-container { display: none; }
</style>

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
/* PROFILE FORM */
.pf-label { display:block; font-size:12px; font-weight:700; color:#64748b; margin-bottom:6px; text-transform:uppercase; letter-spacing:.4px; }
.pf-field { position:relative; margin-bottom:14px; }
.pf-field i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:14px; pointer-events:none; }
.pf-input { width:100%; padding:11px 14px 11px 42px; border:1.5px solid #e2e8f0; border-radius:10px; font-size:14px; color:#1a2535; font-family:inherit; transition:.2s; box-sizing:border-box; background:#f8fafc; }
.pf-input:focus { outline:none; border-color:#1a4a7a; background:#fff; box-shadow:0 0 0 3px rgba(26,74,122,.1); }
.pf-row2 { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.btn-save-profile { background:linear-gradient(135deg,#1a4a7a,#0f2942); color:#fff; border:none; padding:13px 32px; border-radius:12px; font-size:14px; font-weight:700; cursor:pointer; transition:.2s; display:inline-flex; align-items:center; gap:8px; margin-top:6px; }
.btn-save-profile:hover { opacity:.9; transform:translateY(-1px); box-shadow:0 8px 20px rgba(15,41,66,.25); }
.alert-profile { padding:12px 16px; border-radius:10px; font-size:14px; font-weight:500; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
.alert-success-p { background:#dcfce7; color:#166534; border:1px solid #86efac; }
.alert-error-p   { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
/* ADDRESS PICKER */
.pf-select { appearance:none; -webkit-appearance:none; cursor:pointer;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat:no-repeat; background-position:right 12px center; background-size:16px; }
.pf-select:disabled { opacity:.55; cursor:not-allowed; }
.addr-preview-box { margin-top:8px; font-size:12px; color:#166534; background:#f0fdf4; border:1px solid #86efac; border-radius:8px; padding:8px 12px; display:none; align-items:center; gap:6px; }
@media(max-width:768px){ .pf-row2{grid-template-columns:1fr} }
</style>

<?php
$totalOrders = isset($orders) ? count($orders) : 0;
$successOrders = isset($orders) ? count(array_filter($orders, fn($o) => $o['trang_thai'] == 3)) : 0;
$username = $user['ten_nd'] ?? ($_SESSION['tennd'] ?? 'U');
$avatarLetter = mb_strtoupper(mb_substr($username, 0, 1));
$isAdmin = isset($user['quyen_nd']) && $user['quyen_nd'] == 1;
$avatarPath = $user['avatar'] ?? ($_SESSION['avatar'] ?? null);
?>

<div class="acc-wrap">
<div class="acc-container">

    <!-- SIDEBAR -->
    <div class="acc-sidebar">
        <div class="acc-avatar" style="overflow: hidden; cursor: pointer; position: relative;" onclick="document.getElementById('avatarInput').click();" title="Nhấn để đổi ảnh đại diện">
            <?php if ($avatarPath): ?>
                <img id="avatarPreviewSidebar" src="<?= $avatarPath ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <span id="avatarLetterSidebar"><?= $avatarLetter ?></span>
            <?php endif; ?>
            <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); font-size: 10px; color: white; text-align: center; padding: 2px 0;">Đổi ảnh</div>
        </div>
        <div class="acc-username"><?= htmlspecialchars($username) ?></div>
        <div class="acc-role"><?= $isAdmin ? '⚡ Quản trị viên' : '👤 Khách hàng' ?></div>

        <?php if (!empty($user['email_nd'])): ?>
        <div class="acc-info-item">
            <i class="fas fa-envelope"></i>
            <span><?= htmlspecialchars($user['email_nd']) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($user['sdt_nd'])): ?>
        <div class="acc-info-item">
            <i class="fas fa-phone"></i>
            <span><?= htmlspecialchars($user['sdt_nd']) ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($user['diachi_nd'])): ?>
        <div class="acc-info-item">
            <i class="fas fa-map-marker-alt"></i>
            <span><?= htmlspecialchars($user['diachi_nd']) ?></span>
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

        <!-- PROFILE EDIT CARD -->
        <div class="acc-card" id="profileCard">
            <div class="acc-card-title"><i class="fas fa-user-edit"></i> Thông tin cá nhân</div>

            <?php if (isset($_SESSION['profile_success'])): ?>
            <div class="alert-profile alert-success-p"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['profile_success']); unset($_SESSION['profile_success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['profile_error'])): ?>
            <div class="alert-profile alert-error-p"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['profile_error']); unset($_SESSION['profile_error']); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=thongtintaikhoan" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
                <input type="hidden" name="capnhat_thongtin" value="1">
                <input type="file" name="avatar" id="avatarInput" style="display: none;" accept="image/*" onchange="previewAvatar(this)">

                <div class="pf-row2">
                    <div>
                        <label class="pf-label">Họ và tên *</label>
                        <div class="pf-field">
                            <input type="text" class="pf-input" name="ten_nd"
                                value="<?= htmlspecialchars($user['ten_nd'] ?? '') ?>"
                                placeholder="Nguyễn Văn A" required maxlength="100">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div>
                        <label class="pf-label">Email</label>
                        <div class="pf-field">
                            <input type="email" class="pf-input" name="email_nd"
                                value="<?= htmlspecialchars($user['email_nd'] ?? '') ?>"
                                placeholder="email@example.com" maxlength="255">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>

                <div class="pf-row2">
                    <div>
                        <label class="pf-label">Số điện thoại</label>
                        <div class="pf-field">
                            <input type="text" class="pf-input" name="sdt_nd"
                                value="<?= htmlspecialchars($user['sdt_nd'] ?? '') ?>"
                                placeholder="0901 234 567" maxlength="20">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                    <div></div><!-- spacer -->
                </div>

                <!-- ===== ADDRESS PICKER (3-level) ===== -->
                <div style="margin-bottom:14px">
                    <label class="pf-label"><i class="fas fa-map-marker-alt" style="margin-right:5px;color:#d4af37"></i>Địa chỉ giao hàng</label>
                    <!-- Hidden field stores the final assembled address -->
                    <input type="hidden" name="diachi_nd" id="pf_full_addr" value="<?= htmlspecialchars($user['diachi_nd'] ?? '') ?>">

                    <!-- Street / House number -->
                    <div class="pf-field" style="margin-bottom:8px">
                        <input type="text" class="pf-input" id="pf_street"
                            placeholder="Số nhà, tên đường..." maxlength="200">
                        <i class="fas fa-road"></i>
                    </div>

                    <!-- Province + District in 2 cols -->
                    <div class="pf-row2" style="gap:8px;margin-bottom:8px">
                        <div class="pf-field" style="margin-bottom:0">
                            <select class="pf-input pf-select" id="pf_province">
                                <option value="">-- Tỉnh / Thành phố --</option>
                            </select>
                            <i class="fas fa-map"></i>
                        </div>
                        <div class="pf-field" style="margin-bottom:0">
                            <select class="pf-input pf-select" id="pf_district" disabled>
                                <option value="">-- Quận / Huyện --</option>
                            </select>
                            <i class="fas fa-city"></i>
                        </div>
                    </div>

                    <!-- Ward -->
                    <div class="pf-field" style="margin-bottom:6px">
                        <select class="pf-input pf-select" id="pf_ward" disabled>
                            <option value="">-- Phường / Xã --</option>
                        </select>
                        <i class="fas fa-home"></i>
                    </div>

                    <!-- Preview assembled address -->
                    <div class="addr-preview-box" id="pf_preview">
                        <i class="fas fa-check-circle"></i>
                        <span id="pf_preview_text"></span>
                    </div>
                </div>

                <button type="submit" class="btn-save-profile">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
            </form>
        </div>

        <!-- PASSWORD CHANGE CARD -->
        <div class="acc-card" id="passwordCard">
            <div class="acc-card-title"><i class="fas fa-lock"></i> Đổi mật khẩu</div>

            <?php if (isset($_SESSION['password_success'])): ?>
            <div class="alert-profile alert-success-p"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['password_success']); unset($_SESSION['password_success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['password_error'])): ?>
            <div class="alert-profile alert-error-p"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['password_error']); unset($_SESSION['password_error']); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=thongtintaikhoan" id="formDoiMK">
                <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
                <input type="hidden" name="doi_mat_khau" value="1">

                <div class="pf-field" style="margin-bottom:14px">
                    <label class="pf-label">Mật khẩu hiện tại *</label>
                    <div style="position:relative">
                        <input type="password" class="pf-input" name="matkhau_cu" id="mk_cu"
                            placeholder="Nhập mật khẩu hiện tại" required maxlength="100">
                        <i class="fas fa-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:14px;pointer-events:none"></i>
                        <button type="button" onclick="togglePw('mk_cu','eye_cu')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:14px"><i class="fas fa-eye" id="eye_cu"></i></button>
                    </div>
                </div>

                <div class="pf-row2">
                    <div>
                        <label class="pf-label">Mật khẩu mới * <span style="font-size:11px;color:#94a3b8;text-transform:none;letter-spacing:0">(ít nhất 6 ký tự)</span></label>
                        <div style="position:relative">
                            <input type="password" class="pf-input" name="matkhau_moi" id="mk_moi"
                                placeholder="Nhập mật khẩu mới" required maxlength="100" minlength="6"
                                oninput="checkStrength(this.value)">
                            <i class="fas fa-key" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:14px;pointer-events:none"></i>
                            <button type="button" onclick="togglePw('mk_moi','eye_moi')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:14px"><i class="fas fa-eye" id="eye_moi"></i></button>
                        </div>
                        <!-- Password strength bar -->
                        <div id="strengthBar" style="height:4px;border-radius:4px;background:#e2e8f0;margin-top:6px;overflow:hidden">
                            <div id="strengthFill" style="height:100%;width:0%;border-radius:4px;transition:.3s"></div>
                        </div>
                        <div id="strengthText" style="font-size:11px;margin-top:3px;color:#94a3b8"></div>
                    </div>
                    <div>
                        <label class="pf-label">Xác nhận mật khẩu mới *</label>
                        <div style="position:relative">
                            <input type="password" class="pf-input" name="matkhau_xacnhan" id="mk_xn"
                                placeholder="Nhập lại mật khẩu mới" required maxlength="100"
                                oninput="checkMatch()">
                            <i class="fas fa-check-double" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:14px;pointer-events:none"></i>
                            <button type="button" onclick="togglePw('mk_xn','eye_xn')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:14px"><i class="fas fa-eye" id="eye_xn"></i></button>
                        </div>
                        <div id="matchText" style="font-size:11px;margin-top:3px"></div>
                    </div>
                </div>

                <button type="submit" class="btn-save-profile" style="background:linear-gradient(135deg,#e53e3e,#c53030);margin-top:6px">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </button>
            </form>
        </div>

        <!-- ORDERS CARD -->
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

            <?php if ($od['trang_thai'] == 2 && !empty($od['id_shipper'])): ?>
            <div class="info-box mt-3" style="width:100%; max-width:100%; border:1px solid #e2e8f0; padding:15px; border-radius:12px; background:#f8fafc; position: relative;">
                <div class="info-box-title" style="margin-bottom:15px; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fas fa-map-marked-alt" style="color:#1a4a7a"></i> Theo dõi lộ trình trực tiếp</span>
                    <span style="font-size: 11px; color: #2ecc71; font-weight: 700;"><span class="live-blink"></span> TRỰC TIẾP</span>
                </div>
                
                <div style="position: relative;">
                    <div id="mapInfoOverlay" style="position: absolute; top: 10px; left: 10px; z-index: 1000; background: rgba(255,255,255,0.9); padding: 8px 12px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); font-size: 12px; display: none;">
                        <div style="display: flex; gap: 15px;">
                            <span><i class="fas fa-route" style="color:#1a4a7a"></i> <strong id="mapDist">--</strong> km</span>
                            <span><i class="fas fa-clock" style="color:#1a4a7a"></i> <strong id="mapTime">--</strong> phút</span>
                        </div>
                    </div>
                    <div id="customerShippingMap" style="height: 350px; width: 100%; border-radius: 10px; z-index: 1; border: 1px solid #e2e8f0;"></div>
                </div>

                <style>
                    .live-blink { display: inline-block; width: 8px; height: 8px; background: #2ecc71; border-radius: 50%; margin-right: 5px; animation: blink 1.5s infinite; }
                    @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
                    .shipper-icon-container { position: relative; }
                    .shipper-pulse { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 35px; height: 35px; background: rgba(52, 152, 219, 0.3); border-radius: 50%; animation: pulse-blue 2s infinite; }
                    @keyframes pulse-blue { 0% { transform: translate(-50%, -50%) scale(0.5); opacity: 1; } 100% { transform: translate(-50%, -50%) scale(2.5); opacity: 0; } }
                </style>
            </div>
            <?php endif; ?>

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

/* ---- Password card helpers ---- */
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function checkStrength(val) {
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');
    if (!fill) return;
    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const levels = [
        { pct:'20%', color:'#ef4444', label:'Rất yếu' },
        { pct:'40%', color:'#f97316', label:'Yếu' },
        { pct:'60%', color:'#eab308', label:'Trung bình' },
        { pct:'80%', color:'#22c55e', label:'Mạnh' },
        { pct:'100%',color:'#16a34a', label:'Rất mạnh 🔒' },
    ];
    const lvl = levels[Math.max(0, score - 1)] || levels[0];
    fill.style.width = val.length === 0 ? '0%' : lvl.pct;
    fill.style.background = lvl.color;
    text.textContent = val.length === 0 ? '' : lvl.label;
    text.style.color = lvl.color;
}

function checkMatch() {
    const moi = document.getElementById('mk_moi');
    const xn  = document.getElementById('mk_xn');
    const txt = document.getElementById('matchText');
    if (!moi || !xn || !txt) return;
    if (xn.value === '') { txt.textContent = ''; return; }
    if (moi.value === xn.value) {
        txt.textContent = '✓ Mật khẩu khớp';
        txt.style.color = '#16a34a';
    } else {
        txt.textContent = '✗ Chưa khớp';
        txt.style.color = '#ef4444';
    }
}
</script>

<!-- ===== ADDRESS PICKER SCRIPT ===== -->
<script src="public/js/address-picker.js"></script>
<script>
  /* Auto-init for profile form */
  document.addEventListener('DOMContentLoaded', function(){
    var hiddenEl = document.getElementById('pf_full_addr');
    if (!hiddenEl) return;
    initAddressPicker({
      streetId:      'pf_street',
      provinceId:    'pf_province',
      districtId:    'pf_district',
      wardId:        'pf_ward',
      hiddenId:      'pf_full_addr',
      previewId:     'pf_preview',
      previewTextId: 'pf_preview_text',
      existingAddress: hiddenEl.value
    });
  });
</script>

</script>
<script>
  function previewAvatar(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
              var sidebarAvatar = document.querySelector('.acc-avatar');
              if (sidebarAvatar) {
                  // Xóa chữ nếu đang có, thay bằng hình
                  var letterSpan = document.getElementById('avatarLetterSidebar');
                  if (letterSpan) letterSpan.style.display = 'none';
                  
                  var imgPreview = document.getElementById('avatarPreviewSidebar');
                  if (!imgPreview) {
                      imgPreview = document.createElement('img');
                      imgPreview.id = 'avatarPreviewSidebar';
                      imgPreview.style.width = '100%';
                      imgPreview.style.height = '100%';
                      imgPreview.style.objectFit = 'cover';
                      sidebarAvatar.insertBefore(imgPreview, sidebarAvatar.firstChild);
                  }
                  imgPreview.src = e.target.result;
              }
          }
          reader.readAsDataURL(input.files[0]);
      }
  }
</script>

<?php if (isset($_GET['view_order']) && $od['trang_thai'] == 2 && !empty($od['id_shipper'])): ?>
<!-- Scripts for Tracking Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapContainer = document.getElementById('customerShippingMap');
        if (mapContainer) {
            const map = L.map('customerShippingMap').setView([10.762622, 106.660172], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let shipperMarker, destMarker, routingControl;
            let hasFitted = false;

            const shipperIcon = L.divIcon({
                className: 'shipper-icon-container',
                html: '<div class="shipper-pulse"></div><img src="https://cdn-icons-png.flaticon.com/512/2830/2830305.png" style="width: 35px; height: 35px; position: relative; z-index: 2;">',
                iconSize: [35, 35],
                iconAnchor: [17, 17]
            });

            const destIcon = L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/149/149059.png',
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            });

            const destinationAddress = <?php echo json_encode($od['diachi_nguoinhan']); ?>;
            let destLatLng = null;

            // Geocode Address (with progressive fallback)
            async function geocodeWithFallback(address) {
                let parts = address.split(',').map(p => p.trim());
                while (parts.length > 0) {
                    let query = parts.join(', ');
                    try {
                        let res = await fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query));
                        let data = await res.json();
                        if (data && data.length > 0) return data[0];
                    } catch (e) {
                        console.error("Geocoding API error:", e);
                    }
                    parts.shift();
                }
                return null;
            }

            geocodeWithFallback(destinationAddress).then(result => {
                if (result) {
                    destLatLng = L.latLng(result.lat, result.lon);
                    destMarker = L.marker(destLatLng, {icon: destIcon}).addTo(map).bindPopup("<b>Điểm nhận hàng của bạn</b>").openPopup();
                } else {
                    // Báo lỗi cho người dùng nếu Nominatim không tìm thấy toạ độ
                    const errBox = document.createElement("div");
                    errBox.style = "background: #fef2f2; color: #991b1b; padding: 10px; margin-bottom: 10px; border-radius: 5px; font-size: 14px;";
                    errBox.innerHTML = "<i class='fas fa-exclamation-triangle'></i> Hệ thống bản đồ không tìm thấy toạ độ Tỉnh/Thành phố của bạn. Bản đồ sẽ chỉ hiện vị trí của Shipper.";
                    document.getElementById('customerShippingMap').parentNode.insertBefore(errBox, document.getElementById('customerShippingMap'));
                }
                updateShipperLocation();
                setInterval(updateShipperLocation, 10000);
            });

            // Fetch Shipper GPS
            function updateShipperLocation() {
                fetch('index.php?action=get_shipper_location&id_shipper=<?php echo $od['id_shipper']; ?>')
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.lat && data.lng) {
                            const shipperLatLng = L.latLng(parseFloat(data.lat), parseFloat(data.lng));
                            
                            if (shipperMarker) {
                                shipperMarker.setLatLng(shipperLatLng);
                            } else {
                                shipperMarker = L.marker(shipperLatLng, {icon: shipperIcon}).addTo(map).bindPopup("<b>Shipper đang trên đường</b>");
                            }

                            if (destLatLng) {
                                if (routingControl) {
                                    routingControl.setWaypoints([shipperLatLng, destLatLng]);
                                } else {
                                    routingControl = L.Routing.control({
                                        waypoints: [shipperLatLng, destLatLng],
                                        routeWhileDragging: false,
                                        addWaypoints: false,
                                        draggableWaypoints: false,
                                        showAlternatives: false,
                                        fitSelectedRoutes: false,
                                        createMarker: function() { return null; },
                                        lineOptions: {
                                            styles: [
                                                { color: '#ffffff', opacity: 0.9, weight: 14 },
                                                { color: '#3498db', opacity: 1, weight: 9 }
                                            ]
                                        }
                                    }).addTo(map);

                                    routingControl.on('routesfound', function(e) {
                                        const summary = e.routes[0].summary;
                                        const distOverlay = document.getElementById('mapInfoOverlay');
                                        if (distOverlay) {
                                            distOverlay.style.display = 'block';
                                            document.getElementById('mapDist').textContent = (summary.totalDistance / 1000).toFixed(2);
                                            document.getElementById('mapTime').textContent = Math.round(summary.totalTime / 60);
                                        }
                                    });
                                }

                                if (!hasFitted) {
                                    const group = new L.featureGroup([shipperMarker, destMarker]);
                                    map.fitBounds(group.getBounds().pad(0.3));
                                    hasFitted = true;
                                }
                            } else if (!hasFitted) {
                                map.setView(shipperLatLng, 15);
                                hasFitted = true;
                            }
                        } else {
                            console.warn("Chưa có tín hiệu GPS từ Shipper");
                        }
                    })
                    .catch(err => console.error("GPS Fetch Error:", err));
            }
            
            // Fix map render issue inside hidden modal initially
            setTimeout(() => { map.invalidateSize(); }, 500);
        }
    });
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>