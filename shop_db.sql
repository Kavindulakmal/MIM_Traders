-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 09:46 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(2, 'admin1', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(16, 2, 3, 'Breeze 3 in 1 Brilliant white', 2242, 1, 'ab4b6c1cd70381ea7e978f6e2c057a14056411f7.png'),
(17, 2, 4, 'ASHOK LEYLAND Silent 400 kVA', 2500000, 1, 'choose-image.jpg'),
(24, 1, 6, 'PVC Pipes', 550, 2, 'p3.jpg'),
(25, 1, 14, 'Kansai Paints WonderWood Water Base', 1050, 2, '10.jpg'),
(26, 1, 16, 'Kansai Excel Mica Marble', 1128, 10, '4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `total_product` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `placed_on` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(1, 1, 'kamal', 'kamal@gmail.com', '0112345678', 'hello'),
(2, 1, 'akila', 'akila@gmail.com', '0770481203', 'good');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(5, 1, 'saman kumara', '0112345678', 'saman@gmail.com', 'credit card', ' no 21, new kandy road, kaduwela, western, Sri Lanka - 10000', 'Breeze 3 in 1 Brilliant white (2242 x 1) - Super Seamless Diamond Disc-(NPDD003)/(NPDD004) (1140 x 1) - ASHOK LEYLAND Silent 400 kVA (2500000 x 1) - ', 2503382, '2025-03-20', 'completed'),
(6, 1, 'Mahela Udawatta', '0789456123', 'mahela@yahoo.com', 'paypal', ' No 456/9, Ds Senanayake street , Kandy, Central, Sri Lanka - 20000', 'Super Seamless Diamond Disc-(NPDD003)/(NPDD004) (1140 x 1) - Breeze 3 in 1 Brilliant white (2242 x 1) - ASHOK LEYLAND Silent 400 kVA (2500000 x 1) - Garden Hose (2280 x 1) - PVC Pipes (550 x 4) - Brush Master Black (850 x 1) - ', 2508712, '2025-03-20', 'completed'),
(7, 1, 'shahrukh khan', '0774005123', 'shahrukh@yahoo.com', 'credit card', ' Raja Mawatha , Randenigala, Kandy, Central, Sri Lanka - 30000', 'ESTWING Hammer (7500 x 1) - ASHOK LEYLAND Silent 400 kVA (2250000 x 1) - ', 2031750, '2025-03-22', 'shipped');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(10) NOT NULL,
  `image_01` varchar(100) NOT NULL,
  `image_02` varchar(100) NOT NULL,
  `image_03` varchar(100) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `discount` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `image_01`, `image_02`, `image_03`, `category`, `discount`) VALUES
(2, 'Super Seamless Diamond Disc-(NPDD003)/(NPDD004)', 'Use for : Granite, Marble, Tile & Concrete Cutting Segment Size : 08mm', 1140, 'cd56a62bfaa37fabb8bfbe150512b703ea9fb05e.png', 'cd56a62bfaa37fabb8bfbe150512b703ea9fb05e.png', 'cd56a62bfaa37fabb8bfbe150512b703ea9fb05e.png', NULL, 0),
(3, 'Breeze 3 in 1 Brilliant white', 'Here&#039;s where simple meets sublime. With uncomplicated shapes and all-season color, this textured porcelain dinnerware brings fresh style to the table all year.', 2250, 'ab4b6c1cd70381ea7e978f6e2c057a14056411f7.png', 'ab4b6c1cd70381ea7e978f6e2c057a14056411f7.png', 'ab4b6c1cd70381ea7e978f6e2c057a14056411f7.png', NULL, 5),
(4, 'ASHOK LEYLAND Silent 400 kVA', 'ASHOK LEYLAND Silent 400 kVA Three Phase 990 L Diesel Generators', 2500000, 'choose-image.jpg', 'choose-image.jpg', 'choose-image.jpg', NULL, 10),
(5, 'Garden Hose', 'Anton&#39;s Garden Hose is a versatile and durable solution for all your watering needs. Engineered with high thickness and crafted from original PVC material', 2280, 'a1.jpg', 'a2.jpg', 'a3.jpg', NULL, 0),
(6, 'PVC Pipes', 'Anton&#39;s PVC Pipes in the 20-32mm range', 550, 'p3.jpg', 'p2.jpg', 'p1.jpg', 'plumbing', 0),
(8, 'ESTWING Hammer', 'RIP CLAW VERSATILITY – Estwing hammers are the quintessential multitool for a variety of tasks. From effortlessly pulling nails with their finely crafted rip claw to expertly prying apart stubborn boards and undertaking precision demolition work', 7500, '718GmjiwbAL._AC_SX569_.jpg', '61+jyU8C1yL._AC_SX569_.jpg', '81Yim29R2UL._AC_SX569_.jpg', 'tools', 0),
(9, 'Hard Hat Clip Collection - Marker & Carpenter&#39;s Pencil - Green', 'About this item\r\nReliability: This clip is built to withstand the toughest conditions regardless of how fast or hard you are going.\r\nCompatibility: No proprietary hard hat or hard hat system needed.\r\nCompact and Lightweight: Minimal weight for maximum utility.\r\nDurable Materials: Made from PETG. Heat resistant up to 194-230 °F\r\nUSA Made: Proudly designed, manufactured, and packaged in New Hampshire.', 8650, '51uqg+TAYEL._AC_SX569_.jpg', '51iLS21H-rL._AC_SX569_.jpg', '51m7001AuNL._AC_SX569_.jpg', 'tools', 0),
(12, 'S-Lon PVC Conduit Pipe', 'White color\r\nManufactured in compliance with international standards.\r\nStrong and durable\r\nFire resistant and built with flexible material that doesn’t dent or break during installation\r\nWithstands to all weather conditions', 300, 'conduit-pipe-16157807068384.jpg', 'Screenshot 2025-03-22 143946.png', '01_6.jpg', 'plumbing', 2),
(13, 'S-Lon PVC Bend 45°', 'Unique combination of properties:\r\nToughness\r\nStiffness\r\nHigh tensile and hoop strength\r\nExcellent resistance to creep', 158, 'drainage-fittings45-16152807579752.jpg', 'drainage-fittings45-16152807579752.jpg', 'drainage-fittings45-16152807579752.jpg', 'plumbing', 1),
(14, 'Kansai Paints WonderWood Water Base', 'WonderWood Water Base Int. &amp; Ext. – Top Coat', 1050, '10.jpg', '10.jpg', '10.jpg', 'paints', 0),
(15, 'Kansai Wall Filler', 'Kansai Paints-Kansai Wall Filler 1L  600', 600, 'Kansai-Wall-Filler.jpeg', 'Kansai-Wall-Filler.jpeg', 'Kansai-Wall-Filler.jpeg', 'paints', 6),
(16, 'Kansai Excel Mica Marble', 'Ex. Emulsion – Excel Mica Marble 2L  1200', 1200, '4.jpg', '4.jpg', '4.jpg', 'paints', 6),
(17, 'Kansai Excel Anti Peel', 'Kansai Paints Ex. Emulsion – Excel Anti Peel 2L', 1600, '7.jpg', '7.jpg', '7.jpg', 'paints', 6),
(18, 'Kansai Metal Prime', 'Kansai Paints Anticorrosive – Metal Prime 2L', 2200, '5.jpg', '5.jpg', '5.jpg', 'paints', 6),
(19, 'Tokyo Super Blended Cement', 'Appropriate for concrete under normal and aggressive environments (Marine, Marshy Lands, Sulphate Soils. Etc…)\r\nCan be used for general construction\r\nRecommend for High Performance concrete, mass concretes and as a low heat cement\r\nAvailable in 50 Kg packs &amp; bulk carrier\r\nHighest 100 Day Strength\r\n“Tokyo Super +” Blended Hydraulic cement is a blended cement manufactured to conform to Sri Lanka Standard Specification SLS 1247:2008', 2900, '167_2.jpg', '167_2.jpg', '167_2.jpg', 'building', 0),
(20, 'Portland-Composite Cement', 'INSEE Sanstha Manufactured Sand (M-Sand) complying with BS EN 12620:2013\r\nNatural Sand, Quarry Sand and Offshore Sand complying with BS EN 12620:2013', 2900, 'pic-202310041681070872_43780_25-642.png', 'pic-202310041681070872_43780_25-642.png', 'pic-994-EN-202.jpg', 'building', 2),
(21, 'Mahaweli Marine Plus cement', 'INSEE Mahaweli Marine Plus is a high strength Portland Composite Cement of either Ground Granulated Blast Furnace Slag or fly ash variants available for the first time in the local retail market.', 2900, 'pic-202310041681070883_27304_25-643.png', '301160504_408617578033535_5854168169691601980_n.jpg', '173783153_107587838114405_5190833458221161779_n.jpg', 'building', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'user1', 'user1@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(2, 'kasun', 'kasun@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(6, 'rohan', 'rohan@abc.com', '$2y$10$N2Nccm03Z.5sTq35XIz6duOJAzGES8IfnKfdEN2xl2l'),
(7, 'akila', 'akila@abc.com', '064d20ccc70a061d349e91d2c79116fb8d62d6a6');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `pid`, `name`, `price`, `image`) VALUES
(8, 2, 9, 'Hard Hat Clip Collection - Marker & Carpenter&#39;s Pencil - Green', 8650, '51uqg+TAYEL._AC_SX569_.jpg'),
(10, 1, 3, 'Breeze 3 in 1 Brilliant white', 2138, 'ab4b6c1cd70381ea7e978f6e2c057a14056411f7.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
