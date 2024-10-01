-- Drop Constraints and Indexes if needed
ALTER TABLE Table1 DROP FOREIGN KEY FK_UserType;
ALTER TABLE Table1 DROP CONSTRAINT UQ_Username;
ALTER TABLE Table1 DROP CONSTRAINT UQ_EmailAddress;

DROP INDEX IF EXISTS IX_Username ON Table1;
DROP INDEX IF EXISTS IX_EmailAddress ON Table1;

-- Drop Tables
DROP TABLE IF EXISTS Table1;
DROP TABLE IF EXISTS UserTypeTable;
