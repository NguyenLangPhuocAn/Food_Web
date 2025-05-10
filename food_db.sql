-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 10, 2025 lúc 09:01 AM
-- Phiên bản máy phục vụ: 8.0.32
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `food_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lienhe`
--

CREATE TABLE `lienhe` (
  `Id` int NOT NULL,
  `Sdt` varchar(10) COLLATE utf32_vietnamese_ci NOT NULL,
  `Ten` varchar(36) COLLATE utf32_vietnamese_ci NOT NULL,
  `GopY` text COLLATE utf32_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `lienhe`
--

INSERT INTO `lienhe` (`Id`, `Sdt`, `Ten`, `GopY`) VALUES
(4, '0907563107', 'Nguyen Lang Phuoc Na', 'Dở');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `monan`
--

CREATE TABLE `monan` (
  `Id` int NOT NULL,
  `Ten` varchar(255) CHARACTER SET utf32 COLLATE utf32_vietnamese_ci NOT NULL,
  `Noi_Dung` text CHARACTER SET utf32 COLLATE utf32_vietnamese_ci NOT NULL,
  `Gia` decimal(10,2) NOT NULL,
  `Image` varchar(255) COLLATE utf32_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `monan`
--

INSERT INTO `monan` (`Id`, `Ten`, `Noi_Dung`, `Gia`, `Image`) VALUES
(8, 'Phở bò', 'phở bò siêu ngon được làm từ chú bò thảo nguyên', 100000.00, 'pho.jpg'),
(9, 'phở bò', 'phở bò không giá', 150000.00, 'pho.jpg'),
(10, 'phở bò', 'phở bò tái nạm gầu gân', 50000.00, '681b22753638d.jpg'),
(11, 'phở gà', 'phở gà hầm xương', 40000.00, '681b25b45b82b.jpg'),
(12, 'phở gà nấm', 'phở gà nấm đông cô', 60000.00, '681b25d6ef3c3.jpg'),
(13, 'phở gà trộn', 'Thành phần: bánh phở, gà xé, nước hầm gà', 50000.00, '681b26462be4b.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tintuc`
--

CREATE TABLE `tintuc` (
  `Id` int NOT NULL,
  `Tieu_De` varchar(255) CHARACTER SET utf32 COLLATE utf32_vietnamese_ci NOT NULL,
  `Noi_Dung` text CHARACTER SET utf32 COLLATE utf32_vietnamese_ci NOT NULL,
  `Image` varchar(255) CHARACTER SET utf32 COLLATE utf32_vietnamese_ci DEFAULT NULL,
  `Ngay_Dang` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `tintuc`
--

INSERT INTO `tintuc` (`Id`, `Tieu_De`, `Noi_Dung`, `Image`, `Ngay_Dang`) VALUES
(2, 'chú bò thảo nguyên', '<p>asdlkasokodkodaskodskodsakods ff</p>\r\n<p><img src=\"../Food_Web/images/pho.jpg\" alt=\"\" width=\"69\" height=\"50\"></p>', 'pho.jpg', '2025-04-04 14:37:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `Id` int NOT NULL,
  `Username` varchar(50) COLLATE utf32_vietnamese_ci NOT NULL,
  `Password` varchar(255) COLLATE utf32_vietnamese_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`Id`, `Username`, `Password`) VALUES
(1, 'admin', '123456');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  ADD PRIMARY KEY (`Id`);

--
-- Chỉ mục cho bảng `monan`
--
ALTER TABLE `monan`
  ADD PRIMARY KEY (`Id`);

--
-- Chỉ mục cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  ADD PRIMARY KEY (`Id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `lienhe`
--
ALTER TABLE `lienhe`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `monan`
--
ALTER TABLE `monan`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `Id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
