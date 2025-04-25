<?php
session_start();

// Supprimez uniquement les variables de session spécifiques
unset($_SESSION['user']);
unset($_SESSION['iduser']);
unset($_SESSION['email']);

// Redirigez vers la page de connexion
header("Location: login.php");
exit;
?>