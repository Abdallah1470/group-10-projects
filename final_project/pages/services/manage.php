<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/login.php');
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <div class="service-manage-container">
        <h2>Manage Services</h2>
        <a href="add.php">Add New Service</a>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($services as $service): ?>
            <tr>
                <td><?= htmlspecialchars($service['id']) ?></td>
                <td><?= htmlspecialchars($service['name']) ?></td>
                <td><?= htmlspecialchars($service['description']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $service['id'] ?>">Edit</a> |
                    <a href="delete.php?id=<?= $service['id'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>