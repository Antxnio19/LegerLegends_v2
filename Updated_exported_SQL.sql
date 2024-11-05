-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 24, 2024 at 03:02 PM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accounting_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Client_Accounts`
--

CREATE TABLE `Client_Accounts` (
  `id` int(11) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `account_description` text,
  `normal_side` enum('debit','credit') NOT NULL,
  `account_category` varchar(50) DEFAULT NULL,
  `account_subcategory` varchar(50) DEFAULT NULL,
  `initial_balance` decimal(10,2) DEFAULT '0.00',
  `balance` decimal(10,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `account_order` varchar(10) DEFAULT NULL,
  `statement` enum('IS','BS','RE') DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT '1',
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Client_Accounts`
--

INSERT INTO `Client_Accounts` (`id`, `account_name`, `account_number`, `account_description`, `normal_side`, `account_category`, `account_subcategory`, `initial_balance`, `balance`, `created_at`, `modified_at`, `ModifiedBy`, `user_id`, `account_order`, `statement`, `IsActive`, `comment`) VALUES
(1, 'Cash', '1001', 'Cash account for daily transactions', 'credit', 'Asset', 'Current Assets', '10010.00', '1610.00', '2024-10-23 09:46:20', '2024-10-23 17:25:52', 'bportier1024', 1, '01', 'BS', 0, 'Main cash account'),
(2, 'Accounts Receivable', '1002', 'Money owed by customers', 'debit', 'Asset', 'Current Assets', '0.00', '0.00', '2024-10-23 09:46:20', '2024-10-23 09:46:20', NULL, 1, '02', 'BS', 1, 'Customer payments pending'),
(3, 'Inventory', '1003', 'Goods available for sale', 'debit', 'Asset', 'Current Assets', '0.00', '75.50', '2024-10-23 09:46:20', '2024-10-23 09:46:20', NULL, 1, '03', 'BS', 1, 'Stock of products'),
(4, 'Accounts Payable', '2001', 'Money owed to suppliers', 'credit', 'Liability', 'Current Liabilities', '0.00', '0.00', '2024-10-23 09:46:20', '2024-10-23 09:46:20', NULL, 1, '04', 'BS', 1, 'Outstanding supplier invoices'),
(5, 'Owner Equity', '3001', 'Owner’s investment in the business', 'credit', 'Equity', 'Owner Equity', '0.00', '10000.00', '2024-10-23 09:46:20', '2024-10-23 09:46:20', NULL, 1, '05', 'RE', 1, 'Capital investment by owner');

