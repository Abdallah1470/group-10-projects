    <?php
    require_once '../includes/auth.php';

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (login($email, $password)) {
            header('Location: /web_project'); // Redirect to dashboard
            exit;
        } else {
            $error = "Invalid credentials!";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
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

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-container h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .login-container label {
            font-size: 1rem;
            color: #333;
            text-align: left;
            margin-bottom: 5px;
        }

        .login-container input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            color: #333;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .login-container input:focus {
            border-color: #007bff;
        }

        .login-container button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 1rem;
            margin-bottom: 15px;
        }

        .login-container a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        @media (max-width: 500px) {
            .login-container {
                padding: 30px;
            }
        }
        </style>
    </head>

    <body>

        <div class="login-container">
            <h2>Login</h2>

            <?php if (isset($error)) : ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit" name="login">Login</button>
            </form>

        </div>

    </body>

    </html>