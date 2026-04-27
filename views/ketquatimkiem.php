<?php
$pageTitle = "Kết quả tìm kiếm - ChronoLux";
ob_start();
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .search-page {
        font-family: 'Inter', sans-serif;
        background: #f4f7f6;
        padding: 40px 0 80px;
        min-height: 80vh;
    }

    .search-page .container {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* ─── SEARCH HEADER ─── */
    .search-header {
        margin-bottom: 40px;
    }

    .search-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #0f2942;
        margin-bottom: 6px;
    }

    .search-header h1 span {
        color: #d4af37;
    }

    .search-count {
        font-size: 14px;
        color: #64748b;
    }

    .search-divider {
        height: 3px;
        width: 60px;
        background: linear-gradient(90deg, #d4af37, #0f2942);
        border-radius: 3px;
        margin: 14px 0 30px;
    }

    /* ─── SEARCH BAR INLINE ─── */
    .search-redo {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        max-width: 500px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        margin-bottom: 40px;
    }

    .search-redo input {
        flex: 1;
        padding: 13px 18px;
        border: none;
        outline: none;
        font-size: 15px;
        color: #1e293b;
        font-family: 'Inter', sans-serif;
        background: transparent;
    }

    .search-redo button {
        background: linear-gradient(135deg, #0f2942, #1a4a7a);
        color: #fff;
        border: none;
        padding: 13px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    .search-redo button:hover {
        background: linear-gradient(135deg, #1a4a7a, #0f2942);
    }

    /* ─── PRODUCT GRID ─── */
    .sq-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 60px;
    }

    .sq-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: all 0.35s ease;
        position: relative;
    }

    .sq-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border-color: #e2e8f0;
        text-decoration: none;
    }

    .sq-img {
        height: 240px;
        overflow: hidden;
        background: #f8fafc;
        position: relative;
    }

    .sq-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.5s ease;
    }

    .sq-card:hover .sq-img img {
        transform: scale(1.08);
    }

    .sq-info {
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex-grow: 1;
    }

    .sq-name {
        font-size: 15px;
        font-weight: 600;
        color: #1a2535;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.5;
        transition: 0.2s;
    }

    .sq-card:hover .sq-name { color: #d4af37; }

    .sq-rating {
        display: flex;
        gap: 3px;
        color: #fbbf24;
        font-size: 12px;
    }

    .sq-price {
        font-size: 18px;
        font-weight: 800;
        color: #e53e3e;
        margin-top: auto;
    }

    /* ─── NOT FOUND ─── */
    .not-found-box {
        text-align: center;
        padding: 80px 20px;
    }

    .not-found-box i {
        font-size: 80px;
        color: #cbd5e1;
        margin-bottom: 25px;
        display: block;
    }

    .not-found-box h2 {
        font-size: 24px;
        font-weight: 700;
        color: #0f2942;
        margin-bottom: 12px;
    }

    .not-found-box p {
        color: #64748b;
        font-size: 15px;
        max-width: 460px;
        margin: 0 auto 30px;
        line-height: 1.7;
    }

    .btn-back-shop {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #0f2942, #1a4a7a);
        color: #fff;
        padding: 14px 32px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        transition: 0.3s;
        box-shadow: 0 8px 20px rgba(15,41,66,0.2);
    }

    .btn-back-shop:hover {
        transform: translateY(-2px);
        color: #fff;
        text-decoration: none;
        box-shadow: 0 12px 25px rgba(15,41,66,0.3);
    }

    /* ─── RELATED SECTION ─── */
    .related-section {
        border-top: 2px solid #e2e8f0;
        padding-top: 50px;
    }

    .related-section .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 25px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
    }

    .related-section .section-title {
        font-size: 22px;
        font-weight: 800;
        color: #0f2942;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
    }

    .related-section .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -14px;
        height: 3px;
        width: 50px;
        background: #d4af37;
    }

    .related-section .section-link {
        font-size: 14px;
        font-weight: 600;
        color: #d4af37;
        text-decoration: none;
        transition: 0.3s;
    }

    .related-section .section-link:hover { color: #0f2942; text-decoration: none; }

    @media (max-width: 1024px) { .sq-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px)  { .sq-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px)  { .sq-grid { grid-template-columns: 1fr; } }
</style>

<div class="search-page">
    <div class="container">

        <?php if (!empty($kw)): ?>

            <div class="search-header">
                <h1>Kết quả cho "<span><?= htmlspecialchars($kw) ?></span>"</h1>
                <?php if (!empty($data_cart)): ?>
                    <p class="search-count"><i class="fas fa-boxes"></i> Tìm thấy <?= count($data_cart) ?> sản phẩm</p>
                <?php endif; ?>
                <div class="search-divider"></div>
            </div>

            <!-- Re-search bar -->
            <form class="search-redo" onsubmit="event.preventDefault(); const kw=this.querySelector('input').value.trim(); if(kw) window.location.href='index.php?action=ketquatimkiem&keyword='+encodeURIComponent(kw);">
                <input type="text" value="<?= htmlspecialchars($kw) ?>" placeholder="Tìm kiếm đồng hồ...">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>

            <?php if (!empty($data_cart)): ?>
                <div class="sq-grid">
                    <?php foreach ($data_cart as $row): ?>
                        <a class="sq-card" href="index.php?action=chitietsanpham&id=<?= $row['id_sp'] ?>">
                            <div class="sq-img">
                                <img src="<?= htmlspecialchars($row['hinhanh_sp']) ?>" alt="<?= htmlspecialchars($row['ten_sp']) ?>">
                            </div>
                            <div class="sq-info">
                                <h4 class="sq-name"><?= htmlspecialchars($row['ten_sp']) ?></h4>
                                <div class="sq-rating">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    <i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i>
                                </div>
                                <p class="sq-price"><?= number_format($row['gia_sp'], 0, ',', '.') ?>đ</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="not-found-box">
                    <i class="fas fa-search-minus"></i>
                    <h2>Không tìm thấy sản phẩm</h2>
                    <p>Rất tiếc, không có sản phẩm nào khớp với từ khóa "<strong><?= htmlspecialchars($kw) ?></strong>". Hãy thử lại với từ khóa khác hoặc khám phá toàn bộ bộ sưu tập của chúng tôi.</p>
                    <a href="index.php?action=sanpham" class="btn-back-shop"><i class="fas fa-store"></i> Xem tất cả sản phẩm</a>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <!-- Sản phẩm liên quan -->
        <?php if (!empty($spnn) && $spnn != 0): ?>
        <div class="related-section">
            <div class="section-header">
                <h2 class="section-title">Sản phẩm gợi ý</h2>
                <a href="index.php?action=sanpham" class="section-link">Xem tất cả <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="sq-grid">
                <?php foreach ($spnn as $value): ?>
                    <a class="sq-card" href="index.php?action=chitietsanpham&id=<?= $value['id_sp'] ?>">
                        <div class="sq-img">
                            <img src="<?= htmlspecialchars($value['hinhanh_sp']) ?>" alt="<?= htmlspecialchars($value['ten_sp']) ?>">
                        </div>
                        <div class="sq-info">
                            <h4 class="sq-name"><?= htmlspecialchars($value['ten_sp']) ?></h4>
                            <div class="sq-rating">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star-half-o"></i><i class="fa fa-star-o"></i>
                            </div>
                            <p class="sq-price"><?= number_format($value['gia_sp'], 0, ',', '.') ?>đ</p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>