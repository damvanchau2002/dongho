<!-- TỔNG QUAN -->
<div class="sidebar-section">
    <h6 class="sidebar-title"><i class="fas fa-home"></i> Tổng quan</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (!isset($_GET['action']) || $_GET['action'] == 'quantri') && !isset($_GET['idloai']) ? 'active' : '' ?>" href="index.php?action=quantri">
            <i class="fas fa-chart-pie"></i> Bảng điều khiển
        </a>
    </nav>
</div>

<!-- KINH DOANH -->
<div class="sidebar-section <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlydonhang', 'chitietdonhang', 'quanlymagiamgia', 'themmagiamgia'])) ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-briefcase"></i> Kinh doanh</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlydonhang', 'chitietdonhang'])) ? 'active' : '' ?>" href="index.php?action=quanlydonhang">
            <i class="fas fa-file-invoice-dollar"></i> Đơn hàng
        </a>
        <a class="nav-link <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlymagiamgia', 'themmagiamgia'])) ? 'active' : '' ?>" href="index.php?action=quanlymagiamgia">
            <i class="fas fa-ticket-alt"></i> Khuyến mãi
        </a>
    </nav>
</div>

<!-- SẢN PHẨM & DANH MỤC -->
<div class="sidebar-section <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlysanpham', 'themsanpham', 'sua', 'quanlyloai', 'themloai', 'sualoai'])) || isset($_GET['idloai']) ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-boxes"></i> Sản phẩm & Danh mục</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlysanpham', 'themsanpham', 'sua']) && !isset($_GET['idloai'])) ? 'active' : '' ?>" href="index.php?action=quanlysanpham">
            <i class="fas fa-box"></i> Tất cả sản phẩm
        </a>
        <a class="nav-link <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlyloai', 'themloai', 'sualoai'])) ? 'active' : '' ?>" href="index.php?action=quanlyloai">
            <i class="fas fa-tags"></i> Thương hiệu
        </a>
        
        <a class="nav-link has-submenu" onclick="toggleSubmenu(this)" style="cursor:pointer">
            <i class="fas fa-filter"></i> Lọc theo hãng <i class="fas fa-angle-down float-right mt-1" style="font-size:0.8rem"></i>
        </a>
        <div class="brand-list">
            <?php 
                $hasCategories = false;
                if (isset($data) && is_array($data) && count($data) > 0) {
                    $firstElement = reset($data);
                    if (is_array($firstElement) && isset($firstElement['ten_loaisp'])) {
                        $hasCategories = true;
                    }
                }
            ?>
            <?php if ($hasCategories): ?>
                <?php foreach ($data as $value): 
                    if(isset($value['ten_loaisp']) && $value['ten_loaisp'] == 'THÊM') continue;
                ?>
                    <a class="nav-link <?= (isset($_GET['idloai']) && $_GET['idloai'] == $value['id_loaisp']) ? 'active' : '' ?>" 
                       href="index.php?action=quanlysanpham&idloai=<?php echo $value['id_loaisp'] ?>">
                        <i class="fas fa-circle" style="font-size: 0.4rem; margin-right: 8px;"></i> <?php echo $value['ten_loaisp']; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </nav>
</div>

<!-- MARKETING & CSKH -->
<div class="sidebar-section <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlybanner', 'thembanner', 'suabanner', 'quanlytinnhan'])) ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-bullhorn"></i> Marketing & CSKH</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlybanner', 'thembanner', 'suabanner'])) ? 'active' : '' ?>" href="index.php?action=quanlybanner">
            <i class="fas fa-images"></i> Quản lý Banner
        </a>
        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'quanlytinnhan') ? 'active' : '' ?>" href="index.php?action=quanlytinnhan">
            <i class="fas fa-comments"></i> Chat / Tin nhắn
        </a>
    </nav>
</div>

<!-- HỆ THỐNG -->
<div class="sidebar-section <?= (isset($_GET['action']) && $_GET['action'] == 'quanlynguoidung') ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-cogs"></i> Hệ thống</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'quanlynguoidung') ? 'active' : '' ?>" href="index.php?action=quanlynguoidung">
            <i class="fas fa-users-cog"></i> Người dùng
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-shipping-fast"></i> Cấu hình giao hàng
        </a>
    </nav>
</div>

<script>
function toggleSubmenu(element) {
    const parent = element.closest('.sidebar-section');
    parent.classList.toggle('active');
}
</script>

<?php if (isset($_SESSION['toast_success']) || isset($_SESSION['toast_error'])): ?>
    <div id="adminToast" class="admin-toast <?php echo isset($_SESSION['toast_success']) ? 'success' : 'error'; ?>">
        <div class="toast-icon">
            <i class="fas <?php echo isset($_SESSION['toast_success']) ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
        </div>
        <div class="toast-content">
            <?php 
                echo isset($_SESSION['toast_success']) ? $_SESSION['toast_success'] : $_SESSION['toast_error']; 
                unset($_SESSION['toast_success']);
                unset($_SESSION['toast_error']);
            ?>
        </div>
        <button class="toast-close" onclick="document.getElementById('adminToast').remove()"><i class="fas fa-times"></i></button>
    </div>

    <style>
        .admin-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 250px;
            animation: slideInRight 0.3s ease-out forwards;
            border-left: 5px solid;
        }
        .admin-toast.success { border-left-color: #10b981; }
        .admin-toast.error { border-left-color: #ef4444; }
        .admin-toast .toast-icon { font-size: 1.5rem; }
        .admin-toast.success .toast-icon { color: #10b981; }
        .admin-toast.error .toast-icon { color: #ef4444; }
        .admin-toast .toast-content { flex-grow: 1; font-weight: 500; color: #334155; font-size: 0.95rem; }
        .admin-toast .toast-close { background: none; border: none; cursor: pointer; color: #94a3b8; padding: 0; font-size: 1.2rem; }
        .admin-toast .toast-close:hover { color: #475569; }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .admin-toast.hiding {
            animation: fadeOutRight 0.3s ease-out forwards;
        }
    </style>
    
    <script>
        setTimeout(() => {
            const toast = document.getElementById('adminToast');
            if (toast) {
                toast.classList.add('hiding');
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    </script>
<?php endif; ?>
