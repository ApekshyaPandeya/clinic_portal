<?php
// public/doctors.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Only admins can see this
if ($_SESSION['role'] !== 'admin') {
    redirect('admin_dashboard.php');
}

// Search logic
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM doctors";
$params = [];

if ($search) {
    $query .= " WHERE name LIKE ? OR specialization LIKE ?";
    $params = ["%$search%", "%$search%"];
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$doctors = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h1>Doctors</h1>
                    <p style="color: var(--text-light);">Manage medical staff</p>
                </div>
                <a href="add_doctor.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Doctor
                </a>
            </header>

            <div class="card" style="margin-bottom: 2rem;">
                <form method="GET" class="form-group" style="margin-bottom: 0;">
                    <div style="position: relative;">
                        <i class="fas fa-search"
                            style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-light);"></i>
                        <input type="text" name="search" value="<?= h($search) ?>"
                            placeholder="Search doctors by name or specialization..." style="padding-left: 2.5rem;">
                    </div>
                </form>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($doctors)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-light);">No doctors found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($doctors as $d): ?>
                                <tr>
                                    <td style="font-weight: 500;"><?= h($d['name']) ?></td>
                                    <td><?= h($d['specialization']) ?></td>
                                    <td><?= h($d['email']) ?></td>
                                    <td><?= h($d['contact']) ?></td>
                                    <td>
                                        <div style="display: flex; gap: 1rem;">
                                            <a href="edit_doctor.php?id=<?= $d['id'] ?>" style="color: var(--primary);"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="delete_doctor.php?id=<?= $d['id'] ?>" style="color: var(--error);"
                                                onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                        </div>
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