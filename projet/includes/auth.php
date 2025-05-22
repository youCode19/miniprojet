<?php
// NE PAS démarrer la session ici !
// La session doit être démarrée UNE SEULE FOIS dans includes/session_init.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/config.php';


/**
 * Authentifie un utilisateur avec email et mot de passe
 */
function authenticateUser($email, $password) {
    global $pdo;

    if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
        if (time() - $_SESSION['last_attempt_time'] < 300) {
            return false;
        } else {
            unset($_SESSION['login_attempts']);
            unset($_SESSION['last_attempt_time']);
        }
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([strtolower(trim($email))]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'last_login' => date('Y-m-d H:i:s')
            ];

            $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")
                ->execute([$user['id']]);

            return $user['role'];
        }

        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $_SESSION['last_attempt_time'] = time();

        return false;
    } catch (PDOException $e) {
        error_log("Erreur d'authentification: " . $e->getMessage());
        return false;
    }
}

/**
 * Vérifie l'accès admin
 */
function checkAdminAccess() {
    if (!isset($_SESSION['user']['id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        $_SESSION['error'] = "Veuillez vous connecter";
        header('Location: /projet/index.php');
        exit();
    }

    if (!in_array($_SESSION['user']['role'], ['admin', 'superadmin'])) {
        $_SESSION['error'] = "Permissions insuffisantes";
        header('Location: /projet/index.php');
        exit();
    }
}

/**
 * Vérifie si un utilisateur est connecté
 */
function isLoggedIn() {
    return isset($_SESSION['user']);
}

/**
 * Déconnecte l'utilisateur
 */
function logout() {
    session_unset();
    session_destroy();
    header('Location: /projet/index.php');
    exit();
}

/**
 * Enregistre un nouvel utilisateur
 */
function registerUser($name, $email, $password, $role = 'client') {
    global $pdo;

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            throw new Exception("Email déjà utilisé");
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword, $role]);

        $userId = $pdo->lastInsertId();
        $pdo->commit();

        return $userId;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Erreur d'inscription: " . $e->getMessage());
        return false;
    }
}
