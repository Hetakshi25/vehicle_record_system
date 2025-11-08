<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = intval($_SESSION['user_id']);
    $visit_id  = intval($_POST['visit_id'] ?? 0);
    $status    = $_POST['status'] ?? '';

    // Validate request
    if ($visit_id <= 0 || !in_array($status, ['approved', 'rejected'])) {
        $_SESSION['error'] = "Invalid request.";
        header("Location: manage_visits.php");
        exit;
    }

    // Update visit status (only for logged-in seller)
    $stmt = $conn->prepare("UPDATE visits SET status = ? WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("sii", $status, $visit_id, $seller_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = "Visit request has been updated.";
        } else {
            // No row changed: either wrong seller_id/visit_id, or status already set
            $_SESSION['info'] = "No changes made (maybe status already set).";
        }
    } else {
        $_SESSION['error'] = "Database error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: manage_visits.php");
    exit;

} else {
    header("Location: manage_visits.php");
    exit;
}
?>
