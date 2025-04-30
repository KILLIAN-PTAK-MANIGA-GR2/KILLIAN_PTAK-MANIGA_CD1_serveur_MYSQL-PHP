<?php
// filepath: c:\laragon\www\php\pokemon-card-platform\pages\dashboard.php
require '../db.php'; // Inclure la connexion à la base de données
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si non connecté
    exit;
}

// Récupérer les informations de l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les cartes obtenues par l'utilisateur depuis la table `user_cards`
$stmt = $pdo->prepare("
    SELECT cards.* 
    FROM user_cards 
    JOIN cards ON user_cards.card_id = cards.id 
    WHERE user_cards.user_id = ?
");
$stmt->execute([$user_id]);
$obtained_cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pokémon Card Platform</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/themes.css">
    <script src="../assets/js/app.js" defer></script>
    <script src="../assets/js/api.js" defer></script>
    <script src="../assets/js/ui.js" defer></script>
    <script src="../assets/js/theme.js" defer></script>
</head>
<body>
    <header>
        <h1>Bienvenue sur votre Tableau de Bord</h1>
        <nav class="desktop-nav">
            <ul>
                <li><a href="acceuile.php">Accueil</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Tableau de Bord</a></li>
                    <li><a href="trade.php">Échanger</a></li>
                    <li><a href="logout.php" id="logout-link">Déconnexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
        <section id="profile">
            <h2>Votre Profil</h2>
            <p>Nom d'utilisateur : <span><?php echo htmlspecialchars($user['username']); ?></span></p>
            <p>Email : <span><?php echo htmlspecialchars($user['email']); ?></span></p>
            <p>Date d'inscription : <span><?php echo htmlspecialchars($user['created_at']); ?></span></p>
        </section>
        <section id="obtained-cards">
            <h2>Vos Cartes Obtenues</h2>
            <div class="card-container">
                <?php if (!empty($obtained_cards)): ?>
                    <?php foreach ($obtained_cards as $card): ?>
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($card['image']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
                            <h4><?php echo htmlspecialchars($card['name']); ?></h4>
                            <p><?php echo htmlspecialchars($card['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune carte obtenue pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2023 Pokémon Card Platform. Tous droits réservés.</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Gérer l'affichage/masquage du mot de passe
            const passwordField = document.getElementById('user-password');
            const togglePasswordButton = document.getElementById('toggle-password');
            let isPasswordVisible = false;

            togglePasswordButton.addEventListener('click', () => {
                if (isPasswordVisible) {
                    passwordField.textContent = '********'; // Masquer le mot de passe
                    togglePasswordButton.textContent = 'Afficher le mot de passe';
                } else {
                    passwordField.textContent = '<?php echo htmlspecialchars($user['password']); ?>'; // Afficher le mot de passe
                    togglePasswordButton.textContent = 'Masquer le mot de passe';
                }
                isPasswordVisible = !isPasswordVisible;
            });
        });
    </script>
</body>
</html>
