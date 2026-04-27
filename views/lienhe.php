<?php
$pageTitle = "Liên hệ - ChronoLux";
ob_start();
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
.lh-page { font-family:'Inter',sans-serif; background:#f0f4f8; padding-bottom:60px; }

/* HERO */
.lh-hero { background:linear-gradient(135deg,#0f2942 0%,#1a4a7a 100%); padding:60px 20px; text-align:center; position:relative; overflow:hidden; }
.lh-hero::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
.lh-hero-inner { position:relative; max-width:800px; margin:0 auto; }
.lh-hero h1 { font-size:40px; font-weight:900; color:#fff; margin:0 0 16px; }
.lh-hero p { font-size:16px; color:rgba(255,255,255,.75); margin:0; line-height:1.6; }

/* MAIN WRAP */
.lh-wrap { max-width:1160px; margin:-40px auto 40px; padding:0 20px; position:relative; z-index:2; }

/* CONTACT CARDS & FORM */
.lh-grid { display:grid; grid-template-columns:400px 1fr; gap:24px; }

/* LEFT: INFO CARDS */
.lh-info-col { display:flex; flex-direction:column; gap:16px; }
.lh-card { background:#fff; border-radius:20px; padding:28px; box-shadow:0 12px 32px rgba(0,0,0,.06); border:1px solid #f0f4f8; transition:.2s; }
.lh-card:hover { transform:translateY(-3px); box-shadow:0 16px 40px rgba(0,0,0,.1); border-color:#e2e8f0; }
.lh-icon-box { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; margin-bottom:16px; }
.bg-blue { background:#eff6ff; color:#2563eb; }
.bg-gold { background:#fef3c7; color:#d97706; }
.bg-green { background:#f0fdf4; color:#16a34a; }
.lh-card h3 { font-size:16px; font-weight:700; color:#1a2535; margin:0 0 8px; }
.lh-card p { font-size:14px; color:#64748b; margin:0; line-height:1.6; }
.lh-card a { color:#1a4a7a; text-decoration:none; font-weight:600; transition:.2s; }
.lh-card a:hover { color:#d4af37; text-decoration:underline; }

/* RIGHT: FORM */
.lh-form-card { background:#fff; border-radius:20px; padding:40px; box-shadow:0 12px 32px rgba(0,0,0,.06); border:1px solid #f0f4f8; }
.lh-form-card h2 { font-size:26px; font-weight:800; color:#1a2535; margin:0 0 8px; }
.lh-form-card > p { font-size:14px; color:#64748b; margin:0 0 32px; }

.lh-row { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
.lh-group { display:flex; flex-direction:column; gap:8px; }
.lh-label { font-size:13px; font-weight:700; color:#1a2535; letter-spacing:.3px; }
.lh-input { width:100%; padding:14px 16px; border:1.5px solid #e2e8f0; border-radius:12px; font-size:14px; font-family:inherit; color:#1a2535; transition:.2s; box-sizing:border-box; background:#f8fafc; }
.lh-input:focus { outline:none; border-color:#1a4a7a; background:#fff; box-shadow:0 0 0 4px rgba(26,74,122,.1); }
.lh-input::placeholder { color:#cbd5e1; }
textarea.lh-input { resize:vertical; min-height:120px; }

.btn-submit { display:inline-flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:16px; background:linear-gradient(135deg,#0f2942,#1a4a7a); color:#fff; border:none; border-radius:12px; font-weight:800; font-size:15px; cursor:pointer; font-family:inherit; transition:.2s; margin-top:12px; }
.btn-submit:hover { opacity:.9; transform:translateY(-2px); box-shadow:0 8px 24px rgba(15,41,66,.3); }

/* MAP */
.lh-map { max-width:1160px; margin:0 auto 40px; padding:0 20px; }
.map-container { border-radius:20px; overflow:hidden; box-shadow:0 12px 32px rgba(0,0,0,.06); border:1px solid #f0f4f8; background:#fff; height:400px; }
.map-container iframe { width:100%; height:100%; border:none; display:block; }

/* FEATURED PRODUCTS */
.lh-products { max-width:1160px; margin:0 auto; padding:0 20px; }
.lh-sec-title { text-align:center; font-size:26px; font-weight:800; color:#1a2535; margin:0 0 32px; }
.lh-prod-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
.lh-prod-card { background:#fff; border-radius:16px; overflow:hidden; border:1px solid #f0f4f8; transition:.2s; text-decoration:none; display:block; }
.lh-prod-card:hover { box-shadow:0 12px 32px rgba(0,0,0,.1); transform:translateY(-3px); }
.lh-prod-card img { width:100%; height:180px; object-fit:cover; display:block; transition:.3s; }
.lh-prod-card:hover img { transform:scale(1.05); }
.lh-prod-body { padding:16px; }
.lh-prod-name { font-size:14px; font-weight:600; color:#1a2535; margin-bottom:8px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.4; height:40px; }
.lh-prod-price { font-size:16px; font-weight:800; color:#e53e3e; }
.lh-stars { color:#fbbf24; font-size:12px; margin-bottom:6px; }

@media(max-width:992px) {
    .lh-grid { grid-template-columns:1fr; }
    .lh-info-col { flex-direction:row; flex-wrap:wrap; }
    .lh-card { flex:1; min-width:250px; }
    .lh-prod-grid { grid-template-columns:repeat(2,1fr); }
}
@media(max-width:600px) {
    .lh-row { grid-template-columns:1fr; gap:16px; }
    .lh-form-card { padding:24px; }
}
</style>

<!-- HERO -->
<div class="lh-hero">
    <div class="lh-hero-inner">
        <h1>Liên hệ với chúng tôi</h1>
        <p>Chúng tôi luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn về sản phẩm, dịch vụ và chính sách của ChronoLux.</p>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="lh-wrap">
    <div class="lh-grid">
        
        <!-- LEFT: INFO -->
        <div class="lh-info-col">
            <div class="lh-card">
                <div class="lh-icon-box bg-blue"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Trụ sở chính</h3>
                <p>Z-115, Xã Quyết Thắng, TP.Thái Nguyên,<br>Tỉnh Thái Nguyên.</p>
            </div>
            
            <div class="lh-card">
                <div class="lh-icon-box bg-gold"><i class="fas fa-phone-alt"></i></div>
                <h3>Hotline hỗ trợ</h3>
                <p><strong>038983165 - NTL</strong></p>
                <p style="margin-top:4px">Thứ 2 - Chủ Nhật: 8:00 - 22:00</p>
            </div>

            <div class="lh-card">
                <div class="lh-icon-box bg-green"><i class="fas fa-share-alt"></i></div>
                <h3>Kết nối với ChronoLux</h3>
                <p style="margin-bottom:8px"><i class="fab fa-facebook" style="color:#1877F2;width:20px"></i> <a href="#" target="_blank">facebook.com/ChronoLuxWatch</a></p>
                <p><i class="fab fa-youtube" style="color:#FF0000;width:20px"></i> <a href="#" target="_blank">youtube.com/ChronoLuxWatch</a></p>
            </div>
        </div>

        <!-- RIGHT: FORM -->
        <div class="lh-form-card">
            <h2>Gửi tin nhắn cho chúng tôi</h2>
            <p>Vui lòng điền thông tin bên dưới, chuyên viên tư vấn sẽ liên hệ lại với bạn sớm nhất.</p>
            
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất có thể!'); this.reset();">
                <div class="lh-row">
                    <div class="lh-group">
                        <label class="lh-label">Họ và tên *</label>
                        <input type="text" class="lh-input" placeholder="Nguyễn Văn A" required>
                    </div>
                    <div class="lh-group">
                        <label class="lh-label">Số điện thoại *</label>
                        <input type="tel" class="lh-input" placeholder="0901 234 567" required>
                    </div>
                </div>
                
                <div class="lh-group" style="margin-bottom:20px">
                    <label class="lh-label">Địa chỉ Email</label>
                    <input type="email" class="lh-input" placeholder="email@example.com">
                </div>

                <div class="lh-group" style="margin-bottom:20px">
                    <label class="lh-label">Chủ đề cần hỗ trợ *</label>
                    <select class="lh-input" required>
                        <option value="" disabled selected>Chọn chủ đề...</option>
                        <option>Tư vấn mua đồng hồ</option>
                        <option>Hỗ trợ bảo hành / Sửa chữa</option>
                        <option>Phản ánh dịch vụ</option>
                        <option>Hợp tác kinh doanh</option>
                        <option>Khác</option>
                    </select>
                </div>

                <div class="lh-group">
                    <label class="lh-label">Nội dung tin nhắn *</label>
                    <textarea class="lh-input" placeholder="Vui lòng mô tả chi tiết yêu cầu của bạn..." required></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Gửi yêu cầu ngay
                </button>
            </form>
        </div>

    </div>
</div>

<!-- GOOGLE MAP -->
<div class="lh-map">
    <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14873.308960682973!2d105.80164805!3d21.553702!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31352723ec08bb41%3A0xda0c0a8501da86ee!2zxJDhuqFpIGjhu41jIFRyw6xuZyBIw7JhIEPDtG5nIG5naGnhu4dwIFRow6FpIE5ndXnDqm4!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<!-- FEATURED PRODUCTS -->
<?php if (!empty($spnn) && $spnn !== 0): ?>
<div class="lh-products">
    <h2 class="lh-sec-title">Sản phẩm tiêu biểu</h2>
    <div class="lh-prod-grid">
        <?php foreach ((array)$spnn as $value): ?>
            <a href="index.php?action=chitietsanpham&id=<?= $value['id_sp'] ?>" class="lh-prod-card">
                <img src="<?= $value['hinhanh_sp'] ?>" alt="<?= htmlspecialchars($value['ten_sp']) ?>">
                <div class="lh-prod-body">
                    <div class="lh-prod-name"><?= htmlspecialchars($value['ten_sp']) ?></div>
                    <div class="lh-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="lh-prod-price"><?= number_format($value['gia_sp'], 0, ',', '.') ?>đ</div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    
    <div style="text-align:center;margin-top:32px">
        <a href="index.php?action=sanpham" style="display:inline-flex;align-items:center;gap:8px;padding:12px 32px;background:linear-gradient(135deg,#0f2942,#1a4a7a);color:#fff;border-radius:12px;font-weight:700;text-decoration:none;transition:.2s" onmouseover="this.style.opacity='.9';this.style.transform='translateY(-2px)'" onmouseout="this.style.opacity='1';this.style.transform='none'">
            Xem thêm sản phẩm <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>