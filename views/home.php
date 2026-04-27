<?php
$pageTitle = "ChronoLux | Đồng Hồ Cao Cấp Chính Hãng";
ob_start();
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&display=swap');

.home-wrapper { font-family: 'Inter', sans-serif; background: #f4f7f6; color: #1a2535; padding-bottom: 60px; }
.container { max-width: 1240px; margin: 0 auto; padding: 0 20px; }

/* HERO SECTION */
.hero-section { display: flex; gap: 20px; padding-top: 30px; margin-bottom: 40px; }
.hero-main { flex: 2.5; border-radius: 16px; overflow: hidden; position: relative; height: 420px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
.hero-main img { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 0; transition: opacity 0.8s ease; }
.hero-main img.active { opacity: 1; z-index: 1; }
.hero-main a { display: block; width: 100%; height: 100%; }
.banner-dots { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 10; }
.banner-dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.4); cursor: pointer; transition: all 0.3s; }
.banner-dot.active { background: #fff; width: 24px; border-radius: 5px; }

.hero-side { flex: 1; display: flex; flex-direction: column; gap: 20px; }
.hero-side-banner { height: 200px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.06); position: relative; display: block; }
.hero-side-banner img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s ease; }
.hero-side-banner:hover img { transform: scale(1.05); }

/* POLICIES */
.policies { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 60px; }
.policy-card { background: #fff; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: 0.3s; }
.policy-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.06); }
.policy-icon { width: 54px; height: 54px; border-radius: 50%; background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309; display: flex; justify-content: center; align-items: center; font-size: 22px; flex-shrink: 0; }
.policy-text h4 { margin: 0 0 4px; font-size: 15px; font-weight: 700; color: #0f2942; }
.policy-text p { margin: 0; font-size: 13px; color: #64748b; line-height: 1.4; }

/* SECTION TITLES */
.section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 12px; }
.section-title { font-size: 24px; font-weight: 800; color: #0f2942; margin: 0; position: relative; text-transform: uppercase; letter-spacing: 0.5px; }
.section-title::after { content: ''; position: absolute; left: 0; bottom: -14px; height: 3px; width: 60px; background: #d4af37; }
.section-link { font-size: 14px; font-weight: 600; color: #d4af37; text-decoration: none; transition: 0.3s; display: flex; align-items: center; gap: 5px; }
.section-link:hover { color: #0f2942; }

/* PRODUCT GRID */
.product-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 60px; }
.product-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04); transition: 0.4s ease; text-decoration: none; display: flex; flex-direction: column; border: 1px solid #f1f5f9; position: relative; }
.product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.08); border-color: #e2e8f0; text-decoration: none; }
.product-img { height: 260px; position: relative; overflow: hidden; background: #f8fafc; }
.product-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s ease; }
.product-card:hover .product-img img { transform: scale(1.08); }
.product-badge { position: absolute; top: 12px; left: 12px; background: linear-gradient(135deg, #d4af37, #b8962e); color: #fff; font-size: 10px; font-weight: 700; padding: 4px 10px; border-radius: 6px; z-index: 2; letter-spacing: 0.5px; text-transform: uppercase; }
.product-info { padding: 20px; display: flex; flex-direction: column; gap: 8px; flex-grow: 1; }
.product-title { font-size: 15px; font-weight: 600; color: #1a2535; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5; transition: 0.2s; }
.product-card:hover .product-title { color: #d4af37; }
.product-rating { display: flex; gap: 4px; color: #fbbf24; font-size: 12px; }
.product-price { font-size: 18px; font-weight: 800; color: #e53e3e; margin-top: auto; }

/* FLASH SALE SPECIFIC */
.fs-section { background: linear-gradient(135deg, #0f2942, #1a4a7a); padding: 40px 30px; margin-bottom: 60px; border-radius: 20px; color: #fff; box-shadow: 0 15px 40px rgba(15,41,66,0.2); }
.fs-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.fs-title { display: flex; align-items: center; gap: 15px; font-size: 28px; font-weight: 800; font-style: italic; color: #fff; margin: 0; letter-spacing: 1px; }
.fs-title i { color: #facc15; font-size: 32px; animation: pulse 2s infinite; }
@keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
.fs-timer { display: flex; gap: 8px; align-items: center; }
.fs-time-box { background: #fff; color: #0f2942; font-size: 18px; font-weight: 800; padding: 6px 12px; border-radius: 8px; min-width: 45px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
.fs-colon { font-size: 20px; font-weight: 800; color: #fff; }
.fs-link { color: #f8fafc; text-decoration: none; font-size: 14px; font-weight: 600; padding: 8px 16px; border: 1px solid rgba(255,255,255,0.3); border-radius: 20px; transition: 0.3s; }
.fs-link:hover { background: #fff; color: #0f2942; text-decoration: none; }
.fs-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 15px; }
.fs-card { background: #fff; border-radius: 12px; overflow: hidden; text-decoration: none; position: relative; transition: 0.3s; display: block; }
.fs-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.3); text-decoration: none; }
.fs-img-wrapper { height: 160px; position: relative; }
.fs-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
.fs-discount { position: absolute; top: 0; right: 0; background: linear-gradient(135deg, #ef4444, #b91c1c); color: #fff; font-size: 12px; font-weight: 800; padding: 4px 10px; border-bottom-left-radius: 10px; z-index: 2; box-shadow: -2px 2px 10px rgba(0,0,0,0.1); }
.fs-info { padding: 12px; text-align: center; display: flex; flex-direction: column; gap: 4px; }
.fs-price-old { font-size: 12px; color: #94a3b8; text-decoration: line-through; }
.fs-price-new { font-size: 16px; font-weight: 800; color: #e53e3e; margin-bottom: 6px; }
.fs-progress { height: 16px; background: #fee2e2; border-radius: 10px; position: relative; overflow: hidden; }
.fs-progress-fill { background: linear-gradient(90deg, #f59e0b, #ef4444); height: 100%; border-radius: 10px; }
.fs-progress-text { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 800; color: #fff; text-transform: uppercase; text-shadow: 0 1px 2px rgba(0,0,0,0.3); }

/* OFFER SECTION */
.offer-section { background: linear-gradient(rgba(15,41,66,0.85), rgba(15,41,66,0.85)), url('https://images.unsplash.com/photo-1547996160-81dfa63595aa?q=80&w=2070&auto=format&fit=crop') no-repeat center/center/cover; padding: 80px 20px; margin-bottom: 60px; border-radius: 24px; color: #fff; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.offer-content { max-width: 600px; margin: 0 auto; }
.offer-content h4 { color: #d4af37; font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 15px; }
.offer-content h2 { font-size: 42px; font-weight: 800; margin-bottom: 20px; line-height: 1.2; font-family: 'Cinzel', serif; }
.offer-content p { font-size: 16px; color: #cbd5e1; margin-bottom: 35px; line-height: 1.6; }
.btn-gold { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #d4af37, #b8962e); color: #fff; font-size: 15px; font-weight: 700; padding: 16px 40px; border-radius: 30px; text-decoration: none; transition: 0.3s; box-shadow: 0 4px 15px rgba(212,175,55,0.4); text-transform: uppercase; letter-spacing: 1px; }
.btn-gold:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(212,175,55,0.6); color: #fff; text-decoration: none; }

/* TESTIMONIALS */
.testimonials { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 80px; }
.review-card { background: #fff; padding: 35px 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); position: relative; text-align: center; transition: 0.3s; }
.review-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.08); }
.review-icon { position: absolute; top: 25px; left: 30px; font-size: 40px; color: #f1f5f9; z-index: 0; }
.review-text { font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 25px; position: relative; z-index: 1; font-style: italic; }
.review-rating { color: #fbbf24; font-size: 14px; margin-bottom: 20px; display: flex; justify-content: center; gap: 4px; }
.review-user img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid #f8fafc; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.review-user h4 { margin: 0; font-size: 16px; font-weight: 700; color: #0f2942; }

/* BRANDS */
.brand-logos { display: flex; justify-content: space-around; align-items: center; padding: 40px 0; border-top: 1px solid #e2e8f0; opacity: 0.6; transition: 0.3s; flex-wrap: wrap; gap: 20px;}
.brand-logos:hover { opacity: 1; }
.brand-logos h3 { font-size: 26px; font-weight: 800; color: #94a3b8; margin: 0; font-family: 'Cinzel', serif; text-transform: uppercase; letter-spacing: 2px;}

/* PAGINATION OVERRIDES */
.sp-pagination { display: flex !important; justify-content: center !important; gap: 8px !important; margin-top: 40px !important; margin-bottom: 20px !important; padding: 0 !important; width: 100% !important; }
.page-btn { width: 40px !important; height: 40px !important; margin: 0 !important; padding: 0 !important; border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important; background: #fff !important; color: #475569 !important; font-weight: 600 !important; font-size: 14px !important; display: flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important; transition: 0.2s !important; font-family: inherit !important; flex-shrink: 0 !important; box-sizing: border-box !important; text-decoration: none !important; }
.page-btn:hover { border-color: #1a4a7a !important; color: #1a4a7a !important; transform: translateY(-2px); text-decoration: none !important; }
.page-btn.active { background: linear-gradient(135deg, #0f2942, #1a4a7a) !important; color: #fff !important; border-color: transparent !important; pointer-events: none; }

@media (max-width: 1024px) {
    .product-grid { grid-template-columns: repeat(3, 1fr); }
    .fs-grid { grid-template-columns: repeat(4, 1fr); }
    .fs-section { padding: 30px 20px; }
}
@media (max-width: 768px) {
    .hero-section { flex-direction: column; }
    .policies { grid-template-columns: 1fr; gap: 15px; }
    .product-grid { grid-template-columns: repeat(2, 1fr); }
    .fs-grid { grid-template-columns: repeat(3, 1fr); }
    .testimonials { grid-template-columns: 1fr; }
    .fs-header { flex-direction: column; gap: 15px; align-items: flex-start; }
    .offer-section { padding: 50px 20px; }
    .offer-content h2 { font-size: 32px; }
}
@media (max-width: 480px) {
    .fs-grid { grid-template-columns: repeat(2, 1fr); }
    .product-grid { grid-template-columns: 1fr; }
}
</style>

<div class="home-wrapper">
    <div class="container">
        <!-- HERO SECTION -->
        <div class="hero-section">
            <div class="hero-main" id="mainBannerCarousel">
                <?php if (!empty($banners['main'])): ?>
                    <?php foreach ($banners['main'] as $index => $banner): ?>
                        <a href="<?= htmlspecialchars($banner['link']) ?>">
                            <img src="<?= htmlspecialchars($banner['hinh_anh']) ?>" alt="Main Banner" class="<?= $index === 0 ? 'active' : '' ?>">
                        </a>
                    <?php endforeach; ?>
                    <div class="banner-dots">
                        <?php foreach ($banners['main'] as $index => $banner): ?>
                            <div class="banner-dot <?= $index === 0 ? 'active' : '' ?>" data-slide="<?= $index ?>"></div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <a href="#">
                        <img src="https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?q=80&w=1974&auto=format&fit=crop" alt="Default Banner" class="active">
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="hero-side">
                <?php if (!empty($banners['side1'])): ?>
                    <a href="<?= htmlspecialchars($banners['side1'][0]['link']) ?>" class="hero-side-banner">
                        <img src="<?= htmlspecialchars($banners['side1'][0]['hinh_anh']) ?>" alt="Side Banner 1">
                    </a>
                <?php else: ?>
                    <a href="#" class="hero-side-banner">
                        <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=1988&auto=format&fit=crop" alt="Side Banner Default">
                    </a>
                <?php endif; ?>

                <?php if (!empty($banners['side2'])): ?>
                    <a href="<?= htmlspecialchars($banners['side2'][0]['link']) ?>" class="hero-side-banner">
                        <img src="<?= htmlspecialchars($banners['side2'][0]['hinh_anh']) ?>" alt="Side Banner 2">
                    </a>
                <?php else: ?>
                    <a href="#" class="hero-side-banner">
                        <img src="https://images.unsplash.com/photo-1587836374828-cb43870b9e23?q=80&w=2070&auto=format&fit=crop" alt="Side Banner Default">
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- POLICIES -->
        <div class="policies">
            <div class="policy-card">
                <div class="policy-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="policy-text">
                    <h4>Bảo hành quốc tế 5 năm</h4>
                    <p>Cam kết chất lượng tuyệt đối cho mọi sản phẩm.</p>
                </div>
            </div>
            <div class="policy-card">
                <div class="policy-icon"><i class="fas fa-check-circle"></i></div>
                <div class="policy-text">
                    <h4>Chính hãng 100%</h4>
                    <p>Hoàn tiền gấp 10 lần nếu phát hiện hàng giả.</p>
                </div>
            </div>
            <div class="policy-card">
                <div class="policy-icon"><i class="fas fa-shipping-fast"></i></div>
                <div class="policy-text">
                    <h4>Giao hàng hỏa tốc</h4>
                    <p>Miễn phí vận chuyển tận tay trên toàn quốc.</p>
                </div>
            </div>
        </div>

        <!-- FLASH SALE -->
        <?php if (!empty($flash_sale_products) && count($flash_sale_products) > 0): ?>
        <div class="fs-section">
            <div class="fs-header">
                <h2 class="fs-title"><i class="fas fa-bolt"></i> ƯU ĐÃI ĐỘC QUYỀN</h2>
                <?php 
                $max_end_time = 0;
                foreach (array_slice($flash_sale_products, 0, 6) as $fs_product) {
                    $endTimeTs = strtotime($fs_product['flash_sale_end']);
                    if ($endTimeTs > $max_end_time) $max_end_time = $endTimeTs;
                }
                ?>
                <div class="fs-timer" id="flash-sale-timer">
                    <span class="fs-time-box">00</span><span class="fs-colon">:</span>
                    <span class="fs-time-box">00</span><span class="fs-colon">:</span>
                    <span class="fs-time-box">00</span>
                </div>
                <a href="index.php?action=sanpham" class="fs-link">Xem tất cả ưu đãi <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></a>
            </div>

            <div class="fs-grid">
                <?php foreach (array_slice($flash_sale_products, 0, 6) as $fs_product) { 
                    $giaGoc = (float)$fs_product['gia_sp'];
                    $giaFS = (float)$fs_product['flash_sale_price'];
                    $ptGiam = ($giaGoc > 0) ? round((($giaGoc - $giaFS) / $giaGoc) * 100) : 0;
                ?>
                <a href="index.php?action=chitietsanpham&id=<?=$fs_product['id_sp']; ?>" class="fs-card">
                    <?php if($ptGiam > 0): ?>
                        <div class="fs-discount">-<?=$ptGiam?>%</div>
                    <?php endif; ?>
                    <div class="fs-img-wrapper">
                        <img src="<?=$fs_product['hinhanh_sp']?>" alt="">
                    </div>
                    <div class="fs-info">
                        <div class="fs-price-old"><?=number_format($giaGoc,0,',','.')?> đ</div>
                        <div class="fs-price-new"><?=number_format($giaFS,0,',','.')?> ₫</div>
                        <div class="fs-progress">
                            <div class="fs-progress-fill" style="width: <?php echo rand(70, 95); ?>%;"></div>
                            <div class="fs-progress-text">Chỉ còn ít suất</div>
                        </div>
                    </div>
                </a>
                <?php } ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- FEATURED PRODUCTS -->
        <div class="section-header">
            <h2 class="section-title">Bộ Sưu Tập Nổi Bật</h2>
            <a href="index.php?action=sanpham" class="section-link">Xem thêm <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="product-grid">
            <?php 
            if(!empty($spnoibat)){
                foreach (array_slice($spnoibat, 0, 8) as $value) {
            ?>
            <a href="index.php?action=chitietsanpham&id=<?=$value['id_sp']; ?>" class="product-card">
                <div class="product-badge">Top Picks</div>
                <div class="product-img">
                    <img src="<?php echo $value['hinhanh_sp'];?>" alt="">
                </div>
                <div class="product-info">
                    <h4 class="product-title"><?php echo $value['ten_sp'];?></h4>
                    <div class="product-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="product-price"><?php echo number_format($value['gia_sp'], 0, ',', '.');?> ₫</div>
                </div>
            </a>
            <?php }} else { echo "<p>Đang cập nhật...</p>"; } ?>
        </div>

        <!-- OFFER BANNER -->
        <div class="offer-section">
            <div class="offer-content">
                <h4>Tinh Hoa Chế Tác</h4>
                <h2>CHRONOLUX EXCLUSIVE</h2>
                <p>Khám phá những cỗ máy thời gian được hoàn thiện thủ công tinh xảo, sử dụng vật liệu cao cấp nhất như Sapphire chống trầy xước và bộ máy cơ khí chính xác đến từng phần trăm giây.</p>
                <a href="index.php?action=sanpham" class="btn-gold">Khám Phá Ngay <i class="fas fa-play" style="font-size: 12px;"></i></a>
            </div>
        </div>

        <!-- NEWEST PRODUCTS -->
        <div class="section-header" id="new-products">
            <h2 class="section-title">Hàng Mới Về</h2>
            <a href="index.php?action=sanpham" class="section-link">Tất cả sản phẩm <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="product-grid" style="margin-bottom: 20px;">
            <?php 
            if(!empty($spmoinhat)){
                $limit_new = 8; 
                $page_new = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                if ($page_new < 1) $page_new = 1;
                $total_new = count($spmoinhat);
                $total_pages_new = ceil($total_new / $limit_new);
                if ($page_new > $total_pages_new && $total_pages_new > 0) $page_new = $total_pages_new;
                
                $offset_new = ($page_new - 1) * $limit_new;
                $paginated_spmoinhat = array_slice($spmoinhat, $offset_new, $limit_new);

                foreach ($paginated_spmoinhat as $value) {
            ?>
            <a href="index.php?action=chitietsanpham&id=<?=$value['id_sp']; ?>" class="product-card">
                <div class="product-badge" style="background: linear-gradient(135deg, #0f2942, #1a4a7a);">Mới Nhất</div>
                <div class="product-img">
                    <img src="<?php echo $value['hinhanh_sp'];?>" alt="">
                </div>
                <div class="product-info">
                    <h4 class="product-title"><?php echo $value['ten_sp'];?></h4>
                    <div class="product-rating">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                    </div>
                    <div class="product-price"><?php echo number_format($value['gia_sp'], 0, ',', '.');?> ₫</div>
                </div>
            </a>
            <?php }} else { echo "<p>Đang cập nhật...</p>"; } ?>
        </div>

        <!-- PAGINATION FOR NEWEST PRODUCTS -->
        <?php if (!empty($total_pages_new) && $total_pages_new > 1): ?>
        <div class="sp-pagination">
            <?php if ($page_new > 1): ?>
                <a href="?action=home&page=<?= $page_new - 1 ?>#new-products" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            <?php endif; ?>
            
            <?php for($i=1; $i<=$total_pages_new; $i++): ?>
                <a href="?action=home&page=<?= $i ?>#new-products" class="page-btn <?= $i === $page_new ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($page_new < $total_pages_new): ?>
                <a href="?action=home&page=<?= $page_new + 1 ?>#new-products" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- TESTIMONIALS -->
        <div class="section-header" style="margin-top: 40px;">
            <h2 class="section-title">Khách Hàng Nói Gì?</h2>
        </div>
        <div class="testimonials">
            <div class="review-card">
                <i class="fas fa-quote-left review-icon"></i>
                <div class="review-text">"ChronoLux là nơi tôi tìm thấy chiếc đồng hồ phù hợp cho công việc và sự kiện quan trọng. Thiết kế sang trọng, hoàn thiện tinh tế và đeo rất thoải mái."</div>
                <div class="review-rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                <div class="review-user">
                    <img src="images/user-1.png" onerror="this.src='https://ui-avatars.com/api/?name=Ngoc+Anh&background=d4af37&color=fff'">
                    <h4>Ngọc Anh</h4>
                </div>
            </div>
            <div class="review-card">
                <i class="fas fa-quote-left review-icon"></i>
                <div class="review-text">"Dịch vụ tư vấn rất chuyên nghiệp, giúp tôi chọn đúng mẫu đồng hồ cơ phù hợp cổ tay và nhu cầu sử dụng hàng ngày. Chính sách bảo hành rõ ràng."</div>
                <div class="review-rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <div class="review-user">
                    <img src="images/user-namhai.png" onerror="this.src='https://ui-avatars.com/api/?name=Nam+Hai&background=0f2942&color=fff'">
                    <h4>Nam Hải</h4>
                </div>
            </div>
            <div class="review-card">
                <i class="fas fa-quote-left review-icon"></i>
                <div class="review-text">"Tôi thích nhất là bộ sưu tập đồng hồ tối giản của ChronoLux. Mẫu mã đa dạng, dây đeo đẹp và dễ phối đồ cho cả đi làm lẫn đi chơi."</div>
                <div class="review-rating"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i></div>
                <div class="review-user">
                    <img src="images/user-3.png" onerror="this.src='https://ui-avatars.com/api/?name=Thuy+Linh&background=d4af37&color=fff'">
                    <h4>Thùy Linh</h4>
                </div>
            </div>
        </div>

        <!-- LUXURY BRANDS -->
        <div class="brand-logos">
            <h3>Rolex</h3>
            <h3>Omega</h3>
            <h3>Patek Philippe</h3>
            <h3>Audemars Piguet</h3>
            <h3>Cartier</h3>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carousel Logic
    const carousel = document.getElementById('mainBannerCarousel');
    if (carousel) {
        const images = carousel.querySelectorAll('img');
        const dots = carousel.querySelectorAll('.banner-dot');
        if (images.length > 1) {
            let currentSlide = 0;
            function showSlide(n) {
                images[currentSlide].classList.remove('active');
                if(dots[currentSlide]) dots[currentSlide].classList.remove('active');
                currentSlide = (n + images.length) % images.length;
                images[currentSlide].classList.add('active');
                if(dots[currentSlide]) dots[currentSlide].classList.add('active');
            }
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                    resetTimer();
                });
            });
            let timer;
            function startTimer() { timer = setInterval(() => showSlide(currentSlide + 1), 4000); }
            function resetTimer() { clearInterval(timer); startTimer(); }
            startTimer();
        }
    }

    // Flash Sale Timer Logic
    <?php if (!empty($max_end_time)): ?>
    const maxEndTime = <?php echo $max_end_time * 1000; ?>;
    function updateFSTimer() {
        const now = new Date().getTime();
        const distance = maxEndTime - now;
        if (distance < 0) {
            const timerEl = document.getElementById("flash-sale-timer");
            if(timerEl) timerEl.innerHTML = "<span style='color:#fff;font-weight:bold;'>Đã kết thúc</span>";
            return;
        }
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        const timeBoxes = document.querySelectorAll('.fs-time-box');
        if(timeBoxes.length >= 3) {
            timeBoxes[0].innerText = hours.toString().padStart(2, '0');
            timeBoxes[1].innerText = minutes.toString().padStart(2, '0');
            timeBoxes[2].innerText = seconds.toString().padStart(2, '0');
        }
    }
    setInterval(updateFSTimer, 1000);
    updateFSTimer();
    <?php endif; ?>
});
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>