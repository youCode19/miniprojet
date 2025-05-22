<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/includes/auth.php';

checkAdminAccess();

$pageTitle = "Tableau de bord administrateur";

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stats = [
        'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
        'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        'orders' => $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'delivered'")->fetchColumn(),
        'revenue' => $pdo->query("SELECT SUM(total) FROM orders WHERE status = 'delivered'")->fetchColumn()
    ];
} catch (PDOException $e) {
    error_log("Erreur DB dashboard: " . $e->getMessage());
    $_SESSION['error'] = "Erreur de chargement des statistiques";
    $stats = ['products' => 0, 'users' => 0, 'orders' => 0, 'revenue' => 0];
}

require_once ROOT_PATH . '/includes/header.php';
require_once ROOT_PATH . '/includes/prev.html';
?>

<!-- Le reste de votre code HTML reste inchangé -->

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4">Tableau de bord administrateur</h1>
            <p class="lead" style="color: red; font-weight: bolder; text-decoration: underline;">Bienvenue, <?= htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card neumorphic">
                <div class="card-body">
                    <h5 class="card-title">Produits</h5>
                    <p class="display-4"><?= $stats['products'] ?? 0 ?></p>
                    <a href="/projet/admin/products/list.php" class="btn btn-primary">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card neumorphic">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="display-4"><?= $stats['users'] ?? 0 ?></p>
                    <a href="/projet/admin/users/list.php" class="btn btn-primary">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card neumorphic">
                <div class="card-body">
                    <h5 class="card-title">Commandes</h5>
                    <p class="display-4"><?= $stats['orders'] ?? 0 ?></p>
                    <a href="/projet/admin/orders/list.php" class="btn btn-primary">Gérer</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card neumorphic">
                <div class="card-body">
                    <h5 class="card-title">Revenu total</h5>
                    <p class="display-4"><?= isset($stats['revenue']) ? number_format($stats['revenue'], 2) . ' DH' : '0 DH' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row">
        <div class="col-md-12">
            <div class="card neumorphic mb-4">
                <div class="card-header">
                    <h5>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3">
                        <a href="/projet/admin/products/add.php" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Ajouter un produit
                        </a>
                        <a href="/projet/admin/users/add.php" class="btn btn-success">
                            <i class="bi bi-person-plus"></i> Ajouter un administrateur
                        </a>
                        <a href="/projet/admin/orders/pending.php" class="btn btn-warning">
                            <i class="bi bi-hourglass"></i> Commandes en attente
                        </a>
                        <a href="/projet/admin/stats.php" class="btn btn-secondary">
                            <i class="bi bi-bar-chart-line"></i> Statistiques détaillées
                        </a>
                        <a href="/projet/admin/reviews/list.php" class="btn btn-dark">
                            <i class="bi bi-chat-left-text"></i> Avis clients
                        </a>
                        <a href="/projet/admin/stock.php" class="btn btn-outline-primary">
                            <i class="bi bi-box-seam"></i> Gestion des stocks
                        </a>

                        <a href="/projet/index.php"  class="btn btn-outline-info">
                            <i class="bi bi-eye"></i> Voir le site
                        </a>
                        <?php if ($_SESSION['user']['role'] === 'superadmin'): ?>
                            <a href="/projet/admin/settings.php" class="btn btn-info">
                                <i class="bi bi-gear"></i> Paramètres système
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

 

<?php
require_once ROOT_PATH . '/includes/footer.php';
?>