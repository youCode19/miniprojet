<?php
define('ROOT_PATH', dirname(dirname(__DIR__)));
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/auth.php';
require_once ROOT_PATH . '/includes/db.php';
checkAdminAccess();

// Pagination (optionnel, à ajouter si beaucoup de commandes)
// $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
// $perPage = 20;
// $offset = ($page - 1) * $perPage;
// $orders = $pdo->prepare("SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT :offset, :perPage");
// $orders->bindValue(':offset', $offset, PDO::PARAM_INT);
// $orders->bindValue(':perPage', $perPage, PDO::PARAM_INT);
// $orders->execute();

$orders = $pdo->query("
    SELECT o.*, u.name as user_name 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC
")->fetchAll();

$pageTitle = "Gestion des commandes";
require_once ROOT_PATH . '/includes/header.php';
?>
<div class="container my-5">
    <h1>Gestion des commandes</h1>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($order['user_name'] ?? 'Utilisateur supprimé', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= number_format($order['total'], 2) ?> DH</td>
                <td><?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($order['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="view.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info">Voir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once ROOT_PATH . '/includes/footer.php'; ?>