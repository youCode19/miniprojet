<?php
define('ROOT_PATH', dirname(dirname(__DIR__)));
require_once ROOT_PATH . '/includes/session_init.php';
require_once ROOT_PATH . '/includes/auth.php';
require_once ROOT_PATH . '/includes/db.php';
checkAdminAccess();

// Pagination sécurisée
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Récupérer les utilisateurs
$users = $pdo->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT :offset, :perPage");
$users->bindValue(':offset', $offset, PDO::PARAM_INT);
$users->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$users->execute();

// Compter le total
$total = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalPages = ceil($total / $perPage);

require_once ROOT_PATH . '/includes/header.php';
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des Utilisateurs</h1>
    </div>

    <div class="neumorphic p-4">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Éditer</a>
                        <form action="delete.php" method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</div>

<?php require_once ROOT_PATH . '/includes/footer.php'; ?>