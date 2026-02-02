<?php
// public/user_dashboard.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Check if user is logged in and is a 'user'
if ($_SESSION['role'] !== 'user') {
    redirect('admin_dashboard.php');
}

$user_id = $_SESSION['user_id'];

// Get patient details linked to this user
$stmt = $pdo->prepare("SELECT * FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

$appointments = [];
$medications = [];

if ($patient) {
    $patient_id = $patient['id'];

    // Fetch appointments
    $stmt = $pdo->prepare("SELECT a.*, d.name as doctor_name, d.specialization 
                           FROM appointments a 
                           JOIN doctors d ON a.doctor_id = d.id 
                           WHERE a.patient_id = ? 
                           ORDER BY a.appointment_date DESC");
    $stmt->execute([$patient_id]);
    $appointments = $stmt->fetchAll();

    // Fetch medications
    $stmt = $pdo->prepare("SELECT m.*, d.name as doctor_name 
                           FROM medications m 
                           JOIN doctors d ON m.doctor_id = d.id 
                           WHERE m.patient_id = ? 
                           ORDER BY m.created_at DESC");
    $stmt->execute([$patient_id]);
    $medications = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Health Dashboard - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header style="margin-bottom: 3rem;">
                <h1>Hello,
                    <?= h($_SESSION['full_name']) ?>
                </h1>
                <p style="color: var(--text-muted);">Manage your appointments and view your prescriptions.</p>
            </header>

            <?php if (!$patient): ?>
                <div class="alert alert-error" style="background: #fff7ed; color: #9a3412; border: 1px solid #ffedd5;">
                    <i class="fas fa-info-circle"></i> Your account is not yet linked to a patient profile. Please contact
                    the administrator.
                </div>
            <?php else: ?>
                <div class="stats-grid">
                    <div class="card stat-card" style="border-left: 4px solid var(--primary);">
                        <h3>Upcoming Visits</h3>
                        <div class="value">
                            <?= count(array_filter($appointments, fn($a) => $a['status'] == 'Scheduled')) ?>
                        </div>
                    </div>
                    <div class="card stat-card" style="border-left: 4px solid #10b981;">
                        <h3>Active Medications</h3>
                        <div class="value">
                            <?= count($medications) ?>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; margin-top: 3rem;">
                    <!-- Appointments List -->
                    <section class="card">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                            <h2>My Appointments</h2>
                            <a href="book_appointment.php" class="btn btn-primary"
                                style="width: auto; padding: 0.5rem 1rem; font-size: 0.9rem;">Book New</a>
                        </div>
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($appointments)): ?>
                                        <tr>
                                            <td colspan="3" style="text-align: center; color: var(--text-muted);">No
                                                appointments found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($appointments as $app): ?>
                                            <tr>
                                                <td>
                                                    <div style="font-weight: 600;">
                                                        <?= h($app['doctor_name']) ?>
                                                    </div>
                                                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                                                        <?= h($app['specialization']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?= date('M d, Y H:i', strtotime($app['appointment_date'])) ?>
                                                </td>
                                                <td>
                                                    <span
                                                        style="font-size: 0.75rem; font-weight: 700; color: <?= $app['status'] == 'Scheduled' ? '#2563eb' : ($app['status'] == 'Completed' ? '#166534' : '#991b1b') ?>;">
                                                        <?= strtoupper(h($app['status'])) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- Medications List -->
                    <section class="card">
                        <h2 style="margin-bottom: 1.5rem;">Recent Medications</h2>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <?php if (empty($medications)): ?>
                                <p style="color: var(--text-muted); text-align: center;">No medications prescribed yet.</p>
                            <?php else: ?>
                                <?php foreach ($medications as $med): ?>
                                    <div
                                        style="padding: 1rem; background: #f8fafc; border-radius: 12px; border: 1px solid var(--border-color);">
                                        <div style="font-weight: 700; color: var(--primary);">
                                            <?= h($med['medication_name']) ?>
                                        </div>
                                        <div style="font-size: 0.9rem; margin: 0.25rem 0;">
                                            <?= h($med['dosage']) ?> -
                                            <?= h($med['frequency']) ?>
                                        </div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">Prescribed by:
                                            <?= h($med['doctor_name']) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>