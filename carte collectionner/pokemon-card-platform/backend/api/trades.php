<?php
require_once '../database/db_connection.php';
require_once '../helpers/utils.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        handleTradeRequest();
        break;
    case 'GET':
        handleGetTrades();
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}

function handleTradeRequest() {
    global $conn;

    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['user_id'] ?? null;
    $offeredCardId = $data['offered_card_id'] ?? null;
    $requestedCardId = $data['requested_card_id'] ?? null;

    if (!$userId || !$offeredCardId || !$requestedCardId) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO trades (user_id, offered_card_id, requested_card_id) VALUES (?, ?, ?)");
    if ($stmt->execute([$userId, $offeredCardId, $requestedCardId])) {
        echo json_encode(['message' => 'Trade request created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to create trade request']);
    }
}

function handleGetTrades() {
    global $conn;

    $stmt = $conn->query("SELECT * FROM trades");
    $trades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($trades);
}
?>