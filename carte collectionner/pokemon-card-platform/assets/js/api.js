const API_BASE_URL = 'https://pokeapi.co/api/v2';
const CACHE = {};

// Initialize the application
document.addEventListener('DOMContentLoaded', () => {
    initializeApp();
});

function initializeApp() {
    setupEventListeners();
    loadUserData();
    loadCardData();
    displayPokemonCards(); // Load Pokémon cards on page load
}

// Fetch data from the API with caching
async function fetchFromAPI(endpoint) {
    if (CACHE[endpoint]) {
        return CACHE[endpoint];
    }

    try {
        const response = await fetch(`${API_BASE_URL}/${endpoint}`);
        if (!response.ok) {
            throw new Error(`Failed to fetch data from ${endpoint}`);
        }
        const data = await response.json();
        CACHE[endpoint] = data; // Cache the response
        return data;
    } catch (error) {
        console.error(`Error fetching data from ${endpoint}:`, error);
        return null;
    }
}

// Fetch Pokémon list
async function fetchPokemonList(limit = 10) {
    return await fetchFromAPI(`pokemon?limit=${limit}`);
}

// Fetch Pokémon details
async function fetchPokemonDetails(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Failed to fetch Pokémon details');
        }
        return await response.json();
    } catch (error) {
        console.error('Error fetching Pokémon details:', error);
        return null;
    }
}

// Display Pokémon cards
async function displayPokemonCards() {
    const cardList = document.getElementById('card-list');
    if (!cardList) {
        console.error('Card list container not found');
        return;
    }

    cardList.innerHTML = '<p>Loading cards...</p>';

    // Récupérer la liste des Pokémon (par défaut, 10 Pokémon)
    const pokemonList = await fetchPokemonList(10);
    if (!pokemonList || !pokemonList.results) {
        cardList.innerHTML = '<p>Failed to load cards.</p>';
        return;
    }

    cardList.innerHTML = ''; // Effacer le message de chargement

    // Parcourir chaque Pokémon et afficher ses détails
    for (const pokemon of pokemonList.results) {
        const details = await fetchPokemonDetails(pokemon.url);
        if (details) {
            const card = document.createElement('div');
            card.className = 'card';
            card.innerHTML = `
                <img src="${details.sprites.front_default}" alt="${details.name}">
                <h3 class="card-title">${details.name}</h3>
                <p class="card-description">Type: ${details.types.map(t => t.type.name).join(', ')}</p>
            `;
            cardList.appendChild(card);
        }
    }
}

// Load user data
function loadUserData() {
    fetch('/backend/api/users.php')
        .then(response => response.json())
        .then(data => {
            console.log('User data loaded:', data);
        })
        .catch(error => console.error('Error loading user data:', error));
}

// Load card data
function loadCardData() {
    fetch('/backend/api/cards.php')
        .then(response => response.json())
        .then(data => {
            console.log('Card data loaded:', data);
        })
        .catch(error => console.error('Error loading card data:', error));
}

// Setup event listeners
function setupEventListeners() {
    const tradeButton = document.getElementById('trade-button');
    if (tradeButton) {
        tradeButton.addEventListener('click', openTradeModal);
    }

    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('change', toggleTheme);
    }
}

// Open trade modal
function openTradeModal() {
    const modal = document.getElementById('trade-modal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Toggle theme
function toggleTheme() {
    document.body.classList.toggle('dark-theme');
}

// Clear cache
function clearCache() {
    for (const key in CACHE) {
        delete CACHE[key];
    }
}

export { fetchFromAPI, fetchPokemonList, fetchPokemonDetails, displayPokemonCards, clearCache };