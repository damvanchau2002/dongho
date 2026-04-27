<!DOCTYPE html>
<html>
<head>
	<title>Sửa thương hiệu - ChronoLux Admin</title>
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
            <div class="admin-content" style="max-width: 600px;">
                <h2 class="section-title">Sửa thương hiệu</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::csrfToken(); ?>">
                    <div class="mb-3">
                        <label class="form-label">Tên thương hiệu</label>
                        <input type="text" class="form-control" name="tenloai" value="<?php echo htmlspecialchars($category['ten_loaisp']); ?>" required>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" name="sualoai" class="btn btn-primary">Cập nhật</button>
                        <a href="index.php?action=quanlyloai" class="btn btn-secondary">Hủy bỏ</a>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
</body>
</html>
