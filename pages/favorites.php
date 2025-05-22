<?php 
$page_title = "Favoris";
require_once '../includes/config.php';
require_once '../includes/header.php';

try {
    if (!isset($pdo) || !$pdo) {
        throw new Exception("La connexion à la base de données n'est pas initialisée.");
    }
    $stmt = $pdo->query("SELECT * FROM menu");
    $all_menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<main><h1>Erreur</h1><p class='error'>Une erreur est survenue : " . htmlspecialchars($e->getMessage()) . "</p></main>";
    require_once '../includes/footer.php';
    exit;
}
?>

<main>
    <h1>Vos Favoris</h1>
    <section id="favorites-container" class="menu-container">
        <!-- Les favoris seront insérés ici par JavaScript ou affichés directement -->
    </section>
    <button id="clear-favorites" style="display: none; margin: 2rem auto; padding: 1rem; background: #e74c3c; color: white; border: none; border-radius: 5px;"><i class="fas fa-trash"></i> Vider les favoris</button>

    <!-- Tous les items disponibles pour JavaScript (cachés) -->
    <div id="all-menu-items" style="display: none;">
        <?php foreach ($all_menu_items as $item): ?>
            <div class="menu-item" data-id="<?php echo htmlspecialchars($item['id']); ?>" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                <img src="/assets/images/<?php echo htmlspecialchars($item['image'] ?? 'restaurant.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                <p><?php echo htmlspecialchars($item['description']); ?></p>
                <span class="price"><?php echo number_format($item['price'], 2); ?> €</span>
                <button class="add-to-fav"><i class="fas fa-star"></i> Retirer des favoris</button>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>