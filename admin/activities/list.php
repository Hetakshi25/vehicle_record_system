<?php
session_start();
require_once '../../includes/db.php';

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Fetch activities with user info
$query = "SELECT a.id, a.title, a.activity_date, a.activity_time, a.type, u.name AS user_name
          FROM activities a
          JOIN users u ON a.user_id = u.id
          ORDER BY a.activity_date DESC, a.activity_time DESC";

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
<title>Admin - Activities List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
.sidebar { height: 100vh; background-color: #343a40; min-width: 220px; }
.sidebar a { color: #fff; display: block; padding: 15px; text-decoration: none; font-size: 16px; }
.sidebar a:hover { background-color: #495057; text-decoration: none; }
.navbar-brand { font-weight: bold; }
.table th, .table td { vertical-align: middle !important; }
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
        <a href="list.php"><i class="fas fa-calendar-alt me-2"></i>Activities</a>
        <a href="../notifications/list.php"><i class="fas fa-bell me-2"></i>Notifications</a>
        <a href="../reports.php"><i class="fas fa-chart-line me-2"></i>Reports</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <nav class="navbar navbar-light bg-light mb-4 rounded">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h3">Activities List</span>
            </div>
        </nav>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td>
                                <?php if($row['type']=='visit'): ?>
                                    <span class="badge bg-info text-dark"><?= ucfirst($row['type']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= ucfirst($row['type']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($row['activity_date'])) ?></td>
                            <td><?= date('h:i A', strtotime($row['activity_time'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No activities found</td>
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
