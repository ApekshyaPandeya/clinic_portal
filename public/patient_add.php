<?php
// public/patient_add.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token']);
    $name = $_POST['name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';

    if ($name && $dob) {
        $stmt = $pdo->prepare("INSERT INTO patients (name, gender, dob, contact, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $gender, $dob, $contact, $address]);
        redirect('patients.php');
    }
}
$token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header style="margin-bottom: 2rem;">
                <h1>Register New Patient</h1>
                <p style="color: var(--text-light);">Enter patient information to add them to the system.</p>
            </header>

            <div class="card" style="max-width: 800px;">
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $token ?>">

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" id="name" required placeholder="Enter full name">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" name="dob" id="dob" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="text" name="contact" id="contact" placeholder="Enter contact number">
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" rows="3" placeholder="Enter physical address"></textarea>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Save Patient</button>
                        <a href="patients.php" class="btn" style="background: #e2e8f0; color: #475569;">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>