<?php
// public/all_appointments.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

if ($_SESSION['role'] !== 'admin') {
    redirect('user_dashboard.php');
}

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['id']]);
}

// Fetch all appointments
$stmt = $pdo->query("SELECT a.*, p.name as patient_name, d.name as doctor_name 
                     FROM appointments a 
                     JOIN patients p ON a.patient_id = p.id 
                     JOIN doctors d ON a.doctor_id = d.id 
                     ORDER BY a.appointment_date DESC");
$appointments = $stmt->fetchAll();

$token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Appointments - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <h1>Clinic Appointments</h1>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Overview of all scheduled medical visits.</p>

            <div class="table-wrapper card">
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $app): ?>
                            <tr>
                                <td style="font-weight: 600;">
                                    <?= h($app['patient_name']) ?>
                                </td>
                                <td>
                                    <?= h($app['doctor_name']) ?>
                                </td>
                                <td>
                                    <?= date('M d, Y H:i', strtotime($app['appointment_date'])) ?>
                                </td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= $token ?>">
                                        <input type="hidden" name="id" value="<?= $app['id'] ?>">
                                        <select name="status" onchange="this.form.submit()"
                                            style="padding: 4px; border-radius: 4px; border: 1px solid #ddd;">
                                            <option value="Scheduled" <?= $app['status'] == 'Scheduled' ? 'selected' : '' ?>
                                                >Scheduled</option>
                                            <option value="Completed" <?= $app['status'] == 'Completed' ? 'selected' : '' ?>
                                                >Completed</option>
                                            <option value="Cancelled" <?= $app['status'] == 'Cancelled' ? 'selected' : '' ?>
                                                >Cancelled</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </td>
                                <td>
                                    <a href="delete_appointment.php?id=<?= $app['id'] ?>" style="color: var(--error);"
                                        onclick="return confirm('Delete this appointment?')"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>