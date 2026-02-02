<?php
// public/manage_medications.php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

if ($_SESSION['role'] !== 'admin') {
    redirect('user_dashboard.php');
}

$search = $_GET['search'] ?? '';

// Fetch medications based on search
$query = "SELECT m.*, p.name as patient_name, d.name as doctor_name 
          FROM medications m 
          JOIN patients p ON m.patient_id = p.id 
          JOIN doctors d ON m.doctor_id = d.id";

if ($search) {
    $query .= " WHERE m.medication_name LIKE ? OR p.name LIKE ?";
    $stmt = $pdo->prepare($query . " ORDER BY m.created_at DESC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query($query . " ORDER BY m.created_at DESC");
}
$medications = $stmt->fetchAll();

// Fetch patients and doctors for the add form
$patients_list = $pdo->query("SELECT id, name FROM patients")->fetchAll();
$doctors_list = $pdo->query("SELECT id, name FROM doctors")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_medication'])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');

    $p_id = $_POST['patient_id'];
    $d_id = $_POST['doctor_id'];
    $m_name = $_POST['medication_name'];
    $dosage = $_POST['dosage'];
    $frequency = $_POST['frequency'];
    $start = $_POST['start_date'];

    if ($p_id && $d_id && $m_name) {
        $stmt = $pdo->prepare("INSERT INTO medications (patient_id, doctor_id, medication_name, dosage, frequency, start_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$p_id, $d_id, $m_name, $dosage, $frequency, $start]);
        redirect('manage_medications.php?msg=Medication added');
    }
}
$token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Medications - Clinic Portal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1>Medications</h1>
                <button onclick="document.getElementById('add-med-modal').style.display='block'" class="btn btn-primary"
                    style="width: auto;">+ New Prescription</button>
            </header>

            <!-- AJAX Search -->
            <div class="card" style="margin-bottom: 2rem;">
                <h3>Search Medications (Live Autocomplete)</h3>
                <input type="text" id="ajax-med-search" placeholder="Type medication or patient name..."
                    style="width: 100%; padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color); margin-top: 1rem;">
                <div id="ajax-results"></div>
            </div>

            <div class="table-wrapper card">
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Medication</th>
                            <th>Dosage</th>
                            <th>Prescribed By</th>
                            <th>Start Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($medications)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">No
                                    records found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($medications as $m): ?>
                                <tr>
                                    <td><?= h($m['patient_name']) ?></td>
                                    <td style="font-weight: 600; color: var(--primary);"><?= h($m['medication_name']) ?></td>
                                    <td><?= h($m['dosage']) ?> (<?= h($m['frequency']) ?>)</td>
                                    <td><?= h($m['doctor_name']) ?></td>
                                    <td><?= h($m['start_date']) ?></td>
                                    <td>
                                        <a href="delete_medication.php?id=<?= $m['id'] ?>" style="color: var(--error);"
                                            onclick="return confirm('Delete this record?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal for Add Medication -->
    <div id="add-med-modal"
        style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
        <div class="card" style="width: 500px; margin: 50px auto;">
            <h2>Add Prescription</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $token ?>">
                <div class="form-group" style="margin-top: 1rem;">
                    <label>Patient</label>
                    <select name="patient_id" required
                        style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid #ddd;">
                        <?php foreach ($patients_list as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= h($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Doctor</label>
                    <select name="doctor_id" required
                        style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid #ddd;">
                        <?php foreach ($doctors_list as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= h($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Medication Name</label>
                    <input type="text" name="medication_name" required>
                </div>
                <div class="form-group">
                    <label>Dosage</label>
                    <input type="text" name="dosage" required placeholder="e.g. 500mg">
                </div>
                <div class="form-group">
                    <label>Frequency</label>
                    <input type="text" name="frequency" required placeholder="e.g. Once daily">
                </div>
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" required>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" name="add_medication" class="btn btn-primary"
                        style="width: auto;">Save</button>
                    <button type="button" onclick="document.getElementById('add-med-modal').style.display='none'"
                        class="btn" style="background:#e2e8f0; color: #475569; width: auto;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('ajax-med-search').addEventListener('input', function (e) {
            const query = e.target.value;
            const resultsDiv = document.getElementById('ajax-results');

            if (query.length < 2) {
                resultsDiv.innerHTML = '';
                return;
            }

            fetch('api_medications.php?search=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        resultsDiv.innerHTML = '';
                        return;
                    }

                    let html = '<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; margin-top: 1rem; overflow: hidden;">';
                    if (data.length === 0) {
                        html += '<div style="padding: 1rem; color: #64748b;">No matching prescriptions found.</div>';
                    } else {
                        data.forEach(m => {
                            html += `<div style="padding: 1rem; border-bottom: 1px solid #e2e8f0; last-child { border-bottom: none; }">
                                        <div style="font-weight: 600; color: #3b82f6;">${m.medication_name}</div>
                                        <div style="font-size: 0.85rem; color: #64748b;">For: ${m.patient_name} (${m.dosage})</div>
                                     </div>`;
                        });
                    }
                    html += '</div>';
                    resultsDiv.innerHTML = html;
                })
                .catch(err => console.error('Search error:', err));
        });
    </script>
</body>

</html>