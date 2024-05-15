<?php

// I certify that this submission is my own original work - Piyush Malik 

/* 

This PHP code handles user login. It establishes a database connection and checks if the 

form has been submitted. If it has, it retrieves the username and password values and 

performs validation. If the username exists, it verifies the password and redirects the 

user. If the credentials are incorrect, an error message is displayed. It also includes a 

function to sanitize input data and closes the database connection.

*/

session_start();

// Database connection parameters
$servername = "localhost";
$username = "userfa23";
$password = "pwdfa23";
$dbname = "bcs350fa23";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted for user login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    // Client-side validation
    if (strlen($password) === 0) {
        echo '<div class="container mt-3 alert alert-danger">Password cannot be empty. Please try again.</div>';
    } else {
        // Server-side validation
        // Check if the username exists
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                header("Location: mainMenu.php");
                exit(); // Ensure that no further code is executed after redirection
            } else {
                echo '<div class="container mt-3 alert alert-danger"><h2>Incorrect username or password. Please try again.</h2> <br><h3><a href="login.html">Click here to login</a>.</h3></div>';
            }
        } else {
            echo '<div class="container mt-3 alert alert-danger"><h2>Incorrect username or password. Please try again.</h2> <br><h3><a href="login.html">Click here to login</a>.</h3></div>';
        }

        $stmt->close();
    }
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

$conn->close();
?>
