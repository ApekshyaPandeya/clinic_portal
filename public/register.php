<?php
// public/register.php
require_once '../config/db.php';
require_once '../includes/functions.php';
session_start();

if (isset($_SESSION['user_id'])) {
    redirect('admin_dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token'] ?? '');

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    $role = $_POST['role'] ?? 'user'; // Default to user

    if ($username && $email && $password && $full_name) {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $error = "Username or Email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password, $full_name, $role])) {
                $last_id = $pdo->lastInsertId();
                if ($role === 'user') {
                    $stmt_p = $pdo->prepare("INSERT INTO patients (user_id, name, gender, dob) VALUES (?, ?, 'Other', '1900-01-01')");
                    $stmt_p->execute([$last_id, $full_name]);
                }
                $success = "Registration successful! You can now <a href='login.php'>Login</a>.";
            } else {
                $error = "An error occurred during registration.";
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
$token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Clinic Portal</h1>
            <p>Create your account</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= h($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"
                style="background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;">
                <i class="fas fa-check-circle"></i>
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $token ?>">

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" required placeholder="John Doe">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required placeholder="john@example.com">
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required placeholder="john123">
                <div id="username-msg" style="font-size: 0.8rem; margin-top: 5px; font-weight: 500;"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="role">Account Type</label>
                <select name="role" id="role"
                    style="width: 100%; padding: 1rem; border: 1px solid var(--border-color); border-radius: 12px; background: white;">
                    <option value="user">Patient / User</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">
                Sign Up
            </button>
        </form>

        <p style="text-align: center; margin-top: 2rem; color: var(--text-muted); font-size: 0.9rem;">
            Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600;">Sign in</a>
        </p>
    </div>

    <script>
        const usernameInput = document.getElementById('username');
        const usernameMsg = document.getElementById('username-msg');

        usernameInput.addEventListener('input', function () {
            const username = this.value;
            if (username.length < 3) {
                usernameMsg.innerHTML = '';
                return;
            }

            fetch('check_username.php?username=' + encodeURIComponent(username))
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        usernameMsg.innerHTML = '❌ Username taken';
                        usernameMsg.style.color = '#ef4444';
                    } else {
                        usernameMsg.innerHTML = '✅ Username available';
                        usernameMsg.style.color = '#10b981';
                    }
                });
        });
    </script>
</body>

</html>