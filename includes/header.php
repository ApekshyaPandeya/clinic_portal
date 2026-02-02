<nav class="main-nav">
    <div class="container">
        <a href="index.php" class="logo">Clinic<span>Portal</span></a>
        <div class="links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?php echo ($_SESSION['role'] == 'admin') ? 'admin_dashboard.php' : 'patient_dashboard.php'; ?>">Dashboard</a>
                <a href="logout.php" class="btn-logout">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn-register">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>