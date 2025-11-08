<?php
session_start();
require_once '../includes/db.php'; // Your DB connection

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($admin_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['admin_username'] = $username;
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4e73df, #1cc88a);
            height: 100vh;
        }
        .login-card {
            max-width: 400px;
            margin: auto;
            margin-top: 10%;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            background-color: #fff;
        }
        .login-card h2 {
            margin-bottom: 1.5rem;
            font-weight: 700;
            color: #333;
            text-align: center;
        }
        .btn-login {
            background-color: #4e73df;
            color: #fff;
            font-weight: 600;
        }
        .btn-login:hover {
            background-color: #2e59d9;
            color: #fff;
        }
        .error {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card shadow-lg">
            <h2>Admin Login</h2>
            <?php if ($error) : ?>
                <div class="alert alert-danger error"><?= $error ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-login btn-lg">Login</button>
                </div>
            </form>
            <p class="text-center mt-3 text-muted">© <?= date('Y') ?> Your Company</p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
