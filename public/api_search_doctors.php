<?php
require_once '../config/db.php';

$query = $_GET['q'] ?? '';
$search = "%$query%";

// Fetching data without page reload using Ajax [cite: 28, 76]
$stmt = $conn->prepare("SELECT name, specialty FROM doctors WHERE name LIKE ? OR specialty LIKE ?");
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$doctors = [];
while($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

// Return data as JSON for the JavaScript Fetch API [cite: 77]
header('Content-Type: application/json');
echo json_encode($doctors);
?>