<?php
require_once '../../includes/auth.php';

// Check if the user is logged in
if (!isLoggedIn() || $_SESSION['role'] !== 'patient') {
    header('Location: /web_project/login.php'); // Redirect to login if not patient
    exit;
}

require_once '../../config/db.php';

// Get list of available doctors
$stmt = $pdo->query("SELECT * FROM doctors");
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

$confirmationMessage = ''; // Variable to store confirmation message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date, status) 
                               VALUES (?, ?, ?, 'scheduled')");
        $stmt->execute([$user_id, $doctor_id, $appointment_date]);

        // Success message
        $confirmationMessage = "Your appointment has been booked successfully!";
    } catch (PDOException $e) {
        // Error message
        $confirmationMessage = "Error booking appointment: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f2f4f8;
        margin: 0;
        padding: 0;
        color: #444;
    }

    .appointment-booking-container {
        background-color: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 50px auto;
        text-align: center;
        transition: transform 0.3s ease-in-out;
    }

    .appointment-booking-container:hover {
        transform: translateY(-5px);
    }

    .appointment-booking-container h2 {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .appointment-booking-container form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: flex-start;
    }

    .appointment-booking-container label {
        font-size: 1.1rem;
        font-weight: 500;
        color: #555;
        text-align: left;
        margin-bottom: 5px;
    }

    .appointment-booking-container select,
    .appointment-booking-container input {
        padding: 14px;
        border: 2px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        color: #555;
        background-color: #f9f9f9;
        outline: none;
        width: 100%;
        transition: border-color 0.3s ease;
    }

    .appointment-booking-container select:focus,
    .appointment-booking-container input:focus {
        border-color: #007bff;
        background-color: #fff;
    }

    .appointment-booking-container button {
        padding: 14px;
        border: none;
        border-radius: 8px;
        background-color: #007bff;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .appointment-booking-container button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    /* Success Message Style */
    .confirmation-message {
        background-color: #28a745;
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
        font-size: 1.2rem;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0, 128, 0, 0.2);
        display: inline-block;
        opacity: 0;
        animation: fadeIn 2s forwards;
    }

    .error-message {
        background-color: #dc3545;
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
        font-size: 1.2rem;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(255, 0, 0, 0.2);
        display: inline-block;
        opacity: 0;
        animation: fadeIn 2s forwards;
    }

    /* Fade-in animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Button Style for "Go to Home Screen" */
    .go-home-button {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        border-radius: 8px;
        display: inline-block;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    .go-home-button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    @media (max-width: 600px) {
        .appointment-booking-container {
            padding: 30px;
        }

        .appointment-booking-container h2 {
            font-size: 2rem;
        }
    }
    </style>
</head>

<body>
    <?php include '../../includes/header.php'; ?>
    <div class="appointment-booking-container">
        <h2>Book Appointment</h2>

        <form action="book.php" method="POST">
            <label for="doctor">Select Doctor:</label>
            <select name="doctor_id" id="doctor" required>
                <option value="" disabled selected>Select a doctor</option>
                <?php foreach ($doctors as $doctor): ?>
                <option value="<?= $doctor['id'] ?>"><?= htmlspecialchars($doctor['name']) ?> -
                    <?= htmlspecialchars($doctor['specialty']) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="appointment_date">Appointment Date:</label>
            <input type="datetime-local" name="appointment_date" id="appointment_date" required>

            <button type="submit">Book Appointment</button>
        </form>

        <!-- Displaying Confirmation Message -->
        <?php if (!empty($confirmationMessage)): ?>
        <div class="confirmation-message"><?= htmlspecialchars($confirmationMessage) ?></div>
        <a href="/web_project" class="go-home-button">Go to Home Screen</a> <!-- Button to redirect to home -->
        <?php endif; ?>
    </div>
    <?php include '../../includes/footer.php'; ?>
</body>

</html>