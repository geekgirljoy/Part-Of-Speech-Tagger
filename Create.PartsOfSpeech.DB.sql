SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `PartsOfSpeechTagger`
--
CREATE DATABASE IF NOT EXISTS `PartsOfSpeechTagger` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `PartsOfSpeechTagger`;

-- --------------------------------------------------------

--
-- Table structure for table `Dictionary`
--

CREATE TABLE `Dictionary` (
  `ID` int(11) NOT NULL,
  `Word` varchar(100) NOT NULL,
  `Tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Trigrams`
--

CREATE TABLE `Trigrams` (
  `ID` int(11) NOT NULL,
  `Count` int(11) NOT NULL,
  `Word_A` varchar(200) NOT NULL,
  `Word_B` varchar(200) NOT NULL,
  `Word_C` varchar(200) NOT NULL,
  `Tag_A` varchar(50) NOT NULL,
  `Tag_B` varchar(50) NOT NULL,
  `Tag_C` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Sources`
--

CREATE TABLE `Sources` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Genre` varchar(255) NOT NULL,
  `Keywords` text NOT NULL,
  `Description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `Dictionary`
--
ALTER TABLE `Dictionary`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Word` (`Word`);

--
-- Indexes for table `Trigrams`
--
ALTER TABLE `Trigrams`
  ADD PRIMARY KEY (`ID`);
  
--
-- Indexes for table `Sources`
--
ALTER TABLE `Sources`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Word` (`Name`);
  

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Dictionary`
--
ALTER TABLE `Dictionary`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Trigrams`
--
ALTER TABLE `Trigrams`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Sources`
--
ALTER TABLE `Sources`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
