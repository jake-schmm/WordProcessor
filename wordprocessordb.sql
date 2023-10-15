-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2023 at 06:16 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wordprocessordb`
--

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `delta` longtext DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `last_saved` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`id`, `title`, `delta`, `author`, `last_saved`) VALUES
(12, 'New Document', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Tree', '2023-10-14 22:16:24'),
(13, 'New Document', '{\"ops\":[{\"insert\":\"\\n\"}]}', 'Tree', '2023-10-14 22:56:55'),
(14, 'New Document22', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Tree', '2023-10-14 23:42:05'),
(15, 'New Document24', '{\"ops\":[{\"insert\":\"Example\\n\"},{\"attributes\":{\"italic\":true,\"bold\":true},\"insert\":\"HELLO\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\"},\"insert\":\"This is a test\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\"},\"insert\":\"I am great\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\",\"color\":\"#ffc266\"},\"insert\":\"blablabla\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"}]}', 'Tree', '2023-10-15 00:14:50'),
(16, 'New Document26', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Tree', '2023-10-14 23:53:24'),
(17, 'New Document24', '{\"ops\":[{\"insert\":\"Example22\\n\"}]}', 'Tree', '2023-10-15 00:04:15'),
(18, 'New Document', '{\"ops\":[{\"insert\":\"Blabla\\n\"}]}', 'Tree', '2023-10-15 00:02:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(1, 'Tree', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author` (`author`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
