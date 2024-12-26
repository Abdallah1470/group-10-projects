<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/login.php');
    exit;
}

require_once '../../config/db.php';

$service_id = $_GET['id'] ?? null;

if ($service_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$service_id]);
        header('Location: manage.php');
        exit;
    } catch (PDOException $e) {
        die("Error deleting service: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}