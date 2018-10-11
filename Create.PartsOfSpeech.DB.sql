--
-- Database: `PartsOfSpeechTagger`
--
CREATE DATABASE IF NOT EXISTS `PartsOfSpeechTagger` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `PartsOfSpeechTagger`;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `Tags`
--

CREATE TABLE `Tags` (
  `ID` int(11) NOT NULL,
  `Tag` varchar(8) NOT NULL,
  `Definition` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Trigrams`
--

CREATE TABLE `Trigrams` (
  `ID` int(11) NOT NULL,
  `Count` int(11) NOT NULL,
  `Word_A` int(11) NOT NULL,
  `Word_B` int(11) NOT NULL,
  `Word_C` int(11) NOT NULL,
  `Tag_A` int(11) NOT NULL,
  `Tag_B` int(11) NOT NULL,
  `Tag_C` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Words`
--

CREATE TABLE `Words` (
  `ID` int(11) NOT NULL,
  `Word` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Tags`
--
ALTER TABLE `Tags`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Tag` (`Tag`);

--
-- Indexes for table `Trigrams`
--
ALTER TABLE `Trigrams`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Words`
--
ALTER TABLE `Words`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Word` (`Word`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Tags`
--
ALTER TABLE `Tags`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `Trigrams`
--
ALTER TABLE `Trigrams`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `Words`
--
ALTER TABLE `Words`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
