<?php
require_once '../config/db.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_requests (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            $success = "Your message has been sent successfully.";
        } catch (PDOException $e) {
            $error = "Error sending message: " . $e->getMessage();
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
    <title>Contact Us</title>
    <link rel="stylesheet" href="/web_project/assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background: #f5f5f5;
    }

    .contact-container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 30px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .contact-container h2 {
        text-align: center;
        color: #2980b9;
        margin-bottom: 20px;
    }

    .contact-container p {
        text-align: center;
        font-size: 0.9rem;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    form label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    form input,
    form textarea {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }

    form input:focus,
    form textarea:focus {
        outline: none;
        border-color: #2980b9;
        box-shadow: 0 0 5px rgba(41, 128, 185, 0.3);
    }

    form textarea {
        resize: none;
    }

    form button {
        background: #2980b9;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background: #1b6fa4;
    }

    .error {
        color: red;
        font-size: 0.9rem;
        margin-bottom: 10px;
        text-align: center;
    }

    .success {
        color: green;
        font-size: 0.9rem;
        margin-bottom: 10px;
        text-align: center;
    }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <div class="contact-container">
        <h2>Contact Us</h2>
        <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php elseif (!empty($success)): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <form action="contact.php" method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Your Name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Your Email" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required></textarea>

            <button type="submit"><i class="fas fa-paper-plane"></i> Send Message</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>

</html>