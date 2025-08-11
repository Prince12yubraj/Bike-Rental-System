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

// Check if the booking ID and action are provided in the URL
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] === 'approve') {
    $booking_id = $_GET['id'];

    // Update the status of the booking to approved
    $sql = "UPDATE booking SET status = 'Approved' WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        // Redirect back to the manage booking page
        header("Location: managebooking.php");
        exit();
    } else {
        // Handle error
        echo "Error: Unable to approve booking.";
    }
} else {
    // Redirect to manage booking page if booking ID or action is not provided
    header("Location: managebooking.php");
    exit();
}
?>
