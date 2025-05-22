<?php
// config.php ou session_init.php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/projet/index.php', // doit être le même sur tout le projet
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => false, // true si HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['cart_count'] = 0;
}
?>