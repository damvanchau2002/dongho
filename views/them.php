<!DOCTYPE html>
<html>
<head>
	<title>Thêm sản phẩm - ChronoLux Admin</title>
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
			display: none;
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
			    <h2 class="section-title">Thêm sản phẩm mới</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" id="productForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::csrfToken(); ?>">

                    <div class="mb-3">
                        <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tsp" id="tsp" required
                            maxlength="255" placeholder="VD: Nike Air Jordan 1 Retro High OG"
                            value="<?= isset($_POST['tsp']) ? htmlspecialchars($_POST['tsp']) : '' ?>">
                        <div class="invalid-feedback">Tên sản phẩm không được để trống</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá sản phẩm (VNĐ) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="gsp" id="gsp" required
                                    min="1000" max="2000000000" step="1000" placeholder="VD: 500000"
                                    value="<?= isset($_POST['gsp']) ? (int)$_POST['gsp'] : '' ?>">
                                <div class="input-group-append">
                                    <span class="input-group-text">đ</span>
                                </div>
                                <div class="invalid-feedback">Giá phải từ 1.000đ đến 2 tỷđ</div>
                            </div>
                            <small class="text-muted">Tối đa 2,000,000,000đ</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Loại sản phẩm <span class="text-danger">*</span></label>
                            <select class="form-control form-select" name="idlsp" id="idlsp" required>
                                <option value="">-- Chọn loại sản phẩm --</option>
                                <?php foreach ($data as $value): ?>
                                    <option value="<?= $value['id_loaisp'] ?>"
                                        <?= (isset($_POST['idlsp']) && $_POST['idlsp'] == $value['id_loaisp']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($value['ten_loaisp']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn loại thương hiệu</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh sản phẩm</label>
                        <input type="file" class="form-control" name="lha" id="lha" accept="image/*" onchange="previewImage(this)">
                        <img id="preview" class="preview-image mt-2" alt="Preview">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control" name="mtsp" id="mtsp" rows="4" maxlength="500" placeholder="Mô tả..."><?= isset($_POST['mtsp']) ? htmlspecialchars($_POST['mtsp']) : '' ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày nhập</label>
                        <input type="date" class="form-control" name="ngaynhap" value="<?= isset($_POST['ngaynhap']) ? $_POST['ngaynhap'] : date('Y-m-d') ?>">
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" name="them">Lưu sản phẩm</button>
                        <a href="index.php?action=quantri" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
