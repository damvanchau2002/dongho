<?php
require 'utils/connect_db.php';
try {
    $pdo = connectdb();
    $sql = "CREATE TABLE IF NOT EXISTS banners (
        id INT AUTO_INCREMENT PRIMARY KEY,
        hinh_anh VARCHAR(255) NOT NULL,
        link VARCHAR(255) DEFAULT '#',
        vi_tri VARCHAR(50) DEFAULT 'main',
        trang_thai TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
    $pdo->exec($sql);
    
    // check if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM banners");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO banners (hinh_anh, link, vi_tri) VALUES 
            ('https://cf.shopee.vn/file/vn-50009109-1a3ebc4e7ab2e2c0b7937f374737de53_xxhdpi', '#', 'main'),
            ('https://cf.shopee.vn/file/vn-50009109-13edcd3b516bcaefa98f3b14dcd541af_xhdpi', '#', 'side1'),
            ('https://cf.shopee.vn/file/vn-50009109-1e39a3f2bb434bd7a50352ef2dc74f4b_xhdpi', '#', 'side2')
        ");
    }
    echo "Banners table created successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
