document.addEventListener('DOMContentLoaded', () => {
    const approveChecks = document.querySelectorAll('.approve-check');
    const statusCells = document.querySelectorAll('.status');
    const suspendBtns = document.querySelectorAll('.suspend-btn');
    const suspendModal = document.getElementById('suspend-modal');
    const closeBtn = document.querySelector('.close-btn');
    const confirmSuspendBtn = document.getElementById('confirm-suspend');
    const suspendDurationInput = document.getElementById('suspend-duration');
    let selectedRow;

    // Update status based on checkmark change
    approveChecks.forEach((check, index) => {
        check.addEventListener('change', () => {
            const statusCell = statusCells[index];
            if (check.checked) {
                statusCell.textContent = 'Yes';
            } else {
                statusCell.textContent = 'No';
            }
        });
    });

    // Show suspend modal when suspend button is clicked
    suspendBtns.forEach(btn => {
        btn.addEventListener('click', (event) => {
            selectedRow = event.target.closest('tr');
            suspendModal.style.display = 'block';
        });
    });

    // Close modal when close button or outside modal is clicked
    closeBtn.addEventListener('click', () => {
        suspendModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === suspendModal) {
            suspendModal.style.display = 'none';
        }
    });

    // Confirm suspend action
    confirmSuspendBtn.addEventListener('click', () => {
        const suspendDuration = suspendDurationInput.value;
        if (suspendDuration) {
            // Update the selected row
            const row = selectedRow;
            const statusCell = row.querySelector('.status');
            const checkCell = row.querySelector('.approve-check');
            
            // Set the status to 'No' and keep the checkmark checked
            statusCell.textContent = 'No';
            checkCell.checked = true;

            alert(`User suspended for ${suspendDuration} days.`);
            suspendModal.style.display = 'none';
        } else {
            alert('Please enter a duration.');
        }
    });
});
