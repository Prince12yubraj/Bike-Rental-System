<?php
session_start();

// Check if the admin is not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to login page
    exit;
}

include('db.php');
$error = "";
$totalAmount = 0;
$paymentUrls = array(); // Array to hold payment URLs for each bike

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $start_date = $_POST['start_date'];
    $return_date = $_POST['return_date'];
    $bikes = $_POST['bikes'];
    $quantities = $_POST['quantities'];

    // Get today's date
    $today = date("Y-m-d");

    if ($return_date <= $start_date) {
        $error = "End date must be after start date.";
    } elseif ($start_date < $today) {
        $error = "Start date cannot be in the past.";
    } else {
        // Handle license image upload
        $targetDirectory = "image/"; // Directory to store uploaded images
        $licenseImageName = $_FILES["license_image"]["name"];
        $targetFilePath = $targetDirectory . $licenseImageName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Check if the file is a valid image file
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileType, $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["license_image"]["tmp_name"], $targetFilePath)) {
                // File uploaded successfully
                // Proceed with booking process

                // Calculate the number of days between start date and return date
                $startDate = new DateTime($start_date);
                $returnDate = new DateTime($return_date);
                $rentalDays = $returnDate->diff($startDate)->days + 1; // Including both start and return dates

                // Loop through selected bikes and quantities
                foreach ($bikes as $key => $bike_name) {
                    // Check if the bike name exists in quantities array
                    if (isset($quantities[$bike_name])) {
                        $quantity = $quantities[$bike_name];

                        // Query the database to get the price and available quantity of the selected bike
                        $sql = "SELECT price, quantity FROM bikes WHERE bike_name = ?";
                        $stmt = $con->prepare($sql);
                        $stmt->bind_param("s", $bike_name);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $price = $row['price'];
                            $availableQuantity = $row['quantity'];

                            // Check if requested quantity is available
                            if ($quantity > $availableQuantity) {
                                $error = "Requested quantity for $bike_name exceeds available quantity.";
                                break;
                            }

                            // Calculate subtotal for each bike for the entire rental period
                            $subtotal = floatval($price) * intval($quantity) * intval($rentalDays);
                            $totalAmount += $subtotal;

                            // Construct URL for this bike's payment and add to paymentUrls array
                            $paymentUrl = "payment.php?";
                            $paymentUrl .= "name=" . urlencode($name) . "&";
                            $paymentUrl .= "bike_name[]=" . urlencode($bike_name) . "&"; // Use [] to make bike_name an array
                            $paymentUrl .= "quantity[]=" . urlencode($quantity) . "&"; // Use [] to make quantity an array
                            $paymentUrl .= "subtotal[]=" . urlencode($subtotal);
                            $paymentUrls[] = $paymentUrl; // Store URL in array

                            // Insert booking details into the database for each bike
                            $sql_insert_booking = "INSERT INTO `booking`(`name`, `email`, `phone`, `start_date`, `return_date`, `bike_name`, `quantity`, `subtotal`, `license_image`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt_insert_booking = $con->prepare($sql_insert_booking);
                            $stmt_insert_booking->bind_param("ssssssiss", $name, $email, $phone, $start_date, $return_date, $bike_name, $quantity, $subtotal, $targetFilePath);
                            $stmt_insert_booking->execute();

                            // Update bike quantities in the database
                            $sql_update_quantity = "UPDATE bikes SET quantity = quantity - ? WHERE bike_name = ?";
                            $stmt_update_quantity = $con->prepare($sql_update_quantity);
                            $stmt_update_quantity->bind_param("is", $quantity, $bike_name);
                            $stmt_update_quantity->execute();
                        } else {
                            $error = "Error: Bike information not found.";
                            break; // Exit the loop if an error occurs
                        }
                    }
                }

                // After successful booking insertion
                if (empty($error) && !empty($paymentUrls)) {
                    // Redirect to payment.php with all payment URLs
                    header("Location: payment.php?" . http_build_query(array('urls' => $paymentUrls)));
                    exit();
                }

            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Booking</title>
    <style>
       body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
           
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
           
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow-y: auto;
        }
        nav {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        

        .booking {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            width: 50%;
            margin: 0 auto;
        }

        .booking h2 {
            margin: 0 0 30px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .booking form {
            text-align: left;
        }

        .booking label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        .booking input[type="text"],
        .booking input[type="email"],
        .booking input[type="tel"],
        .booking input[type="date"],
        .booking input[type="checkbox"],
        .booking input[type="number"],
        .booking input[type="file"],
        .booking button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #aaa;
            box-sizing: border-box;
            font-size: 16px;
        }

        .booking input[type="file"] {
            cursor: pointer;
        }

        .booking button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .booking button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
        }
         .footer {
            background-color: #333;
            color: white;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: center;
        }

        .footer div {
            margin-bottom: 20px;
        }

        .contact, .quick-links, .follow-us, .payment {
            flex: 1 1 200px;
            margin-right: 20px;
        }

        .contact h3, .quick-links h3, .follow-us h3 {
            margin-bottom: 10px;
            color: #fff;
        }

        .contact p {
            margin: 5px 0;
        }

        .quick-links ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .quick-links ul li {
            margin-bottom: 5px;
        }

        .quick-links ul li a {
            color: #fff;
            text-decoration: none;
        }

        .follow-us a {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
        }

        .copyright {
            flex-basis: 100%;
            text-align: center;
            color: #ccc;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <nav>
        <a href="home.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="booking.php">Booking</a>
        <a href="bookingreport.php">Booking Report</a>
        <a href="signup.php">Sign Up</a>
        <a href="login.php">Login</a>
        <a href="logout.php">Logout</a>
        <a href="http://localhost/bikerentalsystem/admin/adminlogin.php">Admin</a>
    </nav>
    <main>
        <div class="booking">
            <h2>Add Booking</h2>
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post" id="booking-form" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" pattern="[0-9]{10}" title="Please enter a 10-digit phone number" required>
            
                <label for="license_image">Upload License Image:</label>
                <input type="file" id="license_image" name="license_image" accept="image/*" required>
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>
                <label for="return_date">Return Date:</label>
                <input type="date" id="return_date" name="return_date" required>
                <fieldset>
                    <legend>Select Bikes</legend>
                    <?php
                    // Query the database to get all available bikes
                    $sql = "SELECT bike_name, price, quantity FROM bikes";
                    $result = $con->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $bike = $row['bike_name'];
                            $price = $row['price'];
                            $quantity = $row['quantity'];
                            ?>
                            <label>
                                <input type="checkbox" class="bike-checkbox" name="bikes[]" value="<?php echo $bike; ?>">
                                <?php echo $bike . " - Rs" . $price . " (Quantity: " . $quantity . ")"; ?>
                            </label>
                            <input type="number" class="quantity" name="quantities[<?php echo $bike; ?>]" min="1" max="<?php echo $quantity; ?>" value="1"><br>
                            <?php
                        }
                    }
                    ?>
                </fieldset>
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>
    </main>
   <footer class="footer">
        <div class="contact">
            <h3>Contact</h3>
            <p>Email: bikerental.com@gmail.com</p>
            <p>Phone: 9864567371</p>
            <p>Address: Godawari</p>
        </div>

        <div class="quick-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">About</a></li>
                <li><a href="#">Bike</a></li>
            </ul>
        </div>

        <div class="follow-us">
            <h3>Follow Us</h3>
            <!-- Add links to your social media profiles -->
            <a href="#">Facebook</a> 
            <a href="#">Twitter</a> 
            <a href="#">Instagram</a> 
        </div>

        
        <div class="payment">
            <h3>Payment</h3>
        </div>

        <!-- Copyright text -->
        <div class="copyright">© 2024 Bike Rental System. All rights reserved.</div>
    </footer>
</body>
</html>
