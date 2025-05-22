<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '../includes/session_init.php';
require_once ROOT_PATH . '../includes/config.php';
require_once ROOT_PATH . '../includes/db.php';
require_once ROOT_PATH . '../includes/auth.php';

checkAdminAccess(); // Ensure only admins can access this page

$pageTitle = "Gestion des stocks";

$products_stock = [];
try {
    // Fetch products and their stock quantities
    // Joining with product_variants to get stock for variants as well
    $stmt = $pdo->prepare("
        SELECT 
            p.id, 
            p.name, 
            p.sku,
            p.stock_quantity, -- main product stock
            GROUP_CONCAT(CONCAT(pv.id, ':', pv.sku, ':', pv.stock_quantity, ':', pv.price) SEPARATOR ';') as variants_data
        FROM products p
        LEFT JOIN product_variants pv ON p.id = pv.product_id
        GROUP BY p.id
        ORDER BY p.name ASC
    ");
    $stmt->execute();
    $products_stock = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur DB gestion des stocks: " . $e->getMessage());
    $_SESSION['error'] = "Erreur de chargement des stocks.";
}

// Handle stock update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'update_stock') {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $variant_id = filter_input(INPUT_POST, 'variant_id', FILTER_VALIDATE_INT);
    $new_stock = filter_input(INPUT_POST, 'new_stock', FILTER_VALIDATE_INT);

    if (($product_id && $new_stock !== false) || ($variant_id && $new_stock !== false)) {
        try {
            if ($variant_id) {
                // Update variant stock
                $stmt = $pdo->prepare("UPDATE product_variants SET stock_quantity = :new_stock WHERE id = :variant_id");
                $stmt->bindParam(':new_stock', $new_stock, PDO::PARAM_INT);
                $stmt->bindParam(':variant_id', $variant_id, PDO::PARAM_INT);
            } else {
                // Update main product stock
                $stmt = $pdo->prepare("UPDATE products SET stock_quantity = :new_stock WHERE id = :product_id");
                $stmt->bindParam(':new_stock', $new_stock, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            }

            if ($stmt->execute()) {
                $_SESSION['success'] = "Stock mis à jour avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du stock.";
            }
        } catch (PDOException $e) {
            error_log("Erreur DB mise à jour stock: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de la mise à jour du stock: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Données de stock invalides.";
    }
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


require_once ROOT_PATH . '../includes/header.php';
require_once ROOT_PATH . '../includes/prev.html';
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4">Gestion des stocks</h1>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card neumorphic mb-4">
        <div class="card-header">
            <h5>Inventaire des produits</h5>
        </div>
        <div class="card-body">
            <?php if (empty($products_stock)): ?>
                <p>Aucun produit en stock à gérer.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID Produit</th>
                                <th>Nom du produit</th>
                                <th>SKU Principal</th>
                                <th>Stock Principal</th>
                                <th>Variantes (SKU:Stock:Prix)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products_stock as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($product['sku'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="d-inline-flex align-items-center">
                                            <input type="hidden" name="action" value="update_stock">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <input type="number" name="new_stock" value="<?= htmlspecialchars($product['stock_quantity'] ?? 0, ENT_QUOTES, 'UTF-8') ?>" class="form-control form-control-sm w-auto me-2" style="min-width: 80px;">
                                            <button type="submit" class="btn btn-sm btn-primary" title="Mettre à jour le stock principal">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <?php if (!empty($product['variants_data'])): ?>
                                            <ul class="list-unstyled mb-0">
                                                <?php
                                                $variants = explode(';', $product['variants_data']);
                                                foreach ($variants as $variant_str) {
                                                    $variant_parts = explode(':', $variant_str);
                                                    if (count($variant_parts) === 4) {
                                                        echo '<li>';
                                                        echo '<strong>ID ' . htmlspecialchars($variant_parts[0], ENT_QUOTES, 'UTF-8') . '</strong>: ';
                                                        echo 'SKU ' . htmlspecialchars($variant_parts[1], ENT_QUOTES, 'UTF-8') . ', ';
                                                        echo 'Prix: ' . number_format($variant_parts[3], 2) . ' DH, ';
                                                        echo '<form method="POST" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" class="d-inline-flex align-items-center ms-2">';
                                                        echo '<input type="hidden" name="action" value="update_stock">';
                                                        echo '<input type="hidden" name="variant_id" value="' . htmlspecialchars($variant_parts[0], ENT_QUOTES, 'UTF-8') . '">';
                                                        echo '<input type="number" name="new_stock" value="' . htmlspecialchars($variant_parts[2], ENT_QUOTES, 'UTF-8') . '" class="form-control form-control-sm w-auto me-1" style="min-width: 60px;">';
                                                        echo '<button type="submit" class="btn btn-sm btn-outline-primary" title="Mettre à jour le stock de la variante"><i class="bi bi-arrow-clockwise"></i></button>';
                                                        echo '</form>';
                                                        echo '</li>';
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        <?php else: ?>
                                            Aucune variante.
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/projet/admin/products/edit.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-secondary" title="Éditer le produit">
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
require_once ROOT_PATH . '../includes/footer.php';
?>