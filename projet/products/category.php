<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/includes/auth.php';

$categorySlug = $_GET['slug'] ?? '';

if (empty($categorySlug)) {
    header('Location: /projet/products/');
    exit;
}

try {
    // Récupérer la catégorie
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
    $stmt->execute([$categorySlug]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        header('Location: /projet/products/');
        exit;
    }

    // Récupérer les produits de la catégorie (et sous-catégories si besoin)
    $stmtProducts = $pdo->prepare("
        SELECT * FROM products
        WHERE (category_id = ? OR category_id IN (SELECT id FROM categories WHERE parent_id = ?))
        AND stock > 0
        ORDER BY created_at DESC
    ");
    $stmtProducts->execute([$category['id'], $category['id']]);
    $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erreur DB : " . $e->getMessage());
    header('Location: /projet/products/');
    exit;
}

$pageTitle = $category['name'] . " - " . SITE_NAME;
require_once ROOT_PATH . '/includes/header.php';
?>

<main class="container py-5">
    <h1 class="mb-4"><?= htmlspecialchars($category['name']) ?></h1>
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <?php
                    $productData = $product;
                    include ROOT_PATH . '/includes/partials/product-card.php';
                    ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">Aucun produit trouvé dans cette catégorie.</div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once ROOT_PATH . '/includes/footer.php'; ?>