-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 05, 2018 at 09:32 AM
-- Server version: 10.1.23-MariaDB-9+deb9u1
-- PHP Version: 7.0.27-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `PartsOfSpeechTagger`
--

-- --------------------------------------------------------

--
-- Table structure for table `Trigrams`
--

CREATE TABLE `Trigrams` (
  `Hash` varchar(33) NOT NULL,
  `Hash_AB` varchar(33) NOT NULL,
  `Hash_BC` varchar(33) NOT NULL,
  `Hash_AC` varchar(33) NOT NULL,
  `Count` int(11) NOT NULL,
  `Word_A` varchar(100) NOT NULL,
  `Word_B` varchar(100) NOT NULL,
  `Word_C` varchar(100) NOT NULL,
  `Tag_A` varchar(33) NOT NULL,
  `Tag_B` varchar(33) NOT NULL,
  `Tag_C` varchar(33) NOT NULL,
  `Sources` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Trigrams`
--
ALTER TABLE `Trigrams`
  ADD PRIMARY KEY (`Hash`),
  ADD UNIQUE KEY `Hash` (`Hash`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
