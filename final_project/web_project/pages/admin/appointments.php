<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/pages/login.php');
    exit;
}

require_once '../../config/db.php';

// Fetch all appointments with doctor and patient names
try {
    $stmt = $pdo->prepare("
        SELECT a.id, a.appointment_date, a.status, d.name AS doctor_name, u.name AS patient_name 
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.id
        JOIN users u ON a.user_id = u.id
        ORDER BY a.appointment_date ASC
    ");
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching appointments: " . $e->getMessage());
}

// Update appointment status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    if ($appointment_id && $status) {
        try {
            $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $stmt->execute([$status, $appointment_id]);
            header('Location: appointments.php');
            exit;
        } catch (PDOException $e) {
            $error_message = "Error updating appointment status: " . $e->getMessage();
        }
    } else {
        $error_message = "Please select a status.";
    }
}

// Filter by appointment status
if (isset($_GET['status'])) {
    $status_filter = $_GET['status'];
    try {
        $stmt = $pdo->prepare("
            SELECT a.id, a.appointment_date, a.status, d.name AS doctor_name, u.name AS patient_name 
            FROM appointments a
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u ON a.user_id = u.id
            WHERE a.status = ?
            ORDER BY a.appointment_date ASC
        ");
        $stmt->execute([$status_filter]);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching filtered appointments: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f7fc;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 2rem;
    }

    .appointments-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    .appointments-table th,
    .appointments-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .appointments-table th {
        background-color: #f1f1f1;
    }

    .appointments-table td button {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .appointments-table td button:hover {
        background-color: #0056b3;
    }

    .filter-container {
        margin-bottom: 2rem;
        text-align: center;
    }

    .filter-container select,
    .filter-container button {
        padding: 0.8rem;
        margin: 0.5rem;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .filter-container button {
        background-color: #007bff;
        color: white;
        cursor: pointer;
    }

    .filter-container button:hover {
        background-color: #0056b3;
    }

    .message {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
        font-size: 1rem;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .back-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .back-button:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Back Button -->
        <a href="/web_project/pages/dashboard/admin.php" class="back-button">Back to Dashboard</a>

        <h2>Manage Appointments</h2>

        <?php if (isset($error_message)): ?>
        <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- Filter Appointments -->
        <div class="filter-container">
            <form action="appointments.php" method="GET">
                <select name="status" onchange="this.form.submit()">
                    <option value="">Select Status</option>
                    <option value="scheduled"
                        <?= isset($status_filter) && $status_filter === 'scheduled' ? 'selected' : '' ?>>
                        Scheduled
                    </option>
                    <option value="completed"
                        <?= isset($status_filter) && $status_filter === 'completed' ? 'selected' : '' ?>>
                        Completed
                    </option>
                    <option value="canceled"
                        <?= isset($status_filter) && $status_filter === 'canceled' ? 'selected' : '' ?>>
                        Canceled
                    </option>
                </select>
            </form>
        </div>

        <!-- Appointments Table -->
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Patient</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($appointment['status']) ?></td>
                    <td>
                        <form action="appointments.php" method="POST">
                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                            <select name="status" required>
                                <option value="scheduled"
                                    <?= $appointment['status'] === 'scheduled' ? 'selected' : '' ?>>
                                    Scheduled
                                </option>
                                <option value="completed"
                                    <?= $appointment['status'] === 'completed' ? 'selected' : '' ?>>
                                    Completed
                                </option>
                                <option value="canceled" <?= $appointment['status'] === 'canceled' ? 'selected' : '' ?>>
                                    Canceled
                                </option>
                            </select>
                            <button type="submit" name="update_status">Update Status</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>