<?php
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '../../includes/session_init.php';
require_once ROOT_PATH . '../../includes/config.php';
require_once ROOT_PATH . '../../includes/db.php';
require_once ROOT_PATH . '../../includes/auth.php';

checkAdminAccess(); // Ensure only admins can access this page

$pageTitle = "Avis clients";

$reviews = [];
try {
    // Fetch reviews along with user and product names
    $stmt = $pdo->prepare("
        SELECT 
            r.*, 
            u.name as user_name, 
            p.name as product_name 
        FROM reviews r
        LEFT JOIN users u ON r.user_id = u.id
        LEFT JOIN products p ON r.product_id = p.id
        ORDER BY r.created_at DESC
    ");
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur DB avis clients: " . $e->getMessage());
    $_SESSION['error'] = "Erreur de chargement des avis clients.";
}

// Handle review actions (e.g., approve, delete)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $review_id = filter_input(INPUT_POST, 'review_id', FILTER_VALIDATE_INT);
    $action = $_POST['action'];

    if ($review_id) {
        try {
            if ($action === 'approve') {
                $stmt = $pdo->prepare("UPDATE reviews SET status = 'approved' WHERE id = :id");
                $stmt->bindParam(':id', $review_id, PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Avis approuvé avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de l'approbation de l'avis.";
                }
            } elseif ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = :id");
                $stmt->bindParam(':id', $review_id, PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Avis supprimé avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression de l'avis.";
                }
            }
        } catch (PDOException $e) {
            error_log("Erreur DB action avis: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de l'action sur l'avis: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "ID d'avis invalide.";
    }
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

require_once ROOT_PATH . '../../includes/header.php';
require_once ROOT_PATH . '../../includes/prev.html';
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4">Avis clients</h1>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card neumorphic mb-4">
        <div class="card-header">
            <h5>Liste des avis</h5>
        </div>
        <div class="card-body">
            <?php if (empty($reviews)): ?>
                <p>Aucun avis client pour le moment.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Produit</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reviews as $review): ?>
                                <tr>
                                    <td><?= htmlspecialchars($review['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($review['user_name'] ?? 'Invité', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($review['product_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star-fill <?= ($i <= ($review['rating'] ?? 0)) ? 'text-warning' : 'text-secondary'; ?>"></i>
                                        <?php endfor; ?>
                                    </td>
                                    <td><?= nl2br(htmlspecialchars($review['comment'] ?? '', ENT_QUOTES, 'UTF-8')) ?></td>
                                    <td>
                                        <?php
                                            $status = $review['status'] ?? 'unknown'; // Provide a default status
                                            $status_class = '';
                                            switch ($status) {
                                                case 'pending': $status_class = 'bg-warning'; break;
                                                case 'approved': $status_class = 'bg-success'; break;
                                                case 'rejected': $status_class = 'bg-danger'; break;
                                                default: $status_class = 'bg-secondary'; break;
                                            }
                                        ?>
                                        <span class="badge <?= $status_class ?>"><?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8') ?></span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></td>
                                    <td>
                                        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="d-inline">
                                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                            <?php if (($review['status'] ?? 'unknown') === 'pending'): // Check status with null coalescing ?>
                                                <button type="submit" name="action" value="approve" class="btn btn-sm btn-success me-1" title="Approuver">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once ROOT_PATH . '../../includes/footer.php';
?>