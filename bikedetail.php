<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to login page if not logged in
    exit;
}
include('db.php');

// Fetch available bikes from the database
$sql = "SELECT * FROM bikes WHERE quantity > 0";
$result = $con->query($sql);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Bikes</title>
    <style>
        
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
    </style>
</head>
<body>
    <h2>Available Bikes</h2>
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
                        <a href="addbooking.php?bike_id=<?php echo $row['id']; ?>" class="book-now-button">Book Now</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "No available bikes.";
        }
        ?>
    </div>
</body>
</html>

<?php
// Close database connection
$con->close();
?>
