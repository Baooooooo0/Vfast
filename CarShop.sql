-- Xóa database cũ nếu tồn tại và tạo database mới
DROP DATABASE IF EXISTS `carshop`;
CREATE DATABASE IF NOT EXISTS `carshop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `carshop`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
--
-- Cấu trúc bảng cho bảng `users`
--
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `usertype` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pob` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `pob_index` (`pob`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--

-- Đang đổ dữ liệu cho bảng `users`
--
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `birthday`, `usertype`, `password`, `pob`, `receiver_name`, `receiver_phone`, `receiver_address`) VALUES
(0, 'Nguyen Van B', 'nguyenvanb@gmail.com', '0923123449', NULL, 'admin', 'adminpass', 'Hồ Chí Minh', NULL, NULL, NULL),
(1, 'Lê Văn Cừ', 'levancu976@gmail.com', '0366796412', '2004-07-09', 'user', '123', 'Hồ Chí Minh', NULL, NULL, NULL),
(2, 'Đặng Đức Tĩnh', 'dangductinh1105@gmail.com', '0912412515', '2024-07-10', 'admin', '456', 'Ninh Thuận', NULL, NULL, NULL),
(3, 'Nguyen Van A', 'nguyenvana@gmail.com', '0912412513', '2004-04-09', 'user', 'abc', 'Hồ Chí Minh', NULL, NULL, NULL),
(4, 'Gia Bao', 'giabao4785@gmail.com', '0843257392', '2004-04-09', 'user', 'abc', 'Hồ Chí Minh', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--
CREATE TABLE `product` (
  `product_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dimensions` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `battery_capacity` decimal(10,2) DEFAULT NULL,
  `wheel_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seat_count` int DEFAULT NULL,
  `airbags` int DEFAULT NULL,
  `product_price` decimal(10,0) NOT NULL,
  `product_number` int DEFAULT NULL,
  `status` enum('còn','hết') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--
INSERT INTO `product` (`product_id`, `product_name`, `color`, `image`, `dimensions`, `battery_capacity`, `wheel_type`, `seat_count`, `airbags`, `product_price`, `product_number`, `status`) VALUES
('VF01', 'VinFast VF3', 'vàng', '../img/vf-3.jpg', '3114 x 1593 x 1600', '20.00', 'Hợp kim nhôm 16 inch', 5, 2, '320000000', 0, 'hết'),
('VF011', 'VinFast VF3', 'xanh', '../img/VF3_blue.png', '3114 x 1593 x 1600', '20.00', 'Hợp kim nhôm 16 inch', 5, 6, '322000000', 2, 'còn'),
('VF012', 'VinFast VF3', 'đỏ', '../img/VF3_red.png', '3114 x 1593 x 1600', '20.00', 'Hợp kim nhôm 16 inch', 5, 2, '322000000', 2, 'còn'),
('VF013', 'VinFast VF3', 'đen', '../img/VF3_black.png', '3114 x 1593 x 1600', '20.00', 'Hợp kim nhôm 16 inch', 5, 2, '15000', 2, 'còn'),
('VF014', 'VinFast VF3', 'trắng', '../img/VF3_white.png', '3114 x 1593 x 1600', '20.00', 'Hợp kim nhôm 16 inch', 5, 2, '322000000', 1, 'còn'),
('VF02', 'VinFast VF5 ', 'đỏ', '../img/VF5_red.png', '3965 x 1720 x 1600', '37.23', 'Hợp kim nhôm 17 inch', 5, 6, '538000000', 0, 'hết'),
('VF021', 'VinFast VF5', 'vàng', '../img/VF5_yellow.png', '3965 x 1720 x 1600', '37.23', 'Hợp kim nhôm 17 inch', 5, 6, '538000000', 0, 'còn'),
('VF022', 'VinFast VF5', 'xanh', '../img/VF5_Blue.png', '3965 x 1720 x 1600', '37.23', 'Hợp kim nhôm 17 inch', 5, 6, '538000000', 2, 'còn'),
('VF023', 'VinFast VF5', 'đen', '../img/VF5_black.png', '3965 x 1720 x 1600', '37.23', 'Hợp kim nhôm 17 inch', 5, 6, '538000000', 0, 'còn'),
('VF024', 'VinFast VF5', 'trắng', '../img/VF5_white.png', '3965 x 1720 x 1600', '37.23', 'Hợp kim nhôm 17 inch', 5, 6, '538000000', 2, 'còn'),
('VF03', 'VinFast VF6', 'xanh', '../img/vf-6.jpg', '4238 x 1820 x 1594', '59.60', 'Hợp kim nhôm 19 inch', 5, 6, '675000000', 0, 'hết'),
('VF031', 'VinFast VF6', 'xanh lục', '../img/VF6_green.png', '4238 x 1820 x 1594', '59.60', 'Hợp kim nhôm 19 inch', 5, 6, '675000000', 2, 'còn'),
('VF032', 'VinFast VF6', 'đỏ', '../img/VF6_red.png', '4238 x 1820 x 1594', '59.60', 'Hợp kim nhôm 19 inch', 5, 6, '675000000', 2, 'còn'),
('VF033', 'VinFast VF6', 'đen', '../img/VF6_black.png', '4238 x 1820 x 1594', '59.60', 'Hợp kim nhôm 19 inch', 5, 6, '675000000', 2, 'còn'),
('VF034', 'VinFast VF6', 'trắng', '../img/VF6_white.png', '4238 x 1820 x 1594', '59.60', 'Hợp kim nhôm 19 inch', 5, 6, '675000000', 0, 'còn'),
('VF04', 'VinFast VFe34', 'trắng', '../img/VFe34_white.png', '4300 x 1793 x 1613', '42.00', 'Hợp kim nhôm 18 inch', 5, 6, '690000000', 1, 'còn'),
('VF041', 'VinFast VFe34', 'vàng hoàng hôn', '../img/VFe34_sunset_orange.png', '4300 x 1793 x 1613', '42.00', 'Hợp kim nhôm 18 inch', 5, 6, '690000000', 2, 'còn'),
('VF042', 'VinFast VFe34', 'xanh', '../img/VFe34_blue.png', '4300 x 1793 x 1613', '42.00', 'Hợp kim nhôm 18 inch', 5, 6, '690000000', 1, 'còn'),
('VF043', 'VinFast VFe34', 'đỏ', '../img/VFe34_red.png', '4300 x 1793 x 1613', '42.00', 'Hợp kim nhôm 18 inch', 5, 6, '690000000', 2, 'còn'),
('VF044', 'VinFast VFe34', 'đen', '../img/VFe34_black.png', '4300 x 1793 x 1613', '42.00', 'Hợp kim nhôm 18 inch', 5, 6, '690000000', 2, 'còn'),
('VF05', 'VinFast VF7', 'đỏ', '../img/VF7_red.png', '4500 x 1890 x 1640', '60.00', 'Hợp kim nhôm 20 inch', 5, 6, '850000000', 0, 'hết'),
('VF051', 'VinFast VF7', 'xanh', '../img/VF7_blue.png', '4500 x 1890 x 1640', '60.00', 'Hợp kim nhôm 20 inch', 5, 6, '850000000', 2, 'còn'),
('VF052', 'VinFast VF7', 'đen', '../img/vf-7.jpg', '4500 x 1890 x 1640', '60.00', 'Hợp kim nhôm 20 inch', 5, 6, '850000000', 2, 'còn'),
('VF054', 'VinFast VF7', 'trắng', '../img/VF7_white.png', '4500 x 1890 x 1640', '60.00', 'Hợp kim nhôm 20 inch', 5, 6, '850000000', 2, 'còn'),
('VF06', 'VinFast VF8', 'trắng', '../img/vf-8.jpg', '4750 x 1930 x 1660', '82.00', 'Hợp kim nhôm 20 inch', 5, 8, '1100000000', 5, 'còn'),
('VF061', 'VinFast VF8', 'đen', '../img/VF8_black.png', '4750 x 1930 x 1660', '82.00', 'Hợp kim nhôm 20 inch', 5, 8, '1100000000', 2, 'còn'),
('VF07', 'VinFast VF9', 'đen', '../img/vf-9.jpg', '5120 x 2000 x 1800', '123.00', 'Hợp kim nhôm 22 inch', 6, 11, '1500000000', 6, 'còn'),
('VF071', 'VinFast VF9', 'trắng', '../img/VF9_white.png', '5120 x 2000 x 1800', '123.00', 'Hợp kim nhôm 22 inch', 6, 11, '1500000000', 2, 'còn'),
('VF072', 'VinFast VF9', 'xanh', '../img/VF9_blue.png', '5120 x 2000 x 1800', '123.00', 'Hợp kim nhôm 22 inch', 6, 11, '1500000000', 1, 'còn'),
('VF073', 'VinFast VF9', 'đỏ', '../img/VF9_red.png', '5120 x 2000 x 1800', '123.00', 'Hợp kim nhôm 22 inch', 6, 11, '1500000000', 2, 'còn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `locations`
--
CREATE TABLE `locations` (
  `location_id` int NOT NULL AUTO_INCREMENT,
  `location_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_address` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `locations_fk` (`location_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `locations`
--
INSERT INTO `locations` (`location_id`, `location_name`, `location_address`) VALUES
(1, 'Hồ Chí Minh', '17 Đ.Cộng Hòa,Phường 4,Tân Bình,Hồ Chí Minh'),
(2, 'Ninh Thuận', '338 Đ.Thống Nhất,Khu Phố 4,Phan Rang-Tháp Chàm,Ninh Thuận'),
(3, 'Hồ Chí Minh', '70 Tô Ký,Quận 12,Tp.Hồ Chí Minh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--
CREATE TABLE `cart_items` (
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Thêm dữ liệu trạm sạc VinFast vào database

-- Tạo bảng charging_stations nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS `charging_stations` (
  `station_id` int NOT NULL AUTO_INCREMENT,
  `station_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `station_type` enum('dc-super','dc-fast','ac-normal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `power_capacity` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_ports` int NOT NULL,
  `available_ports` int NOT NULL,
  `amenities` text COLLATE utf8mb4_unicode_ci,
  `status` enum('available','busy','unavailable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `operating_hours` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pricing` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`station_id`),
  KEY `idx_location` (`latitude`,`longitude`),
  KEY `idx_status` (`status`),
  KEY `idx_type` (`station_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Xóa dữ liệu cũ nếu có
DELETE FROM `charging_stations`;

-- Thêm dữ liệu trạm sạc VinFast cho 63 tỉnh thành
INSERT INTO `charging_stations` (`station_name`, `address`, `latitude`, `longitude`, `station_type`, `power_capacity`, `total_ports`, `available_ports`, `amenities`, `status`, `operating_hours`, `pricing`) VALUES
('VinFast Charging - Hà Nội', 'Hà Nội, Hà Nội', 21.02850000, 105.85420000, 'dc-super', '150kW', 6, 4, 'restroom,wifi,cafe', 'available', '24/7', '5,000 VNĐ/kWh'),
('VinFast Charging - TP. HCM', 'Quận 1, TP. Hồ Chí Minh', 10.82310000, 106.62970000, 'dc-super', '150kW', 8, 6, 'shopping,restroom,wifi', 'available', '24/7', '5,000 VNĐ/kWh'),
('VinFast Charging - Hải Phòng', 'Hải Phòng, Hải Phòng', 20.84400000, 106.68810000, 'dc-fast', '120kW', 4, 3, 'restroom,parking', 'available', '24/7', '4,800 VNĐ/kWh'),
('VinFast Charging - Đà Nẵng', 'Hải Châu, Đà Nẵng', 16.05440000, 108.20220000, 'dc-fast', '120kW', 6, 4, 'restroom,wifi', 'available', '24/7', '4,500 VNĐ/kWh'),
('VinFast Charging - Cần Thơ', 'Ninh Kiều, Cần Thơ', 10.04520000, 105.74690000, 'ac-normal', '22kW', 4, 4, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Long Xuyên (An Giang)', 'Long Xuyên, An Giang', 10.36990000, 105.43300000, 'dc-fast', '120kW', 4, 2, 'restroom,parking', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Vũng Tàu', 'Vũng Tàu, Bà Rịa - Vũng Tàu', 10.35360000, 107.08400000, 'dc-fast', '120kW', 4, 3, 'restroom,shopping', 'available', '24/7', '4,500 VNĐ/kWh'),
('VinFast Charging - Bắc Giang', 'Bắc Giang, Bắc Giang', 21.27530000, 106.19400000, 'ac-normal', '22kW', 3, 3, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Bắc Kạn', 'Bắc Kạn, Bắc Kạn', 22.14760000, 105.83690000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Bạc Liêu', 'Bạc Liêu, Bạc Liêu', 9.28780000, 105.72860000, 'ac-normal', '22kW', 3, 1, 'restroom,parking', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Bắc Ninh', 'Bắc Ninh, Bắc Ninh', 21.13640000, 106.07290000, 'dc-fast', '120kW', 6, 4, 'restroom,wifi', 'available', '24/7', '4,800 VNĐ/kWh'),
('VinFast Charging - Bến Tre', 'Bến Tre, Bến Tre', 10.23610000, 106.37490000, 'ac-normal', '22kW', 3, 3, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Quy Nhơn (Bình Định)', 'Quy Nhơn, Bình Định', 13.78200000, 109.21950000, 'dc-fast', '100kW', 4, 2, 'restroom,cafe', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Thủ Dầu Một (Bình Dương)', 'Thủ Dầu Một, Bình Dương', 10.98000000, 106.64740000, 'dc-fast', '120kW', 6, 5, 'restroom,shopping', 'available', '24/7', '4,800 VNĐ/kWh'),
('VinFast Charging - Đồng Xoài (Bình Phước)', 'Đồng Xoài, Bình Phước', 11.53750000, 106.98610000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Phan Thiết (Bình Thuận)', 'Phan Thiết, Bình Thuận', 10.92810000, 108.09560000, 'dc-fast', '100kW', 4, 3, 'restroom,parking', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Cà Mau', 'Cà Mau, Cà Mau', 9.17610000, 105.15200000, 'ac-normal', '22kW', 3, 1, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Cao Bằng', 'Cao Bằng, Cao Bằng', 22.66670000, 106.25000000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Buôn Ma Thuột (Đắk Lắk)', 'Buôn Ma Thuột, Đắk Lắk', 12.66670000, 108.05000000, 'dc-fast', '120kW', 4, 3, 'restroom,cafe', 'available', '24/7', '4,500 VNĐ/kWh'),
('VinFast Charging - Gia Nghĩa (Đắk Nông)', 'Gia Nghĩa, Đắk Nông', 12.01110000, 107.54130000, 'ac-normal', '22kW', 3, 3, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Điện Biên Phủ', 'Điện Biên Phủ, Điện Biên', 21.38680000, 103.02510000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Biên Hòa (Đồng Nai)', 'Biên Hòa, Đồng Nai', 10.95700000, 106.82940000, 'dc-fast', '120kW', 6, 5, 'restroom,shopping', 'available', '24/7', '4,800 VNĐ/kWh'),
('VinFast Charging - Cao Lãnh (Đồng Tháp)', 'Cao Lãnh, Đồng Tháp', 10.46100000, 105.63690000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Pleiku (Gia Lai)', 'Pleiku, Gia Lai', 13.98330000, 108.00000000, 'dc-fast', '100kW', 4, 3, 'restroom,parking', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Hà Giang', 'Hà Giang, Hà Giang', 22.75830000, 104.99900000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Phủ Lý (Hà Nam)', 'Phủ Lý, Hà Nam', 20.53600000, 105.91340000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Hà Tĩnh', 'Hà Tĩnh, Hà Tĩnh', 18.34220000, 105.90400000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Vị Thanh (Hậu Giang)', 'Vị Thanh, Hậu Giang', 9.78330000, 105.71670000, 'ac-normal', '22kW', 2, 1, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Hưng Yên', 'Hưng Yên, Hưng Yên', 20.62200000, 106.01000000, 'dc-fast', '120kW', 4, 3, 'restroom,wifi', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Nha Trang (Khánh Hòa)', 'Nha Trang, Khánh Hòa', 12.23880000, 109.19670000, 'dc-fast', '120kW', 6, 4, 'restroom,beach-access', 'available', '24/7', '4,500 VNĐ/kWh'),
('VinFast Charging - Rạch Giá (Kiên Giang)', 'Rạch Giá, Kiên Giang', 10.01520000, 105.08360000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Kon Tum', 'Kon Tum, Kon Tum', 14.35240000, 107.97700000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Lai Châu', 'Lai Châu, Lai Châu', 22.39250000, 103.44290000, 'ac-normal', '22kW', 2, 1, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Đà Lạt (Lâm Đồng)', 'Đà Lạt, Lâm Đồng', 11.94160000, 108.45830000, 'dc-fast', '100kW', 4, 3, 'restroom,cafe', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Lạng Sơn', 'Lạng Sơn, Lạng Sơn', 21.85630000, 106.76130000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Lào Cai', 'Lào Cai, Lào Cai', 22.48690000, 103.97540000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Tân An (Long An)', 'Tân An, Long An', 10.53330000, 106.41670000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Nam Định', 'Nam Định, Nam Định', 20.42760000, 106.16690000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Vinh (Nghệ An)', 'Vinh, Nghệ An', 18.67140000, 105.69720000, 'dc-fast', '120kW', 4, 3, 'restroom,parking', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Ninh Bình', 'Ninh Bình, Ninh Bình', 20.25830000, 105.97400000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Phan Rang (Ninh Thuận)', 'Phan Rang, Ninh Thuận', 11.56990000, 108.99820000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Việt Trì (Phú Thọ)', 'Việt Trì, Phú Thọ', 21.31360000, 105.43060000, 'dc-fast', '120kW', 4, 3, 'restroom,parking', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Tuy Hòa (Phú Yên)', 'Tuy Hòa, Phú Yên', 13.09500000, 109.32290000, 'ac-normal', '22kW', 2, 1, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Đồng Hới (Quảng Bình)', 'Đồng Hới, Quảng Bình', 17.46840000, 106.59440000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Tam Kỳ (Quảng Nam)', 'Tam Kỳ, Quảng Nam', 15.57660000, 108.47900000, 'dc-fast', '100kW', 4, 3, 'restroom', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Quảng Ngãi', 'Quảng Ngãi, Quảng Ngãi', 15.12040000, 108.79030000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Hạ Long (Quảng Ninh)', 'Hạ Long, Quảng Ninh', 20.96010000, 107.08460000, 'dc-fast', '120kW', 6, 4, 'restroom,tourist-info', 'available', '24/7', '4,500 VNĐ/kWh'),
('VinFast Charging - Quảng Trị', 'Quảng Trị, Quảng Trị', 16.74570000, 107.17450000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Sóc Trăng', 'Sóc Trăng, Sóc Trăng', 9.60420000, 105.97380000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Sơn La', 'Sơn La, Sơn La', 21.32570000, 103.91800000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Tây Ninh', 'Tây Ninh, Tây Ninh', 11.31970000, 106.10250000, 'dc-fast', '100kW', 4, 3, 'restroom,parking', 'available', '24/7', '4,000 VNĐ/kWh'),
('VinFast Charging - Thái Bình', 'Thái Bình, Thái Bình', 20.44580000, 106.33330000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Thái Nguyên', 'Thái Nguyên, Thái Nguyên', 21.59220000, 105.84890000, 'ac-normal', '22kW', 3, 2, 'restroom,parking', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Thanh Hóa', 'Thanh Hóa, Thanh Hóa', 19.80670000, 105.78570000, 'dc-fast', '120kW', 6, 4, 'restroom,parking', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Huế (Thừa Thiên Huế)', 'Huế, Thừa Thiên Huế', 16.46370000, 107.59090000, 'dc-fast', '100kW', 4, 3, 'restroom,tourist-info', 'available', '24/7', '4,500 VNĐ/kWh'),
('VinFast Charging - Mỹ Tho (Tiền Giang)', 'Mỹ Tho, Tiền Giang', 10.35700000, 106.36280000, 'ac-normal', '22kW', 3, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Trà Vinh', 'Trà Vinh, Trà Vinh', 9.93560000, 106.35360000, 'ac-normal', '22kW', 2, 1, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Tuyên Quang', 'Tuyên Quang, Tuyên Quang', 21.82380000, 105.21710000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Vĩnh Long', 'Vĩnh Long, Vĩnh Long', 10.24400000, 105.96400000, 'ac-normal', '22kW', 3, 2, 'restroom,parking', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Vĩnh Yên (Vĩnh Phúc)', 'Vĩnh Yên, Vĩnh Phúc', 21.30690000, 105.60810000, 'dc-fast', '120kW', 4, 3, 'restroom,parking', 'available', '24/7', '4,200 VNĐ/kWh'),
('VinFast Charging - Yên Bái', 'Yên Bái, Yên Bái', 21.70160000, 104.91120000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,200 VNĐ/kWh'),
('VinFast Charging - Hòa Bình', 'Hòa Bình, Hòa Bình', 20.81690000, 105.33890000, 'ac-normal', '22kW', 2, 2, 'restroom', 'available', '24/7', '3,000 VNĐ/kWh'),
('VinFast Charging - Hải Dương', 'Hải Dương, Hải Dương', 20.93890000, 106.32750000, 'ac-normal', '22kW', 3, 2, 'restroom,parking', 'available', '24/7', '3,000 VNĐ/kWh');

-- Cập nhật bảng locations hiện có với các tỉnh thành mới
INSERT IGNORE INTO `locations` (`location_name`, `location_address`) VALUES
('An Giang', 'Long Xuyên, An Giang'),
('Bà Rịa - Vũng Tàu', 'Vũng Tàu, Bà Rịa - Vũng Tàu'),
('Bắc Giang', 'Bắc Giang, Bắc Giang'),
('Bắc Kạn', 'Bắc Kạn, Bắc Kạn'),
('Bạc Liêu', 'Bạc Liêu, Bạc Liêu'),
('Bắc Ninh', 'Bắc Ninh, Bắc Ninh'),
('Bến Tre', 'Bến Tre, Bến Tre'),
('Bình Định', 'Quy Nhơn, Bình Định'),
('Bình Dương', 'Thủ Dầu Một, Bình Dương'),
('Bình Phước', 'Đồng Xoài, Bình Phước'),
('Bình Thuận', 'Phan Thiết, Bình Thuận'),
('Cà Mau', 'Cà Mau, Cà Mau'),
('Cần Thơ', 'Ninh Kiều, Cần Thơ'),
('Cao Bằng', 'Cao Bằng, Cao Bằng'),
('Đà Nẵng', 'Hải Châu, Đà Nẵng'),
('Đắk Lắk', 'Buôn Ma Thuột, Đắk Lắk'),
('Đắk Nông', 'Gia Nghĩa, Đắk Nông'),
('Điện Biên', 'Điện Biên Phủ, Điện Biên'),
('Đồng Nai', 'Biên Hòa, Đồng Nai'),
('Đồng Tháp', 'Cao Lãnh, Đồng Tháp'),
('Gia Lai', 'Pleiku, Gia Lai'),
('Hà Giang', 'Hà Giang, Hà Giang'),
('Hà Nam', 'Phủ Lý, Hà Nam'),
('Hà Nội', 'Hà Nội, Hà Nội'),
('Hà Tĩnh', 'Hà Tĩnh, Hà Tĩnh'),
('Hải Dương', 'Hải Dương, Hải Dương'),
('Hải Phòng', 'Hải Phòng, Hải Phòng'),
('Hậu Giang', 'Vị Thanh, Hậu Giang'),
('Hòa Bình', 'Hòa Bình, Hòa Bình'),
('Hưng Yên', 'Hưng Yên, Hưng Yên'),
('Khánh Hòa', 'Nha Trang, Khánh Hòa'),
('Kiên Giang', 'Rạch Giá, Kiên Giang'),
('Kon Tum', 'Kon Tum, Kon Tum'),
('Lai Châu', 'Lai Châu, Lai Châu'),
('Lâm Đồng', 'Đà Lạt, Lâm Đồng'),
('Lạng Sơn', 'Lạng Sơn, Lạng Sơn'),
('Lào Cai', 'Lào Cai, Lào Cai'),
('Long An', 'Tân An, Long An'),
('Nam Định', 'Nam Định, Nam Định'),
('Nghệ An', 'Vinh, Nghệ An'),
('Ninh Bình', 'Ninh Bình, Ninh Bình'),
('Phú Thọ', 'Việt Trì, Phú Thọ'),
('Phú Yên', 'Tuy Hòa, Phú Yên'),
('Quảng Bình', 'Đồng Hới, Quảng Bình'),
('Quảng Nam', 'Tam Kỳ, Quảng Nam'),
('Quảng Ngãi', 'Quảng Ngãi, Quảng Ngãi'),
('Quảng Ninh', 'Hạ Long, Quảng Ninh'),
('Quảng Trị', 'Quảng Trị, Quảng Trị'),
('Sóc Trăng', 'Sóc Trăng, Sóc Trăng'),
('Sơn La', 'Sơn La, Sơn La'),
('Tây Ninh', 'Tây Ninh, Tây Ninh'),
('Thái Bình', 'Thái Bình, Thái Bình'),
('Thái Nguyên', 'Thái Nguyên, Thái Nguyên'),
('Thanh Hóa', 'Thanh Hóa, Thanh Hóa'),
('Thừa Thiên Huế', 'Huế, Thừa Thiên Huế'),
('Tiền Giang', 'Mỹ Tho, Tiền Giang'),
('TP. Hồ Chí Minh', 'Quận 1, TP. Hồ Chí Minh'),
('Trà Vinh', 'Trà Vinh, Trà Vinh'),
('Tuyên Quang', 'Tuyên Quang, Tuyên Quang'),
('Vĩnh Long', 'Vĩnh Long, Vĩnh Long'),
('Vĩnh Phúc', 'Vĩnh Yên, Vĩnh Phúc'),
('Yên Bái', 'Yên Bái, Yên Bái');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;carshop