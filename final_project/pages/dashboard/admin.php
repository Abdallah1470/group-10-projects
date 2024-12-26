<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/pages/login.php'); 
    exit;
}

require_once '../../config/db.php';

// Get key statistics
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM services");
    $num_services = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM doctors");
    $num_doctors = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM appointments");
    $num_appointments = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_requests WHERE status = 'pending'");
    $num_contact_requests = $stmt->fetchColumn();
} catch (PDOException $e) {
    die("Error fetching statistics: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <style>
    body {
        font-family: 'Arial', sans-serif;
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

    .statistics {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .stat {
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        flex-basis: 22%;
        transition: all 0.3s ease;
    }

    .stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .stat h3 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 1rem;
    }

    .stat p {
        font-size: 1.2rem;
        color: #555;
    }

    .stat .icon {
        font-size: 2rem;
        color: #007bff;
        margin-bottom: 1rem;
    }

    .stat a {
        display: inline-block;
        margin-top: 1rem;
        padding: 0.5rem 1.2rem;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 1rem;
    }

    .stat a:hover {
        background-color: #0056b3;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .statistics {
            flex-direction: column;
            align-items: center;
        }

        .stat {
            flex-basis: 80%;
            margin-bottom: 1rem;
        }
    }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>

        <div class="statistics">
            <div class="stat">
                <div class="icon">📋</div>
                <h3>Services</h3>
                <p><?= $num_services ?> Services</p>
                <a href="/web_project/pages/admin/services.php">Manage Services</a>
            </div>
            <div class="stat">
                <div class="icon">👨‍⚕️</div>
                <h3>Doctors</h3>
                <p><?= $num_doctors ?> Doctors</p>
                <a href="/web_project/pages/admin/doctors.php">Manage Doctors</a>
            </div>
            <div class="stat">
                <div class="icon">📅</div>
                <h3>Appointments</h3>
                <p><?= $num_appointments ?> Appointments</p>
                <a href="/web_project/pages/admin/appointments.php">Manage Appointments</a>
            </div>
            <div class="stat">
                <div class="icon">📨</div>
                <h3>Contact Requests</h3>
                <p><?= $num_contact_requests ?> Pending Requests</p>
                <a href="/web_project/pages/admin/contact_requests.php">Manage Requests</a>
            </div>
        </div>
    </div>
</body>

</html>