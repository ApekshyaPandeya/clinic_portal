<?php
// public/patient_delete.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Only admins can delete (or according to your rules)
if ($_SESSION['role'] !== 'admin') {
    redirect('patients.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM patients WHERE id = ?");
    if ($stmt->execute([$id])) {
        redirect('patients.php?msg=Patient deleted successfully');
    }
} else {
    redirect('patients.php');
}
?>