<?php
require "config/database.php";
$db = getConnection();

$sql = "
CREATE TABLE IF NOT EXISTS sanpham_yeuthich (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nd INT NOT NULL,
    id_sp INT NOT NULL,
    ngay_them TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_nd) REFERENCES quanlynguoidung(id_nd) ON DELETE CASCADE,
    FOREIGN KEY (id_sp) REFERENCES sanpham(id_sp) ON DELETE CASCADE,
    UNIQUE KEY unique_favorite (id_nd, id_sp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

if ($db->query($sql) === TRUE) {
    echo "Table sanpham_yeuthich created successfully.";
} else {
    echo "Error creating table: " . $db->error;
}
