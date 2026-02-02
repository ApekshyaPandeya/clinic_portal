<?php
// public/book_appointment.php
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

if (!$patient) {
    die("Patient profile not found.");
}

$doctors = $pdo->query("SELECT id, name, specialization FROM doctors")->fetchAll();
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token'] ?? '');

    $d_id = $_POST['doctor_id'];
    $date = $_POST['appointment_date'];
    $notes = $_POST['notes'];

    if ($d_id && $date) {
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, notes) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$patient['id'], $d_id, $date, $notes])) {
            redirect('my_appointments.php?msg=Appointment booked');
        }
    }
}
$token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Appointment - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <h1>Book an Appointment</h1>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Schedule a visit with one of our specialists.</p>

            <div class="card" style="max-width: 600px;">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $token ?>">

                    <div class="form-group">
                        <label>Select Specialist</label>
                        <select name="doctor_id" required
                            style="width: 100%; padding: 1rem; border: 1px solid var(--border-color); border-radius: 12px;">
                            <?php foreach ($doctors as $d): ?>
                                <option value="<?= $d['id'] ?>">
                                    <?= h($d['name']) ?> (
                                    <?= h($d['specialization']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Preferred Date & Time</label>
                        <input type="datetime-local" name="appointment_date" required min="<?= date('Y-m-d\TH:i') ?>">
                    </div>

                    <div class="form-group">
                        <label>Notes / Symptoms</label>
                        <textarea name="notes" rows="4"
                            placeholder="Briefly describe why you are scheduling this visit..."
                            style="width: 100%; padding: 1rem; border: 1px solid var(--border-color); border-radius: 12px;"></textarea>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                        <a href="my_appointments.php" class="btn" style="background: #e2e8f0; color: #475569;">Back</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>