<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/pages/login.php');
    exit;
}

require_once '../../config/db.php';

// Fetch all appointments
try {
    $stmt = $pdo->query("SELECT 
                            a.id, 
                            u.name AS patient_name, 
                            d.name AS doctor_name, 
                            a.appointment_date, 
                            a.status 
                         FROM 
                            appointments a 
                         JOIN users u ON a.user_id = u.id 
                         JOIN doctors d ON a.doctor_id = d.id");
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching appointments: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <div class="appointments-manage-container">
        <h2>Manage Appointments</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Appointment Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?= htmlspecialchars($appointment['id']) ?></td>
                <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                <td><?= htmlspecialchars($appointment['status']) ?></td>
                <td>
                    <a href="update_status.php?id=<?= $appointment['id'] ?>&status=completed">Mark as Completed</a> |
                    <a href="update_status.php?id=<?= $appointment['id'] ?>&status=canceled">Cancel</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>