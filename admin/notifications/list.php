<?php
session_start();
require_once '../../includes/db.php';

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Handle marking notifications as read
if (isset($_GET['mark_read'])) {
    $nid = intval($_GET['mark_read']);
    $conn->query("UPDATE notifications SET is_read=1 WHERE id=$nid");
    header("Location: list.php");
    exit;
}

// Fetch all notifications with user info if applicable
$query = "SELECT n.id, n.message, n.is_read, n.created_at, u.name AS user_name
          FROM notifications n
          LEFT JOIN users u ON n.user_id = u.id
          ORDER BY n.created_at DESC";

$result = $conn->query($query);
if(!$result){
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Notifications</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
.sidebar { height: 100vh; background-color: #343a40; min-width: 220px; }
.sidebar a { color: #fff; display: block; padding: 15px; text-decoration: none; font-size: 16px; }
.sidebar a:hover { background-color: #495057; text-decoration: none; }
.navbar-brand { font-weight: bold; }
.badge-status { font-size: 0.9rem; }
</style>
</head>
<body>
<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h3 class="text-center text-white mb-4">Admin Panel</h3>
        <a href="../index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="../users/list.php"><i class="fas fa-users me-2"></i>Users</a>
        <a href="../cars/list.php"><i class="fas fa-car me-2"></i>Cars</a>
        <a href="../activities/list.php"><i class="fas fa-calendar-alt me-2"></i>Activities</a>
        <a href="list.php"><i class="fas fa-bell me-2"></i>Notifications</a>
        <a href="../reports.php"><i class="fas fa-chart-line me-2"></i>Reports</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <nav class="navbar navbar-light bg-light mb-4 rounded">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h3">Notifications</span>
            </div>
        </nav>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['user_name'] ?? 'System') ?></td>
                            <td><?= htmlspecialchars($row['message']) ?></td>
                            <td>
                                <?php
                                $status = $row['is_read'] ? 'Read' : 'Unread';
                                $badge = $row['is_read'] ? 'bg-success' : 'bg-warning text-dark';
                                ?>
                                <span class="badge <?= $badge ?> badge-status"><?= $status ?></span>
                            </td>
                            <td><?= date('d M Y h:i A', strtotime($row['created_at'])) ?></td>
                            <td>
                                <?php if(!$row['is_read']): ?>
                                    <a href="list.php?mark_read=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-check"></i> Mark as Read
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No action</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No notifications found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
