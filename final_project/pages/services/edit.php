<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/login.php');
    exit;
}

require_once '../../config/db.php';

$service_id = $_GET['id'] ?? null;

if (!$service_id) {
    die("Invalid request.");
}

// Fetch service details
try {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$service) {
        die("Service not found.");
    }
} catch (PDOException $e) {
    die("Error fetching service: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($name) && !empty($description)) {
        try {
            $stmt = $pdo->prepare("UPDATE services SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $description, $service_id]);
            header('Location: manage.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error updating service: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <div class="service-edit-container">
        <h2>Edit Service</h2>
        <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="edit.php?id=<?= $service_id ?>" method="POST">
            <label for="name">Service Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($service['name']) ?>" required>

            <label for="description">Description</label>
            <textarea id="description" name="description"
                required><?= htmlspecialchars($service['description']) ?></textarea>

            <button type="submit">Update Service</button>
        </form>
    </div>
</body>

</html>