<?php

// I certify that this submission is my own original work - Piyush Malik 

/* This PHP code begins by checking if the user is logged in using session variables. 
If the user is not logged in, it displays a message and a login link. If the user is 
logged in, it establishes a database connection using the provided credentials. It then 
executes an SQL query to fetch all records from the "cars" table. If there are records, 
it displays them in neat tabular format with the columns: ID, Make, Model, Year, Price, 
Trim, and Color. If there are no records, it displays a message indicating such. After 
displaying the records or the no records message, it closes the database connection. The 
code also includes a function to sanitize output to prevent injection attacks. Finally, it 
provides a link to return to the main menu.
*/


session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    echo '<h2>You are logged out. Please login to access this page.</h2>';
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

// SQL query to fetch records (Assuming 'cars' as the application table)
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);

// Check if there are any records
if ($result->num_rows > 0) {
    // Display records in a neat tabular format
    echo "<h2>List of Records</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Make</th><th>Model</th><th>Year</th><th>Price</th><th>Trim</th><th>Color</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . sanitizeOutput($row['id']) . "</td>";
        echo "<td>" . sanitizeOutput($row['make']) . "</td>";
        echo "<td>" . sanitizeOutput($row['model']) . "</td>";
        echo "<td>" . sanitizeOutput($row['year']) . "</td>";
        echo "<td>" . sanitizeOutput($row['price']) . "</td>";
        echo "<td>" . sanitizeOutput($row['trim']) . "</td>";
        echo "<td>" . sanitizeOutput($row['color']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No records found";
}

// Close the database connection
$conn->close();

// Sanitize output to prevent injection attacks
function sanitizeOutput($data) {
    return htmlspecialchars($data);
}
?>

<!-- Link to return to the main menu -->
<br><br>
<a href="mainMenu.php">Back to Main Menu</a>
