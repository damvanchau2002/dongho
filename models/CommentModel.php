<?php
require_once 'BaseModel.php';

class CommentModel extends BaseModel
{
    private function getDb() {
        $ref = new ReflectionProperty('BaseModel', 'connect');
        $ref->setAccessible(true);
        return $ref->getValue($this);
    }

    private function selectall($sql, $params = [], $types = "") {
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

    private function selectone($sql, $params = [], $types = "") {
        $res = $this->selectall($sql, $params, $types);
        return $res ? $res[0] : null;
    }

    private function execute_query($sql, $params = [], $types = "") {
        $db = $this->getDb();
        $stmt = $db->prepare($sql);
        if (!$stmt) return false;
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        return $stmt->execute();
    }

    // Get all comments for a product
    public function getCommentsByProductId($id_sp) {
        $sql = "SELECT * FROM danhgia_binhluan WHERE id_sp = ? AND trang_thai = 1 ORDER BY created_at DESC";
        return $this->selectall($sql, [$id_sp], "i");
    }

    // Get rating statistics for a product
    public function getRatingStats($id_sp) {
        $sql = "SELECT 
                    sao_danh_gia,
                    COUNT(*) as count
                FROM danhgia_binhluan
                WHERE id_sp = ? AND trang_thai = 1
                GROUP BY sao_danh_gia
                ORDER BY sao_danh_gia DESC";
        
        $result = $this->selectall($sql, [$id_sp], "i");
        
        $stats = [
            'total' => 0,
            'average' => 0,
            'ratings' => []
        ];
        
        // Initialize rating counts
        for ($i = 1; $i <= 5; $i++) {
            $stats['ratings'][$i] = 0;
        }
        
        // Fill in the counts
        foreach ($result as $row) {
            $stats['ratings'][$row['sao_danh_gia']] = $row['count'];
            $stats['total'] += $row['count'];
        }
        
        // Calculate average
        if ($stats['total'] > 0) {
            $sumRating = 0;
            foreach ($stats['ratings'] as $rating => $count) {
                $sumRating += $rating * $count;
            }
            $stats['average'] = round($sumRating / $stats['total'], 1);
        }
        
        return $stats;
    }

    // Add new comment
    public function addComment($id_sp, $ten_nguoidung, $email_nguoidung, $sao_danh_gia, $tieu_de, $noi_dung) {
        $sql = "INSERT INTO danhgia_binhluan (id_sp, ten_nguoidung, email_nguoidung, sao_danh_gia, tieu_de, noi_dung, trang_thai) 
                VALUES (?, ?, ?, ?, ?, ?, 1)";
        
        return $this->execute_query($sql, 
            [$id_sp, $ten_nguoidung, $email_nguoidung, $sao_danh_gia, $tieu_de, $noi_dung], 
            "ississ"
        );
    }

    // Get comment by ID
    public function getCommentById($id) {
        $sql = "SELECT * FROM danhgia_binhluan WHERE id = ?";
        return $this->selectone($sql, [$id], "i");
    }

    // Update comment status (approve/reject)
    public function updateCommentStatus($id, $trang_thai) {
        $sql = "UPDATE danhgia_binhluan SET trang_thai = ? WHERE id = ?";
        return $this->execute_query($sql, [$trang_thai, $id], "ii");
    }

    // Delete comment
    public function deleteComment($id) {
        $sql = "DELETE FROM danhgia_binhluan WHERE id = ?";
        return $this->execute_query($sql, [$id], "i");
    }

    // Get all comments for admin
    public function getAllComments($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM danhgia_binhluan ORDER BY created_at DESC LIMIT ? OFFSET ?";
        return $this->selectall($sql, [$limit, $offset], "ii");
    }

    // Count all comments
    public function countAllComments() {
        $sql = "SELECT COUNT(*) as total FROM danhgia_binhluan";
        $result = $this->selectone($sql);
        return $result ? $result['total'] : 0;
    }
}
?>