--
-- Triggers `Client_Accounts`
--
DELIMITER $$
CREATE TRIGGER `event_update_client_accounts` AFTER UPDATE ON `Client_Accounts` FOR EACH ROW BEGIN
    DECLARE log_message_before TEXT DEFAULT '';
    DECLARE log_message_after TEXT DEFAULT '';
    DECLARE currentUser VARCHAR(250);
    
    SELECT Username INTO currentUser
    FROM table1
    WHERE table1.id = NEW.id;
    
    -- Check if the account_name has been updated
    IF OLD.account_name <> NEW.account_name THEN
        SET log_message_before = CONCAT(log_message_before, IF(log_message_before <> '', ' | ', ''), 'Account name changed from: ', OLD.account_name);
        SET log_message_after = CONCAT(log_message_after, IF(log_message_after <> '', ' | ', ''), 'New account name: ', NEW.account_name);
    END IF;

    -- Check if the account_number has been updated
    IF OLD.account_number <> NEW.account_number THEN
        SET log_message_before = CONCAT(log_message_before, IF(log_message_before <> '', ' | ', ''), 'Account number changed from: ', OLD.account_number);
        SET log_message_after = CONCAT(log_message_after, IF(log_message_after <> '', ' | ', ''), 'New account number: ', NEW.account_number);
    END IF;

    -- Check if the normal_side has been updated
    IF OLD.normal_side <> NEW.normal_side THEN
        SET log_message_before = CONCAT(log_message_before, IF(log_message_before <> '', ' | ', ''), 'Normal side changed from: ', OLD.normal_side);
        SET log_message_after = CONCAT(log_message_after, IF(log_message_after <> '', ' | ', ''), 'New normal side: ', NEW.normal_side);
    END IF;

    -- Check if the account_category has been updated
    IF OLD.account_category <> NEW.account_category THEN
        SET log_message_before = CONCAT(log_message_before, IF(log_message_before <> '', ' | ', ''), 'Account category changed from: ', OLD.account_category);
        SET log_message_after = CONCAT(log_message_after, IF(log_message_after <> '', ' | ', ''), 'New account category: ', NEW.account_category);
    END IF;

    -- Check if the account_subcategory has been updated
    IF OLD.account_subcategory <> NEW.account_subcategory THEN
        SET log_message_before = CONCAT(log_message_before, IF(log_message_before <> '', ' | ', ''), 'Account subcategory changed from: ', OLD.account_subcategory);
        SET log_message_after = CONCAT(log_message_after, IF(log_message_after <> '', ' | ', ''), 'New account subcategory: ', NEW.account_subcategory);
    END IF;

    -- Check if the initial_balance has been updated
    IF OLD.initial_balance <> NEW.initial_balance THEN
        SET log_message_before = CONCAT(log_message_before, IF(log_message_before <> '', ' | ', ''), 'Initial balance changed from: ', OLD.initial_balance);
        SET log_message_after = CONCAT(log_message_after, IF(log_message_after <> '', ' | ', ''), 'New initial balance: ', NEW.initial_balance);
    END IF;

    -- Check if the balance has been updated
    IF OLD.balance <> NEW.balance THEN
        IF NEW.balance > OLD.balance THEN
            SET log_message_before = CONCAT(log_message_before, IF(log_message_before <> '', ' | ', ''), 'Balance increased from: ', OLD.balance);
        ELSE
            SET log_message_before = CONCAT(log_message_before, IF(log_message_before <> '', ' | ', ''), 'Balance decreased from: ', OLD.balance);
        END IF;
        SET log_message_after = CONCAT(log_message_after, IF(log_message_after <> '', ' | ', ''), 'New balance: ', NEW.balance);
    END IF;

    -- Check if the comment has been updated
    IF OLD.comment <> NEW.comment THEN
        SET log_message_before = CONCAT(log_message_before, IF(log_message_before <> '', ' | ', ''), 'Comment changed from: ', OLD.comment);
        SET log_message_after = CONCAT(log_message_after, IF(log_message_after <> '', ' | ', ''), 'New comment: ', NEW.comment);
    END IF;

    -- Only log if there are any changes
    IF log_message_before <> '' THEN
        INSERT INTO user_eventlog
        (UserId, Username, UserAcctType, AcctAffected, BeforeAffected, AfterAffected, Event_Status, DateANDTime) 
        VALUES
        (
            NEW.Id,
            currentUser,
            NEW.account_name, 
            NEW.account_category, 
            log_message_before, 
            log_message_after, 
            'Client Account Updated', 
            NOW()
        );
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `expiredpasswords`
--

