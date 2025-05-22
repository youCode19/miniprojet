<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/includes/session_init.php';

require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/auth.php';

if (!isset($_SESSION['user'])) {
    header('Location: /projet/user/login.php');
    exit;
}

if (empty($_SESSION['cart'])) {
    header('Location: view.php');
    exit;
}

$userId = $_SESSION['user']['id'];

// Récupérer les adresses du client dans un tableau
$stmt = $pdo->prepare("
    SELECT * FROM addresses
    WHERE user_id = ?
    ORDER BY is_default DESC
");
$stmt->execute([$userId]);
$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul du total du panier
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Retrieve Location Data from Session
$userLatitude = $_SESSION['last_known_location']['latitude'] ?? null;
$userLongitude = $_SESSION['last_known_location']['longitude'] ?? null;


// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'] ?? '';
    $shippingAddressId = $_POST['shipping_address'] ?? '';

    if (!in_array($paymentMethod, ['card', 'cash_on_delivery'])) {
        $_SESSION['error'] = "Méthode de paiement invalide";
        header('Location: checkout.php');
        exit;
    }

    // Vérifier que l'adresse appartient bien à l'utilisateur
    $addressCheck = $pdo->prepare("SELECT * FROM addresses WHERE id = ? AND user_id = ?");
    $addressCheck->execute([$shippingAddressId, $userId]);
    $shippingAddress = $addressCheck->fetch(PDO::FETCH_ASSOC);

    if (!$shippingAddress) {
        $_SESSION['error'] = "Adresse de livraison invalide";
        header('Location: checkout.php');
        exit;
    }

    $orderNumber = 'CMD-' . strtoupper(uniqid());

    $location_data = null;
    if ($userLatitude !== null && $userLongitude !== null) {
        $location_data = json_encode(['latitude' => $userLatitude, 'longitude' => $userLongitude]);
    }

    try {
        $pdo->beginTransaction();

        // Insertion de la commande
        // Assuming 'user_location' column exists in 'orders' table (TEXT or JSON type)
        $stmt = $pdo->prepare("
            INSERT INTO orders (
                user_id, order_number, status, total, payment_method,
                payment_status, shipping_address, user_location
            ) VALUES (?, ?, 'pending', ?, ?, 'pending', ?, ?)
        ");
        $stmt->execute([
            $userId,
            $orderNumber,
            $total,
            $paymentMethod,
            json_encode($shippingAddress),
            $location_data
        ]);

        $orderId = $pdo->lastInsertId();

        // Insertion des articles
        foreach ($_SESSION['cart'] as $id => $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (
                    order_id, product_id, name, price, quantity, total, image
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $orderId,
                $id,
                $item['name'],
                $item['price'],
                $item['quantity'],
                $item['price'] * $item['quantity'],
                $item['image']
            ]);
        }

        $pdo->commit();

        // Vider le panier
        unset($_SESSION['cart']);
        unset($_SESSION['cart_count']);
        // Optionally clear location from session after order
        unset($_SESSION['last_known_location']);

        // Redirection vers confirmation
        header('Location: /projet/user/orders.php?order_id=' . $orderId);
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur lors de la commande: " . $e->getMessage();
        header('Location: checkout.php');
        exit;
    }
}
require_once ROOT_PATH . '/includes/header.php';

?>
<div class="container my-5">
    <h1 class="mb-4">Finaliser la Commande</h1>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" id="checkout-form" action="/projet/cart/checkout.php">
        <div class="row">
            <div class="col-md-7">
                <div class="neumorphic p-4 mb-4">
                    <h3 class="mb-3">Adresse de Livraison</h3>

                    <?php if (count($addresses) > 0): ?>
                        <?php foreach ($addresses as $address): ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio"
                                   name="shipping_address"
                                   id="address_<?= $address['id'] ?>"
                                   value="<?= $address['id'] ?>"
                                   <?= $address['is_default'] ? 'checked' : '' ?> required>
                            <label class="form-check-label" for="address_<?= $address['id'] ?>">
                                <strong><?= htmlspecialchars($address['first_name']) ?> <?= htmlspecialchars($address['last_name']) ?></strong><br>
                                <?= htmlspecialchars($address['address1']) ?><br>
                                <?= htmlspecialchars($address['zip_code']) ?> <?= htmlspecialchars($address['city']) ?><br>
                                <?= htmlspecialchars($address['country']) ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Aucune adresse enregistrée. <a href="/projet/user/profile.php">Ajoutez une adresse</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-5">
                <div class="neumorphic p-4">
                    <h3 class="mb-3">Récapitulatif</h3>
                    <table class="table">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?></td>
                            <td class="text-end"><?= number_format($item['price'] * $item['quantity'], 2) ?> DH</td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th>Total</th>
                            <th class="text-end"><?= number_format($total, 2) ?> DH</th>
                        </tr>
                    </table>

                    <?php if ($userLatitude !== null && $userLongitude !== null): ?>
                    <div class="mb-3">
                        <h5>Votre Localisation estimée:</h5>
                        <p class="mb-0">
                            Latitude: **<?= number_format($userLatitude, 6) ?>**<br>
                            Longitude: **<?= number_format($userLongitude, 6) ?>**
                        </p>
                        <small class="text-muted">Ceci sera enregistré avec votre commande.</small>
                    </div>
                    <?php endif; ?>

                    <h4 class="mt-4">Méthode de Paiement</h4>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash_on_delivery" checked>
                        <label class="form-check-label" for="cash">Paiement à la livraison</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="card" value="card">
                        <label class="form-check-label" for="card">Carte bancaire</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-4" id="pay-btn">Paiement</button>

                    <a href="/projet/user/orders.php" class="btn btn-outline-secondary w-100 mt-2">Voir la liste des commandes</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const cardRadio = document.getElementById('card');
    if (cardRadio.checked) {
        // Redirige vers la page de paiement carte avec les infos du formulaire
        e.preventDefault();
        // Envoie le formulaire en POST vers process.php (pour afficher le formulaire carte)
        this.action = '/projet/cart/process.php';
        this.submit();
    }
    // Sinon, laisse le POST sur checkout.php pour paiement à la livraison
});
</script>

<?php
// The extra HTML (advantages section, footer, and location script) needs to be placed
// after the main </div> that closes the container of checkout.php content,
// and before the </body> and </html> tags in includes/footer.php.
// If you want it inside checkout.php directly, ensure the layout is correct.

// Assuming includes/footer.php takes care of </body> and </html>
require_once ROOT_PATH . '/includes/footer.php';
?>