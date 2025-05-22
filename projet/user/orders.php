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
$pageTitle = "Mes commandes";


// Récupérer les commandes de l'utilisateur
try {
    $stmt = $pdo->prepare("
        SELECT o.id, o.created_at, o.status, o.total, o.shipping_address
        FROM orders o
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $orders = [];
    error_log("Erreur lors du chargement des commandes : " . $e->getMessage());
}
require_once ROOT_PATH . '/includes/header.php';

?>

<div class="container my-5">
    <h2 class="mb-4">Mes commandes</h2>
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">Vous n'avez pas encore passé de commande.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered neumorphic">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th>Adresse de livraison</th>
                        <th>Détail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td><?= number_format($order['total'], 2, ',', ' ') ?> DH</td>
                            <td>
                                <?php
                                $address = json_decode($order['shipping_address'], true);
                                if ($address) {
                                    echo htmlspecialchars($address['first_name'] . ' ' . $address['last_name']) . '<br>';
                                    echo htmlspecialchars($address['address1']) . '<br>';
                                    echo htmlspecialchars($address['zip_code']) . ' ' . htmlspecialchars($address['city']) . '<br>';
                                    echo htmlspecialchars($address['country']);
                                } else {
                                    echo 'Adresse non disponible';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="/projet/user/order_details.php?id=<?= urlencode($order['id']) ?>" class="btn btn-sm btn-primary">
                                    Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once ROOT_PATH . '/includes/footer.php'; ?>

