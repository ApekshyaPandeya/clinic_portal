<?php
// public/edit_doctor.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Only admins can see this
if ($_SESSION['role'] !== 'admin') {
    redirect('admin_dashboard.php');
}

$id = $_GET['id'] ?? null;
if (!$id) {
    redirect('doctors.php');
}

// Fetch current data
$stmt = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->execute([$id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    redirect('doctors.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $specialization = $_POST['specialization'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($name && $specialization) {
        $stmt = $pdo->prepare("UPDATE doctors SET name = ?, specialization = ?, contact = ?, email = ? WHERE id = ?");
        if ($stmt->execute([$name, $specialization, $contact, $email, $id])) {
            redirect('doctors.php?msg=Doctor updated successfully');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header style="margin-bottom: 2rem;">
                <h1>Edit Doctor</h1>
                <p style="color: var(--text-light);">Update doctor information.</p>
            </header>

            <div class="card" style="max-width: 800px;">
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Doctor's Name</label>
                        <input type="text" name="name" id="name" value="<?= h($doctor['name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="specialization">Specialization</label>
                        <select name="specialization" id="specialization" required>
                            <option value="General Physician" <?= $doctor['specialization'] == 'General Physician' ? 'selected' : '' ?>>General Physician</option>
                            <option value="Cardiology" <?= $doctor['specialization'] == 'Cardiology' ? 'selected' : '' ?>>
                                Cardiology</option>
                            <option value="Neurology" <?= $doctor['specialization'] == 'Neurology' ? 'selected' : '' ?>>
                                Neurology</option>
                            <option value="Pediatrics" <?= $doctor['specialization'] == 'Pediatrics' ? 'selected' : '' ?>>
                                Pediatrics</option>
                            <option value="Dermatology" <?= $doctor['specialization'] == 'Dermatology' ? 'selected' : '' ?>>Dermatology</option>
                            <option value="Orthopedics" <?= $doctor['specialization'] == 'Orthopedics' ? 'selected' : '' ?>>Orthopedics</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="contact">Contact Number</label>
                            <input type="text" name="contact" id="contact" value="<?= h($doctor['contact']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" value="<?= h($doctor['email']) ?>">
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Update Doctor</button>
                        <a href="doctors.php" class="btn" style="background: #e2e8f0; color: #475569;">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>