-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 21, 2023 at 10:14 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sp_mk15`
--

-- --------------------------------------------------------

--
-- Table structure for table `loaisanpham`
--

CREATE TABLE `loaisanpham` (
  `id_loaisp` int(10) NOT NULL,
  `ten_loaisp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loaisanpham`
--

INSERT INTO `loaisanpham` (`id_loaisp`, `ten_loaisp`) VALUES
(1, 'Nike'),
(2, 'Adidas'),
(3, 'Peak'),
(4, 'Jordan'),
(5, 'Puma'),
(6, 'THÊM');

-- --------------------------------------------------------

--
-- Table structure for table `quanlynguoidung`
--

CREATE TABLE `quanlynguoidung` (
  `id_nd` int(10) NOT NULL,
  `ten_nd` varchar(255) DEFAULT NULL,
  `matkhau_nd` varchar(255) DEFAULT NULL,
  `quyen_nd` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quanlynguoidung`
--

INSERT INTO `quanlynguoidung` (`id_nd`, `ten_nd`, `matkhau_nd`, `quyen_nd`) VALUES
(1, 'admin', 'admin', 1),
(2, 'nguoidung1', '1', 2),
(3, 'tu', '1', 2);

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `id_sp` int(10) NOT NULL,
  `ten_sp` varchar(255) DEFAULT NULL,
  `hinhanh_sp` varchar(255) DEFAULT NULL,
  `gia_sp` int(20) DEFAULT NULL,
  `ngaynhap_sp` date DEFAULT NULL,
  `id_loaisp` int(10) DEFAULT NULL,
  `mota_sp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`id_sp`, `ten_sp`, `hinhanh_sp`, `gia_sp`, `ngaynhap_sp`, `id_loaisp`, `mota_sp`) VALUES
(1, 'Nike Air Jordan 1', 'anh/air-jordan-1-retro-high-og-chicago-lost-found-dz5485-612_1.jpg', 200, '2023-08-21', 1, ''),
(3, 'Adidas Super Star', 'anh/adidassuperstar.jpg', 300, '2023-08-21', 2, 'Mô tả của sản phẩm sua'),
(4, 'Greek Preak 1', 'anh/greafreak1.jpg', 10000001, '2023-08-21', 3, ''),
(5, 'Jordan TaTum 2', 'anh/Jordan TaTum 2.jpg', 100000, '2023-08-21', 4, ''),
(6, 'Puma Lamelo Ball Rick & Morty', 'anh/Puma Lamelo Ball Rick & Morty.jpg', 3000000, '2023-08-21', 5, ''),
(7, 'Kyrie 4', 'anh/Kyrie 4.jpg', 20000, '2023-08-21', 1, ''),
(8, 'Adidas Breaknet', 'anh/Adidas Breaknet.jpg', 60000, '2023-08-21', 2, 'Mô tả của sản phẩm thêm 4'),
(9, 'Jordan 4 retro', 'anh/Air-Jordan-4-Retro-White-Cement-2016-Product.jpg', 3000000, '2023-08-21', 4, 'Mô tả của sản phẩm thêm 5'),
(10, 'Puma Suede Classic', 'anh/Puma Suede Classic.jpg', 10000000, '2023-08-21', 5, 'mô tả của thêm mới 1'),
(11, 'Lebron Witness 6', 'anh/Lebron Witness 6.jpg', 2000000, '2023-08-21', 1, ''),
(12, 'Ultra Boost 23', 'anh/Ultra Boost 23.jpg', 2450000, '2023-08-21', 2, ''),
(13, 'Alphabounce Instinct M', 'anh/Alphabounce Instinct M.jpg', 2147483647, '2023-08-21', 2, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `loaisanpham`
--
ALTER TABLE `loaisanpham`
  ADD PRIMARY KEY (`id_loaisp`);

--
-- Indexes for table `quanlynguoidung`
--
ALTER TABLE `quanlynguoidung`
  ADD PRIMARY KEY (`id_nd`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`id_sp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `loaisanpham`
--
ALTER TABLE `loaisanpham`
  MODIFY `id_loaisp` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quanlynguoidung`
--
ALTER TABLE `quanlynguoidung`
  MODIFY `id_nd` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `id_sp` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
