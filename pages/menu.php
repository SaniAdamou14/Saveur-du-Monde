<?php 
$page_title = "Menu";
require_once '../includes/config.php';
require_once '../includes/header.php'; 

$itemsPerPage = 6;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;

try {
    if (!isset($pdo) || !$pdo) {
        throw new Exception("La connexion à la base de données n'est pas initialisée.");
    }

    $stmt = $pdo->query("SELECT COUNT(*) FROM menu");
    $totalItems = $stmt->fetchColumn();
    $totalPages = ceil($totalItems / $itemsPerPage);

    if ($page > $totalPages && $totalPages > 0) {
        $page = $totalPages;
        $offset = ($page - 1) * $itemsPerPage;
    }

    $stmt = $pdo->prepare("SELECT * FROM menu ORDER BY category LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<main><h1>Erreur</h1><p class='error'>Une erreur est survenue : " . htmlspecialchars($e->getMessage()) . "</p></main>";
    require_once '../includes/footer.php';
    exit;
}

// Traitement de l’ajout de commentaire
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment']) && isset($_SESSION['user_id'])) {
    $menu_id = filter_var($_POST['menu_id'], FILTER_VALIDATE_INT);
    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_SPECIAL_CHARS);
    $rating = filter_var($_POST['rating'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]]);
    $user_id = $_SESSION['user_id'];

    if ($menu_id && $comment && $rating) {
        try {
            $stmt = $pdo->prepare("INSERT INTO menu_comments (menu_id, comment, rating, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$menu_id, $comment, $rating]);
            $success = "Commentaire ajouté avec succès !";
        } catch (PDOException $e) {
            $error = "Erreur lors de l’ajout du commentaire : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs du commentaire.";
    }
}
?>

<main>
    <h1>Notre Menu</h1>
    <?php 
    if (isset($success)) echo "<p class='success'>$success</p>";
    if (isset($error)) echo "<p class='error'>$error</p>";
    ?>
    <section class="menu-search">
        <div class="form-group">
            <label for="menu-search"><i class="fas fa-search"></i> Rechercher</label>
            <input type="text" id="menu-search" placeholder="Rechercher un plat...">
        </div>
        <div class="form-group">
            <label for="category-filter"><i class="fas fa-filter"></i> Filtrer par catégorie :</label>
            <select id="category-filter">
                <option value="all">Toutes</option>
                <?php
                try {
                    $categories = $pdo->query("SELECT DISTINCT category FROM menu ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($categories as $category) {
                        echo "<option value='" . htmlspecialchars($category) . "'>" . htmlspecialchars($category) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option value=''>Erreur de chargement des catégories</option>";
                }
                ?>
            </select>
        </div>
    </section>
    <section class="menu-container">
        <?php if (empty($menu_items)): ?>
            <p>Aucun plat disponible pour le moment.</p>
        <?php else: ?>
            <?php foreach ($menu_items as $item): ?>
                <div class="menu-item" data-id="<?php echo htmlspecialchars($item['id']); ?>" data-category="<?php echo htmlspecialchars($item['category']); ?>">
                    <img src="/assets/images/<?php echo htmlspecialchars($item['image'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                    <span class="price"><?php echo number_format($item['price'], 2); ?> €</span>
                    <button class="add-to-fav"><i class="fas fa-star"></i> Ajouter aux favoris</button>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form class="comment-form" method="POST" data-id="<?php echo htmlspecialchars($item['id']); ?>">
                            <input type="hidden" name="menu_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                            <textarea name="comment" placeholder="Votre commentaire" required></textarea>
                            <select name="rating" required>
                                <option value="">Note</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> étoile<?php echo $i > 1 ? 's' : ''; ?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" name="add_comment" class="form-submit-btn"><i class="fas fa-comment"></i> Commenter</button>
                        </form>
                    <?php endif; ?>
                    <div class="comments" data-id="<?php echo htmlspecialchars($item['id']); ?>">
                        <?php
                        try {
                            $stmt = $pdo->prepare("SELECT comment, rating, created_at FROM menu_comments WHERE menu_id = ? ORDER BY rating DESC, created_at DESC LIMIT 3");
                            $stmt->execute([$item['id']]);
                            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (empty($comments)) {
                                echo "<p>Aucun commentaire pour ce plat.</p>";
                            } else {
                                foreach ($comments as $comment) {
                                    echo "<p><strong>" . htmlspecialchars($comment['rating']) . "/5</strong>: " . htmlspecialchars($comment['comment']) . " <small>(" . $comment['created_at'] . ")</small></p>";
                                }
                            }
                        } catch (PDOException $e) {
                            echo "<p>Erreur lors du chargement des commentaires : " . htmlspecialchars($e->getMessage()) . "</p>";
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
    <?php if ($totalPages > 1): ?>
        <section class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="pagination-btn"><i class="fas fa-arrow-left"></i> Précédent</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="pagination-btn <?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="pagination-btn">Suivant <i class="fas fa-arrow-right"></i></a>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>

<?php require_once '../includes/footer.php'; ?>