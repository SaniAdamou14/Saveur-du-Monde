<?php 
$page_title = "À Propos";
require_once '../includes/config.php';
require_once '../includes/header.php';
?>

<main>
    <h1>À Propos de Nous</h1>
    <section class="about-hero">
        <div class="about-image-container">
            <img src="/assets/images/about-hero.jpg" alt="À Propos des Saveurs du Monde" loading="lazy">
            <div class="shape shape-circle"></div>
            <div class="shape shape-rectangle"></div>
            <div class="overlay"></div>
        </div>
    </section>
    <section class="about-content">
        <div class="content-wrapper">
            <h2>Notre Histoire</h2>
            <p>
                Bienvenue chez Les Saveurs du Monde, où chaque plat raconte une histoire. Fondé avec une passion pour la diversité culinaire, notre restaurant vous emmène dans un voyage gustatif à travers les continents. De l’Afrique à l’Asie, en passant par l’Europe et les Amériques, nous célébrons les traditions et les saveurs uniques qui unissent les cultures.
            </p>
            <h2>Notre Mission</h2>
            <p>
                Offrir une expérience culinaire authentique avec des ingrédients frais et locaux, tout en créant un espace chaleureux où chacun se sent chez soi. Que vous soyez amateur de plats épicés ou de douceurs réconfortantes, notre menu est conçu pour ravir vos papilles.
            </p>
        </div>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>