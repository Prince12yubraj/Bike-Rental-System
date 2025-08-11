<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>About us</title>
	<style type="text/css">
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
	<h2>About us</h2>
	Welcome to our bikerental system.
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