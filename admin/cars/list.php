<?php
session_start();
require_once '../../includes/db.php';

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

// Handle Add Car
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car'])) {
    $user_id = $_SESSION['admin_id']; // or another default value
    $brand = $conn->real_escape_string($_POST['brand']);
    $model = $conn->real_escape_string($_POST['model']);
    $variant = $conn->real_escape_string($_POST['variant']);
    $trim = $conn->real_escape_string($_POST['trim']);
    $year = intval($_POST['year']);
    $vin = $conn->real_escape_string($_POST['vin']);
    $registration_no = $conn->real_escape_string($_POST['registration_no']);
    $color = $conn->real_escape_string($_POST['color']);
    $engine = $conn->real_escape_string($_POST['engine']);
    $horsepower = intval($_POST['horsepower']);
    $torque = $conn->real_escape_string($_POST['torque']);
    $fuel = $conn->real_escape_string($_POST['fuel']);
    $fuel_capacity = floatval($_POST['fuel_capacity']);
    $transmission = $conn->real_escape_string($_POST['transmission']);
    $drivetrain = $conn->real_escape_string($_POST['drivetrain']);
    $seats = intval($_POST['seats']);
    $doors = intval($_POST['doors']);
    $mileage = floatval($_POST['mileage']);
    $vehicle_condition = $conn->real_escape_string($_POST['vehicle_condition']);
    $owners = intval($_POST['owners']);
    $warranty = $conn->real_escape_string($_POST['warranty']);
    $insurance = $conn->real_escape_string($_POST['insurance']);
    $price = floatval($_POST['price']);
    $negotiable = isset($_POST['negotiable']) ? 1 : 0;
    $emi_available = isset($_POST['emi_available']) ? 1 : 0;
    $features = $conn->real_escape_string($_POST['features']);
    $description = $conn->real_escape_string($_POST['description']);
    $video_url = $conn->real_escape_string($_POST['video_url']);

    // Handle images
    $upload_dir = '../../uploads/vehicles/';
    $images = [];
    for ($i = 1; $i <= 5; $i++) {
        $images[$i] = null;
        if (!empty($_FILES["image$i"]['name'])) {
            $tmp_name = $_FILES["image$i"]['tmp_name'];
            $filename = time() . "_$i_" . basename($_FILES["image$i"]['name']);
            if(move_uploaded_file($tmp_name, $upload_dir . $filename)){
                $images[$i] = "uploads/vehicles/" . $filename;
            }
        }
    }

    $conn->query("INSERT INTO vehicles 
        (user_id, brand, model, variant, trim, year, vin, registration_no, color, engine, horsepower, torque, fuel, fuel_capacity, transmission, drivetrain, seats, doors, mileage, vehicle_condition, owners, warranty, insurance, price, negotiable, emi_available, features, description, video_url, image1, image2, image3, image4, image5, created_at) 
        VALUES 
        ($user_id,'$brand','$model','$variant','$trim',$year,'$vin','$registration_no','$color','$engine',$horsepower,'$torque','$fuel',$fuel_capacity,'$transmission','$drivetrain',$seats,$doors,$mileage,'$vehicle_condition',$owners,'$warranty','$insurance',$price,$negotiable,$emi_available,'$features','$description','$video_url','{$images[1]}','{$images[2]}','{$images[3]}','{$images[4]}','{$images[5]}',NOW())");

    header("Location: list.php");
    exit;
}

