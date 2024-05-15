<?php

// I certify that this submission is my own original work - Piyush Malik 

/* This PHP code handles user registration for a car database application. 
It checks if the user is logged in; if not, it prompts them to sign in. 
Upon form submission, it validates and sanitizes input data, checks for duplicate records 
in the database, and either inserts a new car record or displays an error message. 
The code integrates Bootstrap for styling and includes JavaScript validation for email 
format and password matching. The user-friendly interface allows users to add car records 
with attributes such as make, model, year, price, trim, and color, providing a link to 
return to the main menu after completion.
*/

session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    echo '<div class="container mt-3"><h2>You are not signed in. Please login to access this page.</h2>';
    echo '<a href="login.html" class="btn btn-primary">Login</a></div>';
    exit();
}

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $id = sanitizeInput($_POST['id'], $conn); // Add this line to get the id
    $make = sanitizeInput($_POST['make'], $conn);
    $model = sanitizeInput($_POST['model'], $conn);
    $year = sanitizeInput($_POST['year'], $conn);
    $price = sanitizeInput($_POST['price'], $conn);
    $trim = sanitizeInput($_POST['trim'], $conn);
    $color = sanitizeInput($_POST['color'], $conn);

    // Check if a record with the same make, model, year, price, trim, and color already exists
    $checkDuplicateSql = "SELECT * FROM cars WHERE make = ? AND model = ? AND year = ? AND price = ? AND trim = ? AND color = ?";
    $checkDuplicateStmt = $conn->prepare($checkDuplicateSql);
    $checkDuplicateStmt->bind_param("ssssss", $make, $model, $year, $price, $trim, $color);
    $checkDuplicateStmt->execute();

    $existingRecord = $checkDuplicateStmt->get_result()->fetch_assoc();
    $checkDuplicateStmt->close();

    if ($existingRecord) {
        // Record with the same attributes already exists, display an error
        echo '<div class="container mt-3 alert alert-danger">Error adding record: A record with the same make, model, year, price, trim, and color already exists.</div>';
    } else {
        // No record with the same attributes, proceed with the insertion
        $insertSql = "INSERT INTO cars (id, make, model, year, price, trim, color) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("issssss", $id, $make, $model, $year, $price, $trim, $color);

        if ($insertStmt->execute()) {
            echo '<div class="container mt-3 alert alert-success">Record added successfully</div>';
        } else {
            echo '<div class="container mt-3 alert alert-danger">Error adding record: ' . $insertStmt->error . '</div>';
        }

        $insertStmt->close();
    }
}

// Close the database connection
$conn->close();

// Sanitize input to prevent injection attacks
function sanitizeInput($data, $conn) {
    return htmlspecialchars(mysqli_real_escape_string($conn, $data));
}
?>

<!-- Bootstrap-formatted HTML form to add a record -->
<div class="container mt-5">
    <h2>Add a Record</h2>
    <form method="post" action="">
    
        <div class="form-group">
            <label for="make">Make:</label>
            <input type="text" class="form-control" name="make" required>
        </div>

        <div class="form-group">
            <label for="model">Model:</label>
            <input type="text" class="form-control" name="model" required>
        </div>

        <div class="form-group">
            <label for="year">Year:</label>
            <input type="text" class="form-control" name="year" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" class="form-control" name="price" required>
        </div>

        <div class="form-group">
            <label for="trim">Trim:</label>
            <input type="text" class="form-control" name="trim" required>
        </div>

        <div class="form-group">
            <label for="color">Color:</label>
            <input type="text" class="form-control" name="color" required>
        </div>

        <button type="submit" class="btn btn-primary" name="addRecord">Add Record</button>
    </form>
</div>

<!-- Link to return to the main menu -->
<div class="container mt-3">
    <a href="mainMenu.php" class="btn btn-secondary">Back to Main Menu</a>
</div>
