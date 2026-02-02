<?php
// public/test_db.php
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>System Diagnostics</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body style="display: flex; align-items: center; justify-content: center; height: 100vh;">
    <div class="glass" style="padding: 3rem; text-align: center; max-width: 500px; width: 100%;">
        <?php
        try {
            $stmt = $pdo->query("SELECT 'ACTIVE' as status");
            $result = $stmt->fetch();
            echo '<i class="fas fa-check-circle" style="font-size: 4rem; color: #10b981; margin-bottom: 2rem;"></i>';
            echo '<h1 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem;">DATABASE CONNECTION: ' . $result['status'] . '</h1>';
            echo '<p style="color: var(--text-muted);">Uplink established with <code>clinic_db</code> via PDO driver.</p>';
        } catch (Exception $e) {
            echo '<i class="fas fa-times-circle" style="font-size: 4rem; color: #f87171; margin-bottom: 2rem;"></i>';
            echo '<h1 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem;">CONNECTION FAILED</h1>';
            echo '<p style="color: #f87171; font-family: monospace; font-size: 0.8rem; background: rgba(239, 68, 68, 0.05); padding: 1rem; border-radius: 12px;">' . h($e->getMessage()) . '</p>';
        }
        ?>
        <div style="margin-top: 3rem;">
            <a href="index.php" class="btn-primary" style="padding: 0.8rem 2rem;">Return Home</a>
        </div>
    </div>
</body>

</html>