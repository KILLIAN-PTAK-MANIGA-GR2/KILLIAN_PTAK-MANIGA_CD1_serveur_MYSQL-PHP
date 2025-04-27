<?php
require '../db.php'; // Inclure la connexion à la base de données

$stmt = $pdo->query("SELECT * FROM cards");
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($cards as $card) {
    echo "<h3>" . htmlspecialchars($card['name']) . "</h3>";
    echo "<p>" . htmlspecialchars($card['description']) . "</p>";
    echo "<hr>";
}
?>