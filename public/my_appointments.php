<?php
// public/my_appointments.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

if ($_SESSION['role'] !== 'user') {
    redirect('admin_dashboard.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

$appointments = [];
if ($patient) {
    $stmt = $pdo->prepare("SELECT a.*, d.name as doctor_name, d.specialization 
                           FROM appointments a 
                           JOIN doctors d ON a.doctor_id = d.id 
                           WHERE a.patient_id = ? 
                           ORDER BY a.appointment_date DESC");
    $stmt->execute([$patient['id']]);
    $appointments = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Appointments - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1>My Appointments</h1>
                <a href="book_appointment.php" class="btn btn-primary" style="width: auto;">Book New</a>
            </header>

            <div class="table-wrapper card">
                <table>
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 2rem;">No
                                    appointments scheduled.</td>
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
                                        <?= date('F d, Y - h:i A', strtotime($app['appointment_date'])) ?>
                                    </td>
                                    <td>
                                        <span
                                            style="padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; background: <?= $app['status'] == 'Scheduled' ? '#eff6ff' : '#f0fdf4' ?>; color: <?= $app['status'] == 'Scheduled' ? '#2563eb' : '#166534' ?>;">
                                            <?= h($app['status']) ?>
                                        </span>
                                    </td>
                                    <td style="color: var(--text-muted); font-size: 0.85rem;">
                                        <?= h($app['notes'] ?: 'N/A') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>