-- =====================================================
-- Script untuk menambahkan kolom-kolom yang diperlukan
-- untuk fitur upload bukti pembayaran
-- =====================================================
-- Jalankan script ini di database topup_game
-- =====================================================

USE topup_game;

-- Tambahkan kolom payment_proof jika belum ada
SET @column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'topup_game'
    AND TABLE_NAME = 'transactions'
    AND COLUMN_NAME = 'payment_proof'
);

SET @query = IF(@column_exists = 0,
    'ALTER TABLE transactions ADD COLUMN payment_proof VARCHAR(255) NULL AFTER status',
    'SELECT "Column payment_proof already exists" AS result'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Tambahkan kolom admin_notes jika belum ada
SET @column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'topup_game'
    AND TABLE_NAME = 'transactions'
    AND COLUMN_NAME = 'admin_notes'
);

SET @query = IF(@column_exists = 0,
    'ALTER TABLE transactions ADD COLUMN admin_notes TEXT NULL AFTER payment_proof',
    'SELECT "Column admin_notes already exists" AS result'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Tambahkan kolom paid_at jika belum ada
SET @column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'topup_game'
    AND TABLE_NAME = 'transactions'
    AND COLUMN_NAME = 'paid_at'
);

SET @query = IF(@column_exists = 0,
    'ALTER TABLE transactions ADD COLUMN paid_at DATETIME NULL AFTER admin_notes',
    'SELECT "Column paid_at already exists" AS result'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Tambahkan kolom completed_at jika belum ada
SET @column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'topup_game'
    AND TABLE_NAME = 'transactions'
    AND COLUMN_NAME = 'completed_at'
);

SET @query = IF(@column_exists = 0,
    'ALTER TABLE transactions ADD COLUMN completed_at DATETIME NULL AFTER paid_at',
    'SELECT "Column completed_at already exists" AS result'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Tampilkan struktur tabel setelah perubahan
DESCRIBE transactions;

SELECT 'Migration completed successfully!' AS status;

