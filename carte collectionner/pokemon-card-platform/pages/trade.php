<?php
// filepath: c:\laragon\www\php\pokemon-card-platform\pages\trade.php
require '../db.php'; // Inclure la connexion à la base de données
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si non connecté
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les cartes disponibles pour l'utilisateur connecté
$stmt = $pdo->prepare("
    SELECT cards.* 
    FROM user_cards 
    JOIN cards ON user_cards.card_id = cards.id 
    WHERE user_cards.user_id = ?
");
$stmt->execute([$user_id]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestion des demandes d'échange
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'send_request') {
        // Envoyer une demande d'échange
        $target_username = $_POST['username'];
        $selected_card_id = $_POST['card_id'];

        // Vérifier si l'utilisateur cible existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$target_username]);
        $target_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($target_user) {
            $target_user_id = $target_user['id'];

            // Vérifier si une demande similaire existe déjà
            $stmt = $pdo->prepare("
                SELECT * FROM trades 
                WHERE from_user_id = ? AND to_user_id = ? AND card_id = ? AND status = 'pending'
            ");
            $stmt->execute([$user_id, $target_user_id, $selected_card_id]);
            $existing_trade = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing_trade) {
                $message = "Une demande d'échange similaire est déjà en attente.";
            } else {
                // Ajouter une nouvelle demande d'échange
                $stmt = $pdo->prepare("
                    INSERT INTO trades (from_user_id, to_user_id, card_id, status, created_at) 
                    VALUES (?, ?, ?, 'pending', NOW())
                ");
                $stmt->execute([$user_id, $target_user_id, $selected_card_id]);

                $_SESSION['message'] = "Demande d'échange envoyée avec succès à $target_username.";
                header('Location: trade.php');
                exit;
            }
        } else {
            $message = "Utilisateur introuvable.";
        }

        // Rediriger pour éviter la soumission répétée du formulaire
        header('Location: trade.php');
        exit;
    } elseif ($action === 'propose_card') {
        // Proposer une carte en retour
        $trade_id = $_POST['trade_id'];
        $proposed_card_id = $_POST['status'];

        // Vérifier que la carte proposée existe pour l'utilisateur connecté
        $stmt = $pdo->prepare("
            SELECT * FROM user_cards WHERE user_id = ? AND card_id = ?
        ");
        $stmt->execute([$user_id, $proposed_card_id]);
        if (!$stmt->fetch()) {
            die("Erreur : La carte proposée n'existe pas pour l'utilisateur.");
        }

        // Mettre à jour la table trades avec la carte proposée et le statut
        $stmt = $pdo->prepare("
            UPDATE trades 
            SET proposed_card_id = ?, status = 'waiting_acceptance' 
            WHERE id = ? AND to_user_id = ?
        ");
        $stmt->execute([$proposed_card_id, $trade_id, $user_id]);

        $message = "Carte proposée avec succès.";

        // Rediriger pour éviter la soumission répétée du formulaire
        header('Location: trade.php');
        exit;
    } elseif ($action === 'accept_trade') {
        // Accepter l'échange
        $trade_id = $_POST['trade_id'];

        // Récupérer les informations de l'échange
        $stmt = $pdo->prepare("
            SELECT * FROM trades 
            WHERE id = ? AND (from_user_id = ? OR to_user_id = ?)
        ");
        $stmt->execute([$trade_id, $user_id, $user_id]);
        $trade = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$trade) {
            die("Erreur : Échange introuvable.");
        }

        $from_user_id = $trade['from_user_id'];
        $to_user_id = $trade['to_user_id'];
        $card_id = $trade['card_id'];
        $proposed_card_id = $trade['proposed_card_id'];

        // Vérifier que les deux cartes existent
        $stmt = $pdo->prepare("
            SELECT * FROM user_cards WHERE user_id = ? AND card_id = ?
        ");
        $stmt->execute([$from_user_id, $card_id]);
        if (!$stmt->fetch()) {
            die("Erreur : La carte demandée n'existe pas pour l'utilisateur A.");
        }

        $stmt = $pdo->prepare("
            SELECT * FROM user_cards WHERE user_id = ? AND card_id = ?
        ");
        $stmt->execute([$to_user_id, $proposed_card_id]);
        if (!$stmt->fetch()) {
            die("Erreur : La carte proposée n'existe pas pour l'utilisateur B.");
        }

        // Mettre à jour le user_id pour échanger les cartes
        $stmt = $pdo->prepare("
            UPDATE user_cards 
            SET user_id = ? 
            WHERE user_id = ? AND card_id = ?
        ");
        $stmt->execute([$to_user_id, $from_user_id, $card_id]); // Transférer la carte de A à B
        $stmt->execute([$from_user_id, $to_user_id, $proposed_card_id]); // Transférer la carte de B à A

        // Supprimer l'échange de la base de données
        $stmt = $pdo->prepare("
            DELETE FROM trades 
            WHERE id = ?
        ");
        $stmt->execute([$trade_id]);

        $message = "Échange accepté et complété avec succès.";
        header('Location: trade.php');
        exit;
    } elseif ($action === 'reject_trade') {
        // Refuser l'échange
        $trade_id = $_POST['trade_id'];

        // Supprimer l'échange de la base de données
        $stmt = $pdo->prepare("
            DELETE FROM trades 
            WHERE id = ?
        ");
        $stmt->execute([$trade_id]);

        $message = "Échange refusé avec succès.";
        header('Location: trade.php'); // Recharger la page pour mettre à jour la liste des échanges
        exit;
    }
}

// Récupérer les demandes d'échange reçues
$stmt = $pdo->prepare("
    SELECT trades.*, cards.name AS card_name, users.username AS from_username 
    FROM trades 
    JOIN cards ON trades.card_id = cards.id 
    JOIN users ON trades.from_user_id = users.id 
    WHERE trades.to_user_id = ? AND trades.status IN ('pending', 'waiting_acceptance')
");
$stmt->execute([$user_id]);
$received_trades = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <div class="header-left">
            <?php if (isset($_SESSION['username']) && !empty($_SESSION['username'])): ?>
                <p>Connecté en tant que : <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
            <?php else: ?>
                <p>Non connecté</p>
            <?php endif; ?>
        </div>
        <h1>Échanger des Cartes</h1>
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
    </header>
    <main>
        <h2>Proposer un échange</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
            <p class="message"><?php echo htmlspecialchars($_SESSION['message']); ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="action" value="send_request">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>

            <label for="card_id">Sélectionnez une carte :</label>
            <select id="card_id" name="card_id" required>
                <?php foreach ($cards as $card): ?>
                    <option value="<?php echo $card['id']; ?>">
                        <?php echo htmlspecialchars($card['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Envoyer la demande</button>
        </form>

        <h2>Demandes d'échange reçues</h2>
        <?php foreach ($received_trades as $trade): ?>
            <div class="trade-request">
                <p>Demande de : <?php echo htmlspecialchars($trade['from_username']); ?></p>
                <p>Carte proposée : <?php echo htmlspecialchars($trade['card_name']); ?></p>

                <?php if ($trade['status'] === 'pending'): ?>
                    <form method="POST">
                        <input type="hidden" name="trade_id" value="<?php echo $trade['id']; ?>">
                        <label for="status">Proposer une carte en retour :</label>
                        <select name="status" required>
                            <?php foreach ($cards as $card): ?>
                                <option value="<?php echo $card['id']; ?>">
                                    <?php echo htmlspecialchars($card['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="action" value="propose_card">Proposer</button>
                    </form>
                    <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cet échange ?');">
                        <input type="hidden" name="trade_id" value="<?php echo $trade['id']; ?>">
                        <button type="submit" name="action" value="reject_trade">Refuser l'échange</button>
                    </form>
                <?php elseif ($trade['status'] === 'waiting_acceptance'): ?>
                    <form method="POST">
                        <input type="hidden" name="trade_id" value="<?php echo $trade['id']; ?>">
                        <button type="submit" name="action" value="accept_trade">Accepter l'échange</button>
                    </form>
                    <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir refuser cet échange ?');">
                        <input type="hidden" name="trade_id" value="<?php echo $trade['id']; ?>">
                        <button type="submit" name="action" value="reject_trade">Refuser l'échange</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <h2>Vos Cartes</h2>
        <ul>
            <?php foreach ($cards as $card): ?>
                <li>
                    <h3><?php echo htmlspecialchars($card['name']); ?></h3>
                    <p><?php echo htmlspecialchars($card['description']); ?></p>
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