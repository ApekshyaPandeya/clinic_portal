<?php
// includes/auth_check.php
session_start();

// 1. Mandatory Login Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/**
 * Role-Based Access Control
 */
function restrictToAdmin()
{
    if ($_SESSION['role'] !== 'admin') {
        header("Location: dashboard.php?error=unauthorized");
        exit();
    }
}
?>