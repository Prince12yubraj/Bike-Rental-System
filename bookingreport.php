<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to login page if not logged in
    exit;
}
include('db.php');

// Fetch booking data from the database
$sql = "SELECT * FROM booking";
$result = $con->query($sql);

// Check if the booking has been rejected
if (isset($_GET['reject_id'])) {
    $reject_id = $_GET['reject_id'];
    $reject_sql = "UPDATE booking SET status='Rejected' WHERE id=$reject_id";
    if ($con->query($reject_sql) === TRUE) {
        // Update successful, refresh the page to reflect changes
        header("Location: bookingreport.php");
        exit();
    } else {
        echo "Error updating record: " . $con->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        .approved {
            color: green;
        }
        .rejected {
            color: red;
        }
        .pending {
            color: orange;
        }
        .no-bookings {
            text-align: center;
            font-style: italic;
            color: #777;
        }
        .license-image {
            max-width: 100px;
            max-height: 100px;
            cursor: pointer;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 50px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.9);
        }
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Booking Report</h1>
    </header>
    <div class="container">
        <div class="scrollable-container">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Bike ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Start Date</th>
                        <th>Return Date</th>
                        <th>Bike Name</th>
                        <th>Total Amount</th>
                        <th>License</th>
                        <th>Status</th>
                        
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['return_date']; ?></td>
                            <td><?php echo $row['bike_name']; ?></td>
                            <td>Rs <?php echo $row['subtotal']; ?></td>
                            <td>
                                <?php if (!empty($row['license_image'])): ?>
                                    <!-- Add a link to open the image in a modal -->
                                    <img src="<?php echo $row['license_image']; ?>" alt="License Image" class="license-image" onclick="openModal('<?php echo $row['license_image']; ?>')">
                                <?php else: ?>
                                    No image available
                                <?php endif; ?>
                            </td>
                            <td class="<?php echo $row['approved'] === 'Approved' ? 'approved' : ($row['status'] === 'Rejected' ? 'rejected' : 'pending'); ?>">
                                <?php echo $row['status']; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <a href="?reject_id=<?php echo $row['id']; ?>">Reject</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p class="no-bookings">No bookings found.</p>
            <?php endif; ?>
        </div>
    </div>

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
