-- ========================================================
-- DIGIDES v2 - Sistem Informasi Desa Sukamaju
-- Database Export ready for phpMyAdmin / MySQL / MariaDB
-- Generated: 2026-09-02 09:54:26
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+07:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digides`
--
CREATE DATABASE IF NOT EXISTS `digides` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `digides`;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_27_105239_create_permission_tables', 1),
(5, '2026_08_27_105246_create_activity_log_table', 1),
(6, '2026_08_27_105247_add_event_column_to_activity_log_table', 1),
(7, '2026_08_27_105248_add_batch_uuid_column_to_activity_log_table', 1),
(8, '2026_08_27_110000_add_custom_fields_to_users_table', 1),
(9, '2026_08_27_110001_create_desa_profiles_table', 1),
(10, '2026_08_27_110002_create_backup_records_table', 1),
(11, '2026_08_27_120000_create_penduduks_table', 1),
(12, '2026_08_27_120001_create_penduduk_documents_table', 1),
(13, '2026_08_27_120002_create_penduduk_mutasis_table', 1),
(14, '2026_08_27_130000_create_surat_templates_table', 1),
(15, '2026_08_27_130001_create_surat_arsips_table', 1),
(16, '2026_08_27_130002_create_buku_ekspedisis_table', 1),
(17, '2026_08_27_130003_create_buku_agendas_table', 1),
(18, '2026_08_28_102321_create_buku_peraturan_desa_table', 1),
(19, '2026_08_28_102322_create_buku_keputusan_kades_table', 1),
(20, '2026_08_28_102323_create_buku_inventaris_aset_table', 1),
(21, '2026_08_28_102324_create_buku_tanah_desa_table', 1),
(22, '2026_08_28_102325_create_buku_anggaran_desa_table', 1),
(23, '2026_08_28_102326_create_buku_lembaran_desa_table', 1),
(24, '2026_08_28_130500_create_institutions_table', 1),
(25, '2026_08_28_130600_create_aparatur_table', 1),
(26, '2026_08_28_130700_create_absensi_table', 1),
(27, '2026_08_29_020000_create_keuangan_apbdes_table', 1),
(28, '2026_08_29_020001_create_keuangan_kas_transaksi_table', 1),
(29, '2026_08_29_020002_create_pembangunan_proyek_table', 1),
(30, '2026_08_29_020003_create_pembangunan_kader_table', 1),
(31, '2026_08_29_020004_add_kategori_and_jenis_pembantu_to_keuangan_kas_transaksi_table', 1),
(32, '2026_08_29_030000_create_keuangan_rabs_and_items_table', 1),
(33, '2026_08_30_040000_create_pembangunan_inventaris_hasil_table', 1),
(34, '2026_08_30_050000_create_institution_records_tables', 1),
(35, '2026_08_31_170000_create_schedules_table', 2),
(36, '2026_09_02_120000_add_foto_desa_path_to_desa_profiles_table', 3);

-- --------------------------------------------------------

--
-- Struktur tabel untuk `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'desa.view', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(2, 'desa.update', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(3, 'user.view', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(4, 'user.create', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(5, 'user.edit', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(6, 'user.delete', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(7, 'kependudukan.view', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(8, 'kependudukan.create', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(9, 'kependudukan.edit', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(10, 'kependudukan.delete', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(11, 'kependudukan.verify', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(12, 'persuratan.view', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(13, 'persuratan.create', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(14, 'persuratan.print', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(15, 'kelembagaan.view', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(16, 'kelembagaan.manage_master', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(17, 'kelembagaan.edit_content', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(18, 'absensi.scan', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(19, 'absensi.override', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(20, 'absensi.rekap', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(21, 'administrasi.view', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(22, 'administrasi.manage', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(23, 'keuangan.view', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(24, 'keuangan.manage', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(25, 'pembangunan.view', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(26, 'pembangunan.manage', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(27, 'audit.view', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(28, 'backup.manage', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin Desa', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(2, 'Staff Desa', 'web', '2026-08-30 14:04:17', '2026-08-30 14:04:17');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` INT NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` INT NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` INT NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Struktur tabel untuk `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(11, 1),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(16, 1),
(17, 1),
(17, 2),
(18, 1),
(18, 2),
(19, 1),
(20, 1),
(21, 1),
(21, 2),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1);

-- --------------------------------------------------------

--
-- Struktur tabel untuk `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` DATETIME NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `phone` VARCHAR(255) NULL DEFAULT NULL,
  `avatar_path` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `avatar_path`, `is_active`, `last_login_at`) VALUES
(1, 'Administrator Desa Sukamaju', 'admin@desa.id', '2026-09-02 05:13:26', '$2y$12$Wer4QhMSSVqEKXGSjsWOUuQef7McwvQa8SH0lgY7z2lAtPCTQQDHC', NULL, '2026-08-30 14:04:17', '2026-09-02 09:33:06', '081234567890', NULL, 1, '2026-09-02 09:33:06'),
(2, 'Jack Grealish', 'staff@desa.id', '2026-09-02 05:13:26', '$2y$12$N8ey6o7TzO4DZQRn4X4F7.jVLJLeCnj.zWbE72lAkbJct/YOWrJtO', NULL, '2026-08-30 14:04:17', '2026-09-02 05:13:26', '081298765432', NULL, 1, '2026-09-02 05:07:02');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` INT NULL DEFAULT NULL,
  `ip_address` VARCHAR(255) NULL DEFAULT NULL,
  `user_agent` TEXT NULL DEFAULT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('kY419ZmYZp8vxuAa9Xchy9or1L4LhdXmyQi4X9mf', 1, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiI3U3g2OGtEajNrMzRveDRWV256RWV5RzM1M1ltMEtBU3VQeVM2S29jIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2RpZ2lkZXMudGVzdFwva2V1YW5nYW4iLCJyb3V0ZSI6ImtldWFuZ2FuLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1788171520),
('ONulL0z6g8grCiT1DrOYaRT5xSym7HcbaQJttCEQ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJsdEE1bFB0R1JVN1VXZTJ0amdiWk94bXZIYVFqSnIwRzhocHhZT3Z6IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvZGlnaWRlcy50ZXN0XC9hYnNlbnNpIiwicm91dGUiOiJhYnNlbnNpLmluZGV4In0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1788328990),
('qS0nF2Hz8TFTbos9vzJK9NMSflIQJMzcXdMxknD4', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJMaFBPUUVYUlFaSUNHY0UyRHYzRHd0ZDhvMjRrcHFZMkJSTWYzSndiIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOltdLCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvZGlnaWRlcy50ZXN0XC9hZG1pbmlzdHJhc2lcL3BlcmF0dXJhbi1kZXNhIiwicm91dGUiOiJhZG1pbmlzdHJhc2kucGVyYXR1cmFuLWRlc2EuaW5kZXgifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1788342645);

-- --------------------------------------------------------

--
-- Struktur tabel untuk `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('digides-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:28:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"desa.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"desa.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"user.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"user.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"user.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:11:\"user.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:17:\"kependudukan.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:19:\"kependudukan.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:17:\"kependudukan.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:19:\"kependudukan.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:19:\"kependudukan.verify\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:15:\"persuratan.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:17:\"persuratan.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:16:\"persuratan.print\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:16:\"kelembagaan.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:25:\"kelembagaan.manage_master\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:24:\"kelembagaan.edit_content\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:12:\"absensi.scan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:16:\"absensi.override\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:13:\"absensi.rekap\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:17:\"administrasi.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:19:\"administrasi.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:13:\"keuangan.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:15:\"keuangan.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:16:\"pembangunan.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:18:\"pembangunan.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:10:\"audit.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:13:\"backup.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"Admin Desa\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"Staff Desa\";s:1:\"c\";s:3:\"web\";}}}', 1788413011);

-- --------------------------------------------------------

--
-- Struktur tabel untuk `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` TEXT NOT NULL,
  `attempts` INT NOT NULL,
  `reserved_at` INT NULL DEFAULT NULL,
  `available_at` INT NOT NULL,
  `created_at` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` TEXT NOT NULL,
  `options` TEXT NULL DEFAULT NULL,
  `cancelled_at` INT NULL DEFAULT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` VARCHAR(255) NOT NULL,
  `queue` VARCHAR(255) NOT NULL,
  `payload` TEXT NOT NULL,
  `exception` TEXT NOT NULL,
  `failed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `desa_profiles`
--

DROP TABLE IF EXISTS `desa_profiles`;
CREATE TABLE `desa_profiles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_desa` VARCHAR(255) NOT NULL,
  `kode_desa` VARCHAR(255) NULL DEFAULT NULL,
  `kecamatan` VARCHAR(255) NOT NULL,
  `kabupaten` VARCHAR(255) NOT NULL,
  `provinsi` VARCHAR(255) NOT NULL,
  `kode_pos` VARCHAR(255) NULL DEFAULT NULL,
  `alamat_kantor` TEXT NOT NULL,
  `email_desa` VARCHAR(255) NULL DEFAULT NULL,
  `telepon_desa` VARCHAR(255) NULL DEFAULT NULL,
  `website` VARCHAR(255) NULL DEFAULT NULL,
  `logo_path` VARCHAR(255) NULL DEFAULT NULL,
  `nama_kades` VARCHAR(255) NOT NULL,
  `nip_kades` VARCHAR(255) NULL DEFAULT NULL,
  `nik_kades` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `foto_desa_path` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `desa_profiles`
--

INSERT INTO `desa_profiles` (`id`, `nama_desa`, `kode_desa`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `alamat_kantor`, `email_desa`, `telepon_desa`, `website`, `logo_path`, `nama_kades`, `nip_kades`, `nik_kades`, `created_at`, `updated_at`, `foto_desa_path`) VALUES
(1, 'Desa Sukamaju', '3202112001', 'Cikole', 'Sukabumi', 'Jawa Barat', '43113', 'Jl. Raya Sukamaju No. 01, Kec. Cikole, Kab. Sukabumi', 'kontak@desa-sukamaju.id', '0266-221144', 'https://desa-sukamaju.id', NULL, 'H. Rahmat Hidayat, S.IP', '197508172005011003', '3202111708750001', '2026-08-30 14:04:17', '2026-08-30 14:04:17', NULL);

-- --------------------------------------------------------

--
-- Struktur tabel untuk `penduduks`
--

DROP TABLE IF EXISTS `penduduks`;
CREATE TABLE `penduduks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nik` VARCHAR(255) NOT NULL,
  `no_kk` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(255) NOT NULL,
  `tempat_lahir` VARCHAR(255) NOT NULL,
  `tanggal_lahir` DATE NOT NULL,
  `jenis_kelamin` VARCHAR(255) NOT NULL,
  `agama` VARCHAR(255) NOT NULL,
  `pendidikan_terakhir` VARCHAR(255) NULL DEFAULT NULL,
  `pekerjaan` VARCHAR(255) NULL DEFAULT NULL,
  `status_perkawinan` VARCHAR(255) NOT NULL DEFAULT 'belum_kawin',
  `status_dalam_keluarga` VARCHAR(255) NOT NULL DEFAULT 'kepala_keluarga',
  `kewarganegaraan` VARCHAR(255) NOT NULL DEFAULT 'WNI',
  `golongan_darah` VARCHAR(255) NULL DEFAULT NULL,
  `alamat_lengkap` TEXT NOT NULL,
  `rt` VARCHAR(255) NOT NULL,
  `rw` VARCHAR(255) NOT NULL,
  `dusun` VARCHAR(255) NULL DEFAULT NULL,
  `telepon` VARCHAR(255) NULL DEFAULT NULL,
  `sumber_data` VARCHAR(255) NOT NULL DEFAULT 'manual',
  `status_penduduk` VARCHAR(255) NOT NULL DEFAULT 'tetap',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penduduks`
--

INSERT INTO `penduduks` (`id`, `nik`, `no_kk`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pendidikan_terakhir`, `pekerjaan`, `status_perkawinan`, `status_dalam_keluarga`, `kewarganegaraan`, `golongan_darah`, `alamat_lengkap`, `rt`, `rw`, `dusun`, `telepon`, `sumber_data`, `status_penduduk`, `created_at`, `updated_at`) VALUES
(1, '3202111504700001', '3202111504700001', 'Asep Suhendar', 'Sukabumi', '1970-04-15', 'L', 'Islam', 'SLTA/Sederajat', 'Petani', 'kawin', 'kepala_keluarga', 'WNI', 'O', 'Kp. Cikole RT 001 RW 002 Desa Sukamaju', '001', '002', 'Cikole', '082112340001', 'prodeskel', 'tetap', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(2, '3202114508730001', '3202111504700001', 'Siti Rohmah', 'Sukabumi', '1973-08-05', 'P', 'Islam', 'SLTP/Sederajat', 'Ibu Rumah Tangga', 'kawin', 'istri', 'WNI', 'A', 'Kp. Cikole RT 001 RW 002 Desa Sukamaju', '001', '002', 'Cikole', NULL, 'prodeskel', 'tetap', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(3, '3202110206050001', '3202111504700001', 'Rizki Maulana Suhendar', 'Sukabumi', '2005-06-02', 'L', 'Islam', 'SLTA/Sederajat', 'Pelajar/Mahasiswa', 'belum_kawin', 'anak', 'WNI', 'O', 'Kp. Cikole RT 001 RW 002 Desa Sukamaju', '001', '002', 'Cikole', '083112340001', 'manual', 'tetap', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(4, '3202114910130001', '3202111504700001', 'Nur Fadilah Suhendar', 'Sukabumi', '2013-10-09', 'P', 'Islam', 'Belum Tamat SD', 'Pelajar/Mahasiswa', 'belum_kawin', 'anak', 'WNI', 'A', 'Kp. Cikole RT 001 RW 002 Desa Sukamaju', '001', '002', 'Cikole', NULL, 'manual', 'tetap', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(5, '3202111203800002', '3202111203800002', 'H. Dedi Supriadi', 'Cianjur', '1980-03-12', 'L', 'Islam', 'Diploma IV/S1', 'Pedagang', 'kawin', 'kepala_keluarga', 'WNI', 'B', 'Kp. Sukasari RT 003 RW 001 Desa Sukamaju', '003', '001', 'Sukasari', '081234567890', 'prodeskel', 'tetap', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(6, '3202115304830002', '3202111203800002', 'Hj. Imas Nuraeni', 'Sukabumi', '1983-04-13', 'P', 'Islam', 'SLTA/Sederajat', 'Ibu Rumah Tangga', 'kawin', 'istri', 'WNI', 'B', 'Kp. Sukasari RT 003 RW 001 Desa Sukamaju', '003', '001', 'Sukasari', NULL, 'prodeskel', 'tetap', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(7, '3202111507100002', '3202111203800002', 'Muhamad Fauzan Supriadi', 'Sukabumi', '2010-07-15', 'L', 'Islam', 'SLTP/Sederajat', 'Pelajar/Mahasiswa', 'belum_kawin', 'anak', 'WNI', 'A', 'Kp. Sukasari RT 003 RW 001 Desa Sukamaju', '003', '001', 'Sukasari', NULL, 'manual', 'tetap', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(8, '3202110809750003', '3202110809750003', 'Ade Suryadi', 'Sukabumi', '1975-09-08', 'L', 'Islam', 'SLTA/Sederajat', 'Buruh Harian Lepas', 'kawin', 'kepala_keluarga', 'WNI', 'AB', 'Kp. Karangtengah RT 002 RW 004 Desa Sukamaju', '002', '004', 'Karangtengah', '087891234500', 'migrasi_legacy', 'tetap', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(9, '3202114210780003', '3202110809750003', 'Ai Nuraeni Suryadi', 'Bandung', '1978-10-02', 'P', 'Islam', 'SLTP/Sederajat', 'Ibu Rumah Tangga', 'kawin', 'istri', 'WNI', NULL, 'Kp. Karangtengah RT 002 RW 004 Desa Sukamaju', '002', '004', 'Karangtengah', NULL, 'migrasi_legacy', 'tetap', '2026-08-30 14:04:17', '2026-08-30 14:04:17'),
(10, '3202111806920004', '3202111806920004', 'Yudi Permana', 'Sukabumi', '1992-06-18', 'L', 'Islam', 'Diploma IV/S1', 'PNS', 'kawin', 'kepala_keluarga', 'WNI', 'O', 'Kp. Cikole RT 001 RW 003 Desa Sukamaju', '001', '003', 'Cikole', '081298765432', 'manual', 'pindah', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(11, '3202118506450005', '3202110809750003', 'Sarinem', 'Sukabumi', '1945-06-25', 'P', 'Islam', 'Tidak/Belum Sekolah', NULL, 'cerai_mati', 'famili_lain', 'WNI', NULL, 'Kp. Karangtengah RT 002 RW 004 Desa Sukamaju', '002', '004', 'Karangtengah', NULL, 'migrasi_legacy', 'tetap', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(12, '3204150703950001', '3204150703950001', 'Budi Santoso', 'Bogor', '1995-03-07', 'L', 'Islam', 'Diploma IV/S1', 'Wiraswasta', 'belum_kawin', 'kepala_keluarga', 'WNI', 'A', 'Kp. Sukasari RT 004 RW 001 Desa Sukamaju', '004', '001', 'Sukasari', '089512345678', 'manual', 'tetap', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(13, '3202110203880006', '3202110203880000', 'Jajang Kurniawan', 'Sukabumi', '1988-03-02', 'L', 'Islam', 'SLTA/Sederajat', 'Petani', 'kawin', 'kepala_keluarga', 'WNI', NULL, 'Kp. Cikole RT 005 RW 001 Desa Sukamaju', '005', '001', 'Cikole', NULL, 'manual', 'tetap', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(14, '3202115504910006', '3202110203880000', 'Dewi Rahayu', 'Sukabumi', '1991-04-15', 'P', 'Islam', 'SLTA/Sederajat', 'Pedagang', 'kawin', 'istri', 'WNI', NULL, 'Kp. Cikole RT 005 RW 001 Desa Sukamaju', '005', '001', 'Cikole', NULL, 'manual', 'tetap', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(15, '3202112501200006', '3202110203880000', 'Ikhsan Maulana', 'Sukabumi', '2020-01-25', 'L', 'Islam', NULL, NULL, 'belum_kawin', 'anak', 'WNI', NULL, 'Kp. Cikole RT 005 RW 001 Desa Sukamaju', '005', '001', 'Cikole', NULL, 'manual', 'tetap', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(16, '3202111012960007', '3202111012960000', 'Dedi Wahyudi', 'Sukabumi', '1996-12-10', 'L', 'Islam', 'SLTA/Sederajat', 'Karyawan Swasta', 'belum_kawin', 'kepala_keluarga', 'WNI', NULL, 'Kp. Sukasari RT 006 RW 002 Desa Sukamaju', '006', '002', 'Sukasari', NULL, 'manual', 'tetap', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(17, '3202114903840008', '3202114903840000', 'Tini Andriani', 'Sukabumi', '1984-03-09', 'P', 'Islam', 'Diploma IV/S1', 'Guru', 'kawin', 'kepala_keluarga', 'WNI', NULL, 'Kp. Karangtengah RT 007 RW 003 Desa Sukamaju', '007', '003', 'Karangtengah', NULL, 'manual', 'tetap', '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `penduduk_documents`
--

DROP TABLE IF EXISTS `penduduk_documents`;
CREATE TABLE `penduduk_documents` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `penduduk_id` INT NOT NULL,
  `jenis_dokumen` VARCHAR(255) NOT NULL DEFAULT 'ktp',
  `nama_file` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` INT NOT NULL DEFAULT 0,
  `mime_type` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `penduduk_mutasis`
--

DROP TABLE IF EXISTS `penduduk_mutasis`;
CREATE TABLE `penduduk_mutasis` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `penduduk_id` INT NOT NULL,
  `jenis_mutasi` VARCHAR(255) NOT NULL,
  `tanggal_mutasi` DATE NOT NULL,
  `keterangan` TEXT NULL DEFAULT NULL,
  `berkas_pendukung_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penduduk_mutasis`
--

INSERT INTO `penduduk_mutasis` (`id`, `penduduk_id`, `jenis_mutasi`, `tanggal_mutasi`, `keterangan`, `berkas_pendukung_path`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 10, 'pindah_keluar', '2026-03-15', 'Pindah ke Kota Bogor mengikuti penempatan kerja PNS.', NULL, 2, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 15, 'lahir', '2020-01-25', 'Kelahiran di RSUD Sukabumi. Berat badan 3,2 kg.', NULL, 2, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(3, 12, 'pindah_masuk', '2025-11-01', 'Pindah masuk dari Kab. Bogor. Membuka usaha konveksi di desa.', NULL, 2, '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `surat_templates`
--

DROP TABLE IF EXISTS `surat_templates`;
CREATE TABLE `surat_templates` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_surat` VARCHAR(255) NOT NULL,
  `nama_surat` VARCHAR(255) NOT NULL,
  `penomoran_format` VARCHAR(255) NOT NULL,
  `template_blade` VARCHAR(255) NOT NULL,
  `schema_fields_json` TEXT NULL DEFAULT NULL,
  `icon` VARCHAR(255) NULL DEFAULT NULL,
  `deskripsi` TEXT NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_templates_kode_surat_unique` (`kode_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `surat_arsips`
--

DROP TABLE IF EXISTS `surat_arsips`;
CREATE TABLE `surat_arsips` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_surat` VARCHAR(255) NOT NULL,
  `surat_template_id` INT NOT NULL,
  `penduduk_id` INT NOT NULL,
  `user_id` INT NULL DEFAULT NULL,
  `keperluan` TEXT NULL DEFAULT NULL,
  `payload_data` TEXT NULL DEFAULT NULL,
  `file_pdf_path` VARCHAR(255) NULL DEFAULT NULL,
  `tanggal_terbit` DATE NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'terbit',
  `alasan_pembatalan` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_arsips_nomor_surat_unique` (`nomor_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `buku_ekspedisis`
--

DROP TABLE IF EXISTS `buku_ekspedisis`;
CREATE TABLE `buku_ekspedisis` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_urut` INT NOT NULL,
  `tahun` INT NOT NULL,
  `tanggal_pengiriman` DATE NOT NULL,
  `nomor_surat` VARCHAR(255) NOT NULL,
  `tanggal_surat` DATE NOT NULL,
  `perihal` TEXT NOT NULL,
  `tujuan_penerima` VARCHAR(255) NOT NULL,
  `petugas_pengirim` VARCHAR(255) NULL DEFAULT NULL,
  `surat_arsip_id` INT NULL DEFAULT NULL,
  `catatan` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `buku_agendas`
--

DROP TABLE IF EXISTS `buku_agendas`;
CREATE TABLE `buku_agendas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `jenis` VARCHAR(255) NOT NULL DEFAULT 'keluar',
  `nomor_urut` INT NOT NULL,
  `tahun` INT NOT NULL,
  `nomor_surat` VARCHAR(255) NOT NULL,
  `tanggal_surat` DATE NOT NULL,
  `tanggal_diterima_dikirim` DATE NOT NULL,
  `asal_tujuan` VARCHAR(255) NOT NULL,
  `perihal` TEXT NOT NULL,
  `surat_arsip_id` INT NULL DEFAULT NULL,
  `file_surat_path` VARCHAR(255) NULL DEFAULT NULL,
  `keterangan` TEXT NULL DEFAULT NULL,
  `created_by` INT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `buku_peraturan_desa`
--

DROP TABLE IF EXISTS `buku_peraturan_desa`;
CREATE TABLE `buku_peraturan_desa` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun` INT NOT NULL,
  `jenis_peraturan` VARCHAR(255) NOT NULL,
  `nomor_ditetapkan` VARCHAR(255) NOT NULL,
  `tanggal_ditetapkan` DATE NOT NULL,
  `tentang` TEXT NOT NULL,
  `uraian_singkat` TEXT NULL DEFAULT NULL,
  `nomor_kesepakatan_bpd` VARCHAR(255) NULL DEFAULT NULL,
  `nomor_diundangkan` VARCHAR(255) NULL DEFAULT NULL,
  `tanggal_diundangkan` DATE NULL DEFAULT NULL,
  `file_pdf_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `buku_keputusan_kades`
--

DROP TABLE IF EXISTS `buku_keputusan_kades`;
CREATE TABLE `buku_keputusan_kades` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun` INT NOT NULL,
  `nomor_keputusan` VARCHAR(255) NOT NULL,
  `tanggal_keputusan` DATE NOT NULL,
  `tentang` TEXT NOT NULL,
  `uraian_singkat` TEXT NULL DEFAULT NULL,
  `nomor_dilaporkan` VARCHAR(255) NULL DEFAULT NULL,
  `file_pdf_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `buku_inventaris_aset`
--

DROP TABLE IF EXISTS `buku_inventaris_aset`;
CREATE TABLE `buku_inventaris_aset` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun_pengadaan` INT NOT NULL,
  `jenis_barang` VARCHAR(255) NOT NULL,
  `kode_barang` VARCHAR(255) NULL DEFAULT NULL,
  `identitas_barang` TEXT NOT NULL,
  `asal_usul` VARCHAR(255) NOT NULL DEFAULT 'apbdes',
  `harga_perolehan` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `kondisi` VARCHAR(255) NOT NULL DEFAULT 'baik',
  `lokasi_penempatan` VARCHAR(255) NOT NULL,
  `foto_barang_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `buku_tanah_desa`
--

DROP TABLE IF EXISTS `buku_tanah_desa`;
CREATE TABLE `buku_tanah_desa` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `jenis_tanah` VARCHAR(255) NOT NULL,
  `nomor_sertifikat_letter_c` VARCHAR(255) NOT NULL,
  `nama_pemilik_asal` VARCHAR(255) NOT NULL,
  `luas_m2` DECIMAL(15,2) NOT NULL,
  `kelas_tanah` VARCHAR(255) NULL DEFAULT NULL,
  `lokasi_blok` VARCHAR(255) NOT NULL,
  `peruntukan_saat_ini` VARCHAR(255) NOT NULL,
  `patok_tanda_batas` TEXT NULL DEFAULT NULL,
  `file_warkah_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `buku_anggaran_desa`
--

DROP TABLE IF EXISTS `buku_anggaran_desa`;
CREATE TABLE `buku_anggaran_desa` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun` INT NOT NULL,
  `jenis_dokumen` VARCHAR(255) NOT NULL DEFAULT 'apbdes',
  `nomor_perdes` VARCHAR(255) NOT NULL,
  `tanggal_penetapan` DATE NOT NULL,
  `total_pendapatan` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total_belanja` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total_pembiayaan` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `keterangan` TEXT NULL DEFAULT NULL,
  `file_pdf_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `buku_lembaran_desa`
--

DROP TABLE IF EXISTS `buku_lembaran_desa`;
CREATE TABLE `buku_lembaran_desa` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun` INT NOT NULL,
  `jenis` VARCHAR(255) NOT NULL DEFAULT 'lembaran_desa',
  `nomor_seri` VARCHAR(255) NOT NULL,
  `tanggal_diundangkan` DATE NOT NULL,
  `judul` TEXT NOT NULL,
  `isi_singkat` TEXT NULL DEFAULT NULL,
  `file_pdf_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur tabel untuk `institutions`
--

DROP TABLE IF EXISTS `institutions`;
CREATE TABLE `institutions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lembaga` VARCHAR(255) NOT NULL,
  `singkatan` VARCHAR(255) NULL DEFAULT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `kategori` VARCHAR(255) NULL DEFAULT NULL,
  `nomor_sk_pendirian` VARCHAR(255) NULL DEFAULT NULL,
  `tanggal_sk` DATE NULL DEFAULT NULL,
  `deskripsi` TEXT NULL DEFAULT NULL,
  `alamat_sekretariat` TEXT NULL DEFAULT NULL,
  `logo_path` VARCHAR(255) NULL DEFAULT NULL,
  `urutan` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  UNIQUE KEY `institutions_slug_unique` (`slug`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `institutions`
--

INSERT INTO `institutions` (`id`, `nama_lembaga`, `singkatan`, `slug`, `kategori`, `nomor_sk_pendirian`, `tanggal_sk`, `deskripsi`, `alamat_sekretariat`, `logo_path`, `urutan`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Badan Permusyawaratan Desa', 'BPD', 'bpd', 'pemerintahan', '140/01/SK-BPD/2024', '2024-01-15', 'Lembaga permusyawaratan desa yang menampung aspirasi masyarakat dan mengawasi jalannya pemerintahan desa.', 'Gedung BPD Lantai 2, Kantor Desa', NULL, 1, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 'Badan Usaha Milik Desa', 'BUMDes', 'bumdes', 'ekonomi', '140/05/SK-BUMDES/2023', '2023-03-10', 'Badan usaha desa yang mengelola unit usaha perdagangan, penyewaan alat pertanian, dan air bersih desa.', 'Ruko Sentra Ekonomi Desa Unit 1-2', NULL, 2, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(3, 'Koperasi Desa', 'Kopdes', 'kopdes', 'ekonomi', '140/09/SK-KOPDES/2022', '2022-06-20', 'Koperasi simpan pinjam dan pengadaan pupuk/saprotan untuk kelompok tani dan pelaku UMKM desa.', 'Jl. Raya Desa No. 45', NULL, 3, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(4, 'Lembaga Pemberdayaan Masyarakat Desa', 'LPMD', 'lpmd', 'kemasyarakatan', '140/03/SK-LPMD/2024', '2024-02-01', 'Wadah yang dibentuk atas prakarsa masyarakat sebagai mitra pemerintah desa dalam menampung dan mewujudkan aspirasi serta kebutuhan masyarakat di bidang pembangunan.', 'Kantor Desa Ruang Sayap Barat', NULL, 4, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(5, 'Pemberdayaan Kesejahteraan Keluarga', 'PKK', 'pkk', 'kemasyarakatan', '140/02/SK-PKK/2024', '2024-01-20', 'Gerakan pemberdayaan wanita dan keluarga untuk mewujudkan keluarga sejahtera, sehat, dan berdaya.', 'Gedung Sekretariat TP-PKK Desa', NULL, 5, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(6, 'Pos Pelayanan Terpadu', 'Posyandu', 'posyandu', 'kesehatan', '140/04/SK-POSYANDU/2024', '2024-02-10', 'Lembaga kesehatan berbasis masyarakat yang melayani pemantauan tumbuh kembang balita, ibu hamil, lansia, dan pencegahan stunting.', 'Poskesdes / Posyandu Kasih Ibu RW 02', NULL, 6, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(7, 'Karang Taruna', 'Karang Taruna', 'karang-taruna', 'kemasyarakatan', '140/06/SK-KT/2024', '2024-03-01', 'Organisasi kepemudaan desa yang bergerak di bidang olahraga, kesenian, penanggulangan masalah sosial, dan kewirausahaan pemuda.', 'Gedung Pemuda / Lapangan Desa', NULL, 7, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(8, 'Lembaga Musyawarah Perwakilan (LMP)', 'LMP', 'lmp', 'kemasyarakatan', '140/07/SK-LMP/2024', '2024-03-15', 'Lembaga perwakilan pemangku adat, tokoh agama, dan tokoh masyarakat untuk musyawarah mufakat penyelesaian perselisihan dan pelestarian adat istiadat desa.', 'Balai Musyawarah Adat Desa', NULL, 8, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `institution_members`
--

DROP TABLE IF EXISTS `institution_members`;
CREATE TABLE `institution_members` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `institution_id` INT NOT NULL,
  `penduduk_id` INT NULL DEFAULT NULL,
  `nama_lengkap` VARCHAR(255) NOT NULL,
  `nik` VARCHAR(255) NULL DEFAULT NULL,
  `jabatan` VARCHAR(255) NOT NULL,
  `nomor_sk_pengangkatan` VARCHAR(255) NULL DEFAULT NULL,
  `tanggal_sk` DATE NULL DEFAULT NULL,
  `periode_mulai` INT NULL DEFAULT NULL,
  `periode_selesai` INT NULL DEFAULT NULL,
  `kontak` VARCHAR(255) NULL DEFAULT NULL,
  `keterangan` TEXT NULL DEFAULT NULL,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `institution_members`
--

INSERT INTO `institution_members` (`id`, `institution_id`, `penduduk_id`, `nama_lengkap`, `nik`, `jabatan`, `nomor_sk_pengangkatan`, `tanggal_sk`, `periode_mulai`, `periode_selesai`, `kontak`, `keterangan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Drs. H. Mulyadi, M.Si', '3202111504700001', 'Ketua BPD', '140/01/SK-BPD/2024', '2024-01-15', 2024, 2030, '081241436332', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(2, 1, 2, 'Ir. Bambang Sutrisno', '3202114508730001', 'Wakil Ketua BPD', '140/01/SK-BPD/2024', '2024-01-15', 2024, 2030, '081252837073', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(3, 1, 3, 'Siti Nurhaliza, S.Pd', '3202110206050001', 'Sekretaris BPD', '140/01/SK-BPD/2024', '2024-01-15', 2024, 2030, '081290802275', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(4, 1, 4, 'Agus Priyanto', '3202114910130001', 'Ketua Bidang Pemerintahan', '140/01/SK-BPD/2024', '2024-01-15', 2024, 2030, '081241235102', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(5, 1, 5, 'Hendro Prasetyo', '3202111203800002', 'Ketua Bidang Pembangunan', '140/01/SK-BPD/2024', '2024-01-15', 2024, 2030, '081278601709', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(6, 2, 1, 'H. Gunawan Saputra, SE', '3202111504700001', 'Direktur Utama', '140/05/SK-BUMDES/2023', '2023-03-10', 2023, 2028, '081246850548', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(7, 2, 2, 'Rina Marlina, A.Md', '3202114508730001', 'Sekretaris', '140/05/SK-BUMDES/2023', '2023-03-10', 2023, 2028, '081235031315', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(8, 2, 3, 'Wahyu Hidayat', '3202110206050001', 'Bendahara', '140/05/SK-BUMDES/2023', '2023-03-10', 2023, 2028, '081230254425', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(9, 2, 4, 'Teguh Wicaksono', '3202114910130001', 'Manajer Unit Usaha Air Bersih', '140/05/SK-BUMDES/2023', '2023-03-10', 2023, 2028, '081288241975', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(10, 3, 1, 'Deden Kurniawan, S.Pt', '3202111504700001', 'Ketua Koperasi', '140/09/SK-KOPDES/2022', '2022-06-20', 2022, 2027, '081228881204', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(11, 3, 2, 'Yanti Sumarni', '3202114508730001', 'Sekretaris', '140/09/SK-KOPDES/2022', '2022-06-20', 2022, 2027, '081211446119', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(12, 3, 3, 'Eka Pratama', '3202110206050001', 'Bendahara', '140/09/SK-KOPDES/2022', '2022-06-20', 2022, 2027, '081279629223', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(13, 4, 1, 'H. Suryadi Ahmad', '3202111504700001', 'Ketua LPMD', '140/03/SK-LPMD/2024', '2024-02-01', 2024, 2029, '081221798290', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(14, 4, 2, 'Fauzi Rahman, ST', '3202114508730001', 'Sekretaris', '140/03/SK-LPMD/2024', '2024-02-01', 2024, 2029, '081297073149', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(15, 4, 3, 'Hj. Rokayah', '3202110206050001', 'Bendahara', '140/03/SK-LPMD/2024', '2024-02-01', 2024, 2029, '081295835008', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(16, 4, 4, 'Kusnadi', '3202114910130001', 'Seksi Pembangunan Gotong Royong', '140/03/SK-LPMD/2024', '2024-02-01', 2024, 2029, '081245521529', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(17, 5, 1, 'Ny. Hj. Endang Sulastri', '3202111504700001', 'Ketua TP PKK Desa', '140/02/SK-PKK/2024', '2024-01-20', 2024, 2030, '081230394337', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(18, 5, 2, 'Ny. Ratna Dewi', '3202114508730001', 'Wakil Ketua', '140/02/SK-PKK/2024', '2024-01-20', 2024, 2030, '081226698283', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(19, 5, 3, 'Ny. Dewi Sartika, S.Pd', '3202110206050001', 'Sekretaris', '140/02/SK-PKK/2024', '2024-01-20', 2024, 2030, '081272968045', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(20, 5, 4, 'Ny. Sri Wahyuni', '3202114910130001', 'Bendahara', '140/02/SK-PKK/2024', '2024-01-20', 2024, 2030, '081266254513', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(21, 5, 5, 'Ny. Maryati', '3202111203800002', 'Ketua Pokja I (Penghayatan Pancasila)', '140/02/SK-PKK/2024', '2024-01-20', 2024, 2030, '081249311029', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(22, 5, 6, 'Ny. Fitri Handayani', '3202115304830002', 'Ketua Pokja II (Pendidikan & Keterampilan)', '140/02/SK-PKK/2024', '2024-01-20', 2024, 2030, '081288584439', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(23, 5, 7, 'Ny. Anisa Rahma', '3202111507100002', 'Ketua Pokja III (Pangan, Sandang, Perumahan)', '140/02/SK-PKK/2024', '2024-01-20', 2024, 2030, '081226044549', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(24, 5, 8, 'Ny. dr. Laila Nur', '3202110809750003', 'Ketua Pokja IV (Kesehatan, Kelestarian LH)', '140/02/SK-PKK/2024', '2024-01-20', 2024, 2030, '081255774932', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(25, 6, 1, 'Bdn. Nining Suhartini, S.Tr.Keb', '3202111504700001', 'Koordinator Kader Posyandu Desa', '140/04/SK-POSYANDU/2024', '2024-02-10', 2024, 2029, '081249017292', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(26, 6, 2, 'Nurul Aini', '3202114508730001', 'Sekretaris / Pengelola Data KMS', '140/04/SK-POSYANDU/2024', '2024-02-10', 2024, 2029, '081248875054', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(27, 6, 3, 'Siti Khotimah', '3202110206050001', 'Bendahara & Logistik PMT', '140/04/SK-POSYANDU/2024', '2024-02-10', 2024, 2029, '081217520708', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(28, 6, 4, 'Wulandari', '3202114910130001', 'Kader Penimbangan Balita', '140/04/SK-POSYANDU/2024', '2024-02-10', 2024, 2029, '081294121982', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(29, 6, 5, 'Rukmini', '3202111203800002', 'Kader Posyandu Lansia', '140/04/SK-POSYANDU/2024', '2024-02-10', 2024, 2029, '081224740023', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(30, 7, 1, 'Rizky Firmansyah, S.Kom', '3202111504700001', 'Ketua Karang Taruna', '140/06/SK-KT/2024', '2024-03-01', 2024, 2027, '081233995955', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(31, 7, 2, 'Dimas Aditya', '3202114508730001', 'Wakil Ketua', '140/06/SK-KT/2024', '2024-03-01', 2024, 2027, '081264600141', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(32, 7, 3, 'Bayu Nugroho', '3202110206050001', 'Sekretaris', '140/06/SK-KT/2024', '2024-03-01', 2024, 2027, '081292489921', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(33, 7, 4, 'Sinta Maharani', '3202114910130001', 'Bendahara', '140/06/SK-KT/2024', '2024-03-01', 2024, 2027, '081275036363', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(34, 7, 5, 'Aldi Pratama', '3202111203800002', 'Koordinator Olahraga & Seni', '140/06/SK-KT/2024', '2024-03-01', 2024, 2027, '081298849272', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(35, 8, 1, 'K.H. Ahmad Dahlan', '3202111504700001', 'Ketua LMP / Pemangku Adat', '140/07/SK-LMP/2024', '2024-03-15', 2024, 2029, '081254688475', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(36, 8, 2, 'Drs. H. Syarifudin', '3202114508730001', 'Wakil Ketua', '140/07/SK-LMP/2024', '2024-03-15', 2024, 2029, '081250571975', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(37, 8, 3, 'Ust. Marzuki', '3202110206050001', 'Sekretaris', '140/07/SK-LMP/2024', '2024-03-15', 2024, 2029, '081285975335', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(38, 8, 4, 'H. Abdul Somad', '3202114910130001', 'Anggota Keterwakilan Dusun 1', '140/07/SK-LMP/2024', '2024-03-15', 2024, 2029, '081297739458', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26'),
(39, 8, 5, 'H. Maimun', '3202111203800002', 'Anggota Keterwakilan Dusun 2', '140/07/SK-LMP/2024', '2024-03-15', 2024, 2029, '081230103547', NULL, 1, '2026-08-30 14:04:18', '2026-09-02 05:13:26');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `institution_decisions`
--

DROP TABLE IF EXISTS `institution_decisions`;
CREATE TABLE `institution_decisions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `institution_id` INT NOT NULL,
  `nomor_keputusan` VARCHAR(255) NOT NULL,
  `tanggal_keputusan` DATE NOT NULL,
  `tentang` VARCHAR(255) NOT NULL,
  `uraian_singkat` TEXT NULL DEFAULT NULL,
  `dokumen_path` VARCHAR(255) NULL DEFAULT NULL,
  `tahun` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `institution_decisions`
--

INSERT INTO `institution_decisions` (`id`, `institution_id`, `nomor_keputusan`, `tanggal_keputusan`, `tentang`, `uraian_singkat`, `dokumen_path`, `tahun`, `created_at`, `updated_at`) VALUES
(1, 1, '140/01/KEP-BPD/2026', '2026-01-20', 'Persetujuan Penetapan Rancangan APBDes Tahun Anggaran 2026', 'Menyetujui rancangan peraturan desa tentang APBDes 2026.', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 1, '140/02/KEP-BPD/2026', '2026-02-15', 'Hasil Evaluasi Kinerja Pemerintahan Desa Semester II', 'Penyampaian rekomendasi hasil monitoring kegiatan desa.', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(3, 2, '01/SK-DIR/BUMDES/2026', '2026-01-05', 'Penetapan Tarif Layanan Air Bersih Desa', 'Penyesuaian tarif air bersih per meter kubik.', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(4, 3, '02/KEP/KOPDES/2026', '2026-01-18', 'Penyaluran Pinjaman Bergulir UMKM', 'Persetujuan modal kerja untuk 25 pelaku usaha mikro.', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(5, 4, '140/01/SK-LPMD/2026', '2026-01-12', 'Jadwal Gerakan Gotong Royong Kebersihan Lingkungan', 'Penetapan jadwal gotong royong massal tiap Minggu pagi.', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(6, 5, '01/KEP/PKK-DESA/2026', '2026-01-10', 'Program Kerja 10 Pokok PKK Tahun 2026', 'Rencana aksi pembinaan keluarga dan pencegahan stunting.', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(7, 6, '140/01/KEP-POSYANDU/2026', '2026-01-08', 'Jadwal Layanan Posyandu Balita & Posyandu Lansia 5 Dusun', 'Penetapan tanggal giliran penimbangan serentak tiap bulan.', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(8, 7, '01/KT-DESA/KEP/2026', '2026-01-25', 'Pembentukan Panitia Turnamen Bola Voli Antar Dusun', 'Penetapan panitia pelaksana turnamen tahunan.', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(9, 8, '01/LMP-DESA/2026', '2026-01-30', 'Panduan Pelestarian Tradisi Sedekah Bumi & Bersih Desa', 'Penetapan tata cara ritual adat dan kesenian tradisional.', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `institution_activities`
--

DROP TABLE IF EXISTS `institution_activities`;
CREATE TABLE `institution_activities` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `institution_id` INT NOT NULL,
  `nama_kegiatan` VARCHAR(255) NOT NULL,
  `tanggal_kegiatan` DATE NOT NULL,
  `lokasi` VARCHAR(255) NULL DEFAULT NULL,
  `penanggung_jawab` VARCHAR(255) NULL DEFAULT NULL,
  `anggaran` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sumber_dana` VARCHAR(255) NULL DEFAULT NULL,
  `output_hasil` TEXT NULL DEFAULT NULL,
  `foto_dokumentasi` VARCHAR(255) NULL DEFAULT NULL,
  `tahun` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `institution_activities`
--

INSERT INTO `institution_activities` (`id`, `institution_id`, `nama_kegiatan`, `tanggal_kegiatan`, `lokasi`, `penanggung_jawab`, `anggaran`, `sumber_dana`, `output_hasil`, `foto_dokumentasi`, `tahun`, `created_at`, `updated_at`) VALUES
(1, 1, 'Musyawarah Desa Pembahasan RKPDes 2026', '2026-01-10', 'Balai Desa', 'Ketua BPD', '3500000.00', 'APBDes - DDS', 'Berita acara kesepakatan RKPDes 2026', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 1, 'Monitoring Proyek Rabat Beton Dusun 2', '2026-02-22', 'Dusun Sukamaju', 'Ketua Bidang Pembangunan', '750000.00', 'Operasional BPD', 'Laporan fisik progress 75%', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(3, 2, 'Pemasangan Pipa Distribusi Air Bersih RW 04', '2026-02-10', 'RW 04 Dusun Mekar', 'Manajer Unit Air', '18500000.00', 'Kas Usaha BUMDes', '50 sambungan rumah baru terpasang', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(4, 3, 'Distribusi Pupuk Bersubsidi Musim Tanam I', '2026-02-05', 'Gudang Koperasi', 'Ketua Koperasi', '45000000.00', 'Modal Koperasi & Gapoktan', '15 ton pupuk tersalurkan ke 8 kelompok tani', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(5, 4, 'Bakti Gotong Royong Normalisasi Saluran Irigasi', '2026-02-18', 'Saluran Sekunder Dusun 1', 'Seksi Pembangunan', '2000000.00', 'Swadaya & APBDes', 'Pembersihan endapan lumpur sepanjang 800 meter', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(6, 5, 'Pelatihan Pembuatan Olahan Pangan Lokal & MP-ASI', '2026-02-14', 'Aula Balai Desa', 'Pokja III & IV', '4200000.00', 'APBDes Bidang Pemberdayaan', '45 kader PKK terlatih mengolah pangan bergizi', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(7, 6, 'Bulan Timbang & Pemberian Vitamin A Balita', '2026-02-12', 'Posyandu Mawar 1 s/d 5', 'Koordinator Kader', '6500000.00', 'BOK Puskesmas & APBDes', '320 balita tertimbang, 310 balita menerima vitamin A', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(8, 7, 'Pelatihan Digital Marketing bagi Pemuda Pelaku Usaha', '2026-02-20', 'Lab Komputer Kantor Desa', 'Ketua Karang Taruna', '3000000.00', 'APBDes Pembinaan Kepemudaan', '30 pemuda desa mampu membuat konten medsos promosi produk', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(9, 8, 'Musyawarah Mediasi Batas Tanah Adat & Fasum Warga', '2026-02-16', 'Balai Adat Desa', 'Ketua LMP', '800000.00', 'Kas Operasional LMP', 'Surat kesepakatan damai bermaterai antar pihak', NULL, 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `institution_agendas`
--

DROP TABLE IF EXISTS `institution_agendas`;
CREATE TABLE `institution_agendas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `institution_id` INT NOT NULL,
  `tanggal_agenda` DATE NOT NULL,
  `waktu` VARCHAR(255) NULL DEFAULT NULL,
  `nama_agenda` VARCHAR(255) NOT NULL,
  `tempat` VARCHAR(255) NULL DEFAULT NULL,
  `peserta` VARCHAR(255) NULL DEFAULT NULL,
  `pembahasan` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'rencana',
  `tahun` INT NOT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `institution_agendas`
--

INSERT INTO `institution_agendas` (`id`, `institution_id`, `tanggal_agenda`, `waktu`, `nama_agenda`, `tempat`, `peserta`, `pembahasan`, `status`, `tahun`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-03-05', '09:00 WIB', 'Rapat Dengar Pendapat Warga RW 03', 'Aula Balai Desa', 'Anggota BPD, Pengurus RT/RW', 'Aspirasi perbaikan saluran drainase utama.', 'rencana', 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 1, '2026-03-12', '13:30 WIB', 'Sidang Pleno LKPJ Kepala Desa Akhir Tahun', 'Gedung Serbaguna', 'BPD & Pemdes', 'Pembahasan laporan keterangan pertanggungjawaban kades.', 'rencana', 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(3, 2, '2026-03-15', '10:00 WIB', 'Musyawarah Kerja Tahunan BUMDes', 'Kantor BUMDes', 'Direksi & Dewan Pengawas', 'Laporan keuangan triwulan I & rencana ekspansi minimarket desa.', 'rencana', 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(4, 3, '2026-03-20', '09:00 WIB', 'Rapat Anggota Tahunan (RAT) 2026', 'Balai Desa', 'Seluruh Anggota Koperasi', 'Pembagian SHU dan pertanggungjawaban pengurus.', 'rencana', 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(5, 4, '2026-03-08', '08:00 WIB', 'Koordinasi Penataan Taman Desa Ramah Anak', 'Lokasi Lapangan Desa', 'Pengurus LPMD & Tokoh Masyarakat', 'Rencana swadaya penanaman pohon peneduh dan bangku taman.', 'rencana', 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(6, 5, '2026-03-10', '13:00 WIB', 'Pertemuan Rutin Bulanan PKK & Arisan', 'Sekretariat PKK', 'Pengurus & Ketua Dasawisma', 'Evaluasi lomba halaman asri teratur indah nyaman (HATINYA PKK).', 'rencana', 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(7, 6, '2026-03-18', '08:30 WIB', 'Pemeriksaan Kesehatan Berkala & Senam Lansia', 'Posyandu RW 03', 'Lansia se-desa & Tim Medis Puskesmas', 'Cek tensi, gula darah, dan edukasi pola makan lansia.', 'rencana', 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(8, 7, '2026-03-22', '15:30 WIB', 'Technical Meeting Turnamen Voli Cup 2026', 'Tribun Lapangan Desa', 'Official 8 Tim Dusun', 'Drawing grup dan penetapan regulasi pertandingan.', 'rencana', 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(9, 8, '2026-03-25', '19:30 WIB', 'Musyawarah Persiapan Acara Adat Ruwatan Desa', 'Serambi Masjid Jami Desa', 'Pengurus LMP, Tokoh Agama, RT/RW', 'Penjadwalan doa bersama dan santunan anak yatim se-desa.', 'rencana', 2026, '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `aparatur`
--

DROP TABLE IF EXISTS `aparatur`;
CREATE TABLE `aparatur` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `penduduk_id` INT NOT NULL,
  `nip` VARCHAR(255) NULL DEFAULT NULL,
  `jabatan` VARCHAR(255) NOT NULL,
  `qr_token` VARCHAR(255) NOT NULL,
  `jam_masuk_standar` TIME NOT NULL DEFAULT '08:00:00',
  `jam_pulang_standar` TIME NOT NULL DEFAULT '16:00:00',
  `toleransi_terlambat_menit` INT NOT NULL DEFAULT 15,
  `status_kepegawaian` VARCHAR(255) NOT NULL,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  UNIQUE KEY `aparatur_penduduk_id_unique` (`penduduk_id`),
  UNIQUE KEY `aparatur_qr_token_unique` (`qr_token`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `aparatur`
--

INSERT INTO `aparatur` (`id`, `penduduk_id`, `nip`, `jabatan`, `qr_token`, `jam_masuk_standar`, `jam_pulang_standar`, `toleransi_terlambat_menit`, `status_kepegawaian`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, '197505102005011002', 'Kepala Desa', '8n038dkPA1ILLWF1edSkPn42VYNuiKVg', '08:00:00', '16:00:00', 15, 'perangkat_desa', 1, '2026-08-30 14:04:18', '2026-08-31 09:35:53'),
(2, 2, '198208152008022001', 'Sekretaris Desa', 'amvbE40DmqPY7DfbsJjHjdPywYMricZl', '08:00:00', '16:00:00', 15, 'pns', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(3, 3, '198811202010011004', 'Kaur Keuangan', 'q7b8evQQ8W6LZaVQuJUkVaUoZ15fmAg4', '08:00:00', '16:00:00', 15, 'perangkat_desa', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(4, 4, '199003122015022003', 'Kaur Umum & Perencanaan', '7amrbm20t4DphDwjtwGSGiZ2g8yCxOJ5', '08:00:00', '16:00:00', 15, 'perangkat_desa', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(5, 5, '198604052009011007', 'Kasi Pemerintahan', '4xShvjHhKE5NLrInk2r8oe4Ot6GDIijF', '08:00:00', '16:00:00', 15, 'perangkat_desa', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(6, 6, '199201252018032001', 'Kasi Kesejahteraan & Pelayanan', 'cEOl7JFT2rYsaz90TTxztgANL5TFlaLL', '08:00:00', '16:00:00', 15, 'pppk', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(7, 7, NULL, 'Kepala Dusun 1 (Cikole)', 'vmP6uGZU7vxEI0ztIqRmnGj4zxPhUKsH', '08:00:00', '16:00:00', 15, 'perangkat_desa', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(8, 8, NULL, 'Kepala Dusun 2 (Sukamaju)', 'jtybwjxVwq4Mum91QhitrxopsbljwNxd', '08:00:00', '16:00:00', 15, 'perangkat_desa', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `absensi`
--

DROP TABLE IF EXISTS `absensi`;
CREATE TABLE `absensi` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `aparatur_id` INT NOT NULL,
  `tanggal` DATE NOT NULL,
  `jam_masuk` TIME NULL DEFAULT NULL,
  `jam_pulang` TIME NULL DEFAULT NULL,
  `status_kehadiran` VARCHAR(255) NOT NULL DEFAULT 'hadir',
  `metode_absen` VARCHAR(255) NOT NULL,
  `catatan` TEXT NULL DEFAULT NULL,
  `verified_by` INT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id`, `aparatur_id`, `tanggal`, `jam_masuk`, `jam_pulang`, `status_kehadiran`, `metode_absen`, `catatan`, `verified_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-09-02', '05:46:22', NULL, 'hadir', 'qr_scanner', NULL, NULL, '2026-09-02 05:46:22', '2026-09-02 05:46:22');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `keuangan_apbdes`
--

DROP TABLE IF EXISTS `keuangan_apbdes`;
CREATE TABLE `keuangan_apbdes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun_anggaran` INT NOT NULL,
  `kode_rekening` VARCHAR(255) NOT NULL,
  `jenis` VARCHAR(255) NOT NULL,
  `bidang` VARCHAR(255) NULL DEFAULT NULL,
  `uraian` TEXT NOT NULL,
  `anggaran` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `realisasi` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sumber_dana` VARCHAR(255) NOT NULL DEFAULT 'DDS',
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `keuangan_apbdes`
--

INSERT INTO `keuangan_apbdes` (`id`, `tahun_anggaran`, `kode_rekening`, `jenis`, `bidang`, `uraian`, `anggaran`, `realisasi`, `sumber_dana`, `created_at`, `updated_at`) VALUES
(1, 2026, '4.1.1.01', 'pendapatan', 'Pendapatan Asli Desa (PADes)', 'Bagi Hasil Usaha BUMDes Sukamaju Makmur', '45000000.00', '22500000.00', 'PAD', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 2026, '4.2.1.01', 'pendapatan', 'Pendapatan Transfer - Dana Desa', 'Penyaluran Dana Desa (DDS) Tahap I & II', '850000000.00', '510000000.00', 'DDS', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(3, 2026, '4.2.2.01', 'pendapatan', 'Pendapatan Transfer - Alokasi Dana Desa', 'Alokasi Dana Desa (ADD) dari APBD Kabupaten', '420000000.00', '280000000.00', 'ADD', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(4, 2026, '4.2.3.01', 'pendapatan', 'Pendapatan Transfer - Bagi Hasil Pajak', 'Bagi Hasil Pajak & Retribusi Daerah (PBH)', '65000000.00', '35000000.00', 'PBH', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(5, 2026, '5.1.1.01', 'belanja', 'Bidang 1: Penyelenggaraan Pemerintahan Desa', 'Penghasilan Tetap & Tunjangan Kepala Desa serta Perangkat Desa', '280000000.00', '165000000.00', 'ADD', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(6, 2026, '5.2.1.01', 'belanja', 'Bidang 2: Pelaksanaan Pembangunan Desa', 'Pengaspalan Hotmix Jalan Usaha Tani Dusun Babakan', '185000000.00', '185000000.00', 'DDS', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(7, 2026, '5.2.2.01', 'belanja', 'Bidang 2: Pelaksanaan Pembangunan Desa', 'Pembangunan Gedung Posyandu Melati Dusun 2', '95000000.00', '50000000.00', 'DDS', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(8, 2026, '5.3.1.01', 'belanja', 'Bidang 3: Pembinaan Kemasyarakatan Desa', 'Penyelenggaraan Festival Seni Budaya & Turnamen Olahraga Desa', '35000000.00', '20000000.00', 'PAD', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(9, 2026, '5.4.1.01', 'belanja', 'Bidang 4: Pemberdayaan Masyarakat Desa', 'Pelatihan Keterampilan UMKM & Honor Insentif Kader KPM', '45000000.00', '25000000.00', 'DDS', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(10, 2026, '5.5.1.01', 'belanja', 'Bidang 5: Penanggulangan Bencana & Darurat', 'Dana Siaga Mitigasi Longsor & Tanggap Darurat Kesehatan', '50000000.00', '12500000.00', 'DDS', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(11, 2026, '6.1.1.01', 'pembiayaan', 'Penerimaan Pembiayaan Desa', 'Sisa Lebih Perhitungan Anggaran (SiLPA) Tahun Anggaran 2025', '85000000.00', '85000000.00', 'DLL', '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `keuangan_kas_transaksi`
--

DROP TABLE IF EXISTS `keuangan_kas_transaksi`;
CREATE TABLE `keuangan_kas_transaksi` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `buku_kas_type` VARCHAR(255) NOT NULL DEFAULT 'umum',
  `tahun_anggaran` INT NOT NULL,
  `tanggal` DATE NOT NULL,
  `nomor_bukti` VARCHAR(255) NOT NULL,
  `kode_rekening` VARCHAR(255) NULL DEFAULT NULL,
  `uraian` TEXT NOT NULL,
  `penerimaan` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `pengeluaran` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `saldo` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sumber_dana` VARCHAR(255) NOT NULL DEFAULT 'DDS',
  `file_bukti_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `kategori_kas` VARCHAR(255) NOT NULL DEFAULT 'tunai',
  `jenis_pembantu` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `keuangan_kas_transaksi`
--

INSERT INTO `keuangan_kas_transaksi` (`id`, `buku_kas_type`, `tahun_anggaran`, `tanggal`, `nomor_bukti`, `kode_rekening`, `uraian`, `penerimaan`, `pengeluaran`, `saldo`, `sumber_dana`, `file_bukti_path`, `created_by`, `created_at`, `updated_at`, `kategori_kas`, `jenis_pembantu`) VALUES
(1, 'umum', 2026, '2026-01-10', 'BKM-01/DDS/2026', '4.2.1.01', 'Penerimaan Penyaluran Dana Desa (DDS) Tahap I Tahun 2026', '340000000.00', '0.00', '340000000.00', 'DDS', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'tunai', 'umum'),
(2, 'umum', 2026, '2026-01-15', 'BKK-01/DDS/2026', '5.2.1.01', 'Pembayaran Termin 1 Pengaspalan Jalan Usaha Tani Dusun Babakan', '0.00', '90000000.00', '250000000.00', 'DDS', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'tunai', 'panjar'),
(3, 'umum', 2026, '2026-02-01', 'BKM-02/ADD/2026', '4.2.2.01', 'Penerimaan Alokasi Dana Desa (ADD) Triwulan I', '105000000.00', '0.00', '355000000.00', 'ADD', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'tunai', 'umum'),
(4, 'umum', 2026, '2026-02-05', 'BKK-02/ADD/2026', '5.1.1.01', 'Pembayaran Siltap dan Tunjangan Aparatur Desa Bulan Januari', '0.00', '23500000.00', '331500000.00', 'ADD', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'tunai', 'umum'),
(5, 'umum', 2026, '2026-02-20', 'BKK-03/DDS/2026', '5.2.2.01', 'Pembelian Material Batu, Semen & Besi Gedung Posyandu Dusun 2', '0.00', '35000000.00', '296500000.00', 'DDS', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'tunai', 'panjar'),
(6, 'umum', 2026, '2026-03-01', 'BKM-03/PAD/2026', '4.1.1.01', 'Setoran Bagi Hasil Laba BUMDes Unit Pengelolaan Air Bersih', '12500000.00', '0.00', '309000000.00', 'PAD', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'tunai', 'pajak'),
(7, 'bank', 2026, '2026-01-10', 'TRF-BJB/01/2026', '4.2.1.01', 'Transfer Masuk dari Kas Daerah - Dana Desa Tahap I', '340000000.00', '0.00', '340000000.00', 'DDS', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'bank', NULL),
(8, 'bank', 2026, '2026-01-14', 'CEK-BJB/01/2026', '5.2.1.01', 'Penarikan Cek Operasional Belanja Aspal Jalan Babakan', '0.00', '90000000.00', '250000000.00', 'DDS', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'bank', NULL),
(9, 'bank', 2026, '2026-01-31', 'BUNGA-BJB/01/2026', '4.1.1.01', 'Penerimaan Pendapatan Bunga Rekening Giro Kas Desa', '350000.00', '0.00', '250350000.00', 'PAD', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'bank', NULL),
(10, 'bank', 2026, '2026-01-31', 'ADM-BJB/01/2026', '5.1.1.01', 'Biaya Administrasi Bulanan Bank & Pajak Giro', '0.00', '75000.00', '250275000.00', 'PAD', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'bank', NULL);

-- --------------------------------------------------------

--
-- Struktur tabel untuk `keuangan_rabs`
--

DROP TABLE IF EXISTS `keuangan_rabs`;
CREATE TABLE `keuangan_rabs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun_anggaran` INT NOT NULL,
  `nomor_rab` VARCHAR(255) NOT NULL,
  `bidang` VARCHAR(255) NOT NULL,
  `sub_bidang` VARCHAR(255) NULL DEFAULT NULL,
  `nama_kegiatan` VARCHAR(255) NOT NULL,
  `lokasi` VARCHAR(255) NOT NULL,
  `waktu_pelaksanaan` VARCHAR(255) NOT NULL,
  `sumber_dana` VARCHAR(255) NOT NULL DEFAULT 'DDS',
  `nama_ppkd` VARCHAR(255) NULL DEFAULT NULL,
  `jabatan_ppkd` VARCHAR(255) NULL DEFAULT NULL,
  `total_anggaran` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(255) NOT NULL DEFAULT 'draft',
  `keterangan` TEXT NULL DEFAULT NULL,
  `file_lampiran_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  UNIQUE KEY `keuangan_rabs_nomor_rab_unique` (`nomor_rab`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `keuangan_rabs`
--

INSERT INTO `keuangan_rabs` (`id`, `tahun_anggaran`, `nomor_rab`, `bidang`, `sub_bidang`, `nama_kegiatan`, `lokasi`, `waktu_pelaksanaan`, `sumber_dana`, `nama_ppkd`, `jabatan_ppkd`, `total_anggaran`, `status`, `keterangan`, `file_lampiran_path`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2026, 'RAB/01/DDS/2026', 'Bidang 2: Pelaksanaan Pembangunan Desa', 'Pekerjaan Umum dan Penataan Ruang', 'Pembangunan Rabat Beton Jalan Usaha Tani Dusun Babakan', 'Dusun Babakan RT 02 / RW 03', '90 Hari Kalender (Maret - Mei 2026)', 'DDS', 'Irvan Hermawan, S.T.', 'Kepala Seksi Kesejahteraan', '145000000.00', 'disetujui', 'Peningkatan akses jalan pertanian sepanjang 450 meter dengan spesifikasi tebal 15cm dan lebar 2.5 meter', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 2026, 'RAB/02/DDS/2026', 'Bidang 2: Pelaksanaan Pembangunan Desa', 'Kawasan Permukiman dan Sanitasi', 'Penyediaan Sarana Air Bersih & MCK Komunal Dusun 2', 'Dusun 2 RT 04 / RW 02', '60 Hari Kalender (Juni - Juli 2026)', 'DDS', 'Irvan Hermawan, S.T.', 'Kepala Seksi Kesejahteraan', '65000000.00', 'draft', 'Pengeboran sumur artesis dalam, tangki toren 2000L, dan pipanisasi ke 40 KK', NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `keuangan_rab_items`
--

DROP TABLE IF EXISTS `keuangan_rab_items`;
CREATE TABLE `keuangan_rab_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `keuangan_rab_id` INT NOT NULL,
  `kode_rekening` VARCHAR(255) NULL DEFAULT NULL,
  `kategori` VARCHAR(255) NOT NULL DEFAULT 'bahan_material',
  `uraian` VARCHAR(255) NOT NULL,
  `volume` DECIMAL(15,2) NOT NULL DEFAULT 1.00,
  `satuan` VARCHAR(255) NOT NULL DEFAULT 'Unit',
  `harga_satuan` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total_harga` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `keterangan` VARCHAR(255) NULL DEFAULT NULL,
  `urutan` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `keuangan_rab_items`
--

INSERT INTO `keuangan_rab_items` (`id`, `keuangan_rab_id`, `kode_rekening`, `kategori`, `uraian`, `volume`, `satuan`, `harga_satuan`, `total_harga`, `keterangan`, `urutan`, `created_at`, `updated_at`) VALUES
(16, 1, '5.2.1.01', 'bahan_material', 'Semen Portland (50 kg) Standar SNI', '450.00', 'Zak', '68000.00', '30600000.00', 'Semen Gresik / Tiga Roda', 1, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(17, 1, '5.2.1.01', 'bahan_material', 'Pasir Pasang / Pasir Cor Berkualitas', '85.00', 'M3', '280000.00', '23800000.00', 'Pasir kali Galunggung', 2, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(18, 1, '5.2.1.01', 'bahan_material', 'Batu Split / Kerikil Cor 2/3', '95.00', 'M3', '310000.00', '29450000.00', 'Split pecah mesin', 3, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(19, 1, '5.2.1.01', 'bahan_material', 'Besi Wiremesh M6 Standar', '60.00', 'Lembar', '360000.00', '21600000.00', 'Tulangan bawah jalan', 4, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(20, 1, '5.2.1.02', 'upah_tenaga_kerja', 'Upah Tukang Batu Berpengalaman', '90.00', 'HOK', '130000.00', '11700000.00', 'Tukang lokal warga desa', 5, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(21, 1, '5.2.1.02', 'upah_tenaga_kerja', 'Upah Pekerja / Pembantu Tukang (Padat Karya Tunai Desa)', '200.00', 'HOK', '95000.00', '19000000.00', 'PKTD masyarakat miskin/pra-sejahtera', 6, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(22, 1, '5.2.1.03', 'sewa_alat', 'Sewa Mesin Molen Pengaduk Semen', '20.00', 'Hari', '250000.00', '5000000.00', 'Termasuk bahan bakar & operator mesin', 7, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(23, 1, '5.2.1.04', 'operasional', 'Papan Nama Proyek & Prasasti Marmer Peresmian', '1.00', 'Paket', '1850000.00', '1850000.00', 'Transparansi publik dan prasasti', 8, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(24, 1, '5.2.1.04', 'operasional', 'Honorarium Tim Pengelola Kegiatan (TPK) & Pelaporan', '1.00', 'Paket', '2000000.00', '2000000.00', 'Dokumentasi 0%, 50%, 100% dan LPJ', 9, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(25, 2, '5.2.1.01', 'bahan_material', 'Tangki Air Pinguin 2000 Liter & Menara Besi Siku', '1.00', 'Unit', '14500000.00', '14500000.00', 'Kapasitas 2.000 Liter SNI', 1, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(26, 2, '5.2.1.01', 'bahan_material', 'Pipa PVC Rucika AW 1 Inch & Fitting Sambungan', '120.00', 'Batang', '85000.00', '10200000.00', 'Pipa distribusi dusun', 2, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(27, 2, '5.2.1.01', 'bahan_material', 'Mesin Pompa Submersible Deep Well 1.5 HP', '1.00', 'Unit', '8800000.00', '8800000.00', 'Pompa submersible kedalaman 60m', 3, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(28, 2, '5.2.1.03', 'sewa_alat', 'Jasa Pengeboran Sumur Artesis Kedalaman 60 Meter', '1.00', 'Paket', '22000000.00', '22000000.00', 'Rig bor sumur lengkap', 4, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(29, 2, '5.2.1.02', 'upah_tenaga_kerja', 'Upah Pemasangan Jaringan Pipa & Kran Distribusi', '70.00', 'HOK', '110000.00', '7700000.00', 'Instalatur lokal', 5, '2026-09-02 05:13:26', '2026-09-02 05:13:26'),
(30, 2, '5.2.1.04', 'operasional', 'Uji Laboratorium Kualitas Air & Sosialisasi Warga', '1.00', 'Paket', '1800000.00', '1800000.00', 'Uji lab kelayakan konsumsi', 6, '2026-09-02 05:13:26', '2026-09-02 05:13:26');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `pembangunan_proyek`
--

DROP TABLE IF EXISTS `pembangunan_proyek`;
CREATE TABLE `pembangunan_proyek` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun_anggaran` INT NOT NULL,
  `nama_kegiatan` VARCHAR(255) NOT NULL,
  `lokasi` VARCHAR(255) NOT NULL,
  `volume` VARCHAR(255) NOT NULL,
  `anggaran_biaya` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `realisasi_biaya` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `sumber_dana` VARCHAR(255) NOT NULL DEFAULT 'Dana Desa (DDS)',
  `pelaksana_tpk` VARCHAR(255) NOT NULL,
  `status_progres` VARCHAR(255) NOT NULL DEFAULT 'perencanaan',
  `persentase_selesai` INT NOT NULL DEFAULT 0,
  `foto_titik_nol` VARCHAR(255) NULL DEFAULT NULL,
  `foto_50_persen` VARCHAR(255) NULL DEFAULT NULL,
  `foto_100_persen` VARCHAR(255) NULL DEFAULT NULL,
  `file_rab_path` VARCHAR(255) NULL DEFAULT NULL,
  `manfaat_warga` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembangunan_proyek`
--

INSERT INTO `pembangunan_proyek` (`id`, `tahun_anggaran`, `nama_kegiatan`, `lokasi`, `volume`, `anggaran_biaya`, `realisasi_biaya`, `sumber_dana`, `pelaksana_tpk`, `status_progres`, `persentase_selesai`, `foto_titik_nol`, `foto_50_persen`, `foto_100_persen`, `file_rab_path`, `manfaat_warga`, `created_at`, `updated_at`) VALUES
(1, 2026, 'Pengaspalan Hotmix Jalan Usaha Tani Dusun Babakan', 'Dusun Babakan RT 03 / RW 01', 'Panjang 450m x Lebar 3m x Tebal 4cm', '185000000.00', '185000000.00', 'Dana Desa (DDS)', 'TPK Dusun Babakan (Ketua: Bpk. Suryadi)', 'selesai', 100, NULL, NULL, NULL, NULL, 'Mempermudah akses transportasi panen 120 KK petani padi dan palawija', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 2026, 'Pembangunan Gedung Posyandu Melati Dusun 2', 'Kp. Sukamaju RW 02 (Samping Balai Dusun)', 'Bangunan Permanen 6m x 8m', '95000000.00', '50000000.00', 'Dana Desa (DDS)', 'TPK Pembangunan Desa Sukamaju', 'proses', 50, NULL, NULL, NULL, NULL, 'Pelayanan pemeriksaan kesehatan ibu hamil, imunisasi balita & posbindu lansia', '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(3, 2026, 'Pembangunan Drainase Lingkungan & U-Ditch RW 03', 'Jl. Flamboyan Gang 2 RT 01 s/d RT 03 RW 03', 'Panjang 200m (Beton Precast U-Ditch 40x40)', '65000000.00', '0.00', 'Dana Desa (DDS)', 'TPK Dusun 3 Sukamaju', 'perencanaan', 0, NULL, NULL, NULL, NULL, 'Mencegah genangan air hujan dan banjir luapan bagi 65 KK warga RW 03', '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `pembangunan_kader`
--

DROP TABLE IF EXISTS `pembangunan_kader`;
CREATE TABLE `pembangunan_kader` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `penduduk_id` INT NOT NULL,
  `jenis_kader` VARCHAR(255) NOT NULL DEFAULT 'posyandu',
  `jabatan` VARCHAR(255) NOT NULL,
  `nomor_sk` VARCHAR(255) NULL DEFAULT NULL,
  `tanggal_sk` DATE NULL DEFAULT NULL,
  `honor_bulanan` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `keterangan` TEXT NULL DEFAULT NULL,
  `status_aktif` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembangunan_kader`
--

INSERT INTO `pembangunan_kader` (`id`, `penduduk_id`, `jenis_kader`, `jabatan`, `nomor_sk`, `tanggal_sk`, `honor_bulanan`, `keterangan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 'kpm_stunting', 'Koordinator Kader Pembangunan Manusia (KPM Stunting)', '141.1/SK-08/DS/2026', '2026-01-05', '450000.00', 'Monitoring pemenuhan gizi 1000 HPK & pendataan balita di seluruh dusun', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 2, 'posyandu', 'Ketua Kader Posyandu Melati Dusun 1', '141.1/SK-09/DS/2026', '2026-01-05', '300000.00', 'Pelaksanaan posyandu balita bulanan dan PMT', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(3, 3, 'guru_paud', 'Tenaga Pendidik PAUD Tunas Bangsa', '141.1/SK-10/DS/2026', '2026-01-05', '500000.00', 'Insentif guru PAUD binaan desa', 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `pembangunan_inventaris_hasil`
--

DROP TABLE IF EXISTS `pembangunan_inventaris_hasil`;
CREATE TABLE `pembangunan_inventaris_hasil` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun_anggaran` INT NOT NULL,
  `nomor_inventaris` VARCHAR(255) NOT NULL,
  `nama_hasil_pembangunan` VARCHAR(255) NOT NULL,
  `pembangunan_proyek_id` INT NULL DEFAULT NULL,
  `kategori_aset` VARCHAR(255) NOT NULL DEFAULT 'jalan_jembatan',
  `volume` VARCHAR(255) NOT NULL,
  `lokasi` VARCHAR(255) NOT NULL,
  `tanggal_serah_terima` DATE NULL DEFAULT NULL,
  `sumber_dana` VARCHAR(255) NOT NULL DEFAULT 'DDS',
  `nilai_aset` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `kondisi` VARCHAR(255) NOT NULL DEFAULT 'baik',
  `status_pengelolaan` VARCHAR(255) NOT NULL DEFAULT 'dikelola_desa',
  `penanggung_jawab` VARCHAR(255) NULL DEFAULT NULL,
  `keterangan` TEXT NULL DEFAULT NULL,
  `foto_hasil_path` VARCHAR(255) NULL DEFAULT NULL,
  `file_bast_path` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  UNIQUE KEY `pembangunan_inventaris_hasil_nomor_inventaris_unique` (`nomor_inventaris`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pembangunan_inventaris_hasil`
--

INSERT INTO `pembangunan_inventaris_hasil` (`id`, `tahun_anggaran`, `nomor_inventaris`, `nama_hasil_pembangunan`, `pembangunan_proyek_id`, `kategori_aset`, `volume`, `lokasi`, `tanggal_serah_terima`, `sumber_dana`, `nilai_aset`, `kondisi`, `status_pengelolaan`, `penanggung_jawab`, `keterangan`, `foto_hasil_path`, `file_bast_path`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2026, 'INV-BANG/2026/001', 'Jalan Usaha Tani Rabat Beton Babakan', 1, 'jalan_jembatan', 'Panjang 450m x Lebar 2.5m x Tebal 15cm', 'Dusun Babakan RT 02 / RW 03', '2026-03-25', 'DDS', '145000000.00', 'baik', 'dikelola_desa', 'Kaur Pembangunan & Kepala Dusun Babakan', 'Konstruksi beton K-225 bertulang wiremesh, menghubungkan sentra pertanian ke jalan utama', NULL, NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18'),
(2, 2026, 'INV-BANG/2026/002', 'Sarana MCK & Sumur Bor Komunal Dusun 1', NULL, 'sarana_air_bersih', '1 Unit Bangunan 4 Pintu & Toren 1.500L', 'Kp. Sukamaju RW 01 RT 04', '2026-02-15', 'Bantuan Provinsi', '48000000.00', 'baik', 'diserahkan_ke_masyarakat', 'Ketua RW 01 Kp. Sukamaju', 'Fasilitas sanitasi publik untuk 35 KK warga padat permukiman', NULL, NULL, 1, '2026-08-30 14:04:18', '2026-08-30 14:04:18');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `schedules`
--

DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `tag` VARCHAR(255) NOT NULL DEFAULT 'Persuratan',
  `time` VARCHAR(255) NOT NULL DEFAULT '09:00 WIB',
  `date` DATE NULL DEFAULT NULL,
  `pic` VARCHAR(255) NULL DEFAULT NULL,
  `user_id` INT NULL DEFAULT NULL,
  `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
  `order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `schedules`
--

INSERT INTO `schedules` (`id`, `title`, `description`, `tag`, `time`, `date`, `pic`, `user_id`, `is_completed`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Pelayanan Surat Keterangan Usaha (SKU)', '3 Berkas pemohon walk-in desk', 'Persuratan', '09:30 WIB', NULL, 'Staff Pelayanan', NULL, 0, 1, '2026-08-31 09:21:36', '2026-08-31 09:21:36'),
(2, 'Verifikasi Duplikasi NIK Kependudukan', 'Sinkronisasi data RT 02 / RW 01', 'Kependudukan', '11:00 WIB', NULL, 'Admin Desa', NULL, 0, 2, '2026-08-31 09:21:36', '2026-08-31 09:21:36'),
(3, 'Cetak Rekap Buku Ekspedisi & Agenda', 'Penutupan buku register bulan berjalan', 'Administrasi', '14:00 WIB', NULL, 'Sekretariat', NULL, 0, 3, '2026-08-31 09:21:36', '2026-08-31 09:21:36'),
(4, 'Monitoring Proyek Fisik RKP Desa', 'Inspeksi pembangunan posyandu Dusun 2', 'Pembangunan', '15:30 WIB', NULL, 'TPK Desa', NULL, 0, 4, '2026-08-31 09:21:36', '2026-08-31 09:21:36'),
(5, 'kunjungan', 'meninjau pembangunan', 'Kependudukan', '07.00', NULL, 'franklyn', 1, 0, 5, '2026-08-31 09:26:27', '2026-08-31 09:26:27');

-- --------------------------------------------------------

--
-- Struktur tabel untuk `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` VARCHAR(255) NULL DEFAULT NULL,
  `description` TEXT NOT NULL,
  `subject_type` VARCHAR(255) NULL DEFAULT NULL,
  `subject_id` INT NULL DEFAULT NULL,
  `causer_type` VARCHAR(255) NULL DEFAULT NULL,
  `causer_id` INT NULL DEFAULT NULL,
  `properties` JSON NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `event` VARCHAR(255) NULL DEFAULT NULL,
  `batch_uuid` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `subject_id`, `causer_type`, `causer_id`, `properties`, `created_at`, `updated_at`, `event`, `batch_uuid`) VALUES
(1, 'desa_profile', 'Profil desa Desa Sukamaju was created', 'App\\Models\\DesaProfile', 1, NULL, NULL, '{\"attributes\": {\"id\": 1, \"website\": \"https://desa-sukamaju.id\", \"kode_pos\": \"43113\", \"provinsi\": \"Jawa Barat\", \"kabupaten\": \"Sukabumi\", \"kecamatan\": \"Cikole\", \"kode_desa\": \"3202112001\", \"logo_path\": null, \"nama_desa\": \"Desa Sukamaju\", \"nik_kades\": \"3202111708750001\", \"nip_kades\": \"197508172005011003\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"email_desa\": \"kontak@desa-sukamaju.id\", \"nama_kades\": \"H. Rahmat Hidayat, S.IP\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"telepon_desa\": \"0266-221144\", \"alamat_kantor\": \"Jl. Raya Sukamaju No. 01, Kec. Cikole, Kab. Sukabumi\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(2, 'user_management', 'User Administrator Desa Sukamaju was created', 'App\\Models\\User', 1, NULL, NULL, '{\"attributes\": {\"name\": \"Administrator Desa Sukamaju\", \"email\": \"admin@desa.id\", \"phone\": \"081234567890\", \"is_active\": true}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(3, 'user_management', 'User Jack Grealish was created', 'App\\Models\\User', 2, NULL, NULL, '{\"attributes\": {\"name\": \"Jack Grealish\", \"email\": \"staff@desa.id\", \"phone\": \"081298765432\", \"is_active\": true}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(4, 'kependudukan', 'Data kependudukan Asep Suhendar (NIK: 320211******0001) was created', 'App\\Models\\Penduduk', 1, NULL, NULL, '{\"attributes\": {\"id\": 1, \"rt\": \"001\", \"rw\": \"002\", \"nik\": \"3202111504700001\", \"agama\": \"Islam\", \"dusun\": \"Cikole\", \"no_kk\": \"3202111504700001\", \"telepon\": \"082112340001\", \"pekerjaan\": \"Petani\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"sumber_data\": \"prodeskel\", \"nama_lengkap\": \"Asep Suhendar\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"1970-04-15T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Cikole RT 001 RW 002 Desa Sukamaju\", \"golongan_darah\": \"O\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"SLTA/Sederajat\", \"status_dalam_keluarga\": \"kepala_keluarga\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(5, 'kependudukan', 'Data kependudukan Siti Rohmah (NIK: 320211******0001) was created', 'App\\Models\\Penduduk', 2, NULL, NULL, '{\"attributes\": {\"id\": 2, \"rt\": \"001\", \"rw\": \"002\", \"nik\": \"3202114508730001\", \"agama\": \"Islam\", \"dusun\": \"Cikole\", \"no_kk\": \"3202111504700001\", \"telepon\": null, \"pekerjaan\": \"Ibu Rumah Tangga\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"sumber_data\": \"prodeskel\", \"nama_lengkap\": \"Siti Rohmah\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"P\", \"tanggal_lahir\": \"1973-08-05T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Cikole RT 001 RW 002 Desa Sukamaju\", \"golongan_darah\": \"A\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"SLTP/Sederajat\", \"status_dalam_keluarga\": \"istri\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(6, 'kependudukan', 'Data kependudukan Rizki Maulana Suhendar (NIK: 320211******0001) was created', 'App\\Models\\Penduduk', 3, NULL, NULL, '{\"attributes\": {\"id\": 3, \"rt\": \"001\", \"rw\": \"002\", \"nik\": \"3202110206050001\", \"agama\": \"Islam\", \"dusun\": \"Cikole\", \"no_kk\": \"3202111504700001\", \"telepon\": \"083112340001\", \"pekerjaan\": \"Pelajar/Mahasiswa\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Rizki Maulana Suhendar\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"2005-06-02T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Cikole RT 001 RW 002 Desa Sukamaju\", \"golongan_darah\": \"O\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"belum_kawin\", \"pendidikan_terakhir\": \"SLTA/Sederajat\", \"status_dalam_keluarga\": \"anak\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(7, 'kependudukan', 'Data kependudukan Nur Fadilah Suhendar (NIK: 320211******0001) was created', 'App\\Models\\Penduduk', 4, NULL, NULL, '{\"attributes\": {\"id\": 4, \"rt\": \"001\", \"rw\": \"002\", \"nik\": \"3202114910130001\", \"agama\": \"Islam\", \"dusun\": \"Cikole\", \"no_kk\": \"3202111504700001\", \"telepon\": null, \"pekerjaan\": \"Pelajar/Mahasiswa\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Nur Fadilah Suhendar\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"P\", \"tanggal_lahir\": \"2013-10-09T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Cikole RT 001 RW 002 Desa Sukamaju\", \"golongan_darah\": \"A\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"belum_kawin\", \"pendidikan_terakhir\": \"Belum Tamat SD\", \"status_dalam_keluarga\": \"anak\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(8, 'kependudukan', 'Data kependudukan H. Dedi Supriadi (NIK: 320211******0002) was created', 'App\\Models\\Penduduk', 5, NULL, NULL, '{\"attributes\": {\"id\": 5, \"rt\": \"003\", \"rw\": \"001\", \"nik\": \"3202111203800002\", \"agama\": \"Islam\", \"dusun\": \"Sukasari\", \"no_kk\": \"3202111203800002\", \"telepon\": \"081234567890\", \"pekerjaan\": \"Pedagang\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"sumber_data\": \"prodeskel\", \"nama_lengkap\": \"H. Dedi Supriadi\", \"tempat_lahir\": \"Cianjur\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"1980-03-12T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Sukasari RT 003 RW 001 Desa Sukamaju\", \"golongan_darah\": \"B\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"Diploma IV/S1\", \"status_dalam_keluarga\": \"kepala_keluarga\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(9, 'kependudukan', 'Data kependudukan Hj. Imas Nuraeni (NIK: 320211******0002) was created', 'App\\Models\\Penduduk', 6, NULL, NULL, '{\"attributes\": {\"id\": 6, \"rt\": \"003\", \"rw\": \"001\", \"nik\": \"3202115304830002\", \"agama\": \"Islam\", \"dusun\": \"Sukasari\", \"no_kk\": \"3202111203800002\", \"telepon\": null, \"pekerjaan\": \"Ibu Rumah Tangga\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"sumber_data\": \"prodeskel\", \"nama_lengkap\": \"Hj. Imas Nuraeni\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"P\", \"tanggal_lahir\": \"1983-04-13T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Sukasari RT 003 RW 001 Desa Sukamaju\", \"golongan_darah\": \"B\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"SLTA/Sederajat\", \"status_dalam_keluarga\": \"istri\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(10, 'kependudukan', 'Data kependudukan Muhamad Fauzan Supriadi (NIK: 320211******0002) was created', 'App\\Models\\Penduduk', 7, NULL, NULL, '{\"attributes\": {\"id\": 7, \"rt\": \"003\", \"rw\": \"001\", \"nik\": \"3202111507100002\", \"agama\": \"Islam\", \"dusun\": \"Sukasari\", \"no_kk\": \"3202111203800002\", \"telepon\": null, \"pekerjaan\": \"Pelajar/Mahasiswa\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Muhamad Fauzan Supriadi\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"2010-07-15T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Sukasari RT 003 RW 001 Desa Sukamaju\", \"golongan_darah\": \"A\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"belum_kawin\", \"pendidikan_terakhir\": \"SLTP/Sederajat\", \"status_dalam_keluarga\": \"anak\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(11, 'kependudukan', 'Data kependudukan Ade Suryadi (NIK: 320211******0003) was created', 'App\\Models\\Penduduk', 8, NULL, NULL, '{\"attributes\": {\"id\": 8, \"rt\": \"002\", \"rw\": \"004\", \"nik\": \"3202110809750003\", \"agama\": \"Islam\", \"dusun\": \"Karangtengah\", \"no_kk\": \"3202110809750003\", \"telepon\": \"087891234500\", \"pekerjaan\": \"Buruh Harian Lepas\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"sumber_data\": \"migrasi_legacy\", \"nama_lengkap\": \"Ade Suryadi\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"1975-09-08T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Karangtengah RT 002 RW 004 Desa Sukamaju\", \"golongan_darah\": \"AB\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"SLTA/Sederajat\", \"status_dalam_keluarga\": \"kepala_keluarga\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(12, 'kependudukan', 'Data kependudukan Ai Nuraeni Suryadi (NIK: 320211******0003) was created', 'App\\Models\\Penduduk', 9, NULL, NULL, '{\"attributes\": {\"id\": 9, \"rt\": \"002\", \"rw\": \"004\", \"nik\": \"3202114210780003\", \"agama\": \"Islam\", \"dusun\": \"Karangtengah\", \"no_kk\": \"3202110809750003\", \"telepon\": null, \"pekerjaan\": \"Ibu Rumah Tangga\", \"created_at\": \"2026-08-30T14:04:17.000000Z\", \"updated_at\": \"2026-08-30T14:04:17.000000Z\", \"sumber_data\": \"migrasi_legacy\", \"nama_lengkap\": \"Ai Nuraeni Suryadi\", \"tempat_lahir\": \"Bandung\", \"jenis_kelamin\": \"P\", \"tanggal_lahir\": \"1978-10-02T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Karangtengah RT 002 RW 004 Desa Sukamaju\", \"golongan_darah\": null, \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"SLTP/Sederajat\", \"status_dalam_keluarga\": \"istri\"}}', '2026-08-30 14:04:17', '2026-08-30 14:04:17', 'created', NULL),
(13, 'kependudukan', 'Data kependudukan Yudi Permana (NIK: 320211******0004) was created', 'App\\Models\\Penduduk', 10, NULL, NULL, '{\"attributes\": {\"id\": 10, \"rt\": \"001\", \"rw\": \"003\", \"nik\": \"3202111806920004\", \"agama\": \"Islam\", \"dusun\": \"Cikole\", \"no_kk\": \"3202111806920004\", \"telepon\": \"081298765432\", \"pekerjaan\": \"PNS\", \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Yudi Permana\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"1992-06-18T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Cikole RT 001 RW 003 Desa Sukamaju\", \"golongan_darah\": \"O\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"pindah\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"Diploma IV/S1\", \"status_dalam_keluarga\": \"kepala_keluarga\"}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(14, 'kependudukan', 'Data kependudukan Sarinem (NIK: 320211******0005) was created', 'App\\Models\\Penduduk', 11, NULL, NULL, '{\"attributes\": {\"id\": 11, \"rt\": \"002\", \"rw\": \"004\", \"nik\": \"3202118506450005\", \"agama\": \"Islam\", \"dusun\": \"Karangtengah\", \"no_kk\": \"3202110809750003\", \"telepon\": null, \"pekerjaan\": null, \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"sumber_data\": \"migrasi_legacy\", \"nama_lengkap\": \"Sarinem\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"P\", \"tanggal_lahir\": \"1945-06-25T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Karangtengah RT 002 RW 004 Desa Sukamaju\", \"golongan_darah\": null, \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"cerai_mati\", \"pendidikan_terakhir\": \"Tidak/Belum Sekolah\", \"status_dalam_keluarga\": \"famili_lain\"}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(15, 'kependudukan', 'Data kependudukan Budi Santoso (NIK: 320415******0001) was created', 'App\\Models\\Penduduk', 12, NULL, NULL, '{\"attributes\": {\"id\": 12, \"rt\": \"004\", \"rw\": \"001\", \"nik\": \"3204150703950001\", \"agama\": \"Islam\", \"dusun\": \"Sukasari\", \"no_kk\": \"3204150703950001\", \"telepon\": \"089512345678\", \"pekerjaan\": \"Wiraswasta\", \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Budi Santoso\", \"tempat_lahir\": \"Bogor\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"1995-03-07T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Sukasari RT 004 RW 001 Desa Sukamaju\", \"golongan_darah\": \"A\", \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"belum_kawin\", \"pendidikan_terakhir\": \"Diploma IV/S1\", \"status_dalam_keluarga\": \"kepala_keluarga\"}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(16, 'kependudukan', 'Data kependudukan Jajang Kurniawan (NIK: 320211******0006) was created', 'App\\Models\\Penduduk', 13, NULL, NULL, '{\"attributes\": {\"id\": 13, \"rt\": \"005\", \"rw\": \"001\", \"nik\": \"3202110203880006\", \"agama\": \"Islam\", \"dusun\": \"Cikole\", \"no_kk\": \"3202110203880000\", \"telepon\": null, \"pekerjaan\": \"Petani\", \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Jajang Kurniawan\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"1988-03-02T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Cikole RT 005 RW 001 Desa Sukamaju\", \"golongan_darah\": null, \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"SLTA/Sederajat\", \"status_dalam_keluarga\": \"kepala_keluarga\"}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(17, 'kependudukan', 'Data kependudukan Dewi Rahayu (NIK: 320211******0006) was created', 'App\\Models\\Penduduk', 14, NULL, NULL, '{\"attributes\": {\"id\": 14, \"rt\": \"005\", \"rw\": \"001\", \"nik\": \"3202115504910006\", \"agama\": \"Islam\", \"dusun\": \"Cikole\", \"no_kk\": \"3202110203880000\", \"telepon\": null, \"pekerjaan\": \"Pedagang\", \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Dewi Rahayu\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"P\", \"tanggal_lahir\": \"1991-04-15T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Cikole RT 005 RW 001 Desa Sukamaju\", \"golongan_darah\": null, \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"SLTA/Sederajat\", \"status_dalam_keluarga\": \"istri\"}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(18, 'kependudukan', 'Data kependudukan Ikhsan Maulana (NIK: 320211******0006) was created', 'App\\Models\\Penduduk', 15, NULL, NULL, '{\"attributes\": {\"id\": 15, \"rt\": \"005\", \"rw\": \"001\", \"nik\": \"3202112501200006\", \"agama\": \"Islam\", \"dusun\": \"Cikole\", \"no_kk\": \"3202110203880000\", \"telepon\": null, \"pekerjaan\": null, \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Ikhsan Maulana\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"2020-01-25T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Cikole RT 005 RW 001 Desa Sukamaju\", \"golongan_darah\": null, \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"belum_kawin\", \"pendidikan_terakhir\": null, \"status_dalam_keluarga\": \"anak\"}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(19, 'kependudukan', 'Data kependudukan Dedi Wahyudi (NIK: 320211******0007) was created', 'App\\Models\\Penduduk', 16, NULL, NULL, '{\"attributes\": {\"id\": 16, \"rt\": \"006\", \"rw\": \"002\", \"nik\": \"3202111012960007\", \"agama\": \"Islam\", \"dusun\": \"Sukasari\", \"no_kk\": \"3202111012960000\", \"telepon\": null, \"pekerjaan\": \"Karyawan Swasta\", \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Dedi Wahyudi\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"1996-12-10T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Sukasari RT 006 RW 002 Desa Sukamaju\", \"golongan_darah\": null, \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"belum_kawin\", \"pendidikan_terakhir\": \"SLTA/Sederajat\", \"status_dalam_keluarga\": \"kepala_keluarga\"}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(20, 'kependudukan', 'Data kependudukan Tini Andriani (NIK: 320211******0008) was created', 'App\\Models\\Penduduk', 17, NULL, NULL, '{\"attributes\": {\"id\": 17, \"rt\": \"007\", \"rw\": \"003\", \"nik\": \"3202114903840008\", \"agama\": \"Islam\", \"dusun\": \"Karangtengah\", \"no_kk\": \"3202114903840000\", \"telepon\": null, \"pekerjaan\": \"Guru\", \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Tini Andriani\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"P\", \"tanggal_lahir\": \"1984-03-09T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Karangtengah RT 007 RW 003 Desa Sukamaju\", \"golongan_darah\": null, \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"kawin\", \"pendidikan_terakhir\": \"Diploma IV/S1\", \"status_dalam_keluarga\": \"kepala_keluarga\"}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(21, 'kependudukan', 'Data kependudukan Ahmad Fauzi Ramadan (NIK: 320211******0009) was created', 'App\\Models\\Penduduk', 18, NULL, NULL, '{\"attributes\": {\"id\": 18, \"rt\": \"007\", \"rw\": \"003\", \"nik\": \"3202110502010009\", \"agama\": \"Islam\", \"dusun\": \"Karangtengah\", \"no_kk\": \"3202114903840000\", \"telepon\": null, \"pekerjaan\": \"Pelajar/Mahasiswa\", \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"sumber_data\": \"manual\", \"nama_lengkap\": \"Ahmad Fauzi Ramadan\", \"tempat_lahir\": \"Sukabumi\", \"jenis_kelamin\": \"L\", \"tanggal_lahir\": \"2001-02-05T00:00:00.000000Z\", \"alamat_lengkap\": \"Kp. Karangtengah RT 007 RW 003 Desa Sukamaju\", \"golongan_darah\": null, \"kewarganegaraan\": \"WNI\", \"status_penduduk\": \"tetap\", \"status_perkawinan\": \"belum_kawin\", \"pendidikan_terakhir\": \"SLTA/Sederajat\", \"status_dalam_keluarga\": \"anak\"}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(22, 'mutasi_penduduk', 'created', 'App\\Models\\PendudukMutasi', 1, NULL, NULL, '{\"attributes\": {\"id\": 1, \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"created_by\": 2, \"keterangan\": \"Pindah ke Kota Bogor mengikuti penempatan kerja PNS.\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"penduduk_id\": 10, \"jenis_mutasi\": \"pindah_keluar\", \"tanggal_mutasi\": \"2026-03-15T00:00:00.000000Z\", \"berkas_pendukung_path\": null}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(23, 'mutasi_penduduk', 'created', 'App\\Models\\PendudukMutasi', 2, NULL, NULL, '{\"attributes\": {\"id\": 2, \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"created_by\": 2, \"keterangan\": \"Kelahiran di RSUD Sukabumi. Berat badan 3,2 kg.\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"penduduk_id\": 15, \"jenis_mutasi\": \"lahir\", \"tanggal_mutasi\": \"2020-01-25T00:00:00.000000Z\", \"berkas_pendukung_path\": null}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(24, 'mutasi_penduduk', 'created', 'App\\Models\\PendudukMutasi', 3, NULL, NULL, '{\"attributes\": {\"id\": 3, \"created_at\": \"2026-08-30T14:04:18.000000Z\", \"created_by\": 2, \"keterangan\": \"Pindah masuk dari Kab. Bogor. Membuka usaha konveksi di desa.\", \"updated_at\": \"2026-08-30T14:04:18.000000Z\", \"penduduk_id\": 12, \"jenis_mutasi\": \"pindah_masuk\", \"tanggal_mutasi\": \"2025-11-01T00:00:00.000000Z\", \"berkas_pendukung_path\": null}}', '2026-08-30 14:04:18', '2026-08-30 14:04:18', 'created', NULL),
(25, 'auth', 'Pengguna Administrator Desa Sukamaju berhasil masuk ke sistem.', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '[]', '2026-08-30 14:08:39', '2026-08-30 14:08:39', NULL, NULL),
(26, 'auth', 'Pengguna Administrator Desa Sukamaju berhasil masuk ke sistem.', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '[]', '2026-08-30 14:08:40', '2026-08-30 14:08:40', NULL, NULL),
(27, 'auth', 'Pengguna Administrator Desa Sukamaju berhasil masuk ke sistem.', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '[]', '2026-08-31 08:27:09', '2026-08-31 08:27:09', NULL, NULL),
(28, 'auth', 'Pengguna Administrator Desa Sukamaju berhasil masuk ke sistem.', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '[]', '2026-09-02 04:33:21', '2026-09-02 04:33:21', NULL, NULL),
(29, 'auth', 'Pengguna Administrator Desa Sukamaju keluar dari sistem.', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '[]', '2026-09-02 05:06:58', '2026-09-02 05:06:58', NULL, NULL),
(30, 'auth', 'Pengguna Jack Grealish berhasil masuk ke sistem.', 'App\\Models\\User', 2, 'App\\Models\\User', 2, '[]', '2026-09-02 05:07:02', '2026-09-02 05:07:02', NULL, NULL),
(31, 'auth', 'Pengguna Jack Grealish keluar dari sistem.', 'App\\Models\\User', 2, 'App\\Models\\User', 2, '[]', '2026-09-02 05:07:13', '2026-09-02 05:07:13', NULL, NULL),
(32, 'auth', 'Pengguna Administrator Desa Sukamaju berhasil masuk ke sistem.', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '[]', '2026-09-02 05:07:18', '2026-09-02 05:07:18', NULL, NULL),
(33, 'auth', 'Pengguna Administrator Desa Sukamaju berhasil masuk ke sistem.', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '[]', '2026-09-02 09:33:06', '2026-09-02 09:33:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur tabel untuk `backup_records`
--

DROP TABLE IF EXISTS `backup_records`;
CREATE TABLE `backup_records` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL DEFAULT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `size_bytes` INT NOT NULL DEFAULT 0,
  `status` VARCHAR(255) NOT NULL DEFAULT 'success',
  `backup_type` VARCHAR(255) NOT NULL DEFAULT 'database',
  `error_message` TEXT NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
