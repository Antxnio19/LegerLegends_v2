    // Store the original list of accounts
    const accounts = [
        "Cash",
        "Accounts Receivable",
        "Supplies (Specialty Items)",
        "Prepaid Insurance",
        "Prepaid Rent",
        "Office Equipment",
        "Store Equipment",
        "Accumulated Depreciation",
        "Accounts Payable",
        "Wages Payable",
        "Unearned Subscription Revenue",
        "Unearned Service/Ticket Revenue",
        "Unearned Repair Fees",
        "Retained Earnings",
        "Service Fees",
        "Wages Expense",
        "Salaries Expense",
        "Advertising Expense",
        "Store Supplies Expense",
        "Rent Expense",
        "Telephone Expense",
        "Electricity Expense",
        "Utilities Expense",
        "Insurance Expense",
        "Depreciation Expense"
    ];



    function addDebitField() {
        const container = document.getElementById('debit-container');
        const newDebitDiv = document.createElement('div');

        const newInput = document.createElement('input');
        newInput.type = 'number';
        newInput.name = 'debit[]';
        newInput.placeholder = 'Debit';
        newInput.step = '0.01';
        newInput.required = true;

        const accountSelect = document.createElement('select');
        accountSelect.name = 'account_debit_select[]';
        accountSelect.required = true;

        // Create a default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Account';
        accountSelect.appendChild(defaultOption);

        // Add all accounts to the select
        accounts.forEach(account => {
            const option = document.createElement('option');
            option.value = account;
            option.textContent = account;
            accountSelect.appendChild(option);
        });

        accountSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            if (selectedValue) {
                // Remove the selected value from all other select elements
                const all_debit_Selects = document.querySelectorAll('select[name="account_debit_select[]"]');
                all_debit_Selects.forEach(select => {
                    if (select !== this) {
                        // Remove the selected option from other selects
                        Array.from(select.options).forEach(option => {
                            if (option.value === selectedValue) {
                                option.remove();
                            }
                        });
                    }
                });
            }

            // Reset other dropdowns to include all accounts again
            allSelects.forEach(select => {
                if (select !== this) {
                    resetSelectOptions(select);
                }
            });
        });

        newDebitDiv.appendChild(newInput);
        newDebitDiv.appendChild(accountSelect);
        container.appendChild(newDebitDiv);
    }

    function addCreditField() {
        const container = document.getElementById('credit-container');
        const newCreditDiv = document.createElement('div');

        const newInput = document.createElement('input');
        newInput.type = 'number';
        newInput.name = 'credit[]';
        newInput.placeholder = 'Credit';
        newInput.step = '0.01';
        newInput.required = true;

        const accountSelect = document.createElement('select');
        accountSelect.name = 'account_credit_select[]';
        accountSelect.required = true;

        // Create a default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Account';
        accountSelect.appendChild(defaultOption);

        // Add all accounts to the select
        accounts.forEach(account => {
            const option = document.createElement('option');
            option.value = account;
            option.textContent = account;
            accountSelect.appendChild(option);
        });

        accountSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            if (selectedValue) {
                // Remove the selected value from all other select elements
                const allSelects = document.querySelectorAll('select[name="account_credit_select[]"]');
                allSelects.forEach(select => {
                    if (select !== this) {
                        // Remove the selected option from other selects
                        Array.from(select.options).forEach(option => {
                            if (option.value === selectedValue) {
                                option.remove();
                            }
                        });
                    }
                });
            }

            // Reset other dropdowns to include all accounts again
            allSelects.forEach(select => {
                if (select !== this) {
                    resetSelectOptions(select);
                }
            });
        });

        newCreditDiv.appendChild(newInput);
        newCreditDiv.appendChild(accountSelect);
        container.appendChild(newCreditDiv);
    }


    function resetSelectOptions(select) {
        // Clear current options
        select.innerHTML = '';
        
        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Account';
        select.appendChild(defaultOption);

        // Add all accounts to the select
        accounts.forEach(account => {
            const option = document.createElement('option');
            option.value = account;
            option.textContent = account;
            select.appendChild(option);
        });
    }
