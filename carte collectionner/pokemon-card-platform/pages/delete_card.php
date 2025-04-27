<?php
require '../db.php'; // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM cards WHERE id = ?");
    $stmt->execute([$id]);

    echo "Carte supprimée avec succès !";
}
?>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
    <button type="submit">Supprimer</button>
</form>