<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '../../includes/session_init.php';
require_once ROOT_PATH . '../../includes/config.php';
require_once ROOT_PATH . '../../includes/db.php';
require_once ROOT_PATH . '../../includes/auth.php';

checkAdminAccess(); // Ensure only admins can access this page

$pageTitle = "Commandes en attente";

$pending_orders = [];
try {
    $stmt = $pdo->prepare("SELECT o.*, u.name as user_name, u.email as user_email FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.status = 'pending' ORDER BY o.created_at DESC");
    $stmt->execute();
    $pending_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur DB commandes en attente: " . $e->getMessage());
    $_SESSION['error'] = "Erreur de chargement des commandes en attente.";
}

require_once ROOT_PATH . '../../includes/header.php';
require_once ROOT_PATH . '../../includes/prev.html';
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4">Commandes en attente</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card neumorphic mb-4">
        <div class="card-header">
            <h5>Liste des commandes en attente</h5>
        </div>
        <div class="card-body">
            <?php if (empty($pending_orders)): ?>
                <p>Aucune commande en attente pour le moment.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Client</th>
                                <th>Email Client</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Date de commande</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_orders as $order): ?>
                                <tr>
                                    <td><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($order['user_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($order['user_email'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= number_format($order['total'], 2) ?> DH</td>
                                    <td><span class="badge bg-warning"><?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <a href="/projet/admin/orders/view.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-info me-1" title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/projet/admin/orders/edit.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-primary" title="Gérer la commande">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once ROOT_PATH . '../../includes/footer.php';
?>