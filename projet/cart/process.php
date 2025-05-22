<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/db.php';
require_once ROOT_PATH . '/includes/auth.php';

if (!isset($_SESSION['user'])) {
    header('Location: /projet/user/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'] ?? '';
    $shippingAddressId = $_POST['shipping_address'] ?? '';
    $userId = $_SESSION['user']['id'];
    $cart = $_SESSION['cart'] ?? [];

    // Vérifier l'adresse
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE id = ? AND user_id = ?");
    $stmt->execute([$shippingAddressId, $userId]);
    $shippingAddress = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($paymentMethod === 'card' && empty($_POST['card_number'])) {
        // Affiche le formulaire de carte bancaire
        require_once ROOT_PATH . '/includes/header.php';
        ?>
      <div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="mb-4 text-center">Paiement par carte bancaire</h2>
                <form method="POST" action="/projet/cart/process.php">
                    <input type="hidden" name="payment_method" value="card">
                    <input type="hidden" name="shipping_address" value="<?= htmlspecialchars($shippingAddressId) ?>">

                    <div class="mb-3">
                        <label for="card_number" class="form-label">Numéro de carte</label>
                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                    </div>

                    <div class="mb-3 row">
                        <div class="col">
                            <label for="card_expiry" class="form-label">Date d'expiration</label>
                            <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/AA" required>
                        </div>
                        <div class="col">
                            <label for="card_cvc" class="form-label">CVC</label>
                            <input type="text" class="form-control" id="card_cvc" name="card_cvc" placeholder="123" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="card_holder" class="form-label">Titulaire de la carte</label>
                        <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="Nom Prénom" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success w-100">Valider le paiement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        <?php
        require_once ROOT_PATH . '/includes/footer.php';
        exit;
    }

    // Ici, tu traites la commande (paiement à la livraison OU carte déjà saisie)
    // ... (reprends le code d'insertion de la commande et des articles)
    // ... (vide le panier et redirige)
}
?>