<?php
define('ROOT_PATH', dirname(__DIR__)); // ou dirname(__FILE__) selon ta structure
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/config.php';
require_once ROOT_PATH . '/includes/auth.php';
// Récupérer les informations de l'utilisateur
$userId = $_SESSION['user']['id'];
$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$userId]);
$user = $user->fetch();

// Récupérer les adresses
$addresses = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC");
$addresses->execute([$userId]);

// Mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $userId
    ]);
    $_SESSION['user']['name'] = $_POST['name'];
    $_SESSION['user']['email'] = $_POST['email'];
    $_SESSION['success'] = "Profil mis à jour avec succès";
    header("Location: profile.php");
    exit;
}

// Ajout d'une adresse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $isDefault = isset($_POST['is_default']) ? 1 : 0;
    
    // Si c'est l'adresse par défaut, désactiver les autres
    if ($isDefault) {
        $pdo->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO addresses (
            user_id, type, first_name, last_name, company,
            address1, address2, city, state, zip_code, country,
            phone, is_default
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $userId,
        $_POST['type'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['company'],
        $_POST['address1'],
        $_POST['address2'],
        $_POST['city'],
        $_POST['state'],
        $_POST['zip_code'],
        $_POST['country'],
        $_POST['phone'],
        $isDefault
    ]);
    
    $_SESSION['success'] = "Adresse ajoutée avec succès";
    header("Location: profile.php");
    exit;
}

// Mise à jour d'une adresse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_address'])) {
    $addressId = $_POST['address_id'];
    $isDefault = isset($_POST['is_default']) ? 1 : 0;

    // Si c'est l'adresse par défaut, désactiver les autres
    if ($isDefault) {
        $pdo->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
    }

    $stmt = $pdo->prepare("
        UPDATE addresses SET
            type = ?,
            first_name = ?,
            last_name = ?,
            company = ?,
            address1 = ?,
            address2 = ?,
            city = ?,
            state = ?,
            zip_code = ?,
            country = ?,
            phone = ?,
            is_default = ?
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([
        $_POST['type'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['company'],
        $_POST['address1'],
        $_POST['address2'],
        $_POST['city'],
        $_POST['state'],
        $_POST['zip_code'],
        $_POST['country'],
        $_POST['phone'],
        $isDefault,
        $addressId,
        $userId
    ]);
    $_SESSION['success'] = "Adresse mise à jour avec succès";
    header("Location: profile.php");
    exit;
}

// Suppression d'une adresse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_address'])) {
    $addressId = $_POST['address_id'];
    $stmt = $pdo->prepare("DELETE FROM addresses WHERE id = ? AND user_id = ?");
    $stmt->execute([$addressId, $userId]);
    $_SESSION['success'] = "Adresse supprimée avec succès";
    header("Location: profile.php");
    exit;
}

require_once ROOT_PATH . '/includes/header.php';

?>



