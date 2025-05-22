<?php 
$page_title = "Accueil";
require_once 'includes/header.php'; 
?>
<main class>
    <section class="hero">
        <h1>Bienvenue aux Saveurs du Monde</h1>
        <p>Explorez une cuisine authentique venue des quatre coins du globe</p>
        <button class="cta-btn" onclick="window.location.href='/pages/menu.php'">Découvrir le Menu</button>
    </section>
    <section class="features">
        <div class="feature-card">
            <h3>Saveurs Uniques</h3>
            <p>Découvrez des plats exquis préparés avec passion.</p>
        </div>
        <div class="feature-card">
            <h3>Ambiance Chaleureuse</h3>
            <p>Un cadre convivial pour tous vos moments.</p>
        </div>
        <div class="feature-card">
            <h3>Réservations</h3>
            <p>Contactez-nous pour réserver votre table !</p>
        </div>
    </section>
</main>
<?php require_once 'includes/footer.php'; ?>