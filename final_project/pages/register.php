<?php
require_once '../includes/auth.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']); // Hash password for security
    $role = $_POST['role'];

    try {
        // Insert new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role]);

        header('Location: login.php'); // Redirect to login page after registration
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fa;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .register-container {
        background-color: white;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        text-align: center;
    }

    .register-container h2 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 20px;
    }

    .register-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .register-container label {
        font-size: 1rem;
        color: #333;
        text-align: left;
        margin-bottom: 5px;
    }

    .register-container input,
    .register-container select {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
        color: #333;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .register-container input:focus,
    .register-container select:focus {
        border-color: #007bff;
    }

    .register-container button {
        padding: 12px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .register-container button:hover {
        background-color: #0056b3;
    }

    .error-message {
        color: red;
        font-size: 1rem;
        margin-bottom: 15px;
    }

    .login-link {
        margin-top: 20px;
        font-size: 0.9rem;
    }

    .login-link a {
        color: #007bff;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 500px) {
        .register-container {
            padding: 30px;
        }
    }
    </style>
</head>

<body>

    <div class="register-container">
        <h2>Register</h2>

        <?php if (isset($error)) : ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="admin">Admin</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
            </select>

            <button type="submit" name="register">Register</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

</body>

</html>