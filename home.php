<?php
session_start();
include('db.php');

// Check for database connection errors
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch all bikes from the database
$sql = "SELECT * FROM bikes";
$result = $con->query($sql);

// Check if there are any records
if ($result === false) {
    die("Error fetching bikes: " . $con->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
        
        .bike-container {
            display: flex;
            flex-wrap: wrap;
        }
        .bike {
            width: 250px;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .bike img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .book-now-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin-top: 10px;
        }
        .bike-details {
            display: none;
        }
        .bike:hover .bike-details {
            display: block;
        }
        h1,h2{
            text-align: center;

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
        <h1>Welcome to our Bike Rental Service</h1>
        <h2>Existing Bikes</h3>
        <div class="bike-container">
        <?php
        // Check if there are any available bikes
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="bike">
                    <h3><?php echo $row['bike_name']; ?></h3>
                    <p><strong>Model:</strong> <?php echo $row['model']; ?></p>
                    <p><strong>Engine:</strong> <?php echo $row['engine']; ?></p>
                    <p><strong>Price:</strong> <?php echo $row['price']; ?></p>
                    <p><strong>Quantity:</strong> <?php echo $row['quantity']; ?></p>
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['bike_name']; ?>">
                    <div class="bike-details">
                        <p><strong>Specifications:</strong> <?php echo $row['specifications']; ?></p>
                        <a href="booking.php?bike_id=<?php echo $row['id']; ?>" class="book-now-button">Book Now</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "No available bikes.";
        }
        ?>
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

<?php
// Close database connection
$con->close();
?>
