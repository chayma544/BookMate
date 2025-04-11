-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 09:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookmate`
--

-- --------------------------------------------------------

--
-- Table structure for table `livre`
--

CREATE TABLE `livre` (
  `book_id` int(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `author_name` varchar(50) NOT NULL,
  `language` varchar(50) NOT NULL DEFAULT 'English',
  `genre` varchar(50) NOT NULL,
  `release_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'good',
  `dateAjout` date NOT NULL DEFAULT curdate(),
  `availability` varchar(50) NOT NULL DEFAULT 'available',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `livre`
--

INSERT INTO `livre` (`book_id`, `title`, `author_name`, `language`, `genre`, `release_date`, `status`, `dateAjout`, `availability`, `user_id`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 'English', 'Classic', NULL, 'good', '2025-04-03', 'available', 1),
(2, '1984', 'George Orwell', 'English', 'Dystopian', NULL, 'good', '2025-04-03', 'available', 2);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `requester_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `type` enum('BORROW','EXCHANGE') DEFAULT NULL,
  `status` enum('PENDING','ACCEPTED','REJECTED') DEFAULT NULL,
  `datedeb` date DEFAULT NULL,
  `durée` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `age` int(11) NOT NULL DEFAULT 18,
  `address` varchar(50) NOT NULL,
  `user_swap_score` int(11) NOT NULL DEFAULT 0,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `FirstName`, `LastName`, `age`, `address`, `user_swap_score`, `email`, `password`) VALUES
(1, 'John', 'Doe', 30, '123 Main St', 0, NULL, NULL),
(2, 'Jane', 'Smith', 25, '456 Oak Ave', 0, NULL, NULL),
(3, 'John', 'Doe', 25, '123 Main St', 0, NULL, NULL),
(4, 'John', 'Doe', 25, '123 Main St', 0, NULL, NULL),
(5, 'John', 'Doe', 25, '123 Main St', 0, NULL, NULL),
(6, 'John', 'Doe', 25, '123 Main St', 0, NULL, NULL),
(7, 'John', 'Doe', 25, '1234 Main St, SomeCity, Country', 0, NULL, NULL),
(8, 'John', 'Doe', 25, '1234 Main St, SomeCity, Country', 0, NULL, NULL),
(9, 'John', 'Doe', 25, '1234 Main St, SomeCity, Country', 0, NULL, NULL),
(10, 'John', 'Doe', 25, '1234 Main St, SomeCity, Country', 0, NULL, NULL),
(11, 'John', 'Doe', 25, '1234 Main St, SomeCity, Country', 0, NULL, NULL),
(12, 'John', 'Doe', 25, '1234 Main St, SomeCity, Country', 0, NULL, NULL),
(13, 'John', 'Doe', 25, '1234 Main St, SomeCity, Country', 0, NULL, NULL),
(14, 'John', 'Doe', 25, '1234 Main St, SomeCity, Country', 0, NULL, NULL),
(15, 'John', 'Doe', 25, '1234 Main St, SomeCity, Country', 0, NULL, NULL),
(16, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(17, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(18, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(19, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(20, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(21, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(22, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(23, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(24, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(25, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(26, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(27, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(28, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(29, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(30, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(31, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(32, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(33, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(34, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(35, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(36, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(37, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL),
(38, 'aaaa', 'bbbbb', 25, '123 Main St', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `livre`
--
ALTER TABLE `livre`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD KEY `requests_ibfk_1` (`requester_id`),
  ADD KEY `requests_ibfk_2` (`book_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`,`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `livre`
--
ALTER TABLE `livre`
  MODIFY `book_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `livre`
--
ALTER TABLE `livre`
  ADD CONSTRAINT `livre_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`requester_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `livre` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
