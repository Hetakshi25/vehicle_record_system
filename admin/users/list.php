<?php
session_start();
require_once '../../includes/db.php';

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Handle Approve/Reject actions
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] === 'approve') {
        $conn->query("UPDATE users SET approval_status='approved' WHERE id=$id");
    } elseif ($_GET['action'] === 'reject') {
        $conn->query("UPDATE users SET approval_status='rejected' WHERE id=$id");
    }
    header("Location: list.php");
    exit;
}

// Fetch users from the database
$query = "SELECT id, name, email, phone, role, gender, dob, address, approval_status, created_at 
          FROM users ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Users List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
.sidebar { height: 100vh; background-color: #343a40; }
.sidebar a { color: #fff; display: block; padding: 15px; text-decoration: none; }
.sidebar a:hover { background-color: #495057; }
.navbar-brand { font-weight: bold; }
</style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h3 class="text-center text-white mb-4">Admin Panel</h3>
        <a href="../index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="list.php"><i class="fas fa-users me-2"></i>Users</a>
        <a href="../cars/list.php"><i class="fas fa-car me-2"></i>Cars</a>
        <a href="../activities/list.php"><i class="fas fa-calendar-alt me-2"></i>Activities</a>
        <a href="../notifications/list.php"><i class="fas fa-bell me-2"></i>Notifications</a>
        <a href="../reports.php"><i class="fas fa-chart-line me-2"></i>Reports</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <nav class="navbar navbar-light bg-light mb-4 rounded">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h3">Users List</span>
            </div>
        </nav>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Address</th>
                        <th>Approval Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                <td><?= ucfirst($row['role']) ?></td>
                                <td><?= ucfirst($row['gender']) ?></td>
                                <td><?= $row['dob'] ? date('d M Y', strtotime($row['dob'])) : '-' ?></td>
                                <td><?= htmlspecialchars($row['address']) ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $row['approval_status']=='approved'?'bg-success':'' ?>
                                        <?= $row['approval_status']=='rejected'?'bg-danger':'' ?>
                                        <?= $row['approval_status']=='pending'?'bg-warning text-dark':'' ?>
                                    ">
                                        <?= ucfirst($row['approval_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($row['approval_status']=='pending'): ?>
                                        <a href="list.php?action=approve&id=<?= $row['id'] ?>" class="btn btn-success btn-sm mb-1">
                                            <i class="fas fa-check"></i> Approve
                                        </a>
                                        <a href="list.php?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mb-1">
                                            <i class="fas fa-times"></i> Reject
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">No actions</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No users found</td>
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
