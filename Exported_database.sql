-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 02, 2024 at 12:01 AM
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
(6, 'Cash', '1', 'Cash account', 'debit', 'asset', 'current asset', '0.00', '8875.00', '2024-11-06 14:16:11', '2024-12-01 17:59:04', 'amil1024', 0, '1', 'IS', 1, 'Testing 123'),
(7, 'Account Receivable ', '2', 'Account Receivable ', 'debit', 'asset', 'current asset', '0.00', '3450.00', '2024-11-06 14:18:51', '2024-11-06 15:14:14', 'amil1024', 0, '2', 'BS', 1, 'Addams family'),
(8, 'Prepaid Rent', '3', 'Prepaid Rent', 'debit', 'asset', 'current asset', '0.00', '3000.00', '2024-11-06 14:21:28', '2024-11-20 19:28:03', 'amil1024', 0, '3', 'RE', 1, 'Testing again'),
(9, 'Prepaid Insurance', '4', 'Prepaid Insurance', 'debit', 'asset', 'current asset', '0.00', '1650.00', '2024-11-06 14:23:12', '2024-11-18 16:23:45', 'amil1024', 0, '4', 'IS', 1, 'whatever'),
(10, 'Supplies', '5', 'Supplies', 'debit', 'asset', 'current asset', '0.00', '1020.00', '2024-11-06 14:24:08', '2024-11-18 16:25:41', 'amil1024', 0, '5', 'IS', 1, 'Supplies'),
(11, 'Property Plant and Equipment', '6', 'Property Plant and Equipment', 'debit', 'asset', 'non-current assets', '8800.00', '0.00', '2024-11-06 14:25:30', '2024-11-20 19:35:12', 'amil1024', 0, '6', 'IS', 1, 'Property Plant and Equipment'),
(12, 'Office Equipment', '7', 'Office Equipment', 'debit', 'asset', 'non-current assets', '0.00', '9300.00', '2024-11-06 14:26:05', '2024-11-06 15:04:45', 'amil1024', 0, '7', 'IS', 1, 'Office Equipment'),
(13, 'Accumulated Depreciation ', '8', 'Accumulated Depreciation ', 'debit', 'asset', 'non-current assets', '0.00', '-500.00', '2024-11-06 14:28:04', '2024-11-29 17:24:26', 'amil1024', 0, '8', 'IS', 1, 'Accumulated Depreciation '),
(14, 'Accounts Payable ', '9', 'Accounts Payable ', 'credit', 'liability', 'liability', '0.00', '1000.00', '2024-11-06 14:29:58', '2024-11-20 14:18:44', 'amil1024', 0, '9', 'IS', 1, 'Accounts Payable '),
(15, 'Salaries Payable', '10', 'Salaries Payable', 'credit', 'liability', 'liability', '0.00', '20.00', '2024-11-06 14:31:20', '2024-11-20 14:18:39', 'amil1024', 0, '10', 'IS', 1, 'Salaries Payable'),
(16, 'Contributed Capital', '11', 'Contributed Capital', 'credit', 'equity', 'equity', '0.00', '20250.00', '2024-11-06 14:32:51', '2024-11-20 14:18:34', 'amil1024', 0, '11', 'IS', 1, 'Contributed Capital'),
(17, 'Retained earnings', '12', 'Retained earnings', 'credit', 'equity', 'equity', '0.00', '4525.00', '2024-11-06 14:33:32', '2024-11-20 19:40:04', 'amil1024', 0, '12', 'IS', 1, 'Retained earnings'),
(18, 'Service Revenue', '13', 'Service Revenue', 'credit', 'Revenue', 'Revenue', '0.00', '13425.00', '2024-11-06 14:37:49', '2024-11-29 16:40:29', 'amil1024', 0, '13', 'IS', 1, 'Service Revenue'),
(19, 'Insurance Expense', '14', 'Insurance Expense', 'debit', 'Expense', 'Expense', '0.00', '150.00', '2024-11-06 14:38:38', '2024-11-29 16:41:09', 'amil1024', 0, '14', 'IS', 1, 'Insurance Expense'),
(20, 'Depreciation Expense', '15', 'Depreciation Expense', 'debit', 'Expense', 'Expense', '0.00', '500.00', '2024-11-06 14:39:51', '2024-11-29 16:41:26', 'amil1024', 0, '15', 'IS', 1, 'Depreciation Expense'),
(21, 'Rent Expense ', '16', 'Rent Expense ', 'debit', 'Expense', 'Current Assets', '0.00', '1500.00', '2024-11-06 14:40:48', '2024-11-29 16:41:30', 'amil1024', 0, '16', 'IS', 1, 'Rent Expense '),
(22, 'Supplies Expense ', '17', 'Supplies Expense ', 'debit', 'Expense', 'Current Assets', '0.00', '1380.00', '2024-11-06 14:41:23', '2024-11-29 16:41:34', 'amil1024', 0, '17', 'IS', 1, 'Supplies Expense '),
(23, 'Salaries Expense', '18', 'Salaries Expense', 'debit', 'Expense', 'Expense', '0.00', '4920.00', '2024-11-06 14:42:47', '2024-11-29 16:41:37', 'amil1024', 0, '18', 'IS', 1, 'Salaries Expense'),
(24, 'Telephone Expense ', '19', 'Telephone Expense ', 'debit', 'Expense', 'Expense', '0.00', '130.00', '2024-11-06 14:43:23', '2024-11-29 16:41:40', 'amil1024', 0, '19', 'IS', 1, 'Telephone Expense '),
(25, 'Utilities Expense ', '20', 'Utilities Expense ', 'debit', 'Expense', 'Expense', '0.00', '200.00', '2024-11-06 14:43:51', '2024-12-01 17:56:32', 'amil1024', 0, '20', 'IS', 1, 'Utilities Expense '),
(26, 'Advertising Expense', '21', 'Advertising Expense', 'debit', 'Expense', 'Expense', '0.00', '120.00', '2024-11-06 14:44:18', '2024-11-29 16:41:48', 'amil1024', 0, '21', 'IS', 1, 'Advertising Expense'),
(27, 'Unearned Revenue', '22', 'Unearned Revenue', 'credit', 'liability', 'liability', '0.00', '1000.00', '2024-11-06 15:02:11', '2024-11-29 16:39:47', 'amil1024', 0, '22', 'IS', 1, 'Unearned Revenue'),
(28, 'Inventory', '28', 'Inventory', 'debit', 'inventory', 'inventory', '0.00', '0.00', '2024-11-13 21:59:07', '2024-12-01 18:17:53', 'bportier1024', 0, '28', 'BS', 1, 'inventory');

