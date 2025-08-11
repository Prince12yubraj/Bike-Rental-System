<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
      body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 10px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
         
    </style>
</head>
<body>
    <nav>
        <!-- Navigation links -->
    </nav>
    <main>
        <div class="payment-details">
            <h2>Payment Details</h2>
            <?php
            // Check if payment URLs are present in $_GET
            if (isset($_GET['urls'])) {
                $paymentUrls = $_GET['urls'];

                // Loop through each payment URL
                foreach ($paymentUrls as $paymentUrl) {
                    // Parse the URL to extract parameters
                    $params = parse_url($paymentUrl, PHP_URL_QUERY);
                    parse_str($params, $bookingDetails);

                    // Check if booking details exist for this URL
                    if (isset($bookingDetails['bike_name']) && isset($bookingDetails['quantity']) && isset($bookingDetails['subtotal'])) {
                        $count = count($bookingDetails['bike_name']);

                        // Loop through each booking detail
                        for ($i = 0; $i < $count; $i++) {
                            $name = $bookingDetails['name'];
                            $bike_name = $bookingDetails['bike_name'][$i];
                            $quantity = $bookingDetails['quantity'][$i];
                            $subtotal = $bookingDetails['subtotal'][$i];

                            // Display booking details
                            echo "<p>Name: " . htmlspecialchars($name) . "</p>";
                            echo "<p>Bike Name: " . htmlspecialchars($bike_name) . "</p>";
                            echo "<p>Quantity: " . htmlspecialchars($quantity) . "</p>";
                            echo "<p>Subtotal: Rs " . htmlspecialchars($subtotal) . "</p>";
                            echo "<hr>";
                        }
                    } else {
                        echo "<p>No booking details found.</p>";
                    }
                }
            } else {
                echo "<p>No booking details found.</p>";
            }
            ?>
            <a href="dashboard.php"><button type="submit">Proceed to Payment</button></a>
        </div>
    </main>
    <footer>
        <!-- Footer content -->
    </footer>
</body>
</html>
