<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require __DIR__ . '/../inc/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user1 = isset($_GET['user1']) ? (int)$_GET['user1'] : 0;
$user2 = isset($_GET['user2']) ? (int)$_GET['user2'] : 0;

if ($user1 <= 0 || $user2 <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user IDs']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT *
        FROM messages
        WHERE (sender_id = ? AND receiver_id = ?)
        OR (sender_id = ? AND receiver_id = ?)
        ORDER BY created_at ASC
    ");
    $stmt->execute([$user1, $user2, $user2, $user1]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
