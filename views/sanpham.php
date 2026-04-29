<?php
$pageTitle = "Sản phẩm đồng hồ - ChronoLux";
ob_start();

function buildFilterUrl($newParams = []) {
    $params = $_GET;
    foreach ($newParams as $key => $value) {
        if ($value === '' || $value === null) unset($params[$key]);
        else $params[$key] = $value;
    }
    $qs = http_build_query($params);
    return $qs ? 'index.php?action=sanpham&' . $qs : 'index.php?action=sanpham';
}

function filterProducts($products, $priceFrom=null, $priceTo=null) {
    if (!is_array($products)) return [];
    return array_filter($products, function($p) use ($priceFrom, $priceTo) {
        $price = (int)$p['gia_sp'];
        if ($priceFrom && $price < (int)$priceFrom) return false;
        if ($priceTo   && $price > (int)$priceTo)   return false;
        return true;
    });
}

function sortProducts($products, $sortType='relevant') {
    if (!is_array($products)) return [];
    $sorted = $products;
    if ($sortType === 'low-high')  usort($sorted, fn($a,$b) => (int)$a['gia_sp'] - (int)$b['gia_sp']);
    if ($sortType === 'high-low')  usort($sorted, fn($a,$b) => (int)$b['gia_sp'] - (int)$a['gia_sp']);
    return $sorted;
}

$priceFrom = $_GET['price_from'] ?? null;
$priceTo   = $_GET['price_to']   ?? null;
$sortType  = $_GET['sort']       ?? 'relevant';
$activeCategory = $_GET['idloai'] ?? null;

$rawProducts = $activeCategory ? ($data2 ?: []) : ($data1 ?: []);
if ($rawProducts === 0) $rawProducts = [];
$filtered = sortProducts(filterProducts($rawProducts, $priceFrom, $priceTo), $sortType);
$totalCount = count($filtered);

// PAGINATION LOGIC
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$totalPages = ceil($totalCount / $limit);
if ($page > $totalPages && $totalPages > 0) $page = $totalPages;

