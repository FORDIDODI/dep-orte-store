-- Script sederhana untuk membuat tabel promo_products dan promo_usage
-- Jalankan script ini di phpMyAdmin jika migration tidak berjalan
-- CATATAN: Jika kolom sudah ada, akan muncul error, tapi bisa diabaikan

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

-- 3. Tambah kolom user_limit_per_account di tabel promo_codes
-- Jika kolom sudah ada, akan muncul error #1060, tapi bisa diabaikan
ALTER TABLE `promo_codes` 
ADD COLUMN `user_limit_per_account` int(11) DEFAULT NULL COMMENT 'Limit penggunaan per user/akun (NULL = unlimited)' AFTER `usage_limit`;

-- 4. Tambah kolom valid_from jika belum ada
-- Jika kolom sudah ada, akan muncul error #1060, tapi bisa diabaikan
ALTER TABLE `promo_codes` 
ADD COLUMN `valid_from` datetime DEFAULT NULL AFTER `used_count`;
