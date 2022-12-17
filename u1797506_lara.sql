-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 08, 2022 at 03:14 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `point_of_sale`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','NON-ACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON-ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `store_id`, `photo`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Category 1', 'category-1', 1, 'assets/category/97ToMv-1.png', 'NON-ACTIVE', '2022-11-21 14:18:49', '2022-12-06 14:10:05'),
(2, 'Category 2', 'category-2', 1, 'assets/category/SLeKot-2.png', 'ACTIVE', '2022-11-21 14:18:49', '2022-12-06 14:11:28'),
(3, 'Category 3', 'category-3', 1, 'assets/category/4NbwXP-3.png', 'ACTIVE', '2022-11-21 14:18:49', '2022-12-06 14:09:15'),
(4, 'Category 4', 'category-4', 2, 'assets/category/UnawyH-Untitled-5.png', 'ACTIVE', '2022-11-21 14:18:49', '2022-12-06 15:18:05'),
(5, 'Category 5 edit', 'category-5', 2, 'assets/category/uNVKbZ-39-Update-Pengajuan-Dana.png', 'ACTIVE', '2022-11-21 14:18:49', '2022-12-06 14:20:34'),
(7, 'Category 7', 'category-7', 3, 'assets/category/OaH3bu-c.png', 'NON-ACTIVE', '2022-11-21 14:18:49', '2022-11-21 14:18:49'),
(8, 'Category 8', 'category-8', 3, 'assets/category/XNyoFX-DO.jpeg', 'NON-ACTIVE', '2022-11-21 14:18:49', '2022-11-21 14:18:49'),
(16, 'NEW category api', 'new-category-api', 2, 'assets/category/0nttlk-3-Data-User.png', 'ACTIVE', '2022-12-06 15:31:26', '2022-12-06 15:31:26');

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
(5, '2022_10_15_124705_create_stores_table', 1),
(6, '2022_10_18_154408_create_categories_table', 2),
(7, '2022_10_20_233921_create_products_table', 3),
(14, '2022_10_23_161358_create_orders_table', 4),
(15, '2022_10_23_161424_create_order_details_table', 4),
(16, '2022_10_23_161704_create_order_temporaries_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `total_bayar` int(11) NOT NULL,
  `kembalian` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `store_id`, `user_id`, `order_id`, `total`, `description`, `date`, `discount`, `total_bayar`, `kembalian`, `created_at`, `updated_at`) VALUES
