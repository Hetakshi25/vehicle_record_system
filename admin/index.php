<?php
session_start();
require_once '../includes/db.php';

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch counts for dashboard
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_cars = $conn->query("SELECT COUNT(*) as count FROM vehicles")->fetch_assoc()['count'];
$new_cars = $conn->query("SELECT COUNT(*) as count FROM vehicles WHERE vehicle_condition='New'")->fetch_assoc()['count'];
$used_cars = $conn->query("SELECT COUNT(*) as count FROM vehicles WHERE vehicle_condition='Used'")->fetch_assoc()['count'];
$total_activities = $conn->query("SELECT COUNT(*) as count FROM activities")->fetch_assoc()['count'];
$total_notifications = $conn->query("SELECT COUNT(*) as count FROM notifications")->fetch_assoc()['count'];

// Fetch recent activities (latest 5)
$recent_activities = $conn->query("
    SELECT a.id, u.name AS user_name, a.title, a.activity_date, a.activity_time, a.type
    FROM activities a
    JOIN users u ON a.user_id = u.id
    ORDER BY a.activity_date DESC, a.activity_time DESC
    LIMIT 5
");

if (!$recent_activities) {
    die("Query Error: " . $conn->error);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f6f9;
}
.sidebar {
    min-height: 100vh;
    background-color: #343a40;
}
.sidebar a {
    color: #fff;
    display: block;
    padding: 15px;
    text-decoration: none;
}
.sidebar a:hover {
    background-color: #495057;
    text-decoration: none;
}
.navbar-brand {
    font-weight: bold;
}
.card {
    border-radius: 10px;
}
.card .card-body i {
    font-size: 2.5rem;
}
.table th, .table td {
    vertical-align: middle;
}
</style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3 flex-shrink-0">
        <h3 class="text-center text-white mb-4">Admin Panel</h3>
        <a href="index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="users/list.php"><i class="fas fa-users me-2"></i>Users</a>
        <a href="cars/list.php"><i class="fas fa-car me-2"></i>Cars</a>
        <a href="activities/list.php"><i class="fas fa-calendar-alt me-2"></i>Activities</a>
        <a href="notifications/list.php"><i class="fas fa-bell me-2"></i>Notifications</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <nav class="navbar navbar-light bg-white mb-4 rounded shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h3">Welcome, <?= $_SESSION['admin_username'] ?></span>
            </div>
        </nav>

        <!-- Cards Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Total Users</h6>
                            <h3><?= $total_users ?></h3>
                        </div>
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Total Cars</h6>
                            <h3><?= $total_cars ?></h3>
                            <small>New: <?= $new_cars ?> | Used: <?= $used_cars ?></small>
                        </div>
                        <i class="fas fa-car"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Activities</h6>
                            <h3><?= $total_activities ?></h3>
                        </div>
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Notifications</h6>
                            <h3><?= $total_notifications ?></h3>
                        </div>
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm p-3">
                    <h6 class="mb-3">User & Car Stats</h6>
                    <canvas id="statsChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm p-3">
                    <h6 class="mb-3">Recent Activities</h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                       <tbody>
<?php if($recent_activities && $recent_activities->num_rows > 0): ?>
    <?php while($row = $recent_activities->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['user_name']) ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= date('d M Y', strtotime($row['activity_date'])) ?> <?= $row['activity_time'] ?></td>
        <td>
            <span class="badge <?= $row['type']=='visit'?'bg-info':'bg-secondary' ?>">
                <?= ucfirst($row['type']) ?>
            </span>
        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="4" class="text-center">No activities found</td></tr>
<?php endif; ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const ctx = document.getElementById('statsChart').getContext('2d');
const statsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Users', 'Total Cars', 'New Cars', 'Used Cars', 'Activities', 'Notifications'],
        datasets: [{
            label: 'Count',
            data: [<?= $total_users ?>, <?= $total_cars ?>, <?= $new_cars ?>, <?= $used_cars ?>, <?= $total_activities ?>, <?= $total_notifications ?>],
            backgroundColor: ['#0d6efd','#198754','#ffc107','#fd7e14','#0dcaf0','#dc3545']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
