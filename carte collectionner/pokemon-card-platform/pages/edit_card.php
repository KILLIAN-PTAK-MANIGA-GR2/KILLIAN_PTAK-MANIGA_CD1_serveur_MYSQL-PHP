<?php
require '../db.php'; // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE cards SET name = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $description, $id]);

    echo "Carte mise à jour avec succès !";
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cards WHERE id = ?");
$stmt->execute([$id]);
$card = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $card['id']; ?>">
    <input type="text" name="name" value="<?php echo htmlspecialchars($card['name']); ?>" required>
    <textarea name="description"><?php echo htmlspecialchars($card['description']); ?></textarea>
    <button type="submit">Mettre à jour</button>
</form>