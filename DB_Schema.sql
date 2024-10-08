-- Create UserTypeTable
CREATE TABLE UserTypeTable (
    UserTypeId INT NOT NULL PRIMARY KEY,
    Description VARCHAR(255) NOT NULL
);

-- Insert data into UserTypeTable
INSERT INTO UserTypeTable (UserTypeId, Description)
VALUES 
    (1, 'Administrator'),
    (2, 'Manager'),
    (3, 'Accountant'),
    (4, 'User (Client)');


-- Create Table for Udsers formerly Table1
CREATE TABLE EmployeeAccounts (
   Id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
   UserTypeId CHAR(10) NOT NULL,
   Username VARCHAR(50) NOT NULL,
   Password VARCHAR(255) NOT NULL,
   EmailAddress VARCHAR(100) NOT NULL,
   DateOfBirth DATE NOT NULL,
   FirstName VARCHAR(50) NOT NULL,
   LastName VARCHAR(50) NOT NULL,
   Address VARCHAR(255) NOT NULL,
   SecurityQuestions CHAR(10) NULL,
   SecurityAnswers CHAR(10) NULL,
   FailedAttempts INT DEFAULT 0,
   LockoutUntil DATETIME NULL,
   CreatedDate DATETIME DEFAULT CURRENT_TIMESTAMP,
   ModifiedDate DATETIME ON UPDATE CURRENT_TIMESTAMP,
   ModifiedBy VARCHAR(50),
   IsActive BOOLEAN DEFAULT 0
);

-- Hashed password1 for user 1 and user 6: $2y$10$SKXO3TzwiR2A6CkkaaHUcetqi4S5mOFc/h.dIafbIiYBh0an4HeCm
-- Hashed password2 for user 2 and user 7: $2y$10$PbtJ8UH9jWnvU62qXHG48.qHzbrFvq1JY7KdBSbl9FgLpMSv0rfey
-- Hashed password3 for user 3 and user 8: $2y$10$fpkTbB1k.mSQaEDmWiXNReSpS1fh2URaiy2Y5igHos2aHI3eFS/N6
-- Hashed password4 for user 4 and user 9: $2y$10$gX6gbVy3/IrAg82LTlNJMOSYnKM4Y8AU/K1pYMf6/bb6lnXbxrjge
-- Hashed password5 for user 5 and user 10: $2y$10$oMBBc9fdYJHKkKiuzoIB../FI43m.tDKu98CI97af2XUSinxPFn7a

-- Insert sample users into EmployeeAccounts formerly Table1
INSERT INTO EmployeeAccounts (UserTypeId, Username, Password, EmailAddress, DateOfBirth, FirstName, LastName, Address)
VALUES
('1', 'johndoe', '$2y$10$SKXO3TzwiR2A6CkkaaHUcetqi4S5mOFc/h.dIafbIiYBh0an4HeCm', 'johndoe@example.com', '1990-01-01', 'John', 'Doe', '123 Main St'),
('1', 'janedoe', '$2y$10$PbtJ8UH9jWnvU62qXHG48.qHzbrFvq1JY7KdBSbl9FgLpMSv0rfey', 'janedoe@example.com', '1992-02-02', 'Jane', 'Doe', '124 Main St'),
('2', 'alexsmith', '$2y$10$fpkTbB1k.mSQaEDmWiXNReSpS1fh2URaiy2Y5igHos2aHI3eFS/N6', 'alexsmith@example.com', '1994-03-03', 'Alex', 'Smith', '125 Main St'),
('2', 'maryjohnson', '$2y$10$gX6gbVy3/IrAg82LTlNJMOSYnKM4Y8AU/K1pYMf6/bb6lnXbxrjge', 'maryjohnson@example.com', '1996-04-04', 'Mary', 'Johnson', '126 Main St'),
('3', 'davidbrown', '$2y$10$oMBBc9fdYJHKkKiuzoIB../FI43m.tDKu98CI97af2XUSinxPFn7a', 'davidbrown@example.com', '1998-05-05', 'David', 'Brown', '127 Main St'),
('3', 'michaelwhite', '$2y$10$SKXO3TzwiR2A6CkkaaHUcetqi4S5mOFc/h.dIafbIiYBh0an4HeCm', 'michaelwhite@example.com', '1995-06-06', 'Michael', 'White', '128 Main St'),
('3', 'sarahconnor', '$2y$10$gX6gbVy3/IrAg82LTlNJMOSYnKM4Y8AU/K1pYMf6/bb6lnXbxrjge', 'sarahconnor@example.com', '1997-07-07', 'Sarah', 'Connor', '129 Main St'),
('4', 'robertgreen', '$2y$10$oMBBc9fdYJHKkKiuzoIB../FI43m.tDKu98CI97af2XUSinxPFn7a', 'robertgreen@example.com', '1999-08-08', 'Robert', 'Green', '130 Main St'),
('4', 'emilydavis', '$2y$10$hT7uOQ1N4QZlJdOa0tC7V.OH8Zs9Wn3Wk6K1wD9A5x4yC4K3y9F7C', 'emilydavis@example.com', '2000-09-09', 'Emily', 'Davis', '131 Main St');

