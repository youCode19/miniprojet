-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 22, 2025 at 02:57 AM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_neumorphic`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `type` enum('shipping','billing') COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `type`, `first_name`, `last_name`, `company`, `address1`, `address2`, `city`, `state`, `zip_code`, `country`, `phone`, `is_default`, `created_at`) VALUES
(1, 3, 'shipping', 'Jean', 'Client', NULL, '123 Rue Principale', NULL, 'Paris', NULL, '75001', 'France', NULL, 1, '2025-04-19 20:36:16'),
(2, 3, 'billing', 'Jean', 'Client', NULL, '123 Rue Principale', NULL, 'Paris', NULL, '75001', 'France', NULL, 1, '2025-04-19 20:36:16'),
(3, 4, 'shipping', 'Marie', 'Client', NULL, '456 Avenue Centrale', NULL, 'Lyon', NULL, '69002', 'France', NULL, 1, '2025-04-19 20:36:16'),
(4, NULL, 'billing', 'jkkh', 'hjkhj', 'hkvj', 'hjkhvjhjk', 'khkj', 'k', NULL, 'hhjkh', 'khh', '2', 0, '2025-04-21 22:46:44'),
(5, NULL, 'shipping', 'fdvfdvfvd', 'vfvdfvfd', '', 'fdvfvfd', '', 'vddf', NULL, 'dfdf', 'vvdv', '5245', 0, '2025-04-21 22:47:08'),
(6, 1, 'billing', 'fdvfdvfvd', 'hjkhj', '', 'hjkhvjhjk', '', 'vddf', NULL, 'dfdf', 'khh', '5245', 0, '2025-05-12 02:10:52'),
(8, 2, 'shipping', 'zdefz', 'aedzfr', 'adezfr', 'zadefzr', 'aezfr', 'dfsgbfbgh', NULL, '87522', 'fvdbgf', '1075524', 0, '2025-05-20 17:40:05'),
(9, 8, 'billing', 'zsdeczd', 'dcfv', 'scd fv', 'sxdcfd', 'xscdf', 'hjk', NULL, '44587', 'uykiyk', '525363', 0, '2025-05-20 21:47:02'),
(10, 8, 'shipping', 'yoyoy', 'mzod:', 'GVHGQO', 'RABAT', 'TAKADDOM', 'DEKOZSK?', NULL, '1712', 'JED?QWK', '5725275', 0, '2025-05-20 22:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `api_tokens`
--

DROP TABLE IF EXISTS `api_tokens`;
CREATE TABLE IF NOT EXISTS `api_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` json DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  KEY `idx_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('color','size','text','select') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `display_name`, `type`, `created_at`) VALUES
(1, 'color', 'Couleur', 'color', '2025-04-19 20:36:15'),
(2, 'size', 'Taille', 'size', '2025-04-19 20:36:15'),
(3, 'storage', 'Stockage', 'select', '2025-04-19 20:36:15'),
(4, 'material', 'Matériau', 'select', '2025-04-19 20:36:15');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

