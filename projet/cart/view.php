<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/auth.php';

// Initialisation du panier si besoin
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['quantity'] as $productId => $quantity) {
        if (isset($_SESSION['cart'][$productId])) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId]['quantity'] = (int)$quantity;
            }
        }
    }
    header('Location: view.php');
    exit;
}

// Calcul du total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

require_once ROOT_PATH . '/includes/header.php';
?>

<div class="container my-5">
    <h1 class="mb-4">Votre Panier</h1>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-info neumorphic">
            Votre panier est vide. <a href="/projet/index.php">Commencez vos achats</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <div class="neumorphic p-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                        <tr>
                            <td>
                                <img src="../assets/img/products/<?= htmlspecialchars($item['image']) ?>" width="50" height="50" class="me-3">
                                <?= htmlspecialchars($item['name']) ?>
                            </td>
                            <td><?= number_format($item['price'], 2) ?> DH</td>
                            <td>
                                <input type="number" name="quantity[<?= $id ?>]" 
                                       value="<?= $item['quantity'] ?>" min="1" 
                                       class="form-control" style="width: 70px;">
                            </td>
                            <td><?= number_format($item['price'] * $item['quantity'], 2) ?> DH</td>
                            <td>
                                <a href="remove.php?id=<?= $id ?>" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th colspan="2"><?= number_format($total, 2) ?> DH</th>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="/projet/index.php" class="btn btn-outline-secondary">Continuer mes achats</a>
                    <a href="/projet/cart/checkout.php" class="btn btn-success">Passer la commande</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
    
</div>
<?php require_once ROOT_PATH . '/includes/footer.php';
?>