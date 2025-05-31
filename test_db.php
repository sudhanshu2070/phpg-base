<?php
require 'inc/db.php';

try {
    $result = $pdo->query("SELECT * FROM users LIMIT 1");
    $user = $result->fetch(PDO::FETCH_ASSOC);
    echo "Connected! First user: ";
    print_r($user);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
