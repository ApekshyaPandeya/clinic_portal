<?php
// public/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="logo">
        <i class="fas fa-clinic-medical"></i>
        <span>Clinic Portal</span>
    </div>

    <nav class="sidebar-nav">
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <!-- Admin Navigation -->
            <a href="admin_dashboard.php" class="nav-item <?= $current_page == 'admin_dashboard.php' ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="doctors.php" class="nav-item <?= $current_page == 'doctors.php' ? 'active' : '' ?>">
                <i class="fas fa-user-md"></i> Doctors
            </a>
            <a href="patients.php" class="nav-item <?= $current_page == 'patients.php' ? 'active' : '' ?>">
                <i class="fas fa-user-injured"></i> Patients
            </a>
            <a href="all_appointments.php" class="nav-item <?= $current_page == 'all_appointments.php' ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i> Appointments
            </a>
            <a href="manage_medications.php"
                class="nav-item <?= $current_page == 'manage_medications.php' ? 'active' : '' ?>">
                <i class="fas fa-pills"></i> Medications
            </a>
        <?php else: ?>
            <!-- User Navigation -->
            <a href="user_dashboard.php" class="nav-item <?= $current_page == 'user_dashboard.php' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> My Dashboard
            </a>
            <a href="my_appointments.php" class="nav-item <?= $current_page == 'my_appointments.php' ? 'active' : '' ?>">
                <i class="fas fa-calendar-check"></i> My Appointments
            </a>
            <a href="my_medications.php" class="nav-item <?= $current_page == 'my_medications.php' ? 'active' : '' ?>">
                <i class="fas fa-prescription-bottle-alt"></i> My Medications
            </a>
            <a href="profile.php" class="nav-item <?= $current_page == 'profile.php' ? 'active' : '' ?>">
                <i class="fas fa-user-circle"></i> My Profile
            </a>
        <?php endif; ?>

        <div style="margin-top: 3rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
            <a href="logout.php" class="nav-item" style="color: var(--error);">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>
</aside>