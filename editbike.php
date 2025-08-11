<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to login page if not logged in
    exit;
}
// Include database connection file
include('db.php');

// Check if ID parameter exists
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch bike details based on ID
    $sql = "SELECT * FROM bikes WHERE id = ?";
    if ($stmt = $con->prepare($sql)) {
        // Bind ID parameter
        $stmt->bind_param("i", $id);
        // Execute the statement
        $stmt->execute();
        // Store result
        $result = $stmt->get_result();
        // Fetch bike details
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $bike_name = $row['bike_name'];
            $model = $row['model'];
            $engine = $row['engine'];
            $price = $row['price'];
            $quantity = $row['quantity']; // Define quantity
            $specifications = $row['specifications']; // Define specifications
        } else {
            // Redirect to error page if bike not found
            header("location: error.php");
            exit();
        }
        // Close statement
        $stmt->close();
    }

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and update bike details
        // Validate bike name
        $bike_name = trim($_POST["bike_name"]);
        // Validate model
        $model = trim($_POST["model"]);
        // Validate engine
        $engine = trim($_POST["engine"]);
        // Validate price
        $price = trim($_POST["price"]);
        // Validate quantity
        $quantity = trim($_POST["quantity"]);
        // Validate specification
        $specifications = trim($_POST["specifications"]);

        // Handle image upload
        if ($_FILES["image"]["name"] != "") {
            $target_dir = "image/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                $error = "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["image"]["size"] > 500000) {
                $error = "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $error = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // Update image path in the database
                    $image_path = $target_file;
                } else {
                    $error = "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Update bike details in the database
        $sql = "UPDATE bikes SET bike_name = ?, model = ?, engine = ?, price = ?, quantity = ?, specifications = ?, image = ? WHERE id = ?";
        if ($stmt = $con->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssissi", $bike_name, $model, $engine, $price, $quantity, $specifications, $image_path, $id);
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to managebike.php after updating
                header("location: managebike.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            // Close statement
            $stmt->close();
        }

        // Close connection
        $con->close();
    }
} else {
    // Redirect to error page if id parameter is missing
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bike</title>
    <style type="text/css">
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
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

form {
    padding: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="number"],
textarea,
input[type="file"] {
    width: calc(100% - 22px);
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

textarea {
    height: 100px;
}

button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

.error {
    color: red;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <h2>Edit Bike</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="bike_name">Bike Name:</label>
        <input type="text" id="bike_name" name="bike_name" value="<?php echo htmlspecialchars($bike_name); ?>" required>
        <br>
        <label for="model">Model:</label>
        <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($model); ?>" required>
        <br>
        <label for="engine">Engine:</label>
        <input type="text" id="engine" name="engine" value="<?php echo htmlspecialchars($engine); ?>" required>
        <br>
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
        <br>
        <label for="quantity">Quantity:</label> <!-- Fixed typo -->
        <input type="number" id="quantity" name="quantity" min="0" value="<?php echo htmlspecialchars($quantity); ?>" required>
        <br>
        <label for="image">New Bike Image:</label>
        <input type="file" id="image" name="image">
        <br>
        <label for="specifications">Specifications:</label>
        <textarea id="specifications" name="specifications" required><?php echo htmlspecialchars($specifications); ?></textarea>
        <br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
