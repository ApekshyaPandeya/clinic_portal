<?php
// config/db.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Server Configuration (Updated for School Server)
$host = "localhost";
$user = "np03cy4a240062";
$pass = "ycvoHfpjCw";
$dbname = "np03cy4a240062";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>