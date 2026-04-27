<!DOCTYPE html>
<html>
<head>
	<title>Quản lý thương hiệu - ChronoLux Admin</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css" />
    <style>
        .action-links { display: flex; gap: 8px; }
        .action-btn { padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
        .action-edit { background: #e0f2fe; color: #0284c7; }
        .action-delete { background: #fee2e2; color: #ef4444; }
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
                    <h2 class="section-title mb-0">Quản lý thương hiệu</h2>
                    <a href="index.php?action=themloai" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Thêm mới</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="10%">ID</th>
                                <th width="70%">Tên thương hiệu</th>
                                <th width="20%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($data) && is_array($data)): ?>
                                <?php foreach ($data as $cat): 
                                    if($cat['ten_loaisp'] == 'THÊM') continue;
                                ?>
                                    <tr>
                                        <td>#<?php echo $cat['id_loaisp']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($cat['ten_loaisp']); ?></strong></td>
                                        <td class="text-center">
                                            <div class="action-links justify-content-center">
                                                <a href="index.php?action=sualoai&id=<?php echo $cat['id_loaisp']; ?>" class="action-btn action-edit">Sửa</a>
                                                <form method="POST" action="index.php?action=xoaloai" style="display:inline" onsubmit="return confirm('Xóa thương hiệu này có thể làm mất các sản phẩm liên quan. Bạn chắc chứ?')">
                                                    <input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::csrfToken(); ?>">
                                                    <input type="hidden" name="id" value="<?php echo $cat['id_loaisp']; ?>">
                                                    <button type="submit" class="action-btn action-delete" style="border:none;cursor:pointer">Xóa</button>
                                                </form>
                                            </div>
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
