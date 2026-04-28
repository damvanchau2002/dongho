<!DOCTYPE html>
<html>
<head>
	<title>Chi tiết đơn hàng - ChronoLux Admin</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css" />
    
    <!-- Leaflet CSS for Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        #shippingMap { height: 400px; width: 100%; border-radius: 10px; margin-top: 20px; display: none; }
    </style>
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
                                $statusText = [0 => 'Đã hủy', 1 => 'Chờ xử lý', 2 => 'Đang giao hàng', 3 => 'Đã hoàn thành'];
                                $statusClass = [0 => 'badge-danger', 1 => 'badge-warning', 2 => 'badge-info', 3 => 'badge-success'];
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
                                        <option value="2" <?php echo $order['trang_thai'] == 2 ? 'selected' : ''; ?>>Đang giao hàng</option>
                                        <option value="3" <?php echo $order['trang_thai'] == 3 ? 'selected' : ''; ?>>Đã hoàn thành</option>
                                        <option value="0" <?php echo $order['trang_thai'] == 0 ? 'selected' : ''; ?>>Đã hủy</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="shipperSelectContainer" style="<?php echo $order['trang_thai'] == 2 ? 'display:block;' : 'display:none;'; ?>">
                                    <label class="font-weight-bold">Chỉ định Người giao hàng (Shipper):</label>
                                    <select name="id_shipper" id="id_shipper" class="form-control form-select">
                                        <option value="0">-- Chọn Shipper --</option>
                                        <?php if (!empty($shippers)): ?>
                                            <?php foreach ($shippers as $s): ?>
                                                <option value="<?= $s['id_nd'] ?>" <?= (isset($order['id_shipper']) && $order['id_shipper'] == $s['id_nd']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($s['ten_nd']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
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

                <!-- Bản đồ Tracking -->
                <div class="order-info-box" id="mapContainer" style="<?php echo ($order['trang_thai'] == 2 && isset($order['id_shipper']) && $order['id_shipper'] > 0) ? 'display:block;' : 'display:none;'; ?>">
                    <h5><i class="fas fa-map-marked-alt text-primary"></i> Bản đồ theo dõi lộ trình giao hàng</h5>
                    <p class="text-muted small">Đang theo dõi vị trí của Shipper theo thời gian thực.</p>
                    <div id="shippingMap"></div>
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
    
    <!-- Scripts for Tracking Map -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    
    <script>
        // Hiển thị dropdown Shipper khi chọn Đang giao hàng
        const statusSelect = document.querySelector('select[name="trang_thai"]');
        const shipperContainer = document.getElementById('shipperSelectContainer');
        statusSelect.addEventListener('change', function() {
            if (this.value == '2') shipperContainer.style.display = 'block';
            else shipperContainer.style.display = 'none';
        });

        // Map Tracking Logic
        <?php if ($order['trang_thai'] == 2 && isset($order['id_shipper']) && $order['id_shipper'] > 0): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('shippingMap').style.display = 'block';
            const map = L.map('shippingMap').setView([10.762622, 106.660172], 13); // Default HCMC
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let shipperMarker, destMarker, routingControl;

            const shipperIcon = L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/2830/2830305.png', // Delivery icon
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            });

            const destIcon = L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/149/149059.png', // Pin icon
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            });

            const destinationAddress = <?php echo json_encode($order['diachi_nguoinhan']); ?>;
            let destLatLng = null;

            // 1. Geocode Delivery Address (with progressive fallback)
            async function geocodeWithFallback(address) {
                let parts = address.split(',').map(p => p.trim());
                while (parts.length > 0) {
                    let query = parts.join(', ');
                    try {
                        let res = await fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query));
                        let data = await res.json();
                        if (data && data.length > 0) return data[0];
                    } catch (e) {
                        console.error("Geocoding API error:", e);
                    }
                    parts.shift(); // Bỏ phần chi tiết nhất (đầu tiên), thử lại với cấp chung chung hơn (Quận, Tỉnh)
                }
                return null;
            }

            geocodeWithFallback(destinationAddress).then(result => {
                if (result) {
                    destLatLng = L.latLng(result.lat, result.lon);
                    destMarker = L.marker(destLatLng, {icon: destIcon}).addTo(map).bindPopup("<b>Điểm giao hàng:</b><br>" + destinationAddress).openPopup();
                    map.setView(destLatLng, 13);
                } else {
                    alert("Hệ thống bản đồ không thể định vị được Tỉnh/Thành phố trong địa chỉ: " + destinationAddress + "\n(Bản đồ sẽ chỉ hiện vị trí Shipper)");
                }
                updateShipperLocation(); // Initial fetch
                setInterval(updateShipperLocation, 10000); // Poll every 10s
            });

            // 2. Fetch Shipper Location
            function updateShipperLocation() {
                fetch('index.php?action=get_shipper_location&id_shipper=<?php echo $order['id_shipper']; ?>')
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.lat && data.lng) {
                            const shipperLatLng = L.latLng(data.lat, data.lng);
                            
                            if (shipperMarker) {
                                shipperMarker.setLatLng(shipperLatLng);
                            } else {
                                shipperMarker = L.marker(shipperLatLng, {icon: shipperIcon}).addTo(map).bindPopup("<b>Shipper</b>");
                                if(!destLatLng) map.setView(shipperLatLng, 15);
                            }

                            if (destLatLng) {
                                if (routingControl) {
                                    routingControl.setLatLngs([shipperLatLng, destLatLng]);
                                } else {
                                    routingControl = L.polyline([shipperLatLng, destLatLng], {
                                        color: '#e74c3c',
                                        weight: 6,
                                        dashArray: '10, 10',
                                        opacity: 0.8
                                    }).addTo(map);
                                    
                                    // Zoom to fit both markers
                                    map.fitBounds(routingControl.getBounds(), {padding: [50, 50]});
                                }
                            }
                        } else {
                            console.warn("Chưa có tín hiệu GPS từ Shipper");
                        }
                    })
                    .catch(err => console.error("GPS Fetch Error:", err));
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>
