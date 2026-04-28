<!DOCTYPE html>
<html>
<head>
	<title>Sửa sản phẩm - ChronoLux Admin</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css" />
	<style>
		.preview-image {
			width: 150px;
			height: 150px;
			object-fit: cover;
			border-radius: 8px;
			border: 2px solid #ddd;
		}
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
			    <h2 class="section-title">Sửa sản phẩm</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::csrfToken(); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" name="tsp" value="<?php echo htmlspecialchars($data2[0]['ten_sp']); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá sản phẩm</label>
                            <input type="number" class="form-control" name="gsp" value="<?php echo htmlspecialchars($data2[0]['gia_sp']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Loại sản phẩm</label>
                            <select class="form-select form-control" name="idlsp">
                                <?php
                                    foreach ($data as $value) {
                                        $selected = ($value['id_loaisp'] == $data2[0]['id_loaisp']) ? 'selected' : '';
                                        echo "<option value='{$value['id_loaisp']}' {$selected}>{$value['ten_loaisp']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="so_luong_ton" required
                            min="0" step="1" placeholder="VD: 100"
                            value="<?php echo (isset($data2[0]['so_luong_ton'])) ? (int)$data2[0]['so_luong_ton'] : 0; ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-danger font-weight-bold"><i class="fas fa-bolt"></i> Giá Flash Sale (để trống nếu không giảm)</label>
                            <input type="number" class="form-control" name="flash_sale_price" value="<?php echo htmlspecialchars($data2[0]['flash_sale_price'] ?? ''); ?>" placeholder="Ví dụ: 150000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-danger font-weight-bold"><i class="far fa-clock"></i> Thời gian kết thúc Flash Sale</label>
                            <input type="datetime-local" class="form-control" name="flash_sale_end" value="<?php echo htmlspecialchars($data2[0]['flash_sale_end'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control" name="mtsp" rows="4"><?php echo htmlspecialchars($data2[0]['mota_sp']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-block">Hình ảnh hiện tại</label>
                        <img src="<?php echo $data2[0]['hinhanh_sp'];?>" class="preview-image mb-2">
                        <label class="form-label d-block">Thay đổi hình ảnh</label>
                        <input type="file" class="form-control" name="lha">
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" name="sua">Cập nhật sản phẩm</button>
                        <a href="index.php?action=quantri" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
