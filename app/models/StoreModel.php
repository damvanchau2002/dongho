<?php

class StoreModel
{
    private $model;

    public function __construct()
    {
        $this->model = new BaseModel();
    }

    public function categories()
    {
        return $this->model->layloaisanpham();
    }

    public function products()
    {
        return $this->model->laysanpham();
    }

    public function productsPaginated($limit, $offset)
    {
        return $this->model->laysanphamPhanTrang($limit, $offset);
    }

    public function countTotalProducts()
    {
        return $this->model->demTongSanPham();
    }

    public function productsByCategory($id)
    {
        return $this->model->laysanphamtheoidloai($id);
    }

    public function productsByCategoryPaginated($id, $limit, $offset)
    {
        return $this->model->laysanphamtheoidloaiPhanTrang($id, $limit, $offset);
    }

    public function countTotalProductsByCategory($id)
    {
        return $this->model->demTongSanPhamTheoLoai($id);
    }

    public function productById($id)
    {
        $rows = $this->model->laysanpham_id($id);
        if ($rows === 0 || empty($rows)) {
            return null;
        }
        return $rows[0];
    }

    public function randomProducts($limit = 8)
    {
        return $this->model->laysanphamngaunhien($limit);
    }

    public function featuredProducts()
    {
        return $this->model->laysanphamnoibat();
    }

    public function newestProducts()
    {
        return $this->model->laysanphammoinhat();
    }

    public function productsByIdList($ids)
    {
        return $this->model->laysanphamtheoid_list($ids);
    }

    public function searchProducts($keyword)
    {
        return $this->model->timkiemsp($keyword);
    }

    public function deleteProduct($id)
    {
        return $this->model->xoasanpham($id);
    }

    public function loginUser($username)
    {
        return $this->model->kiemtradangnhap($username);
    }

    public function getUserByEmail($email)
    {
        return $this->model->kiemtraemail($email);
    }

    public function registerUser($username, $email, $passwordHash)
    {
        return $this->model->dangky($username, $email, $passwordHash);
    }

    public function updateUserPassword($userId, $passwordHash)
    {
        return $this->model->capnhatmatkhau($userId, $passwordHash);
    }

    public function createProduct($ten, $link_ha, $gia, $ngay, $id_l, $mota, $so_luong_ton = 0)
    {
        return $this->model->themsanpham($ten, $link_ha, $gia, $ngay, $id_l, $mota, $so_luong_ton);
    }

    public function updateProduct($id, $ten, $link_ha, $gia, $ngay, $id_l, $mota, $so_luong_ton = 0)
    {
        return $this->model->suasanpham($id, $ten, $link_ha, $gia, $ngay, $id_l, $mota, $so_luong_ton);
    }

    public function createCategory($name)
    {
        return $this->model->themloaisp($name);
    }

    public function updateInventory($id, $qty)
    {
        return $this->model->capNhatTonKho($id, $qty);
    }

    // --- SHIPPER METHODS ---
    public function getShippers() {
        return $this->model->getShippers();
    }

    public function updateShipperLocation($id_shipper, $lat, $lng) {
        return $this->model->updateShipperLocation($id_shipper, $lat, $lng);
    }

    public function getShipperLocation($id_shipper) {
        return $this->model->getShipperLocation($id_shipper);
    }

    public function getShipperOrders($id_shipper) {
        return $this->model->getShipperOrders($id_shipper);
    }

    public function getAvailableOrders() {
        return $this->model->getAvailableOrders();
    }

    public function assignShipper($id_dh, $id_shipper) {
        return $this->model->assignShipper($id_dh, $id_shipper);
    }

    public function updateCategory($id, $name)
    {
        return $this->model->sualoaisp($id, $name);
    }

    public function getAllUsers()
    {
        return $this->model->layTatCaNguoiDung();
    }

    public function getUserById($id)
    {
        return $this->model->layNguoiDungTheoId($id);
    }

    public function updateUserRole($id, $role)
    {
        return $this->model->capNhatQuyenNguoiDung($id, $role);
    }

    public function deleteUser($id)
    {
        return $this->model->xoaNguoiDung($id);
    }

    public function updateUserProfile($id, $ten, $email, $sdt, $diachi)
    {
        return $this->model->capNhatThongTinNguoiDung($id, $ten, $email, $sdt, $diachi);
    }

    public function getDiscountCode($code)
    {
        return $this->model->layMaGiamGia($code);
    }

    public function getAllDiscountCodes()
    {
        return $this->model->layTatCaMaGiamGia();
    }

    public function createDiscountCode($code, $loai, $gia_tri, $so_luong, $ngay_het_han)
    {
        return $this->model->themMaGiamGia($code, $loai, $gia_tri, $so_luong, $ngay_het_han);
    }

    public function deleteDiscountCode($id)
    {
        return $this->model->xoaMaGiamGia($id);
    }

    public function useDiscountCode($code)
    {
        return $this->model->dungMaGiamGia($code);
    }

    public function deleteCategory($id)
    {
        return $this->model->xoaloaisp($id);
    }

    public function categoryById($id)
    {
        return $this->model->layloaisptheoid($id);
    }

    public function getStats()
    {
        return $this->model->layThongKe();
    }

    public function getChartData()
    {
        return $this->model->layDuLieuBieuDo();
    }

    public function storeMomoInfo($customer_id, $momo_status, $link_data)
    {
        return $this->model->storeMomoInfo($customer_id, $momo_status, $link_data);
    }

    public function updatePaymentMethod($ma_dh, $pttt)
    {
        return $this->model->capNhatPhuongThucThanhToan($ma_dh, $pttt);
    }

    public function createOrder($data, $items)
    {
        $orderId = $this->model->taoDonHang($data);
        if ($orderId) {
            $this->model->taoChiTietDonHang($orderId, $items);
            
            // Nếu có mã giảm giá, giảm số lượng mã đã dùng
            if (isset($_SESSION['discount'])) {
                $this->useDiscountCode($_SESSION['discount']['ma_code']);
            }
        }
        return $orderId;
    }

    public function allOrders()
    {
        return $this->model->layTatCaDonHang();
    }

    public function orderById($id)
    {
        return $this->model->layDonHangTheoId($id);
    }

    public function updateOrderStatus($id, $status)
    {
        return $this->model->capNhatTrangThaiDonHang($id, $status);
    }

    public function cancelOrder($id, $reason)
    {
        return $this->model->huyDonHang($id, $reason);
    }

    public function userOrders($userId)
    {
        return $this->model->layDonHangNguoiDung($userId);
    }

    public function flashSaleProducts()
    {
        return $this->model->laySanPhamFlashSale();
    }

    public function updateFlashSale($id, $price, $endDate)
    {
        // Nếu không có price hoặc endDate, coi như xoá flash sale
        if (empty($price) || empty($endDate)) {
            $price = 0;
            $endDate = null;
        }
        return $this->model->capNhatFlashSale($id, $price, $endDate);
    }

    public function getAllProvinces() {
        return $this->model->getAllProvinces();
    }

    public function getWardsByProvince($provinceCode) {
        return $this->model->getWardsByProvince($provinceCode);
    }

    public function getProvinceByCode($code) {
        return $this->model->getProvinceByCode($code);
    }

    public function getWardByCode($code) {
        return $this->model->getWardByCode($code);
    }
}
