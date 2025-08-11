<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to login page if not logged in
    exit;
}
// Include database connection file
include('db.php');

// Check for database connection errors
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Initialize variables for form submission
$bike_name = $model = $engine = $price = $quantity = $specifications = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $bike_name = $_POST['bike_name'];
    $model = $_POST['model'];
    $engine = $_POST['engine'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $specifications = $_POST['specifications'];

    // Handle image upload
    $target_dir = "image/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        $error = "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $error = "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert bike details into the database
            $sql = "INSERT INTO bikes (bike_name, model, engine, price, quantity, specifications, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssiiss", $bike_name, $model, $engine, $price, $quantity, $specifications, $target_file);

            if ($stmt->execute()) {
                // Redirect to the manage bikes page after successful insertion
                header("Location: managebike.php");
                exit();
            } else {
                $error = 'Error adding bike: ' . $con->error;
            }
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Bike</title>
    <style>
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
        h3 {
            text-align: center;
            margin-bottom: 20px;
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
            width: 100%;
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
        p.error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Add New Bike</h3>
        <!-- Display error message if any -->
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <!-- Add bike form -->
        <form method="post" enctype="multipart/form-data">
            <label for="bike_name">Bike Name:</label>
            <input type="text" id="bike_name" name="bike_name" value="<?php echo $bike_name; ?>" required>
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" value="<?php echo $model; ?>" required>
            <label for="engine">Engine:</label>
            <input type="text" id="engine" name="engine" value="<?php echo $engine; ?>" required>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" min="0" step="any" value="<?php echo $price; ?>" required>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="0" value="<?php echo $quantity; ?>" required>
            <label for="specifications">Specifications:</label>
            <textarea id="specifications" name="specifications" required><?php echo $specifications; ?></textarea>
            <label for="image">Bike Image:</label>
            <input type="file" id="image" name="image" required>
            <button type="submit" name="submit">Add Bike</button>
        </form>
    </div>
</body>
</html>

<?php
// Close database connection
$con->close();
?>
