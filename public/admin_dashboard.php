<?php
// public/admin_dashboard.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Only admins can see this
if ($_SESSION['role'] !== 'admin') {
    redirect('login.php');
}

// Fetch some stats for the dashboard
$patient_count = $pdo->query("SELECT COUNT(*) FROM patients")->fetchColumn();
$doctor_count = $pdo->query("SELECT COUNT(*) FROM doctors")->fetchColumn();
$appointment_count = $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();

// Fetch recent appointments
$stmt = $pdo->query("SELECT a.*, p.name as patient_name, d.name as doctor_name 
                     FROM appointments a 
                     JOIN patients p ON a.patient_id = p.id 
                     JOIN doctors d ON a.doctor_id = d.id 
                     ORDER BY a.appointment_date DESC LIMIT 5");
$recent_appointments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header style="margin-bottom: 2rem;">
                <h1>Dashboard</h1>
                <p style="color: var(--text-light);">Welcome back, <?= h($_SESSION['full_name']) ?></p>
            </header>

            <div class="stats-grid">
                <div class="card stat-card">
                    <h3>Total Patients</h3>
                    <div class="value"><?= $patient_count ?></div>
                </div>
                <div class="card stat-card">
                    <h3>Total Doctors</h3>
                    <div class="value"><?= $doctor_count ?></div>
                </div>
                <div class="card stat-card">
                    <h3>Total Appointments</h3>
                    <div class="value"><?= $appointment_count ?></div>
                </div>
            </div>

            <section class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2>Recent Appointments</h2>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_appointments)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: var(--text-light);">No recent
                                        appointments found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent_appointments as $app): ?>
                                    <tr>
                                        <td style="font-weight: 500;"><?= h($app['patient_name']) ?></td>
                                        <td><?= h($app['doctor_name']) ?></td>
                                        <td><?= date('M d, Y H:i', strtotime($app['appointment_date'])) ?></td>
                                        <td>
                                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; 
                                                background: <?= $app['status'] == 'completed' ? '#f0fdf4' : '#fff7ed' ?>; 
                                                color: <?= $app['status'] == 'completed' ? '#166534' : '#9a3412' ?>;">
                                                <?= ucfirst(h($app['status'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>