CREATE TABLE `expiredpasswords` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `ExpiredPassword` varchar(255) NOT NULL,
  `DateExpired` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expiredpasswords`
--

INSERT INTO `expiredpasswords` (`Id`, `UserId`, `Username`, `FirstName`, `LastName`, `ExpiredPassword`, `DateExpired`) VALUES
(1, 1, 'bportier1024', 'Brandon', 'Portier', '$2b$12$GnFWDjtNJTmUgUH8chxYW.8uhdPFv78I7jecrr2jk/zAIzlGCVBSG', '2024-09-01 00:00:00'),
(2, 2, 'jportier1024', 'Julia', 'Portier', '$2b$12$VMVhG/Ow6msZMrwTEITP5O6q79KZTqCBuzpuylK/QQLXkE8fC95t6', '2024-08-15 00:00:00'),
(3, 3, 'lportier1024', 'Luke', 'Portier', '$2b$12$GlYHUD/iXNv5vBuTKWGRo.eOfk0mpM.64ZyCcI7i6AwEMTK/SBa1K', '2024-09-10 00:00:00'),
(4, 4, 'jbond1024', 'James', 'Bond', '$2b$12$9VPaQz8auzNCzYqTCZla/O4aOU77GLZ8CW6Hm/oebqzwMkBGZYNr6', '2024-09-20 00:00:00'),
(5, 5, 'wthomas1024', 'Wendy', 'Thomas', '$2b$12$TA75wgmYqtKFJQ/X759zj.l29HUMYVpBNgVwoVFJoedX.Er7Ybe/a', '2024-09-25 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `Journal_Entries`
--

CREATE TABLE `Journal_Entries` (
  `id` int(11) NOT NULL,
  `client_account_id` int(11) NOT NULL,
  `account_type` varchar(50) NOT NULL,
  `account_debit` varchar(50) NOT NULL,
  `account_credit` varchar(50) NOT NULL,
  `debit` decimal(10,2) DEFAULT '0.00',
  `credit` decimal(10,2) DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(255) DEFAULT NULL,
  `IsApproved` tinyint(1) DEFAULT '1',
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Journal_Entries`
--

INSERT INTO `Journal_Entries` (`id`, `client_account_id`, `account_type`, `account_debit`, `account_credit`, `debit`, `credit`, `created_at`, `ModifiedBy`, `IsApproved`, `comment`) VALUES
(1, 1, 'Expense', '', '', '150.00', '150.00', '2023-09-15 10:30:00', 'John Doe', 1, 'Approved'),
(2, 2, 'Revenue', '', '', '500.00', '500.00', '2023-09-16 11:00:00', 'Jane Smith', 1, 'Payment for consulting'),
(3, 3, 'Expense', '', '', '75.50', '75.00', '2023-09-17 12:15:00', 'Alex Johnson', 1, 'Monthly electricity bill'),
(4, 1, 'Asset', '', '', '1200.00', '1200.00', '2023-09-18 09:45:00', 'Mary Lee', 1, 'New laptop purchase'),
(5, 4, 'Liability', '', '', '300.00', '300.00', '2023-09-19 14:20:00', 'David Brown', 1, 'Loan repayment'),
(6, 1, 'Expense', '', '', '60.00', '0.00', '2024-10-23 09:46:20', 'John Doe', 2, 'not correct, debit and credit Value needs to be equal'),
(7, 1, 'Expense', '', '', '200.00', '200.00', '2024-10-23 09:46:20', 'John Doe', 0, 'Test transaction 2'),
(8, 2, 'Expense', '', '', '500.00', '500.00', '2024-10-23 09:46:20', 'John Doe', 0, 'Test transaction 3'),
(9, 4, 'Expense', '', '', '300.00', '300.00', '2024-10-23 09:46:20', 'John Doe', 1, 'Approved'),
(10, 1, 'Asset', 'Cash', 'Service Fees', '100.00', '100.00', '2024-10-23 15:53:30', 'amil1024', 0, 'fees for service!'),
(11, 1, 'Asset', 'Cash', 'Telephone Expense', '25.00', '25.00', '2024-10-23 15:55:51', 'amil1024', 1, 'Approved'),
(12, 1, 'Expense', 'Cash', 'Advertising Expense', '250.00', '250.00', '2024-10-23 16:28:11', 'ddog1024', 0, 'advertising transaction'),
(13, 1, 'Asset', 'Cash', 'Store Supplies Expense', '100.00', '100.00', '2024-10-23 17:32:29', 'amil1024', 1, 'Approved'),
(14, 1, 'Asset', 'Cash', 'Rent Expense', '300.00', '300.00', '2024-10-23 19:33:01', 'amil1024', 0, 'Paying for Rent');

