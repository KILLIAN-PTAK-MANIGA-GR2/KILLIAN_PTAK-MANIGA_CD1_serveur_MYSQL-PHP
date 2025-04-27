import { displayPokemonCards } from './api.js';

// This file serves as the main JavaScript entry point, initializing the application, setting up event listeners, and managing the overall application state.



function initializeApp() {
    setupEventListeners();
    loadUserData();
    loadCardData();
    displayPokemonCards(); // Load Pokémon cards on page load
}

function setupEventListeners() {
    const tradeButton = document.getElementById('trade-button');
    if (tradeButton) {
        tradeButton.addEventListener('click', openTradeModal);
    }
}

function loadUserData() {
    // Fetch user data from the API
    fetch('/backend/api/users.php')
        .then(response => response.json())
        .then(data => {
            // Handle user data
            console.log('User data loaded:', data);
        })
        .catch(error => console.error('Error loading user data:', error));
}

function loadCardData() {
    // Fetch card data from the API
    fetch('/backend/api/cards.php')
        .then(response => response.json())
        .then(data => {
            // Handle card data
            console.log('Card data loaded:', data);
        })
        .catch(error => console.error('Error loading card data:', error));
}

function openTradeModal() {
    // Logic to open the trade modal
    const modal = document.getElementById('trade-modal');
    if (modal) {
        modal.style.display = 'block';
    }
}
