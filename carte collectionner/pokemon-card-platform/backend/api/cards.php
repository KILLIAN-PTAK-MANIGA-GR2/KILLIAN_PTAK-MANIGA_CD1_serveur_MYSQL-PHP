<?php
header('Content-Type: application/json');
echo json_encode(['success' => true, 'cards' => []]);

require_once '../database/db_connection.php';
require_once '../helpers/utils.php';

function fetchCards($db) {
    $query = "SELECT * FROM cards";
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCardById($db, $id) {
    $query = "SELECT * FROM cards WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $card = getCardById($db, $_GET['id']);
        if ($card) {
            echo json_encode($card);
        } else {
            echo json_encode(['error' => 'Card not found']);
        }
    } else {
        $cards = fetchCards($db);
        echo json_encode($cards);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>