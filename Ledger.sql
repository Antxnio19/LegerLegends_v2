-- Drop the existing Ledger_Transactions table if it exists
DROP TABLE IF EXISTS Ledger_Transactions;

-- Create the Ledger_Transactions table
CREATE TABLE Ledger_Transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT, -- Foreign key to Client_Accounts
    transaction_date DATETIME DEFAULT CURRENT_TIMESTAMP, -- Date of transaction
    reference_number VARCHAR(50), -- Unique reference number for the transaction
    description TEXT, -- Description of the transaction
    debit DECIMAL(10, 2) DEFAULT 0, -- Debit amount
    credit DECIMAL(10, 2) DEFAULT 0, -- Credit amount
    balance DECIMAL(10, 2), -- Balance after the transaction
    FOREIGN KEY (account_id) REFERENCES Client_Accounts(id)
);

-- Insert sample transactions into Ledger_Transactions for Cash account
INSERT INTO Ledger_Transactions (
    account_id, 
    transaction_date, 
    reference_number, 
    description, 
    debit, 
    credit, 
    balance
) VALUES
(1, '2024-10-01 09:00:00', 'REF001', 'Initial deposit', 5000.00, 0.00, 5000.00),
(1, '2024-10-02 10:30:00', 'REF002', 'Payment received from customer', 2000.00, 0.00, 7000.00),
(1, '2024-10-03 14:15:00', 'REF003', 'Purchase of supplies', 0.00, 1000.00, 6000.00),
(1, '2024-10-04 16:45:00', 'REF004', 'Cash withdrawal for petty cash', 0.00, 500.00, 5500.00);

-- Insert sample transactions into Ledger_Transactions for Accounts Receivable account
INSERT INTO Ledger_Transactions (
    account_id, 
    transaction_date, 
    reference_number, 
    description, 
    debit, 
    credit, 
    balance
) VALUES
(2, '2024-10-01 09:00:00', 'REF005', 'Initial balance', 3000.00, 0.00, 3000.00),
(2, '2024-10-02 10:00:00', 'REF006', 'Invoice issued to customer', 500.00, 0.00, 3500.00),
(2, '2024-10-05 12:30:00', 'REF007', 'Customer payment received', 0.00, 1500.00, 2000.00);

-- Insert sample transactions into Ledger_Transactions for Inventory account
INSERT INTO Ledger_Transactions (
    account_id, 
    transaction_date, 
    reference_number, 
    description, 
    debit, 
    credit, 
    balance
) VALUES
(3, '2024-10-01 09:00:00', 'REF008', 'Initial stock', 8000.00, 0.00, 8000.00),
(3, '2024-10-04 11:00:00', 'REF009', 'Purchase of new inventory', 3000.00, 0.00, 11000.00),
(3, '2024-10-06 14:00:00', 'REF010', 'Inventory sold to customer', 0.00, 2000.00, 9000.00);

-- Insert sample transactions into Ledger_Transactions for Accounts Payable account
INSERT INTO Ledger_Transactions (
    account_id, 
    transaction_date, 
    reference_number, 
    description, 
    debit, 
    credit, 
    balance
) VALUES
(4, '2024-10-01 09:00:00', 'REF011', 'Initial balance', 0.00, 2000.00, 2000.00),
(4, '2024-10-03 15:30:00', 'REF012', 'Invoice received from supplier', 0.00, 1000.00, 3000.00),
(4, '2024-10-05 17:30:00', 'REF013', 'Payment made to supplier', 500.00, 0.00, 2500.00);

-- Insert sample transactions into Ledger_Transactions for Owner Equity account
INSERT INTO Ledger_Transactions (
    account_id, 
    transaction_date, 
    reference_number, 
    description, 
    debit, 
    credit, 
    balance
) VALUES
(5, '2024-10-01 09:00:00', 'REF014', 'Initial investment by owner', 0.00, 10000.00, 10000.00),
(5, '2024-10-07 13:30:00', 'REF015', 'Owner withdrawal', 0.00, 2000.00, 8000.00);
