-- Unique Constraints
ALTER TABLE EmployeeAccounts
ADD CONSTRAINT UQ_Username UNIQUE (Username);

ALTER TABLE EmployeeAccounts
ADD CONSTRAINT UQ_EmailAddress UNIQUE (EmailAddress);

-- Indexes
CREATE INDEX IX_Username ON EmployeeAccounts (Username);
CREATE INDEX IX_EmailAddress ON EmployeeAccounts (EmailAddress);

-- Foreign Key Constraints
ALTER TABLE EmployeeAccounts
ADD CONSTRAINT FK_UserType FOREIGN KEY (UserTypeId) REFERENCES UserTypeTable(UserTypeId);
