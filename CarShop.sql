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
(3, 'Nguyen Van A', 'nguyenvana@gmail.com', '0912412513', '2004-04-09', 'user', 'abc', 'Hồ Chí Minh', NULL, NULL, NULL);

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
('VF013', 'VinFast VF3', 'đen', '../img/VF3_black.png', '3114 x 1593 x 1600', '20.00', 'Hợp kim nhôm 16 inch', 5, 2, '322000000', 2, 'còn'),
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
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` decimal(10,0) NOT NULL,
  `quantity` int NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transactions`
--
CREATE TABLE `transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `product_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int NOT NULL,
  `transaction_date` date NOT NULL,
  `deposit` decimal(10,0) NOT NULL,
  `transaction_number` int NOT NULL,
  `payment_method` enum('Chuyển khoản ngân hàng','MOMO') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_status` enum('pending','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `momo_trans_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `fk_customer` (`customer_id`),
  KEY `fk_product` (`product_id`),
  CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `transactions`
--
INSERT INTO `transactions` (`transaction_id`, `product_id`, `customer_id`, `transaction_date`, `deposit`, `transaction_number`, `payment_method`, `transaction_status`, `order_id`, `request_id`, `momo_trans_id`, `receiver_name`, `receiver_phone`, `receiver_address`) VALUES
(1, 'VF01', 1, '2024-07-01', '15000000', 3, 'MOMO', 'pending', NULL, NULL, NULL, '', '', ''),
(2, 'VF014', 3, '2024-08-15', '15000000', 1, 'MOMO', 'pending', NULL, NULL, NULL, '', '', ''),
(27, 'VF023', 0, '2024-08-28', '15000000', 1, 'MOMO', 'pending', NULL, NULL, NULL, '', '', ''),
(28, 'VF042', 1, '2024-08-28', '15000000', 1, 'MOMO', 'pending', NULL, NULL, NULL, '', '', ''),
(29, 'VF073', 1, '2024-08-28', '15000000', 1, 'MOMO', 'pending', NULL, NULL, NULL, '', '', ''),
(30, 'VF072', 3, '2024-08-28', '15000000', 1, 'MOMO', 'pending', NULL, NULL, NULL, '', '', ''),
(33, 'VF02', 1, '2024-09-04', '15000000', 2, 'MOMO', 'pending', NULL, NULL, NULL, '', '', ''),
(34, 'VF021', 1, '2024-09-04', '15000000', 2, 'MOMO', 'pending', NULL, NULL, NULL, '', '', ''),
(36, 'VF034', 1, '2024-09-04', '15000000', 2, 'MOMO', 'pending', NULL, NULL, NULL, '', '', '');

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `trg_transactions_delete` AFTER DELETE ON `transactions` FOR EACH ROW BEGIN
  -- Declare variables to store the deleted information
  DECLARE v_product_id varchar(10);
  DECLARE v_transaction_number int;

  -- Get the information from the deleted row
  SET v_product_id = OLD.product_id;
  SET v_transaction_number = OLD.transaction_number;

  -- Update the product table
  UPDATE `product`
  SET product_number = product_number + v_transaction_number
  WHERE product_id = v_product_id;
END
$$
DELIMITER ;
DELIMITER $$
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
$$
DELIMITER ;
DELIMITER $$
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
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_status` AFTER UPDATE ON `transactions` FOR EACH ROW BEGIN
  IF (SELECT product_number FROM product WHERE product_id = NEW.product_id) = 0 THEN
    UPDATE product
    SET status = 'hết'
    WHERE product_id = NEW.product_id;
  END IF; 
END
$$
DELIMITER ;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;