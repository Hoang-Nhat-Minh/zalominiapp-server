-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 03, 2026 at 02:36 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `miniapp_miniapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_chats`
--

CREATE TABLE `ai_chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `title`, `department`, `appointment_date`, `appointment_time`, `note`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Đăng ký hộ kinh doanh', 'Phòng Kinh tế', '2026-05-18', '08:00:00', 'Nộp hồ sơ đăng ký cho doanh nghiệp HNM', 'cancelled', '2026-05-18 01:50:08', '2026-05-18 19:46:54'),
(2, 4, 'Thay đổi thông tin hộ kinh doanh', 'Phòng Kinh tế', '2026-05-20', '13:00:00', 'Abc', 'pending', '2026-05-18 21:24:39', '2026-05-18 21:24:39'),
(3, 5, 'Tiếp công dân', 'Văn phòng UBND', '2026-05-20', '14:30:00', 'Cfg', 'pending', '2026-05-19 00:10:23', '2026-05-19 00:10:23'),
(4, 1, 'Bổ sung hồ sơ còn thiếu', 'Bộ phận Một cửa', '2026-05-19', '15:30:00', NULL, 'cancelled', '2026-05-19 01:37:53', '2026-05-19 08:39:31'),
(5, 4, 'Đăng ký tạm trú', 'Công an Phường', '2026-05-23', '14:30:00', 'Qbv', 'pending', '2026-05-19 10:30:05', '2026-05-19 10:30:05'),
(6, 6, 'Tư vấn thủ tục hành chính', 'Văn phòng UBND', '2026-05-21', '14:30:00', 'Tygg', 'pending', '2026-05-19 10:35:49', '2026-05-19 10:35:49'),
(7, 6, 'Tư vấn thủ tục hành chính', 'Văn phòng UBND', '2026-05-21', '15:30:00', NULL, 'pending', '2026-05-19 10:36:11', '2026-05-19 10:36:11'),
(8, 5, 'Xác nhận hoàn công công trình', 'Phòng Quản lý Đô thị', '2026-06-17', '10:00:00', NULL, 'pending', '2026-06-03 08:18:18', '2026-06-03 08:18:18'),
(9, 5, 'Nộp hồ sơ lần đầu', 'Bộ phận Một cửa', '2026-06-22', '09:00:00', 'Nộp hồ sơ đăng ký sản phẩm ocop', 'pending', '2026-06-19 01:32:55', '2026-06-19 01:32:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_05_13_025625_create_profiles_table', 1),
(6, '2026_05_13_025633_create_profile_timelines_table', 1),
(7, '2026_05_13_025837_create_appointments_table', 1),
(10, '2026_05_13_031834_create_posts_table', 1),
(11, '2026_05_13_032036_create_party_documents_table', 1),
(12, '2026_05_13_032042_create_party_votes_table', 1),
(13, '2026_05_13_032049_create_party_vote_responses_table', 1),
(14, '2026_05_13_035712_create_ai_chats_table', 1),
(15, '2026_05_13_031048_create_reports_table', 2),
(16, '2026_05_13_031115_create_notifications_table', 3),
(17, '2026_05_19_031928_create_notification_user_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('emergency','government','utility','community') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `content`, `type`, `sent_at`, `created_at`, `updated_at`) VALUES
(1, 'Triển khai phiên bản demo', 'Triển khai phiên bản demo 0.0.1', 'utility', '2026-05-19 03:27:08', '2026-05-19 03:27:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification_user`
--

CREATE TABLE `notification_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `notification_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_user`
--

INSERT INTO `notification_user` (`id`, `notification_id`, `user_id`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-05-18 20:48:42', '2026-05-19 03:32:04', '2026-05-18 20:48:42'),
(2, 1, 2, 1, '2026-06-23 01:38:58', '2026-05-19 03:32:08', '2026-06-23 01:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `last_login_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`id`, `name`, `email`, `password`, `department_id`, `phone`, `status`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'Hoang Nhat Minh', 'admin@admin.com', '$2y$10$w9mEXJb36kIIIMZu.eYwIu7gbWkp4qOMnzDl9f9WQaKL6Leu1AhRq', 1, '0987654321', 'active', '2026-07-14 03:35:18', '0000-00-00 00:00:00', '2026-07-14 03:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `party_documents`
--

