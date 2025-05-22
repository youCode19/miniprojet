<?php
define('ROOT_PATH', dirname(dirname(__DIR__)));
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/auth.php';
require_once ROOT_PATH . '/includes/db.php';

checkAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['id'] ?? 0;

    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$productId]);

        $_SESSION['success'] = "Produit supprimé avec succès.";
        header('Location: list.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression : " . $e->getMessage();
        header('Location: list.php');
        exit;
    }
}
require_once ROOT_PATH . '/includes/header.php';
?>
