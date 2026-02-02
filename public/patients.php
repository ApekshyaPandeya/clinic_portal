<?php
// public/patients.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// Only admins can access the full registry
if ($_SESSION['role'] !== 'admin') {
    redirect('user_dashboard.php');
}

$search = $_GET['search'] ?? '';
$query = "SELECT * FROM patients";
$params = [];

if ($search) {
    $query .= " WHERE name LIKE ? OR contact LIKE ?";
    $params = ["%$search%", "%$search%"];
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$patients = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h1>Patient Registry</h1>
                    <p style="color: var(--text-muted);">Manage all clinic patients.</p>
                </div>
                <a href="patient_add.php" class="btn btn-primary" style="width: auto;">
                    <i class="fas fa-plus"></i> Add Patient
                </a>
            </header>

            <!-- Regular PHP Search (No AJAX) -->
            <div class="card" style="margin-bottom: 2rem;">
                <form method="GET" style="display: flex; gap: 1rem;">
                    <input type="text" name="search" value="<?= h($search) ?>"
                        placeholder="Search by name or contact..."
                        style="flex: 1; padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color);">
                    <button type="submit" class="btn btn-primary" style="width: auto; padding: 0 2rem;">Search</button>
                    <?php if ($search): ?>
                        <a href="patients.php" class="btn"
                            style="background: #f1f5f9; color: var(--text-dark); text-decoration: none; display: flex; align-items: center;">Clear</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-wrapper card">
                <table id="patients-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($patients)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No
                                    patients found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($patients as $p): ?>
                                <tr>
                                    <td>#<?= h($p['id']) ?></td>
                                    <td style="font-weight: 600;"><?= h($p['name']) ?></td>
                                    <td><?= h($p['gender']) ?></td>
                                    <td><?= h($p['contact']) ?></td>
                                    <td>
                                        <div style="display: flex; gap: 1rem;">
                                            <a href="patient_edit.php?id=<?= $p['id'] ?>" style="color: var(--primary);"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="patient_delete.php?id=<?= $p['id'] ?>" style="color: var(--error);"
                                                onclick="return confirm('Are you sure you want to delete this patient?')"><i
                                                    class="fas fa-trash"></i></a>
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