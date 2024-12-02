document.getElementById('first_name').addEventListener('input', generateUsername);
document.getElementById('last_name').addEventListener('input', generateUsername);

function generateUsername() {
    var firstName = document.getElementById('first_name').value;
    var lastName = document.getElementById('last_name').value;

    var today = new Date();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var year = today.getFullYear().toString().slice(-2);

    if (firstName && lastName) {
        var username = firstName.charAt(0) + lastName + month + year;
        username = username.toLowerCase(); 
        document.getElementById('username').value = username; 
        document.getElementById('generatedUsername').value = username; 

        document.getElementById('usernameFeedback').style.display = 'block';
    } else {
        document.getElementById('username').value = ""; 
        document.getElementById('generatedUsername').value = ""; 
        document.getElementById('usernameFeedback').style.display = 'none'; 
    }
}

// Password validation
document.getElementById('password').addEventListener('input', function () {
    var password = this.value;

    var lengthRequirement = document.getElementById('lengthRequirement');
    var letterRequirement = document.getElementById('letterRequirement');
    var numberRequirement = document.getElementById('numberRequirement');
    var specialRequirement = document.getElementById('specialRequirement');

    if (password.length >= 8) {
        lengthRequirement.classList.remove('invalid');
        lengthRequirement.classList.add('valid');
    } else {
        lengthRequirement.classList.remove('valid');
        lengthRequirement.classList.add('invalid');
    }

    if (/^[A-Za-z]/.test(password)) {
        letterRequirement.classList.remove('invalid');
        letterRequirement.classList.add('valid');
    } else {
        letterRequirement.classList.remove('valid');
        letterRequirement.classList.add('invalid');
    }

    if (/\d/.test(password)) {
        numberRequirement.classList.remove('invalid');
        numberRequirement.classList.add('valid');
    } else {
        numberRequirement.classList.remove('valid');
        numberRequirement.classList.add('invalid');
    }

    if (/[\W_]/.test(password)) {
        specialRequirement.classList.remove('invalid');
        specialRequirement.classList.add('valid');
    } else {
        specialRequirement.classList.remove('valid');
        specialRequirement.classList.add('invalid');
    }
});

document.getElementById('createUserForm').addEventListener('submit', function (event) {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;

    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        event.preventDefault(); 
    }
});
