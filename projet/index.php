<?php

// Initialisation
define('ROOT_PATH', __DIR__); // Correction du chemin racine
require_once ROOT_PATH . '/includes/session_init.php'; // Toujours démarrer la session en premier !
require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/includes/auth.php';

// echo '<pre>'; print_r($_SESSION); echo '</pre>';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

$pageTitle = "Accueil - " . SITE_NAME;
$pageDescription = "Boutique en ligne avec design Neumorphique. Découvrez nos produits exclusifs.";

require_once ROOT_PATH . '/includes/header.php';
?>

<div class="video-background-container mb-5">
    <video autoplay loop muted playsinline class="backgroundvid">
        <source src="/assets/vids/vidback.mp4" type="video/mp4">
    </video>
    <div class="video-overlay"></div>
    <div class="carousel-on-video">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center align-items-center" style="height:300px;">
                        <h2 class="text-white">Bienvenue sur <?= SITE_NAME ?></h2>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="d-flex justify-content-center align-items-center" style="height:300px;">
                        <h2 class="text-white">Découvrez nos nouveautés</h2>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="d-flex justify-content-center align-items-center" style="height:300px;">
                        <h2 class="text-white">Qualité et Innovation</h2>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Précédent</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Suivant</span>
            </a>
        </div>
    </div>
</div>
    
<section class="hero text-center neumorphic mb-5 py-5">
    <div class="container py-5">
        <form method="GET" action="">
            <div class="input-group neumorphic p-1 rounded-pill flex-wrap gap-2">
                <div class="input-group-prepend">
                    <select name="category" class="form-control neumorphic">
                        <option value="">Toutes catégories</option>
                        <?php
                        $allCategories = $pdo->query("
                            SELECT id, name, slug
                            FROM categories
                            WHERE parent_id IS NULL
                            ORDER BY name
                        ")->fetchAll();
                        foreach ($allCategories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['slug'], ENT_QUOTES, 'UTF-8') ?>" <?= (isset($_GET['category']) && $_GET['category'] === $cat['slug']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="text" name="q" class="form-control neumorphic-inset" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">

                <div class="input-group-prepend">
                    <select name="sort" class="form-control neumorphic">
                        <option value="">Trier par</option>
                        <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'price_asc') ? 'selected' : '' ?>>Prix croissant</option>
                        <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'price_desc') ? 'selected' : '' ?>>Prix décroissant</option>
                        <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'name_asc') ? 'selected' : '' ?>>Nom A-Z</option>
                        <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'name_desc') ? 'selected' : '' ?>>Nom Z-A</option>
                        <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] === 'newest') ? 'selected' : '' ?>>Plus récents</option>
                        <option value="oldest" <?= (isset($_GET['sort']) && $_GET['sort'] === 'oldest') ? 'selected' : '' ?>>Plus anciens</option>
                    </select>
                </div>

                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary neumorphic">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
               <?php if (isset($_GET['category']) || isset($_GET['sort']) || isset($_GET['q'])): ?>
    <a href="index.php" class="btn btn-outline-secondary ml-2">Réinitialiser</a>
<?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</section>

<?php
// SECTION DES CATÉGORIES (Tableau d'icônes cliquables)
try {
    $categories = $pdo->query("
        SELECT id, name, slug, image ,description
        FROM categories 
        WHERE parent_id IS NULL AND is_featured = 1 
        ORDER BY display_order 
        LIMIT 12 -- Augmenté la limite pour avoir plus d'icônes comme dans l'image
    ")->fetchAll();

    if ($categories): ?>
        <section class="category-section mb-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h2 class="section-title">Nos Catégories</h2>
                    <a href="/categories/" class="btn btn-outline-primary">Toutes les catégories</a>
                </div>
                <div class="row g-4 justify-content-center"> <?php foreach ($categories as $category): ?>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2"> <a href="products/category.php?slug=<?= urlencode($category['slug']) ?>" class="category-card neumorphic text-center d-block p-3">
                                <div class="category-image">
                                    <img src="/projet/assets/img/categories/<?= htmlspecialchars($category['image'] ?? 'default.jpg', ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="<?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>" 
                                         loading="lazy" class="img-fluid">
                                </div>
                                <h3 class="mt-2" style="font-size: 0.95rem;"><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif;
} catch (PDOException $e) {
    error_log("Erreur DB lors du chargement des catégories : " . $e->getMessage());
}
?>

<?php
// Récupération des paramètres pour les produits
$searchTerm = trim($_GET['q'] ?? '');
$categorySlug = $_GET['category'] ?? '';
$sortOption = $_GET['sort'] ?? '';

// Construction de la requête pour les produits principaux (vedettes ou résultats de recherche)
$sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.stock > 0"; // Seulement les produits en stock

$params = [];

// Filtre par catégorie
if (!empty($categorySlug)) {
    $sql .= " AND p.category_id IN (SELECT id FROM categories WHERE slug = ?)";
    $params[] = $categorySlug;
}

// Filtre par recherche Full-Text
if (!empty($searchTerm)) {
    // Note: 'MATCH AGAINST' nécessite des index FULLTEXT sur les colonnes spécifiées (name, short_description, description)
    $sql .= " AND (MATCH(p.name, p.short_description, p.description) AGAINST (? IN NATURAL LANGUAGE MODE) 
           OR p.name LIKE ? 
           OR p.short_description LIKE ? )";
    $params[] = $searchTerm;
    $params[] = '%' . $searchTerm . '%';
    $params[] = '%' . $searchTerm . '%';
}

