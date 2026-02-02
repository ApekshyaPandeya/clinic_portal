<?php
// public/my_medications.php
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

$medications = [];
if ($patient) {
    $stmt = $pdo->prepare("SELECT m.*, d.name as doctor_name 
                           FROM medications m 
                           JOIN doctors d ON m.doctor_id = d.id 
                           WHERE m.patient_id = ?");
    $stmt->execute([$patient['id']]);
    $medications = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Medications - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <h1>My Prescriptions</h1>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">A history of all medications prescribed to you.
            </p>

            <?php if (empty($medications)): ?>
                <div class="card" style="text-align: center; padding: 4rem;">
                    <i class="fas fa-prescription-bottle" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                    <p>No medications found in your records.</p>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                    <?php foreach ($medications as $m): ?>
                        <div class="card">
                            <h3 style="color: var(--primary); margin-bottom: 0.5rem;">
                                <?= h($m['medication_name']) ?>
                            </h3>
                            <div style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">
                                <?= h($m['dosage']) ?>
                            </div>
                            <div style="font-size: 0.9rem; margin-bottom: 0.25rem;"><strong>Frequency:</strong>
                                <?= h($m['frequency']) ?>
                            </div>
                            <div style="font-size: 0.9rem; margin-bottom: 0.25rem;"><strong>Prescribed By:</strong>
                                <?= h($m['doctor_name']) ?>
                            </div>
                            <div style="font-size: 0.9rem;"><strong>Start Date:</strong>
                                <?= h($m['start_date']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>