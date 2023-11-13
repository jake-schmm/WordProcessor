-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2023 at 12:54 AM
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
(13, 'New Document', '{\"ops\":[{\"insert\":\"\\n\"}]}', 'Tree', '2023-10-14 22:56:55'),
(14, 'New Document22', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Tree', '2023-10-14 23:42:05'),
(15, 'New Document24', '{\"ops\":[{\"insert\":\"Example\\n\"},{\"attributes\":{\"italic\":true,\"bold\":true},\"insert\":\"HELLO\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\"},\"insert\":\"This is a test\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\"},\"insert\":\"I am great\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\",\"color\":\"#ffc266\"},\"insert\":\"blablablax\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"}]}', 'Tree', '2023-10-22 22:40:20'),
(16, 'New Document26', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Tree', '2023-10-14 23:53:24'),
(21, 'New Document5', '{\"ops\":[{\"insert\":\"Example2\\n\"}]}', 'Tree', '2023-10-22 22:31:38'),
(22, 'New Document245', '{\"ops\":[{\"insert\":\"Example\\n\"},{\"attributes\":{\"italic\":true,\"bold\":true},\"insert\":\"HELLO\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\"},\"insert\":\"This is a test\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\"},\"insert\":\"I am great\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\",\"color\":\"#ffc266\"},\"insert\":\"blablablas\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"}]}', 'Tree', '2023-10-22 22:32:01'),
(26, 'doc new', '{\"ops\":[{\"insert\":\"This is a new dcoument\\n\"}]}', 'Tree', '2023-10-24 18:50:41'),
(27, 'NEW', '{\"ops\":[{\"insert\":\"A\\n\"}]}', 'Tree', '2023-10-24 18:51:05'),
(28, 'This is new', '{\"ops\":[{\"insert\":\"Example2\\n\"}]}', 'Tree', '2023-10-24 18:55:40'),
(30, 'New Document', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Tree', '2023-10-27 11:13:23'),
(31, 'New Document', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Tree', '2023-10-27 11:15:47'),
(32, 'New Document', '{\"ops\":[{\"insert\":\"e\\n\"}]}', 'Tree', '2023-10-27 14:26:21'),
(33, 'New Document', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Tree', '2023-10-27 11:16:26'),
(35, 'EXAMPLE', '{\"ops\":[{\"insert\":\"EXAMPLE23\\n\"}]}', 'Tree', '2023-10-27 13:36:16'),
(36, 'EXAMPLE2', '{\"ops\":[{\"insert\":\"EXAMPLE1345\\n\"}]}', 'Tree', '2023-10-27 14:26:37'),
(37, 'New Document', '{\"ops\":[{\"insert\":\"Example2345\\n\"}]}', 'Tree', '2023-10-27 14:46:55'),
(38, '1', '{\"ops\":[{\"insert\":\"Example25\\n\"}]}', 'Tree', '2023-10-27 14:47:13'),
(40, '12', '{\"ops\":[{\"insert\":\"Example255\\n\"}]}', 'Tree', '2023-10-27 14:47:23'),
(42, 'New Document', '{\"ops\":[{\"insert\":\"EXAMPLE\\n\"}]}', 'Example', '2023-10-27 15:48:30'),
(43, 'New Document', '{\"ops\":[{\"insert\":\"\\n\"}]}', 'Example', '2023-10-27 15:54:14'),
(44, 'New Document', '{\"ops\":[{\"insert\":\"\\n\"}]}', 'Example', '2023-10-27 15:54:22'),
(45, 'New Document', '{\"ops\":[{\"insert\":\"\\n\"}]}', 'Example', '2023-10-27 15:54:38'),
(46, 'New Document2', '{\"ops\":[{\"insert\":\"hello\\n\"}]}', 'Example', '2023-10-27 15:57:51'),
(47, 'New Document3', '{\"ops\":[{\"insert\":\"Examples\\n\"}]}', 'Example', '2023-10-27 21:46:06'),
(49, 'New Document', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Example', '2023-10-27 17:49:02'),
(50, 'New Document', '{\"ops\":[{\"insert\":\"Example\\n\"}]}', 'Example', '2023-10-27 17:49:41'),
(51, 'New Document', '{\"ops\":[{\"insert\":\"s\\n\"}]}', 'Example', '2023-10-27 21:25:40'),
(52, 'New Document', '{\"ops\":[{\"insert\":\"Hello\\n\"}]}', 'Example', '2023-10-27 19:29:34'),
(54, 'This is a new document', '{\"ops\":[{\"insert\":\"Examples1\\n\"},{\"attributes\":{\"size\":\"huge\"},\"insert\":\"Example\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\",\"color\":\"#e60000\"},\"insert\":\"Test\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"italic\":true,\"size\":\"huge\",\"color\":\"#e60000\",\"bold\":true},\"insert\":\"hello\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"small\"},\"insert\":\"Test\"},{\"insert\":\"\\nTest\\n\"},{\"attributes\":{\"size\":\"large\"},\"insert\":\"Test\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\",\"color\":\"#ffc266\"},\"insert\":\"Test\"},{\"insert\":\"Testing. Testing.\\nExample\"},{\"attributes\":{\"list\":\"ordered\"},\"insert\":\"\\n\"},{\"insert\":\"Example2\"},{\"attributes\":{\"list\":\"ordered\"},\"insert\":\"\\n\"},{\"attributes\":{\"strike\":true},\"insert\":\"Example\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"bold\":true},\"insert\":\"Example\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"serif\",\"bold\":true},\"insert\":\"Hello\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"attributes\":{\"align\":\"center\"},\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"attributes\":{\"align\":\"right\"},\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"attributes\":{\"align\":\"justify\"},\"insert\":\"\\n\"},{\"insert\":\"\\n\"}]}', 'Tree', '2023-11-12 23:50:57'),
(56, 'New Story 2', '{\"ops\":[{\"attributes\":{\"size\":\"huge\"},\"insert\":\"Heading\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"italic\":true,\"bold\":true,\"size\":\"huge\",\"color\":\"#e60000\",\"font\":\"monospace\",\"link\":\"www.youtube.com\"},\"insert\":\"Once upon a time...\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"italic\":true,\"size\":\"huge\",\"color\":\"#e60000\",\"font\":\"monospace\",\"bold\":true},\"insert\":\"There was a time\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"}]}', 'Example2', '2023-10-28 17:19:30'),
(57, 'New Document', '{\"ops\":[{\"insert\":\"Testing. Testing.Testing.\\n\"}]}', 'Tree', '2023-11-06 14:33:53'),
(58, 'New Document', '{\"ops\":[{\"insert\":\"\\n\"}]}', 'Tree', '2023-11-06 18:13:51'),
(59, 'New Document', '{\"ops\":[{\"attributes\":{\"size\":\"huge\",\"font\":\"serif\"},\"insert\":\"Example                      Example\"},{\"insert\":\"\\n\\t\\t                 \"},{\"attributes\":{\"size\":\"huge\"},\"insert\":\"Example\"},{\"insert\":\"\\n\"}]}', 'Tree', '2023-11-06 19:10:14'),
(60, 'New Document', '{\"ops\":[{\"insert\":\"Example \\t\\t\\t\\tExample\\n\"},{\"attributes\":{\"underline\":true,\"italic\":true,\"bold\":true,\"size\":\"huge\",\"link\":\"http://www.youtube.com\"},\"insert\":\"EXAMPLE\"},{\"attributes\":{\"align\":\"center\",\"indent\":8,\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"insert\":\"\\n\"}]}', 'Tree', '2023-11-06 20:40:05'),
(61, 'New Document5', '{\"ops\":[{\"insert\":\"Example \\t\\t\\t\\tExample\\n\"},{\"attributes\":{\"underline\":true,\"italic\":true,\"bold\":true,\"size\":\"huge\",\"link\":\"http://www.youtube.com\"},\"insert\":\"EXAMPLE\"},{\"attributes\":{\"align\":\"center\",\"indent\":8,\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"insert\":\"\\n\"}]}', 'Tree', '2023-11-06 20:41:31'),
(62, 'Test', '{\"ops\":[{\"insert\":\"Examples1\\n\"},{\"attributes\":{\"size\":\"huge\"},\"insert\":\"Example\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\",\"color\":\"#e60000\"},\"insert\":\"Test\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"italic\":true,\"size\":\"huge\",\"color\":\"#e60000\",\"bold\":true},\"insert\":\"hello\"},{\"attributes\":{\"list\":\"bullet\"},\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"small\"},\"insert\":\"Test\"},{\"insert\":\"\\nTest\\n\"},{\"attributes\":{\"size\":\"large\"},\"insert\":\"Test\"},{\"insert\":\"\\n\"},{\"attributes\":{\"size\":\"huge\",\"color\":\"#ffc266\"},\"insert\":\"Test\"},{\"insert\":\"Testing. Testing.\\nExample\"},{\"attributes\":{\"list\":\"ordered\"},\"insert\":\"\\n\"},{\"insert\":\"Example2\"},{\"attributes\":{\"list\":\"ordered\"},\"insert\":\"\\n\"},{\"attributes\":{\"strike\":true},\"insert\":\"Example\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"bold\":true},\"insert\":\"Example\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"serif\",\"bold\":true},\"insert\":\"Hello\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"attributes\":{\"align\":\"center\"},\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"attributes\":{\"align\":\"right\"},\"insert\":\"\\n\"},{\"attributes\":{\"underline\":true,\"strike\":true,\"italic\":true,\"font\":\"monospace\",\"bold\":true},\"insert\":\"Hello\"},{\"attributes\":{\"align\":\"justify\"},\"insert\":\"\\n\"},{\"insert\":\"\\n\"}]}', 'Tree', '2023-11-12 23:51:04');

-- --------------------------------------------------------

--
-- Table structure for table `document_visibility`
--

CREATE TABLE `document_visibility` (
  `document_id` int(11) NOT NULL,
  `visibility_level_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_visibility`
--

INSERT INTO `document_visibility` (`document_id`, `visibility_level_id`) VALUES
(42, 1),
(43, 1),
(45, 1),
(46, 1),
(47, 1),
(49, 1),
(50, 1),
(50, 3),
(51, 1),
(51, 3),
(52, 1),
(52, 3),
(54, 1),
(54, 3),
(56, 1),
(56, 3),
(61, 1);

-- --------------------------------------------------------

--
-- Table structure for table `friends_list`
--

CREATE TABLE `friends_list` (
  `user_username` varchar(50) NOT NULL,
  `friend_username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends_list`
--

INSERT INTO `friends_list` (`user_username`, `friend_username`) VALUES
('Example', 'Example2'),
('Example', 'Tree'),
('Example2', 'Example'),
('Example2', 'Tree'),
('Tree', 'Example'),
('Tree', 'Example2');

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `request_id` int(11) NOT NULL,
  `sender_username` varchar(50) NOT NULL,
  `receiver_username` varchar(50) NOT NULL,
  `status` enum('pending','accepted') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`request_id`, `sender_username`, `receiver_username`, `status`) VALUES
(2, 'Tree', 'Example', 'accepted'),
(4, 'Example', 'Example2', 'accepted'),
(7, 'Tree', 'Example2', 'accepted');

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
(1, 'Tree', 'Admin'),
(7, 'Try', 'e'),
(9, 'Example', '5'),
(10, 'Example2', '5');

-- --------------------------------------------------------

--
-- Table structure for table `visibility_levels`
--

CREATE TABLE `visibility_levels` (
  `id` int(11) NOT NULL,
  `visibility_level` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visibility_levels`
--

INSERT INTO `visibility_levels` (`id`, `visibility_level`) VALUES
(1, 'public'),
(2, 'friends'),
(3, 'myself');

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
-- Indexes for table `document_visibility`
--
ALTER TABLE `document_visibility`
  ADD PRIMARY KEY (`document_id`,`visibility_level_id`),
  ADD KEY `visibility_level_id` (`visibility_level_id`);

--
-- Indexes for table `friends_list`
--
ALTER TABLE `friends_list`
  ADD PRIMARY KEY (`user_username`,`friend_username`),
  ADD KEY `friend_username` (`friend_username`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `sender_username` (`sender_username`,`receiver_username`),
  ADD KEY `receiver_username` (`receiver_username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- Indexes for table `visibility_levels`
--
ALTER TABLE `visibility_levels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `visibility_levels`
--
ALTER TABLE `visibility_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`username`);

--
-- Constraints for table `document_visibility`
--
ALTER TABLE `document_visibility`
  ADD CONSTRAINT `document_visibility_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `document` (`id`),
  ADD CONSTRAINT `document_visibility_ibfk_2` FOREIGN KEY (`visibility_level_id`) REFERENCES `visibility_levels` (`id`);

--
-- Constraints for table `friends_list`
--
ALTER TABLE `friends_list`
  ADD CONSTRAINT `friends_list_ibfk_1` FOREIGN KEY (`user_username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `friends_list_ibfk_2` FOREIGN KEY (`friend_username`) REFERENCES `users` (`username`);

--
-- Constraints for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD CONSTRAINT `friend_requests_ibfk_1` FOREIGN KEY (`sender_username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `friend_requests_ibfk_2` FOREIGN KEY (`receiver_username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
