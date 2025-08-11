<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to login page if not logged in
    exit;
}
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
    <title>Manage Bikes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
        }
        .bike-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            grid-gap: 20px;
        }
        .bike-item {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
        }
        .bike-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .bike-info {
            padding: 10px 0;
            text-align: center;
        }
        .edit-delete {
            text-align: center;
        }
        .add-bike-button {
            text-align: center;
            margin-top: 20px;
        }
        .add-bike-button button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .add-bike-button button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Display bikes from the database -->
        <h3>Existing Bikes</h3>
        <div class="bike-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='bike-item'>";
                    echo "<a href='" . $row['image'] . "' target='_blank'><img src='" . $row['image'] . "' alt='Bike Image'></a>";
                    echo "<div class='bike-info'>";
                    echo "<h4>" . $row['bike_name'] . "</h4>";
                    echo "<p>Model: " . $row['model'] . "</p>";
                    echo "<p>Engine: " . $row['engine'] . "</p>";
                    echo "<p>Price: Rs " . $row['price'] . "</p>";
                    echo "</div>";
                    echo "<div class='edit-delete'>";
                    echo "<a href='editbike.php?id=" . $row['id'] . "'><button>Edit</button></a>";
                    echo "<a href='deletebike.php?id=" . $row['id'] . "'><button>Delete</button></a>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "No bikes found.";
            }
            ?>
        </div>
         <div class="add-bike-button">
        <a href="addbike.php"><button>Add Bike</button></a>
    </div>
    </div>
</body>
</html>

<?php
// Close database connection
$con->close();
?>
