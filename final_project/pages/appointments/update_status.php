<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/login.php');
    exit;
}

require_once '../../config/db.php';

// Get appointment ID and new status
$appointment_id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

if ($appointment_id && in_array($status, ['completed', 'canceled'])) {
    try {
        $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->execute([$status, $appointment_id]);
        header("Location: manage.php"); // Redirect back to manage appointments
        exit;
    } catch (PDOException $e) {
        die("Error updating appointment status: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}