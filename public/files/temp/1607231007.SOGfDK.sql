-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Des 2020 pada 03.16
-- Versi server: 8.0.22
-- Versi PHP: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bounche_parking_system_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `gates`
--

CREATE TABLE `gates` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `gates`
--

INSERT INTO `gates` (`id`, `created_at`, `updated_at`, `name`, `description`) VALUES
(1, '2020-12-01 16:04:48', '2020-12-01 16:04:48', 'GATE 1', NULL),
(2, '2020-12-01 16:05:00', '2020-12-01 16:05:09', 'GATE 2', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2020_11_29_072540_create_roles_table', 2),
(10, '2020_11_29_073725_create_modules_table', 2),
(11, '2020_11_29_074230_create_permissions_table', 2),
(12, '2020_11_30_054803_add_gender_and_photo_column_on_users_table', 3),
(13, '2020_12_01_103225_create_role_permission_table', 4),
(16, '2020_12_01_130027_add_is_permit_all_column_on_roles_table', 5),
(17, '2020_12_01_225627_create_gates_table', 6),
(18, '2020_12_02_061458_create_parking_fees_table', 7),
(20, '2020_12_02_125245_create_parkings_table', 8),
(21, '2020_12_03_010907_create_user_role_table', 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `modules`
--

CREATE TABLE `modules` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `modules`
--

INSERT INTO `modules` (`id`, `created_at`, `updated_at`, `name`, `display_name`, `description`) VALUES
(1, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 'users', 'Users', NULL),
(2, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 'roles', 'Roles', NULL),
(3, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 'modules', 'Modules', NULL),
(4, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 'permissions', 'Permissions', NULL),
(5, '2020-12-02 19:56:19', '2020-12-02 19:56:19', 'gates', 'Gates', NULL),
(6, '2020-12-02 19:56:35', '2020-12-02 19:56:35', 'parking-fees', 'Parking Fees', NULL),
(7, '2020-12-02 19:56:48', '2020-12-02 19:56:48', 'parkings', 'Parkings', NULL),
(8, '2020-12-02 19:57:03', '2020-12-02 19:57:03', 'parking-lists', 'Parking Lists', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `parkings`
--

CREATE TABLE `parkings` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_type` enum('motorbikes','cars') COLLATE utf8mb4_unicode_ci NOT NULL,
  `vehicle_police_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parking_fee` double NOT NULL,
  `status` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in',
  `out_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `parkings`
--

INSERT INTO `parkings` (`id`, `created_at`, `updated_at`, `code`, `vehicle_type`, `vehicle_police_number`, `parking_fee`, `status`, `out_at`) VALUES
(1, '2020-12-02 06:31:58', '2020-12-02 06:31:58', 'cNBXXg3zlH', 'motorbikes', 'K123H', 3000, 'out', NULL),
(2, '2020-12-02 06:33:13', '2020-12-02 06:33:13', 'fRIiI6hb2w', 'motorbikes', 'K7788H', 3000, 'in', NULL),
(3, '2020-12-02 06:37:27', '2020-12-02 06:37:27', 'SXnCmHnsog', 'motorbikes', 'K123H', 3000, 'in', NULL),
(4, '2020-12-02 06:38:18', '2020-12-02 06:38:18', 'ZGO6VC7XJR', 'motorbikes', 'H666L', 3000, 'in', NULL),
(5, '2020-12-02 15:21:12', '2020-12-02 15:21:12', 'FH3V29RUUO', 'cars', 'HAYOOO', 3000, 'in', NULL),
(6, '2020-12-02 15:21:19', '2020-12-02 17:33:12', 'OSM2TRIM41', 'cars', 'HAYOOOO', 3000, 'out', '2020-12-03 00:33:12'),
(8, '2020-12-02 17:33:22', '2020-12-02 17:33:22', '3SKKFMEFR5', 'motorbikes', 'HAYOOOO', 3000, 'in', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `parking_fees`
--

CREATE TABLE `parking_fees` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vehicle_type` enum('motorbikes','cars') COLLATE utf8mb4_unicode_ci NOT NULL,
  `parking_fee` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `parking_fees`
--

INSERT INTO `parking_fees` (`id`, `created_at`, `updated_at`, `vehicle_type`, `parking_fee`) VALUES
(1, '2020-12-02 03:25:00', '2020-12-02 03:25:00', 'motorbikes', 3000),
(2, '2020-12-02 03:28:06', '2020-12-02 03:28:06', 'cars', 3000),
(3, '2020-12-02 03:57:12', '2020-12-02 03:57:12', 'cars', 5000),
(4, '2020-12-02 04:33:30', '2020-12-02 04:33:30', 'cars', 3000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `module_id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`id`, `created_at`, `updated_at`, `module_id`, `name`, `display_name`, `description`) VALUES
(1, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 1, 'users-read', 'read', NULL),
(2, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 1, 'users-create', 'create', NULL),
(3, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 1, 'users-update', 'update', NULL),
(4, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 1, 'users-delete', 'delete', NULL),
(5, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 2, 'roles-read', 'read', NULL),
(6, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 2, 'roles-create', 'create', NULL),
(7, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 2, 'roles-update', 'update', NULL),
(8, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 2, 'roles-delete', 'delete', NULL),
(9, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 3, 'modules-read', 'read', NULL),
(10, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 3, 'modules-create', 'create', NULL),
(11, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 3, 'modules-update', 'update', NULL),
(12, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 3, 'modules-delete', 'delete', NULL),
(13, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 4, 'permissions-read', 'read', NULL),
(14, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 4, 'permissions-create', 'create', NULL),
(15, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 4, 'permissions-update', 'update', NULL),
(16, '2020-12-02 19:51:47', '2020-12-02 19:51:47', 4, 'permissions-delete', 'delete', NULL),
(17, '2020-12-02 19:57:29', '2020-12-02 19:57:29', 7, 'parkings-read', 'read', NULL),
(18, '2020-12-02 19:57:40', '2020-12-02 19:57:40', 7, 'parkings-create', 'create', NULL),
(19, '2020-12-02 19:57:50', '2020-12-02 19:57:50', 7, 'parkings-update', 'update', NULL),
(20, '2020-12-02 19:57:58', '2020-12-02 19:57:58', 7, 'parkings-delete', 'delete', NULL),
(21, '2020-12-02 20:00:38', '2020-12-02 20:00:38', 8, 'parking-lists-read', 'read', NULL),
(22, '2020-12-02 20:00:51', '2020-12-02 20:00:51', 8, 'parking-lists-update', 'update', NULL),
(23, '2020-12-02 20:01:17', '2020-12-02 20:01:17', 6, 'parking-fees-read', 'read', NULL),
(24, '2020-12-02 20:01:26', '2020-12-02 20:01:26', 6, 'parking-fees-update', 'update', NULL),
(25, '2020-12-02 20:02:11', '2020-12-02 20:02:11', 5, 'gates-read', 'read', NULL),
(26, '2020-12-02 20:02:21', '2020-12-02 20:02:21', 5, 'gates-create', 'create', NULL),
(27, '2020-12-02 20:02:31', '2020-12-02 20:02:31', 5, 'gates-update', 'update', NULL),
(28, '2020-12-02 20:02:40', '2020-12-02 20:02:40', 5, 'gates-delete', 'delete', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_permit_all` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `created_at`, `updated_at`, `name`, `display_name`, `description`, `is_permit_all`) VALUES
(1, '2020-11-29 22:16:06', '2020-12-02 20:03:02', 'ADMINISTRATOR', 'ADMINISTRATOR', NULL, 1),
(2, '2020-11-30 00:02:22', '2020-12-02 19:47:48', 'USER', 'USER', NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_permission`
--

CREATE TABLE `role_permission` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint NOT NULL,
  `permission_id` bigint NOT NULL,
  `module_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `module_id`) VALUES
(17, 1, 1, 1),
(18, 1, 2, 1),
(19, 1, 3, 1),
(20, 1, 4, 1),
(21, 1, 5, 2),
(22, 1, 6, 2),
(23, 1, 7, 2),
(24, 1, 8, 2),
(25, 1, 9, 3),
(26, 1, 10, 3),
(27, 1, 11, 3),
(28, 1, 12, 3),
(29, 1, 13, 4),
(30, 1, 14, 4),
(31, 1, 15, 4),
(32, 1, 16, 4),
(33, 1, 17, 7),
(34, 1, 18, 7),
(35, 1, 19, 7),
(36, 1, 20, 7),
(37, 1, 21, 8),
(38, 1, 22, 8),
(39, 1, 23, 6),
(40, 1, 24, 6),
(41, 1, 25, 5),
(42, 1, 26, 5),
(43, 1, 27, 5),
(44, 1, 28, 5),
(45, 2, 17, 7),
(46, 2, 18, 7),
(47, 2, 19, 7),
(48, 2, 20, 7),
(49, 2, 21, 8),
(50, 2, 22, 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `gender`, `photo`) VALUES
(1, 'ADMINISTRATOR', 'admin@mail.com', NULL, '$2y$10$CvonIgZ7O5l.vrl/Ex6dceWWHcv7maTs.cSEuh2jCEkWEIjDqJB1y', NULL, '2020-11-29 16:42:20', '2020-11-29 16:42:20', NULL, NULL),
(2, 'USER', 'user@mail.com', NULL, '$2y$10$bM8JJfv9bsMiNX30Zfm4N.wV9ZS2GaYL9Tj.ml6Z42Pa15zAjVuom', NULL, '2020-12-02 17:53:48', '2020-12-02 17:53:48', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_role`
--

CREATE TABLE `user_role` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `role_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`) VALUES
(3, 2, 2),
(4, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `gates`
--
ALTER TABLE `gates`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `parkings`
--
ALTER TABLE `parkings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parkings_code_unique` (`code`);

--
-- Indeks untuk tabel `parking_fees`
--
ALTER TABLE `parking_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `gates`
--
ALTER TABLE `gates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `parkings`
--
ALTER TABLE `parkings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `parking_fees`
--
ALTER TABLE `parking_fees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
