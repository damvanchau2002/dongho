<?php
    $pageTitle = "Quản lý người dùng - ChronoLux";
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
    <style>
        .role-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .role-admin { background: #fee2e2; color: #ef4444; }
        .role-user { background: #e0f2fe; color: #0284c7; }
        .role-shipper { background: #fef3c7; color: #d97706; }
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 1.2rem;
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
                <h2 class="section-title">Quản lý người dùng & Phân quyền</h2>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                            if($_GET['error'] == 'self_demotion') echo "Bạn không thể tự hạ quyền của chính mình!";
                            if($_GET['error'] == 'self_deletion') echo "Bạn không thể tự xóa tài khoản của chính mình!";
                        ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="30%">Người dùng</th>
                                <th width="25%">Email</th>
                                <th width="20%">Vai trò</th>
                                <th width="20%" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= $u['id_nd'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar mr-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold"><?= htmlspecialchars($u['ten_nd'] ?? 'N/A') ?></div>
                                                <div class="small text-muted">ID: #<?= $u['id_nd'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($u['email_nd'] ?? 'Chưa cập nhật') ?></td>
                                    <td>
                                        <form action="index.php?action=capnhatquyen" method="POST" class="d-flex align-items-center">
                                            <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
                                            <input type="hidden" name="id_nd" value="<?= $u['id_nd'] ?>">
                                            <select name="quyen_nd" class="custom-select custom-select-sm mr-2" style="width: 130px; font-family: system-ui, sans-serif;" onchange="this.form.submit()">
                                                <option value="0" <?= $u['quyen_nd'] == 0 ? 'selected' : '' ?>>Khách hàng</option>
                                                <option value="3" <?= $u['quyen_nd'] == 3 ? 'selected' : '' ?>>Shipper</option>
                                                <option value="1" <?= $u['quyen_nd'] == 1 ? 'selected' : '' ?>>Quản trị viên</option>
                                            </select>
                                            <span class="role-badge <?= $u['quyen_nd'] == 1 ? 'role-admin' : ($u['quyen_nd'] == 3 ? 'role-shipper' : 'role-user') ?>">
                                                <?= $u['quyen_nd'] == 1 ? 'Admin' : ($u['quyen_nd'] == 3 ? 'Shipper' : 'User') ?>
                                            </span>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($u['id_nd'] != $_SESSION['id_nd']): ?>
                                            <form method="POST" action="index.php?action=xoanguoidung" style="display:inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này? Thao tác này không thể hoàn tác.')">
                                                <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
                                                <input type="hidden" name="id" value="<?= $u['id_nd'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-user-slash mr-1"></i> Xóa
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge badge-light p-2">Đang đăng nhập</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
	    </div>
	  </div>
	</div>
</body>
</html>