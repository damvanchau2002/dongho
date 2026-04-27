<?php
require 'config/database.php';
$db = getConnection();
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
echo "DB Update Success";
