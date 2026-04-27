<?php
    $pageTitle = "Thêm mã giảm giá - ChronoLux";
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
                <h2 class="section-title">Tạo mã giảm giá mới</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form action="index.php?action=themmagiamgia" method="POST" class="mt-4">
                    <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Mã Code (ví dụ: SALE10)</label>
                                <input type="text" name="ma_code" class="form-control" placeholder="Nhập mã giảm giá" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Loại giảm giá</label>
                                <select name="loai" class="form-control">
                                    <option value="fixed">Số tiền cố định (đ)</option>
                                    <option value="percentage">Phần trăm (%)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Giá trị giảm</label>
                                <input type="number" step="0.01" name="gia_tri" class="form-control" placeholder="Nhập số tiền hoặc %" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Số lượng sử dụng</label>
                                <input type="number" name="so_luong" class="form-control" value="100" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Ngày hết hạn (để trống nếu không giới hạn)</label>
                                <input type="date" name="ngay_het_han" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="them" class="btn btn-primary px-5">Tạo mã ngay</button>
                        <a href="index.php?action=quanlymagiamgia" class="btn btn-light ml-2">Hủy bỏ</a>
                    </div>
                </form>
            </div>
	    </div>
	  </div>
	</div>
</body>
</html>