-- Set the first 3 accounts to active
UPDATE EmployeeAccounts 
SET IsActive = 1 
WHERE Id = 1 OR Id = 2 OR Id = 3 OR Id = 4;




-- Create ExpiredPasswords table
CREATE TABLE ExpiredPasswords (
    Id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    UserId INT NOT NULL,  -- Add UserId to link to Table1
    Username VARCHAR(50) NOT NULL,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    ExpiredPassword VARCHAR(255) NOT NULL,
    DateExpired DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserId) REFERENCES EmployeeAccounts(Id)  -- Foreign key referencing Table1
);


-- Insert expired passwords into ExpiredPasswords
INSERT INTO ExpiredPasswords (UserId, Username, FirstName, LastName, ExpiredPassword, DateExpired) VALUES
(1, 'johndoe', 'John', 'Doe', '****321', '2024-09-01'),
(2, 'janedoe', 'Jane', 'Doe', 'abcd1234', '2024-08-15'),
(3, 'alexsmith', 'Alex', 'Smith', 'pass1234', '2024-09-10'),
(4, 'maryjohnson', 'Mary', 'Johnson', 'mypassword', '2024-09-20'),
(5, 'davidbrown', 'David', 'Brown', 'password123', '2024-09-25');


CREATE TABLE IT_TicketsTable (
    TicketID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Issue TEXT NOT NULL,
    Priority ENUM('low', 'medium', 'high') NOT NULL,
    UserId INT,  -- Change from VARCHAR(10) to INT
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserId) REFERENCES EmployeeAccounts(Id)  -- Optional foreign key constraint
);

-- Create a table to store client accounts
CREATE TABLE Client_Accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(20) NOT NULL,
    account_description TEXT,
    normal_side ENUM('debit', 'credit') NOT NULL,
    account_category VARCHAR(50),
    account_subcategory VARCHAR(50),
    initial_balance DECIMAL(10, 2),
    debit DECIMAL(10, 2) DEFAULT 0,
    credit DECIMAL(10, 2) DEFAULT 0,
    balance DECIMAL(10, 2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    modified_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Track modification time
    ModifiedBy VARCHAR(255), -- Field to track who modified the record
    user_id INT,
    account_order VARCHAR(10),
    statement ENUM('IS', 'BS', 'RE'),
    comment TEXT,
    active BOOLEAN DEFAULT TRUE
);


-- Insert sample data into Client_Accounts
INSERT INTO Client_Accounts (
    account_name, 
    account_number, 
    account_description, 
    normal_side, 
    account_category, 
    account_subcategory, 
    initial_balance, 
    debit, 
    credit, 
    balance, 
    user_id, 
    account_order, 
    statement, 
    comment
) VALUES
('Cash', '1001', 'Cash account for daily transactions', 'debit', 'Asset', 'Current Assets', 5000.00, 0.00, 0.00, 5000.00, 1, '01', 'BS', 'Main cash account'),
('Accounts Receivable', '1002', 'Money owed by customers', 'debit', 'Asset', 'Current Assets', 3000.00, 0.00, 0.00, 3000.00, 1, '02', 'BS', 'Customer payments pending'),
('Inventory', '1003', 'Goods available for sale', 'debit', 'Asset', 'Current Assets', 8000.00, 0.00, 0.00, 8000.00, 1, '03', 'BS', 'Stock of products'),
('Accounts Payable', '2001', 'Money owed to suppliers', 'credit', 'Liability', 'Current Liabilities', 2000.00, 0.00, 0.00, 2000.00, 1, '04', 'BS', 'Outstanding supplier invoices'),
('Owner Equity', '3001', 'Owners investment in the business', 'credit', 'Equity', 'Owner Equity', 10000.00, 0.00, 0.00, 10000.00, 1, '05', 'RE', 'Capital investment by owner');