(1, 2, 11, 'INV-2022-12-04-22:08:48', 17696, NULL, '2022-12-04', 1500, 20000, 3804, '2022-12-04 15:08:48', '2022-12-04 15:08:48'),
(2, 2, 11, 'INV-2022-12-04-22:09:58', 32000, NULL, '2022-12-05', 1500, 50000, 19500, '2022-12-04 15:09:58', '2022-12-04 15:09:58'),
(3, 1, 1, 'INV-2022-12-04-22:17:01', 11544, NULL, '2022-12-04', 1000, 15000, 4456, '2022-12-04 15:17:01', '2022-12-04 15:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_name`, `product_code`, `price`, `quantity`, `sub_total`, `created_at`, `updated_at`) VALUES
(1, 'INV-2022-12-04-22:08:48', 'Product 7', 'Product 7', 5000, 2, 10000, '2022-12-04 15:08:48', '2022-12-04 15:08:48'),
(2, 'INV-2022-12-04-22:08:48', 'Product 8', 'Product 8', 3000, 2, 6000, '2022-12-04 15:08:48', '2022-12-04 15:08:48'),
(3, 'INV-2022-12-04-22:08:48', 'Mechelle Sawyer', 'Recusandae Min', 424, 4, 1696, '2022-12-04 15:08:48', '2022-12-04 15:08:48'),
(4, 'INV-2022-12-04-22:09:58', 'Product 9', 'Product 9', 2000, 6, 12000, '2022-12-04 15:09:58', '2022-12-04 15:09:58'),
(5, 'INV-2022-12-04-22:09:58', 'Product 10', 'Product 10', 2000, 10, 20000, '2022-12-04 15:09:58', '2022-12-04 15:09:58'),
(6, 'INV-2022-12-04-22:17:01', 'Product 1', 'Product 1', 1000, 1, 1000, '2022-12-04 15:17:01', '2022-12-04 15:17:01'),
(7, 'INV-2022-12-04-22:17:01', 'Product 7', 'Product 7', 5000, 1, 5000, '2022-12-04 15:17:01', '2022-12-04 15:17:01'),
(8, 'INV-2022-12-04-22:17:01', 'Product 8', 'Product 8', 3000, 1, 3000, '2022-12-04 15:17:01', '2022-12-04 15:17:01'),
(9, 'INV-2022-12-04-22:17:01', 'Shelby Alford', 'NEW PRODUCT 20', 120, 1, 120, '2022-12-04 15:17:01', '2022-12-04 15:17:01'),
(10, 'INV-2022-12-04-22:17:01', 'Product 9', 'Product 9', 2000, 1, 2000, '2022-12-04 15:17:01', '2022-12-04 15:17:01'),
(11, 'INV-2022-12-04-22:17:01', 'Mechelle Sawyer', 'Recusandae Min', 424, 1, 424, '2022-12-04 15:17:01', '2022-12-04 15:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `order_temporaries`
--

CREATE TABLE `order_temporaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
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

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('admin@admin.com', '$2y$10$Ikb8DHSwnZ0H7V5QLrMzMufbedsq0lePnUY8gF8iCllTDJWAm2IVO', '2022-10-15 08:32:22');

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
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(2, 'App\\Models\\User', 1, 'authToken', '3f83770641e9a8a9655f261214e9647f1c03a44843a5f65a9fe30adf7e69aa63', '[\"*\"]', '2022-12-06 13:04:25', NULL, '2022-12-05 15:10:06', '2022-12-06 13:04:25'),
(3, 'App\\Models\\User', 11, 'authToken', '7b19cf02788943279c132762d645b1e3fb461a18270892317a4cbcd69ba09638', '[\"*\"]', NULL, NULL, '2022-12-06 13:28:45', '2022-12-06 13:28:45'),
(4, 'App\\Models\\User', 11, 'authToken', '900df87432b0e3aff25ce4b7ee0bd71c94a96852a841baf57483a79577832782', '[\"*\"]', '2022-12-08 13:46:27', NULL, '2022-12-06 13:30:48', '2022-12-08 13:46:27');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `old_price` int(11) NOT NULL,
  `new_price` int(11) NOT NULL,
  `limit_stock` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `type` enum('PCS','PACK','LITER','ROLL','METER','KILOGRAM') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','NON-ACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON-ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `name`, `slug`, `category_id`, `store_id`, `old_price`, `new_price`, `limit_stock`, `stock`, `type`, `description`, `photo`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Product 1', 'Product 1', 'product-1', 1, 1, 2000, 1000, 10, 99, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:23:56', '2022-12-04 15:17:01'),
(2, 'Product 2', 'Product 2', 'product-2', 1, 1, 2000, 1500, 5, 50, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:27:24', '2022-12-05 15:39:18'),
(3, 'Product 3', 'Product 3', 'product-3', 2, 1, 10000, 5000, 10, 100, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:29:57', '2022-11-21 14:29:57'),
(4, 'Product 4', 'Product 4', 'product-4', 2, 1, 6000, 3000, 10, 100, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:30:37', '2022-11-21 14:30:37'),
(5, 'Product 5', 'Product 5', 'product-5', 3, 1, 8000, 4000, 10, 100, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:31:08', '2022-11-21 14:31:08'),
(6, 'Product 6', 'Product 6', 'product-6', 3, 1, 2000, 1500, 10, 100, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:31:43', '2022-11-21 14:31:43'),
(7, 'Product 7', 'Product 7', 'product-7', 4, 2, 7000, 6000, 10, 20, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:33:45', '2022-12-05 14:38:47'),
(8, 'Product 8', 'Product 8', 'product-8', 4, 2, 6000, 3000, 10, 97, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:34:12', '2022-12-04 15:17:01'),
(9, 'Product 9', 'Product 9', 'product-9', 5, 2, 4000, 2000, 10, 93, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:34:43', '2022-12-04 15:17:01'),
(10, 'Product 10', 'Product 10', 'product-10', 5, 2, 6000, 2000, 10, 90, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:35:06', '2022-12-04 15:09:58'),
(11, 'NEW PRODUCT 1', 'NEW PRODUCT 1', 'new-product-1', 7, 3, 5000, 2500, 10, 100, 'PCS', NULL, NULL, 'ACTIVE', '2022-11-21 14:51:14', '2022-11-21 14:51:14'),
(14, 'Product 100', 'Guy Gray', 'guy-gray', 1, 1, 405, 974, 74, 17, 'ROLL', 'Omnis necessitatibus ea illo quidem ad deserunt', NULL, 'NON-ACTIVE', '2022-11-23 13:42:55', '2022-11-23 13:42:55'),
(15, 'NEW PRODUCT 20', 'Shelby Alford', 'shelby-alford', 5, 2, 815, 120, 72, 62, 'PACK', 'Minima quia quidem enim repellendus Sunt blanditiis voluptatibus fugiat dolor consequatur Non et adipisci rem sit quo vel nisi fugiat', NULL, 'ACTIVE', '2022-11-23 14:16:16', '2022-12-04 15:17:01'),
(16, 'Recusandae Min', 'Mechelle Sawyer', 'mechelle-sawyer', 5, 2, 644, 424, 51, 13, 'METER', 'Animi in voluptatum labore ullam sunt aute', NULL, 'ACTIVE', '2022-11-23 14:18:06', '2022-12-04 15:17:01'),
(17, 'Reprehenderit', 'Laurel Pollard', 'laurel-pollard', 5, 2, 579, 922, 25, 15, 'PCS', 'Laboris amet ut a perferendis commodo minima officia', NULL, 'ACTIVE', '2022-11-23 14:40:40', '2022-12-06 15:22:19');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `status` enum('ACTIVE','NON-ACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `slug`, `store_code`, `location`, `description`, `discount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'STORE-1', 'store-1', 'STORE-1', 'Optio in porro incidunt molestiae rerum sint id', 'Ipsum aperiam voluptatem', 1000, 'ACTIVE', '2022-11-21 14:12:34', '2022-11-21 14:12:34'),
(2, 'STORE-2', 'store-2', 'STORE-2', 'Et itaque officia ac', 'Aperiam sunt id aut deleniti est illum excepturi veniam', 1500, 'ACTIVE', '2022-11-21 14:13:30', '2022-12-04 14:59:11'),
(3, 'STORE-3', 'store-3', 'STORE-3', 'Laboris ullamco accusantium', 'Voluptatem minima veritatis dolorem sit velit sunt similique quos minus eum', 0, 'ACTIVE', '2022-11-21 14:14:06', '2022-11-21 14:14:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('ACTIVE','NON-ACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NON-ACTIVE',
  `roles` enum('ADMINISTRATOR','MANAGER','CASHIER') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `store_id`, `email_verified_at`, `password`, `status`, `roles`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ADMINISTRATOR', 'admin@admin.com', NULL, '2022-10-15 06:15:38', '$2y$10$Fxr/sMwi2/1h3.Mnzmn/a.uu/HhW3N5WosRcNlmjxfHleQZvTPmPS', 'ACTIVE', 'ADMINISTRATOR', NULL, '2022-10-15 06:13:09', '2022-10-15 06:15:38'),
(9, 'Buckminster Weiss', 'manager@manager.com', 2, '2022-11-19 15:19:43', '$2y$10$qPBKtnOLQR2eMVkt4vtYdOejZQ1BAaL4i/W7zqyibbplY3pqSmrAW', 'ACTIVE', 'MANAGER', NULL, '2022-10-18 07:36:02', '2022-11-19 15:19:43'),
(10, 'Barry Moon', 'dikof@mailinator.com', 3, NULL, '$2y$10$u88bvfg/l1Y7exLn69YlC.RMyV.0jhOCfXgRVTvHIZrj9YLyu90Mi', 'NON-ACTIVE', 'MANAGER', NULL, '2022-10-18 07:36:11', '2022-10-18 08:33:12'),
(11, 'Kasir 1', 'kasir@kasir.com', 2, '2022-12-04 08:24:44', '$2y$10$hdAnj8kiWQgMQtcI2BgBaOZA3RlL9LedN6J/L09FNHjdC/GGyIINC', 'ACTIVE', 'CASHIER', NULL, '2022-10-18 07:36:16', '2022-12-04 08:24:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_temporaries`
--
ALTER TABLE `order_temporaries`
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
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`product_code`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stores_store_code_unique` (`store_code`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_temporaries`
--
ALTER TABLE `order_temporaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
