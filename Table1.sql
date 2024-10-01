-- Create UserTypeTable
CREATE TABLE UserTypeTable (
    UserTypeId VARCHAR(10) NOT NULL PRIMARY KEY,
    Description VARCHAR(255) NOT NULL
);

-- Create Table1 for Users
CREATE TABLE Table1 (
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

-- Create ExpiredPasswords table
CREATE TABLE ExpiredPasswords (
    Id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    UserId INT NOT NULL,  -- Add UserId to link to Table1
    Username VARCHAR(50) NOT NULL,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    ExpiredPassword VARCHAR(255) NOT NULL,
    DateExpired DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserId) REFERENCES Table1(Id)  -- Foreign key referencing Table1
);

-- Insert sample users into Table1
INSERT INTO Table1 (UserTypeId, Username, Password, EmailAddress, DateOfBirth, FirstName, LastName, Address)
VALUES
('1', 'johndoe', 'password1', 'johndoe@example.com', '1990-01-01', 'John', 'Doe', '123 Main St'),
('1', 'janedoe', 'password2', 'janedoe@example.com', '1992-02-02', 'Jane', 'Doe', '124 Main St'),
('1', 'alexsmith', 'password3', 'alexsmith@example.com', '1994-03-03', 'Alex', 'Smith', '125 Main St'),
('1', 'maryjohnson', 'password4', 'maryjohnson@example.com', '1996-04-04', 'Mary', 'Johnson', '126 Main St'),
('1', 'davidbrown', 'password5', 'davidbrown@example.com', '1998-05-05', 'David', 'Brown', '127 Main St');

-- Insert expired passwords into ExpiredPasswords
INSERT INTO ExpiredPasswords (UserId, Username, FirstName, LastName, ExpiredPassword, DateExpired) VALUES
(1, 'johndoe', 'John', 'Doe', '****321', '2024-09-01'),
(2, 'janedoe', 'Jane', 'Doe', 'abcd1234', '2024-08-15'),
(3, 'alexsmith', 'Alex', 'Smith', 'pass1234', '2024-09-10'),
(4, 'maryjohnson', 'Mary', 'Johnson', 'mypassword', '2024-09-20'),
(5, 'davidbrown', 'David', 'Brown', 'password123', '2024-09-25');
