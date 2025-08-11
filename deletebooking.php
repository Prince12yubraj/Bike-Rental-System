<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to login page if not logged in
    exit;
}
include('db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Check if booking ID is provided
if (!isset($_GET['id'])) {
    header("Location: managebooking.php");
    exit();
}

$booking_id = $_GET['id'];

// Database connection
include('db.php');

// Retrieve booking information before deletion
$sql = "SELECT bike_name, quantity FROM booking WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $bike_name = $row['bike_name'];
    $quantity = $row['quantity'];

    // Delete booking from the database
    $sql_delete = "DELETE FROM booking WHERE id = ?";
    $stmt_delete = $con->prepare($sql_delete);
    $stmt_delete->bind_param("i", $booking_id);
    $stmt_delete->execute();

    // Update bike quantity in the bikes table
    $sql_update = "UPDATE bikes SET quantity = quantity + ? WHERE bike_name = ?";
    $stmt_update = $con->prepare($sql_update);
    $stmt_update->bind_param("is", $quantity, $bike_name);
    $stmt_update->execute();

    // Close statements
    $stmt->close();
    $stmt_delete->close();
    $stmt_update->close();
}

// Close connection
$con->close();

// Redirect to manage booking page
header("Location: managebooking.php");
exit();
?>
