-- Script untuk membuat tabel promo_products dan promo_usage
-- Jalankan script ini di phpMyAdmin jika migration tidak berjalan

USE `topup_game`;

-- 1. Buat tabel promo_products
CREATE TABLE IF NOT EXISTS `promo_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promo_code_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promo_code_id_product_id` (`promo_code_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `promo_products_ibfk_1` FOREIGN KEY (`promo_code_id`) REFERENCES `promo_codes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `promo_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 2. Buat tabel promo_usage
CREATE TABLE IF NOT EXISTS `promo_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promo_code_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `used_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `promo_code_id` (`promo_code_id`),
  KEY `user_id` (`user_id`),
  KEY `promo_code_id_user_id` (`promo_code_id`,`user_id`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `promo_usage_ibfk_1` FOREIGN KEY (`promo_code_id`) REFERENCES `promo_codes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `promo_usage_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `promo_usage_ibfk_3` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- 3. Tambah kolom user_limit_per_account di tabel promo_codes (jika belum ada)
-- Cek dulu apakah kolom sudah ada, jika belum baru tambahkan
SET @dbname = DATABASE();
SET @tablename = 'promo_codes';
SET @columnname = 'user_limit_per_account';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1', -- Kolom sudah ada, tidak perlu ditambahkan
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN `', @columnname, '` int(11) DEFAULT NULL COMMENT ''Limit penggunaan per user/akun (NULL = unlimited)'' AFTER `usage_limit`')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 4. Tambah kolom valid_from jika belum ada (untuk konsistensi)
SET @columnname2 = 'valid_from';
SET @preparedStatement2 = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname2)
  ) > 0,
  'SELECT 1', -- Kolom sudah ada, tidak perlu ditambahkan
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN `', @columnname2, '` datetime DEFAULT NULL AFTER `used_count`')
));
PREPARE alterIfNotExists2 FROM @preparedStatement2;
EXECUTE alterIfNotExists2;
DEALLOCATE PREPARE alterIfNotExists2;
