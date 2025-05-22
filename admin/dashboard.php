<?php 
require_once '../includes/config.php';
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
$page_title = "Tableau de bord Admin";

$menuCount = $pdo->query("SELECT COUNT(*) FROM menu")->fetchColumn();
$messagesCount = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$reservationsCount = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();

require_once '../includes/header.php';
?>

<main>
    <h1>Tableau de bord Admin</h1>
    <section class="admin-stats">
        <div class="stat-card">
            <h3>Plats</h3>
            <p><?php echo $menuCount; ?></p>
        </div>
        <div class="stat-card">
            <h3>Messages</h3>
            <p><?php echo $messagesCount; ?></p>
        </div>
        <div class="stat-card">
            <h3>Réservations</h3>
            <p><?php echo $reservationsCount; ?></p>
        </div>
    </section>
    <section class="admin-options">
        <a href="manage_menu.php" class="admin-btn">Gérer le Menu</a>
        <a href="messages.php" class="admin-btn">Voir les Messages</a>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>