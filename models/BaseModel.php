<?php
/**
 * BaseModel - Xử lý database
 * Sử dụng Prepared Statements để chống SQL Injection
 */
require_once dirname(__FILE__) . '/../helpers/SecurityHelper.php';

class BaseModel
{
	public $connect=null;
	private $columnsCache = [];

	function __construct()
	{
		$this->ketnoi();
		$this->ensureSchema(); // Khởi tạo schema một lần duy nhất sau khi kết nối
	}

	/**
	 * Tập trung toàn bộ lệnh CREATE TABLE và ALTER TABLE vào đây.
	 * Gọi một lần trong __construct — không lằp lại trong từng method nghiệp vụ.
	 */
	private function ensureSchema(): void
	{
		$db = $this->connect;

		// Bảng mã giảm giá
		$db->query("
			CREATE TABLE IF NOT EXISTS ma_giam_gia (
				id          INT(10) AUTO_INCREMENT PRIMARY KEY,
				ma_code     VARCHAR(50) NOT NULL UNIQUE,
				loai        ENUM('fixed','percentage') NOT NULL DEFAULT 'fixed',
				gia_tri     DECIMAL(20,2) NOT NULL,
				so_luong    INT(11) NOT NULL DEFAULT 0,
				ngay_het_han DATE DEFAULT NULL,
				ngay_tao    DATETIME DEFAULT CURRENT_TIMESTAMP
			)
		");

		// Bảng lưu thông tin Momo
		$db->query("
			CREATE TABLE IF NOT EXISTS momos (
				id          INT AUTO_INCREMENT PRIMARY KEY,
				customer_id INT NULL,
				momo_status INT DEFAULT 0,
				link_data   TEXT NULL,
				created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
			)
		");

		// Bảng đơn hàng
		$db->query("
			CREATE TABLE IF NOT EXISTS donhang (
				id_dh                   INT(10) AUTO_INCREMENT PRIMARY KEY,
				ma_dh                   VARCHAR(10),
				id_nd                   INT(10),
				ten_nguoinhan           VARCHAR(255),
				email_nguoinhan         VARCHAR(255),
				sdt_nguoinhan           VARCHAR(20),
				diachi_nguoinhan        TEXT,
				ghichu_nguoinhan        TEXT,
				tong_tien               DECIMAL(20,2),
				giam_gia                DECIMAL(20,2) DEFAULT 0,
				ngay_dat                DATETIME DEFAULT CURRENT_TIMESTAMP,
				trang_thai              INT DEFAULT 1,
				ly_do_huy               TEXT DEFAULT NULL,
				phuong_thuc_thanh_toan  VARCHAR(50) DEFAULT 'COD'
			)
		");

		// Backward-compat: bổ sung cột nếu DB cũ chưa có
		foreach ([
			"giam_gia"               => "ALTER TABLE donhang ADD COLUMN giam_gia DECIMAL(20,2) DEFAULT 0 AFTER tong_tien",
			"ly_do_huy"              => "ALTER TABLE donhang ADD COLUMN ly_do_huy TEXT DEFAULT NULL AFTER trang_thai",
			"phuong_thuc_thanh_toan" => "ALTER TABLE donhang ADD COLUMN phuong_thuc_thanh_toan VARCHAR(50) DEFAULT 'COD' AFTER ly_do_huy",
		] as $col => $alter) {
			$check = $db->query("SHOW COLUMNS FROM donhang LIKE '{$col}'");
			if ($check && $check->num_rows === 0) {
				$db->query($alter);
			}
		}

		// Bảng chi tiết đơn hàng
		$db->query("
			CREATE TABLE IF NOT EXISTS chitietdonhang (
				id_ctdh INT(10) AUTO_INCREMENT PRIMARY KEY,
				id_dh   INT(10),
				id_sp   INT(10),
				so_luong INT,
				gia_ban DECIMAL(20,2)
			)
		");

		// Bảng email_nd nếu thiếu trong quanlynguoidung
		$checkEmail = $db->query("SHOW COLUMNS FROM quanlynguoidung LIKE 'email_nd'");
		if ($checkEmail && $checkEmail->num_rows === 0) {
            $db->query("ALTER TABLE quanlynguoidung ADD COLUMN email_nd VARCHAR(255) DEFAULT NULL AFTER ten_nd");
        }

		// Thêm số điện thoại người dùng
		$checkSdt = $db->query("SHOW COLUMNS FROM quanlynguoidung LIKE 'sdt_nd'");
		if ($checkSdt && $checkSdt->num_rows === 0) {
			$db->query("ALTER TABLE quanlynguoidung ADD COLUMN sdt_nd VARCHAR(20) DEFAULT NULL AFTER email_nd");
		}

		// Thêm địa chỉ người dùng
		$checkDiachi = $db->query("SHOW COLUMNS FROM quanlynguoidung LIKE 'diachi_nd'");
		if ($checkDiachi && $checkDiachi->num_rows === 0) {
			$db->query("ALTER TABLE quanlynguoidung ADD COLUMN diachi_nd VARCHAR(500) DEFAULT NULL AFTER sdt_nd");
		}

		// Bổ sung GPS và Shipper ID
		$checkLat = $db->query("SHOW COLUMNS FROM quanlynguoidung LIKE 'lat'");
		if ($checkLat && $checkLat->num_rows === 0) {
			$db->query("ALTER TABLE quanlynguoidung ADD COLUMN lat DECIMAL(10,8) DEFAULT NULL");
			$db->query("ALTER TABLE quanlynguoidung ADD COLUMN lng DECIMAL(11,8) DEFAULT NULL");
		}
		
		$checkIdShipper = $db->query("SHOW COLUMNS FROM donhang LIKE 'id_shipper'");
		if ($checkIdShipper && $checkIdShipper->num_rows === 0) {
			$db->query("ALTER TABLE donhang ADD COLUMN id_shipper INT DEFAULT NULL AFTER trang_thai");
		}

		// Tạo tài khoản Shipper test mặc định nếu chưa có
		$checkShipper = $db->query("SELECT * FROM quanlynguoidung WHERE ten_nd = 'shipper' LIMIT 1");
		if ($checkShipper && $checkShipper->num_rows === 0) {
			$pass = password_hash('123456', PASSWORD_DEFAULT);
			$db->query("INSERT INTO quanlynguoidung (ten_nd, email_nd, matkhau_nd, quyen_nd) VALUES ('shipper', 'shipper@demo.com', '$pass', 3)");
		} else {
            // Cập nhật tài khoản shipper cũ lên quyền 3
            $db->query("UPDATE quanlynguoidung SET quyen_nd = 3 WHERE ten_nd = 'shipper' AND quyen_nd = 2");
        }

		// Backward-compat: bổ sung cột flash sale và tồn kho cho bảng sanpham
		foreach ([
			"flash_sale_price" => "ALTER TABLE sanpham ADD COLUMN flash_sale_price DECIMAL(20,2) DEFAULT 0",
			"flash_sale_end"   => "ALTER TABLE sanpham ADD COLUMN flash_sale_end DATETIME NULL",
			"so_luong_ton"     => "ALTER TABLE sanpham ADD COLUMN so_luong_ton INT DEFAULT 0"
		] as $col => $alter) {
			$check = $db->query("SHOW COLUMNS FROM sanpham LIKE '{$col}'");
			if ($check && $check->num_rows === 0) {
				$db->query($alter);
			}
		}

		// Đảm bảo cột mota_sp có thể chứa văn bản dài (LONGTEXT) và giá sản phẩm đủ lớn
		$db->query("ALTER TABLE sanpham MODIFY COLUMN mota_sp LONGTEXT");
		$db->query("ALTER TABLE sanpham MODIFY COLUMN gia_sp DECIMAL(20,2)");

        $db->query("
            CREATE TABLE IF NOT EXISTS banners (
                id INT AUTO_INCREMENT PRIMARY KEY,
                hinh_anh VARCHAR(255) NOT NULL,
                link VARCHAR(255) DEFAULT '#',
                vi_tri VARCHAR(50) DEFAULT 'main',
                trang_thai TINYINT(1) DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $resBan = $db->query("SELECT COUNT(*) AS c FROM banners");
        if ($resBan && $resBan->fetch_assoc()['c'] == 0) {
            $db->query("INSERT INTO banners (hinh_anh, link, vi_tri) VALUES 
                ('https://cf.shopee.vn/file/vn-50009109-1a3ebc4e7ab2e2c0b7937f374737de53_xxhdpi', '#', 'main'),
                ('https://cf.shopee.vn/file/vn-50009109-13edcd3b516bcaefa98f3b14dcd541af_xhdpi', '#', 'side1'),
                ('https://cf.shopee.vn/file/vn-50009109-1e39a3f2bb434bd7a50352ef2dc74f4b_xhdpi', '#', 'side2')
            ");
        }

        // Bảng bình luận và đánh giá sản phẩm
        $db->query("
            CREATE TABLE IF NOT EXISTS danhgia_binhluan (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_sp INT NOT NULL,
                ten_nguoidung VARCHAR(100) NOT NULL,
                email_nguoidung VARCHAR(100) NOT NULL,
                sao_danh_gia TINYINT(1) NOT NULL DEFAULT 5,
                tieu_de VARCHAR(255) NOT NULL,
                noi_dung LONGTEXT NOT NULL,
                trang_thai TINYINT(1) DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_sp) REFERENCES sanpham(id_sp) ON DELETE CASCADE
            )
        ");

        // Bảng tin nhắn Live Chat
        $db->query("
            CREATE TABLE IF NOT EXISTS tin_nhan (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                sender_type ENUM('user', 'admin') NOT NULL,
                message TEXT NOT NULL,
                is_read TINYINT(1) DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES quanlynguoidung(id_nd) ON DELETE CASCADE
            )
        ");
    }

	public function ketnoi(){
		// Sử dụng kết nối tập trung từ Database core nếu có
		if (class_exists('Database')) {
			$this->connect = Database::connection();
		} else {
			// Fallback cho môi trường cũ hoặc script độc lập
			require_once dirname(__DIR__) . '/config/database.php';
			$this->connect = getConnection();
		}

		if (!$this->connect || $this->connect->connect_error) {
			die("Database connection failed.");
		}
	}
	
	/**
	 * Kiểm tra đăng nhập và lấy dữ liệu user
	 * @param string $tk - username
	 * @param string $mk_hash - password hash từ database để verify
	 * @return array|int - user data hoặc 0 nếu fail
	 */
	public function kiemtradangnhap($tk, $mk_hash = null){
		$stmt = $this->connect->prepare("SELECT * FROM quanlynguoidung WHERE ten_nd = ?");
		if (!$stmt) {
			return 0;
		}
		$stmt->bind_param("s", $tk);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}
	
	/**
	 * Kiểm tra số lượng bản ghi khớp
	 */
	public function dembanghi($tk, $mk_hash = null){
		$stmt = $this->connect->prepare("SELECT * FROM quanlynguoidung WHERE ten_nd = ?");
		if (!$stmt) {
			return 0;
		}
		$stmt->bind_param("s", $tk);
		$stmt->execute();
		$result = $stmt->get_result();
		$dem = $result->num_rows > 0 ? 1 : 0;
		$stmt->close();
		return $dem;
	}
	
	public function layloaisanpham(){
		$stmt = $this->connect->prepare("SELECT * FROM loaisanpham");
		if (!$stmt) {
			return 0;
		}
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}

	public function layMaGiamGia($code){
		$stmt = $this->connect->prepare("SELECT * FROM ma_giam_gia WHERE ma_code = ? AND (ngay_het_han IS NULL OR ngay_het_han >= CURDATE()) AND so_luong > 0");
		if (!$stmt) {
			return 0;
		}
		$stmt->bind_param("s", $code);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = $result->fetch_assoc();
		$stmt->close();
		return $data;
	}

	public function layTatCaMaGiamGia(){
		$stmt = $this->connect->prepare("SELECT * FROM ma_giam_gia ORDER BY ngay_tao DESC");
		if (!$stmt) {
			return 0;
		}
		$stmt->execute();
		$result = $stmt->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}

	public function themMaGiamGia($code, $loai, $gia_tri, $so_luong, $ngay_het_han){
		$stmt = $this->connect->prepare("INSERT INTO ma_giam_gia (ma_code, loai, gia_tri, so_luong, ngay_het_han) VALUES (?, ?, ?, ?, ?)");
		if (!$stmt) return false;
		$stmt->bind_param("ssdis", $code, $loai, $gia_tri, $so_luong, $ngay_het_han);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

	public function storeMomoInfo($customer_id, $momo_status, $link_data) {
		$stmt = $this->connect->prepare("INSERT INTO momos (customer_id, momo_status, link_data) VALUES (?, ?, ?)");
		if (!$stmt) return false;
		$stmt->bind_param("iis", $customer_id, $momo_status, $link_data);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

	public function xoaMaGiamGia($id){
		$stmt = $this->connect->prepare("DELETE FROM ma_giam_gia WHERE id = ?");
		if (!$stmt) return false;
		$stmt->bind_param("i", $id);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}
	
	public function laysanpham(){
		$stmt = $this->connect->prepare("SELECT * FROM sanpham, loaisanpham WHERE sanpham.id_loaisp = loaisanpham.id_loaisp");
		if (!$stmt) {
			return 0;
		}
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}

	public function demTongSanPham(){
		$stmt = $this->connect->prepare("SELECT COUNT(*) as total FROM sanpham");
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$stmt->close();
		return $row['total'];
	}

	public function laysanphamPhanTrang($limit, $offset){
		$stmt = $this->connect->prepare("SELECT * FROM sanpham LEFT JOIN loaisanpham ON sanpham.id_loaisp = loaisanpham.id_loaisp ORDER BY sanpham.id_sp DESC LIMIT ? OFFSET ?");
		$stmt->bind_param("ii", $limit, $offset);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) return 0;
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}

	public function laysanphamtheoidloai($idl){
		if (!SecurityHelper::validateInteger($idl)) {
			return 0;
		}
		$stmt = $this->connect->prepare("SELECT * FROM sanpham, loaisanpham WHERE sanpham.id_loaisp = loaisanpham.id_loaisp AND sanpham.id_loaisp = ?");
		if (!$stmt) {
			return 0;
		}
		$stmt->bind_param("i", $idl);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}

	public function demTongSanPhamTheoLoai($idl){
		$stmt = $this->connect->prepare("SELECT COUNT(*) as total FROM sanpham WHERE id_loaisp = ?");
		$stmt->bind_param("i", $idl);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$stmt->close();
		return $row['total'];
	}

	public function laysanphamtheoidloaiPhanTrang($idl, $limit, $offset){
		$stmt = $this->connect->prepare("SELECT * FROM sanpham LEFT JOIN loaisanpham ON sanpham.id_loaisp = loaisanpham.id_loaisp WHERE sanpham.id_loaisp = ? ORDER BY sanpham.id_sp DESC LIMIT ? OFFSET ?");
		$stmt->bind_param("iii", $idl, $limit, $offset);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) return 0;
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}

	public function laysanphamngaunhien($slsp){
		$stmt = $this->connect->prepare("SELECT * FROM sanpham ORDER BY RAND() LIMIT ?");
		if (!$stmt) return 0;
		$stmt->bind_param("i", $slsp);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}
	
	public function themsanpham($ten, $link_ha, $gia, $ngay, $id_l, $mota, $so_luong_ton = 0){
		$stmt = $this->connect->prepare("INSERT INTO sanpham (ten_sp, hinhanh_sp, gia_sp, ngaynhap_sp, id_loaisp, mota_sp, so_luong_ton) VALUES (?, ?, ?, ?, ?, ?, ?)");
		if (!$stmt) {
			return false;
		}
		$stmt->bind_param("ssdsssi", $ten, $link_ha, $gia, $ngay, $id_l, $mota, $so_luong_ton);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	public function themloaisp($ten){
		$stmt = $this->connect->prepare("INSERT INTO loaisanpham (ten_loaisp) VALUES (?)");
		if (!$stmt) return false;
		$stmt->bind_param("s", $ten);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	public function sualoaisp($id, $ten){
		$stmt = $this->connect->prepare("UPDATE loaisanpham SET ten_loaisp = ? WHERE id_loaisp = ?");
		if (!$stmt) return false;
		$stmt->bind_param("si", $ten, $id);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	public function xoaloaisp($id){
		$stmt = $this->connect->prepare("DELETE FROM loaisanpham WHERE id_loaisp = ?");
		if (!$stmt) return false;
		$stmt->bind_param("i", $id);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	public function layloaisptheoid($id){
		$stmt = $this->connect->prepare("SELECT * FROM loaisanpham WHERE id_loaisp = ?");
		if (!$stmt) return 0;
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$res = $stmt->get_result();
		$data = $res->fetch_assoc();
		$stmt->close();
		return $data;
	}

	public function layTatCaNguoiDung(){
		$stmt = $this->connect->prepare("SELECT * FROM quanlynguoidung ORDER BY id_nd DESC");
		if (!$stmt) return 0;
		$stmt->execute();
		$result = $stmt->get_result();
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}

	public function layNguoiDungTheoId($id){
		$stmt = $this->connect->prepare("SELECT * FROM quanlynguoidung WHERE id_nd = ?");
		if (!$stmt) return 0;
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$data = $result->fetch_assoc();
		$stmt->close();
		return $data;
	}

	public function capNhatQuyenNguoiDung($id, $quyen){
		$stmt = $this->connect->prepare("UPDATE quanlynguoidung SET quyen_nd = ? WHERE id_nd = ?");
		if (!$stmt) return false;
		$stmt->bind_param("ii", $quyen, $id);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

	public function xoaNguoiDung($id){
		$stmt = $this->connect->prepare("DELETE FROM quanlynguoidung WHERE id_nd = ?");
		if (!$stmt) return false;
		$stmt->bind_param("i", $id);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

	/**
	 * Cập nhật thông tin cá nhân người dùng (tên, email, sdt, địa chỉ)
	 */
	public function capNhatThongTinNguoiDung($id, $ten, $email, $sdt, $diachi) {
		$stmt = $this->connect->prepare(
			"UPDATE quanlynguoidung SET ten_nd = ?, email_nd = ?, sdt_nd = ?, diachi_nd = ? WHERE id_nd = ?"
		);
		if (!$stmt) return false;
		$stmt->bind_param("ssssi", $ten, $email, $sdt, $diachi, $id);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

	public function layThongKe() {
		$stats = [];
		
		// Tổng sản phẩm
		$res = $this->connect->query("SELECT COUNT(*) as total FROM sanpham");
		$stats['total_products'] = $res->fetch_assoc()['total'];

		// Tổng thương hiệu
		$res = $this->connect->query("SELECT COUNT(*) as total FROM loaisanpham WHERE ten_loaisp != 'THÊM'");
		$stats['total_brands'] = $res->fetch_assoc()['total'];

		// Tổng người dùng
		$res = $this->connect->query("SELECT COUNT(*) as total FROM quanlynguoidung");
		$stats['total_users'] = $res->fetch_assoc()['total'];

		// Tổng đơn hàng
		$res = $this->connect->query("SHOW TABLES LIKE 'donhang'");
		if ($res && $res->num_rows > 0) {
			$res2 = $this->connect->query("SELECT COUNT(*) as total FROM donhang");
			$stats['total_orders'] = $res2->fetch_assoc()['total'];

			$res_pending = $this->connect->query("SELECT COUNT(*) as total FROM donhang WHERE trang_thai = 1");
			$stats['total_pending_orders'] = $res_pending->fetch_assoc()['total'] ?? 0;
			
			// Revenue (Completed only: trang_thai = 3)
			$res3 = $this->connect->query("SELECT SUM(tong_tien) as total FROM donhang WHERE trang_thai = 3");
			$total_rev = $res3->fetch_assoc()['total'] ?? 0;
			$stats['total_revenue'] = $total_rev;
			$stats['total_profit'] = $total_rev * 0.4; // Ước tính lợi nhuận 40%

			$res4 = $this->connect->query("SELECT SUM(tong_tien) as total FROM donhang WHERE trang_thai = 3 AND DATE(ngay_dat) = CURDATE()");
			$stats['revenue_today'] = $res4->fetch_assoc()['total'] ?? 0;

			$res5 = $this->connect->query("SELECT SUM(tong_tien) as total FROM donhang WHERE trang_thai = 3 AND MONTH(ngay_dat) = MONTH(CURDATE()) AND YEAR(ngay_dat) = YEAR(CURDATE())");
			$stats['revenue_month'] = $res5->fetch_assoc()['total'] ?? 0;
		} else {
			$stats['total_orders'] = 0;
			$stats['total_pending_orders'] = 0;
			$stats['total_revenue'] = 0;
			$stats['revenue_today'] = 0;
			$stats['revenue_month'] = 0;
		}
		
		return $stats;
	}

	public function layDuLieuBieuDo() {
		$chartData = [
			'revenue_by_month' => [],
			'orders_by_status' => [],
			'top_products' => []
		];

		$res = $this->connect->query("SHOW TABLES LIKE 'donhang'");
		if (!$res || $res->num_rows == 0) {
			return $chartData;
		}

		// 1. Doanh thu & Đơn hàng trong 6 tháng gần nhất
		$sqlRevenue = "
			SELECT 
				DATE_FORMAT(ngay_dat, '%m/%Y') as month_year,
				SUM(CASE WHEN trang_thai = 3 THEN tong_tien ELSE 0 END) as total_revenue,
				COUNT(*) as total_orders
			FROM donhang 
			WHERE ngay_dat >= DATE_SUB(LAST_DAY(CURDATE()), INTERVAL 6 MONTH)
			GROUP BY DATE_FORMAT(ngay_dat, '%Y-%m'), DATE_FORMAT(ngay_dat, '%m/%Y')
			ORDER BY DATE_FORMAT(ngay_dat, '%Y-%m') ASC
		";
		$resRev = $this->connect->query($sqlRevenue);
		if ($resRev) {
			while ($row = $resRev->fetch_assoc()) {
				$chartData['revenue_by_month'][] = [
					'month' => $row['month_year'],
					'revenue' => (float)$row['total_revenue'],
					'orders' => (int)$row['total_orders']
				];
			}
		}

		// 2. Phân bổ trạng thái đơn hàng (Trong năm nay)
		$sqlStatus = "
			SELECT trang_thai, COUNT(*) as count 
			FROM donhang 
			WHERE YEAR(ngay_dat) = YEAR(CURDATE())
			GROUP BY trang_thai
		";
		$resStat = $this->connect->query($sqlStatus);
		
		$statusMap = [
			1 => 'Chờ xử lý',
			2 => 'Đang giao hàng',
			3 => 'Hoàn thành',
			0 => 'Đã hủy'
		];

		$statusCounts = [
			'Chờ xử lý' => 0,
			'Đang giao hàng' => 0,
			'Hoàn thành' => 0,
			'Đã hủy' => 0
		];

		if ($resStat) {
			while ($row = $resStat->fetch_assoc()) {
				$statusCode = (int)$row['trang_thai'];
				$statusName = $statusMap[$statusCode] ?? "Khác ($statusCode)";
				if (isset($statusCounts[$statusName])) {
					$statusCounts[$statusName] += (int)$row['count'];
				} else {
					$statusCounts[$statusName] = (int)$row['count'];
				}
			}
		}
		
		foreach ($statusCounts as $label => $count) {
			$chartData['orders_by_status']['labels'][] = $label;
			$chartData['orders_by_status']['data'][] = $count;
		}

		// 3. Top 5 sản phẩm bán chạy nhất
		$sqlTopProducts = "
			SELECT sp.ten_sp, SUM(ct.so_luong) as total_sold
			FROM chitietdonhang ct
			JOIN sanpham sp ON ct.id_sp = sp.id_sp
			JOIN donhang dh ON ct.id_dh = dh.id_dh
			WHERE dh.trang_thai = 3
			GROUP BY ct.id_sp
			ORDER BY total_sold DESC
			LIMIT 5
		";
		$resTop = $this->connect->query($sqlTopProducts);
		if ($resTop) {
			while ($row = $resTop->fetch_assoc()) {
				$chartData['top_products'][] = [
					'name' => $row['ten_sp'],
					'sold' => (int)$row['total_sold']
				];
			}
		}

		return $chartData;
	}

	public function taoDonHang($data) {
		$stmt = $this->connect->prepare("INSERT INTO donhang (ma_dh, id_nd, ten_nguoinhan, email_nguoinhan, sdt_nguoinhan, diachi_nguoinhan, ghichu_nguoinhan, tong_tien, giam_gia) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		if (!$stmt) return false;

		$giam_gia = $data['giam_gia'] ?? 0;
		$stmt->bind_param("sisssssdd", $data['ma_dh'], $data['id_nd'], $data['ten_nn'], $data['email_nn'], $data['sdt_nn'], $data['diachi_nn'], $data['ghichu_nn'], $data['tong_tien'], $giam_gia);
		if ($stmt->execute()) {
			$orderId = $this->connect->insert_id;
			$stmt->close();
			return $orderId;
		}
		$stmt->close();
		return false;
	}

	public function capNhatPhuongThucThanhToan($ma_dh, $pttt) {
		$stmt = $this->connect->prepare("UPDATE donhang SET phuong_thuc_thanh_toan = ? WHERE ma_dh = ?");
		if (!$stmt) return false;
		$stmt->bind_param("ss", $pttt, $ma_dh);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

	public function dungMaGiamGia($code){
		$stmt = $this->connect->prepare("UPDATE ma_giam_gia SET so_luong = so_luong - 1 WHERE ma_code = ? AND so_luong > 0");
		if (!$stmt) return false;
		$stmt->bind_param("s", $code);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

	public function taoChiTietDonHang($orderId, $items) {
		$stmt = $this->connect->prepare("INSERT INTO chitietdonhang (id_dh, id_sp, so_luong, gia_ban) VALUES (?, ?, ?, ?)");
		if (!$stmt) return false;

		$updateStmt = $this->connect->prepare("UPDATE sanpham SET so_luong_ton = GREATEST(0, so_luong_ton - ?) WHERE id_sp = ?");

		foreach ($items as $item) {
			$stmt->bind_param("iiid", $orderId, $item['id_sp'], $item['so_luong'], $item['gia_ban']);
			$stmt->execute();

			if ($updateStmt) {
				$updateStmt->bind_param("ii", $item['so_luong'], $item['id_sp']);
				$updateStmt->execute();
			}
		}
		$stmt->close();
		if ($updateStmt) $updateStmt->close();
		return true;
	}

	public function layTatCaDonHang() {
		$res = $this->connect->query("SELECT * FROM donhang ORDER BY ngay_dat DESC");
		$data = [];
		while ($row = $res->fetch_assoc()) {
			$data[] = $row;
		}
		return $data;
	}

	public function layDonHangTheoId($id) {
		$stmt = $this->connect->prepare("SELECT * FROM donhang WHERE id_dh = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$order = $stmt->get_result()->fetch_assoc();
		$stmt->close();

		if ($order) {
			$stmt = $this->connect->prepare("SELECT ct.*, sp.ten_sp, sp.hinhanh_sp FROM chitietdonhang ct JOIN sanpham sp ON ct.id_sp = sp.id_sp WHERE ct.id_dh = ?");
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$res = $stmt->get_result();
			$items = [];
			while ($row = $res->fetch_assoc()) {
				$items[] = $row;
			}
			$order['items'] = $items;
			$stmt->close();
		}
		return $order;
	}

	public function capNhatTrangThaiDonHang($id, $status) {
		$stmt = $this->connect->prepare("UPDATE donhang SET trang_thai = ? WHERE id_dh = ?");
		$stmt->bind_param("ii", $status, $id);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

	public function huyDonHang($id, $reason) {
		$status = 0; // 0 là trạng thái hủy
		$stmt = $this->connect->prepare("UPDATE donhang SET trang_thai = ?, ly_do_huy = ? WHERE id_dh = ?");
		$stmt->bind_param("isi", $status, $reason, $id);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

	public function layDonHangNguoiDung($id_nd) {
		$stmt = $this->connect->prepare("SELECT * FROM donhang WHERE id_nd = ? ORDER BY ngay_dat DESC");
		$stmt->bind_param("i", $id_nd);
		$stmt->execute();
		$res = $stmt->get_result();
		$data = [];
		while ($row = $res->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}
	
	public function laysanpham_id($id_sua){
		if (!SecurityHelper::validateInteger($id_sua)) {
			return 0;
		}
		$stmt = $this->connect->prepare("SELECT * FROM sanpham WHERE id_sp = ?");
		if (!$stmt) {
			return 0;
		}
		$stmt->bind_param("i", $id_sua);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}
	
	public function suasanpham($id_sua, $ten, $link_ha, $gia, $ngay, $id_l, $mota, $so_luong_ton = 0){
		if (!SecurityHelper::validateInteger($id_sua)) {
			return false;
		}
		$stmt = $this->connect->prepare("UPDATE sanpham SET ten_sp = ?, hinhanh_sp = ?, gia_sp = ?, ngaynhap_sp = ?, id_loaisp = ?, mota_sp = ?, so_luong_ton = ? WHERE id_sp = ?");
		if (!$stmt) {
			return false;
		}
		$stmt->bind_param("ssdsssii", $ten, $link_ha, $gia, $ngay, $id_l, $mota, $so_luong_ton, $id_sua);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}
	
	public function xoasanpham($id_xoa){
		if (!SecurityHelper::validateInteger($id_xoa)) {
			return false;
		}
		$stmt = $this->connect->prepare("DELETE FROM sanpham WHERE id_sp = ?");
		if (!$stmt) {
			return false;
		}
		$stmt->bind_param("i", $id_xoa);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	public function kiemtraemail($email){
		if ($this->hasColumn('quanlynguoidung', 'email_nd')) {
			$stmt = $this->connect->prepare("SELECT * FROM quanlynguoidung WHERE email_nd = ?");
			if (!$stmt) return 0;
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$result = $stmt->get_result();
			if ($result->num_rows == 0) return 0;
			
			$data = [];
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			$stmt->close();
			return $data;
		}
		return 0;
	}


	public function dangky($tendk, $emaildk, $mkdk_hash) {
		$quyen_nd = 2;

		if ($this->hasColumn('quanlynguoidung', 'email_nd')) {
			$stmt = $this->connect->prepare("INSERT INTO quanlynguoidung (ten_nd, email_nd, matkhau_nd, quyen_nd) VALUES (?, ?, ?, ?)");
			if (!$stmt) {
				return false;
			}
			$stmt->bind_param("sssi", $tendk, $emaildk, $mkdk_hash, $quyen_nd);
			$result = $stmt->execute();
			$stmt->close();
			return $result;
		}

		$stmt = $this->connect->prepare("INSERT INTO quanlynguoidung (ten_nd, matkhau_nd, quyen_nd) VALUES (?, ?, ?)");
		if (!$stmt) {
			return false;
		}
		$stmt->bind_param("ssi", $tendk, $mkdk_hash, $quyen_nd);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	public function capnhatmatkhau($userId, $passwordHash) {
		if (!SecurityHelper::validateInteger($userId)) {
			return false;
		}
		$stmt = $this->connect->prepare("UPDATE quanlynguoidung SET matkhau_nd = ? WHERE id_nd = ?");
		if (!$stmt) {
			return false;
		}
		$stmt->bind_param("si", $passwordHash, $userId);
		$result = $stmt->execute();
		$stmt->close();
		return $result;
	}

	private function hasColumn($table, $column) {
		$key = $table . '.' . $column;
		if (isset($this->columnsCache[$key])) {
			return $this->columnsCache[$key];
		}

		$tableEscaped = $this->connect->real_escape_string($table);
		$columnEscaped = $this->connect->real_escape_string($column);
		$query = "SHOW COLUMNS FROM `{$tableEscaped}` LIKE '{$columnEscaped}'";
		$result = $this->connect->query($query);
		$exists = ($result && $result->num_rows > 0);
		$this->columnsCache[$key] = $exists;
		return $exists;
	}
	
	
	public function laysanphamnoibat(){
		$stmt = $this->connect->prepare("SELECT * FROM sanpham ORDER BY id_loaisp ASC LIMIT 4");
		if (!$stmt) {
			return 0;
		}
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}
	
	public function laysanphammoinhat(){
		$stmt = $this->connect->prepare("SELECT * FROM sanpham ORDER BY ngaynhap_sp DESC");
		if (!$stmt) {
			return 0;
		}
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}
	
	public function laysanphamtheoid_list($id_list) {
		if (empty($id_list)) {
			return [];
		}
		// Create placeholders (?,?,?)
		$placeholders = implode(',', array_fill(0, count($id_list), '?'));
		$types = str_repeat('i', count($id_list));
		
		$stmt = $this->connect->prepare("SELECT * FROM sanpham WHERE id_sp IN ($placeholders)");
		if (!$stmt) {
			return [];
		}
		
		$stmt->bind_param($types, ...$id_list);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}



	public function laysanphamtheoid($id_cart){
		if (empty($id_cart) || !is_array($id_cart)) {
			return 0;
		}
		
		// Validate tất cả IDs
		$ids = array_keys($id_cart);
		foreach ($ids as $id) {
			if (!SecurityHelper::validateInteger($id)) {
				return 0;
			}
		}
		
		$placeholders = implode(',', array_fill(0, count($ids), '?'));
		$types = str_repeat('i', count($ids));
		
		$stmt = $this->connect->prepare("SELECT * FROM sanpham WHERE id_sp IN ($placeholders)");
		if (!$stmt) {
			return 0;
		}
		$stmt->bind_param($types, ...$ids);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}

	/**
	 * Tìm kiếm sản phẩm
	 */
	public function timkiemsp($kw) {
		// Sanitize keyword
		$kw = SecurityHelper::sanitize($kw);
		if (empty($kw) || strlen($kw) > 100) {
			return 0;
		}
		
		// Escape LIKE wildcards
		$kw = SecurityHelper::escapeLike($kw);
		$search_term = '%' . $kw . '%';
		
		$stmt = $this->connect->prepare("SELECT * FROM sanpham WHERE ten_sp LIKE ? OR mota_sp LIKE ?");
		if (!$stmt) {
			return 0;
		}
		$stmt->bind_param("ss", $search_term, $search_term);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows == 0) {
			return 0;
		}
		
		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		$stmt->close();
		return $data;
	}

	/**
	 * Lấy sản phẩm đang có Flash Sale (thời gian kết thúc > hiện tại)
	 */
	public function laySanPhamFlashSale() {
		$stmt = $this->connect->prepare("SELECT * FROM sanpham WHERE flash_sale_end IS NOT NULL AND flash_sale_end > NOW() ORDER BY flash_sale_end ASC");
		if (!$stmt) return 0;
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows == 0) return 0;
		$data = [];
		while ($row = $result->fetch_assoc()) $data[] = $row;
		$stmt->close();
		return $data;
	}

	/**
	 * Cập nhật cấu hình Flash Sale cho sản phẩm
	 */
	public function capNhatFlashSale($id_sp, $price, $endDate) {
		$stmt = $this->connect->prepare("UPDATE sanpham SET flash_sale_price = ?, flash_sale_end = ? WHERE id_sp = ?");
		if (!$stmt) return false;
		$stmt->bind_param("dsi", $price, $endDate, $id_sp);
		$res = $stmt->execute();
		$stmt->close();
		return $res;
	}

    // ---------------------------------------------------------
    // SHIPPER & MAP TRACKING METHODS
    // ---------------------------------------------------------

    public function getShippers() {
        $stmt = $this->connect->prepare("SELECT id_nd, ten_nd FROM quanlynguoidung WHERE quyen_nd = 3");
        $shippers = [];
        if ($stmt) {
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $shippers[] = $row;
            }
            $stmt->close();
        }
        return $shippers;
    }

    public function updateShipperLocation($id_shipper, $lat, $lng) {
        $stmt = $this->connect->prepare("UPDATE quanlynguoidung SET lat = ?, lng = ? WHERE id_nd = ? AND quyen_nd = 2");
        if ($stmt) {
            $stmt->bind_param("ddi", $lat, $lng, $id_shipper);
            $stmt->execute();
            $stmt->close();
            return true;
        }
        return false;
    }

    public function getShipperLocation($id_shipper) {
        $stmt = $this->connect->prepare("SELECT lat, lng FROM quanlynguoidung WHERE id_nd = ? AND quyen_nd = 3 LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("i", $id_shipper);
            $stmt->execute();
            $res = $stmt->get_result();
            $stmt->close();
            return $res->fetch_assoc();
        }
        return null;
    }

    public function getShipperOrders($id_shipper) {
        $stmt = $this->connect->prepare("SELECT * FROM donhang WHERE id_shipper = ? ORDER BY ngay_dat DESC");
        $orders = [];
        if ($stmt) {
            $stmt->bind_param("i", $id_shipper);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $orders[] = $row;
            }
            $stmt->close();
        }
        return $orders;
    }

    public function getAvailableOrders() {
        $stmt = $this->connect->prepare("SELECT * FROM donhang WHERE trang_thai = 2 AND (id_shipper IS NULL OR id_shipper = 0) ORDER BY ngay_dat DESC");
        $orders = [];
        if ($stmt) {
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $orders[] = $row;
            }
            $stmt->close();
        }
        return $orders;
    }

    public function assignShipper($id_dh, $id_shipper) {
        $stmt = $this->connect->prepare("UPDATE donhang SET id_shipper = ? WHERE id_dh = ?");
        if ($stmt) {
            $stmt->bind_param("ii", $id_shipper, $id_dh);
            $stmt->execute();
            $stmt->close();
            return true;
        }
        return false;
    }

}
?>
