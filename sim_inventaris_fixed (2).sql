-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jul 2026 pada 16.18
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sim_inventaris`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrowings`
--

DROP TABLE IF EXISTS `borrowings`;
CREATE TABLE `borrowings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_peminjam` varchar(255) NOT NULL,
  `nomor_telepon` varchar(255) DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal_kembali_aktual` date DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan','terlambat') NOT NULL DEFAULT 'dipinjam',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `borrowings`
--

INSERT INTO `borrowings` (`id`, `nama_peminjam`, `nomor_telepon`, `processed_by`, `tanggal_pinjam`, `tanggal_kembali`, `keterangan`, `tanggal_kembali_aktual`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', NULL, 2, '2026-06-22', '2026-06-29', NULL, '2026-07-06', 'dikembalikan', '2026-07-02 11:12:16', '2026-07-06 01:24:07'),
(2, 'Siti Aminah', NULL, 2, '2026-06-07', '2026-06-12', NULL, NULL, 'dikembalikan', '2026-07-02 11:12:16', '2026-07-02 11:12:16'),
(3, 'hallo', NULL, 1, '2026-07-02', '2026-07-08', NULL, '2026-07-06', 'dikembalikan', '2026-07-02 07:13:50', '2026-07-06 01:26:14'),
(4, 'mala', NULL, 13, '2026-07-06', NULL, NULL, '2026-07-06', 'dikembalikan', '2026-07-06 02:05:41', '2026-07-06 02:07:06'),
(5, 'hallo12', '0910283749400', 13, '2026-07-06', '2026-07-15', NULL, '2026-07-06', 'dikembalikan', '2026-07-06 02:32:22', '2026-07-06 05:24:14'),
(6, 'Asti', '0910283749400', 13, '2026-07-06', '2026-07-06', NULL, NULL, 'dipinjam', '2026-07-06 07:00:40', '2026-07-06 07:00:40'),
(7, 'cscsc', 'cew24434', 13, '2026-07-07', '2026-07-10', 'dwdwd', NULL, 'dipinjam', '2026-07-07 10:47:07', '2026-07-07 10:47:07'),
(8, 'xaddd', '4567654', 13, '2026-07-07', '2026-07-15', 'dwetet', NULL, 'dipinjam', '2026-07-07 10:51:15', '2026-07-07 10:51:15'),
(9, 'csffs', '1234567', 13, '2026-07-07', '2026-07-16', 'xadwdsd', NULL, 'dipinjam', '2026-07-07 10:51:47', '2026-07-07 10:51:47'),
(10, 'wfetetet', '1234567', 13, '2026-07-07', '2026-07-16', 'wergthgd', NULL, 'dipinjam', '2026-07-07 10:52:18', '2026-07-07 10:52:18'),
(11, 'dsgrydr', '12345678', 13, '2026-07-07', '2026-07-14', 'sfddfd', NULL, 'dipinjam', '2026-07-07 10:52:50', '2026-07-07 10:52:50'),
(12, 'cknscksncks', '0910283749400', 13, '2026-07-07', '2026-07-16', 'dwdwd', '2026-07-09', 'dikembalikan', '2026-07-07 10:56:52', '2026-07-08 21:52:31'),
(13, 'dwdwd', '123456', 13, '2026-07-07', '2026-07-07', 'sdfghm', '2026-07-07', 'dikembalikan', '2026-07-07 11:09:41', '2026-07-07 11:10:22'),
(14, 'sddfsfsf', '234567', 13, '2026-07-05', '2026-07-05', 'dscfvbnh', '2026-07-07', 'dikembalikan', '2026-07-07 11:11:27', '2026-07-07 11:11:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `borrowing_details`
--

DROP TABLE IF EXISTS `borrowing_details`;
CREATE TABLE `borrowing_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `borrowing_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `kondisi_saat_kembali` varchar(255) DEFAULT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `borrowing_details`
--

INSERT INTO `borrowing_details` (`id`, `borrowing_id`, `product_id`, `jumlah`, `kondisi_saat_kembali`, `photos`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, NULL, NULL, '2026-07-02 11:12:17', '2026-07-02 11:12:17'),
(2, 2, 5, 1, NULL, NULL, NULL, '2026-07-02 11:12:17', '2026-07-02 11:12:17'),
(3, 3, 2, 1, NULL, NULL, NULL, '2026-07-02 07:13:50', '2026-07-02 07:13:50'),
(4, 3, 3, 1, NULL, NULL, NULL, '2026-07-02 07:13:50', '2026-07-02 07:13:50'),
(5, 4, 4, 1, NULL, NULL, NULL, '2026-07-06 02:05:41', '2026-07-06 02:05:41'),
(6, 5, 1, 1, 'baik', '[\"returns\\/JwC2xiQmHgi9lg5NkPJoL83QHPfLGLVDRYYLY20m.jpg\"]', NULL, '2026-07-06 02:32:22', '2026-07-06 05:24:14'),
(7, 6, 4, 1, NULL, NULL, NULL, '2026-07-06 07:00:40', '2026-07-06 07:00:40'),
(8, 7, 4, 1, NULL, NULL, NULL, '2026-07-07 10:47:07', '2026-07-07 10:47:07'),
(9, 8, 4, 1, NULL, NULL, NULL, '2026-07-07 10:51:15', '2026-07-07 10:51:15'),
(10, 9, 5, 1, NULL, NULL, NULL, '2026-07-07 10:51:47', '2026-07-07 10:51:47'),
(11, 9, 3, 5, NULL, NULL, NULL, '2026-07-07 10:51:47', '2026-07-07 10:51:47'),
(12, 10, 4, 1, NULL, NULL, NULL, '2026-07-07 10:52:18', '2026-07-07 10:52:18'),
(13, 11, 4, 1, NULL, NULL, NULL, '2026-07-07 10:52:50', '2026-07-07 10:52:50'),
(14, 12, 1, 1, 'rusak ringan', '[\"returns\\/UNMCxVhCJZGTgImH82viveccKhGkPyFfPxUsGNPH.jpg\"]', 'dwdwdwggf', '2026-07-07 10:56:52', '2026-07-08 21:52:31'),
(15, 13, 4, 1, 'baik', '[\"returns\\/M2CQtY9AR13sAK056oQEekJgyvCGBlTtf4tA8xTp.png\"]', 'sdfgh', '2026-07-07 11:09:41', '2026-07-07 11:10:22'),
(16, 14, 1, 1, 'baik', '[\"returns\\/bPotCxpzKTgtZ60SMCuP1iOeuXcDoBzYfpjSPCol.png\"]', 'xcvbn', '2026-07-07 11:11:27', '2026-07-07 11:11:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-password_reset_code_astisofiana25@gmail.com', 's:6:\"791888\";', 1783585675);

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', '2026-07-02 11:12:16', '2026-07-02 11:12:16'),
(2, 'Furnitur', '2026-07-02 11:12:16', '2026-07-02 11:12:16'),
(3, 'Alat Tulis Kantor', '2026-07-02 11:12:16', '2026-07-02 11:12:16'),
(4, 'Peralatan Jaringan', '2026-07-02 11:12:16', '2026-07-02 11:12:16'),
(5, 'Kendaraan', '2026-07-02 11:12:16', '2026-07-02 11:12:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'EMP001', '', 'staff', '2026-07-03 20:51:11', '2026-07-09 01:41:35'),
(2, 'EMP002', '', 'manager', '2026-07-03 20:51:11', '2026-07-09 01:41:35'),
(3, 'EMP003', '', 'staff', '2026-07-03 20:51:11', '2026-07-09 01:41:35'),
(4, 'EMP004', '', 'manager', '2026-07-03 20:51:11', '2026-07-09 01:41:35'),
(5, 'EMP005', 'yuki', 'staff', '2026-07-09 01:41:35', '2026-07-09 01:43:14'),
(6, 'EMP006', 'harim', 'manager', '2026-07-09 01:41:36', '2026-07-09 01:45:48'),
(7, 'EMP007', '', 'staff', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(8, 'EMP008', '', 'manager', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(9, 'EMP009', '', 'staff', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(10, 'EMP010', '', 'staff', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(11, 'EMP011', '', 'staff', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(12, 'EMP012', '', 'staff', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(13, 'EMP013', '', 'staff', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(14, 'EMP014', '', 'manager', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(15, 'EMP015', '', 'manager', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(16, 'EMP016', '', 'manager', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(17, 'EMP017', '', 'manager', '2026-07-09 01:41:36', '2026-07-09 01:41:36'),
(18, 'EMP018', '', 'manager', '2026-07-09 01:41:36', '2026-07-09 01:41:36');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mail_settings`
--

