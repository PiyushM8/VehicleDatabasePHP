<?php

// I certify that this submission is my own original work - Piyush Malik 

/*
This PHP code handles a main menu page that is only accessible to logged-in users. It starts by starting the session.

The code checks if the user is logged in by checking if the 'username' session variable is set. If it is not set, the code redirects the user to the login page using the header() function. The exit() function is then called to terminate the script execution.

If the user is logged in, the code checks if the form has been submitted for user logout. If it has, the code unsets all session variables, destroys the session, and redirects the user to the login page with a logout message as a query parameter in the URL.

The code then renders an HTML page that displays a main menu. It includes a form with a logout button, as well as links to other sections of the application.

The code also includes a script that checks if the logout message query parameter is present in the URL. If it is, it displays a success message on the page for 3 seconds before removing it.

*/

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}

// Check if the form is submitted for user logout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page with a logout message
    header("Location: login.html?logout=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        a {
            display: block;
            margin: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #45a049;
        }

        .logout-btn {
            background-color: red;
        }

        .back-to-login {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Main Menu</h1>
    <form method="post" action="">
        <button type="submit" class="btn btn-primary logout-btn" name="logout">Logout</button>
    </form>
    <a href="list_records.php">List Records</a>
    <a href="add_records.php">Add Records</a>
    <a href="search_records.php">Search for Records</a>
    <a href="delete_records.php">Delete Records</a>
    
    <script>
        // Check if the logout message query parameter is present
        var urlParams = new URLSearchParams(window.location.search);
        var logoutParam = urlParams.get('logout');

        if (logoutParam === '1') {
            // Display the logout message
            var logoutMsg = document.createElement('div');
            logoutMsg.className = 'container mt-3 alert alert-success';
            logoutMsg.textContent = 'Logout successful!';
            document.body.appendChild(logoutMsg);

            // Remove the message after a delay
            setTimeout(function() {
                logoutMsg.style.display = 'none';
            }, 3000); // 3000 milliseconds (3 seconds)
        }
    </script>
</body>
</html>