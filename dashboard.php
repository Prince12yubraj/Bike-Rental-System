<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php"); // Redirect to login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            margin-bottom: 10px;
        }

        header {
            background-color: #4a90e2;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: fixed;
             top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
        }

        .logout-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .logout-container button {
            background-color: black;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-container button:hover {
            background-color: #333;
        }

        nav {
            background-color: #1664a5;
            padding: 20px;
            width: 200px;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            margin-bottom: 10px;
        }

        nav ul li a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav ul li a:hover {
            background-color: #2b78e4;
        }

        main {
            margin-left: 220px;
            padding: 20px;
            max-width: 800px;
            margin-right: auto;
            margin-left: auto;
        }

        .card {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            max-width: 300px;
            margin-right: auto;
            margin-left: auto;
        }

        .card h2 {
            color: #333;
            font-size: 24px;
        }

        .card p {
            color: #666;
            margin-bottom: 20px;
        }

        .card a {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            background-color: #4a90e2;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .card a:hover {
            background-color: #357ed2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <div class="logout-container">
            <button onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </header>
    <nav>
        <ul>
            <br>
            <li><a href="managebike.php">Manage Bikes</a></li>
            <li><a href="managebooking.php">Manage Bookings</a></li>
            <li><a href="addbike.php">Add New Bike</a></li>
            <li><a href="bikedetail.php">View Available Bikes</a></li>
            <li><a href="bookingreport.php">Booking Reports</a></li>
        </ul>
    </nav>
    <main>
        <br><br><br>
        <section class="card">
            <h2>Manage Bikes</h2>
            <p>View, edit, and delete existing bikes.</p>
            <a href="managebike.php">Go to Manage Bikes</a>
        </section>

        <section class="card">
            <h2>View Available Bikes</h2>
            <p>Check the list of available bikes for rental.</p>
            <a href="bikedetail.php">Go to View Available Bikes</a>
        </section>

        <section class="card">
            <h2>Add New Bike</h2>
            <p>Add a new bike to the inventory.</p>
            <a href="addbike.php">Go to Add New Bike</a>
        </section>

        <section class="card">
            <h2>Manage Users</h2>
            <p>View and manage user accounts.</p>
            <a href="manageuser.php">Go to Manage Users</a>
        </section>
    </main>
</body>
</html>
