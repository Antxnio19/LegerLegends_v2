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
        newDebitDiv.className = 'debit-entry';
        newCreditDiv.className = 'credit-entry';



        const accountSelect = document.createElement('select');
        accountSelect.name = 'account_debit_select[]';
        accountSelect.required = true;

        const newInput = document.createElement('input');
        newInput.type = 'number';
        newInput.name = 'debit[]';
        newInput.placeholder = 'Debit';
        newInput.step = '0.01';
        newInput.required = true;

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
            all_debit_Selects.forEach(select => {
                if (select !== this) {
                    resetSelectOptions(select);
                }
            });
        });

        newDebitDiv.appendChild(newInput);
        newDebitDiv.appendChild(accountSelect);
        container.appendChild(newDebitDiv);
    }
   
    function removeDebitField() {
        const container = document.getElementById('debit-container');
        const debitEntries = container.getElementsByClassName('debit-entry');
        if (debitEntries.length > 0) {
            container.removeChild(debitEntries[debitEntries.length - 1]);
        } else {
            alert("No debit fields to remove.");
        }
    }
    

    function addCreditField() {
        const container = document.getElementById('credit-container');
        const newCreditDiv = document.createElement('div');
        newCreditDiv.className = 'credit-entry';



        const accountSelect = document.createElement('select');
        accountSelect.name = 'account_credit_select[]';
        accountSelect.required = true;

        const newInput = document.createElement('input');
        newInput.type = 'number';
        newInput.name = 'credit[]';
        newInput.placeholder = '$ 0.00';
        newInput.step = '0.01';
        newInput.required = true;
       


        newInput.style.textAlign = 'right'; // Right-align the text
        newInput.style.marginLeft = '5px'; // Space between dollar sign and input




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
                const all_credit_Selects = document.querySelectorAll('select[name="account_credit_select[]"]');
                all_credit_Selects.forEach(select => {
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
            all_credit_Selects.forEach(select => {
                if (select !== this) {
                    resetSelectOptions(select);
                }
            });
        });

        newCreditDiv.appendChild(newInput);
        newCreditDiv.appendChild(accountSelect);
        container.appendChild(newCreditDiv);
    }
    function removeCreditField() {
        const container = document.getElementById('credit-container');
        const debitEntries = container.getElementsByClassName('credit-entry');
        if (debitEntries.length > 0) {
            container.removeChild(debitEntries[debitEntries.length - 1]);
        } else {
            alert("No credit fields to remove.");
        }
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
