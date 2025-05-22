<?php
session_start();
define('ROOT_PATH', dirname(path: __DIR__));
require_once __DIR__ . '/../includes/session_init.php';

require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/includes/auth.php'; 
require_once __DIR__ . '/../includes/config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
if(!empty($email) && !empty($password)) {
    // Authentifier l'utilisateur
    $role = authenticateUser($email, $password);

    if ($role== 'admin' || $role == 'superadmin') {
        header('Location: /projet/admin/dashboard.php');
        exit;
    } elseif ($role == 'client') {
        header('Location: /projet/index.php');
        exit;
        } else {
      die("Identifiants invalides ou compte inactif.");
    }
} else {
    die("Veuillez remplir tous les champs.");
}
} else {
    // Si la méthode n'est pas POST, on redirige vers la page d'accueil
    header('Location: /projet/index.php');
    exit;
}
