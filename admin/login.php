<?php 
require_once '../includes/config.php';
$page_title = "Connexion Admin";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Identifiants incorrects";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <main>
        <section class="admin-login">
            <div class="form-container">
                <h2>Connexion Admin</h2>
                <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" placeholder="Entrez votre identifiant" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <button type="submit" class="form-submit-btn">Se connecter</button>
                </form>
                <p><a href="/index.php">Retour à l'accueil</a></p>
            </div>
        </section>
    </main>
</body>
</html>