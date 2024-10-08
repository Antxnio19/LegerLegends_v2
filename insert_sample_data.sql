-- Insert sample data into UserTypeTable
INSERT INTO UserTypeTable (UserTypeId, Description)
VALUES 
    ('ADMIN', 'Administrator'),
    ('USER', 'Regular User');

-- Insert sample data into Table1
INSERT INTO Table1 (Id, UserTypeId, Username, Password, EmailAddress, DateOfBirth, FirstName, LastName, Address)
VALUES 
    (1, 'ADMIN', 'admin1', 'password123', 'admin1@example.com', '1980-01-01', 'Admin', 'User', '123 Admin St'),
    (2, 'USER', 'user1', 'password123', 'user1@example.com', '1990-01-01', 'John', 'Doe', '456 User Ave');
