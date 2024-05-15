<?php

// I certify that this submission is my own original work - Piyush Malik 

/*
This PHP code handles user logout functionality. It starts by starting the session and then destroying it using the session_destroy() function. This clears all session data and effectively logs the user out.

After destroying the session, the code redirects the user to the login.html page using the header() function. This ensures that the user is redirected to the login page after logging out.

Finally, the exit() function is called to terminate the script execution.

*/

session_start();
session_destroy();
header("Location: login.html"); // Updated this line to point to login.html
exit();
?>
