-- Drop Old Tables to Avoid Conflicts
DROP TABLE IF EXISTS Ledger_Transactions;
DROP TABLE IF EXISTS Journal_Entries;
DROP TABLE IF EXISTS Client_Accounts;

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

-- Create Journal_Entries Table
CREATE TABLE Journal_Entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_account_id INT NOT NULL,
    account_type VARCHAR(50) NOT NULL,
    account_debit VARCHAR(50) NOT NULL,
    account_credit VARCHAR(50) NOT NULL,
    debit DECIMAL(10, 2) DEFAULT 0.00,
    credit DECIMAL(10, 2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ModifiedBy VARCHAR(255),
    IsApproved BOOLEAN DEFAULT 1,
    comment TEXT,
    FOREIGN KEY (client_account_id) REFERENCES Client_Accounts(id) ON DELETE CASCADE
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

-- Trigger Creation
DELIMITER //

CREATE TRIGGER insert_ledger_transaction
AFTER INSERT ON Journal_Entries
FOR EACH ROW
BEGIN
    DECLARE new_balance DECIMAL(10,2);
    
    -- Insert a matching ledger transaction based on the journal entry
    INSERT INTO Ledger_Transactions (
        journal_entry_id, client_account_id, reference_number, 
        transaction_date, description, debit, credit
    ) VALUES (
        NEW.id, NEW.client_account_id, CONCAT('REF-', NEW.id), 
        NEW.created_at, NEW.comment, NEW.debit, NEW.credit
    );

    -- Calculate the new running balance for the client account
    SELECT COALESCE(SUM(debit - credit), 0)
    INTO new_balance
    FROM Ledger_Transactions
    WHERE client_account_id = NEW.client_account_id;

    -- Update the balance in the Client_Accounts table
    UPDATE Client_Accounts
    SET balance = new_balance
    WHERE id = NEW.client_account_id;

    -- Update the balance in the latest ledger transaction
    UPDATE Ledger_Transactions
    SET balance_after = new_balance
    WHERE journal_entry_id = NEW.id;  -- Update balance_after of the newly inserted transaction
END //

DELIMITER ;

-- Insert Sample Data into Client_Accounts
INSERT INTO Client_Accounts (
    account_name, account_number, account_description, normal_side, 
    account_category, account_subcategory, initial_balance, balance, 
    user_id, account_order, statement, comment
) VALUES
('Cash', '1001', 'Cash account for daily transactions', 'debit', 
 'Asset', 'Current Assets', 0.00, 5000.00, 1, '01', 'BS', 'Main cash account'),
('Accounts Receivable', '1002', 'Money owed by customers', 'debit', 
 'Asset', 'Current Assets', 0.00, 3000.00, 1, '02', 'BS', 'Customer payments pending'),
('Inventory', '1003', 'Goods available for sale', 'debit', 
 'Asset', 'Current Assets', 0.00, 8000.00, 1, '03', 'BS', 'Stock of products'),
('Accounts Payable', '2001', 'Money owed to suppliers', 'credit', 
 'Liability', 'Current Liabilities', 0.00, 2000.00, 1, '04', 'BS', 'Outstanding supplier invoices'),
('Owner Equity', '3001', 'Owner’s investment in the business', 'credit', 
 'Equity', 'Owner Equity', 0.00, 10000.00, 1, '05', 'RE', 'Capital investment by owner');

-- Insert Sample Data into Journal_Entries
INSERT INTO Journal_Entries (
    client_account_id, account_type, debit, credit, created_at, ModifiedBy, 
    IsApproved, comment
) VALUES
(1, 'Expense', 150.00, 0.00, '2023-09-15 10:30:00', 'John Doe', 1, 'Purchase of stationery'),
(2, 'Revenue', 0.00, 500.00, '2023-09-16 11:00:00', 'Jane Smith', 1, 'Payment for consulting'),
(3, 'Expense', 75.50, 0.00, '2023-09-17 12:15:00', 'Alex Johnson', 1, 'Monthly electricity bill'),
(1, 'Asset', 1200.00, 0.00, '2023-09-18 09:45:00', 'Mary Lee', 1, 'New laptop purchase'),
(4, 'Liability', 0.00, 300.00, '2023-09-19 14:20:00', 'David Brown', 1, 'Loan repayment');

INSERT INTO Journal_Entries (
    client_account_id, account_type, debit, credit, created_at, ModifiedBy, 
    IsApproved, comment
) VALUES
(1, 'Expense', 60.00, 0.00, NOW(), 'John Doe', 1, 'Test transaction 1');

INSERT INTO Journal_Entries (
    client_account_id, account_type, debit, credit, created_at, ModifiedBy, 
    IsApproved, comment
) VALUES
(1, 'Expense', 200.00, 0.00, NOW(), 'John Doe', 1, 'Test transaction 2');

INSERT INTO Journal_Entries (
    client_account_id, account_type, debit, credit, created_at, ModifiedBy, 
    IsApproved, comment
) VALUES
(2, 'Expense', 500.00, 0.00, NOW(), 'John Doe', 1, 'Test transaction 3');

INSERT INTO Journal_Entries (
    client_account_id, account_type, debit, credit, created_at, ModifiedBy, 
    IsApproved, comment
) VALUES
(4, 'Expense', 300.00, 0.00, NOW(), 'John Doe', 1, 'Test transaction 4');