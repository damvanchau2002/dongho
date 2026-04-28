<?php
    $pageTitle = "Trang Shipper - Giao Hàng";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; }
        .navbar { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); padding: 15px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .navbar-brand { color: white !important; font-weight: 700; font-size: 1.5rem; letter-spacing: 1px; }
        .nav-link { color: rgba(255,255,255,0.9) !important; font-weight: 500; transition: 0.3s; }
        .nav-link:hover { color: #fff !important; text-shadow: 0 0 10px rgba(255,255,255,0.5); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card-header { background: #fff; border-bottom: 1px solid #f0f0f0; border-radius: 15px 15px 0 0 !important; font-weight: 600; }
        .badge-status { padding: 8px 12px; border-radius: 20px; font-weight: 500; font-size: 0.85rem; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-delivering { background: #cce5ff; color: #004085; }
        .badge-success { background: #d4edda; color: #155724; }
        .btn-update { border-radius: 20px; font-weight: 600; padding: 8px 20px; }
        .gps-status { font-size: 0.9rem; color: #28a745; font-weight: 500; display: flex; align-items: center; gap: 8px; }
        .pulsating-circle { width: 10px; height: 10px; background-color: #28a745; border-radius: 50%; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-motorcycle mr-2"></i>Shipper Portal</a>
            <div class="ml-auto d-flex align-items-center">
                <div class="gps-status mr-4" id="gpsStatus">
                    <div class="pulsating-circle"></div> Đang phát GPS
                </div>
                <span class="text-white mr-3">Xin chào, <?= htmlspecialchars($_SESSION['tennd']) ?></span>
                <a href="index.php?action=dangxuat" class="btn btn-sm btn-light rounded-pill"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Đơn hàng chờ nhận -->
        <h4 class="mb-4 font-weight-bold text-primary"><i class="fas fa-motorcycle mr-2"></i>Đơn hàng chờ nhận (Có thể giao)</h4>
        
        <?php if (empty($availableOrders)): ?>
            <div class="alert alert-light text-center py-4 rounded mb-5" style="border:1px dashed #ccc;">
                <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                <p class="mb-0 text-muted">Hiện tại không có đơn hàng nào đang chờ giao.</p>
            </div>
        <?php else: ?>
            <div class="row mb-5">
                <?php foreach ($availableOrders as $o): ?>
                <div class="col-md-6">
                    <div class="card" style="border-left: 4px solid #f39c12;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Mã ĐH: <strong>#<?= $o['ma_dh'] ?></strong></span>
                            <span class="badge-status badge-pending">Đang chờ Shipper</span>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><i class="fas fa-user text-muted mr-2" style="width:20px"></i> <strong><?= htmlspecialchars($o['ten_nguoinhan']) ?></strong></p>
                            <p class="mb-2"><i class="fas fa-phone text-muted mr-2" style="width:20px"></i> <?= htmlspecialchars($o['sdt_nguoinhan']) ?></p>
                            <p class="mb-2"><i class="fas fa-map-marker-alt text-muted mr-2" style="width:20px"></i> <?= htmlspecialchars($o['diachi_nguoinhan']) ?></p>
                            <p class="mb-3"><i class="fas fa-money-bill-wave text-muted mr-2" style="width:20px"></i> <span class="font-weight-bold text-danger"><?= number_format($o['tong_tien'], 0, ',', '.') ?>đ</span></p>
                            
                            <form action="index.php?action=shipper_accept_order" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn nhận giao đơn hàng này?');">
                                <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
                                <input type="hidden" name="id_dh" value="<?= $o['id_dh'] ?>">
                                <button type="submit" class="btn btn-warning w-100 btn-update text-white"><i class="fas fa-hand-paper mr-2"></i> Nhận đơn hàng này</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Đơn hàng của bạn -->
        <h4 class="mb-4 font-weight-bold text-secondary"><i class="fas fa-box-open mr-2"></i>Đơn hàng bạn đang giao</h4>
        
        <?php if (empty($orders)): ?>
            <div class="alert alert-info text-center py-5 rounded" style="background:#fff; border:none; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
                <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
                <h5 class="text-muted">Bạn chưa nhận đơn hàng nào.</h5>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($orders as $o): 
                    $statusMap = [0 => 'Đã hủy', 1 => 'Chờ xử lý', 2 => 'Đang giao', 3 => 'Thành công'];
                    $statusBadge = [0 => 'badge-secondary', 1 => 'badge-pending', 2 => 'badge-delivering', 3 => 'badge-success'];
                ?>
                <div class="col-md-6">
                    <div class="card" style="border-left: 4px solid #1a4a7a;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Mã ĐH: <strong>#<?= $o['ma_dh'] ?></strong></span>
                            <span class="badge-status <?= $statusBadge[$o['trang_thai']] ?>"><?= $statusMap[$o['trang_thai']] ?></span>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><i class="fas fa-user text-muted mr-2" style="width:20px"></i> <strong><?= htmlspecialchars($o['ten_nguoinhan']) ?></strong></p>
                            <p class="mb-2"><i class="fas fa-phone text-muted mr-2" style="width:20px"></i> <?= htmlspecialchars($o['sdt_nguoinhan']) ?></p>
                            <p class="mb-2"><i class="fas fa-map-marker-alt text-muted mr-2" style="width:20px"></i> <?= htmlspecialchars($o['diachi_nguoinhan']) ?></p>
                            <p class="mb-3"><i class="fas fa-money-bill-wave text-muted mr-2" style="width:20px"></i> <span class="font-weight-bold text-danger"><?= number_format($o['tong_tien'], 0, ',', '.') ?>đ</span> (<?= $o['phuong_thuc_thanh_toan'] ?>)</p>
                            
                            <div class="d-grid gap-2">
                                <a href="index.php?action=shipper_view_map&id_dh=<?= $o['id_dh'] ?>" class="btn btn-info btn-update text-white mb-2"><i class="fas fa-map mr-2"></i> Xem bản đồ</a>
                                <?php if ($o['trang_thai'] == 2): ?>
                                <form action="index.php?action=shipper_update_status" method="POST" onsubmit="return confirm('Xác nhận đã giao hàng thành công?');">
                                    <input type="hidden" name="csrf_token" value="<?= SecurityHelper::csrfToken() ?>">
                                    <input type="hidden" name="id_dh" value="<?= $o['id_dh'] ?>">
                                    <input type="hidden" name="trang_thai" value="3">
                                    <button type="submit" class="btn btn-success w-100 btn-update"><i class="fas fa-check-circle mr-2"></i> Báo cáo Đã giao xong</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- GPS Tracking Script -->
    <script>
        const gpsStatus = document.getElementById('gpsStatus');
        
        if ("geolocation" in navigator) {
            navigator.geolocation.watchPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // Send to server
                fetch('index.php?action=shipper_update_location', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ lat: lat, lng: lng })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        gpsStatus.innerHTML = '<div class="pulsating-circle"></div> Đang phát GPS (' + lat.toFixed(4) + ', ' + lng.toFixed(4) + ')';
                        gpsStatus.style.color = '#28a745';
                    }
                })
                .catch(err => console.error(err));
                
            }, function(error) {
                console.error("Error getting location: ", error);
                gpsStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Lỗi GPS. Vui lòng cấp quyền vị trí.';
                gpsStatus.style.color = '#dc3545';
            }, {
                enableHighAccuracy: true,
                maximumAge: 10000,
                timeout: 5000
            });
        } else {
            gpsStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Trình duyệt không hỗ trợ GPS.';
            gpsStatus.style.color = '#dc3545';
        }
    </script>
</body>
</html>
