<?php
    // set page title
	$pageTitle = "Quản trị - ChronoLux";

	if (isset($_POST['nutdx'])) {
		session_unset();
        header("Location: index.php");
        exit;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Quản trị - ChronoLux</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/admin_custom.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            border-radius: 15px;
            padding: 20px;
            color: white;
            transition: transform 0.3s;
            border: none;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card.bg-products { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card.bg-brands { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
        .stat-card.bg-orders { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }
        .stat-card.bg-users { background: linear-gradient(135deg, #5ee7df 0%, #b490ca 100%); }
        .stat-card.bg-revenue { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: #1e293b; }
        .stat-card.bg-pending { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #1e293b; }
        
        .stat-card .icon {
            font-size: 2.5rem;
            opacity: 0.3;
        }
        .stat-card .info h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        .stat-card .info p {
            font-size: 0.9rem;
            margin-bottom: 0;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .table thead th {
            border-bottom: 2px solid #edf2f9;
            color: #8b9eb7;
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding-bottom: 15px;
        }
        .table tbody td {
            vertical-align: middle;
            color: #334;
            font-weight: 500;
            border-bottom: 1px solid #edf2f9;
        }
        .action-links {
            display: flex;
            gap: 8px;
        }
        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .action-edit {
            background: #e0f2fe;
            color: #0284c7;
        }
        .action-delete {
            background: #fee2e2;
            color: #ef4444;
        }
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
            <!-- Dashboard Stats -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card bg-products">
                        <div class="info">
                            <h3><?= $stats['total_products'] ?></h3>
                            <p>Sản phẩm</p>
                        </div>
                        <div class="icon"><i class="fas fa-boxes"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-revenue">
                        <div class="info">
                            <h3><?= number_format($stats['revenue_month'], 0, ',', '.') ?>đ</h3>
                            <p>Doanh thu tháng</p>
                        </div>
                        <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-orders">
                        <div class="info">
                            <h3><?= $stats['total_orders'] ?? 0 ?></h3>
                            <p>Tổng đơn hàng</p>
                        </div>
                        <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-pending">
                        <div class="info">
                            <h3><?= $stats['total_pending_orders'] ?? 0 ?></h3>
                            <p>Đơn chờ duyệt</p>
                        </div>
                        <div class="icon"><i class="fas fa-clock"></i></div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-5">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0" style="border-radius: 15px;">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold text-secondary mb-4">Biểu đồ Doanh Thu (6 tháng)</h5>
                            <canvas id="revenueChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="border-radius: 15px; height: 100%;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title font-weight-bold text-secondary mb-4">Trạng thái Đơn hàng</h5>
                            <div style="position: relative; flex-grow: 1; min-height: 250px; width: 100%; display: flex; justify-content: center;">
                                <canvas id="orderStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


	    </div>
	  </div>
	</div>

    <script>
        // Dữ liệu từ Backend PHP truyền xuống
        const chartData = <?= json_encode($chartData ?? ['revenue_by_month' => [], 'orders_by_status' => []]) ?>;

        // 1. Biểu đồ Doanh Thu (Bar Chart)
        const revCtx = document.getElementById('revenueChart').getContext('2d');
        const labelsRev = chartData.revenue_by_month.map(item => item.month);
        const dataRev = chartData.revenue_by_month.map(item => item.revenue);

        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: labelsRev,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: dataRev,
                    backgroundColor: 'rgba(67, 233, 123, 0.2)',
                    borderColor: '#43e97b',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#43e97b',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' đ';
                            }
                        }
                    }
                }
            }
        });

        // 2. Biểu đồ Trạng thái đơn hàng (Doughnut Chart)
        const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const labelsStatus = chartData.orders_by_status.labels || [];
        const dataStatus = chartData.orders_by_status.data || [];

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: labelsStatus,
                datasets: [{
                    data: dataStatus,
                    backgroundColor: [
                        '#fee140', // Chờ xử lý
                        '#43e97b', // Hoàn thành
                        '#fa709a'  // Đã hủy
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });
    </script>
</body>
</html>
