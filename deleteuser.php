<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to login page if not logged in
    exit;
}
include('db.php');

// Check if user_id is provided and it is numeric
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete user from the database
    $delete_query = "DELETE FROM user WHERE user_id = $user_id";

    if ($con->query($delete_query) === TRUE) {
        // Deletion successful
        $_SESSION['success_message'] = "User deleted successfully.";
    } else {
        // Deletion failed
        $_SESSION['error_message'] = "Error deleting user: " . $con->error;
    }

    // Redirect back to the manage user page
    header("Location: manageuser.php");
    exit();
} else {
    // Invalid user ID
    $_SESSION['error_message'] = "Invalid user ID.";
    header("Location: manageuser.php");
    exit();
}
?>
