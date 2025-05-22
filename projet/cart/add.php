<?php
require '../includes/session_init.php';    
require '../includes/auth.php';
require '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;

    $product = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
    $product->execute([$productId]);
    $product = $product->fetch();

    if ($product) {
        $_SESSION['cart'][$productId] = [
            'id' => $productId,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image' => $product['image']
        ];

        $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));

        echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier !']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Produit introuvable.']);
exit;
?>