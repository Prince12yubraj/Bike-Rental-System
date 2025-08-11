<?php
session_start();
include('db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Check if email already exists
        $check_query = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($con, $check_query);
        if (mysqli_num_rows($result) > 0) {
            echo "<script type='text/javascript'>alert('Email is already registered!')</script>";
        } else {
            $query = "INSERT INTO user(first_name,last_name,address,phone_number,email,password) VALUES('$first_name','$last_name','$address','$phone_number','$email','$password')";
            mysqli_query($con, $query);
            echo "<script type='text/javascript'>alert('Successfully Registered')</script>";
            // Redirect to login page
            header('Location: login.php');
            exit();
        }
    } else {
        echo "<script type='text/javascript'>alert('Please enter some valid information!')</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <style type="text/css">
        .signup {
            text-align: center;
            background-color: white;
            margin: auto;
            width: 300px;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .signup h1 {
            margin-bottom: 20px;
        }

        .signup input[type="text"],
        .signup input[type="email"],
        .signup input[type="password"],
        .signup input[type="tel"] {
            width: calc(100% - 22px); /* Adjusted width to accommodate padding */
            padding: 10px;
            margin-bottom: 20px; /* Increased margin for better spacing */
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .signup button {
            width: 100%;
            padding: 10px;
            background-color: #45a049;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signup button:hover {
            background-color: #45a049;
        }

        .signup p {
            margin-top: 15px;
        }

        .signup p a {
            color: #45a049;
            text-decoration: none;
        }

        .signup p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class= "signup">
        <form method="post">
            <h1>Signup</h1>
            <label>First Name: <input type="text" name="first_name" placeholder="Enter your first name" required></label><br><br>
            <label>Last Name: <input type="text" name="last_name" placeholder="Enter your last name" required></label><br><br>
            <label>Address: <input type="text" name="address" placeholder="Enter your address" required></label><br><br>
            <label>Phone Number: <input type="tel" name="phone_number" placeholder="Enter your phone number" required></label><br><br>
            <label>Email: <input type="email" name="email" placeholder="Enter your email" required></label><br><br>
            <label>Password: <input type="password" name="password" placeholder="Enter your password" required></label><br><br>
            <button>Signup</button> <br><br>
        </form>
        <p>Already have an account? <a href="login.php">Login Here</a></p>
    </div>
</body>
</html>
