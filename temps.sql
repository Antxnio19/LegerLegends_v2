ISELECT Id, Username FROM Table1;


INSERT INTO ExpiredPasswords (UserId, Username, FirstName, LastName, ExpiredPassword, DateExpired) VALUES
(1, 'johndoe', 'John', 'Doe', '****321', '2024-09-01'),
(2, 'janedoe', 'Jane', 'Doe', 'abcd1234', '2024-08-15'),
(3, 'alexsmith', 'Alex', 'Smith', 'pass1234', '2024-09-10'),
(4, 'maryjohnson', 'Mary', 'Johnson', 'mypassword', '2024-09-20'),
(5, 'davidbrown', 'David', 'Brown', 'password123', '2024-09-25');
