-- Drop Old Tables to Avoid Conflicts
DROP TABLE IF EXISTS Ledger_Transactions;
DROP TABLE IF EXISTS Journal_Entry_Lines;
DROP TABLE IF EXISTS Journal_Entries;
DROP TABLE IF EXISTS Client_Accounts;
DROP TABLE IF EXISTS ErrorCodes;

-- Create Client_Accounts Table
CREATE TABLE Client_Accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_name VARCHAR(100) NOT NULL,
    account_number VARCHAR(20) NOT NULL UNIQUE,
    account_description TEXT,
    normal_side ENUM('debit', 'credit') NOT NULL,
    account_category VARCHAR(50),
    account_subcategory VARCHAR(50),
    initial_balance DECIMAL(10, 2) DEFAULT 0.00,
    balance DECIMAL(10, 2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    modified_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ModifiedBy VARCHAR(255),
    user_id INT,
    account_order VARCHAR(10),
    statement ENUM('IS', 'BS', 'RE'),
    IsActive BOOLEAN DEFAULT 1,
    comment TEXT
);

CREATE TABLE Journal_Entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entry_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by VARCHAR(50) NOT NULL,
    entry_type ENUM('Normal', 'Adjusted') DEFAULT 'Normal',
    is_approved ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    comment TEXT
);

CREATE TABLE Journal_Entry_Lines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    journal_entry_id INT NOT NULL,
    account VARCHAR(100) NOT NULL,
    account_type ENUM('Asset', 'Liability', 'Revenue', 'Expense') NOT NULL,
    debit DECIMAL(15, 2) DEFAULT 0,
    credit DECIMAL(15, 2) DEFAULT 0,
    FOREIGN KEY (journal_entry_id) REFERENCES Journal_Entries(id) ON DELETE CASCADE
);

-- Create Ledger_Transactions Table
CREATE TABLE Ledger_Transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    journal_entry_id INT NOT NULL,
    client_account_id INT NOT NULL,
    reference_number VARCHAR(50) NOT NULL,
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    description TEXT,
    debit DECIMAL(10, 2) DEFAULT 0.00,
    credit DECIMAL(10, 2) DEFAULT 0.00,
    balance_after DECIMAL(10, 2),
    FOREIGN KEY (journal_entry_id) REFERENCES Journal_Entries(id) ON DELETE CASCADE,
    FOREIGN KEY (client_account_id) REFERENCES Client_Accounts(id) ON DELETE CASCADE
);

-- Create Error Table
CREATE TABLE ErrorCodes (
    Error_Code_ID INT PRIMARY KEY,
    Error_Type VARCHAR(50),
    Error_Message TEXT,
    description TEXT,
    Resolution_Steps TEXT,
    Severity_Level ENUM('Low', 'Medium', 'High', 'Critical'),
    Created_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Modified_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Trigger Creation
DELIMITER //

CREATE TRIGGER update_ledger_transaction
BEFORE UPDATE ON Journal_Entries
FOR EACH ROW 
BEGIN
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

            read_balance_loop: LOOP
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
END //

DELIMITER ;



-- Insert Sample Data into Client_Accounts with Initial Balance as 0
INSERT INTO Client_Accounts (
    account_name, account_number, account_description, normal_side, 
    account_category, account_subcategory, initial_balance, balance, 
    user_id, account_order, statement, comment
) VALUES
('Cash', '1001', 'Cash account for daily transactions', 'debit', 
 'Asset', 'Current Assets', 0.00, 0.00, 1, '01', 'BS', 'Main cash account'),
('Accounts Receivable', '1002', 'Money owed by customers', 'debit', 
 'Asset', 'Current Assets', 0.00, 0.00, 1, '02', 'BS', 'Pending customer payments'),
('Inventory', '1003', 'Goods available for sale', 'debit', 
 'Asset', 'Current Assets', 0.00, 0.00, 1, '03', 'BS', 'Stock of products'),
('Accounts Payable', '2001', 'Money owed to suppliers', 'credit', 
 'Liability', 'Current Liabilities', 0.00, 0.00, 1, '04', 'BS', 'Outstanding invoices'),
('Owner Equity', '3001', 'Owner’s investment in the business', 'credit', 
 'Equity', 'Owner Equity', 0.00, 0.00, 1, '05', 'RE', 'Capital investment');


-- Insert Sample Data into Journal_Entries
INSERT INTO Journal_Entries (created_by, entry_type, is_approved, comment)
VALUES 
('admin', 'Normal', 'Pending', 'Initial funding from owner'),
('admin', 'Adjusted', 'Pending', 'Adjustment for inventory valuation'),
('user1', 'Normal', 'Pending', 'Unsuccessful payment transaction');

-- Insert Sample Data into Journal_Entry_Lines
INSERT INTO Journal_Entry_Lines (journal_entry_id, account, account_type, debit, credit)
VALUES 
(1, 'Cash', 'Asset', 5000.00, 0),                
(1, 'Owner Equity', 'Equity', 0, 5000.00),       
(2, 'Inventory', 'Asset', 300.00, 0),            
(2, 'Accounts Payable', 'Liability', 0, 300.00), 
(3, 'Accounts Receivable', 'Asset', 100.00, 0),  
(3, 'Cash', 'Asset', 0, 100.00);                 

-- Optionally, insert sample data into the ErrorCodes table for testing
INSERT INTO ErrorCodes (Error_Code_ID, Error_Type, Error_Message, description, Resolution_Steps, Severity_Level)
VALUES 
(1001, 'Database', 'Unable to connect to database', 
 'Failed connection to the database server.', 
 'Verify server status and connection details.', 
 'Critical'),
(2001, 'Authentication', 'Invalid username or password', 
 'Login attempt failed due to incorrect credentials.', 
 'Prompt user for correct credentials.', 
 'High'),
(3001, 'Network', 'Network timeout occurred', 
 'Network connection lost while trying to access the server.', 
 'Check network settings and server availability.', 
 'Medium'),
(4001, 'Application', 'Unexpected null value', 
 'An unexpected null value was encountered in the application.', 
 'Check code for null handling.', 
 'Medium'),
(5001, 'Deprecation', 'Feature X is deprecated', 
 'Feature X is no longer supported.', 
 'Inform users to transition to Feature Y.', 
 'Low');
