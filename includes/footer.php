<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS (Your Styling) -->
    <link href="../assets/css/style.css" rel="stylesheet">

</head>
<body>
    </div> <!-- END container from header.php -->

    <!-- FOOTER -->
    <footer class="footer-section text-light pt-5 pb-3 mt-5">
      <div class="container">
        <div class="row">

          <!-- About Section -->
          <div class="col-md-3 mb-4">
            <h5 class="fw-bold text-uppercase mb-3">About Us</h5>
            <p class="small text-muted">
              <?php echo APP_NAME; ?> is your trusted platform to buy, sell, and manage vehicles.
              Our mission is to simplify your car journey with reliability and style.
            </p>
          </div>

          <!-- Quick Links -->
          <div class="col-md-2 mb-4">
            <h5 class="fw-bold text-uppercase mb-3">Quick Links</h5>
            <ul class="list-unstyled">
              <li><a href="about.php" class="footer-link">About Us</a></li>
              <li><a href="faqs.php" class="footer-link">FAQs</a></li>
              <li><a href="privacy.php" class="footer-link">Privacy</a></li>
              <li><a href="terms.php" class="footer-link">Terms of Use</a></li>
              <li><a href="admin/login.php" class="footer-link">Admin Login</a></li>
            </ul>
          </div>

          <!-- Newsletter -->
          <div class="col-md-4 mb-4">
            <h5 class="fw-bold text-uppercase mb-3">Subscribe Newsletter</h5>
            <form action="<?php echo BASE_URL; ?>subscribe.php" method="POST" class="d-flex mt-2">
              <input type="email" name="email" placeholder="Enter Email Address" class="form-control me-2 rounded-pill" required>
              <button type="submit" class="btn btn-warning rounded-pill px-3"><i class="bi bi-envelope-fill"></i></button>
            </form>
            <small class="text-muted d-block mt-2">
              *We send the latest deals & auto news every week.
            </small>
          </div>

          <!-- Social Media -->
          <div class="col-md-3 mb-4">
            <h5 class="fw-bold text-uppercase mb-3">Connect with Us</h5>
            <div class="d-flex gap-3 fs-4">
              <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
              <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
              <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
              <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
            </div>
          </div>

        </div>

        <!-- Divider -->
        <hr class="border-secondary">

        <!-- Copyright -->
        <div class="text-center small text-muted">
          &copy; <?php echo date("Y"); ?> <?php echo APP_NAME; ?>. All Rights Reserved. 🚗
        </div>
      </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
    <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Apply saved preferences
    if (localStorage.getItem("darkMode") === "true") {
      document.body.classList.add("dark-mode");
    }
    if (localStorage.getItem("largeText") === "true") {
      document.body.classList.add("large-text");
    }
    let savedTheme = localStorage.getItem("themeColor") || "warning";
    document.querySelectorAll(".btn").forEach(btn => {
      if (btn.classList.contains("btn-warning") || btn.classList.contains("btn-primary") || btn.classList.contains("btn-success")) {
        btn.className = btn.className.replace(/btn-(warning|primary|success)/g, "btn-" + savedTheme);
      }
    });
  });
</script>

   
  </body>
</html>
