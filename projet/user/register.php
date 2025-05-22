<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/../includes/session_init.php';
require_once ROOT_PATH . '/../includes/db.php';
require_once ROOT_PATH . '/../includes/auth.php';
require_once ROOT_PATH . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $errors = [];

    if (strlen($name) < 3) {
        $errors[] = "Le nom doit contenir au moins 3 caractères.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    if ($password !== $passwordConfirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Cette adresse email est déjà utilisée.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, is_active) VALUES (?, ?, ?, 'client', 1)");
        $stmt->execute([$name, $email, $hashedPassword]);

        $userId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $_SESSION['user'] = $stmt->fetch();

        $_SESSION['success'] = "Inscription réussie. Bienvenue, $name !";
        header('Location: /projet/index.php');
        exit;
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: /projet/index.php');
        exit;
    }
}
?>