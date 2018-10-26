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
  `Hash` varchar(33) NOT NULL,
  `Tag` varchar(8) NOT NULL,
  `Count` int(11) NOT NULL,
  `Definition` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Trigrams`
--

CREATE TABLE `Trigrams` (
  `Hash` varchar(33) NOT NULL,
  `Count` int(11) NOT NULL,
  `Word_A` varchar(100) NOT NULL,
  `Word_B` varchar(100) NOT NULL,
  `Word_C` varchar(100) NOT NULL,
  `Tag_A` varchar(33) NOT NULL,
  `Tag_B` varchar(33) NOT NULL,
  `Tag_C` varchar(33) NOT NULL,
  `Sources` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- --------------------------------------------------------

--
-- Table structure for table `Words`
--

CREATE TABLE `Words` (
  `Hash` varchar(33) NOT NULL,
  `Word` varchar(100) NOT NULL,
  `Count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Tags`
--
ALTER TABLE `Tags`
  ADD PRIMARY KEY (`Hash`),
  ADD UNIQUE KEY `Hash` (`Hash`);

--
-- Indexes for table `Trigrams`
--
ALTER TABLE `Trigrams`
  ADD PRIMARY KEY (`Hash`),
  ADD UNIQUE KEY `Hash` (`Hash`);


--
-- Indexes for table `Words`
--
ALTER TABLE `Words`
  ADD PRIMARY KEY (`Hash`),
  ADD UNIQUE KEY `Hash` (`Hash`);