--
-- Triggers `Journal_Entries`
--
DELIMITER $$
CREATE TRIGGER `insert_ledger_transaction` AFTER INSERT ON `Journal_Entries` FOR EACH ROW BEGIN
    DECLARE new_balance DECIMAL(10,2);
    
    -- Insert a matching ledger transaction based on the journal entry
    INSERT INTO Ledger_Transactions (
        journal_entry_id, client_account_id, reference_number, 
        transaction_date, description, debit, credit
    ) VALUES (
        NEW.id, NEW.client_account_id, CONCAT('REF-', NEW.id), 
        NEW.created_at, NEW.comment, NEW.debit, NEW.credit
    );

    -- Calculate the new running balance for the client account
    SELECT COALESCE(SUM(debit - credit), 0)
    INTO new_balance
    FROM Ledger_Transactions
    WHERE client_account_id = NEW.client_account_id;

    -- Update the balance in the Client_Accounts table
    UPDATE Client_Accounts
    SET balance = new_balance
    WHERE id = NEW.client_account_id;

    -- Update the balance in the latest ledger transaction
    UPDATE Ledger_Transactions
    SET balance_after = new_balance
    WHERE journal_entry_id = NEW.id;  -- Update balance_after of the newly inserted transaction
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Ledger_Transactions`
--

CREATE TABLE `Ledger_Transactions` (
  `id` int(11) NOT NULL,
  `journal_entry_id` int(11) NOT NULL,
  `client_account_id` int(11) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `transaction_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  `debit` decimal(10,2) DEFAULT '0.00',
  `credit` decimal(10,2) DEFAULT '0.00',
  `balance_after` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Ledger_Transactions`
--

INSERT INTO `Ledger_Transactions` (`id`, `journal_entry_id`, `client_account_id`, `reference_number`, `transaction_date`, `description`, `debit`, `credit`, `balance_after`) VALUES
(1, 1, 1, 'REF-1', '2023-09-15 10:30:00', 'Purchase of stationery', '150.00', '0.00', '150.00'),
(2, 2, 2, 'REF-2', '2023-09-16 11:00:00', 'Payment for consulting', '0.00', '500.00', '-500.00'),
(3, 3, 3, 'REF-3', '2023-09-17 12:15:00', 'Monthly electricity bill', '75.50', '0.00', '75.50'),
(4, 4, 1, 'REF-4', '2023-09-18 09:45:00', 'New laptop purchase', '1200.00', '0.00', '1350.00'),
(5, 5, 4, 'REF-5', '2023-09-19 14:20:00', 'Loan repayment', '0.00', '300.00', '-300.00'),
(6, 6, 1, 'REF-6', '2024-10-23 09:46:20', 'Test transaction 1', '60.00', '0.00', '1410.00'),
(7, 7, 1, 'REF-7', '2024-10-23 09:46:20', 'Test transaction 2', '200.00', '0.00', '1610.00'),
(8, 8, 2, 'REF-8', '2024-10-23 09:46:20', 'Test transaction 3', '500.00', '0.00', '0.00'),
(9, 9, 4, 'REF-9', '2024-10-23 09:46:20', 'Test transaction 4', '300.00', '0.00', '0.00'),
(10, 10, 1, 'REF-10', '2024-10-23 15:53:30', 'fees for service!', '100.00', '100.00', '1610.00'),
(11, 11, 1, 'REF-11', '2024-10-23 15:55:51', 'Paying user for telephone expenses', '25.00', '25.00', '1610.00'),
(12, 12, 1, 'REF-12', '2024-10-23 16:28:11', 'advertising transaction', '250.00', '250.00', '1610.00'),
(13, 13, 1, 'REF-13', '2024-10-23 17:32:29', 'Office Supplies', '100.00', '100.00', '1610.00'),
(14, 14, 1, 'REF-14', '2024-10-23 19:33:01', 'Paying for Rent', '300.00', '300.00', '1610.00');

-- --------------------------------------------------------

--
-- Table structure for table `table1`
--

CREATE TABLE `table1` (
  `Id` int(11) NOT NULL,
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
  `FailedAttempts` int(11) DEFAULT '0',
  `LockoutUntil` datetime DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ModifiedDate` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(50) DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT '0',
  `Approved` tinyint(1) DEFAULT '0',
  `Position` varchar(50) DEFAULT NULL,
  `ExpiryDuration` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table1`
--

INSERT INTO `table1` (`Id`, `UserTypeId`, `Username`, `Password`, `EmailAddress`, `DateOfBirth`, `FirstName`, `LastName`, `Address`, `SecurityQuestions`, `SecurityAnswers`, `FailedAttempts`, `LockoutUntil`, `CreatedDate`, `ModifiedDate`, `ModifiedBy`, `IsActive`, `Approved`, `Position`, `ExpiryDuration`) VALUES
(5, 'Accountant', 'wthomas1024', '$2y$10$oSyNxPU1Ah139Qlo63ms9uIpTuag.8GPtDYIZEX.ZHcETKjnv2cxG', 'wmthomas@gmail.com', '1969-05-23', 'Wendy', 'lol', 'Your Local Wendys', 'q2', 'vickie', 2, NULL, '2024-10-02 16:23:10', '2024-10-23 13:13:33', NULL, 1, 1, NULL, 10),
(11, 'Manager', 'amil1024', '$2y$10$I3S/xplC48Fqt0ax6K2LduVBnr.fA/MGoL7rJsHDXsENToVIQCSV6', 'Amil@gmail.com', '2024-10-01', 'Alex', 'Mil', '3335 gold mist dr', 'q1', 'sam', 0, NULL, '2024-10-02 18:28:27', '2024-10-22 22:29:01', NULL, 1, 1, NULL, 0),
(12, 'Accountant', 'ddog1024', '$2y$10$kj7h.6q2F9pOXGhDnmLEgebTV7bilH3zwV7izCeE01a54v.KiOlzm', 'dog@gmail.com', '2013-06-04', 'dog', 'dog', 'asdajskdadadasdsad', 'q5', 'atlanta', 0, NULL, '2024-10-02 19:03:04', '2024-10-09 13:11:05', NULL, 1, 1, NULL, 0),
(1, 'Admin', 'bportier1024', '$2y$10$2Gv5Xre7u3GgnMQOk1lgPuUM1irWxLuudMaEgZ4KYTBUOo1fSIaGy', 'bdportier@gmail.com', '2000-05-30', 'Brandon', 'Portier', '3335 gold mist dr buford Ga', 'q1', 'sam', 0, NULL, '2024-10-02 10:36:52', '2024-10-02 16:38:46', NULL, 1, 1, '', 30),
(2, 'Manager', 'jportier1024', '$2y$10$j0GJAuqpsGhPXSR6yaXQDOBIiwmx5O5E.3B2DwL1A3AyP1kRlM5OW', 'jhportier@gmail.com', '2000-05-30', 'Julia', 'Portier', '3335 gold mist dr buford Ga', 'q1', 'sam', 0, NULL, '2024-10-02 15:21:52', '2024-10-09 14:57:56', NULL, 1, 1, NULL, 30),
(3, 'Manager', 'lportier1024', '$2y$10$4NzCCJQQ6WVtJ7jBK9wOReFYsVPnhQZxRhl6li2b1yXGqLYK/bUJu', 'laportier@gmail.com', '2001-01-29', 'Luke', 'Portier', '3335 gold mist dr buford Ga', 'q1', 'sam', 0, NULL, '2024-10-02 15:30:44', '2024-10-09 14:59:09', NULL, 1, 1, NULL, 30),
(4, 'Accountant', 'jbond1024', '$2y$10$qSCFEsxh4nEibSxleO3pseiWmT.8UYmxWr.vY2My4UAV3fuLTBsNq', 'jbond@gmail.com', '1988-06-15', 'James', 'Bond', 'somewhere', 'q1', 'sam', 1, NULL, '2024-10-02 15:32:37', '2024-10-23 12:39:41', NULL, 1, 1, NULL, 30),
(13, 'Accountant', 'nfrom1024', '$2y$10$1ysk7NY4nOGvA1iZfos6je3J1pDPeCGo77KEKfU7dw1mFNDneL2yu', 'nfrom@gmail.com', '2003-06-10', 'Nic', 'From', '1234 cool street rd sw coolville', 'q1', 'cat', 0, NULL, '2024-10-23 13:07:31', '2024-10-23 13:11:01', NULL, 1, 1, NULL, NULL);

--
-- Triggers `table1`
--
DELIMITER $$
CREATE TRIGGER `event_insert_table1` AFTER INSERT ON `table1` FOR EACH ROW BEGIN
    INSERT INTO user_eventlog
     (UserId, UserAcctType, AcctAffected, BeforeAffected, AfterAffected, Event_Status, DateANDTime) 
    VALUES
    (NEW.Id, NEW.UserTypeId, 'User Accounts', 'N/A', NEW.Username, 'Created', NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `event_update_table1` AFTER UPDATE ON `table1` FOR EACH ROW BEGIN
    -- Check if FailedAttempts have changed
    IF OLD.FailedAttempts = NEW.FailedAttempts OR OLD.LockoutUntil != NEW.LockoutUntil THEN
        -- If FailedAttempts have not changed, proceed with the insert
        INSERT INTO user_eventlog
        (UserId, Username, UserAcctType, AcctAffected, BeforeAffected, AfterAffected, Event_Status, DateANDTime) 
        VALUES
        (
            NEW.Id, 
            NEW.UserTypeId, 
            'User Accounts', 
            CONCAT(
                'Username: ', OLD.Username, ', ', 
                'EmailAddress: ', OLD.EmailAddress, ', ', 
                'FirstName: ', OLD.FirstName, ', ', 
                'LastName: ', OLD.LastName, ', ', 
                'Address: ', OLD.Address
            ), 
            CONCAT(
                'Username: ', NEW.Username, ', ', 
                'EmailAddress: ', NEW.EmailAddress, ', ', 
                'FirstName: ', NEW.FirstName, ', ', 
                'LastName: ', NEW.LastName, ', ', 
                'Address: ', NEW.Address
            ), 
            'Account Updated', 
            NOW()
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `usersuspensions`
--

CREATE TABLE `usersuspensions` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usersuspensions`
--

