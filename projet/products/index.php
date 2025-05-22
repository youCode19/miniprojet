<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/auth.php';

// Pagination
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Gestion du tri
$sort = $_GET['sort'] ?? '';
$orderBy = "p.created_at DESC";
switch ($sort) {
    case 'price_asc':
        $orderBy = "p.price ASC";
        break;
    case 'price_desc':
        $orderBy = "p.price DESC";
        break;
    case 'popular':
        $orderBy = "p.sales DESC";
        break;
    case 'newest':
        $orderBy = "p.created_at DESC";
        break;
}

// Récupération des produits
try {
    $products = $pdo->query("
        SELECT p.*, c.name AS category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.stock > 0
        ORDER BY $orderBy
        LIMIT $perPage OFFSET $offset
    ")->fetchAll();

    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products WHERE stock > 0")->fetchColumn();
    $totalPages = ceil($totalProducts / $perPage);

} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $products = [];
    $totalPages = 1;
}

$pageTitle = "Tous nos produits";
require_once ROOT_PATH . '/includes/header.php';
?>


<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="display-5 fw-bold">Nos produits</h1>
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Trier par <i class="bi bi-filter"></i>
            </button>
            <ul class="dropdown-menu neumorphic-dropdown" aria-labelledby="sortDropdown">
                <li><a class="dropdown-item" href="?sort=newest">Nouveautés</a></li>
                <li><a class="dropdown-item" href="?sort=price_asc">Prix croissant</a></li>
                <li><a class="dropdown-item" href="?sort=price_desc">Prix décroissant</a></li>
                <li><a class="dropdown-item" href="?sort=popular">Plus populaires</a></li>
            </ul>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <div class="row g-4">
        <?php if (empty($products)): ?>
            <div class="col-12">
                <div class="alert alert-info neumorphic">
                    Aucun produit disponible pour le moment
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <?php
                    $productData = $product;
                    include ROOT_PATH . '/includes/partials/product-card.php';
                    ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link neumorphic-hover" href="?page=<?= $i ?><?= $sort ? '&sort=' . urlencode($sort) : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</main>

<?php require_once ROOT_PATH . '/includes/footer.php'; ?>