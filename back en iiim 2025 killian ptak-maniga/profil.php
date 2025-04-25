<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="container mx-auto p-6">
        <div class="bg-white p-6 rounded shadow-md">
            <h1 class="text-3xl font-bold mb-4">Bienvenue sur votre profil, <?= htmlspecialchars($_SESSION['user']['email']) ?> !</h1>
            <p class="text-lg mb-6">Vous êtes connecté.</p>

            <!-- Bouton pour aller vers la page index.php -->
            <div class="flex space-x-4">
                <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Aller à la page Index
                </a>
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Se déconnecter
                </a>
            </div>
        </div>
    </div>

</body>
</html>