<?php

// I certify that this submission is my own original work - Piyush Malik 

/* This module checks if a user is logged in using session variables. If the user is not 
logged in, it displays a message and a login link. If the user is logged in, it establishes
a database connection and checks if a record ID is provided for deletion. If a record ID is
provided, it calls the function deleteRecord() to delete the corresponding record from the 
database. It then calls the function displayRecords() to display the existing records in a 
container with their details and a "Delete Record" button for each. The displayed records 
are fetched from the "cars" table in the database. The code also includes functions to 
sanitize input and output to prevent injection attacks. Finally, it provides a link to 
return to the main menu.
*/

session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    echo '<h2>You are not signed in. Please login to access this page.</h2>';
    echo '<a href="login.html">Login</a>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Adding the Bootstrap CDN link here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Delete Records</title>
    <style>
        .record-container {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <?php
        // Establish a database connection
        $servername = "localhost";
        $username = "userfa23";
        $password = "pwdfa23";
        $dbname = "bcs350fa23";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if a record ID is provided for deletion
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recordId'])) {
            deleteRecord($conn, $_POST['recordId']);
        }

        // Display records and provide a "Delete Record" button for each
        displayRecords($conn);

        // Close the database connection
        $conn->close();

        // Function to delete a record
        function deleteRecord($conn, $recordId) {
            $deleteSql = "DELETE FROM cars WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $recordId);
            $deleteStmt->execute();
            $deleteStmt->close();
            echo '<div class="alert alert-success" role="alert">Record deleted successfully.</div>';
        }

        // Function to display records
        function displayRecords($conn) {
            $sql = "SELECT * FROM cars";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="record-container">';
                    echo '<p><strong>ID:</strong> ' . sanitizeOutput($row['id']) . '</p>';
                    echo '<p><strong>Make:</strong> ' . sanitizeOutput($row['make']) . '</p>';
                    echo '<p><strong>Model:</strong> ' . sanitizeOutput($row['model']) . '</p>';
                    echo '<p><strong>Year:</strong> ' . sanitizeOutput($row['year']) . '</p>';
                    echo '<p><strong>Price:</strong> ' . sanitizeOutput($row['price']) . '</p>';
                    echo '<p><strong>Trim:</strong> ' . sanitizeOutput($row['trim']) . '</p>';
                    echo '<p><strong>Color:</strong> ' . sanitizeOutput($row['color']) . '</p>';
                    echo '<form method="post" action="delete_records.php">';
                    echo '<input type="hidden" name="recordId" value="' . sanitizeOutput($row['id']) . '">';
                    echo '<button type="submit" class="btn btn-danger">Delete Record</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo '<div class="alert alert-info" role="alert">No records found.</div>';
            }
        }

        // Function to sanitize input to prevent injection attacks
        function sanitizeInput($data, $conn) {
            return htmlspecialchars(mysqli_real_escape_string($conn, $data));
        }

        // Function to sanitize output to prevent injection attacks
        function sanitizeOutput($data) {
            return htmlspecialchars($data);
        }
        ?>
    </div>
    <!-- Link to return to the main menu -->
    <div class="container">
        <a href="mainMenu.php" class="btn btn-primary">Back to Main Menu</a>
    </div>
</body>
</html>