// Application du tri
switch ($sortOption) {
    case 'price_asc':
        $sql .= " ORDER BY p.price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY p.price DESC";
        break;
    case 'name_asc':
        $sql .= " ORDER BY p.name ASC";
        break;
    case 'name_desc':
        $sql .= " ORDER BY p.name DESC";
        break;
    case 'newest':
        $sql .= " ORDER BY p.created_at DESC";
        break;
    case 'oldest':
        $sql .= " ORDER BY p.created_at ASC";
        break;
    default:
        // Tri par défaut si aucun tri spécifié, ou si c'est la page d'accueil sans filtre
        if (empty($searchTerm) && empty($categorySlug)) {
            $sql .= " ORDER BY p.is_featured DESC, p.created_at DESC"; // Produits vedettes en premier, puis les plus récents
        } else {
            $sql .= " ORDER BY p.created_at DESC"; // Sinon, simplement par date de création
        }
        break;
}

// Exécution de la requête principale (produits vedettes ou résultats de recherche)
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Database error lors du chargement des produits : " . $e->getMessage());
    $products = []; // En cas d'erreur, s'assurer que $products est un tableau vide
}

// Déterminer quels produits afficher dans la section "Produits Vedettes"
$hasFilters = !empty($searchTerm) || !empty($categorySlug) || !empty($sortOption);
$displayProducts = $products; // Par défaut, affiche les résultats de la requête ci-dessus

// Si aucun filtre n'est appliqué, on affiche spécifiquement les produits vedettes
if (!$hasFilters) {
    try {
        $displayProducts = $pdo->query("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.is_featured = 1 AND p.stock > 0 
            ORDER BY p.created_at DESC 
            LIMIT 10
        ")->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur DB lors du chargement des produits vedettes : " . $e->getMessage());
        $displayProducts = [];
    }
}
?>

<?php if (!empty($displayProducts)): ?>
    <section class="featured-products mb-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title">
                    <?= $hasFilters ? "Résultats de la Recherche" : "Produits Vedettes" ?>
                </h2>
                <?php if (!$hasFilters): // Afficher "Voir plus" seulement pour les produits vedettes par défaut ?>
                    <a href="products/?filter=featured" class="btn btn-outline-primary">Voir plus</a>
                <?php endif; ?>
            </div>
            <div class="row g-4">
                <?php foreach ($displayProducts as $product): ?>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <?php
                        $productData = $product;
                        include ROOT_PATH . '/includes/partials/product-card.php';
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php else: ?>
    <div class="container mb-5">
        <div class="alert alert-warning">
            <?= $hasFilters ? "Aucun produit trouvé correspondant à votre recherche." : "Aucun produit vedette disponible pour le moment." ?>
        </div>
    </div>
<?php endif; ?>

<div class="container mb-5">
    <div class="row">
        <?php
        // Nouveautés
        try {
            $newProducts = $pdo->query("
                SELECT id, name, slug, price, image 
                FROM products 
                WHERE is_new = 1 AND stock > 0 
                ORDER BY created_at DESC 
                LIMIT 4
            ")->fetchAll();
            if ($newProducts): ?>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <section class="neumorphic p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>Nouveautés</h3>
                            <a href="/products/?filter=new" class="btn btn-sm btn-outline-primary">Voir tout</a>
                        </div>
                        <div class="row g-3">
                            <?php foreach ($newProducts as $product): ?>
                                <div class="col-6">
                                    <?php
                                    $productData = $product;
                                    include ROOT_PATH . '/includes/partials/product-card.php';
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
            <?php endif;
        } catch (PDOException $e) {
            error_log("Erreur DB lors du chargement des nouveautés : " . $e->getMessage());
        }

        // Best-sellers
        try {
            $bestSellers = $pdo->query("
                SELECT id, name, slug, price, image 
                FROM products 
                WHERE is_bestseller = 1 AND stock > 0 
                ORDER BY sales DESC 
                LIMIT 4
            ")->fetchAll(PDO::FETCH_ASSOC);
            if ($bestSellers): ?>
                <div class="col-lg-6">
                    <section class="neumorphic p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>Meilleures Ventes</h3>
                            <a href="/products/?filter=bestseller" class="btn btn-sm btn-outline-primary">Voir tout</a>
                        </div>
                        <div class="row g-3">
                            <?php foreach ($bestSellers as $product): ?>
                                <div class="col-6">
                                    <?php
                                    $productData = $product;
                                    include ROOT_PATH . '/includes/partials/product-card.php';
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
            <?php endif;
        } catch (PDOException $e) {
            error_log("Erreur DB lors du chargement des best-sellers : " . $e->getMessage());
        }
        ?>
    </div>
</div>


<?php require_once ROOT_PATH . '/includes/footer.php'; ?>