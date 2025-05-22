<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '../includes/session_init.php';
require_once ROOT_PATH . '../includes/config.php';
require_once ROOT_PATH . '../includes/db.php';
require_once ROOT_PATH . '../includes/auth.php';

checkAdminAccess(); // Ensure only admins can access this page

$pageTitle = "Statistiques détaillées";

$stats_details = [];
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Total products by category
    $stmt_products_by_category = $pdo->query("SELECT c.name, COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.name ORDER BY product_count DESC");
    $stats_details['products_by_category'] = $stmt_products_by_category->fetchAll(PDO::FETCH_ASSOC);

    // Top 5 selling products (based on order_items)
    $stmt_top_selling_products = $pdo->query("SELECT p.name, SUM(oi.quantity) as total_quantity_sold, SUM(oi.price * oi.quantity) as total_revenue FROM order_items oi JOIN products p ON oi.product_id = p.id GROUP BY p.name ORDER BY total_quantity_sold DESC LIMIT 5");
    $stats_details['top_selling_products'] = $stmt_top_selling_products->fetchAll(PDO::FETCH_ASSOC);

    // Orders by status
    $stmt_orders_by_status = $pdo->query("SELECT status, COUNT(*) as order_count FROM orders GROUP BY status");
    $stats_details['orders_by_status'] = $stmt_orders_by_status->fetchAll(PDO::FETCH_ASSOC);

    // Registered users over time (e.g., by month)
    $stmt_users_by_month = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as user_count FROM users GROUP BY month ORDER BY month ASC");
    $stats_details['users_by_month'] = $stmt_users_by_month->fetchAll(PDO::FETCH_ASSOC);

    // Total revenue over time (e.g., by month, for delivered orders)
    $stmt_revenue_by_month = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total) as monthly_revenue FROM orders WHERE status = 'delivered' GROUP BY month ORDER BY month ASC");
    $stats_details['revenue_by_month'] = $stmt_revenue_by_month->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erreur DB statistiques détaillées: " . $e->getMessage());
    $_SESSION['error'] = "Erreur de chargement des statistiques détaillées.";
}

require_once ROOT_PATH . '../includes/header.php';
require_once ROOT_PATH . '../includes/prev.html';
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4">Statistiques détaillées</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card neumorphic">
                <div class="card-header">
                    <h5>Produits par catégorie</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats_details['products_by_category'])): ?>
                        <p>Aucune donnée disponible.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($stats_details['products_by_category'] as $row): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>
                                    <span class="badge bg-primary rounded-pill"><?= $row['product_count'] ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card neumorphic">
                <div class="card-header">
                    <h5>Top 5 des produits vendus</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats_details['top_selling_products'])): ?>
                        <p>Aucune donnée disponible.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($stats_details['top_selling_products'] as $row): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>
                                    <span class="badge bg-success rounded-pill"><?= $row['total_quantity_sold'] ?> ventes (<?= number_format($row['total_revenue'], 2) ?> DH)</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card neumorphic">
                <div class="card-header">
                    <h5>Commandes par statut</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats_details['orders_by_status'])): ?>
                        <p>Aucune donnée disponible.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($stats_details['orders_by_status'] as $row): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= ucfirst(htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8')) ?>
                                    <span class="badge bg-info rounded-pill"><?= $row['order_count'] ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card neumorphic">
                <div class="card-header">
                    <h5>Nouveaux utilisateurs par mois</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats_details['users_by_month'])): ?>
                        <p>Aucune donnée disponible.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($stats_details['users_by_month'] as $row): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($row['month'], ENT_QUOTES, 'UTF-8') ?>
                                    <span class="badge bg-warning rounded-pill"><?= $row['user_count'] ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card neumorphic">
                <div class="card-header">
                    <h5>Revenu mensuel (commandes livrées)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($stats_details['revenue_by_month'])): ?>
                        <p>Aucune donnée disponible.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($stats_details['revenue_by_month'] as $row): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($row['month'], ENT_QUOTES, 'UTF-8') ?>
                                    <span class="badge bg-danger rounded-pill"><?= number_format($row['monthly_revenue'], 2) ?> DH</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once ROOT_PATH . '../includes/footer.php';
?>