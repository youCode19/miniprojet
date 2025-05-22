<?php
if (!defined('ROOT_PATH')) {
    die('Accès direct interdit');
}

if (!isset($productData)) return;
?>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<div class="product-card-sm neumorphic">
    <a href="/projet/products/product.php?id=<?= $productData['id'] ?>">
        <img src="/projet/assets/img/products/<?= htmlspecialchars($productData['image'] ?? 'default.jpg') ?>" 
             alt="<?= htmlspecialchars($productData['name']) ?>"
             class="img-fluid"
             loading="lazy">
    </a>
    <div class="p-2">
        <h6 class="mb-1">
            <a href="/projet/products/product.php?id=<?= $productData['id'] ?>">
                <?= htmlspecialchars(mb_strimwidth($productData['name'], 0, 30, "...")) ?>
            </a>
        </h6>
        <div class="d-flex justify-content-between align-items-center">
            <span class="price"><?= number_format($productData['price'], 2) ?> DH</span>
            <button 
                class="btn btn-primary btn-add-to-cart" 
                data-id="<?= htmlspecialchars($productData['id']) ?>"
                data-name="<?= htmlspecialchars($productData['name']) ?>"
                data-price="<?= htmlspecialchars($productData['price']) ?>"
                data-image="<?= htmlspecialchars($productData['image']) ?>"
                type="button">
                <i class="fas fa-cart-plus"></i> Ajouter
            </button>
        </div>
    </div>
</div>