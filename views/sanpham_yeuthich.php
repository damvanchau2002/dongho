<?php
$pageTitle = "Sản phẩm yêu thích - ChronoLux";
ob_start();
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.fav-page { font-family: 'Inter', sans-serif; background: #f0f4f8; min-height: 100vh; padding: 40px 0; }
.fav-wrap { max-width: 1260px; margin: 0 auto; padding: 0 20px; }
.fav-header { display: flex; align-items: center; gap: 10px; margin-bottom: 30px; font-size: 24px; font-weight: 800; color: #1a2535; }
.fav-header i { color: #dc3545; }

.empty-state { text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
.empty-state i { font-size: 60px; color: #cbd5e1; margin-bottom: 20px; }
.empty-state h5 { font-size: 18px; font-weight: 700; color: #1a2535; margin-bottom: 10px; }
.empty-state p { color: #64748b; margin-bottom: 20px; }
.btn-continue { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #0f2942, #1a4a7a); color: #fff; font-weight: 700; border-radius: 10px; text-decoration: none; transition: 0.3s; }
.btn-continue:hover { opacity: 0.9; transform: translateY(-2px); color: #fff; }

.fav-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
.fav-card { background: #fff; border-radius: 16px; overflow: hidden; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; display: flex; flex-direction: column; border: 1px solid #e2e8f0; }
.fav-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-color: #cbd5e1; }
.btn-remove { position: absolute; top: 12px; right: 12px; z-index: 10; width: 35px; height: 35px; background: #fff; border: 1px solid #e2e8f0; border-radius: 50%; color: #dc3545; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
.btn-remove:hover { background: #fee2e2; border-color: #fca5a5; }

.fav-img-wrap { height: 220px; background: #f8fafc; overflow: hidden; display: block; }
.fav-img-wrap img { width: 100%; height: 100%; object-fit: contain; padding: 20px; transition: 0.4s; }
.fav-card:hover .fav-img-wrap img { transform: scale(1.05); }

.fav-info { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; text-align: center; }
.fav-title { font-size: 14px; font-weight: 600; color: #1a2535; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-decoration: none; }
.fav-title:hover { color: #d4af37; text-decoration: none; }
.fav-price { font-size: 16px; font-weight: 800; color: #e53e3e; margin-bottom: 15px; margin-top: auto; }

.btn-add-cart { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px; background: #f0f4f8; color: #1a4a7a; border: none; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; transition: 0.2s; text-decoration: none; font-family: inherit; }
.btn-add-cart:hover { background: #1a4a7a; color: #fff; text-decoration: none; }

@media (max-width: 1024px) { .fav-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px) { .fav-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .fav-grid { grid-template-columns: 1fr; } }
</style>

<div class="fav-page">
    <div class="fav-wrap">
        <h1 class="fav-header"><i class="fas fa-heart"></i> Sản phẩm yêu thích của bạn</h1>

        <?php if (empty($favorites)): ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h5>Danh sách trống</h5>
                <p>Bạn chưa thêm sản phẩm nào vào danh sách yêu thích.</p>
                <a href="index.php?action=sanpham" class="btn-continue">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="fav-grid">
                <?php foreach ($favorites as $sp): ?>
                    <div class="fav-card" id="fav-item-<?= $sp['id_sp'] ?>">
                        <button onclick="removeFavorite(<?= $sp['id_sp'] ?>)" class="btn-remove" title="Bỏ yêu thích">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        
                        <a href="index.php?action=chitietsanpham&id=<?= $sp['id_sp'] ?>" class="fav-img-wrap">
                            <img src="<?= $sp['hinhanh_sp'] ?>" alt="<?= htmlspecialchars($sp['ten_sp']) ?>">
                        </a>
                        
                        <div class="fav-info">
                            <a href="index.php?action=chitietsanpham&id=<?= $sp['id_sp'] ?>" class="fav-title">
                                <?= htmlspecialchars($sp['ten_sp']) ?>
                            </a>
                            <div class="fav-price"><?= number_format($sp['gia_sp'], 0, ',', '.') ?>đ</div>
                            <a href="index.php?action=giohang&id=<?= $sp['id_sp'] ?>" class="btn-add-cart">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function removeFavorite(id_sp) {
    if(!confirm('Bạn có chắc muốn bỏ sản phẩm này khỏi danh sách yêu thích?')) return;
    
    fetch('index.php?action=api/favorite/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_sp: id_sp })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const item = document.getElementById('fav-item-' + id_sp);
            if (item) {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    item.remove();
                    // Check if grid is empty
                    const grid = document.querySelector('.fav-grid');
                    if (grid && grid.children.length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }, 300);
            }
        } else {
            alert(data.message || 'Lỗi xử lý yêu thích');
            if (data.require_login) {
                window.location.href = 'index.php?action=taikhoan';
            }
        }
    })
    .catch(err => {
        console.error('Lỗi khi bỏ yêu thích:', err);
    });
}
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>
