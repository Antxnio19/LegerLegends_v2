-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 02, 2024 at 01:02 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ledgerledgends`
--

-- --------------------------------------------------------

--
-- Table structure for table `expiredpasswords`
--

DROP TABLE IF EXISTS `expiredpasswords`;
CREATE TABLE IF NOT EXISTS `expiredpasswords` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `Username` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `ExpiredPassword` varchar(255) NOT NULL,
  `DateExpired` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `UserId` (`UserId`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expiredpasswords`
--

INSERT INTO `expiredpasswords` (`Id`, `UserId`, `Username`, `FirstName`, `LastName`, `ExpiredPassword`, `DateExpired`) VALUES
(1, 1, 'johndoe', 'John', 'Doe', '****321', '2024-09-01 00:00:00'),
(2, 2, 'janedoe', 'Jane', 'Doe', 'abcd1234', '2024-08-15 00:00:00'),
(3, 3, 'alexsmith', 'Alex', 'Smith', 'pass1234', '2024-09-10 00:00:00'),
(4, 4, 'maryjohnson', 'Mary', 'Johnson', 'mypassword', '2024-09-20 00:00:00'),
(5, 5, 'davidbrown', 'David', 'Brown', 'password123', '2024-09-25 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `table1`
--

DROP TABLE IF EXISTS `table1`;
CREATE TABLE IF NOT EXISTS `table1` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `UserTypeId` char(10) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `EmailAddress` varchar(100) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `SecurityQuestions` char(10) DEFAULT NULL,
  `SecurityAnswers` char(10) DEFAULT NULL,
  `FailedAttempts` int DEFAULT '0',
  `LockoutUntil` datetime DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ModifiedDate` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(50) DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT '0',
  `Approved` tinyint(1) DEFAULT '0',
  `Position` varchar(50) DEFAULT NULL,
  `ExpiryDuration` int DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `table1`
--

INSERT INTO `table1` (`Id`, `UserTypeId`, `Username`, `Password`, `EmailAddress`, `DateOfBirth`, `FirstName`, `LastName`, `Address`, `SecurityQuestions`, `SecurityAnswers`, `FailedAttempts`, `LockoutUntil`, `CreatedDate`, `ModifiedDate`, `ModifiedBy`, `IsActive`, `Approved`, `Position`, `ExpiryDuration`) VALUES
(1, '1', 'johndoe', 'password1', 'johndoe@example.com', '1990-01-01', 'John', 'Doe', '123 Main St', NULL, NULL, 0, NULL, '2024-10-02 07:38:44', '2024-10-02 08:45:39', NULL, 0, 0, 'user', 30),
(2, '1', 'janedoe', 'password2', 'janedoe@example.com', '1992-02-02', 'Jane', 'Doe', '124 Main St', NULL, NULL, 0, NULL, '2024-10-02 07:38:44', '2024-10-02 08:29:00', NULL, 0, 0, NULL, NULL),
(3, '1', 'alexsmith', 'password3', 'alexsmith@example.com', '1994-03-03', 'Alex', 'Smith', '125 Main St', NULL, NULL, 0, NULL, '2024-10-02 07:38:44', '2024-10-02 08:17:57', NULL, 0, 0, NULL, NULL),
(4, '1', 'maryjohnson', 'password4', 'maryjohnson@example.com', '1996-04-04', 'Mary', 'Johnson', '126 Main St', NULL, NULL, 0, NULL, '2024-10-02 07:38:44', '2024-10-02 07:38:44', NULL, 0, 0, NULL, NULL),
(5, '1', 'davidbrown', 'password5', 'davidbrown@example.com', '1998-05-05', 'David', 'Brown', '127 Main St', NULL, NULL, 0, NULL, '2024-10-02 07:38:44', '2024-10-02 07:38:44', NULL, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usersuspensions`
--

DROP TABLE IF EXISTS `usersuspensions`;
CREATE TABLE IF NOT EXISTS `usersuspensions` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `UserId` (`UserId`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usersuspensions`
--

INSERT INTO `usersuspensions` (`Id`, `UserId`, `StartDate`, `EndDate`) VALUES
(1, 3, '2024-10-02', '2024-10-05'),
(2, 1, '2024-10-24', '2024-11-02');

-- --------------------------------------------------------

--
-- Table structure for table `usertypetable`
--

DROP TABLE IF EXISTS `usertypetable`;
CREATE TABLE IF NOT EXISTS `usertypetable` (
  `UserTypeId` varchar(10) NOT NULL,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`UserTypeId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roster`
--

DROP TABLE IF EXISTS `user_roster`;
CREATE TABLE IF NOT EXISTS `user_roster` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `IsActive` tinyint(1) DEFAULT '1',
  `IsApproved` tinyint(1) DEFAULT '0',
  `SuspensionStartDate` date DEFAULT NULL,
  `SuspensionEndDate` date DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_roster`
--

INSERT INTO `user_roster` (`Id`, `FirstName`, `LastName`, `Username`, `Password`, `IsActive`, `IsApproved`, `SuspensionStartDate`, `SuspensionEndDate`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 'John', 'Doe', 'johndoe', 'password123', 1, 1, NULL, NULL, '2024-10-02 12:30:47', '2024-10-02 12:30:47'),
(2, 'Jane', 'Smith', 'janesmith', 'password456', 1, 0, NULL, NULL, '2024-10-02 12:30:47', '2024-10-02 12:30:47');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
