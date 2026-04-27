-- SQL Scripts to prepare database for security upgrade
-- Run these BEFORE running migrate_passwords.php

-- ============================================================================
-- 1. Ensure password fields are large enough for bcrypt hashes (61 chars)
-- ============================================================================

ALTER TABLE `quanlynguoidung` MODIFY COLUMN `matkhau_nd` VARCHAR(255) NOT NULL;
ALTER TABLE `customer` MODIFY COLUMN `pass` VARCHAR(255) NOT NULL;

-- ============================================================================
-- 2. Optional: Backup current passwords (if you want to keep old values)
-- ============================================================================

-- Create backup table for original passwords (do NOT use in production)
-- CREATE TABLE `quanlynguoidung_password_backup` (
--     `id_nd` INT PRIMARY KEY,
--     `original_password` VARCHAR(255),
--     `backed_up_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (`id_nd`) REFERENCES `quanlynguoidung`(`id_nd`)
-- );

-- Backup passwords from quanlynguoidung
-- INSERT INTO `quanlynguoidung_password_backup` (id_nd, original_password)
-- SELECT id_nd, matkhau_nd FROM quanlynguoidung;

-- ============================================================================
-- 3. Verify current password format (should show plaintext, no hashes)
-- ============================================================================

-- Check quanlynguoidung table
SELECT `ten_nd`, `matkhau_nd`, LENGTH(`matkhau_nd`) as pwd_length 
FROM `quanlynguoidung` 
LIMIT 5;

-- Check customer table
SELECT `name`, `pass`, LENGTH(`pass`) as pwd_length 
FROM `customer` 
LIMIT 5;

-- Note: Password length should be < 60 chars (mostly 6-20 chars for plaintext)
-- After migration, all will be 60 chars (bcrypt hash format: $2y$cost$...)

-- ============================================================================
-- 4. Verify migration status (before running migrate_passwords.php)
-- ============================================================================

-- Count plaintext vs hashed passwords (BEFORE)
SELECT 
    'quanlynguoidung' as table_name,
    COUNT(*) as total_records,
    SUM(CASE WHEN matkhau_nd LIKE '$2%' THEN 1 ELSE 0 END) as hashed_count,
    SUM(CASE WHEN matkhau_nd NOT LIKE '$2%' THEN 1 ELSE 0 END) as plaintext_count
FROM `quanlynguoidung`
UNION ALL
SELECT 
    'customer' as table_name,
    COUNT(*) as total_records,
    SUM(CASE WHEN pass LIKE '$2%' THEN 1 ELSE 0 END) as hashed_count,
    SUM(CASE WHEN pass NOT LIKE '$2%' THEN 1 ELSE 0 END) as plaintext_count
FROM `customer`;

-- ============================================================================
-- 5. After running migrate_passwords.php, verify migration success
-- ============================================================================

-- Check if all passwords are hashed (AFTER migration)
SELECT 
    't' as table_name,
    COUNT(*) as total_records,
    SUM(CASE WHEN matkhau_nd LIKE '$2y$%' OR matkhau_nd LIKE '$2a$%' THEN 1 ELSE 0 END) as hashed_count,
    SUM(CASE WHEN matkhau_nd NOT LIKE '$2y$%' AND matkhau_nd NOT LIKE '$2a$%' THEN 1 ELSE 0 END) as plaintext_count
FROM `quanlynguoidung`;

-- Check first migrated password
SELECT `ten_nd`, `matkhau_nd`, LENGTH(`matkhau_nd`) as hash_length 
FROM `quanlynguoidung` 
WHERE `matkhau_nd` LIKE '$2y$%' 
LIMIT 1;

-- ============================================================================
-- 6. Create index for faster lookups (OPTIONAL but recommended)
-- ============================================================================

-- Add index on email for customer table
ALTER TABLE `customer` ADD INDEX `idx_email` (`email`);

-- Add index on ten_nd for quanlynguoidung table
ALTER TABLE `quanlynguoidung` ADD INDEX `idx_ten_nd` (`ten_nd`);

-- ============================================================================
-- 7. Add created_at and updated_at timestamps (OPTIONAL)
-- ============================================================================

-- For customer table (if not already exists)
-- ALTER TABLE `customer` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
-- ALTER TABLE `customer` ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- For quanlynguoidung table (if not already exists)  
-- ALTER TABLE `quanlynguoidung` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
-- ALTER TABLE `quanlynguoidung` ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- ============================================================================
-- 8. Rollback procedure (if something goes wrong)
-- ============================================================================

-- If you have backup table, restore from backup:
-- UPDATE `quanlynguoidung` q
-- INNER JOIN `quanlynguoidung_password_backup` b ON q.id_nd = b.id_nd
-- SET q.matkhau_nd = b.original_password;

-- Or restore from database backup (recommended approach):
-- 1. Restore from full database backup
-- 2. Run migration again

-- ============================================================================
-- CHECKLIST SEBELUM MENJALANKAN MIGRATION
-- ============================================================================

-- ✓ Backup database penuh
-- ✓ Verify password fields are VARCHAR(255)
-- ✓ Check current passwords dengan query di atas
-- ✓ Ensure migrate_passwords.php tersedia
-- ✓ Check logs directory exists and is writable
-- ✓ Have SecurityHelper.php file ready

-- CHECKLIST SETELAH MENJALANKAN MIGRATION

-- ✓ Check logs di logs/app-YYYY-MM-DD.log
-- ✓ Verify tất cả passwords sudah di-hash
-- ✓ Test login dengan account lama
-- ✓ Test signup dengan account baru
-- ✓ Test password verification berfungsi
-- ✓ Delete migrate_passwords.php
-- ✓ Delete MIGRATION_COMPLETED marker
