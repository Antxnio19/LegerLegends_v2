CREATE TABLE accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    account_name VARCHAR(255) NOT NULL,
    account_number VARCHAR(50) NOT NULL UNIQUE,
    account_description TEXT,
    normal_side ENUM('debit', 'credit') NOT NULL,
    account_order VARCHAR(10),
    statement ENUM('IS', 'BS', 'RE'),
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    comment TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE account_balances (
    balance_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT,
    initial_balance DECIMAL(15, 2) DEFAULT 0.00,
    debit DECIMAL(15, 2) DEFAULT 0.00,
    credit DECIMAL(15, 2) DEFAULT 0.00,
    balance DECIMAL(15, 2) GENERATED ALWAYS AS (initial_balance + debit - credit) STORED,
    FOREIGN KEY (account_id) REFERENCES accounts(account_id)
);

CREATE TABLE account_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    account_category VARCHAR(100),
    account_subcategory VARCHAR(100)
);