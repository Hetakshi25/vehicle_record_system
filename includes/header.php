<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php'; // now $conn is available

$notifications = [];
$unread_count = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = (int) $_SESSION['user_id'];

    // Fetch latest 5 notifications
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Count unread
    $stmt2 = $conn->prepare("SELECT COUNT(*) as cnt FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result()->fetch_assoc();
    $unread_count = (int)$result2['cnt'];
    $stmt2->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= APP_NAME; ?> - Buy, Sell & Manage Vehicles">

  <!-- Title -->
  <title><?= APP_NAME; ?></title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    .lux-navbar {
      background: #111;
      padding: 0.8rem 1rem;
    }
    .navbar-brand {
      font-weight: 600;
      font-size: 1.2rem;
      color: #ffc107 !important;
    }
    .nav-link {
      font-weight: 500;
      color: #fff !important;
      margin-right: 1rem;
      transition: color 0.2s ease-in-out;
    }
    .nav-link:hover {
      color: #ffc107 !important;
    }
    .search-input {
      border-radius: 25px 0 0 25px;
      border: none;
    }
    .search-btn {
      border-radius: 0 25px 25px 0;
      background: #ffc107;
      color: #000;
      border: none;
    }
    .user-dropdown-toggle {
      background: #222;
      color: #fff !important;
      font-weight: 500;
    }
    .username {
      font-size: 0.95rem;
    }
    .dropdown-menu {
      font-size: 0.9rem;
    }
    /* Notification bell */
    .notification-bell {
      position: relative;
      font-size: 1.4rem;
      color: #fff;
      cursor: pointer;
    }
    .notification-bell .badge {
      position: absolute;
      top: -5px;
      right: -8px;
      background: #dc3545;
      color: #fff;
      font-size: 0.7rem;
      padding: 3px 6px;
      border-radius: 50%;
    }
    .notification-dropdown {
      width: 320px;
      max-height: 400px;
      overflow-y: auto;
    }
    .notification-item {
      font-size: 0.9rem;
      padding: 10px;
      border-bottom: 1px solid #f1f1f1;
    }
    .notification-item:last-child {
      border-bottom: none;
    }
    .notification-item.unread {
      background: #f8f9fa;
      font-weight: 500;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg lux-navbar fixed-top">
  <div class="container">

    <!-- Brand -->
    <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL; ?>index.php">
      <i class="bi bi-car-front-fill me-2"></i> <?= APP_NAME; ?>
    </a>

    <!-- Mobile toggle -->
    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL; ?>index.php"><i class="bi bi-house-door"></i> Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="vehicles.php"><i class="bi bi-car-front"></i> Vehicles</a>
        </li>

        <!-- Services Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="servicesMenu" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-grid"></i> Services
          </a>
          <ul class="dropdown-menu shadow-lg border-0 rounded-3">
            <li><a class="dropdown-item" href="new_cars.php">Buy New Car</a></li>
            <li><a class="dropdown-item" href="old_cars.php">Sell Old Car</a></li>
          </ul>
        </li>

        <!-- Search bar -->
        <li class="nav-item d-none d-lg-block ms-3">
          <form class="d-flex" action="index.php" method="get" role="search">
            <div class="input-group input-group-sm">
              <input class="form-control search-input" name="search" type="search" placeholder="Search cars">
              <button class="btn search-btn" type="submit">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </form>
        </li>

        <!-- Notifications -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item dropdown ms-3">
          <a class="nav-link position-relative notification-bell" href="#" id="notifMenu" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <?php if ($unread_count > 0): ?>
              <span class="badge"><?= $unread_count; ?></span>
            <?php endif; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 rounded-3 notification-dropdown">
            <?php if (count($notifications) > 0): ?>
              <?php foreach ($notifications as $note): ?>
                <li class="notification-item <?= $note['is_read'] ? '' : 'unread'; ?>">
                  <?php if ($note['type'] === 'reply'): ?>
                    <i class="bi bi-chat-dots text-success me-2"></i>
                  <?php elseif ($note['type'] === 'visit'): ?>
                    <i class="bi bi-calendar-event text-warning me-2"></i>
                  <?php else: ?>
                    <i class="bi bi-info-circle text-primary me-2"></i>
                  <?php endif; ?>
                  <?= htmlspecialchars($note['message']); ?><br>
                  <small class="text-muted"><?= date("M d, H:i", strtotime($note['created_at'])); ?></small>
                </li>
              <?php endforeach; ?>
              <li><a class="dropdown-item text-center text-primary fw-semibold py-2" href="notifications.php">View all</a></li>
            <?php else: ?>
              <li class="dropdown-item text-center text-muted py-3">No notifications</li>
            <?php endif; ?>
          </ul>
        </li>
        <?php endif; ?>

        <!-- User Dropdown -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item dropdown ms-3">
            <a class="nav-link dropdown-toggle d-flex align-items-center px-3 py-2 rounded-pill user-dropdown-toggle" 
               href="#" id="userMenu" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-2 fs-5"></i> 
              <span class="username"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 rounded-3">
              <li><a class="dropdown-item py-2" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
              <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person me-2"></i> Profile</a></li>
              <li><a class="dropdown-item py-2" href="settings.php"><i class="bi bi-gear me-2"></i> Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item ms-3"><a class="btn btn-outline-light btn-sm" href="login.php">Login</a></li>
          <li class="nav-item ms-2"><a class="btn btn-warning btn-sm" href="signup.php">Register</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<!-- MAIN CONTENT -->
<div class="container" style="margin-top:100px;">
  <!-- Page content goes here -->
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
