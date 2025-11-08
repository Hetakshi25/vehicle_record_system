<?php
session_start();
require_once '../includes/db.php';

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Handle CSV download
if (isset($_GET['download']) && in_array($_GET['download'], ['users', 'cars', 'activities'])) {
    $type = $_GET['download'];

    switch($type) {
        case 'users':
            $query = "SELECT id, name, email, phone, role, dob, gender, address, created_at FROM users ORDER BY created_at DESC";
            $filename = "users_report.csv";
            break;
        case 'cars':
            $query = "SELECT id, brand, model, variant, trim, price, mileage, transmission, vehicle_condition, fuel, color, features, created_at FROM vehicles ORDER BY created_at DESC";
            $filename = "cars_report.csv";
            break;
        case 'activities':
            $query = "SELECT id, user_id, title, activity_date, activity_time, type, created_at FROM activities ORDER BY created_at DESC";
            $filename = "activities_report.csv";
            break;
    }

    $result = $conn->query($query);

    if($result && $result->num_rows > 0){
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename='.$filename);

        $output = fopen('php://output', 'w');
        // Output header row
        $fields = array_keys($result->fetch_assoc());
        fputcsv($output, $fields);

        // Output data rows
        $result->data_seek(0); // reset pointer
        while($row = $result->fetch_assoc()){
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    } else {
        die("No data found to download.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Reports</title>
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
        <a href="index.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
        <a href="users/list.php"><i class="fas fa-users me-2"></i>Users</a>
        <a href="cars/list.php"><i class="fas fa-car me-2"></i>Cars</a>
        <a href="activities/list.php"><i class="fas fa-calendar-alt me-2"></i>Activities</a>
        <a href="reports.php"><i class="fas fa-chart-line me-2"></i>Reports</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        <nav class="navbar navbar-light bg-light mb-4 rounded">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h3">Download Reports</span>
            </div>
        </nav>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-2 text-primary"></i>
                        <h5 class="card-title">Users Report</h5>
                        <a href="?download=users" class="btn btn-primary"><i class="fas fa-download"></i> Download CSV</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-car fa-3x mb-2 text-success"></i>
                        <h5 class="card-title">Cars Report</h5>
                        <a href="?download=cars" class="btn btn-success"><i class="fas fa-download"></i> Download CSV</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-alt fa-3x mb-2 text-warning"></i>
                        <h5 class="card-title">Activities Report</h5>
                        <a href="?download=activities" class="btn btn-warning"><i class="fas fa-download"></i> Download CSV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
