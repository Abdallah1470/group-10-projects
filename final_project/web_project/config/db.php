<?php
// Database configuration
$host = 'localhost'; // XAMPP default host
$dbname = 'hospital_db'; // Database name
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password (empty)

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>