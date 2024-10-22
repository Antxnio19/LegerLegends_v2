CREATE TABLE journal_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_type VARCHAR(255) NOT NULL,
    account_description VARCHAR(255),
    debit DECIMAL(10, 2) NOT NULL,
    credit DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ModifiedBy VARCHAR(255),
    IsApproved ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    comment TEXT
);
