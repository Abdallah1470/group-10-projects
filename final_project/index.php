<?php
require_once 'config/db.php';
require_once 'includes/auth.php';

$isLoggedIn = isLoggedIn();
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Bridge Hospital</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    .background-section {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('/web_project/assets/images/hospital-bg.png');
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        min-height: 60vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
        transition: all 0.3s ease;
    }

    .background-section h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .background-section p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    /* media query for responsiveness */
    @media (max-width: 768px) {
        .background-section {
            min-height: 60vh;
            padding: 60px 15px;
        }

        .background-section h1 {
            font-size: 2rem;
        }

        .background-section p {
            font-size: 1rem;
        }
    }

    .buttons {
        margin: 20px 0;
    }

    .buttons a {
        display: inline-block;
        padding: 10px 20px;
        margin: 0 10px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
    }

    .primary-btn {
        background-color: #2980b9;
        color: white;
    }

    .secondary-btn {
        background-color: transparent;
        border: 2px solid white;
        color: white;
    }

    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        padding: 40px 20px;
    }

    .feature {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .feature i {
        color: #2980b9;
        margin-bottom: 20px;
    }

    .feature {
        transition: transform 0.3s ease;
    }

    .feature:hover {
        transform: translateY(-5px);
    }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="background-section">
        <h1>Welcome to Health Bridge Hospital</h1>
        <p>Your health and well-being are our priority</p>

        <div class="buttons">
            <?php if (!$isLoggedIn): ?>
            <a href="pages/login.php" class="primary-btn">Login</a>
            <a href="pages/register.php" class="secondary-btn">Register</a>
            <?php else: ?>
            <?php if ($userRole === 'patient'): ?>
            <a href="pages/appointments/book.php" class="primary-btn">Book Appointment</a>
            <a href="pages/dashboard/patient.php" class="secondary-btn">My Dashboard</a>
            <?php elseif ($userRole === 'doctor'): ?>
            <a href="pages/dashboard/doctor.php" class="primary-btn">Doctor Dashboard</a>
            <?php elseif ($userRole === 'admin'): ?>
            <a href="pages/dashboard/admin.php" class="primary-btn">Admin Dashboard</a>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="features">
        <div class="feature">
            <i class="fas fa-hospital-user fa-3x"></i>
            <h3>Our Services</h3>
            <p>Explore our comprehensive range of medical services designed for your well-being.</p>
            <a href="pages/services.php" class="primary-btn">View Services</a>
        </div>

        <div class="feature">
            <i class="fas fa-user-md fa-3x"></i>
            <h3>Expert Doctors</h3>
            <p>Meet our team of experienced healthcare professionals.</p>
            <a href="pages/about.php" class="primary-btn">Meet Our Doctors</a>
        </div>

        <div class="feature">
            <i class="fas fa-calendar-check fa-3x"></i>
            <h3>Easy Appointments</h3>
            <p>Schedule your visit with just a few clicks.</p>
            <?php if ($isLoggedIn && $userRole === 'patient'): ?>
            <a href="pages/appointments/book.php" class="primary-btn">Book Now</a>
            <?php else: ?>
            <a href="pages/login.php" class="primary-btn">Login to Book</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!$isLoggedIn): ?>
    <div class="is-registered" style="text-align: center; padding: 40px 20px; background: #f8f9fa;">
        <h2>New to our Hospital?</h2>
        <p>Register now to access our services and manage your appointments online.</p>
        <a href="pages/register.php" class="primary-btn">Register Now</a>
    </div>
    <?php endif; ?>

    <?php include 'includes/footer.php'; ?>
</body>

</html>