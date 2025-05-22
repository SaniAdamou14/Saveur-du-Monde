document.addEventListener('DOMContentLoaded', () => {
    // Son pour les interactions
    const clickSound = new Audio('/assets/sounds/click.mp3');

    // Fonction pour afficher les notifications toast
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Bouton CTA
    const ctaBtn = document.querySelector('.cta-btn');
    if (ctaBtn) {
        ctaBtn.addEventListener('click', () => {
            clickSound.play();
            window.location.href = '/pages/menu.php';
        });
    }

    // Animation des items du menu
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            item.style.transform = 'translateY(-5px)';
        });
        item.addEventListener('mouseleave', () => {
            item.style.transform = 'translateY(0)';
        });
    });

    // Gestion des favoris avec localStorage
    const favButtons = document.querySelectorAll('.add-to-fav');
    const loadFavorites = () => JSON.parse(localStorage.getItem('favorites')) || [];
    const saveFavorites = (favorites) => localStorage.setItem('favorites', JSON.stringify(favorites));

    favButtons.forEach(button => {
        const itemId = button.parentElement.dataset.id;
        const favorites = loadFavorites();
        button.textContent = favorites.includes(itemId) ? 'Retirer des favoris' : 'Ajouter aux favoris';
        button.style.backgroundColor = favorites.includes(itemId) ? '#e74c3c' : '#3498db';

        button.addEventListener('click', () => {
            clickSound.play();
            let favorites = loadFavorites();
            if (favorites.includes(itemId)) {
                favorites = favorites.filter(id => id !== itemId);
                button.textContent = 'Ajouter aux favoris';
                button.style.backgroundColor = '#3498db';
                showToast(`Plat #${itemId} retiré des favoris !`);
            } else {
                favorites.push(itemId);
                button.textContent = 'Retirer des favoris';
                button.style.backgroundColor = '#e74c3c';
                showToast(`Plat #${itemId} ajouté aux favoris !`);
            }
            saveFavorites(favorites);
        });
    });

    // Recherche dans le menu
    const searchInput = document.getElementById('menu-search');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const search = e.target.value.toLowerCase();
            menuItems.forEach(item => {
                const name = item.querySelector('h3').textContent.toLowerCase();
                item.style.display = name.includes(search) ? 'block' : 'none';
            });
        });
    }

    // Filtre par catégorie dans le menu
    const categoryFilter = document.getElementById('category-filter');
    if (categoryFilter) {
        categoryFilter.addEventListener('change', (e) => {
            const selectedCategory = e.target.value;
            menuItems.forEach(item => {
                const itemCategory = item.dataset.category;
                item.style.display = (selectedCategory === 'all' || itemCategory === selectedCategory) ? 'block' : 'none';
            });
        });
    }

    // Mode sombre
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            clickSound.play();
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            darkModeToggle.textContent = isDark ? '☀️' : '🌙';
            document.cookie = `dark_mode=${isDark ? 'on' : 'off'};path=/;max-age=31536000`; // 1 an
        });
    }

    // Bouton Retour en haut
    const scrollTopBtn = document.getElementById('scroll-to-top');
    if (scrollTopBtn) {
        window.addEventListener('scroll', () => {
            scrollTopBtn.style.display = window.scrollY > 200 ? 'block' : 'none';
        });

        scrollTopBtn.addEventListener('click', () => {
            clickSound.play();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Gestion avancée des favoris (page favorites.php)
    const favoritesContainer = document.getElementById('favorites-container');
    const clearFavoritesBtn = document.getElementById('clear-favorites');
    if (favoritesContainer) {
        const favorites = loadFavorites();
        const allItems = Array.from(document.querySelectorAll('.menu-item')); // Récupérer tous les items (si disponibles)
        const favoriteItems = allItems.filter(item => favorites.includes(item.dataset.id));

        if (favorites.length > 0) {
            favoritesContainer.innerHTML = favoriteItems.length > 0 
                ? favoriteItems.map(item => item.outerHTML).join('') 
                : '<p>Aucun favori pour le moment.</p>';
            clearFavoritesBtn.style.display = 'block';
        } else {
            favoritesContainer.innerHTML = '<p>Aucun favori pour le moment.</p>';
        }

        clearFavoritesBtn.addEventListener('click', () => {
            if (confirm('Voulez-vous vraiment vider vos favoris ?')) {
                clickSound.play();
                localStorage.removeItem('favorites');
                favoritesContainer.innerHTML = '<p>Aucun favori pour le moment.</p>';
                clearFavoritesBtn.style.display = 'none';
                showToast('Favoris vidés !');
            }
        });
    }


    // Menu hamburger pour mobile
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            clickSound.play();
            navLinks.classList.toggle('active');
            hamburger.textContent = navLinks.classList.contains('active') ? '✖' : '☰';
        });
    }

    // Barre de progression pour les formulaires
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const progressBar = form.querySelector('#progress-bar');
        if (progressBar) {
            const requiredFields = form.querySelectorAll('input[required], textarea[required]');
            const totalFields = requiredFields.length;

            const updateProgress = () => {
                const filledFields = Array.from(requiredFields).filter(field => field.value.trim() !== '').length;
                const percentage = (filledFields / totalFields) * 100;
                progressBar.style.width = `${percentage}%`;
            };

            requiredFields.forEach(field => {
                field.addEventListener('input', updateProgress);
            });
        }
    });

    // Prévisualisation des images dans manage_menu.php
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    imagePreview.innerHTML = `<img src="${event.target.result}" alt="Prévisualisation" style="max-width: 200px; border-radius: 8px;">`;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.innerHTML = '';
            }
        });
    }
});