<?php
if (isset($_POST['nutdx'])) {
    session_unset();
}

$isLoggedIn = isset($_SESSION['tennd']);
$isAdmin = $isLoggedIn && $_SESSION['quyennd'] == 1;
?>

<div class="navbar">
    <div class="logo">
        <a href="./" class="brand-wrap">
            <img src="images/logo.png" width="120px" alt="Watch Shop logo">
            <span class="brand-text">ChronoLux</span>
        </a>
    </div>
    <nav>
        <ul id="MenuItems" class="menu-items">
            <li><a href="./">Trang chủ</a></li>
            <li><a href="index.php?action=sanpham">Sản phẩm</a></li>
            <li><a href="index.php?action=gioithieu">Giới thiệu</a></li>
            <li><a href="index.php?action=lienhe">Liên hệ</a></li>
            <li><a href="#" class="search-ico"><i class="fa fa-search" aria-hidden="true"></i></a></li>
            <?php if ($isLoggedIn) { ?>
                <li class="navbar__user">
                    <a class="nav-link nav-link__active <?= $isAdmin ? 'active' : ''; ?>" href="#">
                        Xin chào <?= $_SESSION['tennd']; ?>
                    </a>
                    <ul class="navbar__user-menu">
                        <li class="navbar__user-menu-item">
                            <a href="index.php?action=thongtintaikhoan">
                                <i class="fas fa-user-circle me-2"></i> Tài khoản của tôi
                            </a>
                        </li>
                        <?php if ($isAdmin) { ?>
                            <li class="navbar__user-menu-item">
                                <a class="nav-link active" href="index.php?action=quantri">
                                    <i class="fas fa-user-shield me-2"></i> Quản trị
                                </a>
                            </li>
                        <?php } ?>
                        <li class="navbar__user-menu-item">
                            <a href="index.php?action=thongtintaikhoan#don-hang">
                                <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                            </a>
                        </li>
                        <li class="navbar__user-menu-item navbar__user-menu-item--separate">
                            <form method="post" class="m-0">
                                <button type="submit" name="nutdx" class="btn-dangxuat w-100 text-start px-3 py-2">
                                    <i class="fas fa-sign-out-alt me-2 text-danger"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            <?php } else { ?>
                <li><a class="nav-link" href="index.php?action=taikhoan">Đăng nhập / Đăng ký</a></li>
            <?php } ?>
        </ul>
    </nav>
    <?php 
    $cart_count = 0;
    if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $cart_count = array_sum($_SESSION['cart']);
    }
    ?>
    <a href="index.php?action=giohang" style="position: relative; display: inline-block; margin-right: 15px;">
        <img src="images/cart.png" width="30px" height="30px">
        <?php if($cart_count > 0): ?>
            <span style="position: absolute; top: -8px; right: -12px; background-color: #ff523b; color: white; border-radius: 50%; padding: 2px 6px; font-size: 11px; font-weight: bold; line-height: 1; min-width: 18px; text-align: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?= $cart_count ?></span>
        <?php endif; ?>
    </a>
    <img src="images/menu.png" class="menu-icon" onClick="menutoggle()">
</div>

<!--search-->
<form action="index.php?action=ketquatimkiem&keyword=" method="POST" id="search-form">
    <div class="search-bar">
        <div class="search">
            <input type="text" id="search-input" placeholder="Tìm đồng hồ bạn yêu thích..." name="str" required>
            <button type="submit" name="submit" class="btn__search"><i class="fa fa-search"></i></button>
            <a href="#" class="search-cancel">&times;</a>
        </div>
    </div>
</form>




