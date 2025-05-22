<?php
define('ROOT_PATH', dirname(dirname(__DIR__)));
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/auth.php';
require_once ROOT_PATH . '/includes/db.php';
checkAdminAccess();

$orderId = $_GET['id'] ?? 0;

// Récupérer la commande
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    die("Commande introuvable.");
}

// Récupérer les articles de la commande
$orderItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$orderItems->execute([$orderId]);

require_once ROOT_PATH . '/includes/header.php';
?>
<div class="container my-5">
    <h1>Détails de la commande #<?= htmlspecialchars($order['order_number']) ?></h1>
    <p><strong>Statut :</strong> <?= htmlspecialchars($order['status']) ?></p>
    <p><strong>Total :</strong> <?= number_format($order['total'], 2) ?> DH</p>

    <h3>Articles</h3>
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
            <?php foreach ($orderItems as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= number_format($item['price'], 2) ?> DH</td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['total'], 2) ?> DH</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formulaire de changement de statut -->
    <h3>Changer le statut</h3>
    <form method="POST" action="update_status.php">
        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
        <div class="mb-3">
            <select name="status" class="form-select" required>
                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>En traitement</option>
                <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Terminée</option>
                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Annulée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
<?php require_once ROOT_PATH . '/includes/footer.php'; ?>