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

// Handle reject or approve action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $booking_id = intval($_GET['id']); // Validate booking ID as integer

    // If action is reject or approve
    if ($action === 'reject' || $action === 'approve') {
        // Update status in the database
        $status = ($action === 'reject') ? 'Rejected' : 'Approved';
        $sql_status_update = "UPDATE booking SET status = ? WHERE id = ?";
        $stmt_status_update = $con->prepare($sql_status_update);
        $stmt_status_update->bind_param("si", $status, $booking_id);
        $stmt_status_update->execute();

        // Error handling
        if ($stmt_status_update->error) {
            // Handle error, maybe log it or show a user-friendly message
        }
    }
}

// Fetch all bookings from the database
$sql = "SELECT * FROM booking";
$result = $con->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Booking</title>
    <style>
        /* CSS styles */
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .action-links a {
            text-decoration: none;
            color: #007bff;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .license-image {
            max-width: 100px;
            max-height: 100px;
            cursor: pointer;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
        }

        /* Modal content */
        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
        }

        /* Close button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        .rejected-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
          .add-booking-btn {
        margin-top: 20px;
        margin-left: auto;
        margin-right: auto;
        display: block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .add-booking-btn:hover {
        background-color: #0056b3;
    }
            

       
    </style>
</head>
<body>
    <h2>Manage Booking</h2>
  
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Start Date</th>
            <th>Return Date</th>
            <th>Bike Name</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>License</th>
            <th>Status</th>
            <th>Action</th>
            <th>Edit/Delete Booking</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>"; // Added htmlspecialchars for security
                echo "<td>" . htmlspecialchars($row['email']) . "</td>"; // Added htmlspecialchars for security
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>"; // Added htmlspecialchars for security
                echo "<td>" . $row['start_date'] . "</td>";
                echo "<td>" . $row['return_date'] . "</td>";
                echo "<td>" . $row['bike_name'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['subtotal'] . "</td>";
                echo "<td>";
                if (!empty($row['license_image'])) {
                    // Open modal popup on click
                    echo "<img src='" . htmlspecialchars($row['license_image']) . "' alt='License Image' class='license-image' onclick='openModal(\"" . htmlspecialchars($row['license_image']) . "\")'>";
                } else {
                    echo "No image";
                }
                echo "</td>";
                echo "<td>";
                if ($row['status'] == 'Rejected') {
                    echo "Rejected";
                } elseif ($row['status'] == 'Approved') {
                    echo "Approved";
                } else {
                    echo "Pending";
                }
                echo "</td>";
                echo "<td class='action-links'>";
                if ($row['approved'] == 0) {
                    echo "<a href='managebooking.php?action=approve&id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to approve this booking?\")'>Approve</a>";
                    echo " | ";
                    echo "<a href='managebooking.php?action=reject&id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to reject this booking?\")'>Reject</a>";
                }
                echo "</td>";
                echo "<td>";
                echo "<div class='edit-delete'>";
                echo "<a href='editbooking.php?id=" . $row['id'] . "'><button>Edit</button></a>";
                echo "<a href='deletebooking.php?action=delete&id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this booking?\")'><button>Delete</button></a>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No bookings found.</td></tr>";
        }
        ?>
        <a href="addbooking.php"><button class="add-booking-btn">Add Booking</button></a>
    </table>
    <!-- Modal popup for displaying the image -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img01">
    </div>

    <script>
        // JavaScript functions to open and close the modal
        function openModal(imgSrc) {
            var modal = document.getElementById('myModal');
            var modalImg = document.getElementById("img01");
            modal.style.display = "block";
            modalImg.src = imgSrc;
        }

        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
        }
    </script>
</body>
</html>
