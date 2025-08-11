<?php
session_start();
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

// Fetch booking details from the database
$sql = "SELECT * FROM booking WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $booking = $result->fetch_assoc();
} else {
    // Redirect if booking ID is invalid
    header("Location: managebooking.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $start_date = $_POST['start_date'];
    $return_date = $_POST['return_date'];
    $bike_name = $_POST['bike_name'];
    $quantity = $_POST['quantity'];
    $subtotal = $_POST['subtotal'];
    $approved = $_POST['approved'];

    // Update booking details in the database
    $sql = "UPDATE booking SET name = ?, email = ?, phone = ?, start_date = ?, return_date = ?, bike_name = ?, quantity = ?, subtotal = ?, approved = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssiiii", $name, $email, $phone, $start_date, $return_date, $bike_name, $quantity, $subtotal, $approved, $booking_id);
    $stmt->execute();

    // Redirect to manage booking page
    header("Location: managebooking.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
         input[type="tel"],
         input[type="date"],
         input[type="number"]{
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
   
    </style>
</head>
<body>

    <h2>Edit Booking</h2>
    <form method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $booking['name']; ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $booking['email']; ?>" required><br>
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo $booking['phone']; ?>" required><br>
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $booking['start_date']; ?>" required><br>
        <label for="return_date">Return Date:</label>
        <input type="date" id="return_date" name="return_date" value="<?php echo $booking['return_date']; ?>" required><br>
        <label for="bike_name">Bike Name:</label>
        <input type="text" id="bike_name" name="bike_name" value="<?php echo $booking['bike_name']; ?>" required><br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $booking['quantity']; ?>" required><br>
        <label for="subtotal">Total:</label>
        <input type="number" id="subtotal" name="subtotal" value="<?php echo $booking['subtotal']; ?>" required><br>
        
        <button type="submit">Update Booking</button>
    </form>
</body>
</html>
