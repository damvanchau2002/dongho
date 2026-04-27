<?php
$pageTitle = "Sửa Banner";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?> - ChronoLux Admin</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css">
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
                <div class="admin-header d-flex justify-content-between align-items-center mb-4">
                    <h2>Chỉnh Sửa Banner</h2>
                    <a href="index.php?action=quanlybanner" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
                </div>

                <div class="card shadow-sm border-0 border-radius-15">
                    <div class="card-body p-4">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($banner)): ?>
                            <form method="POST" action="index.php?action=suabanner&id=<?= $banner['id'] ?>" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken(); ?>">
                                
                                <div class="form-group text-center mb-4">
                                    <label class="d-block">Hình ảnh hiện tại</label>
                                    <img src="<?= htmlspecialchars($banner['hinh_anh']) ?>" class="img-thumbnail" style="max-height: 200px" alt="Current Banner">
                                </div>

                                <div class="form-group">
                                    <label>Vị trí hiển thị <span class="text-danger">*</span></label>
                                    <select name="vi_tri" class="form-control" required>
                                        <option value="main" <?= ($banner['vi_tri'] == 'main') ? 'selected' : '' ?>>Banner Chính (Bên trái)</option>
                                        <option value="side1" <?= ($banner['vi_tri'] == 'side1') ? 'selected' : '' ?>>Banner Phụ 1 (Góc trên phải)</option>
                                        <option value="side2" <?= ($banner['vi_tri'] == 'side2') ? 'selected' : '' ?>>Banner Phụ 2 (Góc dưới phải)</option>
                                    </select>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Tải ảnh mới lên (File)</label>
                                        <input type="file" name="hinh_anh" class="form-control-file" accept="image/*">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Hoặc nhập URL ảnh mới</label>
                                        <input type="text" name="hinh_anh_url" class="form-control" placeholder="https://...">
                                    </div>
                                    <small class="text-muted col-12 mt-2">Ghi chú: Nếu để trống, hệ thống sẽ giữ nguyên ảnh hiện tại.</small>
                                </div>

                                <div class="form-group">
                                    <label>Đường dẫn khi click (Link)</label>
                                    <input type="text" name="link" class="form-control" value="<?= htmlspecialchars($banner['link']) ?>" placeholder="Ví dụ: index.php?action=sanpham">
                                </div>

                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select name="trang_thai" class="form-control">
                                        <option value="1" <?= ($banner['trang_thai'] == 1) ? 'selected' : '' ?>>Hiển thị</option>
                                        <option value="0" <?= ($banner['trang_thai'] == 0) ? 'selected' : '' ?>>Tạm ẩn</option>
                                    </select>
                                </div>

                                <div class="form-group mt-4 text-center">
                                    <button type="submit" class="btn btn-primary px-5"><i class="fas fa-save"></i> Cập nhật</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">Banner không tồn tại.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>