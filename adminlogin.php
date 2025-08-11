<?php
session_start();

// Check if the admin is already logged in
if(isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php"); // Redirect to dashboard if already logged in
    exit;
}

require("db.php");

// Check if the login form is submitted
if(isset($_POST['login'])){
    $admin_name = $_POST['admin_name'];
    $admin_password = $_POST['admin_password'];

    // SQL injection prevention: use prepared statements
    $query = "SELECT * FROM `admin` WHERE `admin_name` = ? AND `admin_password` = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $admin_name, $admin_password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if login is successful
    if(mysqli_num_rows($result) == 1) {
        session_start();
        $_SESSION['admin_id'] = $admin_name;
        header('location: dashboard.php');
        exit();
    } else {
        $error_message = "Incorrect username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('image/abc.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        .login-form {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            margin: auto;
            width: 300px;
            padding: 50px;
            border-radius: 10px;
            margin-top: 100px;
        }

        h2 {
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            height: 25px;
            border-radius: 5px;
            padding: 4px;
            border: solid thin #aaa;
            margin-bottom: 10px;
            width: 100%;
        }

        button {
            padding: 10px;
            width: 100%;
            color: white;
            background-color: lightblue;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0099cc;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Admin Login Panel</h2>
        <form method="post">
            <label for="admin_name">Admin Name:</label><br>
            <input type="text" id="admin_name" name="admin_name" placeholder="Admin Name"><br>

            <label for="admin_password">Password:</label><br>
            <input type="password" id="admin_password" name="admin_password" placeholder="Password"><br>

            <button type="submit" name="login">Login</button>
        </form>

        <?php if(isset($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>
    </div>
</body>
</html>
