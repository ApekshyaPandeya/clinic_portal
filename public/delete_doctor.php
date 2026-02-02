<?php
// public/delete_doctor.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Only admins can see this
if ($_SESSION['role'] !== 'admin') {
    redirect('admin_dashboard.php');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM doctors WHERE id = ?");
    if ($stmt->execute([$id])) {
        redirect('doctors.php?msg=Doctor deleted successfully');
    }
} else {
    redirect('doctors.php');
}
?>