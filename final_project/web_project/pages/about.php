<?php
require_once '../config/db.php';
require_once '../includes/auth.php';

// Fetch doctors for the team section
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
    <title>About Us - Hospital Management System</title>
    <link rel="stylesheet" href="/web_project/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        line-height: 1.6;
        background-color: #f9f9f9;
        color: #333;
    }

    a {
        text-decoration: none;
        color: #2980b9;
    }

    .breadcrumb {
        background: #eef2f3;
        padding: 15px;
        text-align: center;
    }

    .about-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/web_project/assets/images/hospital-bg.png') center/cover no-repeat;
        color: white;
        text-align: center;
        padding: 100px 20px;
    }

    .about-hero h1 {
        font-size: 3rem;
        margin-bottom: 10px;
    }

    .section {
        padding: 50px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .section h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 2.5rem;
    }

    .section p {
        text-align: center;
        font-size: 1.2rem;
        margin-bottom: 40px;
    }

    .doctors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .doctor-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .doctor-card:hover {
        transform: translateY(-10px);
    }

    .doctor-card img {
        margin-left: 25%;
        width: 50%;
        height: 250px;
        object-fit: cover;
    }

    .doctor-info {
        padding: 20px;
        text-align: center;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        text-align: center;
    }

    .stat-item {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-10px);
    }

    .stat-item i {
        font-size: 2.5rem;
        color: #3498db;
        margin-bottom: 10px;
    }

    .facilities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .facility-item {
        background: white;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .facility-item:hover {
        transform: translateY(-10px);
    }

    .facility-item i {
        font-size: 2.5rem;
        color: #3498db;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 2rem;
        }

        .section h2 {
            font-size: 2rem;
        }
    }
    </style>
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="about-hero" data-aos="fade-up">
        <h1>About Our Hospital</h1>
        <p>Providing Quality Healthcare Since 1990</p>
    </div>

    <div class="section" data-aos="fade-up">
        <h2>Our Mission</h2>
        <p>To provide exceptional healthcare services and improve the well-being of our community through compassionate
            care, innovation, and excellence.</p>
    </div>

    <div class="section team-section" data-aos="fade-up">
        <h2>Meet Our Medical Team</h2>
        <div class="doctors-grid">
            <?php foreach ($doctors as $doctor): ?>
            <div class="doctor-card">
                <img src="<?= htmlspecialchars('/web_project/' . $doctor['photo']) ?>"
                    alt="<?= htmlspecialchars($doctor['name']) ?>">
                <div class="doctor-info">
                    <h3><?= htmlspecialchars($doctor['name']) ?></h3>
                    <p><strong><?= htmlspecialchars($doctor['specialty']) ?></strong></p>
                    <p><?= htmlspecialchars($doctor['description']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section stats-section" data-aos="fade-up">
        <h2>Our Achievements</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <i class="fas fa-user-md"></i>
                <h3>50+</h3>
                <p>Expert Doctors</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-procedures"></i>
                <h3>100+</h3>
                <p>Hospital Beds</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-smile"></i>
                <h3>10,000+</h3>
                <p>Happy Patients</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-award"></i>
                <h3>30+</h3>
                <p>Years Experience</p>
            </div>
        </div>
    </div>

    <div class="section facilities-section" data-aos="fade-up">
        <h2>Our Facilities</h2>
        <div class="facilities-grid">
            <div class="facility-item">
                <i class="fas fa-heartbeat"></i>
                <h3>Emergency Care</h3>
                <p>24/7 emergency medical services</p>
            </div>
            <div class="facility-item">
                <i class="fas fa-procedures"></i>
                <h3>Operation Theaters</h3>
                <p>State-of-the-art surgical facilities</p>
            </div>
            <div class="facility-item">
                <i class="fas fa-vial"></i>
                <h3>Laboratory</h3>
                <p>Advanced diagnostic services</p>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
    AOS.init({
        duration: 1000,
        once: true
    });
    </script>
</body>

</html>