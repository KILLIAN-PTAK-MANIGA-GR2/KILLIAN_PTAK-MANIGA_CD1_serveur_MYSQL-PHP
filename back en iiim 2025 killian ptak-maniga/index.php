<?php

require_once("session.php"); // Vérifie si l'utilisateur est connecté
require_once("connexion.php"); // Connexion à la base de données

echo "Bienvenue, " . htmlspecialchars($_SESSION['user']['email']) . " !";

session_start();

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit;
}



 /////try {
    ///// 
    ///// SELECT
    /////
    $stmt = $pdo->query("SELECT * FROM book"); // PDO STATEMENT
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupération des données

    ///// 
    ///// EXEC
    /////
  /////   $sql = "INSERT INTO book (title, author, date_publication, category_idcategory) 
    ///// VALUES( 'Le petit prince', 'Sacha Lacombe', '1997-03-28', 1 )";

   /////  $pdo->exec($sql);

    ///// 
    ///// PREPARE & EXECUTE
    /////
     /////$stmt = $pdo->prepare("INSERT INTO book (title, author, date_publication, category_idcategory) 
     /////VALUES( :title, :author, :date_publication, :category )");

     /////$stmt->execute([
     /////    "title" => "Le rouge et le noir",
      /////   "author" => "Standall",
      /////   "date_publication" => "1945-01-01",
      /////   "category" => 1,
     /////]);

     /////$stmt->execute([
      /////   "title" => "One piece",
      /////   "author" => "Oda",
       /////  "date_publication" => "1975-01-01",
      /////   "category" => 1,
  /////   ]);
 /////} catch (PDOException $e) {
  /////   echo "Erreur : " . $e->getMessage();
 /////}
if ($_POST) {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $date_publication = $_POST["date_publication"];
    $category = $_POST["category"]; // Ajout de la récupération de la catégorie

    try {
        $stmt = $pdo->prepare("INSERT INTO book (title, author, date_publication, category_idcategory)
        VALUES( :title, :author, :date_publication, :category )");
        $stmt->execute([
            "title" => $title,
            "author" => $author,
            "date_publication" => $date_publication,
            "category" => $category, // Utilisation de la catégorie récupérée
        ]);

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

if(isset($_GET['action']) && $_GET['action'] == 'delete') {

    $idbook = $_GET['id_book'];

    try {
        $stmt = $pdo->prepare("DELETE FROM book WHERE idbook = :idbook");

        $stmt->execute([
            "idbook" => $idbook,
        ]);

        echo "Le livre a bien été supprimé !";

    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}
if(isset($_GET['action']) && $_GET['action'] == 'modify') {
    $idbook = $_GET['id_book'];

    try {
        // Récupération des informations du livre
        $stmt = $pdo->prepare("SELECT * FROM book WHERE idbook = :idbook");
        $stmt->execute(["idbook" => $idbook]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book) {
            // Affichage du formulaire de modification
            echo '<div class="mt-8 bg-white p-6 rounded shadow-md">
                <h2 class="text-2xl font-bold mb-4">Modifier le livre</h2>
                <form method="POST" action="">
                    <input type="hidden" name="idbook" value="' . htmlspecialchars($book['idbook']) . '">

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                        <input type="text" id="title" name="title" value="' . htmlspecialchars($book['title']) . '" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="author" class="block text-sm font-medium text-gray-700">Author:</label>
                        <input type="text" id="author" name="author" value="' . htmlspecialchars($book['author']) . '" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="date_publication" class="block text-sm font-medium text-gray-700">Date Publication:</label>
                        <input type="date" id="date_publication" name="date_publication" value="' . htmlspecialchars($book['date_publication']) . '" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                        <input type="number" id="category" name="category" value="' . htmlspecialchars($book['category_idcategory']) . '" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Modifier le livre</button>
                </form>
            </div>';
        } else {
            echo '<p class="text-red-500">Livre introuvable.</p>';
        }
    } catch (PDOException $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
}

if (isset($_POST['update'])) {
    $idbook = $_POST['idbook'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $date_publication = $_POST['date_publication'];
    $category = $_POST['category'];

    try {
        $stmt = $pdo->prepare("UPDATE book SET title = :title, author = :author, date_publication = :date_publication, category_idcategory = :category WHERE idbook = :idbook");
        $stmt->execute([
            "title" => $title,
            "author" => $author,
            "date_publication" => $date_publication,
            "category" => $category,
            "idbook" => $idbook,
        ]);

        echo "Le livre a bien été modifié !";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Mes livres en BDD</h1>

        <!-- Table des livres -->
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Title</th>
                    <th class="border border-gray-300 px-4 py-2">Author</th>
                    <th class="border border-gray-300 px-4 py-2">Date Publication</th>
                    <th class="border border-gray-300 px-4 py-2">Category</th>
                    <th class="border border-gray-300 px-4 py-2">Supprimer</th>
                    <th class="border border-gray-300 px-4 py-2">Modifier</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($book["title"]) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($book["author"]) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($book["date_publication"]) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($book["category_idcategory"]) ?></td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <a href="?id_book=<?= $book["idbook"] ?>&action=delete" class="text-red-500 hover:underline">Supprimer</a>
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            <a href="?id_book=<?= $book["idbook"] ?>&action=modify" class="text-blue-500 hover:underline">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Formulaire d'ajout de livre -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Ajouter un livre</h2>
            <form method="POST" class="bg-white p-6 rounded shadow-md">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" id="title" name="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="author" class="block text-sm font-medium text-gray-700">Author:</label>
                    <input type="text" id="author" name="author" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="date_publication" class="block text-sm font-medium text-gray-700">Date Publication:</label>
                    <input type="date" id="date_publication" name="date_publication" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                    <input type="number" id="category" name="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Créer un livre</button>
            </form>
        </div>

        <!-- Boutons de navigation -->
        <div class="mt-8">
            <h1 class="text-xl font-bold">Bienvenue, <?= htmlspecialchars($_SESSION['user']['email']) ?> !</h1>
            <p class="mb-4">Vous êtes connecté.</p>
            <a href="profil.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Aller à la page profil</a>
            <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 ml-4">Se déconnecter</a>
        </div>
    </div>

</body>
</html>