<?php
$pageTitle = "Giới thiệu - ChronoLux";
ob_start();
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
.gt-page { font-family:'Inter',sans-serif; background:#f0f4f8; }

/* HERO */
.gt-hero { background:linear-gradient(135deg,#0f2942 0%,#1a4a7a 50%,#0f2942 100%); padding:80px 20px; text-align:center; position:relative; overflow:hidden; }
.gt-hero::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
.gt-hero-inner { position:relative; max-width:700px; margin:0 auto; }
.gt-hero-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(212,175,55,.15); border:1px solid rgba(212,175,55,.4); color:#d4af37; font-size:12px; font-weight:700; letter-spacing:2px; text-transform:uppercase; padding:6px 18px; border-radius:20px; margin-bottom:24px; }
.gt-hero h1 { font-size:48px; font-weight:900; color:#fff; margin:0 0 16px; line-height:1.15; }
.gt-hero h1 span { background:linear-gradient(135deg,#d4af37,#f0d060); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.gt-hero p { font-size:17px; color:rgba(255,255,255,.75); line-height:1.7; margin:0; }

/* WRAP */
.gt-wrap { max-width:1100px; margin:0 auto; padding:0 20px; }

/* ABOUT SECTION */
.gt-about { display:grid; grid-template-columns:1fr 1fr; gap:48px; align-items:center; padding:64px 0; }
.gt-about-img { border-radius:24px; overflow:hidden; box-shadow:0 24px 64px rgba(0,0,0,.15); position:relative; }
.gt-about-img img { width:100%; display:block; }
.gt-about-img::after { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(212,175,55,.1),transparent); border-radius:24px; }
.gt-about-img-badge { position:absolute; bottom:20px; left:20px; background:#fff; border-radius:12px; padding:12px 18px; box-shadow:0 8px 24px rgba(0,0,0,.15); display:flex; align-items:center; gap:10px; z-index:2; }
.gt-about-img-badge span { font-size:28px; font-weight:900; color:#0f2942; }
.gt-about-img-badge small { font-size:12px; color:#64748b; font-weight:600; line-height:1.3; display:block; }
.gt-about-text {}
.gt-eyebrow { font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:#d4af37; margin-bottom:12px; }
.gt-about-text h2 { font-size:34px; font-weight:800; color:#1a2535; line-height:1.25; margin:0 0 20px; }
.gt-about-text p { font-size:15px; color:#64748b; line-height:1.8; margin-bottom:16px; }

/* STATS */
.gt-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; padding:0 0 48px; }
.gt-stat { background:#fff; border-radius:16px; padding:24px 20px; text-align:center; box-shadow:0 4px 20px rgba(0,0,0,.06); border:1px solid #f0f4f8; transition:.2s; }
.gt-stat:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(0,0,0,.1); }
.gt-stat-icon { font-size:28px; margin-bottom:12px; }
.gt-stat-num { font-size:32px; font-weight:900; color:#0f2942; line-height:1; }
.gt-stat-lbl { font-size:13px; color:#94a3b8; font-weight:600; margin-top:4px; }

/* VALUES */
.gt-values-section { padding:0 0 64px; }
.gt-section-header { text-align:center; margin-bottom:40px; }
.gt-section-header h2 { font-size:32px; font-weight:800; color:#1a2535; margin:8px 0; }
.gt-values { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
.gt-value-card { background:#fff; border-radius:20px; padding:28px; box-shadow:0 4px 20px rgba(0,0,0,.06); border:1px solid #f0f4f8; transition:.2s; }
.gt-value-card:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(0,0,0,.1); border-color:#e2e8f0; }
.gt-value-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:16px; }
.gt-value-card h3 { font-size:16px; font-weight:700; color:#1a2535; margin:0 0 8px; }
.gt-value-card p { font-size:14px; color:#64748b; line-height:1.7; margin:0; }

/* PRODUCTS */
.gt-products-section { padding:0 0 64px; }
.gt-products-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
.gt-prod-card { background:#fff; border-radius:14px; overflow:hidden; border:1px solid #f0f4f8; transition:.2s; text-decoration:none; display:block; }
.gt-prod-card:hover { box-shadow:0 12px 32px rgba(0,0,0,.1); transform:translateY(-3px); }
.gt-prod-card img { width:100%; height:160px; object-fit:cover; display:block; transition:.3s; }
.gt-prod-card:hover img { transform:scale(1.05); }
.gt-prod-body { padding:12px; }
.gt-prod-name { font-size:13px; font-weight:600; color:#1a2535; margin-bottom:6px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.gt-prod-price { font-size:15px; font-weight:800; color:#e53e3e; }

/* CTA */
.gt-cta { background:linear-gradient(135deg,#0f2942,#1a4a7a); border-radius:24px; padding:56px 40px; text-align:center; margin-bottom:48px; position:relative; overflow:hidden; }
.gt-cta::before { content:'⌚'; position:absolute; font-size:200px; opacity:.04; top:-30px; right:-20px; }
.gt-cta h2 { font-size:30px; font-weight:800; color:#fff; margin:0 0 12px; }
.gt-cta p { font-size:16px; color:rgba(255,255,255,.75); margin:0 0 28px; }
.gt-cta-btn { display:inline-flex; align-items:center; gap:10px; padding:14px 36px; background:linear-gradient(135deg,#d4af37,#b8962e); color:#fff; border-radius:12px; text-decoration:none; font-weight:800; font-size:15px; transition:.2s; }
.gt-cta-btn:hover { opacity:.9; transform:translateY(-2px); box-shadow:0 8px 24px rgba(212,175,55,.4); color:#fff; }

@media(max-width:900px){.gt-about{grid-template-columns:1fr}.gt-stats{grid-template-columns:repeat(2,1fr)}.gt-values{grid-template-columns:1fr}}
@media(max-width:600px){.gt-hero h1{font-size:32px}.gt-products-grid{grid-template-columns:repeat(2,1fr)}}
</style>

<!-- HERO -->
<div class="gt-hero">
    <div class="gt-hero-inner">
        <div class="gt-hero-badge"><i class="fas fa-clock"></i> Thương hiệu đồng hồ uy tín</div>
        <h1>Chào mừng đến với<br><span>ChronoLux</span></h1>
        <p>Mang đến những mẫu đồng hồ chính hãng, tinh tế và đẳng cấp — phù hợp với mọi phong cách sống của người Việt hiện đại.</p>
    </div>
</div>

<div class="gt-wrap">

    <!-- ABOUT -->
    <div class="gt-about">
        <div class="gt-about-img" style="position:relative">
            <img src="images/logo.png" alt="ChronoLux Logo">
            <div class="gt-about-img-badge">
                <span>5+</span>
                <small>Năm kinh nghiệm<br>trong ngành</small>
            </div>
        </div>
        <div class="gt-about-text">
            <div class="gt-eyebrow">Câu chuyện của chúng tôi</div>
            <h2>Lý do ChronoLux ra đời</h2>
            <p>ChronoLux ra đời từ niềm đam mê với nghệ thuật chế tác đồng hồ và mong muốn mang các mẫu đồng hồ chất lượng cao đến gần hơn với người Việt.</p>
            <p>Chúng tôi tập trung vào các dòng đồng hồ chính hãng có thiết kế tinh tế, độ bền cao và mức giá hợp lý. Mỗi sản phẩm đều được chọn lọc kỹ về chất liệu, bộ máy và trải nghiệm đeo thực tế.</p>
            <p>ChronoLux cung cấp đa dạng đồng hồ nam, nữ, unisex: từ phong cách cổ điển, tối giản đến thể thao hiện đại với bộ máy quartz hoặc automatic ổn định.</p>
        </div>
    </div>

    <!-- STATS -->
    <div class="gt-stats">
        <div class="gt-stat">
            <div class="gt-stat-icon">⌚</div>
            <div class="gt-stat-num">500+</div>
            <div class="gt-stat-lbl">Mẫu đồng hồ</div>
        </div>
        <div class="gt-stat">
            <div class="gt-stat-icon">😊</div>
            <div class="gt-stat-num">10K+</div>
            <div class="gt-stat-lbl">Khách hàng hài lòng</div>
        </div>
        <div class="gt-stat">
            <div class="gt-stat-icon">🏆</div>
            <div class="gt-stat-num">50+</div>
            <div class="gt-stat-lbl">Thương hiệu uy tín</div>
        </div>
        <div class="gt-stat">
            <div class="gt-stat-icon">🚚</div>
            <div class="gt-stat-num">63</div>
            <div class="gt-stat-lbl">Tỉnh thành giao hàng</div>
        </div>
    </div>

    <!-- VALUES -->
    <div class="gt-values-section">
        <div class="gt-section-header">
            <div class="gt-eyebrow">Cam kết của chúng tôi</div>
            <h2>Tiêu chí bán hàng</h2>
        </div>
        <div class="gt-values">
            <?php
            $values = [
                ['icon'=>'fas fa-certificate','color'=>'#fef3c7','ic'=>'#d97706','title'=>'Chính hãng 100%','desc'=>'Mọi sản phẩm đều có nguồn gốc rõ ràng, giấy tờ minh bạch và được kiểm định trước khi đến tay khách hàng.'],
                ['icon'=>'fas fa-headset','color'=>'#dbeafe','ic'=>'#1d4ed8','title'=>'Tư vấn tận tâm','desc'=>'Đội ngũ tư vấn am hiểu sản phẩm, hỗ trợ khách hàng chọn đúng mẫu phù hợp nhu cầu và ngân sách.'],
                ['icon'=>'fas fa-shield-alt','color'=>'#dcfce7','ic'=>'#15803d','title'=>'Bảo hành minh bạch','desc'=>'Chính sách bảo hành rõ ràng, hỗ trợ nhanh chóng. Đổi trả linh hoạt trong 7 ngày nếu có lỗi từ nhà sản xuất.'],
                ['icon'=>'fas fa-shipping-fast','color'=>'#f3e8ff','ic'=>'#7c3aed','title'=>'Giao hàng toàn quốc','desc'=>'Giao nhanh toàn quốc, đóng gói cẩn thận đảm bảo sản phẩm đến tay nguyên vẹn và đúng hẹn.'],
                ['icon'=>'fas fa-star','color'=>'#fff1f2','ic'=>'#be123c','title'=>'Chất lượng vượt trội','desc'=>'Chỉ chọn các mẫu đạt tiêu chuẩn cao về bộ máy, mặt kính, dây đeo — đảm bảo bền đẹp theo thời gian.'],
                ['icon'=>'fas fa-lock','color'=>'#f0f9ff','ic'=>'#0369a1','title'=>'Thanh toán an toàn','desc'=>'Hỗ trợ nhiều hình thức thanh toán bảo mật: COD, MoMo, VNPay — tiện lợi và an toàn tuyệt đối.'],
            ];
            foreach ($values as $v): ?>
            <div class="gt-value-card">
                <div class="gt-value-icon" style="background:<?=$v['color']?>"><i class="<?=$v['icon']?>" style="color:<?=$v['ic']?>"></i></div>
                <h3><?=$v['title']?></h3>
                <p><?=$v['desc']?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- FEATURED PRODUCTS -->
    <?php if (!empty($spnn) && $spnn !== 0): ?>
    <div class="gt-products-section">
        <div class="gt-section-header">
            <div class="gt-eyebrow">Bộ sưu tập</div>
            <h2>Sản phẩm tiêu biểu</h2>
        </div>
        <div class="gt-products-grid">
        <?php foreach ((array)$spnn as $value): ?>
            <a href="index.php?action=chitietsanpham&id=<?= $value['id_sp'] ?>" class="gt-prod-card">
                <div style="overflow:hidden"><img src="<?= $value['hinhanh_sp'] ?>" alt="<?= htmlspecialchars($value['ten_sp']) ?>"></div>
                <div class="gt-prod-body">
                    <div class="gt-prod-name"><?= htmlspecialchars($value['ten_sp']) ?></div>
                    <div style="color:#fbbf24;font-size:12px;margin-bottom:4px">★★★★☆</div>
                    <div class="gt-prod-price"><?= number_format($value['gia_sp'],0,',','.') ?>đ</div>
                </div>
            </a>
        <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:28px">
            <a href="index.php?action=sanpham" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border:2px solid #1a4a7a;color:#1a4a7a;border-radius:10px;font-weight:700;text-decoration:none;transition:.2s" onmouseover="this.style.background='#1a4a7a';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='#1a4a7a'">
                <i class="fas fa-th-large"></i> Xem toàn bộ sản phẩm
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- CTA -->
    <div class="gt-cta">
        <h2>Sẵn sàng tìm chiếc đồng hồ hoàn hảo?</h2>
        <p>Hơn 500 mẫu đồng hồ chính hãng đang chờ bạn khám phá</p>
        <a href="index.php?action=sanpham" class="gt-cta-btn"><i class="fas fa-shopping-bag"></i> Mua sắm ngay</a>
    </div>

</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>