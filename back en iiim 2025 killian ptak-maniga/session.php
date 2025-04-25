<?php
session_start();

if (!isset($_SESSION['iduser'])) {
    header("Location: connexion.php");
    exit;
}
?>