-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET NAMES utf8 */
;
/*!50503 SET NAMES utf8mb4 */
;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */
;
/*!40103 SET TIME_ZONE='+00:00' */
;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */
;

-- Dumping database structure for carshop
CREATE DATABASE IF NOT EXISTS `carshop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `carshop`;

-- Dumping structure for table carshop.users
CREATE TABLE IF NOT EXISTS `users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `birthday` date DEFAULT NULL,
    `usertype` enum('admin', 'user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `pob` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `receiver_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `receiver_phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `receiver_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    KEY `pob_index` (`pob`)
) ENGINE = InnoDB AUTO_INCREMENT = 12 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table carshop.users: ~3 rows (approximately)
REPLACE INTO
    `users` (
        `id`,
        `name`,
        `email`,
        `phone`,
        `birthday`,
        `usertype`,
        `password`,
        `pob`,
        `receiver_name`,
        `receiver_phone`,
        `receiver_address`
    )
VALUES (
        0,
        'Admin',
        'admin@vfast.com',
        '0900000000',
        NULL,
        'admin',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        NULL,
        NULL,
        NULL,
        NULL
    ),
    (
        10,
        'baodeptrai',
        'giabao4785@gmail.com',
        NULL,
        NULL,
        'user',
        '$2y$10$cBECgOqpTnaXlM28IZO18eTFNIXFS2DnwqBMPSkuBVr5QGeGwoNPC',
        NULL,
        'Lam Dang Gia Bao',
        '0843257392',
        '70 Tô Ký, Quận 12, TP Hồ Chí Minh'
    ),
    (
        11,
        'bing reward',
        'jabao4785@gmail.com',
        '0843257392',
        '2007-01-02',
        'user',
        '123',
        'Tây Ninh',
        NULL,
        NULL,
        NULL
    );

