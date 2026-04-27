<?php
/**
 * Migration Script - Chuyển đổi passwords từ plaintext sang hash
 * RUN ONCE để convert tất cả passwords
 * 
 * Cách dùng: 
 * 1. Đặt file này trong thư mục gốc
 * 2. Truy cập: http://localhost/Website/migrate_passwords.php
 * 3. Xóa file sau khi hoàn thành
 */

// Check if already migrated
if (!file_exists('MIGRATION_COMPLETED')) {
    require_once 'config/database.php';
    require_once 'helpers/SecurityHelper.php';
    require_once 'helpers/Logger.php';
    
    echo "Starting password migration...\n\n";
    
    $conn = getConnection();
    
    try {
        // Lấy tất cả users có password chưa hashed
        $stmt = $conn->prepare("SELECT * FROM quanlynguoidung WHERE matkhau_nd NOT LIKE '$2y$%' AND matkhau_nd NOT LIKE '$2a$%'");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $oldPassword = $row['matkhau_nd'];
            $hashedPassword = SecurityHelper::hashPassword($oldPassword);
            
            // Update password
            $updateStmt = $conn->prepare("UPDATE quanlynguoidung SET matkhau_nd = ? WHERE id_nd = ?");
            $updateStmt->bind_param("si", $hashedPassword, $row['id_nd']);
            $updateStmt->execute();
            $updateStmt->close();
            
            $count++;
            echo "✓ Migrated user: {$row['ten_nd']}\n";
        }
        
        // Cũng migrate customer table nếu có
        $stmt = $conn->prepare("SELECT * FROM customer WHERE pass NOT LIKE '$2y$%' AND pass NOT LIKE '$2a$%'");
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $oldPassword = $row['pass'];
                $hashedPassword = SecurityHelper::hashPassword($oldPassword);
                
                $updateStmt = $conn->prepare("UPDATE customer SET pass = ? WHERE customer_id = ?");
                $updateStmt->bind_param("si", $hashedPassword, $row['customer_id']);
                $updateStmt->execute();
                $updateStmt->close();
                
                $count++;
                echo "✓ Migrated customer: {$row['name']}\n";
            }
            $stmt->close();
        }
        
        echo "\n✓ Migration completed! Migrated {$count} records.\n";
        Logger::info("Password migration completed. {$count} records updated.");
        
        // Tạo marker file
        touch('MIGRATION_COMPLETED');
        echo "\nMigration marker created. You can now delete this file.\n";
        
    } catch (Exception $e) {
        echo "Error during migration: " . $e->getMessage() . "\n";
        Logger::error("Password migration failed", ['error' => $e->getMessage()]);
    }
    
    $conn->close();
} else {
    echo "Migration already completed!\n";
}
?>
