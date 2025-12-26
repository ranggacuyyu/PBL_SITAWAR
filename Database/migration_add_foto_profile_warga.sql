-- ============================================
-- Migration: Add foto_profile column to user_warga table
-- Date: 2025-12-25
-- Description: Menambahkan kolom foto_profile untuk menyimpan foto profil warga
-- ============================================

USE pbl1;

-- Tambahkan kolom foto_profile setelah kolom tanggal_input
ALTER TABLE user_warga 
ADD COLUMN foto_profile VARCHAR(255) DEFAULT NULL AFTER tanggal_input;

-- Verifikasi perubahan
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'pbl1' 
AND TABLE_NAME = 'user_warga'
ORDER BY ORDINAL_POSITION;
