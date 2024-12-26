<?php
require_once '../../includes/auth.php';

// Only allow admins to access this page
if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    header('Location: /web_project/pages/login.php');
    exit;
}

require_once '../../config/db.php';

// Fetch all contact requests
try {
    $stmt = $pdo->prepare("
        SELECT cr.id, cr.name, cr.email, cr.message, cr.status, cr.created_at
        FROM contact_requests cr
        ORDER BY cr.created_at DESC
    ");
    $stmt->execute();
    $contact_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching contact requests: " . $e->getMessage());
}

// Update contact request status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    if ($request_id && $status) {
        try {
            $stmt = $pdo->prepare("UPDATE contact_requests SET status = ? WHERE id = ?");
            $stmt->execute([$status, $request_id]);
            header('Location: contact_requests.php');
            exit;
        } catch (PDOException $e) {
            $error_message = "Error updating contact request status: " . $e->getMessage();
        }
    } else {
        $error_message = "Please select a status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Requests</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <style>
    .dashboard-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 2rem;
    }

    .contact-requests-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    .contact-requests-table th,
    .contact-requests-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .contact-requests-table th {
        background-color: #f1f1f1;
    }

    .contact-requests-table td button {
        background-color: #007bff;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .contact-requests-table td button:hover {
        background-color: #0056b3;
    }

    .filter-container {
        margin-bottom: 2rem;
        text-align: center;
    }

    .filter-container select,
    .filter-container button {
        padding: 0.8rem;
        margin: 0.5rem;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .filter-container button {
        background-color: #007bff;
        color: white;
        cursor: pointer;
    }

    .filter-container button:hover {
        background-color: #0056b3;
    }

    .message {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
    }

    .message:hover {
        background-color: #f0f0f0;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .back-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .back-button:hover {
        background-color: #0056b3;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        overflow: auto;
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Back Button -->
        <a href="/web_project/pages/dashboard/admin.php" class="back-button">Back to Dashboard</a>

        <h2>Contact Requests</h2>

        <?php if (isset($error_message)): ?>
        <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- Contact Requests Table -->
        <table class="contact-requests-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contact_requests as $request): ?>
                <tr>
                    <td><?= htmlspecialchars($request['name']) ?></td>
                    <td><?= htmlspecialchars($request['email']) ?></td>
                    <td>
                        <span class="message"
                            onclick="openModal('message-<?= $request['id'] ?>')"><?= htmlspecialchars(substr($request['message'], 0, 50)) ?>...</span>
                        <!-- Hidden full message in modal -->
                        <div id="message-<?= $request['id'] ?>" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal('message-<?= $request['id'] ?>')">&times;</span>
                                <h4>Full Message</h4>
                                <p><?= htmlspecialchars($request['message']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($request['status']) ?></td>
                    <td>
                        <form action="contact_requests.php" method="POST">
                            <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                            <select name="status" required>
                                <option value="pending" <?= $request['status'] === 'pending' ? 'selected' : '' ?>>
                                    Pending</option>
                                <option value="resolved" <?= $request['status'] === 'resolved' ? 'selected' : '' ?>>
                                    Resolved</option>
                            </select>
                            <button type="submit" name="update_status">Update Status</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($request['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    // Open modal
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    // Close modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    // Close modal when clicked outside of modal content
    window.onclick = function(event) {
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    }
    </script>
</body>

</html>