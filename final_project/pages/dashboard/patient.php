<?php
require_once '../../includes/auth.php';

// Only allow patients to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'patient') {
    header('Location: /web_project/pages/login.php');
    exit;
}

require_once '../../config/db.php';
$user_id = $_SESSION['user_id'];

// Cancel appointment logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_appointment'])) {
    $appointment_id = $_POST['appointment_id'];

    try {
        $stmt = $pdo->prepare("UPDATE appointments SET status = 'canceled' WHERE id = ? AND user_id = ?");
        $stmt->execute([$appointment_id, $user_id]);
        $message = "Appointment successfully canceled.";
    } catch (PDOException $e) {
        $error = "Error canceling appointment: " . $e->getMessage();
    }
}

// Fetch upcoming appointments
try {
    $stmt = $pdo->prepare("
        SELECT 
            a.id,
            d.name AS doctor_name,
            a.appointment_date,
            a.status
        FROM 
            appointments a
        JOIN 
            doctors d ON a.doctor_id = d.id
        WHERE 
            a.user_id = ? AND a.appointment_date >= NOW()
        ORDER BY 
            a.appointment_date ASC
    ");
    $stmt->execute([$user_id]);
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
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="/web_project/assets/css/style.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        max-width: 1000px;
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

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 5px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    table th,
    table td {
        padding: 0.8rem;
        text-align: left;
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

    .cancel-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .cancel-btn:hover {
        background-color: #c82333;
    }

    .empty-message {
        text-align: center;
        font-size: 1.1rem;
        color: #666;
        margin-top: 1rem;
    }
    </style>
</head>

<body>
    <?php include '../../includes/header.php'; ?>
    <div class="dashboard-container">
        <h2>Patient Dashboard</h2>

        <!-- Success or Error Messages -->
        <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Appointments Section -->
        <h3>Upcoming Appointments</h3>
        <?php if (count($appointments) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                    <td><?= date("d M Y, h:i A", strtotime($appointment['appointment_date'])) ?></td>
                    <td><?= ucfirst(htmlspecialchars($appointment['status'])) ?></td>
                    <td>
                        <?php if ($appointment['status'] === 'scheduled'): ?>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                            <button type="submit" name="cancel_appointment" class="cancel-btn">Cancel</button>
                        </form>
                        <?php else: ?>
                        <span>-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="empty-message">You have no upcoming appointments.</p>
        <?php endif; ?>
    </div>
</body>
<?php include '../../includes/footer.php'; ?>

</html>