<div class="container my-5">
    <h1 class="mb-4">Mon Profil</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row">
        <!-- Colonne Profil -->
        <div class="col-md-4">
            <div class="neumorphic p-4 mb-4">
                <h3 class="mb-3">Informations Personnelles</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nom Complet</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>

        <!-- Colonne Adresses -->
        <div class="col-md-8">
            <div class="neumorphic p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Mes Adresses</h3>
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addAddressModal">
                        <i class="fas fa-plus"></i> Ajouter une adresse
                    </button>
                  
                </div>
                <?php if ($addresses->rowCount() > 0): ?>
                    <div class="row">
                        <?php foreach ($addresses as $address): ?>
                        <div class="col-md-6 mb-3">
                            <div class="neumorphic p-3">
                                <div class="d-flex justify-content-between">
                                    <h5><?= $address['type'] === 'shipping' ? 'Livraison' : 'Facturation' ?></h5>
                                    <?php if ($address['is_default']): ?>
                                        <span class="badge bg-primary">Par défaut</span>
                                    <?php endif; ?>
                                </div>
                                <p class="mb-1">
                                    <?= htmlspecialchars($address['first_name']) ?> <?= htmlspecialchars($address['last_name']) ?><br>
                                    <?= $address['company'] ? htmlspecialchars($address['company']) . '<br>' : '' ?>
                                    <?= htmlspecialchars($address['address1']) ?><br>
                                    <?= $address['address2'] ? htmlspecialchars($address['address2']) . '<br>' : '' ?>
                                    <?= htmlspecialchars($address['zip_code']) ?> <?= htmlspecialchars($address['city']) ?><br>
                                    <?= htmlspecialchars($address['country']) ?><br>
                                    <?= htmlspecialchars($address['phone']) ?>
                                </p>
                                <div class="mt-2">
                                    <button 
                                        class="btn btn-sm btn-outline-primary edit-address-btn"
                                        data-toggle="modal"
                                        data-target="#updateAddressModal"
                                        data-id="<?= $address['id'] ?>"
                                        data-type="<?= htmlspecialchars($address['type']) ?>"
                                        data-first_name="<?= htmlspecialchars($address['first_name']) ?>"
                                        data-last_name="<?= htmlspecialchars($address['last_name']) ?>"
                                        data-company="<?= htmlspecialchars($address['company']) ?>"
                                        data-address1="<?= htmlspecialchars($address['address1']) ?>"
                                        data-address2="<?= htmlspecialchars($address['address2']) ?>"
                                        data-zip_code="<?= htmlspecialchars($address['zip_code']) ?>"
                                        data-city="<?= htmlspecialchars($address['city']) ?>"
                                        data-state="<?= htmlspecialchars($address['state']) ?>"
                                        data-country="<?= htmlspecialchars($address['country']) ?>"
                                        data-phone="<?= htmlspecialchars($address['phone']) ?>"
                                        data-is_default="<?= $address['is_default'] ?>"
                                    >
                                        Modifier
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-outline-danger remove-address-btn"
                                        data-toggle="modal"
                                        data-target="#removeAddressModal"
                                        data-id="<?= $address['id'] ?>"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Aucune adresse enregistrée</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Adresse -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content neumorphic">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une adresse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Type d'adresse</label>
                        <select name="type" class="form-control" required>
                            <option value="shipping">Livraison</option>
                            <option value="billing">Facturation</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom*</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom*</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Société</label>
                        <input type="text" name="company" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse*</label>
                        <input type="text" name="address1" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Complément d'adresse</label>
                        <input type="text" name="address2" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code Postal*</label>
                            <input type="text" name="zip_code" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ville*</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pays*</label>
                        <input type="text" name="country" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone*</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_default" id="is_default" class="form-check-input">
                        <label for="is_default" class="form-check-label">Définir comme adresse par défaut</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="add_address" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Mise à jour Adresse -->
<div class="modal fade" id="updateAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content neumorphic">
            <div class="modal-header">
                <h5 class="modal-title">Mettre à jour une adresse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="address_id" id="update_address_id">
                    <div class="mb-3">
                        <label class="form-label">Type d'adresse</label>
                        <select name="type" id="update_type" class="form-control" required>
                            <option value="shipping">Livraison</option>
                            <option value="billing">Facturation</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom*</label>
                            <input type="text" name="first_name" id="update_first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom*</label>
                            <input type="text" name="last_name" id="update_last_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Société</label>
                        <input type="text" name="company" id="update_company" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse*</label>
                        <input type="text" name="address1" id="update_address1" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Complément d'adresse</label>
                        <input type="text" name="address2" id="update_address2" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Code Postal*</label>
                            <input type="text" name="zip_code" id="update_zip_code" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ville*</label>
                            <input type="text" name="city" id="update_city" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pays*</label>
                        <input type="text" name="country" id="update_country" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone*</label>
                        <input type="text" name="phone" id="update_phone" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_default" id="update_is_default" class="form-check-input">
                        <label for="update_is_default" class="form-check-label">Définir comme adresse par défaut</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="update_address" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Suppression Adresse -->
