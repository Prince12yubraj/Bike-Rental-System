<?php
session_start();
include('db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Check if the booking ID is provided in the URL
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Database connection
    include('db.php');

    // Update booking status to "Rejected"
    $sql_update_status = "UPDATE booking SET status = 'Rejected' WHERE id = ?";
    $stmt_update_status = $con->prepare($sql_update_status);
    $stmt_update_status->bind_param("i", $booking_id);

    // Execute the update query
    if ($stmt_update_status->execute()) {
        // Redirect back to the manage booking page with status
        header("Location: managebooking.php?status=rejected");
        exit();
    } else {
        // Handle error
        echo "Error: Unable to update booking status.";
    }

    // Close statement
    $stmt_update_status->close();

    // Close connection
    $con->close();
} else {
    // Redirect to manage booking page if booking ID is not provided
    header("Location: managebooking.php");
    exit();
}
?>
