<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/pages/login.php');
    exit;
}

require_once '../../config/db.php';

// Fetch all services
try {
    $stmt = $pdo->query("SELECT * FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching services: " . $e->getMessage());
}

// Add a new service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $service_name = trim($_POST['service_name']);
    $service_description = trim($_POST['service_description']);

    if ($service_name && $service_description) {
        try {
            $stmt = $pdo->prepare("INSERT INTO services (name, description) VALUES (?, ?)");
            $stmt->execute([$service_name, $service_description]);
            $success_message = "Service added successfully.";
        } catch (PDOException $e) {
            $error_message = "Error adding service: " . $e->getMessage();
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}

// Delete a service
if (isset($_GET['delete_service_id'])) {
    $service_id = $_GET['delete_service_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$service_id]);
        header('Location: services.php');
        exit;
    } catch (PDOException $e) {
        $error_message = "Error deleting service: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
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

    .services-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    .services-table th,
    .services-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .services-table th {
        background-color: #f1f1f1;
    }

    .services-table td button {
        background-color: #f44336;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .services-table td button:hover {
        background-color: #d32f2f;
    }

    .form-container {
        margin-bottom: 2rem;
    }

    .form-container input,
    .form-container textarea {
        width: 100%;
        padding: 0.8rem;
        margin: 0.5rem 0;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-container button {
        background-color: #007bff;
        color: white;
        padding: 0.8rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .form-container button:hover {
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
        <a href="/web_project/pages/dashboard/admin.php" class="back-button">Back to Dashboard</a>

        <h2>Manage Services</h2>

        <?php if (isset($success_message)): ?>
        <div class="message success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- Add Service Form -->
        <div class="form-container">
            <h3>Add New Service</h3>
            <form action="services.php" method="POST">
                <input type="text" name="service_name" placeholder="Service Name" required>
                <textarea name="service_description" placeholder="Service Description" rows="4" required></textarea>
                <button type="submit" name="add_service">Add Service</button>
            </form>
        </div>

        <!-- Services Table -->
        <table class="services-table">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                <tr>
                    <td><?= htmlspecialchars($service['name']) ?></td>
                    <td><?= htmlspecialchars($service['description']) ?></td>
                    <td>
                        <a href="edit_service.php?id=<?= $service['id'] ?>">
                            <button>Edit</button>
                        </a>
                        <a href="services.php?delete_service_id=<?= $service['id'] ?>"
                            onclick="return confirm('Are you sure you want to delete this service?')">
                            <button>Delete</button>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>