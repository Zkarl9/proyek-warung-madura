-- =====================================================================
--  warung_madura.sql
--  Skema database MANUAL untuk aplikasi Warung Madura (Laravel).
--  Tidak memakai migration. Tidak memakai tabel session/cache/jobs
--  bawaan Laravel (driver session/cache/queue diarahkan ke file/sync).
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- users
-- ---------------------------------------------------------------------
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','owner') NOT NULL DEFAULT 'owner',
  `phone` VARCHAR(255) NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- password_reset_tokens
-- ---------------------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- products
-- ---------------------------------------------------------------------
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_produk` VARCHAR(255) NOT NULL,
  `yolo_label` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Label kelas model YOLOv8, misal: indomie_goreng',
  `status_ai` ENUM('belum_dilatih','proses_training','siap_deteksi') NOT NULL DEFAULT 'belum_dilatih',
  `diminta_deteksi_at` TIMESTAMP NULL DEFAULT NULL,
  `kategori` VARCHAR(255) NULL DEFAULT NULL,
  `stok_pajangan` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Stok aktual hasil deteksi kamera',
  `status_kamera` ENUM('ada','habis') NOT NULL DEFAULT 'ada',
  `stok_deteksi_terakhir` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Angka mentah terakhir dari kamera YOLO',
  `harga` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `satuan` VARCHAR(255) NOT NULL DEFAULT 'pcs',
  `foto` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_yolo_label_unique` (`yolo_label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- notification_settings
-- ---------------------------------------------------------------------
CREATE TABLE `notification_settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `wa_number` VARCHAR(255) NULL DEFAULT NULL,
  `fonnte_token` VARCHAR(255) NULL DEFAULT NULL,
  `telegram_chat_id` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_settings_user_id_foreign` (`user_id`),
  CONSTRAINT `notification_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- notifications (tabel bawaan Laravel Notification facade)
-- ---------------------------------------------------------------------
CREATE TABLE `notifications` (
  `id` CHAR(36) NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `notifiable_type` VARCHAR(255) NOT NULL,
  `notifiable_id` BIGINT UNSIGNED NOT NULL,
  `data` TEXT NOT NULL,
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`, `notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- camera_status_logs
-- ---------------------------------------------------------------------
CREATE TABLE `camera_status_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `status` ENUM('ada','habis') NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `camera_status_logs_product_id_foreign` (`product_id`),
  CONSTRAINT `camera_status_logs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- stock_movements
-- ---------------------------------------------------------------------
CREATE TABLE `stock_movements` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `arah` ENUM('masuk','keluar') NOT NULL,
  `jumlah` INT UNSIGNED NOT NULL,
  `harga_beli` INT UNSIGNED NULL DEFAULT NULL,
  `sumber` VARCHAR(255) NULL DEFAULT NULL,
  `alasan` ENUM('terjual','rusak','kadaluarsa','retur','lainnya') NULL DEFAULT NULL,
  `sumber_catatan` ENUM('manual','otomatis') NOT NULL DEFAULT 'manual',
  `keterangan` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
--  SEED DATA — password kedua akun: password123
-- =====================================================================
INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `created_at`, `updated_at`) VALUES
('Admin StockVision', 'admin@stockvision.test', '$2y$12$0rdGvRZVEpD29muJbT5dJeeb7BJqXFnWyL4Ma/tpaDcs8OiSJI4FK', 'admin', NULL, NOW(), NOW()),
('Pemilik Warung', 'owner@stockvision.test', '$2y$12$0rdGvRZVEpD29muJbT5dJeeb7BJqXFnWyL4Ma/tpaDcs8OiSJI4FK', 'owner', '6281234567890', NOW(), NOW());