INSERT INTO `usersuspensions` (`Id`, `UserId`, `StartDate`, `EndDate`) VALUES
(1, 3, '2024-10-02', '2024-10-05'),
(2, 1, '2024-10-24', '2024-11-02'),
(3, 6, '2024-10-02', '2024-10-04'),
(4, 5, '2024-10-02', '2024-10-03'),
(5, 1, '2024-10-02', '2024-10-03'),
(6, 5, '2024-10-02', '2024-10-11');

-- --------------------------------------------------------

--
-- Table structure for table `usertypetable`
--

CREATE TABLE `usertypetable` (
  `UserTypeId` varchar(10) NOT NULL,
  `Description` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_eventlog`
--

CREATE TABLE `user_eventlog` (
  `EventID` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Username` varchar(250) DEFAULT NULL,
  `UserAcctType` varchar(50) NOT NULL,
  `AcctAffected` varchar(100) NOT NULL,
  `BeforeAffected` varchar(250) NOT NULL,
  `AfterAffected` varchar(250) NOT NULL,
  `Event_Status` varchar(100) NOT NULL,
  `DateANDTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_eventlog`
--

INSERT INTO `user_eventlog` (`EventID`, `UserId`, `Username`, `UserAcctType`, `AcctAffected`, `BeforeAffected`, `AfterAffected`, `Event_Status`, `DateANDTime`) VALUES
(1, 1, 'bportier1024', 'Admin', 'User Accounts', 'Username: bportier1024, EmailAddress: bdportier@gmail.com, FirstName: Brandon, LastName: Portier, Address: 3335 gold mist dr buford Ga', 'Username: bportier1024, EmailAddress: bdportier@gmail.com, FirstName: Brandon, LastName: Portier, Address: 3335 gold mist dr buford Ga', 'Account Updated', '2024-10-23 16:46:06'),
(2, 1, 'bportier1024', 'Cash', 'Asset', 'Normal side changed from: debit', 'New normal side: credit', 'Client Account Updated', '2024-10-23 16:46:27'),
(3, 1, 'bportier1024', 'Cash', 'Asset', 'Normal side changed from: credit', 'New normal side: debit', 'Client Account Updated', '2024-10-23 16:46:33'),
(4, 1, 'bportier1024', 'Admin', 'User Accounts', 'Username: bportier1024, EmailAddress: bdportier@gmail.com, FirstName: Brandon, LastName: Portier, Address: 3335 gold mist dr buford Ga', 'Username: bportier1024, EmailAddress: bdportier@gmail.com, FirstName: Brandon, LastName: Portier, Address: 3335 gold mist dr buford Ga', 'Account Updated', '2024-10-23 16:47:29'),
(5, 1, 'bportier1024', 'Cash', 'Asset', 'Normal side changed from: debit', 'New normal side: credit', 'Client Account Updated', '2024-10-23 17:25:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_roster`
--

CREATE TABLE `user_roster` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `IsActive` tinyint(1) DEFAULT '1',
  `IsApproved` tinyint(1) DEFAULT '0',
  `SuspensionStartDate` date DEFAULT NULL,
  `SuspensionEndDate` date DEFAULT NULL,
  `CreatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_roster`
--

INSERT INTO `user_roster` (`Id`, `FirstName`, `LastName`, `Username`, `Password`, `IsActive`, `IsApproved`, `SuspensionStartDate`, `SuspensionEndDate`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 'John', 'Doe', 'johndoe', 'password123', 1, 1, NULL, NULL, '2024-10-02 12:30:47', '2024-10-02 12:30:47'),
(2, 'Jane', 'Smith', 'janesmith', 'password456', 1, 0, NULL, NULL, '2024-10-02 12:30:47', '2024-10-02 12:30:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Client_Accounts`
--
ALTER TABLE `Client_Accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_number` (`account_number`);

--
-- Indexes for table `expiredpasswords`
--
ALTER TABLE `expiredpasswords`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `Journal_Entries`
--
ALTER TABLE `Journal_Entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_account_id` (`client_account_id`);

--
-- Indexes for table `Ledger_Transactions`
--
ALTER TABLE `Ledger_Transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_id` (`journal_entry_id`),
  ADD KEY `client_account_id` (`client_account_id`);

--
-- Indexes for table `table1`
--
ALTER TABLE `table1`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `usersuspensions`
--
ALTER TABLE `usersuspensions`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `usertypetable`
--
ALTER TABLE `usertypetable`
  ADD PRIMARY KEY (`UserTypeId`);

--
-- Indexes for table `user_eventlog`
--
ALTER TABLE `user_eventlog`
  ADD PRIMARY KEY (`EventID`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `user_roster`
--
ALTER TABLE `user_roster`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Client_Accounts`
--
ALTER TABLE `Client_Accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expiredpasswords`
--
ALTER TABLE `expiredpasswords`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Journal_Entries`
--
ALTER TABLE `Journal_Entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `Ledger_Transactions`
--
ALTER TABLE `Ledger_Transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `table1`
--
ALTER TABLE `table1`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `usersuspensions`
--
ALTER TABLE `usersuspensions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_eventlog`
--
ALTER TABLE `user_eventlog`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_roster`
--
ALTER TABLE `user_roster`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Journal_Entries`
--
ALTER TABLE `Journal_Entries`
  ADD CONSTRAINT `journal_entries_ibfk_1` FOREIGN KEY (`client_account_id`) REFERENCES `Client_Accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Ledger_Transactions`
--
ALTER TABLE `Ledger_Transactions`
  ADD CONSTRAINT `ledger_transactions_ibfk_1` FOREIGN KEY (`journal_entry_id`) REFERENCES `Journal_Entries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ledger_transactions_ibfk_2` FOREIGN KEY (`client_account_id`) REFERENCES `Client_Accounts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