DROP TABLE IF EXISTS `attribute_values`;
CREATE TABLE IF NOT EXISTS `attribute_values` (
  `id` int NOT NULL AUTO_INCREMENT,
  `attribute_id` int NOT NULL,
  `value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_value` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attribute_id` (`attribute_id`,`value`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `attribute_id`, `value`, `display_value`, `color_code`, `created_at`) VALUES
(1, 1, 'black', 'Noir', '#000000', '2025-04-19 20:36:15'),
(2, 1, 'white', 'Blanc', '#FFFFFF', '2025-04-19 20:36:15'),
(3, 1, 'blue', 'Bleu', '#0000FF', '2025-04-19 20:36:15'),
(4, 1, 'red', 'Rouge', '#FF0000', '2025-04-19 20:36:15'),
(5, 2, 's', 'Petit (S)', NULL, '2025-04-19 20:36:15'),
(6, 2, 'm', 'Moyen (M)', NULL, '2025-04-19 20:36:15'),
(7, 2, 'l', 'Large (L)', NULL, '2025-04-19 20:36:15'),
(8, 2, 'xl', 'Très large (XL)', NULL, '2025-04-19 20:36:15'),
(9, 3, '64', '64GB', NULL, '2025-04-19 20:36:15'),
(10, 3, '128', '128GB', NULL, '2025-04-19 20:36:15'),
(11, 3, '256', '256GB', NULL, '2025-04-19 20:36:15'),
(12, 3, '512', '512GB', NULL, '2025-04-19 20:36:15'),
(13, 4, 'metal', 'Métal', NULL, '2025-04-19 20:36:15'),
(14, 4, 'plastic', 'Plastique', NULL, '2025-04-19 20:36:15'),
(15, 4, 'wood', 'Bois', NULL, '2025-04-19 20:36:15'),
(16, 4, 'glass', 'Verre', NULL, '2025-04-19 20:36:15');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `session_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` int NOT NULL,
  `variant_id` int DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `variant_id` (`variant_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_session` (`session_id`),
  KEY `idx_user_product` (`user_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`),
  KEY `idx_parent` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `image`, `display_order`, `is_featured`, `created_at`) VALUES
(1, NULL, 'Électronique', 'electronique', 'Appareils électroniques et gadgets high-tech', 'electronics.jpg', 1, 1, '2025-04-19 20:36:15'),
(2, NULL, 'Smartphones', 'smartphones', 'Smartphones et accessoires', 'smartphones.jpg', 2, 1, '2025-04-19 20:36:15'),
(3, NULL, 'Ordinateurs', 'ordinateurs', 'Ordinateurs portables et de bureau', 'computers.jpg', 3, 1, '2025-04-19 20:36:15'),
(4, NULL, 'Mode', 'mode', 'Vêtements et accessoires tendance', 'fashion.jpg', 4, 1, '2025-04-19 20:36:15'),
(5, NULL, 'Maison', 'maison', 'Meubles et décoration intérieure', 'home.jpg', 5, 1, '2025-04-19 20:36:15');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT '0.00',
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `valid_from` datetime NOT NULL,
  `valid_until` datetime NOT NULL,
  `max_uses` int DEFAULT NULL,
  `use_count` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `applies_to` enum('all','categories','products') COLLATE utf8mb4_unicode_ci DEFAULT 'all',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_code` (`code`),
  KEY `idx_validity` (`valid_from`,`valid_until`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `description`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount_amount`, `valid_from`, `valid_until`, `max_uses`, `use_count`, `is_active`, `applies_to`, `created_at`) VALUES
(1, 'WELCOME10', 'Réduction de bienvenue', 'percentage', 10.00, 50.00, NULL, '2025-04-19 21:36:16', '2025-05-19 21:36:16', 100, 0, 1, 'all', '2025-04-19 20:36:16'),
(2, 'FREESHIP', 'Livraison gratuite', 'fixed', 5.99, 30.00, NULL, '2025-04-19 21:36:16', '2025-05-04 21:36:16', NULL, 0, 1, 'all', '2025-04-19 20:36:16'),
(3, 'SUMMER25', 'Promo été', 'percentage', 25.00, 100.00, NULL, '2025-04-19 21:36:16', '2025-06-18 21:36:16', 50, 0, 1, 'all', '2025-04-19 20:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_applicables`
--

DROP TABLE IF EXISTS `coupon_applicables`;
CREATE TABLE IF NOT EXISTS `coupon_applicables` (
  `coupon_id` int NOT NULL,
  `entity_type` enum('category','product') COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int NOT NULL,
  PRIMARY KEY (`coupon_id`,`entity_type`,`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_uses`
--

DROP TABLE IF EXISTS `coupon_uses`;
CREATE TABLE IF NOT EXISTS `coupon_uses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `coupon_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `order_id` int NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_coupon` (`coupon_id`),
  KEY `idx_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
CREATE TABLE IF NOT EXISTS `downloads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int DEFAULT NULL,
  `download_count` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `download_access`
--

DROP TABLE IF EXISTS `download_access`;
CREATE TABLE IF NOT EXISTS `download_access` (
  `id` int NOT NULL AUTO_INCREMENT,
  `download_id` int NOT NULL,
  `order_item_id` int NOT NULL,
  `user_id` int NOT NULL,
  `download_count` int DEFAULT '0',
  `last_download_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_access` (`download_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `subscribed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `related_id` int DEFAULT NULL,
  `related_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled','refunded') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `subtotal` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `tax` decimal(10,2) DEFAULT '0.00',
  `shipping_cost` decimal(10,2) DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `shipping_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_user` (`user_id`),
  KEY `idx_order_number` (`order_number`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `subtotal`, `discount`, `tax`, `shipping_cost`, `total`, `payment_method`, `payment_status`, `shipping_method`, `tracking_number`, `notes`, `created_at`, `updated_at`, `shipping_address`) VALUES
(1, 2, 'CMD-682E039184C69', 'pending', 0.00, 0.00, 0.00, 0.00, 1999.95, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 16:47:13', '2025-05-21 16:47:13', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(2, 2, 'CMD-682E03C439F37', 'pending', 0.00, 0.00, 0.00, 0.00, 949.97, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 16:48:04', '2025-05-21 16:48:04', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(3, 2, 'CMD-682E0617F062F', 'pending', NULL, 0.00, 0.00, 0.00, 949.97, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 16:57:59', '2025-05-21 16:57:59', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(4, 2, 'CMD-682E0680293C0', 'pending', NULL, 0.00, 0.00, 0.00, 1899.99, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 16:59:44', '2025-05-21 16:59:44', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(5, 2, 'CMD-682E082A21D21', 'pending', NULL, 0.00, 0.00, 0.00, 949.97, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 17:06:50', '2025-05-21 17:06:50', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(6, 2, 'CMD-682E086A163A6', 'pending', NULL, 0.00, 0.00, 0.00, 549.98, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 17:07:54', '2025-05-21 17:07:54', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(7, 2, 'CMD-682E09B548DE8', 'pending', NULL, 0.00, 0.00, 0.00, 1159.98, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 17:13:25', '2025-05-21 17:13:25', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(8, 2, 'CMD-682E0A6F16960', 'pending', NULL, 0.00, 0.00, 0.00, 949.97, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 17:16:31', '2025-05-21 17:16:31', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(9, 2, 'CMD-682E0FCB0B107', 'pending', NULL, 0.00, 0.00, 0.00, 549.98, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 17:39:23', '2025-05-21 17:39:23', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(10, 2, 'CMD-682E12E65444F', 'pending', NULL, 0.00, 0.00, 0.00, 549.98, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 17:52:38', '2025-05-21 19:22:21', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(11, 2, 'CMD-682E3C97382C6', 'pending', NULL, 0.00, 0.00, 0.00, 5749.85, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 20:50:31', '2025-05-21 20:50:31', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(12, 2, 'CMD-682E3CDDE5933', 'pending', NULL, 0.00, 0.00, 0.00, 1709.96, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 20:51:41', '2025-05-21 20:51:41', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}'),
(13, 8, 'CMD-682E46763ECF2', 'pending', NULL, 0.00, 0.00, 0.00, 1359.97, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-21 21:32:38', '2025-05-21 21:32:38', '{\"id\":10,\"user_id\":8,\"type\":\"shipping\",\"first_name\":\"yoyoy\",\"last_name\":\"mzod:\",\"company\":\"GVHGQO\",\"address1\":\"RABAT\",\"address2\":\"TAKADDOM\",\"city\":\"DEKOZSK?\",\"state\":null,\"zip_code\":\"1712\",\"country\":\"JED?QWK\",\"phone\":\"5725275\",\"is_default\":0,\"created_at\":\"2025-05-20 23:05:52\"}'),
(14, 2, 'CMD-682E91F694009', 'pending', NULL, 0.00, 0.00, 0.00, 2099.98, 'cash_on_delivery', 'pending', NULL, NULL, NULL, '2025-05-22 02:54:46', '2025-05-22 02:54:46', '{\"id\":8,\"user_id\":2,\"type\":\"shipping\",\"first_name\":\"zdefz\",\"last_name\":\"aedzfr\",\"company\":\"adezfr\",\"address1\":\"zadefzr\",\"address2\":\"aezfr\",\"city\":\"dfsgbfbgh\",\"state\":null,\"zip_code\":\"87522\",\"country\":\"fvdbgf\",\"phone\":\"1075524\",\"is_default\":0,\"created_at\":\"2025-05-20 18:40:05\"}');

-- --------------------------------------------------------

--
-- Table structure for table `order_addresses`
--

DROP TABLE IF EXISTS `order_addresses`;
CREATE TABLE IF NOT EXISTS `order_addresses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `type` enum('shipping','billing') COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `variant_id` int DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `variant_id` (`variant_id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_product_variant` (`product_id`,`variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `name`, `price`, `quantity`, `total`, `image`) VALUES
(1, 1, 37, NULL, 'GoPro Hero 11 Black', 399.99, 5, 1999.95, 'gopro-11.jpg'),
(2, 2, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(3, 2, 31, NULL, 'Sony WH-1000XM5', 349.99, 1, 349.99, 'sony-wh1000xm5.jpg'),
(4, 2, 33, NULL, 'Garmin Venu 2 Plus', 399.99, 1, 399.99, 'garmin-venu2.jpg'),
(5, 3, 33, NULL, 'Garmin Venu 2 Plus', 399.99, 1, 399.99, 'garmin-venu2.jpg'),
(6, 3, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(7, 3, 31, NULL, 'Sony WH-1000XM5', 349.99, 1, 349.99, 'sony-wh1000xm5.jpg'),
(8, 4, 10, NULL, 'NOKIA5G', 1500.00, 1, 1500.00, 'nokia5G.jpg'),
(9, 4, 37, NULL, 'GoPro Hero 11 Black', 399.99, 1, 399.99, 'gopro-11.jpg'),
(10, 5, 33, NULL, 'Garmin Venu 2 Plus', 399.99, 1, 399.99, 'garmin-venu2.jpg'),
(11, 5, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(12, 5, 31, NULL, 'Sony WH-1000XM5', 349.99, 1, 349.99, 'sony-wh1000xm5.jpg'),
(13, 6, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(14, 6, 31, NULL, 'Sony WH-1000XM5', 349.99, 1, 349.99, 'sony-wh1000xm5.jpg'),
(15, 7, 34, NULL, 'DJI Mini 3 Pro', 759.99, 1, 759.99, 'dji-mini3.jpg'),
(16, 7, 33, NULL, 'Garmin Venu 2 Plus', 399.99, 1, 399.99, 'garmin-venu2.jpg'),
(17, 8, 33, NULL, 'Garmin Venu 2 Plus', 399.99, 1, 399.99, 'garmin-venu2.jpg'),
(18, 8, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(19, 8, 31, NULL, 'Sony WH-1000XM5', 349.99, 1, 349.99, 'sony-wh1000xm5.jpg'),
(20, 9, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(21, 9, 31, NULL, 'Sony WH-1000XM5', 349.99, 1, 349.99, 'sony-wh1000xm5.jpg'),
(22, 10, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(23, 10, 31, NULL, 'Sony WH-1000XM5', 349.99, 1, 349.99, 'sony-wh1000xm5.jpg'),
(24, 11, 32, NULL, 'Amazon Echo Studio', 199.99, 2, 399.98, 'echo-studio.jpg'),
(25, 11, 31, NULL, 'Sony WH-1000XM5', 349.99, 3, 1049.97, 'sony-wh1000xm5.jpg'),
(26, 11, 37, NULL, 'GoPro Hero 11 Black', 399.99, 4, 1599.96, 'gopro-11.jpg'),
(27, 11, 36, NULL, 'Steam Deck 256GB', 529.99, 2, 1059.98, 'steam-deck.jpg'),
(28, 11, 34, NULL, 'DJI Mini 3 Pro', 759.99, 1, 759.99, 'dji-mini3.jpg'),
(29, 11, 33, NULL, 'Garmin Venu 2 Plus', 399.99, 1, 399.99, 'garmin-venu2.jpg'),
(30, 11, 15, NULL, 'Xiaomi Poco X5', 299.99, 1, 299.99, 'pocox5.jpg'),
(31, 11, 16, NULL, 'Veste en laine grise', 179.99, 1, 179.99, 'veste-grise.jpg'),
(32, 12, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(33, 12, 31, NULL, 'Sony WH-1000XM5', 349.99, 1, 349.99, 'sony-wh1000xm5.jpg'),
(34, 12, 33, NULL, 'Garmin Venu 2 Plus', 399.99, 1, 399.99, 'garmin-venu2.jpg'),
(35, 12, 34, NULL, 'DJI Mini 3 Pro', 759.99, 1, 759.99, 'dji-mini3.jpg'),
(36, 13, 33, NULL, 'Garmin Venu 2 Plus', 399.99, 1, 399.99, 'garmin-venu2.jpg'),
(37, 13, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(38, 13, 34, NULL, 'DJI Mini 3 Pro', 759.99, 1, 759.99, 'dji-mini3.jpg'),
(39, 14, 32, NULL, 'Amazon Echo Studio', 199.99, 1, 199.99, 'echo-studio.jpg'),
(40, 14, 37, NULL, 'GoPro Hero 11 Black', 399.99, 1, 399.99, 'gopro-11.jpg'),
(41, 14, 10, NULL, 'NOKIA5G', 1500.00, 1, 1500.00, 'nokia5G.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 'À propos', 'a-propos', '<h2>Notre histoire</h2><p>Fondé en 2023, notre magasin s\'engage à fournir les meilleurs produits avec un service exceptionnel.</p>', 'À propos de nous', 'Découvrez l\'histoire et les valeurs de notre entreprise', 1, '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(2, 'Conditions d\'utilisation', 'conditions-utilisation', '<h2>Conditions générales</h2><p>Texte détaillé des conditions...</p>', 'Conditions d\'utilisation', 'Lisez nos conditions générales d\'utilisation', 1, '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(3, 'Politique de confidentialité', 'confidentialite', '<h2>Vos données</h2><p>Comment nous protégeons vos informations...</p>', 'Politique de confidentialité', 'Comment nous utilisons vos données personnelles', 1, '2025-04-19 20:36:16', '2025-04-19 20:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `compare_price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gallery` json DEFAULT NULL,
  `stock` int DEFAULT '0',
  `weight` decimal(10,2) DEFAULT NULL,
  `dimensions` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_bestseller` tinyint(1) DEFAULT '0',
  `is_new` tinyint(1) DEFAULT '1',
  `views` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_category` (`category_id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_price` (`price`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_is_bestseller` (`is_bestseller`),
  KEY `idx_is_new` (`is_new`),
  KEY `idx_stock` (`stock`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `sku`, `name`, `slug`, `short_description`, `description`, `price`, `compare_price`, `cost_price`, `image`, `gallery`, `stock`, `weight`, `dimensions`, `is_featured`, `is_bestseller`, `is_new`, `views`, `created_at`, `updated_at`) VALUES
(1, 2, 'SM-IP13-128', 'iPhone 13 128GB', 'iphone-13-128gb', 'Dernier smartphone Apple avec écran Super Retina XDR', 'L\'iPhone 13 propose un système photo avancé, une puce A15 Bionic ultra-rapide, une autonomie améliorée et une résistance à l\'eau IP68.', 899.99, 999.99, NULL, 'iphone13.jpg', NULL, 100, NULL, NULL, 1, 1, 1, 7, '2025-04-19 20:36:15', '2025-05-21 20:59:54'),
(2, 2, 'SM-S22-256', 'Samsung Galaxy S22', 'samsung-galaxy-s22', 'Écran Dynamic AMOLED 2X 120Hz', 'Avec son design élégant, son écran adaptatif 120Hz et son système photo professionnel, le Galaxy S22 redéfinit l\'expérience smartphone.', 799.99, 849.99, NULL, 'galaxys22.jpg', NULL, 100, NULL, NULL, 1, 1, 1, 2, '2025-04-19 20:36:15', '2025-05-19 16:19:05'),
(3, 3, 'ORD-MBPR-14', 'MacBook Pro 14\"', 'macbook-pro-14', 'Puissance professionnelle portable', 'Avec la puce M1 Pro ou M1 Max, le MacBook Pro 14\" offre des performances exceptionnelles pour les professionnels créatifs.', 1999.99, 2199.99, NULL, 'macbookpro14.jpg', NULL, 100, NULL, NULL, 1, 0, 1, 8, '2025-04-19 20:36:15', '2025-05-22 01:24:47'),
(4, 4, 'VET-CHN-BLK', 'Chemise en lin noir', 'chemise-lin-noir', 'Chemise élégante en lin 100%', 'Chemise respirante en lin de haute qualité, idéale pour les looks décontractés comme habillés.', 59.99, 79.99, NULL, 'chemise-noir.jpg', NULL, 100, NULL, NULL, 1, 1, 1, 5, '2025-04-19 20:36:15', '2025-05-22 00:59:16'),
(5, 5, 'DEC-LMP-NEO', 'Lampe néon design', 'lampe-neon-design', 'Éclairage LED moderne avec style rétro', 'Cette lampe néon apporte une touche rétro-futuriste à votre intérieur avec ses tubes LED personnalisables.', 129.99, NULL, NULL, 'lampe-neon.jpg', NULL, 100, NULL, NULL, 1, 0, 1, 8, '2025-04-19 20:36:15', '2025-05-22 01:07:39'),
(6, 2, 'SM-PX6-256', 'Google Pixel 6', 'google-pixel-6', 'Smartphone Android avec appareil photo révolutionnaire', 'Le Pixel 6 offre des photos professionnelles grâce à son système photo avancé et fonctionne avec le processeur Google Tensor.', 599.99, 699.99, NULL, 'pixel6.jpg', NULL, 85, NULL, NULL, 1, 1, 1, 5, '2025-04-20 08:15:22', '2025-05-22 01:15:28'),
(7, 3, 'ORD-SF17-I7', 'Dell XPS 17', 'dell-xps-17', 'Ordinateur portable haut de gamme avec écran 4K', 'Le XPS 17 combine puissance et élégance avec son processeur Intel Core i7 et son écran InfinityEdge 4K.', 899.99, 999.99, NULL, 'xps17.jpg', NULL, 45, NULL, NULL, 1, 0, 1, 6, '2025-04-21 10:30:45', '2025-05-22 01:22:45'),
(8, 4, 'VET-JNS-BLU', 'Jeans slim bleu', 'jeans-slim-bleu', 'Jeans élégant et confortable', 'Jeans en coton stretch avec coupe slim, résistant et confortable pour un usage quotidien.', 79.99, 89.99, NULL, 'jeans-bleu.jpg', NULL, 120, NULL, NULL, 1, 1, 1, 9, '2025-04-22 13:45:12', '2025-05-22 00:54:51'),
(9, 5, 'DEC-CSH-WHT', 'Coussin blanc design', 'coussin-blanc-design', 'Coussin décoratif en lin blanc', 'Coussin décoratif avec motif géométrique subtil, parfait pour ajouter une touche moderne à votre intérieur.', 29.99, 39.99, NULL, 'coussin-blanc.jpg', NULL, 200, NULL, NULL, 1, 0, 1, 4, '2025-04-23 15:20:18', '2025-05-22 00:53:28'),
(10, 2, 'Partagez ce produit Nokia 5.1 Plus', 'NOKIA5G', 'nokia5g', 'Plus – 5,8&quot; – 32 Go – 3 Go RAM – Noir', 'Le grand écran 5,8 pouces HD+ avec un format 19:9 décuple votre expérience de visionnage. Un design raffiné et des détails qui témoignent d&#039;un savoir-faire de haut niveau.', 1500.00, 1600.00, 1700.00, 'nokia5G.jpg', NULL, 100, NULL, NULL, 1, 0, 1, 4, '2025-05-19 02:53:46', '2025-05-21 21:27:42'),
(11, 2, 'SM-OP10-256', 'OnePlus 10 Pro', 'oneplus-10-pro', 'Écran Fluid AMOLED 120Hz', 'Smartphone haut de gamme avec charge ultra-rapide 80W et système photo Hasselblad.', 899.99, 999.99, NULL, 'oneplus10.jpg', NULL, 75, NULL, NULL, 1, 1, 1, 6, '2025-04-24 09:05:37', '2025-05-22 01:24:41'),
(12, 3, 'ORD-IPAD-AIR', 'iPad Air', 'ipad-air', 'Puissant. Coloré. Polyvalent.', 'Avec la puce M1, l\'iPad Air offre des performances professionnelles dans un design léger et coloré.', 649.99, 699.99, NULL, 'ipad-air.jpg', NULL, 60, NULL, NULL, 1, 0, 1, 7, '2025-04-25 12:40:55', '2025-05-19 21:55:24'),
(13, 4, 'VET-MOC-BRW', 'Mocassins en cuir brun', 'mocassins-cuir-brun', 'Chaussures élégantes en cuir véritable', 'Mocassins en cuir pleine fleur avec semelle confortable, parfaits pour un style à la fois décontracté et élégant.', 129.99, 149.99, NULL, 'mocassins-brun.jpg', NULL, 50, NULL, NULL, 1, 1, 1, 6, '2025-04-26 14:25:40', '2025-05-22 00:58:03'),
(14, 5, 'DEC-TBL-OAK', 'Table basse chêne', 'table-basse-chene', 'Table basse design en chêne massif', 'Table basse moderne avec plateau en chêne massif et piètement métallique, dimensions 120x60x45 cm.', 299.99, 349.99, NULL, 'table-chene.jpg', NULL, 25, NULL, NULL, 1, 0, 1, 7, '2025-04-27 16:10:15', '2025-05-19 09:35:42'),
(15, 2, 'SM-XP5-128', 'Xiaomi Poco X5', 'xiaomi-poco-x5', 'Écran AMOLED 120Hz', 'Smartphone milieu de gamme avec excellent rapport qualité-prix et grande autonomie.', 299.99, 349.99, NULL, 'pocox5.jpg', NULL, 150, NULL, NULL, 1, 1, 1, 15, '2025-04-28 11:30:28', '2025-05-22 01:09:53'),
(16, 4, 'VET-VST-GRY', 'Veste en laine grise', 'veste-laine-grise', 'Veste élégante pour toutes occasions', 'Veste en laine pure avec coupe moderne, parfaite pour les saisons fraîches. Disponible en plusieurs tailles.', 179.99, 199.99, NULL, 'veste-grise.jpg', NULL, 40, NULL, NULL, 1, 1, 1, 12, '2025-04-29 13:45:50', '2025-05-22 01:01:35'),
(31, 1, 'AUD-SNY-WF1', 'Sony WH-1000XM5', 'sony-wh1000xm5', 'Casque antibruit haut de gamme avec IA', 'Les WH-1000XM5 de Sony offrent une réduction de bruit exceptionnelle, un son haute résolution et une autonomie de 30 heures avec la technologie d\'optimisation du bruit intelligente.', 349.99, 399.99, 280.00, 'sony-wh1000xm5.jpg', NULL, 50, 250.00, '20.5x24.5x7.5 cm', 1, 1, 1, 5, '2025-05-21 00:52:38', '2025-05-22 01:10:42'),
(32, 1, 'SMT-AMZ-ECH', 'Amazon Echo Studio', 'amazon-echo-studio', 'Enceinte intelligente haute fidélité', 'L\'Echo Studio offre un son 3D immersif avec Dolby Atmos, reconnaissance vocale Alexa et s\'adapte automatiquement à l\'acoustique de votre pièce.', 199.99, 229.99, 150.00, 'echo-studio.jpg', NULL, 75, 3.50, '20.6x17.5x17.5 cm', 1, 0, 1, 7, '2025-05-21 00:52:38', '2025-05-22 02:06:47'),
(33, 1, 'WCH-GAL-WT4', 'Garmin Venu 2 Plus', 'garmin-venu-2-plus', 'Montre santé avec assistant vocal', 'Suivi avancé de la santé, ECG, oxymétrie de sang, stockage de musique et assistance vocale avec cette montre connectée haut de gamme.', 399.99, 449.99, 320.00, 'garmin-venu2.jpg', NULL, 40, 51.00, '43.6x43.6x12.2 mm', 1, 1, 1, 6, '2025-05-21 00:52:38', '2025-05-22 01:11:29'),
(34, 1, 'DRN-DJI-MN3', 'DJI Mini 3 Pro', 'dji-mini-3-pro', 'Drone compact avec caméra 4K', 'Drone ultra-léger (<249g) avec capteur 1/1.3\", enregistrement 4K/60fps, transmission OcuSync 3.0 et 34 minutes d\'autonomie.', 759.99, 899.99, 600.00, 'dji-mini3.jpg', NULL, 25, 249.00, '14.5x8.7x3.6 cm (pliée)', 1, 0, 1, 6, '2025-05-21 00:52:38', '2025-05-22 02:07:01'),
(35, 1, 'EBK-KND-PW5', 'Kindle Paperwhite 5', 'kindle-paperwhite-5', 'Liseuse étanche avec écran 6.8\"', 'Écran haute résolution 300 ppi, éclairage réglable, étanche IPX8 et des semaines d\'autonomie pour lire partout.', 139.99, 159.99, 100.00, 'kindle-pw5.jpg', NULL, 90, 205.00, '17.4x12.5x0.8 cm', 0, 1, 1, 3, '2025-05-21 00:52:38', '2025-05-22 01:00:23'),
(36, 1, 'GAM-STE-DCK', 'Steam Deck 256GB', 'steam-deck-256gb', 'Console PC portable par Valve', 'Jouez à votre bibliothèque Steam n\'importe où avec cet ordinateur portable optimisé pour le jeu avec écran 7\" 60Hz et contrôles intégrés.', 529.99, 599.99, 450.00, 'steam-deck.jpg', NULL, 35, 669.00, '29.8x11.7x4.9 cm', 1, 1, 1, 1, '2025-05-21 00:52:38', '2025-05-22 01:13:49'),
(37, 1, 'CAM-GP-H11', 'GoPro Hero 11 Black', 'gopro-hero-11', 'Caméra d\'action 5.3K', 'Enregistrement vidéo 5.3K60, stabilisation HyperSmooth 5.0, écran tactile avant et arrière, résistance à l\'eau sans boîtier.', 399.99, 499.99, 300.00, 'gopro-11.jpg', NULL, 60, 153.00, '7.1x5.5x3.3 cm', 1, 0, 1, 3, '2025-05-21 00:52:38', '2025-05-22 01:24:52');

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE IF NOT EXISTS `product_attributes` (
  `product_id` int NOT NULL,
  `attribute_id` int NOT NULL,
  PRIMARY KEY (`product_id`,`attribute_id`),
  KEY `attribute_id` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`product_id`, `attribute_id`) VALUES
(1, 1),
(2, 1),
(4, 1),
(4, 2),
(1, 3),
(2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

DROP TABLE IF EXISTS `product_specifications`;
CREATE TABLE IF NOT EXISTS `product_specifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_specifications`
--

INSERT INTO `product_specifications` (`id`, `product_id`, `name`, `value`, `display_order`) VALUES
(1, 1, 'Écran', '6.1\" Super Retina XDR', 1),
(2, 1, 'Processeur', 'A15 Bionic', 2),
(3, 1, 'Système d\'exploitation', 'iOS 15', 3),
(4, 1, 'Batterie', 'Jusqu\'à 19h de vidéo', 4),
(5, 2, 'Écran', '6.1\" Dynamic AMOLED 2X 120Hz', 1),
(6, 2, 'Processeur', 'Exynos 2200', 2),
(7, 2, 'Système d\'exploitation', 'Android 12', 3),
(8, 3, 'Processeur', 'Apple M1 Pro 8-core', 1),
(9, 3, 'Mémoire', '16GB unifiée', 2),
(10, 3, 'Stockage', '512GB SSD', 3);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE IF NOT EXISTS `product_variants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `name`, `sku`, `price`, `stock`, `image`, `created_at`) VALUES
(1, 1, 'iPhone 13 128GB Bleu', 'SM-IP13-128-BLUE', 899.99, 15, 'iphone13-blue.jpg', '2025-04-19 20:36:15'),
(2, 1, 'iPhone 13 128GB Rouge', 'SM-IP13-128-RED', 899.99, 10, 'iphone13-red.jpg', '2025-04-19 20:36:15'),
(3, 2, 'Galaxy S22 256GB Noir', 'SM-S22-256-BLK', 799.99, 10, 'galaxys22-black.jpg', '2025-04-19 20:36:15'),
(4, 2, 'Galaxy S22 256GB Vert', 'SM-S22-256-GRN', 799.99, 20, 'galaxys22-green.jpg', '2025-04-19 20:36:15'),
(5, 4, 'Chemise lin noir Taille M', 'VET-CHN-BLK-M', 59.99, 40, 'chemise-noir-m.jpg', '2025-04-19 20:36:15'),
(6, 4, 'Chemise lin noir Taille L', 'VET-CHN-BLK-L', 59.99, 60, 'chemise-noir-l.jpg', '2025-04-19 20:36:15');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `product_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `rating` tinyint NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `idx_product` (`product_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_approved` (`is_approved`),
  KEY `idx_product_user` (`product_id`,`user_id`)
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `order_id`, `rating`, `title`, `comment`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 3, 1, NULL, 5, 'Excellent téléphone', 'Très satisfait de mon achat, le téléphone est rapide et l\'autonomie est excellente.', 1, '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(2, 4, 1, NULL, 4, 'Très bon produit', 'Qualité irréprochable mais un peu cher par rapport à la concurrence.', 1, '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(3, 3, 4, NULL, 5, 'Chemise parfaite', 'Tissu très agréable et coupe impeccable.', 1, '2025-04-19 20:36:16', '2025-04-19 20:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `review_replies`
--

DROP TABLE IF EXISTS `review_replies`;
CREATE TABLE IF NOT EXISTS `review_replies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `review_id` int NOT NULL,
  `user_id` int NOT NULL,
  `reply` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_review` (`review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci,
  `group` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `idx_key` (`key`),
  KEY `idx_group` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'store_name', 'MonShop Neumorphic', 'general', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(2, 'store_email', 'contact@monshop.com', 'general', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(3, 'store_phone', '+33123456789', 'general', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(4, 'store_address', '123 Rue du Commerce, Paris', 'general', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(5, 'currency', 'EUR', 'general', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(6, 'currency_symbol', '€', 'general', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(7, 'shipping_cost', '4.99', 'shipping', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(8, 'free_shipping_threshold', '50.00', 'shipping', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(9, 'tax_rate', '20.00', 'tax', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(10, 'theme_color_primary', '#6c5ce7', 'theme', '2025-04-19 20:36:16', '2025-04-19 20:36:16'),
(11, 'theme_color_secondary', '#a29bfe', 'theme', '2025-04-19 20:36:16', '2025-04-19 20:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.jpg',
  `role` enum('superadmin','admin','client') COLLATE utf8mb4_unicode_ci DEFAULT 'client',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `password_reset_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `failed_attempts` int DEFAULT '0',
  `locked_until` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `avatar`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`, `password_reset_token`, `token_expires_at`, `failed_attempts`, `locked_until`) VALUES
(1, 'Super Admin', 'margangstar9@gmail.com', 'younes19', 'default.jpg', 'client', 1, '2025-05-12 03:05:16', '2025-04-19 20:36:15', '2025-05-21 19:27:45', NULL, NULL, 0, NULL),
(2, 'Admin', 'admin@shop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'default.jpg', 'admin', 1, '2025-05-22 03:54:15', '2025-04-19 20:36:15', '2025-05-22 02:54:15', NULL, NULL, 0, NULL),
(3, 'Jean Client', 'jean@client.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'default.jpg', 'client', 1, NULL, '2025-04-19 20:36:15', '2025-04-19 20:36:15', NULL, NULL, 0, NULL),
(4, 'Marie Client', 'marie@client.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'default.jpg', 'client', 1, NULL, '2025-04-19 20:36:15', '2025-04-19 20:36:15', NULL, NULL, 0, NULL),
(5, 'Admin Test', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'default.jpg', 'admin', 1, NULL, '2025-05-13 00:03:36', '2025-05-13 00:03:36', NULL, NULL, 0, NULL),
(6, 'youness', 'mamama@gmail.com', '$2y$10$fkrdJ8hRjqPgMK9ZT3S27.WzIeq/iSH9EzPbEfFni2eJJIdrLVOrW', 'default.jpg', 'client', 1, NULL, '2025-05-18 20:00:57', '2025-05-18 20:00:57', NULL, NULL, 0, NULL),
(7, 'younes', 'superadmin@shop.com', '$2y$10$ZoIO0rdIKwKBoTWM5kHc.uUnZtZhLSICV/iXrYD3Fta0mzgL9Gos2', 'default.jpg', 'client', 1, '2025-05-21 14:51:59', '2025-05-18 21:43:13', '2025-05-21 13:51:59', NULL, NULL, 0, NULL),
(8, 'oy41121', 'youyou@gam.com', '$2y$10$CuKF9T54Ldks3ht5.KSVzeGrM/Jp5KBS74/E.LJRF2vJOWv90.6Ae', 'default.jpg', 'client', 1, '2025-05-22 03:52:30', '2025-05-19 01:17:44', '2025-05-22 02:52:30', NULL, NULL, 0, NULL),
(9, 'hii', 'idan@gmail.com', '$2y$10$z.6pArTROa1KWHd4t4OOtemiAKxK6XlJjQ9aKixYfRIOw2SIc6FlW', 'default.jpg', 'client', 1, NULL, '2025-05-20 22:08:03', '2025-05-20 22:08:03', NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

DROP TABLE IF EXISTS `user_activities`;
CREATE TABLE IF NOT EXISTS `user_activities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `activity_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` mediumtext COLLATE utf8mb4_unicode_ci,
  `reference_id` int DEFAULT NULL,
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_activity` (`activity_type`),
  KEY `idx_reference` (`reference_type`,`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Ma liste de souhaits',
  `is_public` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_items`
--

DROP TABLE IF EXISTS `wishlist_items`;
CREATE TABLE IF NOT EXISTS `wishlist_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `wishlist_id` int NOT NULL,
  `product_id` int NOT NULL,
  `variant_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlist_id` (`wishlist_id`,`product_id`,`variant_id`),
  KEY `product_id` (`product_id`),
  KEY `variant_id` (`variant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products` ADD FULLTEXT KEY `idx_search` (`name`,`short_description`,`description`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD CONSTRAINT `api_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `coupon_applicables`
--
ALTER TABLE `coupon_applicables`
  ADD CONSTRAINT `coupon_applicables_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_uses`
--
ALTER TABLE `coupon_uses`
  ADD CONSTRAINT `coupon_uses_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_uses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `coupon_uses_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `downloads`
--
ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `download_access`
--
ALTER TABLE `download_access`
  ADD CONSTRAINT `download_access_ibfk_1` FOREIGN KEY (`download_id`) REFERENCES `downloads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `download_access_ibfk_2` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `download_access_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_addresses`
--
ALTER TABLE `order_addresses`
  ADD CONSTRAINT `order_addresses_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attributes_ibfk_2` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `review_replies`
--
ALTER TABLE `review_replies`
  ADD CONSTRAINT `review_replies_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD CONSTRAINT `user_activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD CONSTRAINT `wishlist_items_ibfk_1` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
