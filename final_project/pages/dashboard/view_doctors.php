<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/login.php'); // Redirect to login if not admin
    exit;
}

require_once '../../config/db.php';

// Fetch all doctors
try {
    $stmt = $pdo->query("SELECT * FROM doctors");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching doctors: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Doctors</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <div class="doctor-list-container">
        <h2>Doctors List</h2>
        <table border="1">
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Specialty</th>
                <th>Description</th>
                <th>Contact Info</th>
                <th>Location</th>
            </tr>
            <?php foreach ($doctors as $doctor): ?>
            <tr>
                <td><img src="<?= htmlspecialchars('../../' . $doctor['photo']) ?>"
                        alt="Photo of <?= htmlspecialchars($doctor['name']) ?>" width="100"></td>

                <td><?= htmlspecialchars($doctor['name']) ?></td>
                <td><?= htmlspecialchars($doctor['specialty']) ?></td>
                <td><?= htmlspecialchars($doctor['description']) ?></td>
                <td><?= htmlspecialchars($doctor['contact_info']) ?></td>
                <td><?= htmlspecialchars($doctor['location']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>