<?php
// public/profile.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT p.*, u.email as user_email FROM patients p JOIN users u ON p.user_id = u.id WHERE p.user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

if (!$patient) {
    die("Patient profile not found.");
}

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token'] ?? '');

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $stmt = $pdo->prepare("UPDATE patients SET name = ?, gender = ?, dob = ?, contact = ?, address = ? WHERE user_id = ?");
    if ($stmt->execute([$name, $gender, $dob, $contact, $address, $user_id])) {
        $success = "Profile updated successfully!";
        // Refresh data
        $stmt = $pdo->prepare("SELECT p.*, u.email as user_email FROM patients p JOIN users u ON p.user_id = u.id WHERE p.user_id = ?");
        $stmt->execute([$user_id]);
        $patient = $stmt->fetch();
    }
}
$token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Profile - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <h1>My Profile</h1>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Manage your personal information.</p>

            <?php if ($success): ?>
                <div class="alert alert-success"
                    style="background: #f0fdf4; color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #dcfce7;">
                    <i class="fas fa-check-circle"></i>
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <div class="card" style="max-width: 600px;">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $token ?>">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= h($patient['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email (Read-only)</label>
                        <input type="text" value="<?= h($patient['user_email']) ?>" readonly
                            style="background: #f1f5f9;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" required
                                style="width: 100%; padding: 1rem; border: 1px solid var(--border-color); border-radius: 12px;">
                                <option value="Male" <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female
                                </option>
                                <option value="Other" <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" value="<?= h($patient['dob']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact" value="<?= h($patient['contact']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" rows="3"
                            style="width: 100%; padding: 1rem; border: 1px solid var(--border-color); border-radius: 12px;"><?= h($patient['address']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>