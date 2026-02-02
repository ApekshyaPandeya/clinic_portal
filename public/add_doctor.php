<?php
// public/add_doctor.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Only admins can see this
if ($_SESSION['role'] !== 'admin') {
    redirect('admin_dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token']);
    $name = $_POST['name'] ?? '';
    $spec = $_POST['specialization'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($name && $spec) {
        $stmt = $pdo->prepare("INSERT INTO doctors (name, specialization, contact, email) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $spec, $contact, $email]);
        redirect('doctors.php');
    }
}
$token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header style="margin-bottom: 2rem;">
                <h1>Add New Doctor</h1>
                <p style="color: var(--text-light);">Enter doctor information to add them to the staff list.</p>
            </header>

            <div class="card" style="max-width: 800px;">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $token ?>">

                    <div class="form-group">
                        <label for="name">Doctor's Name</label>
                        <input type="text" name="name" id="name" required placeholder="e.g. Dr. John Smith">
                    </div>

                    <div class="form-group">
                        <label for="specialization">Specialization</label>
                        <select name="specialization" id="specialization" required>
                            <option value="">Select Specialization</option>
                            <option value="General Physician">General Physician</option>
                            <option value="Cardiology">Cardiology</option>
                            <option value="Neurology">Neurology</option>
                            <option value="Pediatrics">Pediatrics</option>
                            <option value="Dermatology">Dermatology</option>
                            <option value="Orthopedics">Orthopedics</option>
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="contact">Contact Number</label>
                            <input type="text" name="contact" id="contact" placeholder="Enter contact number">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="Enter email address">
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Save Doctor</button>
                        <a href="doctors.php" class="btn" style="background: #e2e8f0; color: #475569;">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>