<div class="modal fade" id="removeAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content neumorphic">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer une adresse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="address_id" id="remove_address_id">
                    <p>Êtes-vous sûr de vouloir supprimer cette adresse ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" name="remove_address" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div class="container my-5">
    <h2>Liens vers tous les fichiers du projet</h2>
    <ul class="list-group">
        <li class="list-group-item"><a href="/projet/index.php">Accueil (index.php)</a></li>
        <li class="list-group-item"><a href="/projet/products/index.php">Liste des produits (products/index.php)</a></li>
        <li class="list-group-item"><a href="/projet/products/product.php">Détail produit (products/product.php)</a></li>
        <li class="list-group-item"><a href="/projet/products/category.php">Catégorie produit (products/category.php)</a></li>
        <li class="list-group-item"><a href="/projet/products/search.php">Recherche produit (products/search.php)</a></li>
        <li class="list-group-item"><a href="/projet/user/profile.php">Profil utilisateur (user/profile.php)</a></li>
        <li class="list-group-item"><a href="/projet/user/register.php">Inscription (user/register.php)</a></li>
        <li class="list-group-item"><a href="/projet/user/login.php">Connexion (user/login.php)</a></li>
        <li class="list-group-item"><a href="/projet/user/addresses/add.php">Ajouter une adresse (user/addresses/add.php)</a></li>
        <li class="list-group-item"><a href="/projet/cart/view.php">Voir le panier (cart/view.php)</a></li>
        <li class="list-group-item"><a href="/projet/cart/checkout.php">Passer à la caisse (cart/checkout.php)</a></li>
        <li class="list-group-item"><a href="/projet/cart/process.php">Processus commande (cart/process.php)</a></li>
        <li class="list-group-item"><a href="/projet/cart/add.php">Ajouter au panier (cart/add.php)</a></li>
        <li class="list-group-item"><a href="/projet/cart/update.php">Mettre à jour panier (cart/update.php)</a></li>
        <li class="list-group-item"><a href="/projet/cart/remove.php">Supprimer du panier (cart/remove.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/dashboard.php">Dashboard admin (admin/dashboard.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/products/add.php">Ajouter produit (admin/products/add.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/products/edit.php">Éditer produit (admin/products/edit.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/products/delete.php">Supprimer produit (admin/products/delete.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/products/list.php">Liste produits (admin/products/list.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/users/list.php">Liste utilisateurs (admin/users/list.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/users/edit.php">Éditer utilisateur (admin/users/edit.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/users/delete.php">Supprimer utilisateur (admin/users/delete.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/orders/list.php">Liste commandes (admin/orders/list.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/orders/view.php">Voir commande (admin/orders/view.php)</a></li>
        <li class="list-group-item"><a href="/projet/admin/orders/update_status.php">Changer statut commande (admin/orders/update_status.php)</a></li>
        <!-- Fichiers d'includes et assets (non cliquables directement) -->
        <!-- <li class="list-group-item"><code>includes/config.php</code></li>
        <li class="list-group-item"><code>includes/db.php</code></li>
        <li class="list-group-item"><code>includes/auth.php</code></li>
        <li class="list-group-item"><code>includes/header.php</code></li>
        <li class="list-group-item"><code>includes/footer.php</code></li>
        <li class="list-group-item"><code>includes/bootstrap.php</code></li>
        <li class="list-group-item"><code>includes/session_init.php</code></li>
        <li class="list-group-item"><code>includes/partials/product-card.php</code></li>
        <li class="list-group-item"><code>includes/partials/product-card-sm.php</code></li>
        <li class="list-group-item"><code>includes/search_functions.php</code></li>
        <li class="list-group-item"><code>assets/css/neumorphic.css</code></li>
        <li class="list-group-item"><code>assets/js/cart.js</code></li>
        <li class="list-group-item"><code>assets/js/search.js</code></li>
    </ul>
</div> -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pré-remplir le modal de mise à jour
    document.querySelectorAll('.edit-address-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('update_address_id').value = this.dataset.id;
            document.getElementById('update_type').value = this.dataset.type;
            document.getElementById('update_first_name').value = this.dataset.first_name;
            document.getElementById('update_last_name').value = this.dataset.last_name;
            document.getElementById('update_company').value = this.dataset.company;
            document.getElementById('update_address1').value = this.dataset.address1;
            document.getElementById('update_address2').value = this.dataset.address2;
            document.getElementById('update_zip_code').value = this.dataset.zip_code;
            document.getElementById('update_city').value = this.dataset.city;
            document.getElementById('update_state').value = this.dataset.state;
            document.getElementById('update_country').value = this.dataset.country;
            document.getElementById('update_phone').value = this.dataset.phone;
            document.getElementById('update_is_default').checked = this.dataset.is_default == "1";
        });
    });

    // Pré-remplir le modal de suppression
    document.querySelectorAll('.remove-address-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('remove_address_id').value = this.dataset.id;
        });
    });
});
</script>

<?php require_once ROOT_PATH . '/includes/footer.php'; ?>