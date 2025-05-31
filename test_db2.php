<?php
require 'inc/db.php';

try {
    $stmt = $pdo->query("SELECT current_database()");
    $currentDb = $stmt->fetchColumn();
    echo "Connected to database: " . $currentDb . "<br>";

    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in public schema: <br>";
    echo implode(", ", $tables);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
