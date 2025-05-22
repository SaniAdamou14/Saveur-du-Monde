<?php 
require_once '../includes/config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
$page_title = "Gestion du Menu";

if (!isset($pdo) || !$pdo) {
    die("Erreur : La connexion à la base de données n'est pas initialisée.");
}

// Ajouter un plat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_SPECIAL_CHARS);
    $image = $_FILES['image']['name'];

    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");
    }

    $stmt = $pdo->prepare("INSERT INTO menu (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$name, $description, $price, $category, $image])) {
        $success = "Plat ajouté avec succès !";
    } else {
        $error = "Erreur lors de l'ajout.";
    }
}

// Modifier un plat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_SPECIAL_CHARS);
    $image = $_FILES['image']['name'] ?: $_POST['existing_image'];

    if ($_FILES['image']['name']) {
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");
    }

    $stmt = $pdo->prepare("UPDATE menu SET name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?");
    if ($stmt->execute([$name, $description, $price, $category, $image, $id])) {
        $success = "Plat modifié avec succès !";
    } else {
        $error = "Erreur lors de la modification.";
    }
}

// Supprimer un plat
if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Plat supprimé avec succès !";
    } else {
        $error = "Erreur lors de la suppression.";
    }
}

// Récupérer les plats
$stmt = $pdo->query("SELECT * FROM menu ORDER BY category");
$menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<main>
    <h1>Gestion du Menu</h1>
    <?php 
    if (isset($success)) echo "<p class='success'>$success</p>";
    if (isset($error)) echo "<p class='error'>$error</p>";
    ?>

    <!-- Formulaire d'ajout -->
    <section class="add-menu-form">
        <div class="form-container">
            <h2>Ajouter un plat</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name"><i class="fas fa-utensils"></i> Nom</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description"><i class="fas fa-comment"></i> Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price"><i class="fas fa-euro-sign"></i> Prix</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="category"><i class="fas fa-list"></i> Catégorie</label>
                    <input type="text" id="category" name="category" required>
                </div>
                <div class="form-group">
                    <label for="image"><i class="fas fa-image"></i> Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div id="add-image-preview" style="margin-top: 1rem;"></div>
                </div>
                <button type="submit" name="add" class="form-submit-btn"><i class="fas fa-plus"></i> Ajouter</button>
            </form>
        </div>
    </section>

    <!-- Liste des plats avec édition -->
    <section class="menu-list">
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-utensils"></i> Nom</th>
                    <th><i class="fas fa-comment"></i> Description</th>
                    <th><i class="fas fa-euro-sign"></i> Prix</th>
                    <th><i class="fas fa-list"></i> Catégorie</th>
                    <th><i class="fas fa-image"></i> Image</th>
                    <th><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menu_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                        <td><?php echo htmlspecialchars($item['price']); ?> €</td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td><?php echo htmlspecialchars($item['image']); ?></td>
                        <td>
                            <a href="#edit-<?php echo $item['id']; ?>" class="edit-btn" onclick="showEditForm(<?php echo $item['id']; ?>)"><i class="fas fa-edit"></i> Modifier</a>
                            <a href="?delete=<?php echo $item['id']; ?>" class="delete-btn" onclick="return confirm('Supprimer ce plat ?');"><i class="fas fa-trash"></i> Supprimer</a>
                        </td>
                    </tr>
                    <!-- Formulaire d'édition (caché par défaut) -->
                    <tr id="edit-<?php echo $item['id']; ?>" style="display: none;">
                        <td colspan="6">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <input type="hidden" name="existing_image" value="<?php echo $item['image']; ?>">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Prix</label>
                                    <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($item['price']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Catégorie</label>
                                    <input type="text" name="category" value="<?php echo htmlspecialchars($item['category']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" id="edit-image-<?php echo $item['id']; ?>" name="image" accept="image/*">
                                    <div id="edit-image-preview-<?php echo $item['id']; ?>" style="margin-top: 1rem;">
                                        <img src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>" alt="Aperçu actuel" style="max-width: 200px; border-radius: 8px;">
                                    </div>
                                    <small>Actuelle : <?php echo htmlspecialchars($item['image']); ?></small>
                                </div>
                                <button type="submit" name="edit" class="form-submit-btn"><i class="fas fa-save"></i> Sauvegarder</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<script>
// Prévisualisation pour le formulaire d'ajout
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('add-image-preview');
    preview.innerHTML = ''; // Réinitialiser l'aperçu
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '200px';
            img.style.borderRadius = '8px';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});

// Prévisualisation pour chaque formulaire d'édition
<?php foreach ($menu_items as $item): ?>
document.getElementById('edit-image-<?php echo $item['id']; ?>').addEventListener('change', function(e) {
    const preview = document.getElementById('edit-image-preview-<?php echo $item['id']; ?>');
    preview.innerHTML = ''; // Réinitialiser l'aperçu
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '200px';
            img.style.borderRadius = '8px';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});
<?php endforeach; ?>

function showEditForm(id) {
    document.getElementById('edit-' + id).style.display = 'table-row';
}
</script>

<?php require_once '../includes/footer.php'; ?>