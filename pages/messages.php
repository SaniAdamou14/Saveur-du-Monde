<?php 
$page_title = "Vos Messages";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /pages/login.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE email = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_email']]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des messages : " . $e->getMessage();
    $messages = [];
}
?>

<main>
    <h1>Vos Messages</h1>
    <p>Connecté en tant que : <?php echo htmlspecialchars($_SESSION['user_email']); ?> | <a href="/pages/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></p>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <section class="message-list">
        <?php if (empty($messages)): ?>
            <p>Aucun message trouvé.</p>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <div class="message-card">
                    <p><strong><i class="fas fa-comment-dots"></i> Votre message :</strong> <?php echo htmlspecialchars($message['message']); ?></p>
                    <p><small><i class="fas fa-calendar-alt"></i> Envoyé le : <?php echo $message['created_at']; ?></small></p>
                    <?php if ($message['admin_reply']): ?>
                        <p><strong><i class="fas fa-reply"></i> Réponse de l’admin :</strong> <?php echo htmlspecialchars($message['admin_reply']); ?></p>
                        <p><small><i class="fas fa-calendar-alt"></i> Répondu le : <?php echo $message['reply_date']; ?></small></p>
                    <?php else: ?>
                        <p><em><i class="fas fa-hourglass-half"></i> En attente de réponse...</em></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>