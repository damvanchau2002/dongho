<!-- QUẢN LÝ CỬA HÀNG -->
<div class="sidebar-section">
    <h6 class="sidebar-title"><i class="fas fa-store"></i> Cửa hàng</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (!isset($_GET['action']) || $_GET['action'] == 'quantri') && !isset($_GET['idloai']) ? 'active' : '' ?>" href="index.php?action=quantri">
            <i class="fas fa-chart-line"></i> Bảng điều khiển
        </a>
    </nav>
</div>

<!-- THƯƠNG HIỆU -->
<div class="sidebar-section <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlyloai', 'themloai', 'sualoai'])) || isset($_GET['idloai']) ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-tags"></i> Thương hiệu</h6>
    <nav class="nav flex-column">
        <!-- Quản lý nằm ngoài menu con -->
        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'quanlyloai') ? 'active' : '' ?>" href="index.php?action=quanlyloai">
            <i class="fas fa-cog"></i> Quản lý hiệu
        </a>
        
        <!-- Chỉ danh sách hãng là menu con -->
        <a class="nav-link has-submenu" onclick="toggleSubmenu(this)">
            <i class="fas fa-list"></i> Danh sách hãng
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
                        <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> <?php echo $value['ten_loaisp']; ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </nav>
</div>

<!-- SẢN PHẨM -->
<div class="sidebar-section <?= (isset($_GET['action']) && in_array($_GET['action'], ['themsanpham', 'sua'])) ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-box"></i> Sản phẩm</h6>
    <nav class="nav flex-column">
        <a class="nav-link has-submenu" onclick="toggleSubmenu(this)">
            <i class="fas fa-list"></i> Danh sách hàng
        </a>
        <div class="brand-list">
            <a class="nav-link <?= (!isset($_GET['idloai']) && isset($_GET['action']) && $_GET['action'] == 'quanlysanpham') ? 'active' : '' ?>" href="index.php?action=quanlysanpham">
                <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i> Tất cả sản phẩm
            </a>
        </div>
    </nav>
</div>

<!-- ĐƠN HÀNG -->
<div class="sidebar-section <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlydonhang', 'chitietdonhang'])) ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-shopping-cart"></i> Đơn hàng</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'quanlydonhang') ? 'active' : '' ?>" href="index.php?action=quanlydonhang">
            <i class="fas fa-file-invoice-dollar"></i> Quản lý đơn hàng
        </a>
    </nav>
</div>

<!-- MÃ GIẢM GIÁ -->
<div class="sidebar-section <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlymagiamgia', 'themmagiamgia'])) ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-ticket-alt"></i> Khuyến mãi</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'quanlymagiamgia') ? 'active' : '' ?>" href="index.php?action=quanlymagiamgia">
            <i class="fas fa-list-ul"></i> Quản lý mã giảm giá
        </a>
    </nav>
</div>

<!-- VẬN CHUYỂN -->
<div class="sidebar-section">
    <h6 class="sidebar-title"><i class="fas fa-truck"></i> Vận chuyển</h6>
    <nav class="nav flex-column">
        <a class="nav-link" href="#">
            <i class="fas fa-shipping-fast"></i> Cấu hình giao hàng
        </a>
    </nav>
</div>

<!-- HỆ THỐNG -->
<!-- QUẢN LÝ BANNER -->
<div class="sidebar-section <?= (isset($_GET['action']) && in_array($_GET['action'], ['quanlybanner', 'thembanner', 'suabanner'])) ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-image"></i> Quản lý Banner</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'quanlybanner') ? 'active' : '' ?>" href="index.php?action=quanlybanner">
            <i class="fas fa-layer-group"></i> Danh sách Banner
        </a>
    </nav>
</div>

<!-- TƯƠNG TÁC -->
<div class="sidebar-section <?= (isset($_GET['action']) && $_GET['action'] == 'quanlytinnhan') ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-comments"></i> Tương tác</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'quanlytinnhan') ? 'active' : '' ?>" href="index.php?action=quanlytinnhan">
            <i class="fas fa-inbox"></i> Hộp thư Khách hàng
        </a>
    </nav>
</div>

<!-- HỆ THỐNG -->
<div class="sidebar-section <?= (isset($_GET['action']) && $_GET['action'] == 'quanlynguoidung') ? 'active' : '' ?>">
    <h6 class="sidebar-title"><i class="fas fa-cog"></i> Hệ thống</h6>
    <nav class="nav flex-column">
        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'quanlynguoidung') ? 'active' : '' ?>" href="index.php?action=quanlynguoidung">
            <i class="fas fa-users-cog"></i> Quản lý người dùng
        </a>
    </nav>
</div>

<script>
function toggleSubmenu(element) {
    const parent = element.closest('.sidebar-section');
    parent.classList.toggle('active');
}
</script>
