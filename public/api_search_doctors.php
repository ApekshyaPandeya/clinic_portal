<?php
// public/api_search_doctors.php
require_once '../config/db.php';
require_once '../includes/functions.php';

$query = $_GET['q'] ?? '';
$search = "%$query%";

// Fetching data without page reload using Ajax using PDO
$stmt = $pdo->prepare("SELECT name, specialization FROM doctors WHERE name LIKE ? OR specialization LIKE ?");
$stmt->execute([$search, $search]);
$doctors = $stmt->fetchAll();

// Return data as JSON for the JavaScript Fetch API
header('Content-Type: application/json');
echo json_encode($doctors);
?>