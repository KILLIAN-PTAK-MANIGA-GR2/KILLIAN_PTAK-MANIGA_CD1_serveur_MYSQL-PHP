<?php
require_once '../database/db_connection.php';
require_once '../helpers/utils.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        registerUser();
        break;
    case 'login':
        loginUser();
        break;
    case 'profile':
        getUserProfile();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function registerUser() {
    global $conn;

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($username) || empty($password) || empty($email)) {
        echo json_encode(['error' => 'All fields are required']);
        return;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $hashedPassword, $email])) {
        echo json_encode(['success' => 'User registered successfully']);
    } else {
        echo json_encode(['error' => 'Registration failed']);
    }
}

function loginUser() {
    global $conn;

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['error' => 'All fields are required']);
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(['success' => 'Login successful']);
    } else {
        echo json_encode(['error' => 'Invalid username or password']);
    }
}

function getUserProfile() {
    global $conn;

    session_start();
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'User not logged in']);
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['user' => $user]);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'User data loaded successfully']);
?>