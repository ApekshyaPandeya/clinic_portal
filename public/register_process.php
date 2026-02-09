<?php
session_start();
require_once '../config/db.php'; // This file creates $pdo

if (isset($_POST['register'])) {
    // 1. CSRF Check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed. This request was blocked for security.");
    }

    $name = $_POST['full_name'];
    $email = $_POST['email'];
    // 2. Hashing the password (Master Level Security)
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'patient';

    try {
        // 3. Check if email already exists (Using PDO style)
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        
        if ($check->fetch()) {
            header("Location: register.php?error=exists");
            exit();
        }

        // 4. Insert new user using Prepared Statements
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
        
        // In PDO, we pass variables directly into execute()
        if ($stmt->execute([$name, $email, $password, $role])) {
            header("Location: login.php?success=registered");
            exit();
        } else {
            header("Location: register.php?error=failed");
            exit();
        }
    } catch (PDOException $e) {
        // Log the error secretly and show a polite message
        error_log("Registration Error: " . $e->getMessage());
        die("An error occurred during registration. Please try again later.");
    }
}
?>