DROP TABLE IF EXISTS `mail_settings`;
CREATE TABLE `mail_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mailer` varchar(255) NOT NULL DEFAULT 'smtp',
  `host` varchar(255) DEFAULT NULL,
  `port` int(11) NOT NULL DEFAULT 587,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `encryption` varchar(255) DEFAULT NULL,
  `from_address` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_07_02_000001_create_cache_table', 1),
(2, '2024_01_01_000007_create_employees_table', 2),
(3, '2024_01_01_000008_add_employee_id_to_users_table', 3),
(4, '2026_07_04_000002_create_mail_settings_table', 4),
(5, '2026_07_06_000001_add_nomor_telepon_to_borrowings_table', 5),
(6, '2026_07_06_000002_add_return_fields_to_borrowing_details_table', 6),
(7, '2026_07_06_000003_create_notifications_table', 7),
(8, '2026_07_08_000001_add_keterangan_to_borrowings_table', 8),
(9, '2026_07_08_000002_add_status_to_products_table', 9),
(10, '2026_07_08_000003_add_created_by_to_products_table', 10),
(11, '2026_07_08_000003_add_profile_photo_to_users_table', 11);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 13, 'Peminjaman dicatat', 'Peminjaman barang sudah berhasil dicatat. Periksa tanggal kembali pada riwayat Anda.', 'borrow_created', '{\"borrowing_id\":6}', '2026-07-06 07:01:25', '2026-07-06 07:00:41', '2026-07-06 07:01:25'),
(2, 1, 'Staff melakukan peminjaman', 'Asti telah meminjam barang pada 06 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":6,\"staff_id\":13}', NULL, '2026-07-06 07:00:41', '2026-07-06 07:00:41'),
(3, 4, 'Staff melakukan peminjaman', 'Asti telah meminjam barang pada 06 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":6,\"staff_id\":13}', NULL, '2026-07-06 07:00:41', '2026-07-06 07:00:41'),
(4, 6, 'Staff melakukan peminjaman', 'Asti telah meminjam barang pada 06 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":6,\"staff_id\":13}', NULL, '2026-07-06 07:00:41', '2026-07-06 07:00:41'),
(5, 11, 'Staff melakukan peminjaman', 'Asti telah meminjam barang pada 06 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":6,\"staff_id\":13}', '2026-07-08 20:17:53', '2026-07-06 07:00:41', '2026-07-08 20:17:53'),
(6, 13, 'Peringatan tenggat pengembalian', 'Peminjaman dengan tanggal kembali 06 Jul 2026 segera jatuh tempo.', 'due_reminder', '{\"borrowing_id\":6,\"due_date\":\"2026-07-06\"}', '2026-07-06 07:09:54', '2026-07-06 07:04:52', '2026-07-06 07:09:54'),
(7, 13, 'Peminjaman dicatat', 'Peminjaman barang sudah berhasil dicatat.', 'borrow_created', '{\"borrowing_id\":7}', '2026-07-07 11:35:34', '2026-07-07 10:47:07', '2026-07-07 11:35:34'),
(8, 1, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":7,\"staff_id\":13}', NULL, '2026-07-07 10:47:07', '2026-07-07 10:47:07'),
(9, 4, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":7,\"staff_id\":13}', NULL, '2026-07-07 10:47:07', '2026-07-07 10:47:07'),
(10, 6, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":7,\"staff_id\":13}', NULL, '2026-07-07 10:47:07', '2026-07-07 10:47:07'),
(11, 11, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":7,\"staff_id\":13}', '2026-07-08 20:17:53', '2026-07-07 10:47:07', '2026-07-08 20:17:53'),
(12, 13, 'Pengembalian hampir jatuh tempo', 'Pengembalian pada 10 Jul 2026 segera jatuh tempo.', 'due_reminder', '{\"borrowing_id\":7,\"due_date\":\"2026-07-10\"}', '2026-07-07 11:35:34', '2026-07-07 10:50:26', '2026-07-07 11:35:34'),
(13, 13, 'Peminjaman dicatat', 'Peminjaman barang sudah berhasil dicatat.', 'borrow_created', '{\"borrowing_id\":8}', '2026-07-07 11:35:34', '2026-07-07 10:51:15', '2026-07-07 11:35:34'),
(14, 1, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":8,\"staff_id\":13}', NULL, '2026-07-07 10:51:15', '2026-07-07 10:51:15'),
(15, 4, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":8,\"staff_id\":13}', NULL, '2026-07-07 10:51:15', '2026-07-07 10:51:15'),
(16, 6, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":8,\"staff_id\":13}', NULL, '2026-07-07 10:51:15', '2026-07-07 10:51:15'),
(17, 11, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":8,\"staff_id\":13}', '2026-07-08 20:17:53', '2026-07-07 10:51:15', '2026-07-08 20:17:53'),
(18, 13, 'Peminjaman dicatat', 'Peminjaman barang sudah berhasil dicatat.', 'borrow_created', '{\"borrowing_id\":9}', '2026-07-07 11:35:34', '2026-07-07 10:51:47', '2026-07-07 11:35:34'),
(19, 1, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":9,\"staff_id\":13}', NULL, '2026-07-07 10:51:47', '2026-07-07 10:51:47'),
(20, 4, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":9,\"staff_id\":13}', NULL, '2026-07-07 10:51:47', '2026-07-07 10:51:47'),
(21, 6, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":9,\"staff_id\":13}', NULL, '2026-07-07 10:51:47', '2026-07-07 10:51:47'),
(22, 11, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":9,\"staff_id\":13}', '2026-07-08 20:17:53', '2026-07-07 10:51:47', '2026-07-08 20:17:53'),
(23, 13, 'Peminjaman dicatat', 'Peminjaman barang sudah berhasil dicatat.', 'borrow_created', '{\"borrowing_id\":10}', '2026-07-07 11:35:34', '2026-07-07 10:52:19', '2026-07-07 11:35:34'),
(24, 1, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":10,\"staff_id\":13}', NULL, '2026-07-07 10:52:19', '2026-07-07 10:52:19'),
(25, 4, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":10,\"staff_id\":13}', NULL, '2026-07-07 10:52:19', '2026-07-07 10:52:19'),
(26, 6, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":10,\"staff_id\":13}', NULL, '2026-07-07 10:52:19', '2026-07-07 10:52:19'),
(27, 11, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":10,\"staff_id\":13}', '2026-07-08 20:17:53', '2026-07-07 10:52:19', '2026-07-08 20:17:53'),
(28, 13, 'Peminjaman dicatat', 'Peminjaman barang sudah berhasil dicatat.', 'borrow_created', '{\"borrowing_id\":11}', '2026-07-07 11:35:34', '2026-07-07 10:52:50', '2026-07-07 11:35:34'),
(29, 1, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":11,\"staff_id\":13}', NULL, '2026-07-07 10:52:50', '2026-07-07 10:52:50'),
(30, 4, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":11,\"staff_id\":13}', NULL, '2026-07-07 10:52:50', '2026-07-07 10:52:50'),
(31, 6, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":11,\"staff_id\":13}', NULL, '2026-07-07 10:52:50', '2026-07-07 10:52:50'),
(32, 11, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":11,\"staff_id\":13}', '2026-07-08 20:17:53', '2026-07-07 10:52:50', '2026-07-08 20:17:53'),
(33, 13, 'Peminjaman dicatat', 'Peminjaman barang sudah berhasil dicatat.', 'borrow_created', '{\"borrowing_id\":12}', '2026-07-07 11:35:34', '2026-07-07 10:56:52', '2026-07-07 11:35:34'),
(34, 1, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":12,\"staff_id\":13}', NULL, '2026-07-07 10:56:52', '2026-07-07 10:56:52'),
(35, 4, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":12,\"staff_id\":13}', NULL, '2026-07-07 10:56:52', '2026-07-07 10:56:52'),
(36, 6, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":12,\"staff_id\":13}', NULL, '2026-07-07 10:56:52', '2026-07-07 10:56:52'),
(37, 11, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":12,\"staff_id\":13}', '2026-07-08 20:17:53', '2026-07-07 10:56:52', '2026-07-08 20:17:53'),
(38, 13, 'Peminjaman dicatat', 'Peminjaman barang sudah berhasil dicatat.', 'borrow_created', '{\"borrowing_id\":13}', '2026-07-07 11:35:34', '2026-07-07 11:09:41', '2026-07-07 11:35:34'),
(39, 1, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":13,\"staff_id\":13}', NULL, '2026-07-07 11:09:41', '2026-07-07 11:09:41'),
(40, 4, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":13,\"staff_id\":13}', NULL, '2026-07-07 11:09:41', '2026-07-07 11:09:41'),
(41, 6, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":13,\"staff_id\":13}', NULL, '2026-07-07 11:09:41', '2026-07-07 11:09:41'),
(42, 11, 'Peminjaman staf', 'Asti meminjam barang pada 07 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":13,\"staff_id\":13}', '2026-07-08 20:17:53', '2026-07-07 11:09:41', '2026-07-08 20:17:53'),
(43, 13, 'Pengembalian hampir jatuh tempo', 'Pengembalian pada 07 Jul 2026 segera jatuh tempo.', 'due_reminder', '{\"borrowing_id\":13,\"due_date\":\"2026-07-07\"}', '2026-07-07 11:35:34', '2026-07-07 11:09:47', '2026-07-07 11:35:34'),
(44, 13, 'Peminjaman dicatat', 'Peminjaman barang sudah berhasil dicatat.', 'borrow_created', '{\"borrowing_id\":14}', '2026-07-07 11:35:34', '2026-07-07 11:11:27', '2026-07-07 11:35:34'),
(45, 1, 'Peminjaman staf', 'Asti meminjam barang pada 05 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":14,\"staff_id\":13}', NULL, '2026-07-07 11:11:27', '2026-07-07 11:11:27'),
(46, 4, 'Peminjaman staf', 'Asti meminjam barang pada 05 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":14,\"staff_id\":13}', NULL, '2026-07-07 11:11:27', '2026-07-07 11:11:27'),
(47, 6, 'Peminjaman staf', 'Asti meminjam barang pada 05 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":14,\"staff_id\":13}', NULL, '2026-07-07 11:11:27', '2026-07-07 11:11:27'),
(48, 11, 'Peminjaman staf', 'Asti meminjam barang pada 05 Jul 2026', 'staff_borrowed', '{\"borrowing_id\":14,\"staff_id\":13}', '2026-07-08 20:17:53', '2026-07-07 11:11:27', '2026-07-08 20:17:53'),
(49, 1, 'Akun baru', 'Olip terdaftar sebagai staff.', 'user_registered', '{\"user_id\":14,\"role\":\"staff\"}', NULL, '2026-07-08 03:58:46', '2026-07-08 03:58:46'),
(50, 4, 'Akun baru', 'Olip terdaftar sebagai staff.', 'user_registered', '{\"user_id\":14,\"role\":\"staff\"}', NULL, '2026-07-08 03:58:46', '2026-07-08 03:58:46'),
(51, 6, 'Akun baru', 'Olip terdaftar sebagai staff.', 'user_registered', '{\"user_id\":14,\"role\":\"staff\"}', NULL, '2026-07-08 03:58:46', '2026-07-08 03:58:46'),
(52, 11, 'Akun baru', 'Olip terdaftar sebagai staff.', 'user_registered', '{\"user_id\":14,\"role\":\"staff\"}', '2026-07-08 20:17:53', '2026-07-08 03:58:46', '2026-07-08 20:17:53'),
(53, 1, 'Akun baru', 'hellow terdaftar sebagai manager.', 'user_registered', '{\"user_id\":15,\"role\":\"manager\"}', NULL, '2026-07-08 08:33:29', '2026-07-08 08:33:29'),
(54, 4, 'Akun baru', 'hellow terdaftar sebagai manager.', 'user_registered', '{\"user_id\":15,\"role\":\"manager\"}', NULL, '2026-07-08 08:33:29', '2026-07-08 08:33:29'),
(55, 6, 'Akun baru', 'hellow terdaftar sebagai manager.', 'user_registered', '{\"user_id\":15,\"role\":\"manager\"}', NULL, '2026-07-08 08:33:29', '2026-07-08 08:33:29'),
(56, 11, 'Akun baru', 'hellow terdaftar sebagai manager.', 'user_registered', '{\"user_id\":15,\"role\":\"manager\"}', '2026-07-08 20:17:53', '2026-07-08 08:33:29', '2026-07-08 20:17:53'),
(57, 1, 'Akun baru', 'yuki terdaftar sebagai staff.', 'user_registered', '{\"user_id\":16,\"role\":\"staff\"}', NULL, '2026-07-09 01:43:14', '2026-07-09 01:43:14'),
(58, 4, 'Akun baru', 'yuki terdaftar sebagai staff.', 'user_registered', '{\"user_id\":16,\"role\":\"staff\"}', NULL, '2026-07-09 01:43:14', '2026-07-09 01:43:14'),
(59, 6, 'Akun baru', 'yuki terdaftar sebagai staff.', 'user_registered', '{\"user_id\":16,\"role\":\"staff\"}', NULL, '2026-07-09 01:43:15', '2026-07-09 01:43:15'),
(60, 11, 'Akun baru', 'yuki terdaftar sebagai staff.', 'user_registered', '{\"user_id\":16,\"role\":\"staff\"}', NULL, '2026-07-09 01:43:15', '2026-07-09 01:43:15'),
(61, 1, 'Akun baru', 'harim terdaftar sebagai manager.', 'user_registered', '{\"user_id\":17,\"role\":\"manager\"}', NULL, '2026-07-09 01:45:49', '2026-07-09 01:45:49'),
(62, 4, 'Akun baru', 'harim terdaftar sebagai manager.', 'user_registered', '{\"user_id\":17,\"role\":\"manager\"}', NULL, '2026-07-09 01:45:49', '2026-07-09 01:45:49'),
(63, 6, 'Akun baru', 'harim terdaftar sebagai manager.', 'user_registered', '{\"user_id\":17,\"role\":\"manager\"}', NULL, '2026-07-09 01:45:49', '2026-07-09 01:45:49'),
(64, 11, 'Akun baru', 'harim terdaftar sebagai manager.', 'user_registered', '{\"user_id\":17,\"role\":\"manager\"}', NULL, '2026-07-09 01:45:49', '2026-07-09 01:45:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('astisofas123@gmail.com', '$2y$12$ca5zp9DQcOuatZCnNeQJm.URWAsP2vBhZBmgO3ik6mo9hRbDG4lv6', '2026-07-03 21:01:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `stok` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lokasi_penyimpanan` varchar(255) DEFAULT NULL,
  `kondisi_barang` enum('baik','rusak_ringan','rusak_berat') NOT NULL DEFAULT 'baik',
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'approved',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `kode_barang`, `nama_barang`, `category_id`, `stok`, `lokasi_penyimpanan`, `kondisi_barang`, `image`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'BRG07-K002', 'Laptop Dell Latitude', 2, 10, 'Gudang IT Lt.2', 'baik', NULL, 'approved', NULL, '2026-07-02 11:12:16', '2026-07-08 21:52:31'),
(2, 'BRG02-K001', 'Proyektor Epson', 1, 4, 'Ruang AV', 'baik', NULL, 'approved', NULL, '2026-07-02 11:12:16', '2026-07-07 11:01:59'),
(3, 'BRG06-K002', 'Kursi Kantor Ergonomis', 2, 20, 'Gudang Umum', 'baik', 'products/7vbnAj9GmgA65ilpYSBgBC5PsdwoQNh2QCM9io9o.png', 'approved', NULL, '2026-07-02 11:12:16', '2026-07-07 10:51:47'),
(4, 'BRG02-K002', 'Meja Rapat Lipat', 2, 3, 'Gudang Umum', 'rusak_ringan', NULL, 'approved', NULL, '2026-07-02 11:12:16', '2026-07-07 11:10:22'),
(5, 'BRG-005', 'Printer Multifungsi', 3, 5, 'Ruang ATK', 'baik', NULL, 'approved', NULL, '2026-07-02 11:12:16', '2026-07-07 10:51:47'),
(6, 'BRG01-K003', 'swdwd', 3, 4, 'dsdwf', 'baik', NULL, 'approved', NULL, '2026-07-07 10:41:30', '2026-07-08 07:45:08'),
(7, 'BRG03-K001', 'xaxax', 1, 5, 'xsxs', 'baik', NULL, 'approved', 13, '2026-07-07 10:44:52', '2026-07-08 08:22:31'),
(8, 'BRG01-K005', 'dfghjkjytr', 5, 6, 'Parkir', 'baik', 'products/sZo7Q12vfiGTT1U0m1hCEFlNrylw1D9MtfWpy3z7.png', 'approved', 11, '2026-07-08 04:24:40', '2026-07-08 04:24:40'),
(9, 'BRG02-K003', 'dwhdgwhdgw', 3, 2, 'dsahgas', 'baik', 'products/hYI454RmeXHVgJnd4KhJdsPYv6wrFJiKaSv3f1sS.jpg', 'approved', 13, '2026-07-08 21:51:04', '2026-07-08 21:55:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `label`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', '2026-07-02 11:12:16', '2026-07-02 11:12:16'),
(2, 'staff', 'Staff Inventaris', '2026-07-02 11:12:16', '2026-07-02 11:12:16'),
(3, 'manager', 'Manager', '2026-07-02 11:12:16', '2026-07-02 11:12:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6UyEcBUu0CP6B8vyqxMDQrdE6JxqWFlukqrKaVIV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.128.0 Chrome/148.0.7778.271 Electron/42.5.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWRhWFZKODY1Vk03ZFRTSjRadDdKU3dqVnFJSHBCQU5OYm1jSVkyeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783584926),
('fjdTyJuIirBmAliw7IfhauQb0dy6H0AhUXa7qzLx', 17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid1RpbkZmSWlpT0ZuRmNNSGx2UDBWMTNJaUFGWThqaDAzelVpQUZIRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9maWxlIjtzOjU6InJvdXRlIjtzOjEyOiJwcm9maWxlLmVkaXQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxNzt9', 1783586800),
('HqgC7aJJXjLF166ZvdA84TklXQevu5c42WBonERB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.128.0 Chrome/148.0.7778.271 Electron/42.5.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUlFZ3hJWEF2MWVrRGlpZ0N4aUJQakQxTDd6VVBkZEhrellWU1A3eiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783598718),
('zgu3wzm4x28NE3ls4721I8yOsYPPxMZYNWc26YxS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidjhBc3NQbTJaOWJSSkxxVlM0MU1vU1pwVEhwWVpjcGFJTkNHRkdqUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783598730);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role_id`, `employee_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `profile_photo`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Admin Utama', 'admin@kerjain.test', '2026-07-02 11:12:16', '$2y$12$jeAA/WCHADJbP4PRNIUdCO3GJ3s4MZOyBung5xJ3S4fnjXnPvD4g.', 'CsUYlV8I9CycYfFrtPdvglT0N7QEfxu7n3uaWX0zCxs3JiKMxUihGkF5dNeK', NULL, '2026-07-02 11:12:16', '2026-07-02 04:49:22'),
(2, 2, NULL, 'Staff Gudang', 'staff@kerjain.test', '2026-07-02 11:12:16', '$2y$12$ZVhoFlXQ2WzgbAoPMZZsUO.9gDe94hAngzO2BlDb/7q.yegFpZeRu', NULL, NULL, '2026-07-02 11:12:16', '2026-07-02 04:49:23'),
(3, 3, NULL, 'Manager Kantor', 'manager@kerjain.test', '2026-07-02 11:12:16', '$2y$12$iRVIgeo146knPPiEIGwSK.jlbyFNPJZmHV7F9rvIQV1NiZehoccUu', NULL, NULL, '2026-07-02 11:12:16', '2026-07-02 04:49:23'),
(4, 1, NULL, 'safira', 'safira@admin.telkomsel.ac.id', NULL, '$2y$12$PcBNF5P0i80ykL11aPWeeOp0dGevSohh6a.5RyygxAQWE8zRhlt1K', NULL, NULL, '2026-07-02 05:26:02', '2026-07-02 05:26:02'),
(5, 2, NULL, 'daffa', 'daffa@staff.telkomsel.ac.id', NULL, '$2y$12$EQ0iYDwRb16H8WOJWzVaKOzqnd.eZuMrXoE3xbxb9plmh4LZwI2Mq', NULL, NULL, '2026-07-02 05:27:00', '2026-07-02 05:27:00'),
(6, 1, NULL, 'Admin Demo', 'admin@admin.telkomsel.ac.id', '2026-07-02 05:40:04', '$2y$12$oZkvhf2OBkKSPmhsq/ulme9Iw/FEvYe5e4xI1MKyBXC22vHo7Upw.', NULL, NULL, '2026-07-02 05:40:04', '2026-07-02 05:40:04'),
(8, 3, NULL, 'Manager Demo', 'manager@manager.telkomsel.ac.id', '2026-07-02 05:40:05', '$2y$12$xP7ADugiV/Ps6SSQEIVJJu98.wRG9n2RhAFQwEzm9VqLl.TtmBmTO', NULL, NULL, '2026-07-02 05:40:05', '2026-07-02 05:40:05'),
(9, 3, NULL, 'susanti', 'susanti@manager.telkomsel.ac.id', NULL, '$2y$12$M65QkQdJX3G9Z2VMNz86de49IGwMFx8DfTvxzf2uiszFQMQ/dhIpi', NULL, NULL, '2026-07-02 07:45:30', '2026-07-02 07:45:30'),
(10, 3, NULL, 'susanti', 'susanti@manager.telkomsel.com', NULL, '$2y$12$THkgmd1Zh9ot8v9n/WYGeuHnXOSZ3XFUt05Ks7EI0O7slpsUnougO', NULL, NULL, '2026-07-02 23:47:52', '2026-07-02 23:47:52'),
(11, 1, NULL, 'Admin Utama', 'admintelkomreg3@gmail.com', NULL, '$2y$12$yDOi8Lp4UuJ7fDcavkCcgO4cHwbdfhvDW4Z8x.zYyl5Zfdi8Wm8CC', 'I9inl0lTQxVFmLWPnrOLNYy6lPoXzu1wvnHevAlN6GTSNtCJdCQ3NaYJyTXT', 'profiles/zd8LdF56M2XYQrJ7MUPH5UtwieZagi9UavwdxVis.png', '2026-07-03 20:27:31', '2026-07-08 08:05:09'),
(12, 3, 'EMP004', 'siti', 'sitiaminah123_@gmail.com', NULL, '$2y$12$uL70oPeuNjGuQOCHxaPiAulB3lJYQUq9RqqMsyQVXUNe5P2Elz/3i', NULL, NULL, '2026-07-03 20:53:17', '2026-07-03 20:53:17'),
(13, 2, 'EMP003', 'Asti Sofiana', 'astisofas123@gmail.com', NULL, '$2y$12$UW5bBTKKtBUARtfYbK/0Demj4H5z.aWG6ha9MkyPbzJ7qGr5I068.', 'DTh80X3N6TGOjuGZed3XANDvbafO4iKisfiL2RkN1Ni4bUqAyCCcg2wizloi', 'profiles/nb2KqBm5yy57KgXARQNi3QNsk8ogjNDLHuL2LYf0.jpg', '2026-07-03 20:58:31', '2026-07-08 21:48:25'),
(14, 2, 'EMP001', 'Olip', 'olip123@gmail.com', NULL, '$2y$12$hyYBKPvxRXiZzceNr/FZKuZsGoxVJGrA9jOpyGdD0yHp5re1KsXIm', NULL, NULL, '2026-07-08 03:58:45', '2026-07-08 03:58:45'),
(15, 3, 'EMP002', 'hellow', 'astisofiana25@gmail.com', NULL, '$2y$12$itMnEYvlQKlwRLBMKeEC9ex4sevKMXXYLdMaLyAJ3vfQQhLMPe.MK', NULL, NULL, '2026-07-08 08:33:28', '2026-07-08 20:32:18'),
(16, 2, 'EMP005', 'yuki', 'astisofiana24@gmail.com', NULL, '$2y$12$YTkEelEnoYoBtrN/96z4KeaREcqRKW/GieWDnYezjPrlqt8K2q0AO', NULL, NULL, '2026-07-09 01:43:14', '2026-07-09 01:43:14'),
(17, 3, 'EMP006', 'harim', 'harim012@gmail.com', NULL, '$2y$12$WKmQzXeogIH4hlZhi6yooOdxKXfbhnWY1AGM204GKmL47gjNJHA.G', NULL, NULL, '2026-07-09 01:45:49', '2026-07-09 01:45:49');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  ADD KEY `borrowings_processed_by_foreign` (`processed_by`);

--
-- Indeks untuk tabel `borrowing_details`
--
ALTER TABLE `borrowing_details`
  ADD KEY `borrowing_details_borrowing_id_foreign` (`borrowing_id`),
  ADD KEY `borrowing_details_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD UNIQUE KEY `employees_employee_id_unique` (`employee_id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD UNIQUE KEY `products_kode_barang_unique` (`kode_barang`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_employee_id_unique` (`employee_id`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `borrowing_details`
--
ALTER TABLE `borrowing_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `mail_settings`
--
ALTER TABLE `mail_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `borrowing_details`
--
ALTER TABLE `borrowing_details`
  ADD CONSTRAINT `borrowing_details_borrowing_id_foreign` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowing_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
