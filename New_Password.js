document.getElementById('first_name').addEventListener('input', generateUsername);
document.getElementById('last_name').addEventListener('input', generateUsername);

function generateUsername() {
    var firstName = document.getElementById('first_name').value;
    var lastName = document.getElementById('last_name').value;

    // Get today's date
    var today = new Date();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var year = today.getFullYear().toString().slice(-2);

    // Generate username
    if (firstName && lastName) {
        var username = firstName.charAt(0) + lastName + month + year;
        username = username.toLowerCase(); // Convert to lowercase
        document.getElementById('username').value = username; // Display generated username
        document.getElementById('generatedUsername').value = username; // Store in hidden field

        // Show feedback message
        document.getElementById('usernameFeedback').style.display = 'block';
    } else {
        document.getElementById('username').value = ""; // Clear username if fields are empty
        document.getElementById('generatedUsername').value = ""; // Clear hidden field
        document.getElementById('usernameFeedback').style.display = 'none'; // Hide feedback
    }
}

// Password validation
document.getElementById('password').addEventListener('input', function () {
    var password = this.value;

    // Password requirements
    var lengthRequirement = document.getElementById('lengthRequirement');
    var letterRequirement = document.getElementById('letterRequirement');
    var numberRequirement = document.getElementById('numberRequirement');
    var specialRequirement = document.getElementById('specialRequirement');

    // Minimum 8 characters
    if (password.length >= 8) {
        lengthRequirement.classList.remove('invalid');
        lengthRequirement.classList.add('valid');
    } else {
        lengthRequirement.classList.remove('valid');
        lengthRequirement.classList.add('invalid');
    }

    // Starts with a letter
    if (/^[A-Za-z]/.test(password)) {
        letterRequirement.classList.remove('invalid');
        letterRequirement.classList.add('valid');
    } else {
        letterRequirement.classList.remove('valid');
        letterRequirement.classList.add('invalid');
    }

    // Contains a number
    if (/\d/.test(password)) {
        numberRequirement.classList.remove('invalid');
        numberRequirement.classList.add('valid');
    } else {
        numberRequirement.classList.remove('valid');
        numberRequirement.classList.add('invalid');
    }

    // Contains a special character
    if (/[\W_]/.test(password)) {
        specialRequirement.classList.remove('invalid');
        specialRequirement.classList.add('valid');
    } else {
        specialRequirement.classList.remove('valid');
        specialRequirement.classList.add('invalid');
    }
});

// Password confirmation
document.getElementById('createUserForm').addEventListener('submit', function (event) {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;

    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        event.preventDefault(); // Prevent form submission
    }
});
