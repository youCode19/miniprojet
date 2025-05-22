<?php
session_start();

if (isset($_GET['id']) && isset($_SESSION['cart'][$_GET['id']])) {
    unset($_SESSION['cart'][$_GET['id']]);
}

// Optionnel : mettre à jour le compteur du panier
$_SESSION['cart_count'] = count($_SESSION['cart'] ?? []);

// Redirige vers le panier
header('Location: view.php');
exit;
?>
