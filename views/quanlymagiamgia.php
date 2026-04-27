<?php
    $pageTitle = "Quản lý mã giảm giá - ChronoLux";
?>
<!DOCTYPE html>
<html>
<head>
	<title><?= $pageTitle ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css" />
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
                    <h2 class="section-title mb-0">Quản lý mã giảm giá</h2>
                    <a href="index.php?action=themmagiamgia" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i> Tạo mã mới
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">Mã Code</th>
                                <th width="15%">Loại</th>
                                <th width="15%">Giá trị</th>
                                <th width="10%">Số lượng</th>
                                <th width="15%">Ngày hết hạn</th>
                                <th width="15%">Ngày tạo</th>
                                <th width="10%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($discounts)): ?>
                                <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có mã giảm giá nào.</td></tr>
                            <?php else: ?>
                                <?php foreach ($discounts as $d): ?>
                                    <tr>
                                        <td><?= $d['id'] ?></td>
                                        <td><span class="badge badge-info px-3 py-2" style="font-size: 0.9rem;"><?= $d['ma_code'] ?></span></td>
                                        <td><?= $d['loai'] == 'percentage' ? 'Phần trăm (%)' : 'Cố định (đ)' ?></td>
                                        <td class="font-weight-bold text-primary">
                                            <?= number_format($d['gia_tri'], 0, ',', '.') ?> <?= $d['loai'] == 'percentage' ? '%' : 'đ' ?>
                                        </td>
                                        <td><?= $d['so_luong'] ?></td>
                                        <td><?= $d['ngay_het_han'] ? date('d/m/Y', strtotime($d['ngay_het_han'])) : '<span class="text-muted">Không giới hạn</span>' ?></td>
                                        <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($d['ngay_tao'])) ?></td>
                                        <td class="text-center">
                                            <form method="POST" action="index.php?action=xoamagiamgia" style="display:inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã này?')">
                                                <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
                                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" style="cursor:pointer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
	    </div>
	  </div>
	</div>
</body>
</html>