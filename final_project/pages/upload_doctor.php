<?php
require_once './config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $description = $_POST['description'];
    $contact_info = $_POST['contact_info'];
    $location = $_POST['location'];

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo'];
        $upload_dir = 'uploads/'; // Directory to save uploaded photos
        $upload_file = $upload_dir . basename($photo['name']);

        // Ensure the uploads directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Move uploaded file to the target directory
        if (move_uploaded_file($photo['tmp_name'], $upload_file)) {
            try {
                // Insert doctor data into the database
                $stmt = $pdo->prepare("INSERT INTO doctors (name, specialty, description, contact_info, location, photo) 
                                       VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $specialty, $description, $contact_info, $location, $upload_file]);

                echo "Doctor added successfully with photo!";
            } catch (PDOException $e) {
                echo "Database error: " . $e->getMessage();
            }
        } else {
            echo "Failed to upload photo.";
        }
    } else {
        echo "No photo uploaded or upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Doctor</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <div class="doctor-upload-container">
        <h2>Add New Doctor</h2>
        <form action="upload_doctor.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="specialty">Specialty:</label>
            <input type="text" id="specialty" name="specialty" required><br><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br><br>

            <label for="contact_info">Contact Info:</label>
            <input type="text" id="contact_info" name="contact_info" required><br><br>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required><br><br>

            <label for="photo">Upload Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required><br><br>

            <button type="submit">Add Doctor</button>
        </form>
    </div>
</body>

</html>