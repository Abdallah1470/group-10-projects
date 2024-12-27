<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/pages/login.php');
    exit;
}

require_once '../../config/db.php';

// Fetch all doctors
try {
    $stmt = $pdo->prepare("SELECT * FROM doctors");
    $stmt->execute();
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching doctors: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['convert_to_doctor'])) {
    $user_id = $_POST['user_id'];

    try {
        // Fetch user's name
        $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $name = $stmt->fetchColumn();

        if ($name) {
            // Update the user's role to 'doctor'
            $stmt = $pdo->prepare("UPDATE users SET role = 'doctor' WHERE id = ?");
            $stmt->execute([$user_id]);

            $success_message = "User has been successfully converted to a doctor.";
        } else {
            $error_message = "User not found.";
        }
    } catch (PDOException $e) {
        $error_message = "Error converting user to doctor: " . $e->getMessage();
    }
}

// Convert user to doctor
// Convert user to doctor
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_doctor'])) {
    $user_id = $_POST['user_id'];
    $specialty = $_POST['specialty'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $contact_info = $_POST['contact_info'];
    $photo = $_FILES['photo'];

    try {
        // Fetch user's name
        $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $name = $stmt->fetchColumn();

        // Set a default photo path
        $photo_path = '/uploads/doctor_default.png';  // Default photo if no photo is uploaded

        // If a photo is uploaded, handle the upload process
        if ($photo['error'] === 0) {
            // Check if the uploaded file is an image
            $target_dir = "/uploads/";
            $target_file = $target_dir . basename($photo['name']);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validate that the file is an image
            $check = getimagesize($photo["tmp_name"]);
            if ($check !== false) {
                // Move the uploaded file to the correct directory
                if (move_uploaded_file($photo["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file)) {
                    $photo_path = $target_file;  // Update photo path if the upload is successful
                } else {
                    $error_message = "Error uploading the photo.";
                }
            } else {
                $error_message = "The file is not an image.";
            }
        }

        // Insert the doctor data into the database (name, specialty, description, etc.)
        $stmt = $pdo->prepare("INSERT INTO doctors (name, specialty, description, contact_info, location, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $specialty, $description, $contact_info, $location, $photo_path]);

        // Update the user's role to 'doctor'
        $stmt = $pdo->prepare("UPDATE users SET role = 'doctor' WHERE id = ?");
        $stmt->execute([$user_id]);

        $success_message = "User has been successfully converted to a doctor.";
    } catch (PDOException $e) {
        $error_message = "Error converting user to doctor: " . $e->getMessage();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors Management</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <style>
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

    .doctors-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    .doctors-table th,
    .doctors-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .doctors-table th {
        background-color: #f1f1f1;
    }

    .doctors-table td button {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .doctors-table td button:hover {
        background-color: #0056b3;
    }

    .message {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
    }

    .message:hover {
        background-color: #f0f0f0;
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

    .default-photo {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        object-fit: cover;
    }

    .doctor-form-container {
        display: none;
        margin-top: 2rem;
        padding: 2rem;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .doctor-form-container input,
    .doctor-form-container textarea,
    .doctor-form-container select {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .doctor-form-container button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .doctor-form-container button:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <a href="/web_project/pages/dashboard/admin.php" class="back-button">Back to Dashboard</a>

        <h2>Doctors Management</h2>

        <?php if (isset($success_message)): ?>
        <div class="message success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <h3>Convert User to Doctor</h3>
        <form action="doctors.php" method="POST">
            <select name="user_id" id="user_id" required>
                <option value="">Select a User</option>
                <?php
                // Fetch users with role 'patient'
                $stmt = $pdo->prepare("SELECT id, name FROM users WHERE role = 'patient'");
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($users as $user):
                ?>
                <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="convert_to_doctor">Convert to Doctor</button>
        </form>

        <div id="doctor-form-container" class="doctor-form-container">
            <h3>Doctor Details</h3>
            <form action="doctors.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" id="user_id"
                    value="<?= isset($_POST['user_id']) ? $_POST['user_id'] : '' ?>">

                <label for="specialty">Specialty</label>
                <input type="text" name="specialty" required>

                <label for="description">Description</label>
                <textarea name="description" rows="4" required></textarea>

                <label for="location">Location</label>
                <input type="text" name="location" required>

                <label for="contact_info">Contact Info</label>
                <input type="text" name="contact_info" required>

                <label for="photo">Upload Photo</label>
                <input type="file" name="photo" accept="image/*">

                <button type="submit" name="add_doctor">Add Doctor</button>
            </form>
        </div>

        <h3>Doctors List</h3>
        <table class="doctors-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Specialty</th>
                    <th>Location</th>
                    <th>Contact Info</th>
                    <th>Photo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doctors as $doctor): ?>
                <tr>
                    <td><?= htmlspecialchars($doctor['name']) ?></td>
                    <td><?= htmlspecialchars($doctor['specialty']) ?></td>
                    <td><?= htmlspecialchars($doctor['location']) ?></td>
                    <td><?= htmlspecialchars($doctor['contact_info']) ?></td>
                    <td>
                        <?php if ($doctor['photo']): ?>
                        <img src="<?= htmlspecialchars('../../' . $doctor['photo']) ?>" alt="Doctor Photo"
                            class="default-photo">
                        <?php else: ?>
                        <img src="../../assets/images/doctor_default.png" alt="Doctor Photo" class="default-photo">
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="doctors.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="doctor_id" value="<?= $doctor['id'] ?>">
                            <input type="file" name="photo" accept="image/*" required>
                            <button type="submit" name="upload_photo">Upload Photo</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    document.getElementById('user_id').addEventListener('change', function() {
        if (this.value !== "") {
            document.getElementById('doctor-form-container').style.display = 'block';
        } else {
            document.getElementById('doctor-form-container').style.display = 'none';
        }
    });
    </script>
</body>

</html>