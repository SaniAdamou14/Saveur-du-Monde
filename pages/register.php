<?php 
$page_title = "Inscription";
require_once '../includes/config.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $error = "Erreur lors de l'inscription.";
        }
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<main>
    <h1>Inscription</h1>
    <section class="form-container">
        <?php 
        if (isset($success)) echo "<p class='success'>$success</p>";
        if (isset($error)) echo "<p class='error'>$error</p>";
        ?>
        <form method="POST">
            <div class="form-group">
                <label for="name"><i class="fas fa-user"></i> Nom</label>
                <input type="text" id="name" name="name" placeholder="Votre nom" required>
            </div>
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" placeholder="Votre email" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="form-submit-btn"><i class="fas fa-user-plus"></i> S'inscrire</button>
        </form>
        <p>Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>.</p>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>