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

-- Hashed password for user 1 and user 6: $2y$10$SKXO3TzwiR2A6CkkaaHUcetqi4S5mOFc/h.dIafbIiYBh0an4HeCm
-- Hashed password for user 2 and user 7: $2y$10$PbtJ8UH9jWnvU62qXHG48.qHzbrFvq1JY7KdBSbl9FgLpMSv0rfey
-- Hashed password for user 3 and user 8: $2y$10$fpkTbB1k.mSQaEDmWiXNReSpS1fh2URaiy2Y5igHos2aHI3eFS/N6
-- Hashed password for user 4 and user 9: $2y$10$gX6gbVy3/IrAg82LTlNJMOSYnKM4Y8AU/K1pYMf6/bb6lnXbxrjge
-- Hashed password for user 5 and user 10: $2y$10$oMBBc9fdYJHKkKiuzoIB../FI43m.tDKu98CI97af2XUSinxPFn7a

-- Insert sample users into EmployeeAccounts formerly Table1
INSERT INTO EmployeeAccounts (UserTypeId, Username, Password, EmailAddress, DateOfBirth, FirstName, LastName, Address)
VALUES
('1', 'johndoe', '$2y$10$SKXO3TzwiR2A6CkkaaHUcetqi4S5mOFc/h.dIafbIiYBh0an4HeCm', 'johndoe@example.com', '1990-01-01', 'John', 'Doe', '123 Main St'),
('1', 'janedoe', '$2y$10$PbtJ8UH9jWnvU62qXHG48.qHzbrFvq1JY7KdBSbl9FgLpMSv0rfey', 'janedoe@example.com', '1992-02-02', 'Jane', 'Doe', '124 Main St'),
('2', 'alexsmith', '$2y$10$fpkTbB1k.mSQaEDmWiXNReSpS1fh2URaiy2Y5igHos2aHI3eFS/N6', 'alexsmith@example.com', '1994-03-03', 'Alex', 'Smith', '125 Main St'),
('2', 'maryjohnson', '$2y$10$gX6gbVy3/IrAg82LTlNJMOSYnKM4Y8AU/K1pYMf6/bb6lnXbxrjge', 'maryjohnson@example.com', '1996-04-04', 'Mary', 'Johnson', '126 Main St'),
('3', 'davidbrown', '$2y$10$oMBBc9fdYJHKkKiuzoIB../FI43m.tDKu98CI97af2XUSinxPFn7a', 'davidbrown@example.com', '1998-05-05', 'David', 'Brown', '127 Main St');
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
