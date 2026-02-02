<?php
// public/patient_edit.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('patients.php');
}

// Fetch current data
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$id]);
$patient = $stmt->fetch();

if (!$patient) {
    redirect('patients.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';

    if ($name && $dob) {
        $stmt = $pdo->prepare("UPDATE patients SET name = ?, gender = ?, dob = ?, contact = ?, address = ? WHERE id = ?");
        if ($stmt->execute([$name, $gender, $dob, $contact, $address, $id])) {
            redirect('patients.php?msg=Patient updated successfully');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header style="margin-bottom: 2rem;">
                <h1>Edit Patient</h1>
                <p style="color: var(--text-light);">Update patient records.</p>
            </header>

            <div class="card" style="max-width: 800px;">
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" id="name" value="<?= h($patient['name']) ?>" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" required>
                                <option value="Male" <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female
                                </option>
                                <option value="Other" <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" name="dob" id="dob" value="<?= h($patient['dob']) ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="text" name="contact" id="contact" value="<?= h($patient['contact']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" rows="3"><?= h($patient['address']) ?></textarea>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Update Patient</button>
                        <a href="patients.php" class="btn" style="background: #e2e8f0; color: #475569;">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>