-- Dumping structure for table carshop.product
CREATE TABLE IF NOT EXISTS `product` (
    `product_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `product_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `dimensions` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `battery_capacity` decimal(10, 2) DEFAULT NULL,
    `wheel_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `seat_count` int DEFAULT NULL,
    `airbags` int DEFAULT NULL,
    `product_price` decimal(10, 0) NOT NULL,
    `product_number` int DEFAULT NULL,
    `status` enum('còn', 'hết') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`product_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table carshop.product: ~30 rows (approximately)
REPLACE INTO
    `product` (
        `product_id`,
        `product_name`,
        `color`,
        `image`,
        `dimensions`,
        `battery_capacity`,
        `wheel_type`,
        `seat_count`,
        `airbags`,
        `product_price`,
        `product_number`,
        `status`
    )
VALUES (
        'VF01',
        'VinFast VF3',
        'vàng',
        '../img/vf-3.jpg',
        '3114 x 1593 x 1600',
        20.00,
        'Hợp kim nhôm 16 inch',
        5,
        2,
        320000000,
        0,
        'hết'
    ),
    (
        'VF011',
        'VinFast VF3',
        'xanh',
        '../img/VF3_blue.png',
        '3114 x 1593 x 1600',
        20.00,
        'Hợp kim nhôm 16 inch',
        5,
        6,
        322000000,
        2,
        'còn'
    ),
    (
        'VF012',
        'VinFast VF3',
        'đỏ',
        '../img/VF3_red.png',
        '3114 x 1593 x 1600',
        20.00,
        'Hợp kim nhôm 16 inch',
        5,
        2,
        322000000,
        3,
        'còn'
    ),
    (
        'VF013',
        'VinFast VF3',
        'đen',
        '../img/VF3_black.png',
        '3114 x 1593 x 1600',
        20.00,
        'Hợp kim nhôm 16 inch',
        5,
        2,
        15000,
        4,
        'còn'
    ),
    (
        'VF014',
        'VinFast VF3',
        'trắng',
        '../img/VF3_white.png',
        '3114 x 1593 x 1600',
        20.00,
        'Hợp kim nhôm 16 inch',
        5,
        2,
        322000000,
        1,
        'còn'
    ),
    (
        'VF02',
        'VinFast VF5 ',
        'đỏ',
        '../img/VF5_red.png',
        '3965 x 1720 x 1600',
        37.23,
        'Hợp kim nhôm 17 inch',
        5,
        6,
        538000000,
        0,
        'hết'
    ),
    (
        'VF021',
        'VinFast VF5',
        'vàng',
        '../img/VF5_yellow.png',
        '3965 x 1720 x 1600',
        37.23,
        'Hợp kim nhôm 17 inch',
        5,
        6,
        538000000,
        0,
        'còn'
    ),
    (
        'VF022',
        'VinFast VF5',
        'xanh',
        '../img/VF5_Blue.png',
        '3965 x 1720 x 1600',
        37.23,
        'Hợp kim nhôm 17 inch',
        5,
        6,
        538000000,
        2,
        'còn'
    ),
    (
        'VF023',
        'VinFast VF5',
        'đen',
        '../img/VF5_black.png',
        '3965 x 1720 x 1600',
        37.23,
        'Hợp kim nhôm 17 inch',
        5,
        6,
        538000000,
        0,
        'còn'
    ),
    (
        'VF024',
        'VinFast VF5',
        'trắng',
        '../img/VF5_white.png',
        '3965 x 1720 x 1600',
        37.23,
        'Hợp kim nhôm 17 inch',
        5,
        6,
        538000000,
        2,
        'còn'
    ),
    (
        'VF03',
        'VinFast VF6',
        'xanh',
        '../img/vf-6.jpg',
        '4238 x 1820 x 1594',
        59.60,
        'Hợp kim nhôm 19 inch',
        5,
        6,
        675000000,
        0,
        'hết'
    ),
    (
        'VF031',
        'VinFast VF6',
        'xanh lục',
        '../img/VF6_green.png',
        '4238 x 1820 x 1594',
        59.60,
        'Hợp kim nhôm 19 inch',
        5,
        6,
        675000000,
        2,
        'còn'
    ),
    (
        'VF032',
        'VinFast VF6',
        'đỏ',
        '../img/VF6_red.png',
        '4238 x 1820 x 1594',
        59.60,
        'Hợp kim nhôm 19 inch',
        5,
        6,
        675000000,
        2,
        'còn'
    ),
    (
        'VF033',
        'VinFast VF6',
        'đen',
        '../img/VF6_black.png',
        '4238 x 1820 x 1594',
        59.60,
        'Hợp kim nhôm 19 inch',
        5,
        6,
        675000000,
        2,
        'còn'
    ),
    (
        'VF034',
        'VinFast VF6',
        'trắng',
        '../img/VF6_white.png',
        '4238 x 1820 x 1594',
        59.60,
        'Hợp kim nhôm 19 inch',
        5,
        6,
        675000000,
        0,
        'còn'
    ),
    (
        'VF04',
        'VinFast VFe34',
        'trắng',
        '../img/VFe34_white.png',
        '4300 x 1793 x 1613',
        42.00,
        'Hợp kim nhôm 18 inch',
        5,
        6,
        690000000,
        1,
        'còn'
    ),
    (
        'VF041',
        'VinFast VFe34',
        'vàng hoàng hôn',
        '../img/VFe34_sunset_orange.png',
        '4300 x 1793 x 1613',
        42.00,
        'Hợp kim nhôm 18 inch',
        5,
        6,
        690000000,
        2,
        'còn'
    ),
    (
        'VF042',
        'VinFast VFe34',
        'xanh',
        '../img/VFe34_blue.png',
        '4300 x 1793 x 1613',
        42.00,
        'Hợp kim nhôm 18 inch',
        5,
        6,
        690000000,
        1,
        'còn'
    ),
    (
        'VF043',
        'VinFast VFe34',
        'đỏ',
        '../img/VFe34_red.png',
        '4300 x 1793 x 1613',
        42.00,
        'Hợp kim nhôm 18 inch',
        5,
        6,
        690000000,
        2,
        'còn'
    ),
    (
        'VF044',
        'VinFast VFe34',
        'đen',
        '../img/VFe34_black.png',
        '4300 x 1793 x 1613',
        42.00,
        'Hợp kim nhôm 18 inch',
        5,
        6,
        690000000,
        2,
        'còn'
    ),
    (
        'VF05',
        'VinFast VF7',
        'đỏ',
        '../img/VF7_red.png',
        '4500 x 1890 x 1640',
        60.00,
        'Hợp kim nhôm 20 inch',
        5,
        6,
        850000000,
        0,
        'hết'
    ),
    (
        'VF051',
        'VinFast VF7',
        'xanh',
        '../img/VF7_blue.png',
        '4500 x 1890 x 1640',
        60.00,
        'Hợp kim nhôm 20 inch',
        5,
        6,
        850000000,
        2,
        'còn'
    ),
    (
        'VF052',
        'VinFast VF7',
        'đen',
        '../img/vf-7.jpg',
        '4500 x 1890 x 1640',
        60.00,
        'Hợp kim nhôm 20 inch',
        5,
        6,
        850000000,
        2,
        'còn'
    ),
    (
        'VF054',
        'VinFast VF7',
        'trắng',
        '../img/VF7_white.png',
        '4500 x 1890 x 1640',
        60.00,
        'Hợp kim nhôm 20 inch',
        5,
        6,
        850000000,
        2,
        'còn'
    ),
    (
        'VF06',
        'VinFast VF8',
        'trắng',
        '../img/vf-8.jpg',
        '4750 x 1930 x 1660',
        82.00,
        'Hợp kim nhôm 20 inch',
        5,
        8,
        1100000000,
        5,
        'còn'
    ),
    (
        'VF061',
        'VinFast VF8',
        'đen',
        '../img/VF8_black.png',
        '4750 x 1930 x 1660',
        82.00,
        'Hợp kim nhôm 20 inch',
        5,
        8,
        1100000000,
        2,
        'còn'
    ),
    (
        'VF07',
        'VinFast VF9',
        'đen',
        '../img/vf-9.jpg',
        '5120 x 2000 x 1800',
        123.00,
        'Hợp kim nhôm 22 inch',
        6,
        11,
        1500000000,
        6,
        'còn'
    ),
    (
        'VF071',
        'VinFast VF9',
        'trắng',
        '../img/VF9_white.png',
        '5120 x 2000 x 1800',
        123.00,
        'Hợp kim nhôm 22 inch',
        6,
        11,
        1500000000,
        2,
        'còn'
    ),
    (
        'VF072',
        'VinFast VF9',
        'xanh',
        '../img/VF9_blue.png',
        '5120 x 2000 x 1800',
        123.00,
        'Hợp kim nhôm 22 inch',
        6,
        11,
        1500000000,
        1,
        'còn'
    ),
    (
        'VF073',
        'VinFast VF9',
        'đỏ',
        '../img/VF9_red.png',
        '5120 x 2000 x 1800',
        123.00,
        'Hợp kim nhôm 22 inch',
        6,
        11,
        1500000000,
        2,
        'còn'
    );

-- Dumping structure for table carshop.cart_items
CREATE TABLE IF NOT EXISTS `cart_items` (
    `cart_id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `product_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `product_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `product_price` decimal(10, 0) NOT NULL,
    `quantity` int NOT NULL,
    `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`cart_id`),
    KEY `user_id` (`user_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table carshop.cart_items: ~0 rows (approximately)

-- Dumping structure for table carshop.charging_stations
CREATE TABLE IF NOT EXISTS `charging_stations` (
    `station_id` int NOT NULL AUTO_INCREMENT,
    `station_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `latitude` decimal(10, 8) NOT NULL,
    `longitude` decimal(11, 8) NOT NULL,
    `station_type` enum(
        'dc-super',
        'dc-fast',
        'ac-normal'
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `power_capacity` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `total_ports` int NOT NULL,
    `available_ports` int NOT NULL,
    `amenities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    `status` enum(
        'available',
        'busy',
        'unavailable'
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
    `operating_hours` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `pricing` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`station_id`),
    KEY `idx_location` (`latitude`, `longitude`),
    KEY `idx_status` (`status`),
    KEY `idx_type` (`station_type`)
) ENGINE = InnoDB AUTO_INCREMENT = 64 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table carshop.charging_stations: ~63 rows (approximately)
REPLACE INTO
    `charging_stations` (
        `station_id`,
        `station_name`,
        `address`,
        `latitude`,
        `longitude`,
        `station_type`,
        `power_capacity`,
        `total_ports`,
        `available_ports`,
        `amenities`,
        `status`,
        `operating_hours`,
        `pricing`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'VinFast Charging - Hà Nội',
        'Hà Nội, Hà Nội',
        21.02850000,
        105.85420000,
        'dc-super',
        '150kW',
        6,
        4,
        'restroom,wifi,cafe',
        'available',
        '24/7',
        '5,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        2,
        'VinFast Charging - TP. HCM',
        'Quận 1, TP. Hồ Chí Minh',
        10.82310000,
        106.62970000,
        'dc-super',
        '150kW',
        8,
        6,
        'shopping,restroom,wifi',
        'available',
        '24/7',
        '5,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        3,
        'VinFast Charging - Hải Phòng',
        'Hải Phòng, Hải Phòng',
        20.84400000,
        106.68810000,
        'dc-fast',
        '120kW',
        4,
        3,
        'restroom,parking',
        'available',
        '24/7',
        '4,800 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        4,
        'VinFast Charging - Đà Nẵng',
        'Hải Châu, Đà Nẵng',
        16.05440000,
        108.20220000,
        'dc-fast',
        '120kW',
        6,
        4,
        'restroom,wifi',
        'available',
        '24/7',
        '4,500 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        5,
        'VinFast Charging - Cần Thơ',
        'Ninh Kiều, Cần Thơ',
        10.04520000,
        105.74690000,
        'ac-normal',
        '22kW',
        4,
        4,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        6,
        'VinFast Charging - Long Xuyên (An Giang)',
        'Long Xuyên, An Giang',
        10.36990000,
        105.43300000,
        'dc-fast',
        '120kW',
        4,
        2,
        'restroom,parking',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        7,
        'VinFast Charging - Vũng Tàu',
        'Vũng Tàu, Bà Rịa - Vũng Tàu',
        10.35360000,
        107.08400000,
        'dc-fast',
        '120kW',
        4,
        3,
        'restroom,shopping',
        'available',
        '24/7',
        '4,500 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        8,
        'VinFast Charging - Bắc Giang',
        'Bắc Giang, Bắc Giang',
        21.27530000,
        106.19400000,
        'ac-normal',
        '22kW',
        3,
        3,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        9,
        'VinFast Charging - Bắc Kạn',
        'Bắc Kạn, Bắc Kạn',
        22.14760000,
        105.83690000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        10,
        'VinFast Charging - Bạc Liêu',
        'Bạc Liêu, Bạc Liêu',
        9.28780000,
        105.72860000,
        'ac-normal',
        '22kW',
        3,
        1,
        'restroom,parking',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        11,
        'VinFast Charging - Bắc Ninh',
        'Bắc Ninh, Bắc Ninh',
        21.13640000,
        106.07290000,
        'dc-fast',
        '120kW',
        6,
        4,
        'restroom,wifi',
        'available',
        '24/7',
        '4,800 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        12,
        'VinFast Charging - Bến Tre',
        'Bến Tre, Bến Tre',
        10.23610000,
        106.37490000,
        'ac-normal',
        '22kW',
        3,
        3,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        13,
        'VinFast Charging - Quy Nhơn (Bình Định)',
        'Quy Nhơn, Bình Định',
        13.78200000,
        109.21950000,
        'dc-fast',
        '100kW',
        4,
        2,
        'restroom,cafe',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        14,
        'VinFast Charging - Thủ Dầu Một (Bình Dương)',
        'Thủ Dầu Một, Bình Dương',
        10.98000000,
        106.64740000,
        'dc-fast',
        '120kW',
        6,
        5,
        'restroom,shopping',
        'available',
        '24/7',
        '4,800 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        15,
        'VinFast Charging - Đồng Xoài (Bình Phước)',
        'Đồng Xoài, Bình Phước',
        11.53750000,
        106.98610000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        16,
        'VinFast Charging - Phan Thiết (Bình Thuận)',
        'Phan Thiết, Bình Thuận',
        10.92810000,
        108.09560000,
        'dc-fast',
        '100kW',
        4,
        3,
        'restroom,parking',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        17,
        'VinFast Charging - Cà Mau',
        'Cà Mau, Cà Mau',
        9.17610000,
        105.15200000,
        'ac-normal',
        '22kW',
        3,
        1,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        18,
        'VinFast Charging - Cao Bằng',
        'Cao Bằng, Cao Bằng',
        22.66670000,
        106.25000000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        19,
        'VinFast Charging - Buôn Ma Thuột (Đắk Lắk)',
        'Buôn Ma Thuột, Đắk Lắk',
        12.66670000,
        108.05000000,
        'dc-fast',
        '120kW',
        4,
        3,
        'restroom,cafe',
        'available',
        '24/7',
        '4,500 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        20,
        'VinFast Charging - Gia Nghĩa (Đắk Nông)',
        'Gia Nghĩa, Đắk Nông',
        12.01110000,
        107.54130000,
        'ac-normal',
        '22kW',
        3,
        3,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        21,
        'VinFast Charging - Điện Biên Phủ',
        'Điện Biên Phủ, Điện Biên',
        21.38680000,
        103.02510000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        22,
        'VinFast Charging - Biên Hòa (Đồng Nai)',
        'Biên Hòa, Đồng Nai',
        10.95700000,
        106.82940000,
        'dc-fast',
        '120kW',
        6,
        5,
        'restroom,shopping',
        'available',
        '24/7',
        '4,800 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        23,
        'VinFast Charging - Cao Lãnh (Đồng Tháp)',
        'Cao Lãnh, Đồng Tháp',
        10.46100000,
        105.63690000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        24,
        'VinFast Charging - Pleiku (Gia Lai)',
        'Pleiku, Gia Lai',
        13.98330000,
        108.00000000,
        'dc-fast',
        '100kW',
        4,
        3,
        'restroom,parking',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        25,
        'VinFast Charging - Hà Giang',
        'Hà Giang, Hà Giang',
        22.75830000,
        104.99900000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        26,
        'VinFast Charging - Phủ Lý (Hà Nam)',
        'Phủ Lý, Hà Nam',
        20.53600000,
        105.91340000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        27,
        'VinFast Charging - Hà Tĩnh',
        'Hà Tĩnh, Hà Tĩnh',
        18.34220000,
        105.90400000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        28,
        'VinFast Charging - Vị Thanh (Hậu Giang)',
        'Vị Thanh, Hậu Giang',
        9.78330000,
        105.71670000,
        'ac-normal',
        '22kW',
        2,
        1,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        29,
        'VinFast Charging - Hưng Yên',
        'Hưng Yên, Hưng Yên',
        20.62200000,
        106.01000000,
        'dc-fast',
        '120kW',
        4,
        3,
        'restroom,wifi',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        30,
        'VinFast Charging - Nha Trang (Khánh Hòa)',
        'Nha Trang, Khánh Hòa',
        12.23880000,
        109.19670000,
        'dc-fast',
        '120kW',
        6,
        4,
        'restroom,beach-access',
        'available',
        '24/7',
        '4,500 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        31,
        'VinFast Charging - Rạch Giá (Kiên Giang)',
        'Rạch Giá, Kiên Giang',
        10.01520000,
        105.08360000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        32,
        'VinFast Charging - Kon Tum',
        'Kon Tum, Kon Tum',
        14.35240000,
        107.97700000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        33,
        'VinFast Charging - Lai Châu',
        'Lai Châu, Lai Châu',
        22.39250000,
        103.44290000,
        'ac-normal',
        '22kW',
        2,
        1,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        34,
        'VinFast Charging - Đà Lạt (Lâm Đồng)',
        'Đà Lạt, Lâm Đồng',
        11.94160000,
        108.45830000,
        'dc-fast',
        '100kW',
        4,
        3,
        'restroom,cafe',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        35,
        'VinFast Charging - Lạng Sơn',
        'Lạng Sơn, Lạng Sơn',
        21.85630000,
        106.76130000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        36,
        'VinFast Charging - Lào Cai',
        'Lào Cai, Lào Cai',
        22.48690000,
        103.97540000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        37,
        'VinFast Charging - Tân An (Long An)',
        'Tân An, Long An',
        10.53330000,
        106.41670000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        38,
        'VinFast Charging - Nam Định',
        'Nam Định, Nam Định',
        20.42760000,
        106.16690000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        39,
        'VinFast Charging - Vinh (Nghệ An)',
        'Vinh, Nghệ An',
        18.67140000,
        105.69720000,
        'dc-fast',
        '120kW',
        4,
        3,
        'restroom,parking',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        40,
        'VinFast Charging - Ninh Bình',
        'Ninh Bình, Ninh Bình',
        20.25830000,
        105.97400000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        41,
        'VinFast Charging - Phan Rang (Ninh Thuận)',
        'Phan Rang, Ninh Thuận',
        11.56990000,
        108.99820000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        42,
        'VinFast Charging - Việt Trì (Phú Thọ)',
        'Việt Trì, Phú Thọ',
        21.31360000,
        105.43060000,
        'dc-fast',
        '120kW',
        4,
        3,
        'restroom,parking',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        43,
        'VinFast Charging - Tuy Hòa (Phú Yên)',
        'Tuy Hòa, Phú Yên',
        13.09500000,
        109.32290000,
        'ac-normal',
        '22kW',
        2,
        1,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        44,
        'VinFast Charging - Đồng Hới (Quảng Bình)',
        'Đồng Hới, Quảng Bình',
        17.46840000,
        106.59440000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        45,
        'VinFast Charging - Tam Kỳ (Quảng Nam)',
        'Tam Kỳ, Quảng Nam',
        15.57660000,
        108.47900000,
        'dc-fast',
        '100kW',
        4,
        3,
        'restroom',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        46,
        'VinFast Charging - Quảng Ngãi',
        'Quảng Ngãi, Quảng Ngãi',
        15.12040000,
        108.79030000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        47,
        'VinFast Charging - Hạ Long (Quảng Ninh)',
        'Hạ Long, Quảng Ninh',
        20.96010000,
        107.08460000,
        'dc-fast',
        '120kW',
        6,
        4,
        'restroom,tourist-info',
        'available',
        '24/7',
        '4,500 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        48,
        'VinFast Charging - Quảng Trị',
        'Quảng Trị, Quảng Trị',
        16.74570000,
        107.17450000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        49,
        'VinFast Charging - Sóc Trăng',
        'Sóc Trăng, Sóc Trăng',
        9.60420000,
        105.97380000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        50,
        'VinFast Charging - Sơn La',
        'Sơn La, Sơn La',
        21.32570000,
        103.91800000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        51,
        'VinFast Charging - Tây Ninh',
        'Tây Ninh, Tây Ninh',
        11.31970000,
        106.10250000,
        'dc-fast',
        '100kW',
        4,
        3,
        'restroom,parking',
        'available',
        '24/7',
        '4,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        52,
        'VinFast Charging - Thái Bình',
        'Thái Bình, Thái Bình',
        20.44580000,
        106.33330000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        53,
        'VinFast Charging - Thái Nguyên',
        'Thái Nguyên, Thái Nguyên',
        21.59220000,
        105.84890000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom,parking',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        54,
        'VinFast Charging - Thanh Hóa',
        'Thanh Hóa, Thanh Hóa',
        19.80670000,
        105.78570000,
        'dc-fast',
        '120kW',
        6,
        4,
        'restroom,parking',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        55,
        'VinFast Charging - Huế (Thừa Thiên Huế)',
        'Huế, Thừa Thiên Huế',
        16.46370000,
        107.59090000,
        'dc-fast',
        '100kW',
        4,
        3,
        'restroom,tourist-info',
        'available',
        '24/7',
        '4,500 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        56,
        'VinFast Charging - Mỹ Tho (Tiền Giang)',
        'Mỹ Tho, Tiền Giang',
        10.35700000,
        106.36280000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        57,
        'VinFast Charging - Trà Vinh',
        'Trà Vinh, Trà Vinh',
        9.93560000,
        106.35360000,
        'ac-normal',
        '22kW',
        2,
        1,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        58,
        'VinFast Charging - Tuyên Quang',
        'Tuyên Quang, Tuyên Quang',
        21.82380000,
        105.21710000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        59,
        'VinFast Charging - Vĩnh Long',
        'Vĩnh Long, Vĩnh Long',
        10.24400000,
        105.96400000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom,parking',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        60,
        'VinFast Charging - Vĩnh Yên (Vĩnh Phúc)',
        'Vĩnh Yên, Vĩnh Phúc',
        21.30690000,
        105.60810000,
        'dc-fast',
        '120kW',
        4,
        3,
        'restroom,parking',
        'available',
        '24/7',
        '4,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        61,
        'VinFast Charging - Yên Bái',
        'Yên Bái, Yên Bái',
        21.70160000,
        104.91120000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,200 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        62,
        'VinFast Charging - Hòa Bình',
        'Hòa Bình, Hòa Bình',
        20.81690000,
        105.33890000,
        'ac-normal',
        '22kW',
        2,
        2,
        'restroom',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    ),
    (
        63,
        'VinFast Charging - Hải Dương',
        'Hải Dương, Hải Dương',
        20.93890000,
        106.32750000,
        'ac-normal',
        '22kW',
        3,
        2,
        'restroom,parking',
        'available',
        '24/7',
        '3,000 VNĐ/kWh',
        '2025-11-10 03:03:38',
        '2025-11-10 03:03:38'
    );

-- Dumping structure for table carshop.locations
CREATE TABLE IF NOT EXISTS `locations` (
    `location_id` int NOT NULL AUTO_INCREMENT,
    `location_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `location_address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`location_id`),
    KEY `locations_fk` (`location_name`)
) ENGINE = InnoDB AUTO_INCREMENT = 66 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table carshop.locations: ~65 rows (approximately)
REPLACE INTO
    `locations` (
        `location_id`,
        `location_name`,
        `location_address`
    )
VALUES (
        1,
        'Hồ Chí Minh',
        '17 Đ.Cộng Hòa,Phường 4,Tân Bình,Hồ Chí Minh'
    ),
    (
        2,
        'Ninh Thuận',
        '338 Đ.Thống Nhất,Khu Phố 4,Phan Rang-Tháp Chàm,Ninh Thuận'
    ),
    (
        3,
        'Hồ Chí Minh',
        '70 Tô Ký,Quận 12,Tp.Hồ Chí Minh'
    ),
    (
        4,
        'An Giang',
        'Long Xuyên, An Giang'
    ),
    (
        5,
        'Bà Rịa - Vũng Tàu',
        'Vũng Tàu, Bà Rịa - Vũng Tàu'
    ),
    (
        6,
        'Bắc Giang',
        'Bắc Giang, Bắc Giang'
    ),
    (
        7,
        'Bắc Kạn',
        'Bắc Kạn, Bắc Kạn'
    ),
    (
        8,
        'Bạc Liêu',
        'Bạc Liêu, Bạc Liêu'
    ),
    (
        9,
        'Bắc Ninh',
        'Bắc Ninh, Bắc Ninh'
    ),
    (
        10,
        'Bến Tre',
        'Bến Tre, Bến Tre'
    ),
    (
        11,
        'Bình Định',
        'Quy Nhơn, Bình Định'
    ),
    (
        12,
        'Bình Dương',
        'Thủ Dầu Một, Bình Dương'
    ),
    (
        13,
        'Bình Phước',
        'Đồng Xoài, Bình Phước'
    ),
    (
        14,
        'Bình Thuận',
        'Phan Thiết, Bình Thuận'
    ),
    (
        15,
        'Cà Mau',
        'Cà Mau, Cà Mau'
    ),
    (
        16,
        'Cần Thơ',
        'Ninh Kiều, Cần Thơ'
    ),
    (
        17,
        'Cao Bằng',
        'Cao Bằng, Cao Bằng'
    ),
    (
        18,
        'Đà Nẵng',
        'Hải Châu, Đà Nẵng'
    ),
    (
        19,
        'Đắk Lắk',
        'Buôn Ma Thuột, Đắk Lắk'
    ),
    (
        20,
        'Đắk Nông',
        'Gia Nghĩa, Đắk Nông'
    ),
    (
        21,
        'Điện Biên',
        'Điện Biên Phủ, Điện Biên'
    ),
    (
        22,
        'Đồng Nai',
        'Biên Hòa, Đồng Nai'
    ),
    (
        23,
        'Đồng Tháp',
        'Cao Lãnh, Đồng Tháp'
    ),
    (
        24,
        'Gia Lai',
        'Pleiku, Gia Lai'
    ),
    (
        25,
        'Hà Giang',
        'Hà Giang, Hà Giang'
    ),
    (
        26,
        'Hà Nam',
        'Phủ Lý, Hà Nam'
    ),
    (
        27,
        'Hà Nội',
        'Hà Nội, Hà Nội'
    ),
    (
        28,
        'Hà Tĩnh',
        'Hà Tĩnh, Hà Tĩnh'
    ),
    (
        29,
        'Hải Dương',
        'Hải Dương, Hải Dương'
    ),
    (
        30,
        'Hải Phòng',
        'Hải Phòng, Hải Phòng'
    ),
    (
        31,
        'Hậu Giang',
        'Vị Thanh, Hậu Giang'
    ),
    (
        32,
        'Hòa Bình',
        'Hòa Bình, Hòa Bình'
    ),
    (
        33,
        'Hưng Yên',
        'Hưng Yên, Hưng Yên'
    ),
    (
        34,
        'Khánh Hòa',
        'Nha Trang, Khánh Hòa'
    ),
    (
        35,
        'Kiên Giang',
        'Rạch Giá, Kiên Giang'
    ),
    (
        36,
        'Kon Tum',
        'Kon Tum, Kon Tum'
    ),
    (
        37,
        'Lai Châu',
        'Lai Châu, Lai Châu'
    ),
    (
        38,
        'Lâm Đồng',
        'Đà Lạt, Lâm Đồng'
    ),
    (
        39,
        'Lạng Sơn',
        'Lạng Sơn, Lạng Sơn'
    ),
    (
        40,
        'Lào Cai',
        'Lào Cai, Lào Cai'
    ),
    (
        41,
        'Long An',
        'Tân An, Long An'
    ),
    (
        42,
        'Nam Định',
        'Nam Định, Nam Định'
    ),
    (
        43,
        'Nghệ An',
        'Vinh, Nghệ An'
    ),
    (
        44,
        'Ninh Bình',
        'Ninh Bình, Ninh Bình'
    ),
    (
        45,
        'Phú Thọ',
        'Việt Trì, Phú Thọ'
    ),
    (
        46,
        'Phú Yên',
        'Tuy Hòa, Phú Yên'
    ),
    (
        47,
        'Quảng Bình',
        'Đồng Hới, Quảng Bình'
    ),
    (
        48,
        'Quảng Nam',
        'Tam Kỳ, Quảng Nam'
    ),
    (
        49,
        'Quảng Ngãi',
        'Quảng Ngãi, Quảng Ngãi'
    ),
    (
        50,
        'Quảng Ninh',
        'Hạ Long, Quảng Ninh'
    ),
    (
        51,
        'Quảng Trị',
        'Quảng Trị, Quảng Trị'
    ),
    (
        52,
        'Sóc Trăng',
        'Sóc Trăng, Sóc Trăng'
    ),
    (
        53,
        'Sơn La',
        'Sơn La, Sơn La'
    ),
    (
        54,
        'Tây Ninh',
        'Tây Ninh, Tây Ninh'
    ),
    (
        55,
        'Thái Bình',
        'Thái Bình, Thái Bình'
    ),
    (
        56,
        'Thái Nguyên',
        'Thái Nguyên, Thái Nguyên'
    ),
    (
        57,
        'Thanh Hóa',
        'Thanh Hóa, Thanh Hóa'
    ),
    (
        58,
        'Thừa Thiên Huế',
        'Huế, Thừa Thiên Huế'
    ),
    (
        59,
        'Tiền Giang',
        'Mỹ Tho, Tiền Giang'
    ),
    (
        60,
        'TP. Hồ Chí Minh',
        'Quận 1, TP. Hồ Chí Minh'
    ),
    (
        61,
        'Trà Vinh',
        'Trà Vinh, Trà Vinh'
    ),
    (
        62,
        'Tuyên Quang',
        'Tuyên Quang, Tuyên Quang'
    ),
    (
        63,
        'Vĩnh Long',
        'Vĩnh Long, Vĩnh Long'
    ),
    (
        64,
        'Vĩnh Phúc',
        'Vĩnh Yên, Vĩnh Phúc'
    ),
    (
        65,
        'Yên Bái',
        'Yên Bái, Yên Bái'
    );

-- Dumping structure for table carshop.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
    `transaction_id` int NOT NULL AUTO_INCREMENT,
    `product_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `customer_id` int NOT NULL,
    `transaction_date` date NOT NULL,
    `deposit` decimal(10, 0) NOT NULL,
    `transaction_number` int NOT NULL,
    `payment_method` enum(
        'Chuyển khoản ngân hàng',
        'MOMO'
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `transaction_status` enum(
        'pending',
        'completed',
        'cancelled',
        'delivering'
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `order_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `request_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `momo_trans_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `receiver_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `receiver_phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `receiver_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`transaction_id`),
    KEY `fk_customer` (`customer_id`),
    KEY `fk_product` (`product_id`),
    CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 11 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table carshop.transactions: ~5 rows (approximately)
REPLACE INTO
    `transactions` (
        `transaction_id`,
        `product_id`,
        `customer_id`,
        `transaction_date`,
        `deposit`,
        `transaction_number`,
        `payment_method`,
        `transaction_status`,
        `order_id`,
        `request_id`,
        `momo_trans_id`,
        `receiver_name`,
        `receiver_phone`,
        `receiver_address`
    )
VALUES (
        6,
        'VF013',
        10,
        '2025-11-13',
        15000,
        0,
        'Chuyển khoản ngân hàng',
        'completed',
        'ORDVF013U101763016197',
        '1',
        NULL,
        'Lam Dang Gia Bao',
        '0843257392',
        '70 Tô Ký, Quận 12, TP Hồ Chí Minh'
    ),
    (
        7,
        'VF013',
        10,
        '2025-11-13',
        15000,
        0,
        'Chuyển khoản ngân hàng',
        'cancelled',
        'ORDVF013U101763016970',
        '1',
        NULL,
        'Lam Dang Gia Bao',
        '0843257392',
        '70 Tô Ký, Quận 12, TP Hồ Chí Minh'
    ),
    (
        8,
        'VF012',
        10,
        '2025-11-13',
        15000000,
        0,
        'Chuyển khoản ngân hàng',
        'pending',
        'ORDVF012U101763018103',
        '1',
        NULL,
        'Lam Dang Gia Bao',
        '0843257392',
        '70 Tô Ký, Quận 12, TP Hồ Chí Minh'
    ),
    (
        9,
        'VF011',
        10,
        '2025-11-16',
        15000000,
        0,
        'Chuyển khoản ngân hàng',
        'pending',
        'ORDVF011U101763288249',
        '1',
        NULL,
        'Lam Dang Gia Bao',
        '0843257392',
        '70 Tô Ký, Quận 12, TP Hồ Chí Minh'
    ),
    (
        10,
        'VF013',
        10,
        '2025-11-16',
        15000000,
        0,
        'Chuyển khoản ngân hàng',
        'pending',
        'ORDVF013U101763288366',
        '1',
        NULL,
        'Lam Dang Gia Bao',
        '0843257392',
        '70 Tô Ký, Quận 12, TP Hồ Chí Minh'
    );

-- Dumping structure for trigger carshop.trg_transactions_insert
DELIMITER /
/

CREATE TRIGGER `trg_transactions_insert` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
    DECLARE remain_number INT;

    -- Lấy số lượng sản phẩm từ bảng `product`
    SELECT product_number INTO remain_number 
    FROM product 
    WHERE product_id = NEW.product_id;

    -- Nếu sản phẩm không tồn tại
    IF remain_number IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Sản phẩm không tồn tại!';
    -- Nếu số lượng sản phẩm còn lại đủ
    ELSEIF remain_number >= NEW.transaction_number THEN
        UPDATE product 
        SET product_number = product_number - NEW.transaction_number 
        WHERE product_id = NEW.product_id;
    -- Nếu số lượng sản phẩm không đủ
    ELSE 
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Số lượng sản phẩm không đủ!';
    END IF;
END
/
/

DELIMITER;

-- Dumping structure for trigger carshop.trg_transactions_delete
DELIMITER /
/

CREATE TRIGGER `trg_transactions_delete` AFTER DELETE ON `transactions` FOR EACH ROW BEGIN
  -- Declare variables to store the deleted information
  DECLARE v_product_id varchar(50);
  DECLARE v_transaction_number int;

  -- Get the information from the deleted row
  SET v_product_id = OLD.product_id;
  SET v_transaction_number = OLD.transaction_number;

  -- Update the product table
  UPDATE `product`
  SET product_number = product_number + v_transaction_number
  WHERE product_id = v_product_id;
END
/
/

DELIMITER;

-- Dumping structure for trigger carshop.update_product
DELIMITER /
/

CREATE TRIGGER `update_product` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN

  IF OLD.transaction_number <> NEW.transaction_number THEN

    IF NEW.transaction_number < 0 THEN 
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Số giao dịch không thể nhỏ hơn 0.';
    ELSE 

      IF (SELECT product_number FROM product WHERE product_id = NEW.product_id) - (NEW.transaction_number - OLD.transaction_number) < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Số lượng xe không thể nhỏ hơn 0.';
      ELSE
     
        UPDATE product
        SET product_number = product_number - (NEW.transaction_number - OLD.transaction_number)
        WHERE product_id = NEW.product_id;
      END IF;
    END IF;
  END IF;

END
/
/

DELIMITER;

-- Dumping structure for trigger carshop.update_status
DELIMITER /
/

CREATE TRIGGER `update_status` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN
  IF (SELECT product_number FROM product WHERE product_id = NEW.product_id) = 0 THEN
    UPDATE product
    SET status = 'hết'
    WHERE product_id = NEW.product_id;
  END IF; 
END
/
/

DELIMITER;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */
;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */
;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */
;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */
;