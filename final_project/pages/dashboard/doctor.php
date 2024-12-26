<?php
require_once '../../includes/auth.php';

// Only allow doctors to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'doctor') {
    header('Location: /web_project/pages/login.php');
    exit;
}

require_once '../../config/db.php';

// Get the logged-in doctor's ID
$doctor_id = $_SESSION['user_id'];

// Fetch doctor's info
try {
    $stmt = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
    $stmt->execute([$doctor_id]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching doctor info: " . $e->getMessage());
}

// Fetch appointments for the doctor
try {
    $stmt = $pdo->prepare("SELECT 
                                a.id, 
                                u.name AS patient_name, 
                                a.appointment_date, 
                                a.status 
                           FROM 
                                appointments a 
                           JOIN users u ON a.user_id = u.id 
                           WHERE 
                                a.doctor_id = ? 
                           ORDER BY a.appointment_date ASC");
    $stmt->execute([$doctor_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching appointments: " . $e->getMessage());
}

// Update appointment status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'], $_POST['appointment_id'], $_POST['status'])) {
        $appointment_id = $_POST['appointment_id'];
        $status = $_POST['status'];

        try {
            $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $stmt->execute([$status, $appointment_id]);
            header('Location: doctor.php');
            exit;
        } catch (PDOException $e) {
            die("Error updating appointment status: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="/web_project/assets/css/style.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h2,
    h3 {
        color: #333;
    }

    .doctor-info {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .doctor-info img {
        border-radius: 50%;
        border: 4px solid #007bff;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }

    .doctor-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .doctor-details p {
        margin: 0.5rem 0;
        font-size: 1.1rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    table th,
    table td {
        padding: 0.8rem;
        text-align: center;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #007bff;
        color: #ffffff;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    .status-select {
        padding: 0.5rem;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .btn-update {
        padding: 0.5rem 1rem;
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-update:hover {
        background-color: #218838;
    }
    </style>
</head>

<body>
    <?php include '../../includes/header.php'; ?>
    <div class="dashboard-container">
        <h2>Doctor Dashboard</h2>

        <!-- Doctor Info Section -->
        <div class="doctor-info">
            <img src="<?= htmlspecialchars('../../' . $doctor['photo']) ?>" alt="Doctor Photo">
            <div class="doctor-details">
                <p><strong>Name:</strong> <?= htmlspecialchars($doctor['name']) ?></p>
                <p><strong>Specialty:</strong> <?= htmlspecialchars($doctor['specialty']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($doctor['location']) ?></p>
            </div>
        </div>

        <!-- Appointments Section -->
        <h3>My Appointments</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($appointments) > 0): ?>
                <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['id']) ?></td>
                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                    <td><?= htmlspecialchars($appointment['status']) ?></td>
                    <td>
                        <form action="doctor.php" method="POST">
                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                            <select name="status" class="status-select">
                                <option value="scheduled"
                                    <?= $appointment['status'] === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                <option value="completed"
                                    <?= $appointment['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="canceled" <?= $appointment['status'] === 'canceled' ? 'selected' : '' ?>>
                                    Canceled</option>
                            </select>
                            <button type="submit" name="update_status" class="btn-update">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5">No appointments found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include '../../includes/footer.php'; ?>
</body>

</html>