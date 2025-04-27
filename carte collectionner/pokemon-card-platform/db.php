<?php
// filepath: c:\laragon\www\php\pokemon-card-platform\db.php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=carte_collect;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>