// Fetch all vehicles
$query = "SELECT * FROM vehicles ORDER BY created_at DESC";
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
<title>Admin - Vehicles</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<style>
body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
.sidebar { height: 100vh; background-color: #343a40; min-width: 220px; }
.sidebar a { color: #fff; display: block; padding: 15px; text-decoration: none; font-size: 16px; }
.sidebar a:hover { background-color: #495057; text-decoration: none; }
.car-img { width: 80px; height: auto; margin: 2px; border-radius: 5px; }
.img-gallery { display: flex; flex-wrap: wrap; justify-content: center; }
.table th, .table td { vertical-align: middle !important; font-size: 12px; }
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
    <a href="list.php"><i class="fas fa-car me-2"></i>Vehicles</a>
    <a href="../activities/list.php"><i class="fas fa-calendar-alt me-2"></i>Activities</a>
    <a href="../reports.php"><i class="fas fa-chart-line me-2"></i>Reports</a>
    <a href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
</div>

<!-- Main Content -->
<div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Vehicles List</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
            <i class="fas fa-plus"></i> Add Vehicle
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th>Images</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Variant</th>
                    <th>Trim</th>
                    <th>Year</th>
                    <th>VIN</th>
                    <th>Registration</th>
                    <th>Color</th>
                    <th>Engine</th>
                    <th>HP</th>
                    <th>Torque</th>
                    <th>Fuel</th>
                    <th>Fuel Cap.</th>
                    <th>Transmission</th>
                    <th>Drivetrain</th>
                    <th>Seats</th>
                    <th>Doors</th>
                    <th>Mileage</th>
                    <th>Condition</th>
                    <th>Owners</th>
                    <th>Warranty</th>
                    <th>Insurance</th>
                    <th>Price</th>
                    <th>Negotiable</th>
                    <th>EMI</th>
                    <th>Features</th>
                    <th>Description</th>
                    <th>Video</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="text-center">
                    <td><?= $row['id'] ?></td>
                    <td>
                        <div class="img-gallery">
                        <?php
                        $imagesFound = false;
                        for($i=1;$i<=5;$i++){
                            $img = $row["image$i"];
                            if($img && file_exists('../../'.$img)){
                                $imagesFound = true;
                                echo "<img src='../../$img' class='car-img' alt='Car Image'>";
                            }
                        }
                        if(!$imagesFound) echo "<span class='text-muted'>No Image</span>";
                        ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($row['brand']) ?></td>
                    <td><?= htmlspecialchars($row['model']) ?></td>
                    <td><?= htmlspecialchars($row['variant']) ?></td>
                    <td><?= htmlspecialchars($row['trim']) ?></td>
                    <td><?= $row['year'] ?></td>
                    <td><?= htmlspecialchars($row['vin']) ?></td>
                    <td><?= htmlspecialchars($row['registration_no']) ?></td>
                    <td><?= htmlspecialchars($row['color']) ?></td>
                    <td><?= htmlspecialchars($row['engine']) ?></td>
                    <td><?= $row['horsepower'] ?></td>
                    <td><?= htmlspecialchars($row['torque']) ?></td>
                    <td><?= htmlspecialchars($row['fuel']) ?></td>
                    <td><?= $row['fuel_capacity'] ?></td>
                    <td><?= htmlspecialchars($row['transmission']) ?></td>
                    <td><?= htmlspecialchars($row['drivetrain']) ?></td>
                    <td><?= $row['seats'] ?></td>
                    <td><?= $row['doors'] ?></td>
                    <td><?= $row['mileage'] ?></td>
                    <td><?= htmlspecialchars($row['vehicle_condition']) ?></td>
                    <td><?= $row['owners'] ?></td>
                    <td><?= htmlspecialchars($row['warranty']) ?></td>
                    <td><?= htmlspecialchars($row['insurance']) ?></td>
                    <td><?= number_format($row['price'],2) ?></td>
                    <td><?= $row['negotiable'] ? 'Yes' : 'No' ?></td>
                    <td><?= $row['emi_available'] ? 'Yes' : 'No' ?></td>
                    <td><?= htmlspecialchars($row['features']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <?php if(!empty($row['video_url'])): ?>
                            <a href="<?= htmlspecialchars($row['video_url']) ?>" target="_blank">
                                <i class="fab fa-youtube text-danger"></i>
                            </a>
                        <?php else: echo "-"; endif; ?>
                    </td>
                    <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="31" class="text-center">No vehicles found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form method="POST" enctype="multipart/form-data">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-3"><input type="text" name="brand" class="form-control" placeholder="Brand" required></div>
                    <div class="col-md-3"><input type="text" name="model" class="form-control" placeholder="Model" required></div>
                    <div class="col-md-3"><input type="text" name="variant" class="form-control" placeholder="Variant"></div>
                    <div class="col-md-3"><input type="text" name="trim" class="form-control" placeholder="Trim"></div>
                    <div class="col-md-2"><input type="number" name="year" class="form-control" placeholder="Year"></div>
                    <div class="col-md-2"><input type="text" name="vin" class="form-control" placeholder="VIN"></div>
                    <div class="col-md-2"><input type="text" name="registration_no" class="form-control" placeholder="Reg. No."></div>
                    <div class="col-md-2"><input type="text" name="color" class="form-control" placeholder="Color"></div>
                    <div class="col-md-3"><input type="text" name="engine" class="form-control" placeholder="Engine"></div>
                    <div class="col-md-2"><input type="number" name="horsepower" class="form-control" placeholder="HP"></div>
                    <div class="col-md-2"><input type="text" name="torque" class="form-control" placeholder="Torque"></div>
                    <div class="col-md-2">
                        <select name="fuel" class="form-control">
                            <option value="">Fuel</option>
                            <option value="Petrol">Petrol</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Electric">Electric</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="number" step="0.01" name="fuel_capacity" class="form-control" placeholder="Fuel Capacity"></div>
                    <div class="col-md-2">
                        <select name="transmission" class="form-control">
                            <option value="">Transmission</option>
                            <option value="Manual">Manual</option>
                            <option value="Automatic">Automatic</option>
                            <option value="CVT">CVT</option>
                            <option value="Dual-Clutch">Dual-Clutch</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="text" name="drivetrain" class="form-control" placeholder="Drivetrain"></div>
                    <div class="col-md-2"><input type="number" name="seats" class="form-control" placeholder="Seats"></div>
                    <div class="col-md-2"><input type="number" name="doors" class="form-control" placeholder="Doors"></div>
                    <div class="col-md-2"><input type="number" step="0.01" name="mileage" class="form-control" placeholder="Mileage"></div>
                    <div class="col-md-2">
                        <select name="vehicle_condition" class="form-control">
                            <option value="New">New</option>
                            <option value="Used">Used</option>
                            <option value="Certified Pre-Owned">Certified Pre-Owned</option>
                        </select>
                    </div>
                    <div class="col-md-2"><input type="number" name="owners" class="form-control" placeholder="Owners"></div>
                    <div class="col-md-3"><input type="text" name="warranty" class="form-control" placeholder="Warranty"></div>
                    <div class="col-md-3"><input type="text" name="insurance" class="form-control" placeholder="Insurance"></div>
                    <div class="col-md-2"><input type="number" step="0.01" name="price" class="form-control" placeholder="Price"></div>
                    <div class="col-md-2 form-check"><input type="checkbox" name="negotiable" class="form-check-input" id="negotiable"><label class="form-check-label" for="negotiable">Negotiable</label></div>
                    <div class="col-md-2 form-check"><input type="checkbox" name="emi_available" class="form-check-input" id="emi_available"><label class="form-check-label" for="emi_available">EMI</label></div>
                    <div class="col-12"><input type="text" name="features" class="form-control" placeholder="Features"></div>
                    <div class="col-12"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
                    <div class="col-12"><input type="url" name="video_url" class="form-control" placeholder="YouTube Link"></div>
                    <div class="col-md-2"><input type="file" name="image1" class="form-control"></div>
                    <div class="col-md-2"><input type="file" name="image2" class="form-control"></div>
                    <div class="col-md-2"><input type="file" name="image3" class="form-control"></div>
                    <div class="col-md-2"><input type="file" name="image4" class="form-control"></div>
                    <div class="col-md-2"><input type="file" name="image5" class="form-control"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="add_car" class="btn btn-primary">Add Vehicle</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
