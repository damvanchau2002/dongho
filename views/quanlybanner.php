<?php
    $pageTitle = "Quản lý Banner";
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
    <style>
        .banner-preview { width: 250px; height: auto; max-height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd; }
        .action-links { display: flex; gap: 8px; justify-content: center; }
        .action-btn { padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: all 0.2s; border: none; cursor: pointer; }
        .action-edit { background: #e0f2fe; color: #0284c7; }
        .action-delete { background: #fee2e2; color: #ef4444; }
        .action-edit:hover { background: #0284c7; color: white; text-decoration: none; }
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
                <div class="admin-header d-flex justify-content-between align-items-center mb-4">
                    <h2>Danh sách Banner</h2>
                    <a href="index.php?action=thembanner" class="btn btn-primary shadow-sm"><i class="fas fa-plus"></i> Thêm banner mới</a>
                </div>

                <div class="card shadow-sm border-0 border-radius-15">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 text-center align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="30%">Hình ảnh</th>
                                    <th width="20%">Vị trí</th>
                                    <th width="20%">Trạng thái</th>
                                    <th width="25%" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($danhsachbanner) && is_array($danhsachbanner) && count($danhsachbanner) > 0): ?>
                                    <?php foreach ($danhsachbanner as $banner): ?>
                                        <tr>
                                            <td>#<?= $banner['id'] ?></td>
                                            <td>
                                                <img src="<?= htmlspecialchars($banner['hinh_anh']) ?>" class="banner-preview" alt="Banner">
                                            </td>
                                            <td>
                                                <span class="badge badge-info"><?= htmlspecialchars($banner['vi_tri']) ?></span>
                                            </td>
                                            <td>
                                                <?php if($banner['trang_thai'] == 1): ?>
                                                    <span class="badge badge-success">Hiển thị</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">Tạm ẩn</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-links">
                                                    <a href="index.php?action=suabanner&id=<?= $banner['id'] ?>" class="action-btn action-edit"><i class="fas fa-edit"></i> Sửa</a>
                                                    <form method="POST" action="index.php?action=xoabanner" style="display:inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                                        <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken(); ?>">
                                                        <input type="hidden" name="id" value="<?= $banner['id'] ?>">
                                                        <button type="submit" class="action-btn action-delete"><i class="fas fa-trash"></i> Xóa</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="py-4 text-muted">Chưa có banner nào trong hệ thống.</td></tr>
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