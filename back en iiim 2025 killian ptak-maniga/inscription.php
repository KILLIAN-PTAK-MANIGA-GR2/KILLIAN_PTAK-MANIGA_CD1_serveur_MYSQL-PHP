<?php

require_once("connexion.php");

if ($_POST) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (!empty($email) && !empty($password) && !empty($confirm_password)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($password === $confirm_password) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES(:email, :password)");
                    $stmt->execute([
                        'email' => $email,
                        'password' => password_hash($password, PASSWORD_DEFAULT)
                    ]);
                    // Redirection vers la page de connexion après la création du compte
                    header("Location: login.php");
                    exit;
                } catch (PDOException $e) {
                    $error = "Erreur : " . htmlspecialchars($e->getMessage());
                }
            } else {
                $error = "Les mots de passe ne correspondent pas.";
            }
        } else {
            $error = "Veuillez entrer une adresse email valide.";
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="container mx-auto p-6">
        <div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-4 text-center">Inscription</h1>

            <!-- Affichage des erreurs -->
            <?php if (!empty($error)): ?>
                <p class="text-red-500 text-sm mb-4"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" name="email" id="email" placeholder="Email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe:</label>
                    <input type="password" name="password" id="password" placeholder="Mot de passe" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirmez le mot de passe:</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmez le mot de passe" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Inscription</button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-4">
                Vous avez déjà un compte ? <a href="login.php" class="text-blue-500 hover:underline">Connectez-vous ici</a>
            </p>
        </div>
    </div>

</body>
</html>