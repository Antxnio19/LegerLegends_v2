-- Unique Constraints
ALTER TABLE Table1
ADD CONSTRAINT UQ_Username UNIQUE (Username);

ALTER TABLE Table1
ADD CONSTRAINT UQ_EmailAddress UNIQUE (EmailAddress);

-- Indexes
CREATE INDEX IX_Username ON Table1 (Username);
CREATE INDEX IX_EmailAddress ON Table1 (EmailAddress);

-- Foreign Key Constraints
ALTER TABLE Table1
ADD CONSTRAINT FK_UserType FOREIGN KEY (UserTypeId) REFERENCES UserTypeTable(UserTypeId);
