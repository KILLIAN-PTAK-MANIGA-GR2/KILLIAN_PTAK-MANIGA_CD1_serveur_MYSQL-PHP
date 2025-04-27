// This file manages the user interface interactions, including the sidebar menu, modal popups for trades, and the favorite card functionality.

document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const tradeModal = document.getElementById('trade-modal');
    const favoriteButtons = document.querySelectorAll('.favorite-button');
    const themeToggle = document.getElementById('theme-toggle');
    const openBoosterButton = document.getElementById('open-booster');
    const boosterResults = document.getElementById('booster-results');
    const boosterAnimation = document.getElementById('booster-animation');
    const loginWarning = document.getElementById('login-warning');
    const loginLink = document.getElementById('login-link');
    const registerLink = document.getElementById('register-link');
    const logoutLink = document.getElementById('logout-link');
    const tradeButton = document.getElementById('trade-button');
    const cardContainer = document.getElementById('card-container');
    
    // Vérifiez si l'utilisateur est connecté (déclaré une seule fois)
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

    // Gestion de la visibilité des liens en fonction de l'état de connexion
    if (isLoggedIn) {
        loginLink.classList.add('hidden');
        registerLink.classList.add('hidden');
        logoutLink.classList.remove('hidden');
    } else {
        loginLink.classList.remove('hidden');
        registerLink.classList.remove('hidden');
        logoutLink.classList.add('hidden');
    }

    // Gérer la déconnexion
    logoutLink.addEventListener('click', () => {
        // Supprimer uniquement l'état de connexion
        localStorage.removeItem('isLoggedIn');

        // Réinitialiser les données affichées dans le tableau de bord
        clearDashboardData();

        alert('Vous avez été déconnecté.');
        window.location.href = 'acceuile.php'; // Rediriger vers la page d'accueil
    });

    document.getElementById('logout-link-sidebar').addEventListener('click', () => {
        // Supprimer uniquement l'état de connexion
        localStorage.removeItem('isLoggedIn');

        // Réinitialiser les données affichées dans le tableau de bord
        clearDashboardData();

        alert('Vous avez été déconnecté.');
        window.location.href = 'acceuile.php'; // Rediriger vers la page d'accueil
    });

    // Toggle sidebar visibility
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('visible');
        });
    } else {
        console.error('Bouton "Toggle Sidebar" ou sidebar introuvable.');
    }

    // Open trade modal
    if (tradeButton) {
        tradeButton.addEventListener('click', () => {
            alert('Modal d\'échange ouvert !');
        });
    } else {
        console.error('Bouton "Open Trade Modal" introuvable.');
    }

    // Bouton "Ouvrir un Booster"
    if (openBoosterButton) {
        openBoosterButton.addEventListener('click', async () => {
            if (!isLoggedIn) {
                loginWarning.classList.remove('hidden');
                return;
            }

            loginWarning.classList.add('hidden');
            boosterResults.innerHTML = '<p>Ouverture en cours...</p>';
            setTimeout(() => {
                boosterResults.innerHTML = '<p>5 cartes Pokémon aléatoires apparaissent ici !</p>';
            }, 2000); // Simule un délai pour l'animation
        });
    } else {
        console.error('Bouton "Ouvrir un Booster" introuvable.');
    }

    // Open trade modal
    document.querySelectorAll('.trade-button').forEach(button => {
        button.addEventListener('click', () => {
            tradeModal.classList.add('active');
        });
    });

    // Close trade modal
    document.getElementById('close-modal').addEventListener('click', () => {
        tradeModal.classList.remove('active');
    });

    // Favorite card functionality
    favoriteButtons.forEach(button => {
        button.addEventListener('click', () => {
            button.classList.toggle('favorited');
            const cardId = button.dataset.cardId;
            // Call API to update favorite status
            updateFavoriteStatus(cardId, button.classList.contains('favorited'));
        });
    });

    function updateFavoriteStatus(cardId, isFavorited) {
        fetch('/backend/api/users.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cardId, isFavorited })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Favorite status updated successfully');
            } else {
                console.error('Error updating favorite status');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Charger le thème depuis le stockage local
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }

    if (!isLoggedIn) {
        openBoosterButton.style.display = 'none';
        loginWarning.classList.remove('hidden');
        return;
    }

    openBoosterButton.addEventListener('click', async () => {
        // Réinitialiser le conteneur
        boosterResults.innerHTML = '';
        boosterResults.classList.remove('show');

        // Simuler un délai pour l'animation d'ouverture
        setTimeout(() => {
            boosterResults.classList.add('show');
        }, 500);

        // Récupérer 5 cartes aléatoires depuis l'API
        const cards = await fetchBoosterCards(5);

        // Ajouter les cartes avec une animation
        cards.forEach((card, index) => {
            const cardElement = document.createElement('div');
            cardElement.className = 'booster-card';
            cardElement.innerHTML = `
                <img src="${card.image}" alt="${card.name}">
                <h3>${card.name}</h3>
            `;
            boosterResults.appendChild(cardElement);

            // Ajouter un délai pour l'animation de chaque carte
            setTimeout(() => {
                cardElement.classList.add('reveal');
            }, index * 300); // Délai de 300ms entre chaque carte
        });

        // Stocker les cartes dans la collection de l'utilisateur
        saveCardsToCollection(cards);
    });

    // Vérifier si l'utilisateur est connecté
    function isUserLoggedIn() {
        // Simuler une vérification (remplacez par une vraie vérification côté serveur)
        return localStorage.getItem('userLoggedIn') === 'true';
    }

    // Fonction pour récupérer des cartes aléatoires
    async function fetchBoosterCards(count) {
        const response = await fetch('https://pokeapi.co/api/v2/pokemon?limit=150');
        const data = await response.json();
        const results = data.results;

        // Sélectionner des cartes aléatoires
        const selectedCards = [];
        for (let i = 0; i < count; i++) {
            const randomIndex = Math.floor(Math.random() * results.length);
            const pokemon = results[randomIndex];

            // Récupérer les détails de chaque Pokémon
            const detailsResponse = await fetch(pokemon.url);
            const details = await detailsResponse.json();

            selectedCards.push({
                name: details.name,
                image: details.sprites.front_default || 'https://via.placeholder.com/100', // Image par défaut si indisponible
            });
        }

        return selectedCards;
    }

    // Fonction pour stocker les cartes dans la collection
    function saveCardsToCollection(cards) {
        const collection = JSON.parse(localStorage.getItem('collection')) || [];
        collection.push(...cards);
        localStorage.setItem('collection', JSON.stringify(collection));
        console.log('Cartes ajoutées à la collection :', cards);
    }

    document.getElementById('open-booster').addEventListener('click', async () => {
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user) {
            alert('Veuillez vous connecter pour ouvrir un booster.');
            return;
        }

        // Simuler l'ouverture d'un booster
        const newCards = [
            { name: 'Pikachu', image: 'https://via.placeholder.com/100' },
            { name: 'Bulbizarre', image: 'https://via.placeholder.com/100' },
            { name: 'Salamèche', image: 'https://via.placeholder.com/100' },
            { name: 'Carapuce', image: 'https://via.placeholder.com/100' },
            { name: 'Evoli', image: 'https://via.placeholder.com/100' }
        ];

        // Ajouter les nouvelles cartes à la collection
        user.collection.push(...newCards);
        user.boostersOpened += 1;

        // Mettre à jour le localStorage
        localStorage.setItem('user', JSON.stringify(user));

        alert('Booster ouvert avec succès !');
    });

    // Exemple de données de cartes Pokémon
    const pokemonCards = [
        {
            name: 'Pikachu',
            image: 'https://example.com/pikachu.jpg',
        },
        {
            name: 'Bulbizarre',
            image: 'https://example.com/bulbizarre.jpg',
        },
        {
            name: 'Salamèche',
            image: 'https://example.com/salameche.jpg',
        },
    ];

    // Vérifiez si le conteneur existe
    if (cardContainer) {
        // Charger les cartes dans le conteneur
        pokemonCards.forEach((card) => {
            const cardElement = document.createElement('div');
            cardElement.className = 'card';
            cardElement.innerHTML = `
                <img src="${card.image}" alt="${card.name}">
                <h3>${card.name}</h3>
            `;
            cardContainer.appendChild(cardElement);
        });
    } else {
        console.error('Le conteneur des cartes (#card-container) est introuvable.');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.sidebar');
    const toggleButton = document.querySelector('.sidebar-toggle');

    // Écouteur pour afficher/masquer la sidebar
    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
    });
});

function clearDashboardData() {
    // Réinitialiser les champs du profil utilisateur
    document.getElementById('user-name').textContent = '';
    document.getElementById('user-email').textContent = '';
    document.getElementById('boosters-opened').textContent = '';

    // Réinitialiser les cartes collectionnées
    const collectionContainer = document.getElementById('user-collection');
    if (collectionContainer) {
        collectionContainer.innerHTML = ''; // Supprimer toutes les cartes affichées
    }

    // Réinitialiser les cartes favorites
    const favoritesContainer = document.getElementById('user-favorites');
    if (favoritesContainer) {
        favoritesContainer.innerHTML = ''; // Supprimer toutes les cartes favorites affichées
    }
};