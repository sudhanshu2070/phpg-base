<?php
require 'inc/db.php';

try {
    // Show current connected database
    $stmt = $pdo->query("SELECT current_database()");
    echo "Connected to database: " . $stmt->fetchColumn() . "<br><br>";

    // List all tables in 'public' schema
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Tables in 'public' schema:<br>";
    echo implode(', ', $tables);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
