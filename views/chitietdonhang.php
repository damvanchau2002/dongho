<!DOCTYPE html>
<html>
<head>
	<title>Chi tiết đơn hàng - ChronoLux Admin</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css" />
    <style>
        .order-info-box { background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 20px; border: 1px solid #e2e8f0; }
        .order-info-box h5 { color: #0f4c81; font-weight: 700; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
        .item-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title mb-0">Chi tiết đơn hàng #<?php echo $order['ma_dh']; ?></h2>
                    <a href="index.php?action=quanlydonhang" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="order-info-box">
                            <h5>Thông tin người nhận</h5>
                            <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($order['ten_nguoinhan']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email_nguoinhan']); ?></p>
                            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['sdt_nguoinhan']); ?></p>
                            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['diachi_nguoinhan']); ?></p>
                            <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($order['ghichu_nguoinhan']); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="order-info-box">
                            <h5>Trạng thái đơn hàng</h5>
                            <?php
                                $statusText = [0 => 'Đã hủy', 1 => 'Chờ xử lý', 2 => 'Đã hoàn thành'];
                                $statusClass = [0 => 'badge-danger', 1 => 'badge-warning', 2 => 'badge-success'];
                                $paymentRaw = trim((string)($order['phuong_thuc_thanh_toan'] ?? 'COD'));
                                $paymentKey = strtolower($paymentRaw);
                                $paymentMap = [
                                    'cod' => 'Thanh toán khi nhận hàng (COD)',
                                    'momo' => 'Thanh toán qua Ví Momo',
                                    'vnpay' => 'Thanh toán qua VNPay',
                                ];
                                $paymentLabel = $paymentMap[$paymentKey] ?? $paymentRaw;
                            ?>
                            <div class="mb-3">
                                <p class="mb-2"><strong>Trạng thái hiện tại:</strong>
                                    <span class="badge <?php echo $statusClass[$order['trang_thai']] ?? 'badge-secondary'; ?>">
                                        <?php echo $statusText[$order['trang_thai']] ?? 'Không xác định'; ?>
                                    </span>
                                </p>
                                <p class="mb-0"><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($paymentLabel); ?></p>
                            </div>
                            <form action="index.php?action=capnhatdonhang" method="post">
                                <input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::csrfToken(); ?>">
                                <input type="hidden" name="id_dh" value="<?php echo $order['id_dh']; ?>">
                                <div class="mb-3">
                                    <select name="trang_thai" class="form-control form-select mb-3">
                                        <option value="1" <?php echo $order['trang_thai'] == 1 ? 'selected' : ''; ?>>Chờ xử lý</option>
                                        <option value="2" <?php echo $order['trang_thai'] == 2 ? 'selected' : ''; ?>>Đã hoàn thành</option>
                                        <option value="0" <?php echo $order['trang_thai'] == 0 ? 'selected' : ''; ?>>Đã hủy</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Cập nhật trạng thái</button>
                            </form>
                            <?php if ($order['trang_thai'] == 0 && !empty($order['ly_do_huy'])): ?>
                                <div class="alert alert-danger mt-3">
                                    <h6 class="font-weight-bold mb-1"><i class="fas fa-info-circle"></i> Lý do hủy:</h6>
                                    <p class="mb-0 small"><?php echo htmlspecialchars($order['ly_do_huy']); ?></p>
                                </div>
                            <?php endif; ?>
                            <hr>
                            <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['ngay_dat'])); ?></p>
                            <p><strong>Tổng tiền:</strong> <span class="h4 text-primary font-weight-bold"><?php echo number_format($order['tong_tien'], 0, ',', '.'); ?>đ</span></p>
                        </div>
                    </div>
                </div>

                <div class="order-info-box">
                    <h5>Danh sách sản phẩm</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá bán</th>
                                    <th>Số lượng</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td><img src="<?php echo $item['hinhanh_sp']; ?>" class="item-img"></td>
                                        <td><strong><?php echo $item['ten_sp']; ?></strong></td>
                                        <td><?php echo number_format($item['gia_ban'], 0, ',', '.'); ?>đ</td>
                                        <td>x<?php echo $item['so_luong']; ?></td>
                                        <td class="text-right font-weight-bold"><?php echo number_format($item['gia_ban'] * $item['so_luong'], 0, ',', '.'); ?>đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</body>
</html>
