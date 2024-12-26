<?php
$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? null;

// Determine the dashboard URL based on the user's role
$dashboardUrl = '/web_project/pages/login.php';
if ($isLoggedIn) {
    switch ($role) {
        case 'admin':
            $dashboardUrl = '/web_project/pages/dashboard/admin.php';
            break;
        case 'doctor':
            $dashboardUrl = '/web_project/pages/dashboard/doctor.php';
            break;
        case 'patient':
            $dashboardUrl = '/web_project/pages/dashboard/patient.php';
            break;
    }
}
?>
<header>
    <nav class="navbar">
        <div class="nav-left">
            <a href="/web_project" class="logo">
                <img src="/web_project/assets/images/logo.png" alt="Hospital Logo" class="logo-image">
                <span class="logo-text">Health Bridge</span>
            </a>
            <div class="nav-links">
                <a href="/web_project">Home</a>
                <a href="/web_project/pages/about.php">About Us</a>
                <a href="/web_project/pages/services.php">Services</a>
                <a href="/web_project/pages/contact.php">Contact</a>
            </div>
        </div>
        <div class="nav-right">
            <a href="<?= $dashboardUrl ?>" class="btn btn-dashboard">Dashboard</a>
            <?php if ($isLoggedIn): ?>
            <a href="/web_project/pages/logout.php" class="btn btn-logout">Logout</a>
            <?php else: ?>
            <a href="/web_project/pages/login.php" class="btn btn-login">Login</a>
            <a href="/web_project/pages/register.php" class="btn btn-register">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<style>
/* General Navbar Styling */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 3rem;
    background-color: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: all 0.3s ease-in-out;
}

/* Left Side: Logo and Links */
.nav-left {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    text-decoration: none;
    color: #333;
    font-size: 1.6rem;
    font-weight: bold;
    transition: all 0.3s ease;
}

.logo:hover {
    color: #007bff;
}

.logo-image {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    transition: transform 0.3s ease;
}

.logo-text {
    font-size: 1.4rem;
    color: #007bff;
    letter-spacing: 1px;
}

/* Navigation Links */
.nav-links {
    display: flex;
    gap: 2rem;
}

.nav-links a {
    text-decoration: none;
    color: #555;
    font-weight: 500;
    font-size: 1.1rem;
    position: relative;
    transition: color 0.3s ease, transform 0.3s ease;
}

.nav-links a::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    background-color: #007bff;
    bottom: -4px;
    left: 0;
    transition: width 0.3s ease;
}

.nav-links a:hover {
    color: #007bff;
    transform: translateY(-2px);
}

.nav-links a:hover::after {
    width: 100%;
}

/* Right Side: Buttons */
.nav-right {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.btn {
    padding: 0.7rem 1.5rem;
    border-radius: 30px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-dashboard {
    background-color: #17a2b8;
    color: white;
    border: 2px solid #17a2b8;
}

.btn-login {
    background-color: #007bff;
    color: white;
    border: 2px solid #007bff;
}

.btn-register {
    background-color: #28a745;
    color: white;
    border: 2px solid #28a745;
}

.btn-logout {
    background-color: #dc3545;
    color: white;
    border: 2px solid #dc3545;
}

.btn:hover {
    background-color: white;
    color: currentColor;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Hover Effects for Logo Image */
.logo-image:hover {
    transform: scale(1.1);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .navbar {
        padding: 1rem 2rem;
    }

    .logo-text {
        font-size: 1.3rem;
    }

    .nav-left {
        gap: 1.5rem;
    }

    .nav-right {
        gap: 1rem;
    }

    .navbar.open .nav-links {
        display: flex;
        position: absolute;
        top: 70px;
        left: 0;
        right: 0;
        background-color: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        flex-direction: column;
        padding: 1rem;
    }

    .nav-links a {
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }

    .menu-icon {
        display: block;
        cursor: pointer;
    }
}

@media (max-width: 768px) {
    .nav-left {
        flex-direction: column;
        align-items: flex-start;
    }

    .nav-right {
        flex-direction: column;
        gap: 0.8rem;
    }
}

header {
    margin: 0 !important;
    /* Remove any margin around the header */
    padding: 0 !important;
    /* Reset padding if needed */
}
</style>