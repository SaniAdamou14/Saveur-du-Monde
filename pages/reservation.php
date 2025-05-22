<?php 
$page_title = "Réservation";
require_once '../includes/config.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['country_code'] . $_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS);
    $date = $_POST['date']; // Format attendu : YYYY-MM-DD
    $time = $_POST['time']; // Format attendu : HH:MM
    $guests = (int)$_POST['guests'];

    // Validation des données
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $error = "Format de date invalide. Utilisez JJ/MM/AAAA.";
    } elseif (!preg_match('/^\d{2}:\d{2}$/', $time)) {
        $error = "Format d’heure invalide. Utilisez HH:MM.";
    } elseif ($guests < 1 || $guests > 20) {
        $error = "Le nombre d’invités doit être entre 1 et 20.";
    } else {
        try {
            if (!isset($pdo) || !$pdo) {
                throw new Exception("La connexion à la base de données n'est pas initialisée.");
            }

            $stmt = $pdo->prepare("INSERT INTO reservations (name, email, phone, reservation_date, reservation_time, guests) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $phone, $date, $time, $guests])) {
                $success = "Réservation effectuée avec succès !";
                
                // Confirmation par email (optionnel)
                $to = $email;
                $subject = "Confirmation de votre réservation - Les Saveurs du Monde";
                $message = "Bonjour $name,\n\nVotre réservation pour $guests personne(s) le $date à $time a été confirmée.\n\nMerci,\nL'équipe Les Saveurs du Monde";
                $headers = "From: no-reply@saveursdumonde.com";
                if (@mail($to, $subject, $message, $headers)) {
                    $success .= " Une confirmation a été envoyée par email.";
                } else {
                    $warning = "L’email de confirmation n’a pas pu être envoyé (serveur non configuré).";
                }
            } else {
                $error = "Une erreur est survenue lors de l'enregistrement.";
            }
        } catch (PDOException $e) {
            $error = "Erreur SQL : " . $e->getMessage();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<main>
    <h1>Réserver une table</h1>
    <section class="reservation-form">
        <div class="form-container">
            <?php 
            if (isset($success)) echo "<p class='success'>$success</p>";
            if (isset($warning)) echo "<p class='warning'>$warning</p>";
            if (isset($error)) echo "<p class='error'>$error</p>";
            ?>
            <div class="progress-bar-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>
            <form method="POST">
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> Votre nom</label>
                    <input type="text" id="name" name="name" placeholder="Entrez votre nom" required>
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Votre email</label>
                    <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
                </div>
                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i> Numéro de téléphone</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <select name="country_code" id="country_code">
                            <option value="+227" selected>+227 (Niger)</option>
                            <option value="+33">+33 (France)</option>
                            <option value="+1">+1 (USA)</option>
                        </select>
                        <input type="tel" id="phone" name="phone" placeholder="12345678" pattern="[0-9]{6,}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date"><i class="fas fa-calendar-alt"></i> Date</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="time"><i class="fas fa-clock"></i> Heure</label>
                    <input type="time" id="time" name="time" required>
                </div>
                <div class="form-group">
                    <label for="guests"><i class="fas fa-users"></i> Nombre de personnes</label>
                    <input type="number" id="guests" name="guests" min="1" max="20" required>
                </div>
                <button type="submit" class="form-submit-btn"><i class="fas fa-chair"></i> Réserver</button>
            </form>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>