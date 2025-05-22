<?php
// Toujours démarrer la session UNE SEULE FOIS via session_init.php
require_once __DIR__ . '/session_init.php';

if (!defined('SITE_NAME')) define('SITE_NAME', 'Ma Boutique');
$pageTitle = $pageTitle ?? 'Boutique';

// Générer le token CSRF si besoin
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/projet/assets/css/neumorphic.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg neumorphic mb-12">
        <div class="container">
            <a class="navbar-brand col-sum-4" href="/projet/index.php">
                <video src="/projet/assets/vids/SHOP(5).mp4" class="logo" alt="Logo" loop playsinline autoplay height="80px" width="100%" style=" padding:4px; border-radius: 50px;"></video>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list" style="font-size: 2rem;"></i>
            </button>
  

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a class="nav-link" href="/projet/index.php">Home</a></li>
                    <li class="nav-item ml-auto"><a class="nav-link" href="#">About</a></li>
                    <li class="nav-item ml-auto"><a class="nav-link" href="#">Services</a></li>
                    <li class="nav-item ml-auto"><a class="nav-link" href="#">Products</a></li>
                    <li class="nav-item ml-auto"><a class="nav-link" href="#">Contact</a></li>
                </ul>
                <div class="social-icons ml-auto  d-lg-flex">
                    <a href="#" class="fab fa-facebook-f ml-5" aria-label="Facebook"></a>
                    <a href="#" class="fab fa-twitter ml-5" aria-label="Twitter"></a>
                    <a href="#" class="fab fa-linkedin-in ml-5" aria-label="LinkedIn"></a>
                    <a href="#" class="fab fa-instagram ml-5" aria-label="Instagram"></a>
                </div>
            <div class="d-flex ml-5">
                <a href="/projet/cart/view.php" class="btn btn-outline-primary me-2">
                    <i class="bi bi-cart"></i>
                    <span class="cart-count"><?= isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0 ?></span>
                </a>
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if (in_array($_SESSION['user']['role'], ['admin', 'superadmin'])): ?>
                        <a href="/projet/admin/dashboard.php" class="btn btn-primary">
                            <i class="bi bi-speedometer2"></i>
                            <span class="d-none d-md-inline">Tableau de bord</span>
                        </a>
                    <?php endif; ?>
                    <div class="dropdown ml-2">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="bi bi-person"></i>
                            <span class="d-none d-md-inline">Mon compte</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="/projet/user/profile.php">
                                <i class="bi bi-person"></i> Profil
                            </a>
                            <a class="dropdown-item" href="/projet/user/orders.php">
                                <i class="bi bi-receipt"></i> Mes commandes
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="/projet/user/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="#" class="btn btn-outline-primary me-2" data-toggle="modal" data-target="#registerModal">
                        <i class="bi bi-person-plus"></i>
                        <span class="d-none d-md-inline">S'inscrire</span>
                    </a>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#loginModal">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span class="d-none d-md-inline">Connexion</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Login Modal (Formulaire seulement) -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content neumorphism">
                <form action="/projet/user/login.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Connexion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="loginEmail">Email</label>
                            <input type="email" name="email" class="form-control" id="loginEmail" required>
                        </div>
                        <div class="form-group">
                            <label for="loginPassword">Mot de passe</label>
                            <input type="password" name="password" class="form-control" id="loginPassword" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Register Modal (Formulaire seulement) -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content neumorphism">
                <form action="/projet/user/register.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Inscription</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="registerName">Nom complet</label>
                            <input type="text" name="name" class="form-control" id="registerName" required minlength="3">
                        </div>
                        <div class="form-group">
                            <label for="registerEmail">Email</label>
                            <input type="email" name="email" class="form-control" id="registerEmail" required>
                        </div>
                        <div class="form-group">
                            <label for="registerPassword">Mot de passe</label>
                            <input type="password" name="password" class="form-control" id="registerPassword" required minlength="8">
                        </div>
                        <div class="form-group">
                            <label for="registerPasswordConfirm">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirm" class="form-control" id="registerPasswordConfirm" required minlength="8">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
require_once __DIR__ .  '/../includes/prev.html';
require_once __DIR__ .  '/../includes/dark.html';

?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>         