<?php
function isLoggedIn() {
    return isset($_SESSION['user']);
}

function authenticate($username, $password) {
    // Replace with actual authentication logic
    return $username === 'admin' && $password === 'password';
}
?>