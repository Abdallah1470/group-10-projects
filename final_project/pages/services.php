<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

// Check if the user is logged in and determine their role
$isAdmin = isLoggedIn() && ($_SESSION['role'] === 'admin');
$error = "";

// Handle form submission (only for admins)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($name) && !empty($description)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO services (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
            header('Location: services.php'); // Redirect to refresh services
            exit;
        } catch (PDOException $e) {
            $error = "Error adding service: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required.";
    }
}

// Fetch all services from the database
try {
    $stmt = $pdo->query("SELECT * FROM services");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching services: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin: 50px auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #007bff;
        margin-bottom: 20px;
        font-size: 2rem;
    }

    .error-message {
        color: #dc3545;
        margin-bottom: 15px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    label {
        font-weight: 600;
        margin-bottom: 5px;
    }

    input,
    textarea,
    button {
        padding: 12px;
        border: 2px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        outline: none;
        transition: all 0.3s ease;
    }

    input:focus,
    textarea:focus {
        border-color: #007bff;
    }

    button {
        background-color: #007bff;
        color: white;
        cursor: pointer;
        font-weight: bold;
    }

    button:hover {
        background-color: #0056b3;
    }

    .services-list {
        margin-top: 30px;
    }

    .service-card {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .service-card h3 {
        color: #333;
        margin-bottom: 10px;
    }

    .service-card p {
        color: #666;
    }

    @media (max-width: 600px) {
        .container {
            padding: 20px;
        }

        h2 {
            font-size: 1.5rem;
        }
    }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <h2>Our Services</h2>

        <!-- Admin Section: Add New Service -->
        <?php if ($isAdmin): ?>
        <h3>Add a New Service</h3>
        <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="services.php" method="POST">
            <label for="name">Service Name</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <button type="submit">Add Service</button>
        </form>
        <?php endif; ?>

        <!-- Display List of Services -->
        <div class="services-list">
            <h3>Provided Services</h3>
            <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
            <div class="service-card">
                <h3><?= htmlspecialchars($service['name']) ?></h3>
                <p><?= htmlspecialchars($service['description']) ?></p>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>No services available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>