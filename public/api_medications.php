<?php
// public/api_medications.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Only admins can search all medications this way
if ($_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$search = $_GET['search'] ?? '';

$query = "SELECT m.medication_name, m.dosage, p.name as patient_name 
          FROM medications m 
          JOIN patients p ON m.patient_id = p.id 
          WHERE m.medication_name LIKE ? OR p.name LIKE ?
          LIMIT 5";

$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%", "%$search%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($results);
?>