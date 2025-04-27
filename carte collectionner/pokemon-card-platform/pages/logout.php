<?php
// filepath: c:\laragon\www\php\pokemon-card-platform\pages\logout.php
session_start();

// Supprimer les variables de session spécifiques
unset($_SESSION['user_id']);
unset($_SESSION['username']);

// Rediriger vers la page d'accueil
header('Location: acceuile.php');
exit;
?>