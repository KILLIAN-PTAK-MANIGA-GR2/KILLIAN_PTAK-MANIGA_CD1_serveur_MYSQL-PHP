<?php
// filepath: c:\laragon\www\php\pokemon-card-platform\pages\trade.php
require '../db.php'; // Inclure la connexion à la base de données
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si non connecté
    exit;
}

// Récupérer les cartes disponibles pour l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT cards.* 
    FROM user_cards 
    JOIN cards ON user_cards.card_id = cards.id 
    WHERE user_cards.user_id = ?
");
$stmt->execute([$user_id]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Échanger des Cartes - Collection de Cartes Pokémon</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/themes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../assets/js/app.js" defer></script>
    <script src="../assets/js/api.js" defer></script>
    <script src="../assets/js/ui.js" defer></script>
    <script src="../assets/js/theme.js" defer></script>
</head>
<body>
    <header>
        <h1>Échanger des Cartes</h1>
        <nav class="desktop-nav">
            <ul>
                <li><a href="acceuile.php">Accueil</a></li>
                <li><a href="dashboard.php">Tableau de Bord</a></li>
                <li><a href="#" id="logout-button">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <ul>
            <?php foreach ($cards as $card): ?>
                <li>
                    <h3><?php echo htmlspecialchars($card['name']); ?></h3>
                    <p><?php echo htmlspecialchars($card['description']); ?></p>
                    <button>Proposer un échange</button>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="acceuile.php" class="btn-home">Retour à l'accueil</a>
    </main>
    <footer>
        <p>&copy; 2023 Pokémon Card Platform. Tous droits réservés.</p>
    </footer>
</body>
</html>