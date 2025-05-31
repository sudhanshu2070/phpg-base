<?php
session_start();
require __DIR__ . '/../inc/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$sender_id = $_SESSION['user_id'];

// Get POST data safely
$receiver_id = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$parent_message_id = isset($_POST['parent_message_id']) ? $_POST['parent_message_id'] : null;


if ($receiver_id <= 0 || $message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

try {
    // $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message, parent_message_id, sent_at, created_at)
                       VALUES (?, ?, ?, ?, NOW(), NOW())");
    // $stmt->execute([$sender_id, $receiver_id, $message]);
    $stmt->execute([$sender_id, $receiver_id, $message, $parent_message_id]);

    echo json_encode(['success' => true, 'message' => 'Message sent']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
