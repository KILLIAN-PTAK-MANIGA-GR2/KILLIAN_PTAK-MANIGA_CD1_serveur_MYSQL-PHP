<?php
// filepath: c:\laragon\www\php\pokemon-card-platform\pages\acceuile.php
require '../db.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Gérer l'ouverture d'un booster
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['open_booster'])) {
    $user_id = $_SESSION['user_id'];

    // Récupérer 5 cartes aléatoires depuis la table `cards`
    $stmt = $pdo->query("SELECT * FROM cards ORDER BY RAND() LIMIT 5");
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Insérer les cartes obtenues dans la table `user_cards`
    foreach ($cards as $card) {
        $stmt = $pdo->prepare("INSERT INTO user_cards (user_id, card_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $card['id']]);
    }

    // Stocker les cartes obtenues dans une variable pour les afficher
    $obtained_cards = $cards;


}

// Récupérer les cartes Pokémon depuis la base de données
$stmt = $pdo->query("SELECT * FROM cards LIMIT 10"); // Limiter à 10 cartes pour l'affichage
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon Card Collection Platform</title>
    <!-- Liens vers les fichiers CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/themes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Scripts JavaScript -->
    <script src="../assets/js/app.js" type="module" defer></script>
    <script src="../assets/js/api.js" type="module" defer></script>
    <script src="../assets/js/ui.js" type="module" defer></script>
    <script src="../assets/js/theme.js"></script>
</head>
<body>
    <header>
        <h1>Collection de Cartes Pokémon</h1>
        <nav class="desktop-nav">
            <ul>
                <li><a href="acceuile.php">Accueil</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Tableau de Bord</a></li>
                    <li><a href="trade.php">Échanger</a></li>
                    <li><a href="logout.php" id="logout-link">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="login.php" id="login-link">Connexion</a></li>
                    <li><a href="register.php" id="register-link">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- Actions dans l'en-tête -->
        <div class="header-actions">
            <div class="container">
                <!-- Bouton pour basculer entre les thèmes clair et sombre -->
                <label class="toggle" for="switch">
                    <input id="switch" class="input" type="checkbox" />
                    <div class="icon icon--moon">
                        <!-- Icône de lune pour le mode sombre -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 512 512" id="moon">
                            <g>
                                <path
                                    fill="#6A6D68"
                                    d="M412.95,381.15c-8.05,10.119-16.94,19.33-26.55,27.54c-2.271,1.939-4.58,3.819-6.92,5.64..."
                                    opacity=".9"
                                ></path>
                                <path
                                    fill="#A3AAA0"
                                    d="M408.95,377.15c-8.05,10.119-16.94,19.33-26.55,27.54c-2.271,1.939-4.58,3.819-6.92,5.64..."
                                ></path>
                                <circle cx="285" cy="156" r="44.5" fill="#666865" stroke="#5E5E5D" stroke-miterlimit="10" stroke-width="4"></circle>
                                <circle cx="385" cy="300" r="21.5" fill="#666865" stroke="#5E5E5D" stroke-miterlimit="10" stroke-width="4"></circle>
                                <circle cx="166" cy="296.5" r="27.84" fill="#666865" stroke="#5E5E5D" stroke-miterlimit="10" stroke-width="4"></circle>
                                <circle cx="261.25" cy="272.75" r="14.75" fill="#666865" stroke="#5E5E5D" stroke-miterlimit="10" stroke-width="4"></circle>
                                <circle cx="151.5" cy="184" r="28" fill="#666865" stroke="#5E5E5D" stroke-miterlimit="10" stroke-width="4"></circle>
                                <circle cx="297.5" cy="382.501" r="27.5" fill="#666865" stroke="#5E5E5D" stroke-miterlimit="10" stroke-width="4"></circle>
                                <circle cx="395" cy="213" r="18.5" fill="#666865" stroke="#5E5E5D" stroke-miterlimit="10" stroke-width="4"></circle>
                                <circle cx="317" cy="216" r="8" fill="#666865" stroke="#5E5E5D" stroke-miterlimit="10" stroke-width="4"></circle>
                            </g>
                        </svg>
                    </div>
                    <div class="w-8 icon icon--sun">
                        <!-- Icône de soleil pour le mode clair -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.5 47.5" id="sun">
                            <g fill="#ffac33" transform="matrix(1.25 0 0 -1.25 0 47.5)">
                                <path d="M17 35s0 2 2 2 2-2 2-2v-2s0-2-2-2-2 2-2 2v2zM35 21s2 0 2-2-2-2-2-2h-2s-2 0-2 2 2 2 2 2h2zM5 21s2 0 2-2-2-2-2-2H3s-2 0-2 2 2 2 2 2h2zM10.121 29.706s1.414-1.414 0-2.828-2.828 0-2.828 0l-1.415 1.414s-1.414 1.414 0 2.829c1.415 1.414 2.829 0 2.829 0l1.414-1.415ZM31.121 8.707s1.414-1.414 0-2.828-2.828 0-2.828 0l-1.414 1.414s-1.414 1.414 0 2.828 2.828 0 2.828 0l1.414-1.414ZM30.708 26.879s-1.414-1.414-2.828 0 0 2.828 0 2.828l1.414 1.414s1.414 1.414 2.828 0 0-2.828 0-2.828l-1.414-1.414ZM9.708 5.879s-1.414-1.414-2.828 0 0 2.828 0 2.828l1.414 1.414s1.414 1.414 2.828 0 0-2.828 0-2.828L9.708 5.879ZM17 5s0 2 2 2 2-2 2-2V3s0-2-2-2-2 2-2 2v2zM29 19c0 5.523-4.478 10-10 10-5.523 0-10-4.477-10-10 0-5.522 4.477-10 10-10 5.522 0 10 4.478 10 10"></path>
                            </g>
                        </svg>
                    </div>
                </label>
            </div>
        </div>
    </header>
    <!-- Bouton pour afficher/masquer la sidebar -->
    <button class="sidebar-toggle">☰</button>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar hidden">
        <ul>
            <li><a href="acceuile.php">Accueil</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php">Tableau de Bord</a></li>
                <li><a href="trade.php">Échanger</a></li>
                <li><a href="logout.php" id="logout-link-sidebar">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="login.php" id="login-link-sidebar">Connexion</a></li>
                <li><a href="register.php" id="register-link-sidebar">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <main>

        <section id="card-display">
            <h2 class="caCards">Cartes disponibles</h2>
            <div id="card-container" class="card-container">
                <!-- Cards will be dynamically loaded here -->
            </div>
            <div id="card-list" class="card-container">
                <!-- Les cartes Pokémon seront chargées ici -->
            </div>
        </section>
    
        <section id="booster-pack">
            <h2>Ouvrir un Booster</h2>
            <form method="POST">
                <button id="open-booster" class="btn" name="open_booster">
                    <i class="fas fa-box-open"></i> Ouvrir
                </button>
            </form>
            <div id="booster-animation" class="hidden">
                <p>Ouverture du booster...</p>
            </div>
            <?php if (isset($obtained_cards) && !empty($obtained_cards)): ?>
                <div id="booster-results" class="booster-container">
                    <h3>Cartes obtenues :</h3>
                    <div class="card-container">
                        <?php foreach ($obtained_cards as $card): ?>
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($card['image']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
                                <h4><?php echo htmlspecialchars($card['name']); ?></h4>
                                <p><?php echo htmlspecialchars($card['description']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <p id="login-warning" class="hidden">Veuillez vous connecter pour ouvrir un booster.</p>
        </section>

        <section id="trade-section">
            <h2>Échanger des Cartes</h2>
            <button id="trade-button" class="btn">
                <i class="fas fa-exchange-alt"></i> Échanger
            </button>
        </section>

        <section id="date-date">
            <h2>Date et Heure</h2>
            <div id="date-time">
                <?php if (isset($dateTimeData['error'])): ?>
                    <p>Erreur : <?php echo htmlspecialchars($dateTimeData['error']); ?></p>
                <?php elseif (isset($dateTimeData['datetime'])): ?>
                    <p>Fuseau Horaire : <?php echo htmlspecialchars($dateTimeData['timezone_name']); ?></p>
                    <p>Date et Heure Locales : <?php
                        $dateTime = new DateTime($dateTimeData['datetime']);
                        echo $dateTime->format('d/m/Y H:i:s'); // Format français : jour/mois/année heure:minute:seconde
                    ?></p>
                <?php else: ?>
                    <p>Impossible de récupérer la date et l'heure.</p>
                <?php endif; ?>
            </div>
        </section>

    </main>
    <footer>
        <p>&copy; 2023 Pokémon Card Collection Platform.
        </p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');

            // Toggle sidebar visibility
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('hidden');
            });

            const tradeButton = document.getElementById('trade-button');
            if (tradeButton) {
                tradeButton.addEventListener('click', () => {
                    console.log('Trade button clicked!');
                });
            } else {
                console.error('Trade button not found.');
            }

        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBoosterButton = document.getElementById('open-booster');
            if (openBoosterButton) {
                openBoosterButton.addEventListener('click', () => {
                    console.log('Bouton "Ouvrir" cliqué !');
                });
            } else {
                console.error('Le bouton "Ouvrir" n\'a pas été trouvé.');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateTimeElement = document.getElementById('date-time');
            let offset = 0; // Décalage entre l'heure locale et l'heure de l'API
            let location = '';// Emplacement du fuseau horaire

            // Fonction pour récupérer l'heure depuis l'API
            async function fetchDateTimeFromAPI() {
                try {
                    const response = await fetch('https://timezone.abstractapi.com/v1/current_time/?api_key=70da21483b474cbcbdf83e6324fe889c&location=Paris, France');
                    const data = await response.json();

                    if (data && data.datetime) {
                        const apiTime = new Date(data.datetime);
                        offset = apiTime - new Date(); // Calculer le décalage
                        location = data.timezone_location || 'France'; // Récupérer l'emplacement ou définir une valeur par défaut
                    }
                } catch (error) {
                    console.error('Erreur lors de la récupération de l\'heure depuis l\'API :', error);
                }
            }

            // Fonction pour mettre à jour l'heure
            function updateDateTime() {
                const now = new Date(new Date().getTime() + offset); // Ajouter le décalage
                const formattedTime = now.toLocaleString('fr-FR', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                });

                if (dateTimeElement) {
                    dateTimeElement.innerHTML = `
                        <p>Fuseau Horaire : ${location}</p>
                        <p>Date et Heure Locales : ${formattedTime}</p>
                    `;
                }
            }

            // Mettre à jour l'heure toutes les secondes
            setInterval(updateDateTime, 1000);

            // Synchroniser avec l'API toutes les minutes
            setInterval(fetchDateTimeFromAPI, 60000);

            // Appeler immédiatement les fonctions
            fetchDateTimeFromAPI();
            updateDateTime();
        });
    </script>
</body>
</html>