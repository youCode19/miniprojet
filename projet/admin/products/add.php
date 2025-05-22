<?php
define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/../../includes/config.php';
require_once ROOT_PATH . '/../../includes/auth.php';
require_once ROOT_PATH . '/../../includes/db.php';



$categories = $pdo->query("SELECT id, name FROM categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $sku = isset($_POST['sku']) ? trim($_POST['sku']) : '';
        $short_description = isset($_POST['short_description']) ? trim($_POST['short_description']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $price = isset($_POST['price']) ? $_POST['price'] : 0;
        $compare_price = isset($_POST['compare_price']) && $_POST['compare_price'] !== '' ? $_POST['compare_price'] : null;
        $cost_price = isset($_POST['cost_price']) && $_POST['cost_price'] !== '' ? $_POST['cost_price'] : null;
        $stock = isset($_POST['stock']) ? $_POST['stock'] : 0;
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
        $is_new = isset($_POST['is_new']) ? 1 : 0;
        $image = null;

        // Gestion de l'upload d'image (optionnel)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed)) {
                $imageName = uniqid('prod_', true) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], ROOT_PATH . "/../assets/img/products/$imageName");
                $image = $imageName;
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO products 
            (category_id, name, slug, sku, short_description, description, price, compare_price, cost_price, stock, is_featured, is_bestseller, is_new, image, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
            $_POST['category_id'] ?: null,
            htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            $slug,
            htmlspecialchars($sku, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($short_description, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
            $price,
            $compare_price,
            $cost_price,
            $stock,
            $is_featured,
            $is_bestseller,
            $is_new,
            $image
        ]);

        $pdo->commit();
        $_SESSION['success'] = "Produit ajouté avec succès.";
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
    }
}
require_once ROOT_PATH . '/../includes/header.php';

?>

<div class="container my-5">
    <h1>Ajouter un Produit</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" name="sku" id="sku" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="category_id">Catégorie</label>
            <select name="category_id" id="category_id" class="form-control">
                <option value="">Aucune</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="short_description">Description courte</label>
            <textarea name="short_description" id="short_description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="price">Prix</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="compare_price">Prix comparatif</label>
            <input type="number" step="0.01" name="compare_price" id="compare_price" class="form-control">
        </div>
        <div class="form-group">
            <label for="cost_price">Prix de revient</label>
            <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control">
        </div>
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="image">Image du produit</label>
            <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input">
            <label for="is_featured" class="form-check-label">Produit vedette</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_bestseller" id="is_bestseller" class="form-check-input">
            <label for="is_bestseller" class="form-check-label">Meilleure vente</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_new" id="is_new" class="form-check-input">
            <label for="is_new" class="form-check-label">Nouveau produit</label>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
    </form>
</div>