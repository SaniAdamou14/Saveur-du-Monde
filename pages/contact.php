<?php 
$page_title = "Contact";
require_once '../includes/config.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_SPECIAL_CHARS);
    
    $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $email, $message])) {
        $success = "Message envoyé avec succès!";
    } else {
        $error = "Une erreur est survenue.";
    }
}
?>

<main>
    <h1>Contactez-nous</h1>
    <section class="contact-form">
        <div class="form-container">
            <?php 
            if (isset($success)) echo "<p class='success'>$success</p>";
            if (isset($error)) echo "<p class='error'>$error</p>";
            ?>
            <div class="progress-bar-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            <form method="POST" id="contact-form">
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Votre nom</label>
                    <input type="text" id="name" name="name" placeholder="Entrez votre nom" required>
                </div>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Votre email</label>
                        <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="message"><i class="fas fa-comment-dots"></i> Votre message</label>
                    <textarea id="message" name="message" placeholder="Écrivez votre message" required></textarea>
                </div>
                <button type="submit" class="form-submit-btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
            </form>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <p>Connectez-vous pour suivre vos messages : <a href="/pages/login.php">Connexion</a> | <a href="/pages/register.php">Inscription</a></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>