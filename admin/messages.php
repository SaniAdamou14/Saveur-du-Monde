<?php 
require_once '../includes/config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
$page_title = "Messages reçus";

// Vérifier la connexion PDO
if (!isset($pdo) || !$pdo) {
    die("Erreur : La connexion à la base de données n'est pas initialisée.");
}

// Gestion de la réponse
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
    $message_id = filter_var($_POST['message_id'], FILTER_VALIDATE_INT);
    $admin_reply = filter_var($_POST['admin_reply'], FILTER_SANITIZE_SPECIAL_CHARS);

    if ($message_id && $admin_reply) {
        try {
            $stmt = $pdo->prepare("UPDATE contact_messages SET admin_reply = ?, reply_date = NOW() WHERE id = ?");
            if ($stmt->execute([$admin_reply, $message_id])) {
                $success = "Réponse envoyée avec succès !";
            } else {
                $error = "Erreur lors de l’envoi de la réponse.";
            }
        } catch (PDOException $e) {
            $error = "Erreur SQL : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir le champ de réponse.";
    }
}

// Gestion de la suppression
if (isset($_GET['delete'])) {
    $message_id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    try {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        if ($stmt->execute([$message_id])) {
            $success = "Message supprimé avec succès !";
        } else {
            $error = "Erreur lors de la suppression.";
        }
    } catch (PDOException $e) {
        $error = "Erreur SQL : " . $e->getMessage();
    }
}

// Récupérer les messages
try {
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des messages : " . $e->getMessage();
    $messages = [];
}

require_once '../includes/header.php';
?>

<main>
    <h1>Messages reçus</h1>
    <?php 
    if (isset($success)) echo "<p class='success'>$success</p>";
    if (isset($error)) echo "<p class='error'>$error</p>";
    ?>
    <section class="message-list">
        <?php if (empty($messages)): ?>
            <p>Aucun message pour le moment.</p>
        <?php else: ?>
            <div class="message-grid">
                <?php foreach ($messages as $message): ?>
                    <div class="message-card">
                        <div class="message-field">
                            <span class="field-label"><i class="fas fa-user"></i> Nom :</span>
                            <span><?php echo htmlspecialchars($message['name']); ?></span>
                        </div>
                        <div class="message-field">
                            <span class="field-label"><i class="fas fa-envelope"></i> Email :</span>
                            <span><?php echo htmlspecialchars($message['email']); ?></span>
                        </div>
                        <div class="message-field">
                            <span class="field-label"><i class="fas fa-comment-dots"></i> Message :</span>
                            <span><?php echo htmlspecialchars($message['message']); ?></span>
                        </div>
                        <div class="message-field">
                            <span class="field-label"><i class="fas fa-calendar-alt"></i> Date :</span>
                            <span><?php echo $message['created_at']; ?></span>
                        </div>
                        <div class="message-field">
                            <span class="field-label"><i class="fas fa-reply"></i> Réponse :</span>
                            <?php if ($message['admin_reply']): ?>
                                <p><?php echo htmlspecialchars($message['admin_reply']); ?></p>
                                <small>Répondu le <?php echo $message['reply_date']; ?></small>
                            <?php else: ?>
                                <form method="POST">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <textarea name="admin_reply" placeholder="Votre réponse" rows="3" required></textarea>
                                    <button type="submit" name="reply" class="form-submit-btn"><i class="fas fa-reply"></i> Répondre</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <div class="message-field">
                            <span class="field-label"><i class="fas fa-cog"></i> Action :</span>
                            <a href="?delete=<?php echo $message['id']; ?>" class="delete-btn" onclick="return confirm('Supprimer ce message ?');"><i class="fas fa-trash"></i> Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>