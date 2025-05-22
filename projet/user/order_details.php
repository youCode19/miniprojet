<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/includes/auth.php';

if (!isset($_SESSION['user'])) {
    header('Location: /projet/index.php');
    exit;
}

$userId = $_SESSION['user']['id'];
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pageTitle = "Détail de la commande #$orderId";

require_once ROOT_PATH . '/includes/header.php';

// Récupérer la commande
try {
    $stmt = $pdo->prepare("
        SELECT o.*, o.shipping_address
        FROM orders o
        WHERE o.id = ? AND o.user_id = ?
        LIMIT 1
    ");
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo '<div class="container my-5"><div class="alert alert-danger">Commande introuvable.</div></div>';
        require_once ROOT_PATH . '/includes/footer.php';
        exit;
    }

    // Récupérer les articles de la commande
    $stmt = $pdo->prepare("
        SELECT *
        FROM order_items
        WHERE order_id = ?
    ");
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Décoder l'adresse de livraison
    $address = json_decode($order['shipping_address'], true);

} catch (PDOException $e) {
    echo '<div class="container my-5"><div class="alert alert-danger">Erreur lors du chargement de la commande.</div></div>';
    require_once ROOT_PATH . '/includes/footer.php';
    exit;
}
?>

<div class="container my-5">
    <h2 class="mb-4">Commande #<?= htmlspecialchars($order['id']) ?></h2>
    <div class="row">
        <div class="col-md-7">
            <div class="neumorphic p-4 mb-4">
                <h4>Détails des articles</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <img src="/projet/assets/img/products/<?= htmlspecialchars($item['image']) ?>" width="40" height="40" class="me-2" style="object-fit:cover;border-radius:6px;">
                                <?php endif; ?>
                                <?= htmlspecialchars($item['name']) ?>
                            </td>
                            <td><?= number_format($item['price'], 2, ',', ' ') ?> DH</td>
                            <td><?= (int)$item['quantity'] ?></td>
                            <td><?= number_format($item['total'], 2, ',', ' ') ?> DH</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-5">
            <div class="neumorphic p-4">
                <h4>Récapitulatif</h4>
                <ul class="list-unstyled mb-3">
                    <li><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></li>
                    <li><strong>Statut :</strong> <?= htmlspecialchars($order['status']) ?></li>
                    <li><strong>Total :</strong> <?= number_format($order['total'], 2, ',', ' ') ?> DH</li>
                </ul>
                <h5 class="mt-4">Adresse de livraison</h5>
                <?php if ($address): ?>
                    <div>
                        <?= htmlspecialchars($address['first_name'] . ' ' . $address['last_name']) ?><br>
                        <?= htmlspecialchars($address['address1']) ?><br>
                        <?= htmlspecialchars($address['zip_code']) . ' ' . htmlspecialchars($address['city']) ?><br>
                        <?= htmlspecialchars($address['country']) ?>
                    </div>
                <?php else: ?>
                    <div>Adresse non disponible</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <a href="/projet/user/orders.php" class="btn btn-outline-secondary mt-4">Retour à mes commandes</a>
</div>

<?php require_once ROOT_PATH . '/includes/footer.php'; ?>

