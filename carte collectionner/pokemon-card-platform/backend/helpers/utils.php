<?php
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatResponse($data, $status = 200) {
    header("Content-Type: application/json", true, $status);
    echo json_encode($data);
    exit();
}

function validateCardData($card) {
    $requiredFields = ['id', 'name', 'type', 'rarity'];
    foreach ($requiredFields as $field) {
        if (!isset($card[$field])) {
            return false;
        }
    }
    return true;
}

function logError($message) {
    error_log($message, 3, 'error_log.txt');
}
?>