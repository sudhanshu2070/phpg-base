<?php
$host = 'localhost';   
$port = 5432;          
$db   = 'postgres';     
$user = 'postgres';    
$pass = 'admin123';    

try {
    // Connect with PDO
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);

    // Set error mode to exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set the schema search path explicitly to 'public'
    $pdo->exec("SET search_path TO public");

} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}


