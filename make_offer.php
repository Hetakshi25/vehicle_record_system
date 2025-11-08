<?php
// make_offer.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$inquiry_id = isset($_POST['inquiry_id']) ? (int) $_POST['inquiry_id'] : 0;
$offer_amount = isset($_POST['offer_amount']) ? floatval($_POST['offer_amount']) : 0.0;

if ($inquiry_id <= 0 || $offer_amount <= 0) {
    $_SESSION['flash_message'] = "Invalid offer details.";
    header("Location: all_inquiries.php?inquiry_id=".$inquiry_id);
    exit;
}

// verify inquiry exists
$stmt = $conn->prepare("SELECT i.*, v.user_id AS seller_id FROM inquiries i JOIN vehicles v ON i.vehicle_id = v.id WHERE i.id = ?");
$stmt->bind_param("i", $inquiry_id);
$stmt->execute();
$inq = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$inq) {
    $_SESSION['flash_message'] = "Inquiry not found.";
    header("Location: all_inquiries.php");
    exit;
}

// insert offer
$stmt = $conn->prepare("INSERT INTO inquiry_offers (inquiry_id, user_id, offer_amount, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
if (!$stmt) die("SQL Error: " . $conn->error);
$stmt->bind_param("iid", $inquiry_id, $user_id, $offer_amount);
$stmt->execute();
$offer_id = $stmt->insert_id;
$stmt->close();

// insert a chat message (so offer appears as message stream)
$offer_msg = "Made an offer of ₹".number_format($offer_amount,2);
$stmt = $conn->prepare("INSERT INTO inquiry_replies (inquiry_id, user_id, reply_message, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $inquiry_id, $user_id, $offer_msg);
$stmt->execute();
$stmt->close();

$_SESSION['flash_message'] = "Offer sent successfully.";
header("Location: all_inquiries.php?inquiry_id=".$inquiry_id);
exit;
