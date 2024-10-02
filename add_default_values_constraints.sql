-- Modify DateOfBirth default value
ALTER TABLE Table1 
MODIFY DateOfBirth DATE DEFAULT CURRENT_DATE;

-- Check Constraints
ALTER TABLE Table1 
ADD CONSTRAINT CHK_DateOfBirth CHECK (DateOfBirth <= CURRENT_DATE());
