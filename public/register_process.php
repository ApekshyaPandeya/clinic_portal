<?php
session_start();
require_once '../config/db.php';

if (isset($_POST['register'])) {
    // CSRF Check (Mandatory for POST) [cite: 24]
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed.");
    }

    $name = $_POST['full_name'];
    $email = $_POST['email'];
    // Hashing the password (Master Level Security) [cite: 4, 9]
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'patient';

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        header("Location: register.php?error=exists");
        exit();
    }

    // Insert new user [cite: 16, 56]
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    
    if ($stmt->execute()) {
        header("Location: login.php?success=registered");
    } else {
        header("Location: register.php?error=failed");
    }
}
?>