CREATE TABLE `party_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('meeting','directive','resolution','report','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `party_votes`
--

CREATE TABLE `party_votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('open','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `party_vote_responses`
--

CREATE TABLE `party_vote_responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vote_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `answer` enum('agree','disagree','abstain') COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'demo-login', '7c1ae37e7b4f1e4cb365d6c31a1fe8b51669608728dc47b3fda651806ae9634e', '[\"*\"]', '2026-05-18 01:22:24', '2026-05-18 00:52:40', '2026-05-18 01:22:24'),
(2, 'App\\Models\\User', 1, 'demo-login', 'e6216728b59bdb391b04bb31cc5dc499525264b5ad5ac48fc5aaac044504186a', '[\"*\"]', '2026-05-18 01:45:09', '2026-05-18 01:22:27', '2026-05-18 01:45:09'),
(3, 'App\\Models\\User', 1, 'demo-login', 'ff4e848cde8a383a938881ed4d6faa2492cc564933625a816bf0a2be27da7d68', '[\"*\"]', '2026-05-18 02:22:01', '2026-05-18 01:45:11', '2026-05-18 02:22:01'),
(4, 'App\\Models\\User', 1, 'demo-login', '8f52e5432121d889b48939dda6a3e09845a05ea462ae19c605ff3d82838a5db6', '[\"*\"]', '2026-05-18 02:22:53', '2026-05-18 02:22:03', '2026-05-18 02:22:53'),
(5, 'App\\Models\\User', 1, 'demo-login', '66b5fcdd7152827592de67ceda076d00f86026badf24b32a386d37b3e299d93e', '[\"*\"]', '2026-05-18 02:23:27', '2026-05-18 02:22:55', '2026-05-18 02:23:27'),
(6, 'App\\Models\\User', 1, 'demo-login', '1e77fe6370390dd87c54e8fedd46308d01fc8c15b8fa2ee669eb400cff9cd4b9', '[\"*\"]', '2026-05-18 02:29:22', '2026-05-18 02:23:30', '2026-05-18 02:29:22'),
(7, 'App\\Models\\User', 1, 'demo-login', '8fbc7b57ec447c216d5fc50276fa8cba2dcc5d12eb0bcdb921961eaac20bb530', '[\"*\"]', '2026-05-18 02:29:45', '2026-05-18 02:29:25', '2026-05-18 02:29:45'),
(8, 'App\\Models\\User', 1, 'demo-login', '673d4d64e1e4673fc71fc4a43aac5b40fadb90bd4a7ec1edae51a2a0add38f0d', '[\"*\"]', '2026-05-18 02:29:59', '2026-05-18 02:29:48', '2026-05-18 02:29:59'),
(9, 'App\\Models\\User', 1, 'demo-login', 'c241821d11f8c5e0a47545024cff6327841c896e7ba497ef3da97a49d7b5020e', '[\"*\"]', '2026-05-18 02:30:39', '2026-05-18 02:30:02', '2026-05-18 02:30:39'),
(10, 'App\\Models\\User', 1, 'demo-login', 'a9260317507c2c140094c90450b34898f961889b330384bcf925148fcda6b565', '[\"*\"]', '2026-05-18 02:30:57', '2026-05-18 02:30:41', '2026-05-18 02:30:57'),
(11, 'App\\Models\\User', 1, 'demo-login', '57c545654a7395f74debf9d0d7901138989622ceb1b2bf39477c440db65cc279', '[\"*\"]', '2026-05-18 02:31:11', '2026-05-18 02:31:00', '2026-05-18 02:31:11'),
(12, 'App\\Models\\User', 1, 'demo-login', '8b1b18bc9083f5274d24183bb6973af2367d632f42e97ca95de641d194b3a079', '[\"*\"]', '2026-05-18 02:31:27', '2026-05-18 02:31:14', '2026-05-18 02:31:27'),
(13, 'App\\Models\\User', 1, 'demo-login', '297e59b9b0ad37a0e5b303a7228b4ef96aeeec73a0062259013c253793395ee6', '[\"*\"]', '2026-05-18 02:31:40', '2026-05-18 02:31:30', '2026-05-18 02:31:40'),
(14, 'App\\Models\\User', 1, 'demo-login', '51d3b20eaaf0b601542fef436f03b574f8fe48d6fb20d8897cf752183ff2b517', '[\"*\"]', '2026-05-18 03:25:43', '2026-05-18 02:31:43', '2026-05-18 03:25:43'),
(15, 'App\\Models\\User', 1, 'demo-login', 'acbdc6c3bfdef1965cf93c9127c593ee13dadf0704de8573b231e29f4bed5ff8', '[\"*\"]', '2026-05-18 03:32:43', '2026-05-18 03:25:46', '2026-05-18 03:32:43'),
(16, 'App\\Models\\User', 2, 'zalo-auth', '135bf8ef552e244cdf0c8a1a0040996356a0dde59e6d8e4c53be444ceb13a432', '[\"*\"]', '2026-05-18 03:34:59', '2026-05-18 03:31:54', '2026-05-18 03:34:59'),
(17, 'App\\Models\\User', 1, 'demo-login', 'e38d080aa763053ac53e35380c3d516270633bdb2c426fd93512d9ddfbea76c0', '[\"*\"]', '2026-05-18 03:34:06', '2026-05-18 03:32:45', '2026-05-18 03:34:06'),
(18, 'App\\Models\\User', 1, 'demo-login', 'b79e46b778fd665c13dc8b11f4638b2eb6ad9486922c4c5d3d3899c71f9c636b', '[\"*\"]', '2026-05-18 03:44:54', '2026-05-18 03:34:08', '2026-05-18 03:44:54'),
(19, 'App\\Models\\User', 2, 'zalo-auth', 'b14150599a2ff9df717dc25c56da2adabc4bda4798eb09c96f74f22fd7058f74', '[\"*\"]', '2026-05-18 03:40:59', '2026-05-18 03:35:03', '2026-05-18 03:40:59'),
(20, 'App\\Models\\User', 2, 'zalo-auth', '663404fd28d71d25a0fbf8cf966780cdeefcfd4b6d678691f5f43134ed52a797', '[\"*\"]', '2026-05-18 03:41:54', '2026-05-18 03:41:03', '2026-05-18 03:41:54'),
(21, 'App\\Models\\User', 2, 'zalo-auth', '38493116041197405950de5d97edc6322b48e9fe713da43e97bdf7e5137b90d8', '[\"*\"]', '2026-05-18 18:41:04', '2026-05-18 03:41:59', '2026-05-18 18:41:04'),
(22, 'App\\Models\\User', 1, 'demo-login', '461d6fa07afc18fc664766d0bf70e4822410194c8d2088a013effe69708f3b43', '[\"*\"]', '2026-05-18 03:57:14', '2026-05-18 03:44:56', '2026-05-18 03:57:14'),
(23, 'App\\Models\\User', 1, 'demo-login', '584dc4faad15f010ee8c0ccb4c6f6acb1a794f687e765b231681717608e1ddb0', '[\"*\"]', '2026-05-18 18:37:31', '2026-05-18 18:25:25', '2026-05-18 18:37:31'),
(24, 'App\\Models\\User', 1, 'demo-login', 'd638616aebc2db0877975ea2029c4dfeabf8a650712639f3a13038c6d7c77c6f', '[\"*\"]', '2026-05-18 19:34:18', '2026-05-18 18:37:34', '2026-05-18 19:34:18'),
(25, 'App\\Models\\User', 2, 'zalo-auth', 'a192eebf6f2f95aa4630cd37dd47933151a68d237d640029f125a23f458b59ac', '[\"*\"]', '2026-05-18 18:46:40', '2026-05-18 18:41:37', '2026-05-18 18:46:40'),
(26, 'App\\Models\\User', 2, 'zalo-auth', 'c1f675cc8f2ab90619e92220e4d1aa1539484d27833b6175cfc72b6e7f1fbb25', '[\"*\"]', '2026-05-18 19:02:47', '2026-05-18 18:46:44', '2026-05-18 19:02:47'),
(27, 'App\\Models\\User', 2, 'zalo-auth', '19f6d9bd67d5c957519999298d416f5ddba471cead0387fdf19247863fd7ac91', '[\"*\"]', '2026-05-18 19:27:59', '2026-05-18 19:02:52', '2026-05-18 19:27:59'),
(28, 'App\\Models\\User', 2, 'zalo-auth', '5de2c46e1fb11c772bd95db16b188f0fd20ed897686df57b35903e05f534c109', '[\"*\"]', '2026-05-18 20:32:50', '2026-05-18 19:28:03', '2026-05-18 20:32:50'),
(29, 'App\\Models\\User', 1, 'demo-login', 'b94ce357106ced79620a3187814cd1afb84fcf235e8e7b538ea52b5f4e8b914d', '[\"*\"]', '2026-05-18 19:38:25', '2026-05-18 19:34:20', '2026-05-18 19:38:25'),
(30, 'App\\Models\\User', 1, 'demo-login', 'ddbf9800b76a42986d6309620c4d3a9e8ac900fb8608b2fcc7660bb11e33686c', '[\"*\"]', '2026-05-18 19:49:33', '2026-05-18 19:38:28', '2026-05-18 19:49:33'),
(31, 'App\\Models\\User', 1, 'demo-login', 'ee11bff68144e297ee07d34a97dae4c44cacf06b05c4ff9a40ec06254fcceaca', '[\"*\"]', '2026-05-18 19:54:19', '2026-05-18 19:49:35', '2026-05-18 19:54:19'),
(32, 'App\\Models\\User', 1, 'demo-login', 'bfd6a20172b11b80dcd615fc2f39dfa1c24ece373acaddb5d12e6fe5f36b8c7a', '[\"*\"]', '2026-05-18 20:29:03', '2026-05-18 19:54:22', '2026-05-18 20:29:03'),
(33, 'App\\Models\\User', 1, 'demo-login', 'e678c5ab80331c9fad081d0464de292b84f914515fa10f2a09caa1b50eba9364', '[\"*\"]', '2026-05-18 20:29:38', '2026-05-18 20:29:05', '2026-05-18 20:29:38'),
(34, 'App\\Models\\User', 1, 'demo-login', 'b5b57390bca38a8c22a05a7b53027064fac4bdeaa02684d369b0cbe8fd64e1f2', '[\"*\"]', '2026-05-18 20:48:32', '2026-05-18 20:29:41', '2026-05-18 20:48:32'),
(35, 'App\\Models\\User', 2, 'zalo-auth', '49f8f0d5f050135b1039793bc2ead1b70728c2cc1cb562eb4a94623ff32d2d96', '[\"*\"]', '2026-05-18 20:34:09', '2026-05-18 20:32:55', '2026-05-18 20:34:09'),
(36, 'App\\Models\\User', 2, 'zalo-auth', '9a8c1398e982771203ab7f3db2b6ad8438ec975b039fe36eeeb0747dde0cfb4d', '[\"*\"]', '2026-05-18 20:40:21', '2026-05-18 20:34:14', '2026-05-18 20:40:21'),
(37, 'App\\Models\\User', 2, 'zalo-auth', '4a4e0d2f0141f230b49005c6a2642007d527aeb746aaf6efdd241b97f841410c', '[\"*\"]', '2026-05-18 20:42:02', '2026-05-18 20:40:25', '2026-05-18 20:42:02'),
(38, 'App\\Models\\User', 2, 'zalo-auth', 'c165b6b91dc8460d5dbae5e30ef0f13274981e3b407b8cad786d86d3f03493d4', '[\"*\"]', '2026-05-18 20:43:43', '2026-05-18 20:42:09', '2026-05-18 20:43:43'),
(39, 'App\\Models\\User', 2, 'zalo-auth', '63a43dc79a6f19eb2dccb07111bd196678206d27810ec4579afc3822b2e1c894', '[\"*\"]', '2026-05-18 20:45:25', '2026-05-18 20:43:47', '2026-05-18 20:45:25'),
(40, 'App\\Models\\User', 2, 'zalo-auth', 'f0a5aac5d91e72df3aa99795a66862779eebc09ba9a50b4b996ea61c600fbce5', '[\"*\"]', '2026-05-18 21:17:03', '2026-05-18 20:45:31', '2026-05-18 21:17:03'),
(41, 'App\\Models\\User', 1, 'demo-login', '237243f938688e12f0ea2dc8c8405f8b54dd9a4434c4107302169afe81c697fc', '[\"*\"]', '2026-05-18 21:00:22', '2026-05-18 20:48:35', '2026-05-18 21:00:22'),
(42, 'App\\Models\\User', 1, 'demo-login', 'dd8895813e9ffdf9607f058466cf89a56b4e2acecd4a98f845a863e3f11d9334', '[\"*\"]', '2026-05-18 21:12:57', '2026-05-18 21:00:24', '2026-05-18 21:12:57'),
(43, 'App\\Models\\User', 1, 'demo-login', '37ee7c4d5be42547e671d04f04fae781ffce1fcd92228f196a89c863924fff79', '[\"*\"]', '2026-05-19 01:07:26', '2026-05-18 21:13:00', '2026-05-19 01:07:26'),
(44, 'App\\Models\\User', 2, 'zalo-auth', '7d64629036180ec650d4bd960ae70b5540149059de644852709de329a7793473', '[\"*\"]', '2026-05-19 00:43:53', '2026-05-18 21:17:04', '2026-05-19 00:43:53'),
(45, 'App\\Models\\User', 3, 'zalo-auth', 'b9bc690312df1ff230d1aa1d1e4858e37dd3329465a6e4464b2d868f70124cf5', '[\"*\"]', '2026-05-18 21:23:03', '2026-05-18 21:20:46', '2026-05-18 21:23:03'),
(46, 'App\\Models\\User', 4, 'zalo-auth', 'e5d82a048c4348fa7257ce452b141a63938d177652c3bd2ed8ef4ff16d0dc90e', '[\"*\"]', '2026-05-18 21:22:19', '2026-05-18 21:21:33', '2026-05-18 21:22:19'),
(47, 'App\\Models\\User', 4, 'zalo-auth', '6610507ac441960d20a1df598f03591a0822f7adf441ac456963f0e056e7fc59', '[\"*\"]', '2026-05-18 21:25:30', '2026-05-18 21:22:24', '2026-05-18 21:25:30'),
(48, 'App\\Models\\User', 3, 'zalo-auth', '7daf849f9522be1802161b82b410877322e8877c62b67b46bd8b080d365db29a', '[\"*\"]', '2026-05-19 00:46:38', '2026-05-18 21:23:08', '2026-05-19 00:46:38'),
(49, 'App\\Models\\User', 4, 'zalo-auth', 'b085c5ebc64641ef5ffa1d79f669010b5ed6a0815fe987a12969f7aab3e3f1f2', '[\"*\"]', '2026-05-19 10:28:36', '2026-05-18 21:25:34', '2026-05-19 10:28:36'),
(50, 'App\\Models\\User', 5, 'zalo-auth', '585fa51ca23d5bddd6151829ea261b34d4377dff4749c607f3d250a292d9c077', '[\"*\"]', '2026-05-19 00:10:41', '2026-05-19 00:09:07', '2026-05-19 00:10:41'),
(51, 'App\\Models\\User', 5, 'zalo-auth', 'af94f6ca122d37b11be7f8c439d6c0ad4b62a0112148ae5cddcc1fc90e09c1d4', '[\"*\"]', '2026-05-19 00:13:05', '2026-05-19 00:10:46', '2026-05-19 00:13:05'),
(52, 'App\\Models\\User', 2, 'zalo-auth', '8bd70785c22eb79af06b4abad4b5ce497874971da5d6464ede9533476037e0be', '[\"*\"]', '2026-05-19 00:45:27', '2026-05-19 00:43:57', '2026-05-19 00:45:27'),
(53, 'App\\Models\\User', 2, 'zalo-auth', '8c0c7a343fc05203e2b463e640fc08aeb1307ade839af5e80ad6243a71638a84', '[\"*\"]', '2026-05-19 00:45:40', '2026-05-19 00:45:30', '2026-05-19 00:45:40'),
(54, 'App\\Models\\User', 2, 'zalo-auth', '652e3fd7c0598aa945dfb6af30245ec93074bce7d24e292f63e3f6855ce4c556', '[\"*\"]', '2026-05-19 10:02:01', '2026-05-19 00:46:08', '2026-05-19 10:02:01'),
(55, 'App\\Models\\User', 3, 'zalo-auth', '053a32edc24b8b02fccb3dbb5dab72259028450fdd8ad211caaa141f9083355e', '[\"*\"]', '2026-05-19 00:48:35', '2026-05-19 00:46:44', '2026-05-19 00:48:35'),
(56, 'App\\Models\\User', 1, 'demo-login', 'eec0f50821196c3d28e5dff51678af93dd728208d1badabb2a93f9a7bd9567fe', '[\"*\"]', '2026-05-19 01:17:51', '2026-05-19 01:07:28', '2026-05-19 01:17:51'),
(57, 'App\\Models\\User', 1, 'demo-login', 'b1a729df0ce156c94e66b3158be4a7136a722acaa109cd41cfff3a0f7bf21989', '[\"*\"]', '2026-05-19 01:18:43', '2026-05-19 01:17:54', '2026-05-19 01:18:43'),
(58, 'App\\Models\\User', 1, 'demo-login', '65fb87b4d2039632959790d39d5d5375f254a4a341a29ef26ff185d7dcfb2631', '[\"*\"]', '2026-05-19 01:23:04', '2026-05-19 01:18:46', '2026-05-19 01:23:04'),
(59, 'App\\Models\\User', 1, 'demo-login', '45dd6b71bd75271bdad7b9e3fc5f5cf6019c87aff825cf7cfaa5ff492503cc2b', '[\"*\"]', '2026-05-19 01:23:17', '2026-05-19 01:23:07', '2026-05-19 01:23:17'),
(60, 'App\\Models\\User', 1, 'demo-login', 'e2c8b998022daebcb9a3daf30879253077a96a16b759055e16ce459bef88482b', '[\"*\"]', '2026-05-19 09:08:14', '2026-05-19 01:37:24', '2026-05-19 09:08:14'),
(61, 'App\\Models\\User', 1, 'demo-login', 'f6040809a108863be37e4ec89dc9854d8e660a264cfaee372becd8070a835792', '[\"*\"]', '2026-05-19 09:12:00', '2026-05-19 09:08:17', '2026-05-19 09:12:00'),
(62, 'App\\Models\\User', 1, 'demo-login', 'c2be10fe1e487468287535e4913d92284a895954da5647dfd2609463248b30a2', '[\"*\"]', '2026-05-19 09:16:39', '2026-05-19 09:12:03', '2026-05-19 09:16:39'),
(63, 'App\\Models\\User', 1, 'demo-login', '13c909d608785e871366c60d771dea8e6306b0212398a463b5b05b1b951a49ac', '[\"*\"]', '2026-05-19 09:23:35', '2026-05-19 09:16:42', '2026-05-19 09:23:35'),
(64, 'App\\Models\\User', 1, 'demo-login', 'c751670e558dcabaa51be658b4df898a6593a87828dd8991390ea0d785937e93', '[\"*\"]', '2026-05-19 10:19:48', '2026-05-19 09:23:38', '2026-05-19 10:19:48'),
(65, 'App\\Models\\User', 2, 'zalo-auth', '37d05edcbe05463f001190412d27017fb9915ca45166964352e99bb40fb6baa6', '[\"*\"]', '2026-05-20 01:27:50', '2026-05-19 10:02:03', '2026-05-20 01:27:50'),
(66, 'App\\Models\\User', 1, 'demo-login', '145e3569dd6d5c1d347d36f4e3fe609f25aa5d4897f4823bf81e031aea235413', '[\"*\"]', '2026-05-19 10:23:15', '2026-05-19 10:19:49', '2026-05-19 10:23:15'),
(67, 'App\\Models\\User', 4, 'zalo-auth', '19e79586dc3230bdfc06f9f78aeba95c09fc64febcc928b8fd3814f808fc7084', '[\"*\"]', '2026-05-19 10:31:28', '2026-05-19 10:28:38', '2026-05-19 10:31:28'),
(68, 'App\\Models\\User', 6, 'zalo-auth', 'bbfa0005ec55c1c52829e95f047188e64721cc43ec7318680d05a323393d40ec', '[\"*\"]', '2026-05-19 10:37:37', '2026-05-19 10:35:02', '2026-05-19 10:37:37'),
(69, 'App\\Models\\User', 2, 'zalo-auth', 'c2e3016e0263c478802b6f54ecae317adec6de025958908b6d0e4e64668cc3ef', '[\"*\"]', '2026-05-20 03:45:01', '2026-05-20 01:27:54', '2026-05-20 03:45:01'),
(70, 'App\\Models\\User', 2, 'zalo-auth', 'b591442ee9daaa6d09d8d3d890bdb078b9582e94e92453dcb1f5ce25b008cefa', '[\"*\"]', '2026-06-23 01:35:55', '2026-05-20 03:45:04', '2026-06-23 01:35:55'),
(71, 'App\\Models\\User', 5, 'zalo-auth', 'a88510c62369e9101d888727504e3db09fba72dc7f97970f8f38587f651fbcde', '[\"*\"]', '2026-05-22 05:43:51', '2026-05-22 05:41:42', '2026-05-22 05:43:51'),
(72, 'App\\Models\\User', 7, 'zalo-auth', '00e51f614f3fdb8485ac06bb4d7b610d85c2dae3197298b17a25cbc9837fb663', '[\"*\"]', '2026-05-23 02:33:19', '2026-05-23 02:31:20', '2026-05-23 02:33:19'),
(73, 'App\\Models\\User', 7, 'zalo-auth', '866a5e6b4770b06e9f84dbbe22104b568f397fa325b85ab483760cebf1457719', '[\"*\"]', '2026-06-02 14:14:45', '2026-06-02 14:14:26', '2026-06-02 14:14:45'),
(74, 'App\\Models\\User', 7, 'zalo-auth', '3d60e70a7b4319d2d85be9c41311ae429331ee61554e9458e9edbffff38df8c2', '[\"*\"]', '2026-06-02 14:17:11', '2026-06-02 14:14:50', '2026-06-02 14:17:11'),
(75, 'App\\Models\\User', 5, 'zalo-auth', 'f5dc120d05e626cd48e75b18b944bee286137fd137ad6bd8d356464955e6efc2', '[\"*\"]', '2026-06-03 08:18:42', '2026-06-03 08:17:45', '2026-06-03 08:18:42'),
(76, 'App\\Models\\User', 5, 'zalo-auth', 'ccf8cd2dd09ec2d6c367189c14c4aa5d35391267343305d69e174f53b797da90', '[\"*\"]', '2026-06-03 08:20:23', '2026-06-03 08:18:46', '2026-06-03 08:20:23'),
(77, 'App\\Models\\User', 5, 'zalo-auth', '36d09f57f1acaa1cce509632098cfbd9ff59839d362dffa2239ad85a32c45da8', '[\"*\"]', '2026-06-03 08:22:34', '2026-06-03 08:20:28', '2026-06-03 08:22:34'),
(78, 'App\\Models\\User', 5, 'zalo-auth', 'b695a84ea9f4cda87f9bca448642c142c39927e7c79ea279b65597fb0784230c', '[\"*\"]', '2026-06-03 10:14:33', '2026-06-03 09:58:01', '2026-06-03 10:14:33'),
(79, 'App\\Models\\User', 5, 'zalo-auth', '8512eb2f412e079301d1ccb847c8366085a123ba9f7dd0de340d87bd3774d759', '[\"*\"]', '2026-06-19 01:31:42', '2026-06-19 01:27:04', '2026-06-19 01:31:42'),
(80, 'App\\Models\\User', 5, 'zalo-auth', '1ddf250be8d335a17e55930f85cc6b2c2330b9937039c1e3eda7f082d0aac881', '[\"*\"]', '2026-06-19 01:37:27', '2026-06-19 01:31:47', '2026-06-19 01:37:27'),
(81, 'App\\Models\\User', 2, 'zalo-auth', 'bca594213f438111009ab83422d8799aa9c396573a3871fb53c9890ea270b0f5', '[\"*\"]', '2026-06-23 01:39:49', '2026-06-23 01:35:58', '2026-06-23 01:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('news','policy','party','announcement') COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('received','processing','waiting','completed','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'received',
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `officer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `title`, `code`, `type`, `department`, `status`, `description`, `officer_id`, `received_at`, `processed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hồ Sơ Doanh Nghiệp HNM', 'HSDN_901223', 'Doanh Nghiệp', 'Phòng Kinh Tế', 'processing', 'Hồ Sơ Doanh Nghiệp HNM', 186759, '2026-05-18 08:51:30', '2026-05-19 08:51:30', '2026-05-18 08:51:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `profile_timelines`
--

CREATE TABLE `profile_timelines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `profile_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('received','processing','waiting','completed','rejected') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profile_timelines`
--

INSERT INTO `profile_timelines` (`id`, `profile_id`, `status`, `title`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 'received', 'Tiếp nhận hồ sơ', 'Hồ sơ đã được tiếp nhận tại bộ phận một cửa', '2024-05-01 01:00:00', '2024-05-01 01:00:00'),
(2, 1, 'processing', 'Đang thẩm định', 'Chuyên viên đang xem xét và thẩm định hồ sơ', '2024-05-03 02:30:00', '2024-05-03 02:30:00'),
(3, 1, 'waiting', 'Chờ bổ sung giấy tờ', 'Người dân cần bổ sung bản sao công chứng CCCD', '2024-05-05 07:00:00', '2024-05-05 07:00:00'),
(4, 1, 'completed', 'Hoàn thành', 'Hồ sơ đã được phê duyệt và trả kết quả', '2024-05-10 03:00:00', '2024-05-10 03:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category` enum('environment','urban_order','traffic','infrastructure','electricity_water') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','processing','resolved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `officer_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `category`, `title`, `description`, `images`, `address`, `status`, `officer_note`, `created_at`, `updated_at`) VALUES
(1, 2, 'environment', 'Vứt rác bừa bãi', 'Người dân vứt rác bừa bãi ở quảng trường', '\"[\\\"reports\\\\\\/images\\\\\\/INItXJfX4WZWS7Lty6xuJUnLqjKGMavoyALPIClY.jpg\\\",\\\"reports\\\\\\/images\\\\\\/bjm1weqb4yuczIdrCqx9TNHJTPEQaK8uvOzL82kI.jpg\\\"]\"', '21.584574, 105.811994', 'pending', NULL, '2026-05-18 19:28:57', '2026-05-18 19:28:57'),
(2, 4, 'electricity_water', 'Tiền điện cao', 'Cao quá', '\"[]\"', '21.584580, 105.811999', 'pending', NULL, '2026-05-18 21:26:44', '2026-05-18 21:26:44'),
(3, 1, 'environment', 'AAAAAAAAAAA', 'AAAAAAAAAAAAAAAAAAAA', '\"[]\"', NULL, 'pending', NULL, '2026-05-19 10:23:14', '2026-05-19 10:23:14'),
(4, 4, 'traffic', 'Hố đường dddddddddddddddddd', 'Có hố uuuuuuuu7uuuuuuuuuu', '\"[]\"', '21.584562, 105.811986', 'pending', NULL, '2026-05-19 10:31:18', '2026-05-19 10:31:18'),
(5, 6, 'environment', 'Eyhgrrgghjjj', 'Grthgthjhujhhhhgfddfgggh', '\"[\\\"reports\\\\\\/images\\\\\\/pDQxdvSFaFSNs7KvpoQ6lT0NYqA4v89wvnEQaPUF.jpg\\\"]\"', '21.584608, 105.811980', 'pending', NULL, '2026-05-19 10:37:11', '2026-05-19 10:37:11'),
(6, 5, 'environment', 'Tôi bị mất cái tay', 'Hth jdjdjd. D d d d t t t t. T dhsjsidid. Đ', '\"[\\\"reports\\\\\\/images\\\\\\/fUf9c5oDSEOufyv1x32z2LGboiY6GfBci9oaTAeQ.jpg\\\"]\"', '21.584673, 105.811947', 'pending', NULL, '2026-06-03 08:22:23', '2026-06-03 08:22:23'),
(7, 5, 'environment', 'Có ngừoi xả rác thải ra môi trường', 'Gây ô nhiễm vùng canh tác', '\"[\\\"reports\\\\\\/images\\\\\\/LOVe5qRiNDaFRK9mdxAO34RF8pIVMhD3r2nAgr23.jpg\\\"]\"', NULL, 'pending', NULL, '2026-06-19 01:28:21', '2026-06-19 01:28:21'),
(8, 5, 'environment', 'Công ty A xa nước thải ra môi trường', 'Xả nước thải chưa được xử lý, gây ảnh hưởng nghiêm trọng đên các vùng trồng xung quanh', '\"[\\\"reports\\\\\\/images\\\\\\/Eclq7Vd5vFqEJmwVnb8TVd7duVwDvlejurlbTjhC.jpg\\\"]\"', '21.584595, 105.811980', 'pending', NULL, '2026-06-19 01:34:37', '2026-06-19 01:34:37'),
(9, 5, 'environment', 'Công ty A xa nước thải ra môi trường', 'Xả nước thải chưa được xử lý, gây ảnh hưởng nghiêm trọng đên các vùng trồng xung quanh', '\"[\\\"reports\\\\\\/images\\\\\\/O4mHFtYVaZuHB4xAoGARSZdaw1nAMgIngbUKdJNX.jpg\\\"]\"', '21.584595, 105.811980', 'pending', NULL, '2026-06-19 01:34:40', '2026-06-19 01:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zalo_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `citizen_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('citizen','officer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'citizen',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `phone`, `zalo_id`, `avatar`, `citizen_code`, `address`, `role`, `is_verified`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Demo Account', '0993254213', 'demo_account_id', 'https://zlminiapp.hoangnhatminh.id.vn/assets/images/default.jpg', '0912345678', 'Thai Nguyen, Viet Nam', 'citizen', 1, NULL, NULL, NULL, '2026-05-18 21:13:37'),
(2, 'Hoàng Nhật Minh', '0398765432', '3263460122561303247', 'https://s120-ava-talk.zadn.vn/e/1/e/5/13/120/698b73d13def4d07a16b461a99b4a94e.jpg', NULL, 'Viet Nam', 'citizen', 0, '2026-06-23 01:35:58', NULL, '2026-05-18 03:31:54', '2026-06-23 01:35:58'),
(3, 'Thái', NULL, '4469876269853639565', 'https://s120-ava-talk.zadn.vn/6/3/b/3/11/120/3fddf003d8cc70385d9a871c78bd35c8.jpg', NULL, NULL, 'citizen', 0, '2026-05-19 00:46:44', NULL, '2026-05-18 21:20:46', '2026-05-19 00:46:44'),
(4, 'Tạ Huy', '0285', '1671547907787067575', 'https://s120-ava-talk.zadn.vn/4/3/a/a/3/120/4aa7d9ddf52536db0310a5793ba69e13.jpg', NULL, 'Hdff', 'citizen', 0, '2026-05-19 10:28:38', NULL, '2026-05-18 21:21:33', '2026-05-19 10:28:38'),
(5, 'Huy It', NULL, '8503746825267527814', 'https://s120-ava-talk.zadn.vn/b/5/0/0/1/120/9d0d63692546a9dca9803d0fcdeeb77a.jpg', NULL, NULL, 'citizen', 0, '2026-06-19 01:31:47', NULL, '2026-05-19 00:09:07', '2026-06-19 01:31:47'),
(6, 'Tống Nguyễn Quang Nhật', '346898765555', '5883320409951650080', 'https://s120-ava-talk.zadn.vn/3/a/5/3/40/120/a2f6fa169ea1be57d980fefa4fbc060e.jpg', NULL, 'Gghbd5jn', 'citizen', 0, '2026-05-19 10:35:02', NULL, '2026-05-19 10:35:02', '2026-05-19 10:37:31'),
(7, 'Thế Bằng', NULL, '861751673939577712', 'https://s120-ava-talk.zadn.vn/2/2/d/b/8/120/9fdc647190754a0db7d75a6a07c1341a.jpg', NULL, NULL, 'citizen', 0, '2026-06-02 14:14:50', NULL, '2026-05-23 02:31:20', '2026-06-02 14:14:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_chats`
--
ALTER TABLE `ai_chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_user`
--
ALTER TABLE `notification_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `notification_user_notification_id_user_id_unique` (`notification_id`,`user_id`),
  ADD KEY `notification_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `party_documents`
--
ALTER TABLE `party_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `party_votes`
--
ALTER TABLE `party_votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `party_vote_responses`
--
ALTER TABLE `party_vote_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profiles_code_unique` (`code`);

--
-- Indexes for table `profile_timelines`
--
ALTER TABLE `profile_timelines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_zalo_id_unique` (`zalo_id`),
  ADD UNIQUE KEY `users_citizen_code_unique` (`citizen_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_chats`
--
ALTER TABLE `ai_chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notification_user`
--
ALTER TABLE `notification_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `party_documents`
--
ALTER TABLE `party_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `party_votes`
--
ALTER TABLE `party_votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `party_vote_responses`
--
ALTER TABLE `party_vote_responses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `profile_timelines`
--
ALTER TABLE `profile_timelines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notification_user`
--
ALTER TABLE `notification_user`
  ADD CONSTRAINT `notification_user_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notification_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
