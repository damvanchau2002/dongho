<!DOCTYPE html>
<html>
<head>
	<title>Quản lý đơn hàng - ChronoLux Admin</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css" />
    <style>
        .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .status-1 { background: #fff7ed; color: #c2410c; } /* Chờ xử lý */
        .status-2 { background: #f0fdf4; color: #15803d; } /* Đã hoàn thành */
        .status-0 { background: #fef2f2; color: #b91c1c; } /* Đã hủy */
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
                <h2 class="section-title">Quản lý đơn hàng</h2>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Số điện thoại</th>
                                <th>Tổng tiền</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong>#<?php echo $order['ma_dh']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($order['ten_nguoinhan']); ?></td>
                                        <td><?php echo htmlspecialchars($order['sdt_nguoinhan']); ?></td>
                                        <td><span class="text-primary font-weight-bold"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</span></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['ngay_dat'])); ?></td>
                                        <td>
                                            <?php 
                                                $statusText = [0 => 'Đã hủy', 1 => 'Chờ xử lý', 2 => 'Hoàn thành'];
                                                $statusClass = "status-" . $order['trang_thai'];
                                            ?>
                                            <span class="badge-status <?php echo $statusClass; ?>">
                                                <?php echo $statusText[$order['trang_thai']]; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?action=chitietdonhang&id=<?php echo $order['id_dh']; ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> Xem chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có đơn hàng nào.</td></tr>
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
