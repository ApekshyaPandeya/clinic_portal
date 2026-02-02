<?php
// public/api_patients.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$search = $_GET['search'] ?? '';
$query = "SELECT id, name, gender, dob, contact FROM patients";
$params = [];

if ($search) {
    $query .= " WHERE name LIKE ? OR contact LIKE ?";
    $params = ["%$search%", "%$search%"];
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$patients = $stmt->fetchAll();

$today = new DateTime('today');
foreach ($patients as &$p) {
    $birthDate = new DateTime($p['dob']);
    $p['age'] = $birthDate->diff($today)->y;
    $p['name'] = h($p['name']); // Escape for security
}

header('Content-Type: application/json');
echo json_encode($patients);
?>