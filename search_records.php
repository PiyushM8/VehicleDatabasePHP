<?php

// I certify that this submission is my own original work - Piyush Malik 


/*
This PHP code handles the search functionality for a user who is logged in. It starts the session and checks if the user is not logged in. If not, it displays a message and a link to the login page.

The code establishes a database connection using the provided server information.

If the HTTP request method is POST, it collects the form data and adjusts the SQL query based on the selected search field. It uses prepared statements to prevent SQL injection and executes the query.

If the query returns results, it outputs the data in a table format. Otherwise, it displays a message indicating no results were found.

The code includes functions to sanitize input and output to prevent injection attacks.

Finally, it closes the prepared statement and the database connection.

The code also includes an HTML form where the user can choose a field to search and enter the information to look up. Upon submission, the form data is sent to the same page.

There is also a link to return to the main menu.
*/

session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    echo '<h2>You are not signed in. Please login to access this page.</h2>';
    echo '<a href="login.html">Login</a>';
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
    $searchField = sanitizeInput($_POST['searchField'], $conn);
    $searchValue = sanitizeInput($_POST['searchValue'], $conn);

    // Adjust the SQL query based on the selected search field
    if ($searchField === "id") {
        $sql = "SELECT * FROM cars WHERE id = ?";
    } else {
        $sql = "SELECT * FROM cars WHERE $searchField = ?";
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchValue);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data in a table format
        echo "<h2>Search Results</h2>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Make</th><th>Model</th><th>Year</th><th>Price</th><th>Trim</th><th>Color</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . sanitizeOutput($row['id']) . "</td><td>" . sanitizeOutput($row['make']) . "</td><td>" . sanitizeOutput($row['model']) . "</td><td>" . sanitizeOutput($row['year']) . "</td><td>" . sanitizeOutput($row['price']) . "</td><td>" . sanitizeOutput($row['trim']) . "</td><td>" . sanitizeOutput($row['color']) . "</td></tr>";
        }

        echo "</table>";
    } else {
        echo "No results found.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();

// Sanitize input to prevent injection attacks
function sanitizeInput($data, $conn) {
    return htmlspecialchars(mysqli_real_escape_string($conn, $data));
}

// Sanitize output to prevent injection attacks
function sanitizeOutput($data) {
    return htmlspecialchars($data);
}
?>

<!-- HTML form to search for a record -->
<h2>Search Records</h2>
<form method="post" action="">
    <label for="searchField">Choose a field to search:</label>
    <select name="searchField">
        <option value="id">ID</option>
        <option value="make">Make</option>
        <option value="model">Model</option>
        <option value="year">Year</option>
        <option value="price">Price</option>
        <option value="trim">Trim</option>
        <option value="color">Color</option>
    </select><br>

    <label for="searchValue">Enter information to look up:</label>
    <input type="text" name="searchValue" required><br>

    <input type="submit" value="Search">
</form>

<!-- Link to return to the main menu -->
<br><br>
<a href="mainMenu.php">Back to Main Menu</a>
