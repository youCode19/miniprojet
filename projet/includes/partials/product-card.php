<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
    require_once ROOT_PATH . '/includes/header.php';
}
if (!isset($productData)) return;
?>

<div class="product-card neumorphic mb-4">
    <?php if (!empty($productData['compare_price']) && $productData['compare_price'] > $productData['price']): ?>
        <span class="badge bg-danger discount-badge">
            -<?= number_format(100 - ($productData['price'] / $productData['compare_price'] * 100), 0) ?>%
        </span>
    <?php endif; ?>
    <a href="/projet/products/product.php?id=<?= $productData['id'] ?>">
        <img src="/projet/assets/img/products/<?= htmlspecialchars($productData['image'] ?? 'default.jpg') ?>"
             alt="<?= htmlspecialchars($productData['name']) ?>"
             class="img-fluid product-image"
             loading="lazy">
    </a>
    <div class="card-body">
        <h5 class="product-title">
            <a href="/projet/products/product.php?id=<?= $productData['id'] ?>">
                <?= htmlspecialchars($productData['name']) ?>
            </a>
        </h5>
        <div class="product-price mb-2">
            <?php if (!empty($productData['compare_price']) && $productData['compare_price'] > 0): ?>
                <span class="text-muted text-decoration-line-through me-2">
                    <?= number_format($productData['compare_price'], 2) ?> DH
                </span>
            <?php endif; ?>
            <span class="fw-bold text-primary">
                <?= number_format($productData['price'], 2) ?> DH
            </span>
        </div>
        <form action="/projet/cart/add.php" method="POST" class="add-to-cart">
            <input type="hidden" name="product_id" value="<?= $productData['id'] ?>">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-cart-plus"></i> Ajouter
            </button>
        </form>
    </div>
</div>
<script>
document.querySelectorAll('.add-to-cart').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        const originalClass = submitButton.className;

        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

        const data = new FormData(this);
        fetch('/projet/cart/add.php', {
            method: 'POST',
            body: data
        })
        .then(res => res.json())
        .then(response => {
            submitButton.className = 'btn btn-success w-100';
            submitButton.innerHTML = '<i class="bi bi-check-circle"></i> Ajouté';
            
            setTimeout(() => {
                submitButton.className = originalClass;
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 2000);
            
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
        })
        .catch(() => {
            submitButton.className = originalClass;
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });
});
</script>