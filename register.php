<?php

// I certify that this submission is my own original work - Piyush Malik 

/*
This PHP code handles user registration. It starts the session and establishes a database connection using the provided server information.

The "registerUser" function is responsible for validating the form data entered by the user. It checks if the email is associated with a valid domain, if the password and confirm password match, if the username has a length of at least 6 characters, and if the password meets the required pattern.

If the client-side validation fails, the function returns an error message. If the server-side validation fails, it checks if the username is available and returns the corresponding error message.

If all validations pass, the function stores the user's data after hashing the password, and if successful, returns true.

The "isValidDomain" function checks if the email ends with a valid domain from the "validDomains" array.

The "endsWith" function checks if a string ends with a given substring.

If the HTTP request method is POST and the registration button is clicked, the "registerUser" function is called. If successful, a success message is displayed; otherwise, an error message is displayed.

The "isUsernameAvailable" function checks if a given username is available in the database.

The "sanitizeInput" function sanitizes the input data.

Finally, the database connection is closed.
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

function registerUser($conn) {
    $email = sanitizeInput($_POST['email']);
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Client-side validation
    if (!isValidDomain($email)) {
        return '<div class="container mt-3 alert alert-danger">Email must end with a valid domain name. <a href="register.html">Click here to go back to the registration page.</a></div>';
    }

    if ($password !== $confirmPassword) {
        return '<div class="container mt-3 alert alert-danger">Password and Confirm Password must match.</div>';
    }

    // Additional validation
    if (strlen($username) < 6) {
        return '<div class="container mt-3 alert alert-danger">Username must be at least 6 characters long. <a href="register.html">Click here to go back to the registration page.</a></div>';
    }

    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
    if (!preg_match($passwordRegex, $password)) {
        return '<div class="container mt-3 alert alert-danger">Password must contain at least 8 characters with at least one lowercase letter, one uppercase letter, and one numeric digit. <a href="register.html">Click here to go back to the registration page.</a></div>';
    }

    // Server-side validation
    if (isUsernameAvailable($conn, $username)) {
        // Hash and salt the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Save user data to the database
        $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $username, $hashedPassword);

        if ($stmt->execute()) {
            return true;
        } else {
            return '<div class="container mt-3 alert alert-danger"><h2>Error in registration.</h2> <br> <a href="register.html"><h3>Click here to go back to the registration page.<h3></a></div>';
        }
    } else {
        return '<div class="container mt-3 alert alert-danger"><h2>Username is not available. Please choose another username.</h2> <br> <a href="register.html"><h3>Click here to go back to the registration page.<h3></a></div>';
    }
}

function isValidDomain($email) {
    $validDomains = [".com", ".org", ".net", ".edu", ".Co"]; // Add more valid domain names as needed

    foreach ($validDomains as $domain) {
        if (endsWith($email, $domain)) {
            return true;
        }
    }

    return false;
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $result = registerUser($conn);

    if ($result === true) {
        echo '<div class="container mt-3 alert alert-success"><h2>Registration successful.</h2> <br> <h3><a href="login.html">Click here to login.</a></h3></div>';
    } else {
        // Display error message
        echo $result;
    }
}

// Function to check if the username is available
function isUsernameAvailable($conn, $username) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows === 0;
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

$conn->close();
?>
