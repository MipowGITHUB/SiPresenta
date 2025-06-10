-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 05, 2025 at 10:09 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sipresenta`
--

-- --------------------------------------------------------

--
-- Table structure for table `alat_presensis`
--

CREATE TABLE `alat_presensis` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_alat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alat_rfids`
--

CREATE TABLE `alat_rfids` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_alat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asistens`
--

CREATE TABLE `asistens` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rfid_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asistens`
--

INSERT INTO `asistens` (`id`, `nama`, `nim`, `rfid_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Wahyu Andika', '2023103703111075', '1', '2025-05-05 14:15:37', '2025-05-05 14:15:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1746479137),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1746479137;', 1746479137);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kehadiran_asistens`
--

CREATE TABLE `kehadiran_asistens` (
  `id` bigint UNSIGNED NOT NULL,
  `asisten_id` bigint UNSIGNED NOT NULL,
  `pertemuan_id` bigint UNSIGNED NOT NULL,
  `alat_rfid_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` enum('Hadir','Izin','Alpha') COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `waktu_keluar` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kehadiran_praktikans`
--

CREATE TABLE `kehadiran_praktikans` (
  `id` bigint UNSIGNED NOT NULL,
  `praktikan_id` bigint UNSIGNED NOT NULL,
  `pertemuan_id` bigint UNSIGNED NOT NULL,
  `alat_presensi_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` enum('Hadir','Izin','Alpha') COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `waktu_keluar` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas_asistens`
--

CREATE TABLE `kelas_asistens` (
  `id` bigint UNSIGNED NOT NULL,
  `asisten_id` bigint UNSIGNED NOT NULL,
  `praktikum_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas_praktikans`
--

CREATE TABLE `kelas_praktikans` (
  `id` bigint UNSIGNED NOT NULL,
  `praktikan_id` bigint UNSIGNED NOT NULL,
  `praktikum_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_05_183156_create_praktikans_table', 1),
(5, '2025_05_05_183222_create_asistens_table', 1),
(6, '2025_05_05_183243_create_praktikums_table', 1),
(7, '2025_05_05_183258_create_pertemuans_table', 1),
(8, '2025_05_05_184006_create_kehadiran_praktikans_table', 1),
(9, '2025_05_05_184017_create_kehadiran_asistens_table', 1),
(10, '2025_05_05_184028_create_alat_presensis_table', 1),
(11, '2025_05_05_185657_create_alat_rfids_table', 1),
(12, '2025_05_05_201527_create_kelas_praktikans_table', 1),
(13, '2025_05_05_201538_create_kelas_asistens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pertemuans`
--

CREATE TABLE `pertemuans` (
  `id` bigint UNSIGNED NOT NULL,
  `praktikum_id` bigint UNSIGNED NOT NULL,
  `pertemuan_ke` int NOT NULL,
  `modul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kegiatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `praktikans`
--

CREATE TABLE `praktikans` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fingerprint_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `praktikans`
--

INSERT INTO `praktikans` (`id`, `nama`, `nim`, `fingerprint_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ahmad Nur M', '202310370311089', '2', '2025-05-05 14:05:54', '2025-05-05 14:06:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `praktikums`
--

CREATE TABLE `praktikums` (
  `id` bigint UNSIGNED NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matkul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lab` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `praktikums`
--

INSERT INTO `praktikums` (`id`, `kelas`, `matkul`, `lab`, `waktu_mulai`, `waktu_selesai`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'B', 'Pemrograman Berbasis Obyek', 'A / B', '15:15:00', '16:55:00', '2025-05-05 14:12:07', '2025-05-05 14:12:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('cH4XLcZM6om19hlV8GZ0cwhcSiKoqV7RacGgtWaj', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiRGtrQUlCRERMSFFBaXpvdFA5RXBjYktKRDlXcHY3ZmExcDFMSnJTOCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjU1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4va2VoYWRpcmFuLXByYWt0aWthbnMvY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJGE4VUpCOEpZdGdwNWd4WDNEVUpMbk9tRlBlakxmT3h0dnh4Q0FpUVRiZ2dlQ3NOMm9yT3MyIjtzOjg6ImZpbGFtZW50IjthOjA6e319', 1746482115);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'T3am SiPresenta', 'adminsipresenta@gmail.com', NULL, '$2y$12$a8UJB8JYtgp5gxX3DUJLnOmFPejLfOxtvxxCAiQTbggeCsN2orOs2', NULL, '2025-05-05 14:04:23', '2025-05-05 14:04:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alat_presensis`
--
ALTER TABLE `alat_presensis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alat_presensis_device_id_unique` (`device_id`);

--
-- Indexes for table `alat_rfids`
--
ALTER TABLE `alat_rfids`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alat_rfids_uid_unique` (`uid`);

--
-- Indexes for table `asistens`
--
ALTER TABLE `asistens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asistens_nim_unique` (`nim`),
  ADD UNIQUE KEY `asistens_rfid_id_unique` (`rfid_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kehadiran_asistens`
--
ALTER TABLE `kehadiran_asistens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kehadiran_asistens_asisten_id_foreign` (`asisten_id`),
  ADD KEY `kehadiran_asistens_pertemuan_id_foreign` (`pertemuan_id`);

--
-- Indexes for table `kehadiran_praktikans`
--
ALTER TABLE `kehadiran_praktikans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kehadiran_praktikans_praktikan_id_foreign` (`praktikan_id`),
  ADD KEY `kehadiran_praktikans_pertemuan_id_foreign` (`pertemuan_id`);

--
-- Indexes for table `kelas_asistens`
--
ALTER TABLE `kelas_asistens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_asistens_asisten_id_foreign` (`asisten_id`),
  ADD KEY `kelas_asistens_praktikum_id_foreign` (`praktikum_id`);

--
-- Indexes for table `kelas_praktikans`
--
ALTER TABLE `kelas_praktikans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelas_praktikans_praktikan_id_foreign` (`praktikan_id`),
  ADD KEY `kelas_praktikans_praktikum_id_foreign` (`praktikum_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pertemuans`
--
ALTER TABLE `pertemuans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pertemuans_praktikum_id_foreign` (`praktikum_id`);

--
-- Indexes for table `praktikans`
--
ALTER TABLE `praktikans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `praktikans_nim_unique` (`nim`),
  ADD UNIQUE KEY `praktikans_fingerprint_id_unique` (`fingerprint_id`);

--
-- Indexes for table `praktikums`
--
ALTER TABLE `praktikums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alat_presensis`
--
ALTER TABLE `alat_presensis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alat_rfids`
--
ALTER TABLE `alat_rfids`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asistens`
--
ALTER TABLE `asistens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kehadiran_asistens`
--
ALTER TABLE `kehadiran_asistens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kehadiran_praktikans`
--
ALTER TABLE `kehadiran_praktikans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas_asistens`
--
ALTER TABLE `kelas_asistens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas_praktikans`
--
ALTER TABLE `kelas_praktikans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pertemuans`
--
ALTER TABLE `pertemuans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `praktikans`
--
ALTER TABLE `praktikans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `praktikums`
--
ALTER TABLE `praktikums`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kehadiran_asistens`
--
ALTER TABLE `kehadiran_asistens`
  ADD CONSTRAINT `kehadiran_asistens_asisten_id_foreign` FOREIGN KEY (`asisten_id`) REFERENCES `asistens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kehadiran_asistens_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kehadiran_praktikans`
--
ALTER TABLE `kehadiran_praktikans`
  ADD CONSTRAINT `kehadiran_praktikans_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kehadiran_praktikans_praktikan_id_foreign` FOREIGN KEY (`praktikan_id`) REFERENCES `praktikans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_asistens`
--
ALTER TABLE `kelas_asistens`
  ADD CONSTRAINT `kelas_asistens_asisten_id_foreign` FOREIGN KEY (`asisten_id`) REFERENCES `asistens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_asistens_praktikum_id_foreign` FOREIGN KEY (`praktikum_id`) REFERENCES `praktikums` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_praktikans`
--
ALTER TABLE `kelas_praktikans`
  ADD CONSTRAINT `kelas_praktikans_praktikan_id_foreign` FOREIGN KEY (`praktikan_id`) REFERENCES `praktikans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_praktikans_praktikum_id_foreign` FOREIGN KEY (`praktikum_id`) REFERENCES `praktikums` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pertemuans`
--
ALTER TABLE `pertemuans`
  ADD CONSTRAINT `pertemuans_praktikum_id_foreign` FOREIGN KEY (`praktikum_id`) REFERENCES `praktikums` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
