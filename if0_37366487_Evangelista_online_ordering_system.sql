-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql211.infinityfree.com
-- Generation Time: Jul 04, 2026 at 10:42 AM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37366487_Evangelista_online_ordering_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(103, 35, 7, 1, '2025-10-16 14:12:17', '2025-10-16 14:12:17'),
(100, 21, 9, 1, '2025-10-16 14:04:09', '2025-10-16 14:04:09'),
(99, 34, 7, 1, '2025-10-16 13:56:14', '2025-10-16 13:56:14'),
(90, 19, 57, 3, '2024-12-12 06:57:08', '2024-12-12 06:57:08'),
(75, 6, 8, 1, '2024-12-01 07:22:29', '2024-12-01 07:22:29'),
(98, 33, 7, 1, '2025-10-16 13:50:41', '2025-10-16 13:50:41'),
(66, 11, 43, 10, '2024-11-30 17:57:40', '2024-11-30 17:57:40'),
(102, 21, 23, 1, '2025-10-16 14:04:31', '2025-10-16 14:04:31'),
(104, 36, 7, 1, '2025-10-16 14:20:58', '2025-10-16 14:20:58'),
(105, 37, 7, 1, '2025-10-16 14:31:39', '2025-10-16 14:31:39'),
(106, 38, 7, 1, '2025-10-16 14:35:33', '2025-10-16 14:35:33'),
(107, 39, 7, 1, '2025-10-16 14:42:05', '2025-10-16 14:42:05'),
(153, 43, 16, 6, '2025-10-17 10:14:18', '2025-10-17 10:24:22'),
(159, 43, 53, 5, '2025-10-17 10:18:55', '2025-10-17 10:18:55'),
(165, 43, 41, 10, '2025-10-17 15:31:10', '2025-10-17 15:31:10'),
(158, 43, 54, 2, '2025-10-17 10:18:52', '2025-10-17 15:00:34'),
(155, 43, 30, 2, '2025-10-17 10:16:32', '2025-10-17 10:18:51'),
(152, 43, 35, 2, '2025-10-17 10:14:17', '2025-10-17 10:24:06'),
(154, 43, 7, 5, '2025-10-17 10:14:20', '2025-10-17 10:14:20'),
(162, 43, 43, 5, '2025-10-17 15:00:39', '2025-10-17 15:00:39'),
(151, 43, 38, 5, '2025-10-17 10:12:20', '2025-10-17 10:12:20'),
(150, 43, 10, 6, '2025-10-17 10:12:17', '2025-10-17 15:30:08'),
(149, 43, 60, 1, '2025-10-17 10:12:16', '2025-10-17 10:12:16'),
(164, 43, 49, 1, '2025-10-17 15:29:55', '2025-10-17 15:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `payment_method` varchar(15) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `status`, `payment_method`, `total_amount`, `order_date`) VALUES
(101, 10, 'Pending', 'COD', '2096.50', '2024-11-30 15:56:32'),
(100, 10, 'Pending', 'COD', '20664.50', '2024-11-30 15:55:03'),
(99, 10, 'Pending', 'COD', '5462.50', '2024-11-30 15:53:32'),
(98, 10, 'Pending', 'COD', '2025.00', '2024-11-30 15:49:43'),
(97, 10, 'Pending', 'COD', '9505.00', '2024-11-30 15:36:18'),
(82, 6, 'Delivered', 'COD', '7134.50', '2024-11-30 06:55:13'),
(81, 6, 'Delivered', 'COD', '20505.00', '2024-11-30 06:41:20'),
(96, 10, 'Pending', 'COD', '1849.00', '2024-11-30 15:35:11'),
(95, 10, 'Pending', 'COD', '1326.50', '2024-11-30 15:33:53'),
(94, 10, 'Pending', 'Gcash', '4417.50', '2024-11-30 15:31:47'),
(93, 10, 'Pending', 'Bank Transfer', '100.00', '2024-11-30 15:23:14'),
(92, 10, 'Delivered', 'Bank Transfer', '20334.50', '2024-11-30 15:21:48'),
(91, 10, 'Pending', 'COD', '100.00', '2024-11-30 15:07:06'),
(90, 10, 'Pending', 'COD', '2630.00', '2024-11-30 15:02:34'),
(89, 10, 'Cancelled', 'COD', '14510.00', '2024-11-30 14:53:31'),
(88, 10, 'Cancelled', 'COD', '21055.00', '2024-11-30 14:46:37'),
(87, 10, 'Processing', 'COD', '727.00', '2024-11-30 14:39:56'),
(86, 6, 'Cancelled', 'COD', '14154.70', '2024-11-30 12:46:24'),
(85, 6, 'Processing', 'COD', '4784.90', '2024-11-30 12:41:17'),
(84, 6, 'Cancelled', 'COD', '1563.00', '2024-11-30 12:37:48'),
(83, 6, 'Delivered', 'COD', '5875.00', '2024-11-30 12:35:42'),
(102, 10, 'Pending', 'COD', '540.00', '2024-11-30 16:07:19'),
(103, 10, 'Pending', 'COD', '20664.50', '2024-11-30 16:36:30'),
(104, 10, 'Pending', 'COD', '19416.00', '2024-11-30 16:49:32'),
(105, 10, 'Pending', 'COD', '100.00', '2024-11-30 16:50:54'),
(106, 10, 'Pending', 'COD', '100.00', '2024-11-30 16:51:19'),
(107, 10, 'Pending', 'COD', '100.00', '2024-11-30 16:51:21'),
(108, 10, 'Pending', 'COD', '100.00', '2024-11-30 16:51:22'),
(109, 10, 'Pending', 'COD', '100.00', '2024-11-30 16:52:31'),
(110, 10, 'Pending', 'COD', '4060.00', '2024-11-30 17:01:23'),
(111, 10, 'Pending', 'COD', '13300.00', '2024-11-30 17:20:41'),
(112, 10, 'Pending', 'COD', '4082.00', '2024-11-30 17:29:50'),
(113, 10, 'Pending', 'COD', '4494.50', '2024-11-30 17:35:40'),
(114, 10, 'Pending', 'COD', '20505.00', '2024-11-30 17:41:53'),
(115, 10, 'Shipped', 'COD', '1607.00', '2024-11-30 17:43:45'),
(116, 10, 'Pending', 'Bank Transfer', '9663.40', '2024-11-30 17:47:04'),
(117, 12, 'Pending', 'COD', '70390.00', '2024-12-01 04:17:36'),
(118, 12, 'Pending', 'COD', '88045.00', '2024-12-01 04:20:30'),
(119, 12, 'Pending', 'COD', '100.00', '2024-12-01 04:20:31'),
(120, 12, 'Pending', 'COD', '140680.00', '2024-12-01 04:22:20'),
(121, 12, 'Pending', 'COD', '4060.00', '2024-12-01 04:23:44'),
(122, 6, 'Pending', 'COD', '20664.50', '2024-12-01 07:22:33'),
(123, 6, 'Pending', 'COD', '2298.90', '2024-12-01 07:23:58'),
(124, 10, 'Pending', 'COD', '19416.00', '2024-12-01 07:43:45'),
(125, 10, 'Delivered', 'COD', '7085.00', '2024-12-02 10:00:54'),
(126, 14, 'Cancelled', 'COD', '35025.00', '2024-12-02 14:43:48'),
(127, 14, 'Shipped', 'COD', '173944.00', '2024-12-02 14:45:21'),
(128, 15, 'Delivered', 'COD', '22265.00', '2024-12-03 03:46:40'),
(129, 15, 'Cancelled', 'COD', '5600.00', '2024-12-03 03:55:17'),
(130, 17, 'Pending', 'Bank Transfer', '20664.50', '2024-12-08 18:27:30'),
(131, 10, 'Pending', 'COD', '7085.00', '2024-12-11 09:14:14'),
(132, 10, 'Pending', 'Bank Transfer', '1194.50', '2024-12-11 09:21:13'),
(133, 10, 'Pending', 'COD', '11116.50', '2024-12-11 14:16:23'),
(134, 18, 'Pending', 'COD', '4494.50', '2024-12-11 14:34:12'),
(135, 19, 'Shipped', 'Gcash', '8102.50', '2024-12-12 06:58:45'),
(136, 20, 'Cancelled', 'COD', '10429.00', '2024-12-13 04:10:06'),
(137, 20, 'Pending', 'COD', '74735.00', '2024-12-13 04:11:32'),
(138, 21, 'Cancelled', 'COD', '17689.00', '2024-12-13 04:41:29'),
(139, 21, 'Delivered', 'Gcash', '5875.00', '2024-12-13 04:42:43'),
(140, 21, 'Processing', 'Gcash', '6419.50', '2025-08-09 16:10:13'),
(141, 21, 'Pending', 'COD', '20505.00', '2025-10-16 14:05:01'),
(142, 40, 'Pending', 'COD', '20516.00', '2025-10-16 14:47:32'),
(143, 41, 'Pending', 'COD', '20516.00', '2025-10-16 14:57:21'),
(144, 42, 'Pending', 'COD', '20516.00', '2025-10-16 15:50:50'),
(145, 44, 'Pending', 'COD', '20516.00', '2025-10-16 16:14:45'),
(146, 46, 'Cancelled', 'COD', '4417.50', '2025-10-16 16:46:42'),
(147, 46, 'Cancelled', 'COD', '5853.00', '2025-10-16 16:47:13'),
(148, 46, 'Cancelled', 'COD', '46602.50', '2025-10-17 06:27:47'),
(149, 46, 'Cancelled', 'COD', '21891.00', '2025-10-17 06:38:51'),
(150, 46, 'Pending', 'Gcash', '35245.00', '2025-10-17 06:56:00'),
(151, 46, 'Pending', 'Gcash', '20664.50', '2025-10-17 06:57:39'),
(152, 46, 'Pending', 'Gcash', '7134.50', '2025-10-17 07:19:46'),
(153, 46, 'Pending', 'Gcash', '859.00', '2025-10-17 09:36:01'),
(154, 46, 'Pending', 'COD', '4784.90', '2025-10-17 09:51:17'),
(155, 48, 'Cancelled', 'Gcash', '3301.00', '2025-10-17 15:51:41'),
(156, 48, 'Cancelled', 'Gcash', '3136.00', '2025-10-17 15:54:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 78, 11, 5, '31950.00'),
(2, 79, 59, 1, '970.00'),
(3, 80, 42, 1, '570.00'),
(4, 81, 19, 1, '18550.00'),
(5, 82, 8, 1, '6395.00'),
(6, 83, 43, 5, '1050.00'),
(7, 84, 56, 1, '1330.00'),
(8, 85, 28, 1, '4259.00'),
(9, 86, 28, 3, '4259.00'),
(10, 87, 42, 1, '570.00'),
(11, 88, 12, 3, '6350.00'),
(12, 89, 17, 1, '13100.00'),
(13, 90, 45, 1, '2300.00'),
(14, 92, 14, 1, '18395.00'),
(15, 94, 46, 1, '3925.00'),
(16, 95, 30, 1, '1115.00'),
(17, 96, 57, 1, '1590.00'),
(18, 97, 20, 1, '8550.00'),
(19, 98, 55, 1, '1750.00'),
(20, 99, 35, 1, '4875.00'),
(21, 100, 18, 1, '18695.00'),
(22, 101, 29, 1, '1815.00'),
(23, 102, 33, 1, '400.00'),
(24, 103, 18, 1, '18695.00'),
(25, 104, 7, 1, '17560.00'),
(26, 110, 15, 1, '3600.00'),
(27, 111, 13, 1, '12000.00'),
(28, 112, 48, 1, '3620.00'),
(29, 113, 23, 1, '3995.00'),
(30, 114, 19, 1, '18550.00'),
(31, 115, 53, 1, '1370.00'),
(32, 116, 28, 1, '4259.00'),
(33, 116, 63, 1, '1875.00'),
(34, 116, 58, 2, '995.00'),
(35, 116, 42, 1, '570.00'),
(36, 117, 11, 2, '31950.00'),
(37, 118, 9, 10, '7995.00'),
(38, 120, 11, 4, '31950.00'),
(39, 121, 33, 9, '400.00'),
(40, 122, 18, 1, '18695.00'),
(41, 123, 22, 1, '1999.00'),
(42, 124, 7, 1, '17560.00'),
(43, 125, 12, 1, '6350.00'),
(44, 126, 12, 5, '6350.00'),
(45, 127, 7, 9, '17560.00'),
(46, 128, 62, 10, '2015.00'),
(47, 129, 67, 1, '5000.00'),
(48, 130, 18, 1, '18695.00'),
(49, 131, 12, 1, '6350.00'),
(50, 132, 61, 1, '995.00'),
(51, 133, 8, 1, '6395.00'),
(52, 133, 48, 1, '3620.00'),
(53, 134, 23, 1, '3995.00'),
(54, 135, 44, 5, '1455.00'),
(55, 136, 10, 2, '4695.00'),
(56, 137, 51, 2, '24400.00'),
(57, 137, 12, 3, '6350.00'),
(58, 138, 9, 2, '7995.00'),
(59, 139, 43, 5, '1050.00'),
(60, 140, 10, 1, '4695.00'),
(61, 140, 43, 1, '1050.00'),
(62, 141, 19, 1, '18550.00'),
(63, 142, 7, 1, '18560.00'),
(64, 143, 7, 1, '18560.00'),
(65, 144, 7, 1, '18560.00'),
(66, 145, 7, 1, '18560.00'),
(67, 146, 46, 1, '3925.00'),
(68, 147, 47, 1, '5230.00'),
(69, 148, 55, 1, '1750.00'),
(70, 148, 20, 1, '8550.00'),
(71, 148, 8, 5, '6395.00'),
(72, 149, 14, 1, '18395.00'),
(73, 149, 32, 1, '1415.00'),
(74, 150, 11, 1, '31950.00'),
(75, 151, 18, 1, '18695.00'),
(76, 152, 8, 1, '6395.00'),
(77, 153, 60, 1, '690.00'),
(78, 154, 28, 1, '4259.00'),
(79, 155, 59, 3, '970.00'),
(80, 156, 60, 4, '690.00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `prod_description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `time_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `prod_description`, `price`, `stock`, `category`, `product_image`, `time_created`, `time_updated`) VALUES
(7, 'Intel Core i7-12th', 'Experience the pinnacle of performance with the Intel Core i7-12700 Alder Lake Processor. Socket LGA 1700 compatibility, 4.90GHz clock speed, and cutting-edge technology redefine computing power. Elevate your system with unparalleled speed and efficiency.', '18560.00', 10, 'Processor', 'assets/products/image_2025-10-16_220235377.png', '2024-11-28 14:29:03', '2025-10-17 10:24:47'),
(8, 'AMD Ryzen 5 5600X', 'Unleash gaming prowess with the AMD Ryzen 5 5600X. Socket AM4, 3.7GHz base clock, and Wraith Stealth Cooler provide optimal performance. Elevate your experience with VR Ready capabilities, delivering premium desktop processing power for an immersive gaming journey.', '6395.00', 10, 'Processor', 'assets/products/AMD_Ryzen_5_5600X_Socket_AM4_3.7GHz_with_Wraith_Stealth.webp', '2024-11-28 14:33:44', '2025-10-17 10:24:52'),
(9, 'Intel Core I5-12400', 'Experience cutting-edge computing with the Intel Core i5-12400 Alder Lake Processor. Socket 1700, 2.5GHz base clock, and advanced features deliver optimal performance for your demanding tasks. Upgrade to efficient and powerful processing with this Intel Core CPU.', '7995.00', 10, 'Processor', 'assets/products/IntelCoreI5-12400AlderLake2.5GHz.webp', '2024-11-28 14:35:26', '2024-12-13 04:42:18'),
(10, 'AMD Ryzen 5 4600G', 'Unleash powerful performance with AMD Ryzen 5 4600G. Boasting a 3.7GHz clock speed and Radeon Graphics, it delivers seamless multitasking and gaming. Includes Q100v2 MPK cooler for efficient heat management. Elevate your computing experience with this dynamic processor.', '4695.00', 10, 'Processor', 'assets/products/AMD_Ryzen_5_4600G_Socket_Am4_3.7GHz_with_Radeon_Graphics_Processor_with_CPU_Cooler.webp', '2024-11-28 14:36:38', '2025-10-17 10:24:57'),
(11, 'AMD RYZEN 7 9800X3D', 'The AMD Ryzen 7 9800X3D AM5 processor delivers exceptional speed with 8 cores, 16 threads, and a 5.2GHz boost clock. Featuring 3D V-Cache and integrated Radeon Graphics, it excels in gaming and productivity. With DDR5 and PCIe 5.0 support, it ensures lightning-fast performance and seamless multitasking.', '31950.00', 10, 'Processor', 'assets/products/AMDRYZEN79800X3D.webp', '2024-11-28 14:37:09', '2025-10-17 10:25:11'),
(12, 'Asrock B550M Pro4', 'Power up your gaming rig with the Asrock B550M Pro4 Motherboard. Designed for Socket AM4 and DDR4 memory, it includes a reliable power supply. Elevate your gaming experience with this feature-packed motherboard, ensuring stability and performance for your PC build.', '6350.00', 10, 'Motherboard', 'assets/products/B550M Pro4-Asrock.png', '2024-11-28 15:48:37', '2025-10-17 10:25:16'),
(13, 'ASUS Prime B760M-A WIFI D4', 'Elevate your system with the ASUS Prime B760M-A WIFI D4 motherboard. LGA 1700 socket, DDR4 support, and integrated Wi-Fi. Experience reliability and performance in a sleek design. Unleash seamless computing with this feature-rich motherboard.\r\n\r\n', '12000.00', 9, 'Motherboard', 'assets/products/prime-b760m-a.webp', '2024-11-28 15:54:40', '2024-11-30 17:20:41'),
(14, 'Gigabyte Z890 GAMING X WIFI7', 'The Gigabyte Z890 GAMING X WIFI7 (GA-Z890-GAMING-X-WIFI7) LGA 1851 motherboard offers ultimate performance with DDR5 support, PCIe 5.0, and Wi-Fi 7 connectivity. Designed for Intel Ultra processors, it features robust power delivery, advanced cooling, and RGB Fusion for a premium gaming and multitasking experience.', '18395.00', 9, 'Motherboard', 'assets/products/z790-gigabyte-x-wifi7.png', '2024-11-28 15:56:18', '2025-10-17 09:36:11'),
(15, 'Asrock A520M-HVS', 'Build your AM4 system with the ASRock A520M-HVS motherboard. Supporting DDR4 memory, it offers reliability and compatibility. Compact yet feature-rich, it\'s perfect for budget-friendly builds without sacrificing performance.\r\n\r\n', '3600.00', 9, 'Motherboard', 'assets/products/A520M-HVS(M1).png', '2024-11-28 15:57:08', '2024-11-30 17:01:23'),
(16, 'Asus Prime H610M-R D4', 'The Asus Prime H610M-R D4 Socket LGA 1700 DDR4 Motherboard supports 12th Gen Intel processors, offering robust power delivery and enhanced cooling. Features include DDR4 memory support, PCIe 4.0, M.2 slots, and USB 3.2 Gen 1 ports. Ideal for reliable performance and flexible connectivity in a versatile ATX form factor.\r\n\r\n', '4595.00', 10, 'Motherboard', 'assets/products/h610m-r d4-asus.jpg', '2024-11-28 15:57:52', '2024-11-28 15:57:52'),
(17, 'Asrock RX 6600 8G CHALLENGER D', 'Dominate gaming realms with the Asrock RX 6600 8G CHALLENGER D. 8GB GDDR6, 128-bit interface, and dual-fan cooling for supreme graphics performance. Elevate your gaming experience with this powerful video card, designed for exceptional visuals and seamless gameplay.\r\n\r\n', '13100.00', 10, 'GPU', 'assets/products/Radeon RX 6600 Challenger D 8GB(M1).png', '2024-11-28 16:10:19', '2024-12-11 09:20:47'),
(18, 'MSI NVIDIAÂ® GeForce RTX 3060 Ventus 2X OC 12gb', 'Discover the ultimate gaming power with MSI RTX 3060 Ventus 2X OC 12GB. Boasting 192-bit GDDR6 memory and PCI Express Gen 4, it offers incredible speed and responsiveness. G-SYNC technology ensures tear-free, immersive gaming. Unleash unparalleled graphics performance for an unmatched gaming adventure.\r\n\r\n', '18695.00', 10, 'GPU', 'assets/products/3060-msi-12gb.png', '2024-11-28 16:12:55', '2025-10-17 10:25:47'),
(19, 'Galax RTX 4060 8GB 1-Click OC 2X V2 Dual Fan', 'Dominate gaming with Galax RTX 4060 8GB 1-Click OC 2X V2. Featuring 128-bit GDDR6 and dual-fan cooling, it ensures exceptional performance and thermal efficiency. Elevate your gaming experience with this powerhouse video card!\r\n\r\n', '18550.00', 8, 'GPU', 'assets/products/4060-galax-rtx.png', '2024-11-28 16:13:57', '2025-10-16 14:05:01'),
(20, 'MSI NVIDIAÂ® GeForce GTX 1650 D6 Ventus XS OC/XC OC V3 4gb', 'Unleash gaming prowess with MSI GTX 1650 D6 Ventus XS OC V3. Elevate performance with 4GB GDDR6, 128-bit memory, and OC/XC capabilities. Immerse yourself in smooth graphics, advanced cooling, and MSI reliability. Upgrade your gaming experience today!\r\n\r\n', '8550.00', 8, 'GPU', 'assets/products/1650-gtx.jpg', '2024-11-28 16:15:02', '2025-10-17 06:27:47'),
(21, 'SAPPHIRE NITRO+ RX 7800 XT GAMING OC 16GB', 'The SAPPHIRE NITRO+ RX 7800 XT GAMING OC graphics card packs 16GB GDDR6 memory on a 256-bit interface, offering exceptional gaming performance. With advanced cooling and overclocking, it delivers smooth gameplay and stunning visuals. Perfect for gamers seeking high-quality graphics and superior gaming experiences. \r\n\r\n', '36285.00', 10, 'GPU', 'assets/products/rx7800xt_nitro_16gb_amd.jpg', '2024-11-28 16:17:01', '2024-11-28 16:17:01'),
(22, 'Team Elite Vulcan TUF 16GB DDR4 Memory', 'Team Elite Vulcan TUF 16GB DDR4 Memory (2x8GB) at 3200MHz offers powerful gaming performance. Dual-channel setup ensures seamless multitasking. Compatible with TUF motherboards, it\'s reliable and efficient. Elevate your gaming rig with Team Elite Vulcan for optimal speed and responsiveness.\r\n\r\n', '1999.00', 9, 'Memory', 'assets/products/Team-Group-T-Force-Vulcan-TUF-16GB-2x8GB-DDR4-3600MHz-CL18-Dekstop-Memory-Gaming-RAM-2-1-jpg.webp', '2024-11-28 16:26:26', '2024-12-01 07:23:58'),
(23, 'Team Elite TForce Delta 16GB DDR4 Memory', 'Team Elite TForce Delta 16GB DDR4 Memory (2x8GB) delivers seamless 3200MHz performance. The white heatsink with RGB lighting enhances both cooling and style. Perfect for gamers, ensuring fast multitasking and a visually captivating setup. Upgrade for optimal speed, stability, and aesthetics in your gaming rig.\r\n\r\n', '3995.00', 8, 'Memory', 'assets/products/Team_Elite_TForce_Delta_16gb_Memory_White-a_2048x2048.webp', '2024-11-28 16:27:09', '2024-12-11 14:34:12'),
(24, 'Adata XPG Spectrix D50 16GB DDR4 RAM', 'Elevate your system with Adata XPG Spectrix D50 16GB DDR4 RAM. Featuring 3200MHz speed, dynamic RGB lighting, and a sleek white design, this memory kit delivers both style and performance. Unleash the power of dual 8GB modules for enhanced multitasking and an immersive computing experience.\r\n\r\n', '3185.00', 10, 'Memory', 'assets/products/adata-xpg-spectrix d50.jpg', '2024-11-28 16:28:12', '2024-11-28 16:28:12'),
(25, ' G.Skill Trident Z5 Neo 32GB DDR5 RGB memory', 'Elevate your AMD-powered system with G.Skill Trident Z5 Neo 32GB DDR5 RGB memory, featuring 6000MHz speed. Unleash powerful performance and stunning aesthetics for your gaming and multitasking needs. Upgrade your rig with this AMD Expo Memory now! \r\n\r\n', '8655.00', 10, 'Memory', 'assets/products/G.Skill Trident Z5 Neo 32gb 2x16 6000mhz Ddr5 RGB AMD Expo Memory.jpg', '2024-11-28 16:28:58', '2024-11-28 16:28:58'),
(26, 'Kingston Fury Beast 16GB DDR4 Memory', 'Elevate your system\'s performance with Kingston Fury Beast 16GB DDR4 Memory. Boasting a rapid 3600MT/s speed, this kit of two 8GB modules ensures seamless multitasking. The sleek black design, complemented by RGB lighting, adds a touch of style to your rig. Upgrade to unleash the beast within!\r\n\r\n', '3725.00', 10, 'Memory', 'assets/products/Kingston Fury Beast 16gb 2x8.webp', '2024-11-28 16:29:33', '2024-11-28 16:29:33'),
(27, 'Kingston NV1 PCIe M.2 3.0 NVME 1TB', 'Kingston NV1 M.2 SSDs, your gateway to unparalleled speed and storage. NV1 boasts PCIe 3.0 NVMe. Upgrade to seamless, high-performance storage for your system.', '3055.00', 10, 'SSD', 'assets/products/kingston nv1 1tb.jpg', '2024-11-28 16:38:03', '2024-11-28 16:38:03'),
(28, 'Crucial P3 Plus 1TB PCIe 4.0 Gen4 NVMe M.2 SSD', 'Revolutionize your storage with Crucial P3 Plus 1TB PCIe 4.0 NVMe M.2 SSD. Unleash Gen4 speed for rapid data access and seamless performance. Elevate your system\'s responsiveness with this high-capacity, high-speed SSD.\r\n\r\n', '4259.00', 11, 'SSD', 'assets/products/Crucial-P3-Plus-1TB-PCIe-4.0-3D-NAND-NVMe-M.2-SSD-CT1000P3PSSD8.jpg', '2024-11-28 16:40:20', '2025-10-17 09:51:17'),
(29, 'Adata SU650 SSD 512GB SATA 2.5', 'Elevate your storage game with the Adata SU650 Solid State Drive - 512GB. Experience faster data access and reliability with this SATA 2.5\" SSD. It\'s the perfect upgrade for improved performance and efficiency in your computing tasks. \r\n\r\n', '1815.00', 9, 'SSD', 'assets/products/Adata su650.jpg', '2024-11-28 16:40:57', '2024-11-30 15:56:32'),
(30, 'Team Group GX2 256gb SATA 2.5 SSD', 'Elevate your storage with Team Group GX2 256GB SATA 2.5 SSD. Experience swift data access and reliable performance in a compact form. Ideal for upgrading laptops or desktops, this SSD delivers enhanced speed and efficiency for a seamless computing experience.\r\n\r\n', '1115.00', 9, 'SSD', 'assets/products/gx2 team group 256gb.jpg', '2024-11-28 16:41:52', '2024-11-30 15:33:53'),
(31, 'Western Digital Green SN350 1TB NVMe M.2 2280 SSD', 'Western Digital Green SN350 NVMe M.2 SSD, available in 1TB, delivers rapid data access in a compact form. Ideal for laptops and desktops, enjoy quick boot times, faster application loading, and smooth multitasking. Upgrade your system with reliable and efficient storage solutions.\r\n\r\n', '3999.00', 10, 'SSD', 'assets/products/wd-green-sn350-nvme-ssd-1tb.webp', '2024-11-28 16:43:18', '2024-11-28 16:43:18'),
(32, 'Acer AC-550 550w Full Modular 80plus Bronze PSU', 'Introducing the Acer AC-550 550W Full Modular Power Supply. Engineered for efficiency and performance with 80 Plus Bronze certification. Its modular design ensures easy cable management, optimizing airflow. Delivering reliable power for your system, it\'s a must-have for your build.\r\n\r\n', '1415.00', 9, 'Power Supply', 'assets/products/Acer_AC-550_550w_Full_Modular_80plus_Bronze_Power_Supply-b_540x.webp', '2024-11-28 16:48:01', '2025-10-17 06:38:51'),
(33, 'Intelligent 700 watts Dual 12V PSU', 'The Intelligent 700W Dual 12V Power Supply offers efficient and reliable power for your PC. With dual 12V rails, it ensures stable performance for high-end components. Its 700W capacity supports demanding tasks and gaming, making it a smart choice for gamers and professionals seeking optimal power delivery.', '400.00', 10, 'Power Supply', 'assets/products/Intelligent_700_watts_Dual_12V_Power_Supply-a_540x.webp', '2024-11-28 16:48:40', '2024-12-01 06:26:49'),
(34, 'Corsair CX650 650 watts 80 Plus Bronze PSU', 'Elevate your PC\'s power with the Corsair CX650 650W 80 Plus Bronze Power Supply. Delivering reliable, efficient performance, it ensures stable power for your system. Upgrade confidently for enhanced computing capabilities and consistent energy delivery.', '3315.00', 10, 'Power Supply', 'assets/products/CorsairCX650Bronze_PowerSupplywebp.webp', '2024-11-28 16:49:08', '2024-11-28 16:49:08'),
(35, 'Seasonic Focus Gold 750W, 50W 80 Plus Multi-GPU setup Semi-Modular Cables PSU', 'Unleash power with Seasonic Focus Gold 750W PSU. 80 Plus Gold efficiency, 50W reserve, and semi-modular cables support multi-GPU setups. Elevate your system with reliable, efficient, and customizable power delivery for optimal performance.', '4875.00', 9, 'Power Supply', 'assets/products/seasonic_electronics_focus_gm_750_750w_80_1582653991_1548504.jpg', '2024-11-28 16:49:55', '2024-11-30 15:53:32'),
(36, 'Gigabyte P550B 550 watts 80 Plus Bronze, 120mm Silent Hydraulic Bearing (HYB) Fan, PSU', 'Power up your system reliably with the Gigabyte P550B 550W 80 Plus Bronze Power Supply. Delivering efficient performance, this PSU ensures stable power for your components. With 80 Plus Bronze certification, it combines reliability and energy efficiency. Upgrade your system with Gigabyte\'s trusted power solution.', '2955.00', 10, 'Power Supply', 'assets/products/GigabyteP550B.webp', '2024-11-28 16:50:56', '2024-11-28 16:50:56'),
(37, 'RAKK DANUM 240MM AIO CPU Liquid Cooling RGB Black', 'Cool your CPU with the RAKK DANUM 240MM AIO Liquid Cooling system. This RGB-enabled cooler features a 240mm radiator for efficient heat dissipation, ensuring peak performance. With vibrant RGB lighting and a sleek black design, it offers both style and function, keeping your system cool under pressure.', '2195.00', 10, 'AIO Cooling', 'assets/products/RAKK_DANUM_240MM_AIO_CPU_Liquid_Cooling_RGB_Black-b_540x.webp', '2024-11-28 16:59:34', '2024-11-28 16:59:34'),
(38, 'Deepcool AK620 Dual Tower CPU Cooler Black', 'Unleash peak cooling performance with the Deepcool AK620 Dual Tower CPU Cooler. Its sleek black design conceals twin towers for efficient heat dissipation, ensuring optimal temperature control for your high-performance PC.\r\n', '2950.00', 10, 'AIO Cooling', 'assets/products/ak620-deepcool-dualtower.jpg', '2024-11-28 17:00:12', '2024-11-28 17:00:12'),
(39, 'DarkFlash DM12 F 3in1 with Controller Remote RGB Chassis Fan White', 'Transform your PC with the DarkFlash DM12 F 3in1 ARGB Chassis Fans in elegant white. Includes a remote controller for customizable lighting effects, enhancing your gaming setup\'s style and performance with ease. Elevate your rig today!', '1195.00', 10, 'AIO Cooling', 'assets/products/DarkFlash-DM12F-A-RGB-White-13.jpg', '2024-11-28 17:00:54', '2024-11-28 17:00:54'),
(40, 'APNX FP1-120 PWM ARGB Chassis Fan Black', 'The Aerocool APNX FP1-120 PWM ARGB Chassis Fan in black features stunning ARGB lighting and PWM control for optimal airflow. Its sleek design ensures efficient cooling and elevates your PC\'s aesthetics, making it a perfect choice for users seeking high performance and style.', '350.00', 10, 'AIO Cooling', 'assets/products/_APNX_FP1-120_PWM_ARGB_Chassis_Fan_Black-d_540x.webp', '2024-11-28 17:02:17', '2024-11-28 17:02:17'),
(41, 'YGT DF-360 ARGB CPU Liquid Cooling Black', 'Revolutionize your PC cooling with the YGT DF-360 ARGB CPU Liquid Cooling in sleek black. Experience superior heat dissipation and stunning ARGB lighting. Elevate your system\'s performance and aesthetics with this advanced liquid cooling solution.', '2850.00', 10, 'AIO Cooling', 'assets/products/YGT_DF-360_ARGB_CPU_Liquid_Cooling_Black-a_grande.webp', '2024-11-28 17:02:47', '2024-11-28 17:02:47'),
(42, 'InPlay Wind 05 and Wind 01 Acrylic Side Panel Micro ATX Durable Tempered Glass PC Case', 'Elevate your build with InPlay Wind 05 and Wind 01 Micro ATX PC Cases. Durable tempered glass meets acrylic side panels for a sleek aesthetic. Unleash performance in a compact form. Elevate your PC with these cases, where durability meets style seamlessly.', '570.00', 9, 'PC Case', 'assets/products/CASING_Inplay_Wind_01_mATX_b.webp', '2024-11-28 17:07:04', '2024-12-11 09:20:40'),
(43, 'RAKK MASID MATX Tempered Gaming Case Black', 'The RAKK MASID MATX Tempered Gaming Case in black combines style and functionality. Its tempered glass side panel showcases your components, while optimized airflow ensures peak performance. It supports mATX builds, with room for multiple fans, radiators, and clean cable management for a sleek, pro setup.', '1050.00', 10, 'PC Case', 'assets/products/RAKK_MASID_MATX_Tempered_Gaming_Case_Black-b_540x.webp', '2024-11-28 17:07:37', '2025-10-17 10:26:16'),
(44, 'DarkFlash DB330M Micro ATX Tempred Glass Side and Front Panel Gaming PC Case Black', 'Experience the ultimate gaming setup with the darkFlash DB330M Micro ATX PC Case. Featuring tempered glass side and front panels, it showcases your build in stunning style. With optimized airflow, versatile cooling options, and easy cable management, this black case offers both aesthetics and performance.', '1455.00', 10, 'PC Case', 'assets/products/DarkfFlash_DB330M_Micro_ATX_Tempred_Glass_Side_and_Front_Panel_Gaming_PC_Case_Black-c_83974378-19a6-4cd3-9527-79b6ef6f978b_540x.webp', '2024-11-28 17:08:02', '2025-10-17 10:26:13'),
(45, 'Keytech Cyborg ROG Micro ATX with 6fans Gaming PC Case Black', 'Unleash the gaming beast with Keytech Cyborg ROG Micro ATX Gaming PC Case in sleek black. Designed for gamers, it combines style and functionality. With ample space, advanced cooling options, and a striking ROG-inspired design, it\'s the ultimate choice for a high-performance gaming setup.', '2300.00', 9, 'PC Case', 'assets/products/Keytech_Cyborg_ROG_Black-b_b780b80a-ab76-4868-b788-4bda21967e59_540x.webp', '2024-11-28 17:08:23', '2024-11-30 15:02:34'),
(46, 'Cooler Master Masterbox TD300 Mesh Micro ATX Gaming PC Case Black', 'The Cooler Master Masterbox TD300 Mesh Micro ATX Gaming PC Case in Black offers high airflow with its mesh front panel, ensuring optimal cooling for your components. Compact yet spacious, it supports multiple cooling options and up to Micro ATX motherboards. Its edgy design and tempered glass side panel make it perfect for stylish, efficient gaming setups.', '3925.00', 9, 'PC Case', 'assets/products/Cooler Master Masterbox TD300 Mesh Micro ATX Gaming PC Case Black.jpg', '2024-11-28 17:08:50', '2025-10-16 16:47:04'),
(47, 'Nvision EG24S1 PRO 180HZ Flat IPS Panel 24\" Gaming Monitor Black', 'Experience gaming like never before with the Nvision EG24S1 PRO 180Hz Gaming Monitor. Featuring a sleek black design and a flat IPS panel, it delivers stunning visuals with smooth gameplay at 180Hz. Immerse yourself in the action and gain a competitive edge with this high-performance monitor.\r\n\r\n', '5230.00', 10, 'Monitor', 'assets/products/Nvision EG24S1 PRO 180HZ.webp', '2024-11-28 17:15:30', '2025-10-17 06:57:46'),
(48, 'MSI Pro MP223 21.5\" 100Hz VA Monitor', ' Maximize productivity with the MSI Pro MP223 21.5\" VA Monitor. Boasting a 100Hz refresh rate and VA panel, it offers smooth visuals and rich contrasts. Elevate your work setup with this efficient and immersive monitor.', '3620.00', 8, 'Monitor', 'assets/products/MSI Pro MP223 21.5 100Hz VA Monitor.jpg', '2024-11-28 17:15:54', '2024-12-11 14:16:23'),
(49, 'ViewPlus MG-27KI 27\" 165Hz 1MS 2K IPS Gaming Monitor Black', 'Experience gaming in stunning detail with the ViewPlus MG-27KI. This 27\" IPS monitor boasts a 2K resolution, 165Hz refresh rate, and lightning-fast 1ms response time for smooth gameplay. Immerse yourself in vibrant colors and precision performance. Elevate your gaming setup with ViewPlus.', '7420.00', 10, 'Monitor', 'assets/products/ViewPlus_MG-27KI_27_165Hz_1MS_2K_IPS_Gaming_Monitor_Black-a_540x.webp', '2024-11-28 17:16:18', '2024-11-28 17:16:18'),
(50, 'Gigabyte GS27F 27\" 165Hz/170Hz OC IPS Gaming Monitor Black', 'The Gigabyte GS27F 27\" gaming monitor in black boasts a 165Hz/170Hz OC refresh rate and IPS panel for fluid, high-quality visuals. Its sleek design and gaming-oriented features deliver an immersive experience, making it perfect for competitive gaming and multimedia enjoyment.', '10600.00', 10, 'Monitor', 'assets/products/9083_Gigabyte_GS27F_27_165Hz_170Hz_OC_IPS_Gaming_Monitor_Black-d_540x.webp', '2024-11-28 17:16:44', '2024-11-28 17:16:44'),
(51, 'Asus TUF VG279QM 27 280Hz IPS FreeSync Gaming Monitor', 'Experience gaming like never before with the Asus TUF VG279QM 27\" 280Hz IPS FreeSync Gaming Monitor. Enjoy buttery-smooth visuals, vibrant colors, and tear-free gameplay. Elevate your gaming setup with Asus TUF quality and performance.', '24400.00', 8, 'Monitor', 'assets/products/Asus TUF VG279QM 27 280Hz IPS FreeSync Gaming Monitor.jpg', '2024-11-28 17:17:06', '2024-12-13 04:11:32'),
(52, 'Secure 220v Avr', 'Ensure stable power for your devices with the Secure 220V AVR. Guard against voltage fluctuations and power surges, providing reliable protection. This Automatic Voltage Regulator is essential for preserving the longevity and performance of your electronics, offering peace of mind in every power environment.', '250.00', 10, 'UPS/AVR', 'assets/products/Secure 220v Avr.jpg', '2024-11-28 17:20:03', '2024-11-28 17:50:25'),
(53, 'Secure 650va Ups', 'Ensure uninterrupted power with the Secure 650VA UPS. Compact and reliable, it safeguards your devices during power outages. Elevate protection for your electronics and stay connected with Secure 650VA UPS.', '1370.00', 9, 'UPS/AVR', 'assets/products/Secure 650va Ups.webp', '2024-11-28 17:20:31', '2024-11-30 17:43:45'),
(54, 'iLogic Blazer 1000va Ups', 'The iLogic Blazer 1000VA UPS is your reliable power backup solution. With a 1000VA capacity, it safeguards your devices during power outages, providing uninterrupted power. Its efficient design and surge protection ensure your electronics stay safe. Keep your systems running smoothly with this essential UPS.', '2540.00', 10, 'UPS/AVR', 'assets/products/iLogic Blazer 1000va Ups.jpg', '2024-11-28 17:20:58', '2024-11-28 17:50:33'),
(55, 'RAKK TALA 81 Keys White / Trimode / RGB / Universal Hotswap / Gasket Mount / MS Red Switch/ Black', 'The RAKK TALA 81 Keys keyboard in black features a compact layout, RGB lighting, and universal hot-swappable switches. With trimode connectivity (wired, Bluetooth, 2.4GHz), gasket mount design for enhanced typing comfort, and equipped with MS Red switches, it offers a versatile and premium typing experience.', '1750.00', 9, 'Peripherals', 'assets/products/RAKK_TALA_81_Keys_White_Trimode_RGB_Universal_Hotswap_Gasket_Mount_Brown_Switch_Black-a_540x.webp', '2024-11-28 17:30:10', '2025-10-17 07:19:51'),
(56, 'Redragon K617 FIZZ 60% Red Switch, Black/ Grey & White Case Wired RGB Gaming Keyboard', 'Dominate your gaming with the Redragon K617 FIZZ 60% Mechanical Keyboard. Engineered with responsive Red switches, its compact black/grey or white case delivers both style and functionality. Illuminate your setup with RGB lighting and experience precision gaming like never before.', '1330.00', 11, 'Peripherals', 'assets/products/Redragon_K617_FIZZ_60_Red_Switch_Black_Grey-a_540x.webp', '2024-11-28 17:30:51', '2024-12-01 16:14:34'),
(57, 'Fantech MAXPOWER MK853 RED/BLUE Switch White Mechanical Gaming Keyboard', 'Conquer your gaming realm with the Fantech MAXPOWER MK853 Mechanical Gaming Keyboard. Featuring responsive RED/BLUE switches, its white design adds a touch of sophistication. Elevate your gameplay with precision and style, dominating every keystroke in the virtual battlefield.', '1590.00', 9, 'Peripherals', 'assets/products/Fantech_MAXPOWER_MK853_RED-a_540x.webp', '2024-11-28 17:31:21', '2024-11-30 15:35:11'),
(58, 'RAKK DASIG X Ambidextrous Hotswap Trimode PMW3325 Huano 80M RGB Gaming Mouse Black', 'Dominate with precision using the RAKK DASIG X gaming mouse in Black. Designed for ambidextrous use, it features a Trimode PMW3325 sensor, Huano 80M switches, and vibrant RGB lighting. With hotswap capability, this mouse ensures a customized and responsive gaming experience. Elevate your gameplay in style.', '995.00', 8, 'Peripherals', 'assets/products/RAKK DASIG X Ambidextrous Hotswap Trimode PMW3325 Huano 80M RGB Gaming Mouse Black.jpg', '2024-11-28 17:31:54', '2024-11-30 17:47:04'),
(59, 'Logitech G102 Light Sync Black and White Gaming Mouse', 'Step into precision gaming with the Logitech G102 Light Sync Gaming Mouse. Sleek in black and white, it offers responsive control and customizable RGB lighting. Elevate your gameplay with this stylish and high-performance accessory.', '970.00', 10, 'Peripherals', 'assets/products/Logitech G102 Light Sync Black and White Gaming Mouse.jpg', '2024-11-28 17:32:19', '2025-10-17 15:51:43'),
(60, 'Fantech Venom II WGC2+ Mouse Black', 'The Fantech Venom II WGC2+ Mouse in Black offers precision with its 16000 DPI sensor, ergonomic design, and robust build. It features 8 programmable buttons, customizable RGB lighting, and a 2.4GHz wireless connection with a 10m range. Perfect for gamers seeking high performance and style.', '690.00', 9, 'Peripherals', 'assets/products/Fantech Venom II WGC2+ Mouse Black.jpg', '2024-11-28 17:32:40', '2025-10-17 15:54:29'),
(61, 'RAKK Kusog Pro 7.1 RGB Gaming Headset USB Black', '\r\nImmerse in superior gaming audio with the RAKK Kusog Pro 7.1 RGB Gaming Headset. USB connectivity, black sleek design, and RGB lighting offer a personalized gaming experience. Elevate your gameplay with precision sound, comfort, and style in this cutting-edge headset.', '995.00', 9, 'Peripherals', 'assets/products/RAKK_Kusog_Pro_7.1_RGB_Gaming_Headset_USB_Black-c_540x.webp', '2024-11-28 17:33:20', '2024-12-11 09:21:13'),
(62, 'Fantech WHG03P Studio Pro 7.1 Surround Sound Headset White', 'Fantech WHG03P Studio Pro 7.1 Surround Sound Headset in White: Enjoy immersive 7.1 surround sound, a noise-canceling mic, ergonomic design, adjustable headband, in-line controls, and durable construction. Ideal for gaming and multimedia, delivering superior audio clarity and all-day comfort in a sleek white finish.', '2015.00', 10, 'Peripherals', 'assets/products/Fantech WHG03P Studio Pro 7.1 Surround Sound Headset White.webp', '2024-11-28 17:34:11', '2024-12-09 07:10:44'),
(63, 'Redragon H848 IRE PRO 7.1 Tri-mode Wireless Headset', 'Immerse yourself in superior audio with the Redragon H848 IRE PRO 7.1 Wireless Headset. Tri-mode connectivity provides versatility, while the sleek black design complements its premium sound quality. Experience comfort and precision for an unparalleled gaming and multimedia journey.', '1875.00', 9, 'Peripherals', 'assets/products/Redragon H848 IRE PRO 7.1 Tri-mode Wireless Headset.jpg', '2024-11-28 17:34:46', '2024-11-30 17:47:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_contact` varchar(15) DEFAULT NULL,
  `user_address` text DEFAULT NULL,
  `role` enum('Customer','Admin') DEFAULT 'Customer',
  `user_img` varchar(255) DEFAULT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `time_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fullname`, `username`, `password`, `email`, `user_contact`, `user_address`, `role`, `user_img`, `time_created`, `time_updated`, `reset_token`, `token_expiry`) VALUES
(1, 'Yhuri Evangelista', 'admin1', '1admin1', 'evangelista.yhuri.bsit@gmail.com', NULL, NULL, 'Admin', NULL, '2024-12-13 04:35:48', '2024-12-13 04:35:48', NULL, NULL),
(21, 'All Foren', 'allfor00', 'pogiako123', 'allfornetflix22@gmail.com', '09472726438', 'Bulakan, Bulacan', 'Customer', 'yhuri.png', '2024-12-13 04:54:39', '2024-12-13 04:54:39', NULL, '2024-12-13 00:53:55'),
(6, 'Yhuri Evangelista', 'yhuri23', 'qweqwe', 'yhuri@gmail.com', '09472726438', 'Malolos, Bulacan', 'Customer', 'yhuri.png', '2024-12-09 07:07:16', '2024-12-09 07:07:16', 'ce55935ccbbe86558b53a837540cbfb7', '2024-12-09 03:07:16'),
(19, 'John Ruiz', 'ruiz', 'ruzzy', 'ruizdehonor23@gmail.com', '09876543212', 'marilao', 'Customer', 'Women\'s Volleyball, CICT vs CSER , Roxas Court.jpg', '2024-12-13 04:36:56', '2024-12-13 04:36:56', NULL, '2024-12-12 03:04:23'),
(10, 'Mang Inasal', 'mang123', 'qweqwe123', 'signpeace92@gmail.com', '09472726438', 'Bulakan, Bulacan', 'Customer', 'Digilist.png', '2024-12-13 04:52:42', '2024-12-13 04:52:42', NULL, '2024-12-13 00:46:10'),
(12, 'Arisuu', 'Arisuu', 'Pazzwordko03', 'jr.pogi8888@gmail.com', '09321317130', 'pitpitan', 'Customer', NULL, '2024-12-01 04:13:25', '2024-12-01 04:13:25', NULL, NULL),
(18, 'Ian', 'ianjuan', 'ianpogi', 'ianlawrencejuan12@yahoo.com', '09999999999', 'sdasdasd', 'Customer', NULL, '2024-12-11 14:33:58', '2024-12-11 14:33:58', NULL, NULL),
(14, 'Paul Valderama', 'Paul', '12345', 'ict.caballeroja@gmail.com', '09213456789', 'Malolos Bulacan', 'Customer', 'Ian.png', '2024-12-09 07:08:07', '2024-12-09 07:08:07', 'ae685b21b0a8e6e0b28d1769dca8673e', '2024-12-09 03:08:07'),
(15, 'Len Sison', 'len123', 'sample1', 'sisonlennathaniel@gmail.com', '09567892324', 'Kuala Lumpur', 'Customer', 'dashboardpng.png', '2024-12-13 04:36:16', '2024-12-13 04:36:16', NULL, '2024-12-02 23:48:06'),
(42, 'Flores3394', 'user_h5uhsiqy', 'TestPass3394!', 'testuser_h5uhsiqy@example.com', '09412616471', '123 Test Street, Quezon City, Metro Manila, Philippines', 'Customer', NULL, '2025-10-16 15:50:08', '2025-10-16 15:50:08', NULL, NULL),
(43, 'Ramos0453', 'user_fznhqjwf', 'TestPass0453!', 'testuser_fznhqjwf@example.com', NULL, NULL, 'Customer', NULL, '2025-10-16 16:10:45', '2025-10-16 16:10:45', NULL, NULL),
(44, 'Reyes1017', 'user_50a2byjr', 'TestPass1017!', 'testuser_50a2byjr@example.com', '09665822533', '123 Test Street, Quezon City, Metro Manila, Philippines', 'Customer', NULL, '2025-10-16 16:14:03', '2025-10-16 16:14:03', NULL, NULL),
(45, 'Ian Caballero', 'IanC123', 'IanC123', 'iancaballero@gmail.com', NULL, NULL, 'Customer', NULL, '2025-10-16 16:33:57', '2025-10-16 16:33:57', NULL, NULL),
(46, 'Jan Juan', 'janjuan123', 'janjuan123', 'janjuan@gmail.com', '09472726438', 'Guiguinto, Bulacan', 'Customer', NULL, '2025-10-16 16:46:18', '2025-10-16 16:46:18', NULL, NULL),
(47, 'Ian Valderama', 'IanV123', 'IanV123', 'ianvalderama@gmail.com', NULL, NULL, 'Customer', NULL, '2025-10-17 06:29:24', '2025-10-17 06:29:24', NULL, NULL),
(48, 'Juan Dela Cruz', 'JuanDC12', 'JuanDC12', 'juandelacruz11@gmail.com', '09472726438', 'Guiguinto, Bulacan', 'Customer', NULL, '2025-10-17 15:32:45', '2025-10-17 15:32:45', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
