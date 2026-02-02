<?php
// public/check_username.php
require_once '../config/db.php';

$username = $_GET['username'] ?? '';

$response = ['exists' => false];

if ($username) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $response['exists'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>