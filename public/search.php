<?php
require_once '../config/db.php';

// Get the search query from the Ajax request [cite: 25, 77]
$query = isset($_GET['q']) ? $_GET['q'] : '';
$search = "%$query%";

// Use Prepared Statements to prevent SQL Injection [cite: 22, 60, 68]
$stmt = $conn->prepare("SELECT id, full_name, email FROM users WHERE role = 'patient' AND (full_name LIKE ? OR email LIKE ?)");
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Apply XSS Protection to all output 
        $id = $row['id'];
        $name = htmlspecialchars($row['full_name']);
        $email = htmlspecialchars($row['email']);
        
        // Return structured table rows for the Ajax update
        echo "<tr>
                <td>#$id</td>
                <td>$name</td>
                <td>$email</td>
                <td>
                    <a href='edit.php?id=$id' class='btn-edit'>Edit</a>
                    <a href='delete_process.php?id=$id' class='btn-delete' onclick=\"return confirm('Are you sure?')\">Delete</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4' style='text-align:center;'>No patients found matching your search.</td></tr>";
}
?>