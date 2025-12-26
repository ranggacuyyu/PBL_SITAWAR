-- ============================================
-- Migration: Add foto_profile column to user_rt table
-- Date: 2025-12-25
-- Description: Menambahkan kolom foto_profile untuk menyimpan foto profil RT
-- ============================================

USE pbl1;

-- Tambahkan kolom foto_profile setelah kolom password
ALTER TABLE user_rt 
ADD COLUMN foto_profile VARCHAR(255) DEFAULT NULL AFTER password;

-- Verifikasi perubahan
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'pbl1' 
AND TABLE_NAME = 'user_rt'
ORDER BY ORDINAL_POSITION;
