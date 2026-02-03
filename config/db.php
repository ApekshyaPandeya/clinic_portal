<?php
// config/db.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * DB Configuration
 * Toggle between local and server settings
 */

// Local XAMPP Defaults
/*
$host = "localhost";
$dbname = "clinic_db";
$user = "root";
$pass = "";
*/

// College Server Settings (STRICTLY FOR DEPLOYMENT)
$host = "localhost";
$dbname = "np03cy4a240062";
$user = "np03cy4a240062";
$pass = "ycvoHfpjCw";


try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Database connection failed. Please check your configuration.");
}
?>