$offset = ($page - 1) * $limit;
$paginatedProducts = array_slice($filtered, $offset, $limit);
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
.sp-page { font-family:'Inter',sans-serif; background:#f0f4f8; min-height:100vh; padding:28px 0 60px; }
.sp-wrap { max-width:1260px; margin:0 auto; padding:0 20px; }

/* TOPBAR */
.sp-topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
.sp-topbar h1 { font-size:22px; font-weight:800; color:#1a2535; margin:0; display:flex; align-items:center; gap:10px; }
.sp-topbar h1 i { color:#d4af37; }
.sp-count { font-size:13px; color:#64748b; background:#fff; padding:6px 14px; border-radius:20px; border:1px solid #e2e8f0; font-weight:600; }

/* SORT TABS */
.sp-sort { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:20px; background:#fff; border-radius:14px; padding:8px; box-shadow:0 2px 12px rgba(0,0,0,.05); }
.sp-sort-btn { padding:8px 18px; border:none; background:transparent; border-radius:10px; font-size:13px; font-weight:600; color:#64748b; cursor:pointer; transition:.2s; font-family:inherit; display:flex; align-items:center; gap:6px; white-space:nowrap; }
.sp-sort-btn:hover { background:#f0f4f8; color:#1a2535; }
.sp-sort-btn.active { background:linear-gradient(135deg,#0f2942,#1a4a7a); color:#fff; }

/* LAYOUT */
.sp-layout { display:grid; grid-template-columns:240px 1fr; gap:20px; align-items:start; }

/* SIDEBAR */
.sp-sidebar { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.05); overflow:hidden; position:sticky; top:16px; }
.sp-sidebar-head { padding:16px 20px; background:linear-gradient(135deg,#0f2942,#1a4a7a); color:#fff; font-size:14px; font-weight:700; display:flex; align-items:center; gap:8px; }
.sp-filter-section { padding:16px 20px; border-bottom:1px solid #f0f4f8; }
.sp-filter-section:last-child { border-bottom:none; }
.sp-filter-title { font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.8px; margin-bottom:12px; }
.sp-cat-item { display:block; padding:9px 12px; border-radius:10px; text-decoration:none; font-size:13px; font-weight:500; color:#475569; transition:.15s; margin-bottom:2px; }
.sp-cat-item:hover { background:#f0f4f8; color:#1a2535; }
.sp-cat-item.active { background:linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e; font-weight:700; }
.sp-cat-item i { width:16px; margin-right:4px; color:#d4af37; }
.price-inputs { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:10px; }
.price-input { width:100%; padding:9px 10px; border:1.5px solid #e2e8f0; border-radius:8px; font-size:13px; box-sizing:border-box; transition:.2s; font-family:inherit; color:#1a2535; }
.price-input:focus { outline:none; border-color:#1a4a7a; }
.btn-price { width:100%; padding:9px; background:linear-gradient(135deg,#d4af37,#b8962e); color:#fff; border:none; border-radius:8px; font-weight:700; font-size:13px; cursor:pointer; font-family:inherit; }
.btn-price:hover { opacity:.9; }
.btn-reset { display:inline-flex; align-items:center; gap:4px; font-size:12px; color:#94a3b8; text-decoration:none; margin-top:8px; transition:.15s; }
.btn-reset:hover { color:#e53e3e; }

/* PRODUCTS GRID */
.sp-content {}
.products-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
.product-card { background:#fff; border-radius:16px; overflow:hidden; transition:.2s; box-shadow:0 2px 8px rgba(0,0,0,.06); border:1px solid #f0f4f8; position:relative; }
.product-card:hover { box-shadow:0 12px 36px rgba(0,0,0,.12); transform:translateY(-4px); border-color:#e2e8f0; }
.product-card-img-wrap { position:relative; overflow:hidden; height:200px; background:#f8fafc; }
.product-card-img { width:100%; height:200px; object-fit:cover; display:block; transition:.4s; }
.product-card:hover .product-card-img { transform:scale(1.06); }
.product-badge { position:absolute; top:10px; left:10px; background:linear-gradient(135deg,#d4af37,#b8962e); color:#fff; font-size:10px; font-weight:700; padding:3px 8px; border-radius:6px; letter-spacing:.3px; }
.product-card-body { padding:14px; }
.product-name { font-size:13px; color:#1a2535; font-weight:600; margin-bottom:6px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; min-height:34px; line-height:1.4; }
.product-rating { font-size:12px; color:#94a3b8; margin-bottom:8px; display:flex; align-items:center; gap:4px; }
.stars { color:#fbbf24; font-size:12px; }
.product-price { font-size:18px; font-weight:800; color:#e53e3e; }
.product-card-footer { padding:0 14px 14px; }
.btn-detail { display:block; text-align:center; padding:9px; background:#f0f4f8; color:#1a4a7a; border-radius:10px; font-size:12px; font-weight:700; text-decoration:none; transition:.2s; }
.btn-detail:hover { background:linear-gradient(135deg,#0f2942,#1a4a7a); color:#fff; }
.empty-state { grid-column:1/-1; text-align:center; padding:60px 20px; }
.empty-state i { font-size:52px; color:#e2e8f0; margin-bottom:16px; }
.empty-state p { color:#94a3b8; font-size:15px; }

/* PAGINATION */
.sp-pagination { display: flex !important; justify-content: center !important; gap: 6px !important; margin-top: 30px !important; padding: 0 !important; width: 100% !important; }
.page-btn { width: 34px !important; height: 34px !important; margin: 0 !important; padding: 0 !important; border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important; background: #fff !important; color: #475569 !important; font-weight: 600 !important; font-size: 13px !important; display: flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important; transition: 0.2s !important; font-family: inherit !important; flex-shrink: 0 !important; box-sizing: border-box !important; text-decoration: none !important; }
.page-btn:hover { border-color: #1a4a7a !important; color: #1a4a7a !important; transform: translateY(-2px); }
.page-btn.active { background: linear-gradient(135deg, #0f2942, #1a4a7a) !important; color: #fff !important; border-color: transparent !important; pointer-events: none; }

@media(max-width:1100px){ .products-grid{grid-template-columns:repeat(3,1fr)} }
@media(max-width:900px){ .sp-layout{grid-template-columns:1fr} .sp-sidebar{position:static} }
@media(max-width:600px){ .products-grid{grid-template-columns:repeat(2,1fr)} }
</style>

<div class="sp-page">
<div class="sp-wrap">

    <!-- TOPBAR -->
    <div class="sp-topbar">
        <h1><i class="fas fa-clock"></i>
            <?php if ($activeCategory && is_array($data) && $data):
                foreach ($data as $cat) { if ($cat['id_loaisp'] == $activeCategory) { echo htmlspecialchars($cat['ten_loaisp']); break; } }
            else: ?>Tất cả đồng hồ<?php endif; ?>
        </h1>
        <span class="sp-count"><?= $totalCount ?> sản phẩm</span>
    </div>

    <!-- SORT -->
    <div class="sp-sort">
        <?php
        $sorts = ['relevant'=>['Liên quan','fa-th-large'],'newest'=>['Mới nhất','fa-star'],'low-high'=>['Giá tăng dần','fa-arrow-up'],'high-low'=>['Giá giảm dần','fa-arrow-down']];
        foreach ($sorts as $key => [$label, $icon]): ?>
        <button class="sp-sort-btn <?= $sortType === $key ? 'active' : '' ?>" onclick="applySorting('<?= $key ?>')">
            <i class="fas <?= $icon ?>"></i> <?= $label ?>
        </button>
        <?php endforeach; ?>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="sp-layout">

        <!-- SIDEBAR -->
        <div class="sp-sidebar">
            <div class="sp-sidebar-head"><i class="fas fa-sliders-h"></i> Bộ lọc</div>

            <!-- CATEGORY -->
            <div class="sp-filter-section">
                <div class="sp-filter-title">Danh mục</div>
                <a href="index.php?action=sanpham" class="sp-cat-item <?= !$activeCategory ? 'active' : '' ?>">
                    <i class="fas fa-th"></i> Tất cả
                </a>
                <?php if (is_array($data)) foreach ($data as $cat): ?>
                <a href="index.php?action=sanpham&idloai=<?= $cat['id_loaisp'] ?>"
                   class="sp-cat-item <?= $activeCategory == $cat['id_loaisp'] ? 'active' : '' ?>">
                    <i class="fas fa-tag"></i> <?= htmlspecialchars($cat['ten_loaisp']) ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- PRICE -->
            <div class="sp-filter-section">
                <div class="sp-filter-title">Khoảng giá (đ)</div>
                <div class="price-inputs">
                    <input type="number" id="priceFrom" class="price-input" placeholder="Từ" value="<?= htmlspecialchars($priceFrom ?? '') ?>">
                    <input type="number" id="priceTo"   class="price-input" placeholder="Đến" value="<?= htmlspecialchars($priceTo ?? '') ?>">
                </div>
                <button class="btn-price" onclick="applyPriceFilter()"><i class="fas fa-check" style="margin-right:5px"></i>Áp dụng</button>
                <?php if ($priceFrom || $priceTo): ?>
                <div><a href="<?= buildFilterUrl(['price_from'=>'','price_to'=>'']) ?>" class="btn-reset"><i class="fas fa-times"></i> Xóa bộ lọc giá</a></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- PRODUCTS -->
        <div class="sp-content">
            <div class="products-grid">
            <?php if (empty($filtered)): ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>Không tìm thấy sản phẩm phù hợp</p>
                </div>
            <?php else: 
                $store = new StoreModel();
                foreach ($paginatedProducts as $p): 
                    $isFav = false;
                    if (isset($_SESSION['tennd'])) {
                        $isFav = $store->kiemTraYeuThich($_SESSION['id_nd'], $p['id_sp']);
                    }
            ?>
                <div class="product-card position-relative">
                    <div class="product-card-img-wrap">
                        <a href="index.php?action=chitietsanpham&id=<?= $p['id_sp'] ?>">
                            <img src="<?= $p['hinhanh_sp'] ?>" alt="<?= htmlspecialchars($p['ten_sp']) ?>" class="product-card-img" loading="lazy">
                        </a>
                        <span class="product-badge">Chính hãng</span>
                    </div>
                    <div class="product-card-body">
                        <a href="index.php?action=chitietsanpham&id=<?= $p['id_sp'] ?>" style="text-decoration:none">
                            <h4 class="product-name"><?= htmlspecialchars($p['ten_sp']) ?></h4>
                        </a>
                        <div class="product-rating">
                            <?php
                            if (!isset($commentModel)) {
                                require_once BASE_PATH . '/models/CommentModel.php';
                                $commentModel = new CommentModel();
                            }
                            $stats = $commentModel->getRatingStats($p['id_sp']);
                            $avg = $stats['average'];
                            $total = $stats['total'];
                            $sold = $store->getSoldQuantity($p['id_sp']);
                            ?>
                            <span class="stars">
                                <?php
                                $full = floor($avg); $half = ($avg-$full)>=0.5?1:0;
                                for($i=0;$i<$full;$i++) echo '★';
                                if($half) echo '½';
                                for($i=$full+$half;$i<5;$i++) echo '☆';
                                ?>
                            </span>
                            <span style="font-size: 11px;">(<?= $total ?>) | Đã bán <?= number_format($sold, 0, ',', '.') ?></span>
                        </div>
                        <div class="product-price" style="display: flex; justify-content: space-between; align-items: center;">
                            <span><?= number_format($p['gia_sp'],0,',','.') ?>đ</span>
                            <i id="fav-btn-<?=$p['id_sp']?>" class="<?= $isFav ? 'fas text-danger' : 'far text-secondary' ?> fa-heart" onclick="toggleFavorite(<?=$p['id_sp']?>); event.preventDefault();" style="cursor: pointer; font-size: 1.2rem;" title="Yêu thích"></i>
                        </div>
                    </div>
                    <div class="product-card-footer">
                        <a href="index.php?action=chitietsanpham&id=<?= $p['id_sp'] ?>" class="btn-detail">
                            <i class="fas fa-eye" style="margin-right:5px"></i>Xem chi tiết
                        </a>
                    </div>
                </div>
            <?php endforeach; endif; ?>
            </div>

            <!-- PAGINATION CONTROLS -->
            <?php if ($totalPages > 1): ?>
            <div class="sp-pagination">
                <?php if ($page > 1): ?>
                    <button class="page-btn" onclick="applyPagination(<?= $page - 1 ?>)"><i class="fas fa-chevron-left"></i></button>
                <?php endif; ?>
                
                <?php 
                // Show up to 5 pages
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                if ($endPage - $startPage < 4) {
                    if ($startPage == 1) $endPage = min($totalPages, 5);
                    else if ($endPage == $totalPages) $startPage = max(1, $totalPages - 4);
                }
                ?>
                
                <?php if ($startPage > 1): ?>
                    <button class="page-btn" onclick="applyPagination(1)">1</button>
                    <?php if ($startPage > 2): ?><span style="align-self:end; color:#94a3b8;">...</span><?php endif; ?>
                <?php endif; ?>

                <?php for($i=$startPage; $i<=$endPage; $i++): ?>
                    <button class="page-btn <?= $i === $page ? 'active' : '' ?>" onclick="applyPagination(<?= $i ?>)"><?= $i ?></button>
                <?php endfor; ?>

                <?php if ($endPage < $totalPages): ?>
                    <?php if ($endPage < $totalPages - 1): ?><span style="align-self:end; color:#94a3b8;">...</span><?php endif; ?>
                    <button class="page-btn" onclick="applyPagination(<?= $totalPages ?>)"><?= $totalPages ?></button>
                <?php endif; ?>
                
                <?php if ($page < $totalPages): ?>
                    <button class="page-btn" onclick="applyPagination(<?= $page + 1 ?>)"><i class="fas fa-chevron-right"></i></button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</div>

<script>
function buildFilterUrl(params) {
    const url = new URL(window.location.href);
    url.searchParams.set('action','sanpham');
    Object.keys(params).forEach(k => {
        if (params[k] === '' || params[k] === null) url.searchParams.delete(k);
        else url.searchParams.set(k, params[k]);
    });
    return url.pathname + url.search;
}
function applyPriceFilter() {
    const from = document.getElementById('priceFrom').value;
    const to   = document.getElementById('priceTo').value;
    if (from && to && parseFloat(from) > parseFloat(to)) { alert('Giá "Từ" không được lớn hơn "Đến"'); return; }
    const p = {};
    if (from) p.price_from = from;
    if (to)   p.price_to   = to;
    p.page = 1; // reset page on filter
    window.location.href = buildFilterUrl(p);
}
function applySorting(type) {
    window.location.href = buildFilterUrl({sort: type, page: 1});
}
function applyPagination(page) {
    window.location.href = buildFilterUrl({page: page});
}
document.querySelectorAll('#priceFrom,#priceTo').forEach(el => {
    el.addEventListener('keypress', e => { if (e.key === 'Enter') applyPriceFilter(); });
});
</script>

<?php
$content = ob_get_clean();
require BASE_PATH . '/views/layouts/user-layout.php';
?>