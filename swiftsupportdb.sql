-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 11:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `swiftsupportdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `reqmessages1`
--

CREATE TABLE `reqmessages1` (
  `id` int(11) NOT NULL,
  `author` varchar(100) NOT NULL,
  `dateSended` date NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Dumping data for table `reqmessages1`
--

INSERT INTO `reqmessages1` (`id`, `author`, `dateSended`, `content`) VALUES
(1, 'root@root.com', '2025-04-07', 'asfsadfsafas'),
(2, 'root@root.com', '2025-04-07', 'Skicka'),
(3, 'root@root.com', '2025-04-07', 'Test again'),
(4, 'root@root.com', '2025-04-07', 'Test again'),
(5, 'root@root.com', '2025-04-07', 'Test again test again'),
(6, 'root@root.com', '2025-04-07', 'Test again test again'),
(7, 'root@root.com', '2025-04-07', 'Test again test again'),
(8, 'root@root.com', '2025-04-07', 'Test send'),
(9, 'root@root.com', '2025-04-07', 'szfzxzxfxzfzsfsdfsddff'),
(10, 'root@root.com', '2025-04-07', 'tarfsarrsafd a'),
(11, 'root@root.com', '2025-04-07', 'qwerqrqwrqwr'),
(12, 'root@root.com', '2025-04-07', 'sdfsdfsdfdsf sdfsdfsd'),
(13, 'root@root.com', '2025-04-07', 'gfhgfghfhgf');

-- --------------------------------------------------------

--
-- Table structure for table `reqmessages8`
--

CREATE TABLE `reqmessages8` (
  `id` int(11) NOT NULL,
  `author` varchar(100) NOT NULL,
  `dateSended` date NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Dumping data for table `reqmessages8`
--

INSERT INTO `reqmessages8` (`id`, `author`, `dateSended`, `content`) VALUES
(2, 'user@user.com', '2025-04-07', 'y7i9y7iyutyuj'),
(3, 'user@user.com', '2025-04-07', 'wqoiuroiuqwdhkjhfjksdhkfj'),
(4, 'support@support.com', '2025-04-07', 'huasuodiosajdoiasjod');

-- --------------------------------------------------------

--
-- Table structure for table `reqmessages9`
--

CREATE TABLE `reqmessages9` (
  `id` int(11) NOT NULL,
  `author` varchar(100) NOT NULL,
  `dateSended` date NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Dumping data for table `reqmessages9`
--

INSERT INTO `reqmessages9` (`id`, `author`, `dateSended`, `content`) VALUES
(2, 'user@user.com', '2025-04-10', 'z'),
(3, 'support@support.com', '2025-04-10', 'sadasndsamdn');

-- --------------------------------------------------------

--
-- Table structure for table `reqmessages10`
--

CREATE TABLE `reqmessages10` (
  `id` int(11) NOT NULL,
  `author` varchar(100) NOT NULL,
  `dateSended` date NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Dumping data for table `reqmessages10`
--

INSERT INTO `reqmessages10` (`id`, `author`, `dateSended`, `content`) VALUES
(2, 'user@user.com', '2025-04-11', 'adfgsdfagadsfgadfgbdsfgbrabgfsadbjgbsfjdgbhjsdfkjghsfdjkvbjsfdvbhjfdasvfadsvsfdvsfdgsdfgvsdfvsdfgsdfgsdfgsdfgdsg'),
(3, 'support@support.com', '2025-04-11', 'ojsfdafj sdojfnsdnfdsfsdfdsfdsfsdfs'),
(4, 'support@support.com', '2025-04-11', 'sdfsdfsdfdsfdsfsdfsdf'),
(5, 'user@user.com', '2025-04-11', 'sdfsdfsdfdsfsdfsdfsdfsdfsdfsdfss');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `titel` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `creationdate` date NOT NULL,
  `lastupdate` date NOT NULL,
  `status` varchar(100) NOT NULL,
  `priority` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `assignedto` varchar(100) NOT NULL,
  `clientname` varchar(100) NOT NULL,
  `clientemail` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `phonenumber` varchar(30) NOT NULL,
  `comments` text NOT NULL,
  `imges` text NOT NULL,
  `history` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `titel`, `description`, `creationdate`, `lastupdate`, `status`, `priority`, `category`, `assignedto`, `clientname`, `clientemail`, `company`, `phonenumber`, `comments`, `imges`, `history`) VALUES
(1, 'test titel', 'asfsadfsafas', '2025-04-01', '2025-04-07', 'Closed', 'medium', 'invoicing', 'support@support.com', 'test name', 'root@root.com', 'test company', 'test number', '', '', ''),
(8, 'asdsadasda', 'y7i9y7iyutyuj', '2025-04-07', '2025-04-07', 'In Progress', 'medium', 'invoicing', 'support@support.com', 'asdsadasda sdadasd', 'user@user.com', '', '', '', '', ''),
(9, 'zsxcxzcxzcxzcxz', 'z', '2025-04-10', '2025-04-10', 'In Progress', 'low', 'techincalIssue', 'support@support.com', 'user', 'user@user.com', '', '', '', '', ''),
(10, 'asdsadsadsfdsafgdsfgvsdfvdfgsdfvsd', 'adfgsdfagadsfgadfgbdsfgbrabgfsadbjgbsfjdgbhjsdfkjghsfdjkvbjsfdvbhjfdasvfadsvsfdvsfdgsdfgvsdfvsdfgsdfgsdfgsdfgdsg', '2025-04-11', '2025-04-11', 'Closed', 'medium', 'invoicing', 'support@support.com', 'sfdfdgsdfg', 'user@user.com', 'sdfgsdfgsdfgsd', 'sadfgsfdgsdfgsdsdfgsdfgsdfgsdf', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `accesslevel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `accesslevel`) VALUES
(2, 'root@root.com', 'e99a18c428cb38d5f260853678922e03', 10),
(3, 'support@support.com', 'e99a18c428cb38d5f260853678922e03', 5),
(4, 'user@user.com', 'e99a18c428cb38d5f260853678922e03', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reqmessages1`
--
ALTER TABLE `reqmessages1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reqmessages8`
--
ALTER TABLE `reqmessages8`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reqmessages9`
--
ALTER TABLE `reqmessages9`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reqmessages10`
--
ALTER TABLE `reqmessages10`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reqmessages1`
--
ALTER TABLE `reqmessages1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reqmessages8`
--
ALTER TABLE `reqmessages8`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reqmessages9`
--
ALTER TABLE `reqmessages9`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reqmessages10`
--
ALTER TABLE `reqmessages10`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
