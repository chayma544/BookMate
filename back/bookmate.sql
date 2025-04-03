-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2024 at 02:00 PM
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
DROP DATABASE IF EXISTS `bookmate`;
CREATE DATABASE `bookmate` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bookmate`;

-- --------------------------------------------------------
-- Parent tables must be created first
-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `age` int(11) NOT NULL DEFAULT 18,
  `address` varchar(50) NOT NULL,
  `user_swap_score` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `administrateur`
--

CREATE TABLE `administrateur` (
  `admin_id` int(50) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `age` int(100) NOT NULL DEFAULT 18,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Child tables with foreign keys
-- --------------------------------------------------------

--
-- Table structure for table `livre`
--

CREATE TABLE `livre` (
  `book_id` int(50) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `author_name` varchar(50) NOT NULL,
  `language` varchar(50) NOT NULL DEFAULT 'English',
  `genre` varchar(50) NOT NULL,
  `release_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'good',
  `dateAjout` date NOT NULL DEFAULT CURRENT_DATE(),
  `availability` varchar(50) NOT NULL DEFAULT 'available',
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`book_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `user_id` int(11) NOT NULL,
  `admin_id` int(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`,`admin_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Add constraints AFTER all tables exist
-- --------------------------------------------------------

--
-- Constraints for table `livre`
--
ALTER TABLE `livre`
ADD CONSTRAINT `livre_ibfk_1` 
FOREIGN KEY (`user_id`) 
REFERENCES `user` (`user_id`)
ON DELETE SET NULL
ON UPDATE CASCADE;

--
-- Constraints for table `profil`
--
ALTER TABLE `profil`
ADD CONSTRAINT `profil_ibfk_1` 
FOREIGN KEY (`user_id`) 
REFERENCES `user` (`user_id`)
ON DELETE CASCADE
ON UPDATE CASCADE,

ADD CONSTRAINT `profil_ibfk_2` 
FOREIGN KEY (`admin_id`) 
REFERENCES `administrateur` (`admin_id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- --------------------------------------------------------
-- Sample data for testing
-- --------------------------------------------------------

INSERT INTO `user` (`FirstName`, `LastName`, `age`, `address`) VALUES
('John', 'Doe', 30, '123 Main St'),
('Jane', 'Smith', 25, '456 Oak Ave');

INSERT INTO `administrateur` (`FirstName`, `LastName`) VALUES
('Admin', 'User');

INSERT INTO `livre` (`title`, `author_name`, `genre`, `user_id`) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'Classic', 1),
('1984', 'George Orwell', 'Dystopian', 2);

INSERT INTO `profil` (`user_id`, `admin_id`, `password`, `email`) VALUES
(1, 1, '$2y$10$hashedvalue1', 'john@example.com'),
(2, 1, '$2y$10$hashedvalue2', 'jane@example.com');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;