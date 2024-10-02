<?php
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

$passwords = [
    'password1',
    'password2',
    'password3',
    'password4',
    'password5'
];

$hashedPasswords = array_map('hashPassword', $passwords);

foreach ($hashedPasswords as $index => $hashedPassword) {
    echo "Hashed password for user " . ($index + 1) . ": " . $hashedPassword . "\n";
}
?>

