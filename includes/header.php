<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Saveurs du Monde - <?php echo $page_title ?? 'Accueil'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="<?php echo isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'on' ? 'dark-mode' : ''; ?>">
<header>
    <nav role="navigation" aria-label="Menu principal">
        <div class="logo">Les Saveurs du Monde</div>
        <button class="hamburger" aria-label="Ouvrir/Fermer le menu">☰</button>
        <ul class="nav-links">
            <?php if (isset($_SESSION['admin'])): ?>
                <li><a href="/admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                <li><a href="/admin/manage_menu.php"><i class="fas fa-utensils"></i> Gérer le Menu</a></li>
                <li><a href="/admin/messages.php"><i class="fas fa-comment"></i> Messages</a></li>
                <li><a href="/admin/reservations.php"><i class="fas fa-chair"></i> Réservations</a></li>
                <li><a href="/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            <?php else: ?>
                <li><a href="/index.php"><i class="fas fa-home"></i> Accueil</a></li>
                <li><a href="/pages/menu.php"><i class="fas fa-utensils"></i> Menu</a></li>
                <li><a href="/pages/favorites.php"><i class="fas fa-star"></i> Favoris</a></li>
                <li><a href="/pages/about.php"><i class="fas fa-info-circle"></i> À propos</a></li>
                <li><a href="/pages/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                <li><a href="/pages/reservation.php"><i class="fas fa-chair"></i> Réservation</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/pages/messages.php"><i class="fas fa-comment-dots"></i> Mes Messages</a></li>
                    <li><a href="/pages/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/pages/login.php"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
                    <li><a href="/pages/register.php"><i class="fas fa-user-plus"></i> Inscription</a></li>
                <?php endif; ?>
                <li><a href="/admin/login.php" class="admin-login-link"><i class="fas fa-user-shield"></i> Connexion Admin</a></li>
            <?php endif; ?>
            <!-- <li><button id="dark-mode-toggle" class="dark-mode-btn" aria-label="Activer/Désactiver le mode sombre">🌙</button></li> -->
            <li><button id="dark-mode-toggle" class="dark-mode-btn" aria-label="Activer/Désactiver le mode sombre">🌙</button></li>
        </ul>
    </nav>
</header>

<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then(() => console.log('Service Worker enregistré'))
        .catch(error => console.error('Erreur d’enregistrement:', error));
}
</script>