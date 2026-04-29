<?php
$pageTitle = "Chi tiết đồng hồ - ChronoLux";
ob_start();
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.pd-page{font-family:'Inter',sans-serif;background:#f0f4f8;min-height:100vh;padding:28px 0 60px}
.pd-wrap{max-width:1160px;margin:0 auto;padding:0 20px}
.pd-bread{font-size:13px;color:#94a3b8;margin-bottom:20px;display:flex;align-items:center;gap:6px}
.pd-bread a{color:#1a4a7a;text-decoration:none;font-weight:500}.pd-bread a:hover{text-decoration:underline}
.pd-main{display:grid;grid-template-columns:420px 1fr;gap:28px;background:#fff;border-radius:20px;padding:32px;box-shadow:0 4px 24px rgba(0,0,0,.07);margin-bottom:24px}
.pd-img-wrap{border-radius:14px;overflow:hidden;border:1px solid #f0f4f8;background:#f8fafc}
.pd-img{width:100%;aspect-ratio:1;object-fit:cover;display:block;transition:.3s}
.pd-img:hover{transform:scale(1.03)}
.pd-badge{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#0f2942,#1a4a7a);color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;margin-bottom:14px;letter-spacing:.5px}
.pd-title{font-size:24px;font-weight:800;color:#1a2535;margin:0 0 16px;line-height:1.3}
.pd-price-box{background:linear-gradient(135deg,#fff5f5,#fff);border:1px solid #fecaca;border-radius:14px;padding:18px 22px;margin-bottom:22px}
.pd-price{font-size:32px;font-weight:800;color:#e53e3e}
.pd-price-label{font-size:12px;color:#94a3b8;margin-bottom:4px;font-weight:600;text-transform:uppercase;letter-spacing:.5px}
.pd-divider{border:none;border-top:1px solid #f0f4f8;margin:18px 0}
.pd-label{font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px}
.size-opts{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px}
.size-opt{padding:8px 16px;border:1.5px solid #e2e8f0;border-radius:10px;cursor:pointer;font-size:14px;font-weight:600;color:#475569;transition:.15s;user-select:none}
.size-opt:hover,.size-opt.active{border-color:#1a4a7a;color:#1a4a7a;background:#f0f6ff}
.qty-row{display:flex;align-items:center;gap:16px;margin-bottom:24px}
.qty-box{display:flex;align-items:center;border:1.5px solid #e2e8f0;border-radius:10px;overflow:hidden}
.qty-btn{width:36px;height:38px;background:#f8fafc;border:none;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#475569;transition:.15s}
.qty-btn:hover{background:#e2e8f0}
.qty-input{width:52px;height:38px;border:none;border-left:1.5px solid #e2e8f0;border-right:1.5px solid #e2e8f0;text-align:center;font-size:16px;font-weight:700;-moz-appearance:textfield}
.qty-input::-webkit-outer-spin-button,.qty-input::-webkit-inner-spin-button{-webkit-appearance:none}
.pd-actions{display:flex;gap:12px}
.btn-addcart{flex:1;height:50px;background:#fff;border:2px solid #1a4a7a;color:#1a4a7a;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:.2s;font-family:inherit}
.btn-addcart:hover{background:#f0f6ff}
.btn-buynow{flex:1;height:50px;background:linear-gradient(135deg,#0f2942,#1a4a7a);color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:.2s;font-family:inherit}
.btn-buynow:hover{opacity:.9;transform:translateY(-1px);box-shadow:0 8px 20px rgba(15,41,66,.3)}
.pd-desc-card{background:#fff;border-radius:20px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,.06);margin-bottom:24px}
.pd-section-title{font-size:17px;font-weight:800;color:#1a2535;margin-bottom:20px;padding-bottom:14px;border-bottom:2px solid #f0f4f8;display:flex;align-items:center;gap:10px}
.pd-section-title i{color:#d4af37}
.pd-desc-text{font-size:14px;line-height:1.8;color:#475569;white-space:pre-wrap}
.related-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.rel-card{background:#fff;border-radius:14px;overflow:hidden;border:1px solid #f0f4f8;transition:.2s;text-decoration:none;display:block}
.rel-card:hover{box-shadow:0 8px 28px rgba(0,0,0,.1);transform:translateY(-3px)}
.rel-card img{width:100%;height:160px;object-fit:cover;display:block}
.rel-card-body{padding:12px}
.rel-name{font-size:13px;font-weight:600;color:#1a2535;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.rel-price{font-size:15px;font-weight:800;color:#e53e3e}
/* REVIEWS */
.rv-card{background:#fff;border-radius:20px;padding:28px;box-shadow:0 4px 24px rgba(0,0,0,.06);margin-bottom:24px}
.rv-summary{display:grid;grid-template-columns:140px 1fr;gap:28px;margin-bottom:28px;padding-bottom:24px;border-bottom:1px solid #f0f4f8}
.rv-score-box{display:flex;flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg,#fff7ed,#fff);border:1px solid #fed7aa;border-radius:16px;padding:20px}
.rv-score{font-size:42px;font-weight:800;color:#ea580c;line-height:1}
.rv-stars-big{font-size:20px;color:#fbbf24;margin:6px 0}
.rv-count{font-size:12px;color:#94a3b8;font-weight:600}
.rv-bars{flex:1;display:flex;flex-direction:column;justify-content:center;gap:8px}
.rv-bar-row{display:flex;align-items:center;gap:10px;font-size:13px}
.rv-bar-lbl{width:36px;color:#64748b;font-weight:600;text-align:right}
.rv-bar-track{flex:1;height:8px;background:#f0f4f8;border-radius:4px;overflow:hidden}
.rv-bar-fill{height:100%;background:linear-gradient(90deg,#fbbf24,#f59e0b);border-radius:4px;transition:.5s}
.rv-bar-num{width:28px;color:#94a3b8;font-size:12px}
/* LOGIN GATE */
.rv-login-gate{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:14px;padding:32px;text-align:center;margin-bottom:24px}
.rv-login-gate i{font-size:36px;color:#cbd5e1;margin-bottom:12px;display:block}
.rv-login-gate p{color:#64748b;font-size:15px;margin-bottom:16px}
.btn-login-rv{display:inline-flex;align-items:center;gap:8px;padding:11px 28px;background:linear-gradient(135deg,#0f2942,#1a4a7a);color:#fff;border-radius:10px;text-decoration:none;font-weight:700;font-size:14px}
/* FORM */
.rv-form{background:#f8fafc;border-radius:14px;padding:24px;margin-bottom:28px;border:1px solid #f0f4f8}
.rv-user-tag{display:flex;align-items:center;gap:10px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 16px;margin-bottom:18px;font-size:14px;color:#1e40af}
.rv-user-tag i{color:#3b82f6}
.star-rating{display:flex;gap:8px;font-size:30px;margin-bottom:4px}
.star-rating .star{cursor:pointer;color:#e2e8f0;transition:.15s}
.star-rating .star:hover,.star-rating .star.active{color:#fbbf24;transform:scale(1.2)}
.rv-fg{margin-bottom:14px}
.rv-fg label{display:block;font-size:12px;font-weight:700;color:#64748b;margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px}
.rv-input{width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;font-family:inherit;box-sizing:border-box;transition:.2s}
.rv-input:focus{outline:none;border-color:#1a4a7a;box-shadow:0 0 0 3px rgba(26,74,122,.1)}
.btn-rv-submit{padding:12px 32px;background:linear-gradient(135deg,#d4af37,#b8962e);color:#fff;border:none;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer;font-family:inherit;transition:.2s}
.btn-rv-submit:hover{opacity:.9;transform:translateY(-1px)}
/* REVIEW ITEMS */
.rv-item{padding:18px;border:1px solid #f0f4f8;border-radius:12px;margin-bottom:12px;background:#fafbfc}
.rv-item-header{display:flex;justify-content:space-between;margin-bottom:8px}
.rv-author{font-weight:700;color:#1a2535;font-size:14px}
.rv-date{font-size:12px;color:#94a3b8}
.rv-item-stars{color:#fbbf24;font-size:14px;margin-bottom:6px}
.rv-item-content{font-size:14px;color:#475569;line-height:1.6}
.rv-item-title{font-weight:700;color:#1a2535}
.rv-empty{text-align:center;padding:40px;color:#94a3b8}
@media(max-width:900px){.pd-main{grid-template-columns:1fr}.related-grid{grid-template-columns:repeat(2,1fr)}.rv-summary{grid-template-columns:1fr}}
</style>

<?php
require_once BASE_PATH . '/models/CommentModel.php';
$commentModel = new CommentModel();
$id_sp  = $proInfo['id_sp'] ?? 0;
$stats  = $commentModel->getRatingStats($id_sp);
$comments = $commentModel->getCommentsByProductId($id_sp);
// $loggedIn, $currentUserName, $currentUserEmail are passed from controller
?>

<div class="pd-page">
<div class="pd-wrap">

    <!-- BREADCRUMB -->
    <div class="pd-bread">
        <a href="index.php">Trang chủ</a> <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <a href="index.php?action=sanpham">Đồng hồ</a> <i class="fas fa-chevron-right" style="font-size:10px"></i>
        <span style="color:#1a2535;font-weight:600"><?= htmlspecialchars($proInfo['ten_sp']) ?></span>
    </div>

    <!-- PRODUCT MAIN -->
    <div class="pd-main">
        <!-- IMAGE -->
        <div class="pd-img-wrap">
            <img src="<?= $proInfo['hinhanh_sp'] ?>" alt="<?= htmlspecialchars($proInfo['ten_sp']) ?>" class="pd-img" id="productImg">
        </div>

        <!-- INFO -->
        <div>
            <span class="pd-badge"><i class="fas fa-certificate"></i> Hàng chính hãng · Mall</span>
            
            <?php 
                $store = new StoreModel();
                $isFavMain = false;
                if ($loggedIn) {
                    $isFavMain = $store->kiemTraYeuThich($_SESSION['id_nd'], $proInfo['id_sp']);
                }
            ?>
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <h1 class="pd-title" style="margin-bottom: 8px;"><?= htmlspecialchars($proInfo['ten_sp']) ?></h1>
                <button onclick="toggleFavorite(<?=$proInfo['id_sp']?>); event.preventDefault();" class="btn btn-light border shadow-sm" style="border-radius: 50%; width: 45px; height: 45px; color: #dc3545;" title="Yêu thích">
                    <i id="fav-btn-<?=$proInfo['id_sp']?>" class="<?= $isFavMain ? 'fas text-danger' : 'far' ?> fa-heart" style="font-size: 1.2rem;"></i>
                </button>
            </div>

            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;font-size:14px">
                <span style="color:#fbbf24;font-size:16px">
                    <?php
                    $full = floor($stats['average']); $half = ($stats['average']-$full)>=0.5?1:0;
                    for($i=0;$i<$full;$i++) echo '★';
                    if($half) echo '½';
                    for($i=$full+$half;$i<5;$i++) echo '☆';
                    ?>
                </span>
                <span style="color:#94a3b8"><?= $stats['total'] ?> đánh giá</span>
                <span style="color:#e2e8f0">|</span>
                <span style="color:#94a3b8">Đã bán <strong style="color:#1a2535"><?= number_format($totalSold ?? 0, 0, ',', '.') ?></strong></span>
            </div>

            <?php
            $gia_ban = $proInfo['gia_sp'];
            $isFlashSale = false;
            if (!empty($proInfo['flash_sale_end']) && strtotime($proInfo['flash_sale_end']) > time() && !empty($proInfo['flash_sale_price'])) {
                $gia_ban = $proInfo['flash_sale_price'];
                $isFlashSale = true;
            }
            ?>
            <div class="pd-price-box">
                <div class="pd-price-label">Giá bán <?= $isFlashSale ? '<span style="color:#ee4d2d; font-weight:bold; margin-left:8px;"><i class="fas fa-bolt"></i> Đang trong Flash Sale</span>' : '' ?></div>
                <div class="pd-price">
                    <?php if ($isFlashSale): ?>
                        <span style="text-decoration:line-through; color:#94a3b8; font-size:20px; font-weight:600; margin-right:12px;"><?= number_format($proInfo['gia_sp'], 0, ',', '.') ?>đ</span>
                    <?php endif; ?>
                    <?= number_format($gia_ban, 0, ',', '.') ?>đ
                </div>
            </div>

            <form action="index.php?action=giohang&task=add" method="POST" id="addCartForm">
                <div class="pd-label">Kích thước</div>
                <div class="size-opts" id="sizeOptions">
                    <?php foreach ([36,37,38,39,40,41,42,43] as $i => $s): ?>
                    <div class="size-opt <?= $i===0?'active':'' ?>"><?= $s ?></div>
                    <?php endforeach; ?>
                    <input type="hidden" name="size" id="selectedSize" value="36">
                </div>

                <div class="pd-label">Số lượng</div>
                <div class="qty-row">
                    <div class="qty-box">
                        <button type="button" class="qty-btn" onclick="let i=document.getElementById('qtyInput');if(i.value>1)i.value--">−</button>
                        <input type="number" class="qty-input" id="qtyInput" min="1" max="<?= $proInfo['so_luong_ton'] ?>" value="1" name="quantity[<?= $id_sp ?>]">
                        <button type="button" class="qty-btn" onclick="let i=document.getElementById('qtyInput');if(i.value < <?= $proInfo['so_luong_ton'] ?>)i.value++">+</button>
                    </div>
                    <?php if ($proInfo['so_luong_ton'] > 0): ?>
                        <span style="font-size:13px;color:#94a3b8">Còn <?= $proInfo['so_luong_ton'] ?> sản phẩm</span>
                    <?php else: ?>
                        <span style="font-size:13px;color:#e53e3e;font-weight:700">Đã hết hàng</span>
                    <?php endif; ?>
                </div>

                <div class="pd-actions">
                    <?php if ($proInfo['so_luong_ton'] > 0): ?>
                        <button type="submit" class="btn-addcart"><i class="fas fa-cart-plus"></i> Thêm vào giỏ</button>
                        <button type="button" class="btn-buynow" onclick="document.getElementById('addCartForm').submit()"><i class="fas fa-bolt"></i> Mua ngay</button>
                    <?php else: ?>
                        <button type="button" class="btn-addcart" disabled style="background:#e2e8f0;border-color:#e2e8f0;color:#94a3b8;cursor:not-allowed">Hết hàng</button>
                        <button type="button" class="btn-buynow" disabled style="background:#e2e8f0;color:#94a3b8;cursor:not-allowed">Hết hàng</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- DESCRIPTION -->
    <div class="pd-desc-card">
        <div class="pd-section-title"><i class="fas fa-align-left"></i> Mô tả sản phẩm</div>
        <div class="pd-desc-text"><?= htmlspecialchars($proInfo['mota_sp'] ?? '') ?></div>
    </div>

    <!-- SIMILAR PRODUCTS -->
    <?php if (!empty($spnn) && $spnn !== 0): ?>
    <div class="pd-desc-card">
        <div class="pd-section-title"><i class="fas fa-th-large"></i> Sản phẩm tương tự</div>
        <div class="related-grid">
        <?php foreach (array_slice((array)$spnn, 0, 8) as $sp): 
                $isFavRel = false;
                if ($loggedIn) {
                    $isFavRel = $store->kiemTraYeuThich($_SESSION['id_nd'], $sp['id_sp']);
                }
        ?>
            <a href="index.php?action=chitietsanpham&id=<?= $sp['id_sp'] ?>" class="rel-card position-relative">
                <img src="<?= $sp['hinhanh_sp'] ?>" alt="<?= htmlspecialchars($sp['ten_sp']) ?>">
                <div class="rel-card-body">
                    <div class="rel-name"><?= htmlspecialchars($sp['ten_sp']) ?></div>
                    <div class="rel-price" style="display: flex; justify-content: space-between; align-items: center;">
                        <span><?= number_format($sp['gia_sp'],0,',','.') ?>đ</span>
                        <i id="fav-btn-<?=$sp['id_sp']?>" class="<?= $isFavRel ? 'fas text-danger' : 'far text-secondary' ?> fa-heart" onclick="toggleFavorite(<?=$sp['id_sp']?>); event.preventDefault();" style="cursor: pointer; font-size: 1.2rem;" title="Yêu thích"></i>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- REVIEWS -->
    <div class="rv-card">
        <div class="pd-section-title"><i class="fas fa-star"></i> Đánh giá & Bình luận</div>

        <!-- RATING SUMMARY -->
        <div class="rv-summary">
            <div class="rv-score-box">
                <div class="rv-score"><?= number_format($stats['average'],1) ?></div>
                <div class="rv-stars-big">
                    <?php
                    $full = floor($stats['average']); $half = ($stats['average']-$full)>=0.5?1:0;
                    for($i=0;$i<$full;$i++) echo '★';
                    if($half) echo '½';
                    for($i=$full+$half;$i<5;$i++) echo '☆';
                    ?>
                </div>
                <div class="rv-count"><?= $stats['total'] ?> đánh giá</div>
            </div>
            <div class="rv-bars">
                <?php for($i=5;$i>=1;$i--):
                    $cnt = $stats['ratings'][$i] ?? 0;
                    $pct = $stats['total'] > 0 ? ($cnt/$stats['total'])*100 : 0;
                ?>
                <div class="rv-bar-row">
                    <div class="rv-bar-lbl"><?=$i?>★</div>
                    <div class="rv-bar-track"><div class="rv-bar-fill" style="width:<?=$pct?>%"></div></div>
                    <div class="rv-bar-num"><?=$cnt?></div>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- FORM / LOGIN GATE -->
        <?php if ($loggedIn): ?>
        <div class="rv-form">
            <h4 style="margin:0 0 16px;font-size:15px;font-weight:700">Chia sẻ nhận xét của bạn</h4>
            <div class="rv-user-tag"><i class="fas fa-user-circle"></i> Đang bình luận với tên <strong style="margin-left:4px"><?= htmlspecialchars($currentUserName) ?></strong></div>
            <input type="hidden" id="reviewName"  value="<?= htmlspecialchars($currentUserName) ?>">
            <input type="hidden" id="reviewEmail" value="<?= htmlspecialchars($currentUserEmail) ?>">

            <div class="rv-fg">
                <label>Đánh giá sao *</label>
                <div class="star-rating" id="starRating">
                    <?php for($i=1;$i<=5;$i++): ?><span class="star" data-value="<?=$i?>">★</span><?php endfor; ?>
                </div>
            </div>
            <div class="rv-fg">
                <label>Tiêu đề *</label>
                <input type="text" class="rv-input" id="reviewTitle" placeholder="Tóm tắt nhận xét của bạn" required>
            </div>
            <div class="rv-fg">
                <label>Nội dung *</label>
                <textarea class="rv-input" id="reviewContent" rows="4" placeholder="Chia sẻ trải nghiệm chi tiết của bạn..." required></textarea>
            </div>
            <button class="btn-rv-submit" onclick="submitReview(event,<?= $id_sp ?>)"><i class="fas fa-paper-plane" style="margin-right:6px"></i>Gửi đánh giá</button>
        </div>
        <?php else: ?>
        <div class="rv-login-gate">
            <i class="fas fa-lock"></i>
            <p>Bạn cần đăng nhập để gửi đánh giá sản phẩm</p>
            <a href="index.php?action=taikhoan" class="btn-login-rv"><i class="fas fa-sign-in-alt"></i> Đăng nhập ngay</a>
        </div>
        <?php endif; ?>

        <!-- REVIEW LIST -->
        <div id="reviewsList">
        <?php if (empty($comments)): ?>
            <div class="rv-empty"><i class="far fa-comment-dots" style="font-size:40px;display:block;margin-bottom:12px"></i>Chưa có bình luận nào. Hãy là người đầu tiên!</div>
        <?php else: foreach ($comments as $c): ?>
            <div class="rv-item">
                <div class="rv-item-header">
                    <div>
                        <div class="rv-author"><?= htmlspecialchars($c['ten_nguoidung']) ?></div>
                        <div class="rv-date"><?= date('d/m/Y', strtotime($c['created_at'])) ?></div>
                    </div>
                    <div class="rv-item-stars">
                        <?php for($i=0;$i<$c['sao_danh_gia'];$i++) echo '★'; for($i=$c['sao_danh_gia'];$i<5;$i++) echo '☆'; ?>
                    </div>
                </div>
                <div class="rv-item-content">
                    <div class="rv-item-title"><?= htmlspecialchars($c['tieu_de']) ?></div>
                    <?= nl2br(htmlspecialchars($c['noi_dung'])) ?>
                </div>
            </div>
        <?php endforeach; endif; ?>
        </div>
    </div>

</div>
</div>

<script>
// Size selection
document.querySelectorAll('#sizeOptions .size-opt').forEach(el => {
    el.addEventListener('click', function() {
        document.querySelectorAll('#sizeOptions .size-opt').forEach(o => o.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('selectedSize').value = this.innerText;
    });
});

// Star rating
const stars = document.querySelectorAll('.star-rating .star');
let selectedRating = 0;
stars.forEach(s => {
    s.addEventListener('click', function() { selectedRating = this.dataset.value; updateStars(selectedRating); });
    s.addEventListener('mouseover', function() { updateStars(this.dataset.value); });
});
document.getElementById('starRating')?.addEventListener('mouseleave', () => updateStars(selectedRating));
function updateStars(r) { stars.forEach(s => s.classList.toggle('active', s.dataset.value <= r)); }

// Submit review
function submitReview(e, id_sp) {
    e.preventDefault();
    if (!selectedRating) { alert('Vui lòng chọn đánh giá sao'); return; }
    const title   = document.getElementById('reviewTitle').value;
    const content = document.getElementById('reviewContent').value;
    const name    = document.getElementById('reviewName').value;
    const email   = document.getElementById('reviewEmail').value;
    const fd = new FormData();
    fd.append('action','add_comment'); fd.append('id_sp',id_sp);
    fd.append('sao_danh_gia',selectedRating); fd.append('tieu_de',title);
    fd.append('noi_dung',content); fd.append('ten_nguoidung',name); fd.append('email_nguoidung',email);
    fetch('index.php?action=chitietsanpham',{method:'POST',body:fd})
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const el = document.createElement('div');
            el.className = 'rv-item';
            el.innerHTML = `<div class="rv-item-header"><div><div class="rv-author">${esc(name)}</div><div class="rv-date">Vừa xong</div></div><div class="rv-item-stars">${'★'.repeat(selectedRating)}${'☆'.repeat(5-selectedRating)}</div></div><div class="rv-item-content"><div class="rv-item-title">${esc(title)}</div>${esc(content).replace(/\n/g,'<br>')}</div>`;
            const list = document.getElementById('reviewsList');
            const empty = list.querySelector('.rv-empty');
            if (empty) empty.remove();
            list.insertBefore(el, list.firstChild);
            document.getElementById('reviewTitle').value = '';
            document.getElementById('reviewContent').value = '';
            selectedRating = 0; updateStars(0);
            alert('Cảm ơn! Đánh giá của bạn đã được ghi nhận.');
        } else { alert('Lỗi: ' + data.message); }
    }).catch(() => alert('Có lỗi khi gửi bình luận'));
}
function esc(t) { return t.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])); }
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>