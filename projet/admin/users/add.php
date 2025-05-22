<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '../../includes/session_init.php';
require_once ROOT_PATH . '../../includes/config.php';
require_once ROOT_PATH . '../../includes/db.php';
require_once ROOT_PATH . '../../includes/auth.php';

checkAdminAccess(); // Ensure only admins can access this page

$pageTitle = "Ajouter un administrateur";

$name = $email = $password = $role = "";
$name_err = $email_err = $password_err = $role_err = "";
$success_msg = $error_msg = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Veuillez entrer un nom.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Veuillez entrer une adresse email.";
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = :email";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = trim($_POST["email"]);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $email_err = "Cet email est déjà utilisé.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                $error_msg = "Oops! Une erreur inattendue est survenue.";
                error_log("Error checking email existence: " . $stmt->errorInfo()[2]);
            }
            unset($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Veuillez entrer un mot de passe.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate role
    $allowed_roles = ['user', 'admin', 'superadmin']; // From SQL schema for `users` table
    if (empty(trim($_POST["role"])) || !in_array(trim($_POST["role"]), $allowed_roles)) {
        $role_err = "Veuillez sélectionner un rôle valide.";
    } else {
        $role = trim($_POST["role"]);
        // Only superadmin can add superadmin
        if ($role === 'superadmin' && $_SESSION['user']['role'] !== 'superadmin') {
            $role_err = "Seul un super administrateur peut ajouter un rôle de super administrateur.";
        }
    }

    // If there are no input errors, insert into database
    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($role_err)) {
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":role", $param_role, PDO::PARAM_STR);

            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Hashes the password
            $param_role = $role;

            if ($stmt->execute()) {
                $success_msg = "Nouvel administrateur ajouté avec succès.";
                // Clear form fields
                $name = $email = $password = $role = "";
            } else {
                $error_msg = "Erreur lors de l'ajout de l'administrateur.";
                error_log("Error inserting new admin: " . $stmt->errorInfo()[2]);
            }
            unset($stmt);
        }
    } else {
        $error_msg = "Veuillez corriger les erreurs dans le formulaire.";
    }
}

require_once ROOT_PATH . '../../includes/header.php';
require_once ROOT_PATH . '../../includes/prev.html';
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4">Ajouter un administrateur</h1>
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success" role="alert">
                    <?= $success_msg ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error_msg ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card neumorphic mb-4">
        <div class="card-header">
            <h5>Formulaire d'ajout d'administrateur</h5>
        </div>
        <div class="card-body">
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom :</label>
                    <input type="text" name="name" id="name" class="form-control <?= (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($name); ?>">
                    <span class="invalid-feedback"><?= $name_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email :</label>
                    <input type="email" name="email" id="email" class="form-control <?= (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($email); ?>">
                    <span class="invalid-feedback"><?= $email_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe :</label>
                    <input type="password" name="password" id="password" class="form-control <?= (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($password); ?>">
                    <span class="invalid-feedback"><?= $password_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Rôle :</label>
                    <select name="role" id="role" class="form-select <?= (!empty($role_err)) ? 'is-invalid' : ''; ?>">
                        <option value="">Sélectionner un rôle</option>
                        <option value="user" <?= ($role === 'user') ? 'selected' : ''; ?>>Utilisateur Standard</option>
                        <option value="admin" <?= ($role === 'admin') ? 'selected' : ''; ?>>Administrateur</option>
                        <?php if ($_SESSION['user']['role'] === 'superadmin'): ?>
                            <option value="superadmin" <?= ($role === 'superadmin') ? 'selected' : ''; ?>>Super Administrateur</option>
                        <?php endif; ?>
                    </select>
                    <span class="invalid-feedback"><?= $role_err; ?></span>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <a href="/projet/admin/dashboard.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once ROOT_PATH . '../../includes/footer.php';
?>