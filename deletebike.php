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
    header("Location: managebike.php");
    exit();
}

$booking_id = $_GET['id'];

// JavaScript confirmation dialog
echo "<script>
    var confirmDelete = confirm('Are you sure you want to delete this booking?');
    if (confirmDelete) {
        window.location.href = 'deletebike.php?id=$booking_id&confirm=true';
    } else {
        window.location.href = 'managebike.php';
    }
</script>";

// Check if confirmation is received
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
    // Prepare the delete statement
    $sql = "DELETE FROM bikes WHERE id = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("i", $booking_id);
        // Attempt to execute the statement
        if ($stmt->execute()) {
            // Redirect to manage booking page
            header("Location: managebike.php");
            exit();
        } else {
            // Error handling
            echo "Error deleting booking: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error preparing the statement
        echo "Error preparing delete statement: " . $con->error;
    }
}
?>
