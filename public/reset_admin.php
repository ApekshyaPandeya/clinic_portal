<?php
// public/reset_admin.php
require_once '../config/db.php';

try {
    // 1. Generate the fresh hash
    $password = password_hash('password123', PASSWORD_DEFAULT);

    // 2. Disable checks briefly
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // 3. Clear the admin user if they exist
    $pdo->exec("DELETE FROM users WHERE username = 'admin'");

    // 4. Insert the fresh Admin account
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name, email) VALUES ('admin', ?, 'admin', 'Apekshya Pandeya', 'admin@clinicportal.com')");
    $stmt->execute([$password]);

    // 5. Re-enable checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    echo "<h2 style='color: green; font-family: sans-serif;'>✅ Admin password reset successfully!</h2>";
    echo "<p>You can now log in at <a href='login.php'>login.php</a> with:</p>";
    echo "<ul><li>Username: <b>admin</b></li><li>Password: <b>password123</b></li></ul>";

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Error: " . $e->getMessage() . "</h2>";
}
?>