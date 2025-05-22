<?php 
require_once '../includes/config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
$page_title = "Réservations";

if (!isset($pdo) || !$pdo) {
    die("Erreur : La connexion à la base de données n'est pas initialisée.");
}

// Gestion de la suppression
if (isset($_GET['delete'])) {
    $reservation_id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);
    try {
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        if ($stmt->execute([$reservation_id])) {
            $success = "Réservation supprimée avec succès !";
        } else {
            $error = "Erreur lors de la suppression.";
        }
    } catch (PDOException $e) {
        $error = "Erreur SQL : " . $e->getMessage();
    }
}

// Récupérer les réservations
try {
    $stmt = $pdo->query("SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des réservations : " . $e->getMessage();
    $reservations = [];
}

require_once '../includes/header.php';
?>

<main>
    <h1>Gestion des Réservations</h1>
    <?php 
    if (isset($success)) echo "<p class='success'>$success</p>";
    if (isset($error)) echo "<p class='error'>$error</p>";
    ?>
    <section class="reservation-list">
        <?php if (empty($reservations)): ?>
            <p>Aucune réservation pour le moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> Nom</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-phone"></i> Téléphone</th>
                        <th><i class="fas fa-calendar-alt"></i> Date</th>
                        <th><i class="fas fa-clock"></i> Heure</th>
                        <th><i class="fas fa-users"></i> Invités</th>
                        <th><i class="fas fa-cog"></i> Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['reservation_time']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['guests']); ?></td>
                            <td>
                                <a href="?delete=<?php echo $reservation['id']; ?>" class="delete-btn" onclick="return confirm('Supprimer cette réservation ?');"><i class="fas fa-trash"></i> Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>