<?php
    $pageTitle = "Quản lý sản phẩm - ChronoLux";

	if (isset($_POST['nutdx'])) {
		session_unset();
        header("Location: index.php");
        exit;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Quản lý sản phẩm - ChronoLux</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css" />
    <style>
        .table img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .table thead th {
            border-bottom: 2px solid #edf2f9;
            color: #8b9eb7;
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding-bottom: 15px;
        }
        .table tbody td {
            vertical-align: middle;
            color: #334;
            font-weight: 500;
            border-bottom: 1px solid #edf2f9;
        }
        .action-links {
            display: flex;
            gap: 8px;
        }
        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .action-edit {
            background: #e0f2fe;
            color: #0284c7;
        }
        .action-delete {
            background: #fee2e2;
            color: #ef4444;
        }
        .action-edit:hover { background: #0284c7; color: white; }
        .action-delete:hover { background: #ef4444; color: white; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg admin-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="./">CHRONOLUX</a>
            <div class="collapse navbar-collapse justify-content-between">
                <ul class="navbar-nav mb-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ cửa hàng</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?action=quantri">Bảng điều khiển</a></li>
                </ul>
                <div class="user-info">
                    <?php if (isset($_SESSION['tennd'])): ?>
                        <span class="user-name">Xin chào, <?php echo $_SESSION['tennd']; ?></span>
                        <form method="post" class="m-0">
                            <input type="submit" name="nutdx" value="Đăng xuất" class="btn-logout">
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

	<div class="container-fluid px-4 mb-5">
	  <div class="row">
	    <div class="col-lg-2 col-md-3 mb-4">
            <div class="admin-sidebar">
                <?php include APP_PATH . '/views/components/admin-sidebar.php'; ?>
            </div>
	    </div>
	    <div class="col-lg-10 col-md-9">
            <div class="admin-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title mb-0">Quản lý sản phẩm</h2>
                    <a href="index.php?action=themsanpham" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i> Thêm sản phẩm
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" width="5%">STT</th>
                                <th scope="col" width="15%">BỘ SƯU TẬP</th>
                                <th scope="col" width="10%">HÌNH ẢNH</th>
                                <th scope="col" width="10%">GIÁ ($)</th>
                                <th scope="col" width="10%">THƯƠNG HIỆU</th>
                                <th scope="col" width="10%">NGÀY NHẬP</th>
                                <th scope="col" width="10%">TỒN KHO</th>
                                <th scope="col" width="15%">MÔ TẢ</th>
                                <th scope="col" width="15%" class="text-center">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $displayData = isset($_GET['idloai']) ? $data2 : $data1;
                                if($displayData === 0 || empty($displayData)) {
                                    $displayData = [];
                                }
                                
                                $totalCount = count($displayData);
                                $limit = 10;
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                if ($page < 1) $page = 1;
                                $totalPages = ceil($totalCount / $limit);
                                if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
                                
                                $offset = ($page - 1) * $limit;
                                $paginatedData = array_slice($displayData, $offset, $limit);

                                $stt = $offset + 1;

                                if(empty($paginatedData)) {
                                    echo "<tr><td colspan='8' class='text-center py-4 text-muted'>Chưa có sản phẩm nào trong danh mục này.</td></tr>";
                                } else {
                                    foreach ($paginatedData as $value) {
                            ?>
                                <tr>
                                    <td><strong><?php echo $stt++; ?></strong></td>
                                    <td><strong><?php echo $value['ten_sp']; ?></strong></td>
                                    <td><img src="<?php echo $value['hinhanh_sp']; ?>"></td>
                                    <td><span class="text-primary font-weight-bold"><?php echo number_format($value['gia_sp'], 0, ',', '.'); ?>đ</span></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $value['ten_loaisp']; ?></span></td>
                                    <td><?php echo date('d/m/Y', strtotime($value['ngaynhap_sp'])); ?></td>
                                    <td>
                                        <?php if ($value['so_luong_ton'] > 0): ?>
                                            <span class="badge bg-success text-white"><?php echo $value['so_luong_ton']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger text-white">Hết hàng</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?php echo $value['mota_sp']; ?></td>
                                    <td class="text-center">
                                        <div class="action-links justify-content-center">
                                            <a href="index.php?action=sua&id_sua=<?php echo $value['id_sp']; ?>" class="action-btn action-edit">Sửa</a>
                                            <form method="POST" action="index.php?action=xoasanpham" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                                <input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::csrfToken(); ?>">
                                                <input type="hidden" name="id_xoa" value="<?php echo $value['id_sp']; ?>">
                                                <button type="submit" class="action-btn action-delete" style="border:none;cursor:pointer">Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php }} ?>
                        </tbody>
                    </table>

                    <!-- PAGINATION CONTROLS -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?action=quanlysanpham<?= isset($_GET['idloai']) ? '&idloai='.$_GET['idloai'] : '' ?>&page=<?= $page - 1 ?>">Trước</a></li>
                                <?php endif; ?>
                                
                                <?php for($i=1; $i<=$totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?action=quanlysanpham<?= isset($_GET['idloai']) ? '&idloai='.$_GET['idloai'] : '' ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item"><a class="page-link" href="?action=quanlysanpham<?= isset($_GET['idloai']) ? '&idloai='.$_GET['idloai'] : '' ?>&page=<?= $page + 1 ?>">Sau</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
	    </div>
	  </div>
	</div>
</body>
</html>
