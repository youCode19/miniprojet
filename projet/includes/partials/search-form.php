<form method="GET" action="">
  <div class="input-group neumorphic p-1 rounded-pill flex-wrap gap-2">
    <!-- Catégories -->
    <div class="input-group-prepend">
      <select name="category" class="form-control neumorphic">
        <option value="">Toutes catégories</option>
        <?php
        $allCategories = $pdo->query("
          SELECT id, name, slug
          FROM categories
          WHERE parent_id IS NULL
          ORDER BY name
        ")->fetchAll();
        foreach ($allCategories as $cat): ?>
          <option value="<?= htmlspecialchars($cat['slug'], ENT_QUOTES, 'UTF-8') ?>" <?= (isset($_GET['category']) && $_GET['category'] === $cat['slug']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Champ de recherche -->
    <input type="text" name="q" class="form-control neumorphic-inset" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" required>

    <!-- Filtre de tri -->
    <div class="input-group-prepend">
      <select name="sort" class="form-control neumorphic">
        <option value="">Trier par</option>
        <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'price_asc') ? 'selected' : '' ?>>Prix croissant</option>
        <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'price_desc') ? 'selected' : '' ?>>Prix décroissant</option>
        <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'name_asc') ? 'selected' : '' ?>>Nom A-Z</option>
        <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] === 'name_desc') ? 'selected' : '' ?>>Nom Z-A</option>
      </select>
    </div>

    <!-- Bouton de recherche -->
    <div class="input-group-append">
      <button type="submit" class="btn btn-primary neumorphic">
        <i class="fas fa-search"></i> Rechercher
      </button>
    </div>
  </div>
</form>
