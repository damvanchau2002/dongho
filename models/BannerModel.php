<?php
require_once 'BaseModel.php';

class BannerModel extends BaseModel
{
    private function getDb() {
        $ref = new ReflectionProperty('BaseModel', 'connect');
        $ref->setAccessible(true);
        return $ref->getValue($this);
    }

    private function selectall($sql, $params = [], $types = "")
    {
        $db = $this->getDb();
        $stmt = $db->prepare($sql);
        if (!$stmt) return [];
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    private function selectone($sql, $params = [], $types = "")
    {
        $res = $this->selectall($sql, $params, $types);
        return $res ? $res[0] : null;
    }

    private function execute_query($sql, $params = [], $types = "")
    {
        $db = $this->getDb();
        $stmt = $db->prepare($sql);
        if (!$stmt) return false;
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        return $stmt->execute();
    }

    public function getAllBanners()
    {
        $sql = "SELECT * FROM banners ORDER BY id DESC";
        return $this->selectall($sql);
    }

    public function getActiveBanners()
    {
        $sql = "SELECT * FROM banners WHERE trang_thai = 1 ORDER BY id ASC";
        return $this->selectall($sql);
    }

    public function getBannerById($id)
    {
        $sql = "SELECT * FROM banners WHERE id = ?";
        return $this->selectone($sql, [$id], "i");
    }

    public function insertBanner($hinh_anh, $link, $vi_tri, $trang_thai)
    {
        $sql = "INSERT INTO banners (hinh_anh, link, vi_tri, trang_thai) VALUES (?, ?, ?, ?)";
        return $this->execute_query($sql, [$hinh_anh, $link, $vi_tri, $trang_thai], "sssi");
    }

    public function updateBanner($id, $hinh_anh, $link, $vi_tri, $trang_thai)
    {
        if ($hinh_anh) {
            $sql = "UPDATE banners SET hinh_anh = ?, link = ?, vi_tri = ?, trang_thai = ? WHERE id = ?";
            return $this->execute_query($sql, [$hinh_anh, $link, $vi_tri, $trang_thai, $id], "ssssi");
        } else {
            $sql = "UPDATE banners SET link = ?, vi_tri = ?, trang_thai = ? WHERE id = ?";
            return $this->execute_query($sql, [$link, $vi_tri, $trang_thai, $id], "ssii");
        }
    }

    public function updateStatus($id, $trang_thai)
    {
        $sql = "UPDATE banners SET trang_thai = ? WHERE id = ?";
        return $this->execute_query($sql, [$trang_thai, $id], "ii");
    }

    public function deleteBanner($id)
    {
        $sql = "DELETE FROM banners WHERE id = ?";
        return $this->execute_query($sql, [$id], "i");
    }
}