-- --------------------------------------------------------

--
-- Table structure for table `ErrorCodes`
--

CREATE TABLE `ErrorCodes` (
  `Error_Code_ID` int(11) NOT NULL,
  `Error_Type` varchar(50) DEFAULT NULL,
  `Error_Message` text,
  `description` text,
  `Resolution_Steps` text,
  `Severity_Level` enum('Low','Medium','High','Critical') DEFAULT NULL,
  `Created_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ErrorCodes`
--

INSERT INTO `ErrorCodes` (`Error_Code_ID`, `Error_Type`, `Error_Message`, `description`, `Resolution_Steps`, `Severity_Level`, `Created_Date`, `Modified_Date`) VALUES
(1001, 'Database', 'Unable to connect to database', 'Failed connection to the database server.', 'Verify server status and connection details.', 'Critical', '2024-11-05 01:40:19', '2024-11-05 01:40:19'),
(2001, 'Authentication', 'Invalid username or password', 'Login attempt failed due to incorrect credentials.', 'Prompt user for correct credentials.', 'High', '2024-11-05 01:40:19', '2024-11-05 01:40:19'),
(3001, 'Network', 'Network timeout occurred', 'Network connection lost while trying to access the server.', 'Check network settings and server availability.', 'Medium', '2024-11-05 01:40:19', '2024-11-05 01:40:19'),
(4001, 'Application', 'Unexpected null value', 'An unexpected null value was encountered in the application.', 'Check code for null handling.', 'Medium', '2024-11-05 01:40:19', '2024-11-05 01:40:19'),
(5001, 'Deprecation', 'Feature X is deprecated', 'Feature X is no longer supported.', 'Inform users to transition to Feature Y.', 'Low', '2024-11-05 01:40:19', '2024-11-05 01:40:19');

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
  `entry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(50) NOT NULL,
  `entry_type` enum('Normal','Adjusted') DEFAULT 'Normal',
  `is_approved` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Journal_Entries`
--

INSERT INTO `Journal_Entries` (`id`, `entry_date`, `created_by`, `entry_type`, `is_approved`, `comment`) VALUES
(11, '2024-11-06 19:20:03', 'amil1024', 'Normal', 'Rejected', 'Not needed'),
(12, '2024-11-06 19:57:48', 'amil1024', 'Normal', 'Approved', 'Approved'),
(13, '2024-11-06 20:00:05', 'amil1024', 'Normal', 'Approved', 'Approved'),
(14, '2024-11-06 20:00:48', 'amil1024', 'Normal', 'Approved', 'Approved'),
(15, '2024-11-06 20:02:46', 'amil1024', 'Normal', 'Approved', 'Approved'),
(16, '2024-11-06 20:03:26', 'amil1024', 'Normal', 'Approved', 'Approved'),
(17, '2024-11-06 20:03:56', 'amil1024', 'Normal', 'Approved', 'Approved'),
(18, '2024-11-06 20:04:34', 'amil1024', 'Normal', 'Approved', 'Approved'),
(19, '2024-11-06 20:05:48', 'amil1024', 'Normal', 'Approved', 'Approved'),
(20, '2024-11-06 20:06:32', 'amil1024', 'Normal', 'Approved', 'Approved'),
(21, '2024-11-06 20:07:02', 'amil1024', 'Normal', 'Approved', 'Approved'),
(22, '2024-11-06 20:07:55', 'amil1024', 'Normal', 'Approved', 'Approved'),
(23, '2024-11-06 20:09:05', 'amil1024', 'Normal', 'Approved', 'Approved'),
(24, '2024-11-06 20:09:27', 'amil1024', 'Normal', 'Approved', 'Approved'),
(25, '2024-11-06 20:10:09', 'amil1024', 'Normal', 'Approved', 'Approved'),
(26, '2024-11-06 20:10:44', 'amil1024', 'Normal', 'Approved', 'Approved'),
(27, '2024-11-06 20:11:46', 'amil1024', 'Normal', 'Approved', 'Approved'),
(28, '2024-11-06 20:12:39', 'amil1024', 'Normal', 'Approved', 'Approved'),
(29, '2024-11-06 20:13:08', 'amil1024', 'Normal', 'Approved', 'Approved'),
(30, '2024-11-06 20:13:38', 'amil1024', 'Normal', 'Approved', 'Approved'),
(31, '2024-11-06 20:14:13', 'amil1024', 'Normal', 'Approved', 'Approved'),
(32, '2024-11-06 20:14:52', 'amil1024', 'Normal', 'Approved', 'Approved'),
(33, '2024-11-14 02:59:42', 'amil1024', 'Normal', 'Approved', 'Approved'),
(34, '2024-11-15 01:50:02', 'amil1024', 'Adjusted', 'Rejected', 'Testing'),
(35, '2024-11-18 01:36:43', 'amil1024', 'Normal', 'Rejected', 'Not correct'),
(36, '2024-11-18 01:45:57', 'amil1024', 'Normal', 'Rejected', 'testing'),
(37, '2024-11-18 21:23:43', 'amil1024', 'Adjusted', 'Approved', 'Approved'),
(38, '2024-11-18 21:24:16', 'amil1024', 'Normal', 'Rejected', 'not correct'),
(39, '2024-11-18 21:24:56', 'amil1024', 'Adjusted', 'Approved', 'Approved'),
(40, '2024-11-18 21:25:38', 'amil1024', 'Adjusted', 'Approved', 'Approved'),
(41, '2024-11-18 21:26:08', 'amil1024', 'Normal', 'Rejected', 'Not correct'),
(42, '2024-11-18 21:26:37', 'amil1024', 'Normal', 'Rejected', 'Not correct'),
(43, '2024-11-18 21:27:32', 'amil1024', 'Adjusted', 'Approved', 'Approved'),
(44, '2024-11-18 21:28:20', 'amil1024', 'Adjusted', 'Approved', 'Approved'),
(45, '2024-11-18 21:29:14', 'amil1024', 'Normal', 'Approved', 'Approved'),
(46, '2024-11-18 22:34:16', 'amil1024', 'Normal', 'Rejected', 'Not needed'),
(47, '2024-11-29 22:44:20', 'amil1024', 'Normal', 'Pending', ''),
(48, '2024-12-01 22:46:49', 'amil1024', 'Normal', 'Rejected', 'This was only for testing'),
(49, '2024-12-01 22:50:17', 'amil1024', 'Adjusted', 'Rejected', 'Approved'),
(50, '2024-12-01 23:15:03', 'amil1024', 'Normal', 'Rejected', 'Testing'),
(51, '2024-12-01 23:35:37', 'amil1024', 'Normal', 'Rejected', 'We did this for my showcase');

--
-- Triggers `Journal_Entries`
--
DELIMITER $$
CREATE TRIGGER `update_ledger_transaction` BEFORE UPDATE ON `Journal_Entries` FOR EACH ROW BEGIN
    -- Declare variables
    DECLARE new_balance DECIMAL(10, 2);
    DECLARE current_debit DECIMAL(10, 2);
    DECLARE current_credit DECIMAL(10, 2);
    DECLARE current_account_id VARCHAR(100);
    DECLARE done INT DEFAULT FALSE;

    -- Declare cursors
    DECLARE entry_cursor CURSOR FOR
        SELECT account, debit, credit
        FROM Journal_Entry_Lines
        WHERE journal_entry_id = NEW.id;

    DECLARE account_cursor CURSOR FOR
        SELECT DISTINCT account
        FROM Journal_Entry_Lines
        WHERE journal_entry_id = NEW.id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- If the approval status is changing
    IF NEW.is_approved != OLD.is_approved THEN

        -- If the new approval status is 'Approved'
        IF NEW.is_approved = 'Approved' THEN
            OPEN entry_cursor;

            read_loop: LOOP
                FETCH entry_cursor INTO current_account_id, current_debit, current_credit;
                IF done THEN
                    LEAVE read_loop;
                END IF;

                -- Get the client account ID
                SET @client_id = (SELECT id FROM Client_Accounts WHERE account_name = current_account_id LIMIT 1);
                
                -- Insert a matching ledger transaction
                INSERT INTO Ledger_Transactions (
                    journal_entry_id, client_account_id, reference_number, 
                    transaction_date, description, debit, credit
                ) VALUES (
                    NEW.id,
                    @client_id,
                    CONCAT('REF-', NEW.id),
                    NEW.entry_date,
                    NEW.comment,
                    current_debit,
                    current_credit
                );

                -- Calculate the new running balance for the client account
                SELECT COALESCE(SUM(debit - credit), 0)
                INTO new_balance
                FROM Ledger_Transactions
                WHERE client_account_id = @client_id;

                -- Update the balance in the Client_Accounts table
                UPDATE Client_Accounts
                SET balance = new_balance
                WHERE id = @client_id;

            END LOOP;

            CLOSE entry_cursor;

        ELSEIF NEW.is_approved = 'Rejected' THEN
            -- If the entry is rejected, remove the associated ledger entries
            DELETE FROM Ledger_Transactions
            WHERE journal_entry_id = NEW.id;

            OPEN account_cursor;

            read_balance_loop:LOOP
                FETCH account_cursor INTO current_account_id;
                IF done THEN
                    LEAVE read_balance_loop;
                END IF;

                -- Get the client account ID
                SET @client_id = (SELECT id FROM Client_Accounts WHERE account_name = current_account_id LIMIT 1);

                -- Recalculate the balance for this account
                SELECT COALESCE(SUM(debit - credit), 0)
                INTO new_balance
                FROM Ledger_Transactions
                WHERE client_account_id = @client_id;

                -- Update the balance in the Client_Accounts table
                UPDATE Client_Accounts
                SET balance = new_balance
                WHERE id = @client_id;
            END LOOP;

            CLOSE account_cursor;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Journal_Entry_Lines`
--

CREATE TABLE `Journal_Entry_Lines` (
  `id` int(11) NOT NULL,
  `journal_entry_id` int(11) NOT NULL,
  `account` varchar(100) NOT NULL,
  `account_type` enum('Asset','Liability','Revenue','Expense') NOT NULL,
  `debit` decimal(15,2) DEFAULT '0.00',
  `credit` decimal(15,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Journal_Entry_Lines`
--

INSERT INTO `Journal_Entry_Lines` (`id`, `journal_entry_id`, `account`, `account_type`, `debit`, `credit`) VALUES
(30, 11, 'Cash', 'Liability', '100.00', '0.00'),
(31, 11, 'Account Receivable ', 'Liability', '0.00', '100.00'),
(32, 12, 'Cash', 'Liability', '10000.00', '0.00'),
(33, 12, 'Account Receivable ', 'Liability', '1500.00', '0.00'),
(34, 12, 'Supplies', 'Liability', '1250.00', '0.00'),
(35, 12, 'Office Equipment', 'Liability', '7500.00', '0.00'),
(36, 12, 'Contributed Capital', 'Asset', '0.00', '20250.00'),
(37, 13, 'Prepaid Rent', 'Asset', '4500.00', '0.00'),
(38, 13, 'Cash', 'Liability', '0.00', '4500.00'),
(39, 14, 'Prepaid Insurance', 'Liability', '1800.00', '0.00'),
(40, 14, 'Cash', 'Liability', '0.00', '1800.00'),
(41, 15, 'Cash', 'Liability', '3000.00', '0.00'),
(42, 15, 'Unearned Revenue', 'Liability', '0.00', '3000.00'),
(43, 16, 'Office Equipment', 'Liability', '1800.00', '0.00'),
(44, 16, 'Accounts Payable ', 'Asset', '0.00', '1800.00'),
(45, 17, 'Cash', 'Liability', '800.00', '0.00'),
(46, 17, 'Account Receivable ', 'Liability', '0.00', '800.00'),
(47, 18, 'Advertising Expense', 'Liability', '120.00', '0.00'),
(48, 18, 'Cash', 'Liability', '0.00', '120.00'),
(49, 19, 'Accounts Payable ', 'Asset', '800.00', '0.00'),
(50, 19, 'Cash', 'Liability', '0.00', '800.00'),
(51, 20, 'Account Receivable ', 'Liability', '2250.00', '0.00'),
(52, 20, 'Service Revenue', 'Liability', '0.00', '2250.00'),
(53, 21, 'Salaries Expense', 'Liability', '400.00', '0.00'),
(54, 21, 'Cash', 'Liability', '0.00', '400.00'),
(55, 22, 'Cash', 'Liability', '3175.00', '0.00'),
(56, 22, 'Service Revenue', 'Liability', '0.00', '3175.00'),
(57, 23, 'Supplies', 'Liability', '750.00', '0.00'),
(58, 23, 'Cash', 'Liability', '0.00', '750.00'),
(59, 24, 'Account Receivable ', 'Liability', '1100.00', '0.00'),
(60, 24, 'Service Revenue', 'Liability', '0.00', '1100.00'),
(61, 25, 'Cash', 'Liability', '1850.00', '0.00'),
(62, 25, 'Service Revenue', 'Liability', '0.00', '1850.00'),
(63, 26, 'Cash', 'Liability', '1600.00', '0.00'),
(64, 26, 'Account Receivable ', 'Liability', '0.00', '1600.00'),
(65, 27, 'Supplies Expense ', 'Liability', '400.00', '0.00'),
(66, 27, 'Cash', 'Liability', '0.00', '400.00'),
(67, 28, 'Telephone Expense ', 'Liability', '130.00', '0.00'),
(68, 28, 'Cash', 'Liability', '0.00', '130.00'),
(69, 29, 'Utilities Expense ', 'Liability', '200.00', '0.00'),
(70, 29, 'Cash', 'Liability', '0.00', '200.00'),
(71, 30, 'Cash', 'Liability', '2050.00', '0.00'),
(72, 30, 'Service Revenue', 'Liability', '0.00', '2050.00'),
(73, 31, 'Account Receivable ', 'Liability', '1000.00', '0.00'),
(74, 31, 'Service Revenue', 'Liability', '0.00', '1000.00'),
(75, 32, 'Salaries Expense', 'Liability', '4500.00', '0.00'),
(76, 32, 'Cash', 'Liability', '0.00', '4500.00'),
(77, 33, 'Inventory', 'Liability', '100.00', '0.00'),
(78, 33, 'Cash', 'Liability', '0.00', '100.00'),
(79, 34, 'Inventory', 'Liability', '200.00', '0.00'),
(85, 37, 'Insurance Expense', 'Liability', '150.00', '0.00'),
(86, 37, 'Prepaid Insurance', 'Liability', '0.00', '150.00'),
(87, 38, 'Supplies Expense ', 'Liability', '980.00', '0.00'),
(88, 38, 'Supplies', 'Liability', '0.00', '980.00'),
(89, 39, 'Depreciation Expense', 'Liability', '500.00', '0.00'),
(90, 39, 'Accumulated Depreciation ', 'Liability', '0.00', '500.00'),
(91, 40, 'Supplies Expense ', 'Liability', '980.00', '0.00'),
(92, 40, 'Supplies', 'Liability', '0.00', '980.00'),
(93, 41, 'Salaries Expense', 'Liability', '20.00', '0.00'),
(94, 41, 'Salaries Payable', 'Liability', '0.00', '20.00'),
(95, 42, 'Rent Expense ', 'Liability', '1500.00', '0.00'),
(96, 42, 'Prepaid Rent', 'Liability', '0.00', '1500.00'),
(97, 43, 'Unearned Revenue', 'Liability', '2000.00', '0.00'),
(98, 43, 'Service Revenue', 'Liability', '0.00', '2000.00'),
(99, 44, 'Salaries Expense', 'Liability', '20.00', '0.00'),
(100, 44, 'Salaries Payable', 'Liability', '0.00', '20.00'),
(101, 45, 'Rent Expense ', 'Liability', '1500.00', '0.00'),
(102, 45, 'Prepaid Rent', 'Liability', '0.00', '1500.00'),
(103, 46, 'Cash', 'Liability', '200.00', '0.00'),
(104, 46, 'Prepaid Rent', 'Liability', '0.00', '200.00'),
(105, 47, 'Cash', 'Liability', '200.00', '0.00'),
(106, 47, 'Supplies', 'Liability', '0.00', '200.00'),
(107, 48, 'Cash', 'Liability', '200.00', '0.00'),
(108, 48, 'Supplies', 'Liability', '200.00', '0.00'),
(109, 48, 'Rent Expense ', 'Liability', '0.00', '400.00'),
(110, 49, 'Inventory', 'Liability', '100.00', '0.00'),
(111, 49, 'Utilities Expense ', 'Liability', '0.00', '100.00'),
(112, 50, 'Office Equipment', 'Liability', '100.00', '0.00'),
(113, 50, 'Rent Expense ', 'Liability', '100.00', '0.00'),
(114, 50, 'Telephone Expense ', 'Liability', '0.00', '200.00'),
(115, 51, 'Supplies', 'Liability', '1000.00', '0.00'),
(116, 51, 'Depreciation Expense', 'Liability', '1000.00', '0.00'),
(117, 51, 'Telephone Expense ', 'Liability', '0.00', '2000.00');

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
(28, 12, 6, 'REF-12', '2024-11-06 14:57:48', 'Approved', '10000.00', '0.00', NULL),
(29, 12, 7, 'REF-12', '2024-11-06 14:57:48', 'Approved', '1500.00', '0.00', NULL),
(30, 12, 10, 'REF-12', '2024-11-06 14:57:48', 'Approved', '1250.00', '0.00', NULL),
(31, 12, 12, 'REF-12', '2024-11-06 14:57:48', 'Approved', '7500.00', '0.00', NULL),
(32, 12, 16, 'REF-12', '2024-11-06 14:57:48', 'Approved', '0.00', '20250.00', NULL),
(33, 13, 8, 'REF-13', '2024-11-06 15:00:05', 'Approved', '4500.00', '0.00', NULL),
(34, 13, 6, 'REF-13', '2024-11-06 15:00:05', 'Approved', '0.00', '4500.00', NULL),
(35, 14, 9, 'REF-14', '2024-11-06 15:00:48', 'Approved', '1800.00', '0.00', NULL),
(36, 14, 6, 'REF-14', '2024-11-06 15:00:48', 'Approved', '0.00', '1800.00', NULL),
(37, 15, 6, 'REF-15', '2024-11-06 15:02:46', 'Approved', '3000.00', '0.00', NULL),
(38, 15, 27, 'REF-15', '2024-11-06 15:02:46', 'Approved', '0.00', '3000.00', NULL),
(39, 16, 12, 'REF-16', '2024-11-06 15:03:26', 'Approved', '1800.00', '0.00', NULL),
(40, 16, 14, 'REF-16', '2024-11-06 15:03:26', 'Approved', '0.00', '1800.00', NULL),
(41, 17, 6, 'REF-17', '2024-11-06 15:03:56', 'Approved', '800.00', '0.00', NULL),
(42, 17, 7, 'REF-17', '2024-11-06 15:03:56', 'Approved', '0.00', '800.00', NULL),
(43, 18, 26, 'REF-18', '2024-11-06 15:04:34', 'Approved', '120.00', '0.00', NULL),
(44, 18, 6, 'REF-18', '2024-11-06 15:04:34', 'Approved', '0.00', '120.00', NULL),
(45, 19, 14, 'REF-19', '2024-11-06 15:05:48', 'Approved', '800.00', '0.00', NULL),
(46, 19, 6, 'REF-19', '2024-11-06 15:05:48', 'Approved', '0.00', '800.00', NULL),
(47, 20, 7, 'REF-20', '2024-11-06 15:06:32', 'Approved', '2250.00', '0.00', NULL),
(48, 20, 18, 'REF-20', '2024-11-06 15:06:32', 'Approved', '0.00', '2250.00', NULL),
(49, 21, 23, 'REF-21', '2024-11-06 15:07:02', 'Approved', '400.00', '0.00', NULL),
(50, 21, 6, 'REF-21', '2024-11-06 15:07:02', 'Approved', '0.00', '400.00', NULL),
(51, 22, 6, 'REF-22', '2024-11-06 15:07:55', 'Approved', '3175.00', '0.00', NULL),
(52, 22, 18, 'REF-22', '2024-11-06 15:07:55', 'Approved', '0.00', '3175.00', NULL),
(53, 23, 10, 'REF-23', '2024-11-06 15:09:05', 'Approved', '750.00', '0.00', NULL),
(54, 23, 6, 'REF-23', '2024-11-06 15:09:05', 'Approved', '0.00', '750.00', NULL),
(55, 24, 7, 'REF-24', '2024-11-06 15:09:27', 'Approved', '1100.00', '0.00', NULL),
(56, 24, 18, 'REF-24', '2024-11-06 15:09:27', 'Approved', '0.00', '1100.00', NULL),
(57, 25, 6, 'REF-25', '2024-11-06 15:10:09', 'Approved', '1850.00', '0.00', NULL),
(58, 25, 18, 'REF-25', '2024-11-06 15:10:09', 'Approved', '0.00', '1850.00', NULL),
(59, 26, 6, 'REF-26', '2024-11-06 15:10:44', 'Approved', '1600.00', '0.00', NULL),
(60, 26, 7, 'REF-26', '2024-11-06 15:10:44', 'Approved', '0.00', '1600.00', NULL),
(61, 27, 22, 'REF-27', '2024-11-06 15:11:46', 'Approved', '400.00', '0.00', NULL),
(62, 27, 6, 'REF-27', '2024-11-06 15:11:46', 'Approved', '0.00', '400.00', NULL),
(63, 28, 24, 'REF-28', '2024-11-06 15:12:39', 'Approved', '130.00', '0.00', NULL),
(64, 28, 6, 'REF-28', '2024-11-06 15:12:39', 'Approved', '0.00', '130.00', NULL),
(65, 29, 25, 'REF-29', '2024-11-06 15:13:08', 'Approved', '200.00', '0.00', NULL),
(66, 29, 6, 'REF-29', '2024-11-06 15:13:08', 'Approved', '0.00', '200.00', NULL),
(67, 30, 6, 'REF-30', '2024-11-06 15:13:38', 'Approved', '2050.00', '0.00', NULL),
(68, 30, 18, 'REF-30', '2024-11-06 15:13:38', 'Approved', '0.00', '2050.00', NULL),
(69, 31, 7, 'REF-31', '2024-11-06 15:14:13', 'Approved', '1000.00', '0.00', NULL),
(70, 31, 18, 'REF-31', '2024-11-06 15:14:13', 'Approved', '0.00', '1000.00', NULL),
(71, 32, 23, 'REF-32', '2024-11-06 15:14:52', 'Approved', '4500.00', '0.00', NULL),
(72, 32, 6, 'REF-32', '2024-11-06 15:14:52', 'Approved', '0.00', '4500.00', NULL),
(73, 33, 28, 'REF-33', '2024-11-13 21:59:42', 'Approved', '100.00', '0.00', NULL),
(74, 33, 6, 'REF-33', '2024-11-13 21:59:42', 'Approved', '0.00', '100.00', NULL),
(75, 37, 19, 'REF-37', '2024-11-18 16:23:43', 'Approved', '150.00', '0.00', NULL),
(76, 37, 9, 'REF-37', '2024-11-18 16:23:43', 'Approved', '0.00', '150.00', NULL),
(77, 39, 20, 'REF-39', '2024-11-18 16:24:56', 'Approved', '500.00', '0.00', NULL),
(78, 39, 13, 'REF-39', '2024-11-18 16:24:56', 'Approved', '0.00', '500.00', NULL),
(79, 40, 22, 'REF-40', '2024-11-18 16:25:38', 'Approved', '980.00', '0.00', NULL),
(80, 40, 10, 'REF-40', '2024-11-18 16:25:38', 'Approved', '0.00', '980.00', NULL),
(81, 44, 23, 'REF-44', '2024-11-18 16:28:20', 'Approved', '20.00', '0.00', NULL),
(82, 44, 15, 'REF-44', '2024-11-18 16:28:20', 'Approved', '0.00', '20.00', NULL),
(83, 43, 27, 'REF-43', '2024-11-18 16:27:32', 'Approved', '2000.00', '0.00', NULL),
(84, 43, 18, 'REF-43', '2024-11-18 16:27:32', 'Approved', '0.00', '2000.00', NULL),
(85, 45, 21, 'REF-45', '2024-11-18 16:29:14', 'Approved', '1500.00', '0.00', NULL),
(86, 45, 8, 'REF-45', '2024-11-18 16:29:14', 'Approved', '0.00', '1500.00', NULL);

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
(2, 'Manager', 'jportier1024', '$2y$10$bakzVAt/29L1hWBKv6bPPO6OHCBQC6JQYVHC/oFrVUul65hIb5fyK', 'jhportier@gmail.com', '2000-05-30', 'Julia', 'lol', '3335 gold mist dr buford Ga', 'q1', 'sam', 0, NULL, '2024-10-02 15:21:52', '2024-12-01 17:32:09', NULL, 1, 1, NULL, 30),
(3, 'Manager', 'lportier1024', '$2y$10$4NzCCJQQ6WVtJ7jBK9wOReFYsVPnhQZxRhl6li2b1yXGqLYK/bUJu', 'laportier@gmail.com', '2001-01-29', 'Luke', 'Portier', '3335 gold mist dr buford Ga', 'q1', 'sam', 0, NULL, '2024-10-02 15:30:44', '2024-10-09 14:59:09', NULL, 1, 1, NULL, 30),
(4, 'Accountant', 'jbond1024', '$2y$10$qSCFEsxh4nEibSxleO3pseiWmT.8UYmxWr.vY2My4UAV3fuLTBsNq', 'jbond@gmail.com', '1988-06-15', 'James', 'Bond', 'somewhere', 'q1', 'sam', 1, NULL, '2024-10-02 15:32:37', '2024-10-23 12:39:41', NULL, 1, 1, NULL, 30),
(13, 'Accountant', 'nfrom1024', '$2y$10$1ysk7NY4nOGvA1iZfos6je3J1pDPeCGo77KEKfU7dw1mFNDneL2yu', 'nfrom@gmail.com', '2003-06-10', 'Nic', 'From', '1234 cool street rd sw coolville', 'q1', 'cat', 0, NULL, '2024-10-23 13:07:31', '2024-10-23 13:11:01', NULL, 1, 1, NULL, NULL),
(14, 'Accountant', 'brobin1224', '$2y$10$KurQQ1GuZug6T1iisYNH4OHnt9jlCnmHH1diS/L9QcWE2oLznaxWu', 'batmanrobin@gmail.com', '2024-12-16', 'batman', 'robin', '1234 james town', 'q1', 'sam', 0, NULL, '2024-12-01 17:16:47', '2024-12-01 17:17:32', NULL, 1, 1, NULL, NULL),
(15, 'Accountant', 'cllama1224', '$2y$10$5kSCltRD5dUo/WUGvSwHG.aZPBbOvdVF4LCDN1Jibpiuf2sICYGs.', 'bdportier@gmail.com', '2024-07-09', 'carl', 'hat', '1234 i am in my house dr ', 'q1', 'sam', 0, NULL, '2024-12-01 17:22:37', '2024-12-01 17:31:10', NULL, 1, 1, NULL, 10),
(16, 'Accountant', 'rcat1224', '$2y$10$xhNtDFZpUeF9VLPLQTjdAOq2lg0NJ1vuUx7QEao7gjtpq3rl6SZ6e', 'bdportier@gmail.com', '2024-03-20', 'roger', 'dog', '1234 hats off to the bull road', 'q1', 'sam', 0, NULL, '2024-12-01 17:30:17', '2024-12-01 17:32:23', NULL, 0, 0, NULL, 10),
(17, 'Accountant', 'yportier1224', '$2y$10$IBRlv2dwMDPi4u2kidYVCeVJJYlTJYqLB.HURVF/Ak.sjX5eNNugO', 'bdportier@gmail.com', '2024-09-18', 'yoda', 'portier', '1243 cheese rd', 'q1', 'sam', 0, NULL, '2024-12-01 17:35:07', '2024-12-01 17:36:19', NULL, 1, 1, NULL, NULL),
(18, 'Accountant', 'thawk1224', '$2y$10$8VNOKlqlyG3qfRcNbaRmXu6MLdWVGf.898E65mIr6HplBbSJ3gTnW', 'bdportier@gmail.com', '2024-05-15', 'Tony', 'Hawk', '4321 happy rd ', 'q1', 'sam', 0, NULL, '2024-12-01 18:04:09', '2024-12-01 18:06:12', NULL, 1, 1, NULL, NULL),
(19, 'Accountant', 'jhappy1224', '$2y$10$DaOuZS4sUsN2BQqgzOtj1.ccVhSePYM4t6B.tdPqA4pkXuZML8RfS', 'bdportier@gmail.com', '2024-05-22', 'joker', 'happy', '1234 lets have fun dr', 'q1', 'sam', 0, NULL, '2024-12-01 18:20:42', '2024-12-01 18:23:15', NULL, 1, 1, NULL, NULL);

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
(5, 1, 'bportier1024', 'Cash', 'Asset', 'Normal side changed from: debit', 'New normal side: credit', 'Client Account Updated', '2024-10-23 17:25:52'),
(6, 1, 'bportier1024', 'Cash', 'Asset', 'Initial balance changed from: 10010.00', 'New initial balance: 0.00', 'Client Account Updated', '2024-11-03 17:34:59'),
(7, 1, 'bportier1024', 'Cash', 'Asset', 'Normal side changed from: credit', 'New normal side: debit', 'Client Account Updated', '2024-11-03 17:37:48'),
(8, 14, NULL, 'Accountant', 'User Accounts', 'N/A', 'brobin1224', 'Created', '2024-12-01 17:16:47'),
(9, 15, NULL, 'Accountant', 'User Accounts', 'N/A', 'cllama1224', 'Created', '2024-12-01 17:22:37'),
(10, 16, NULL, 'Accountant', 'User Accounts', 'N/A', 'rcat1224', 'Created', '2024-12-01 17:30:17'),
(11, 17, NULL, 'Accountant', 'User Accounts', 'N/A', 'yportier1224', 'Created', '2024-12-01 17:35:07'),
(12, 18, NULL, 'Accountant', 'User Accounts', 'N/A', 'thawk1224', 'Created', '2024-12-01 18:04:09'),
(13, 19, NULL, 'Accountant', 'User Accounts', 'N/A', 'jhappy1224', 'Created', '2024-12-01 18:20:42');

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
-- Indexes for table `ErrorCodes`
--
ALTER TABLE `ErrorCodes`
  ADD PRIMARY KEY (`Error_Code_ID`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Journal_Entry_Lines`
--
ALTER TABLE `Journal_Entry_Lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_id` (`journal_entry_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `expiredpasswords`
--
ALTER TABLE `expiredpasswords`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Journal_Entries`
--
ALTER TABLE `Journal_Entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `Journal_Entry_Lines`
--
ALTER TABLE `Journal_Entry_Lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `Ledger_Transactions`
--
ALTER TABLE `Ledger_Transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `table1`
--
ALTER TABLE `table1`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `usersuspensions`
--
ALTER TABLE `usersuspensions`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_eventlog`
--
ALTER TABLE `user_eventlog`
  MODIFY `EventID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_roster`
--
ALTER TABLE `user_roster`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Journal_Entry_Lines`
--
ALTER TABLE `Journal_Entry_Lines`
  ADD CONSTRAINT `journal_entry_lines_ibfk_1` FOREIGN KEY (`journal_entry_id`) REFERENCES `Journal_Entries` (`id`) ON DELETE CASCADE;

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
