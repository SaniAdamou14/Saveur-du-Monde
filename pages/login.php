<?php 
$page_title = "Connexion";
require_once '../includes/config.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: /pages/messages.php");
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<main>
    <h1>Connexion</h1>
    <section class="form-container">
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" placeholder="Votre email" required>
            </div>
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="form-submit-btn"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
        </form>
        <p>Pas de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>