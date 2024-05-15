<?php

// I certify that this submission is my own original work - Piyush Malik 

/*

This PHP code creates a car records website and database. It establishes a connection to the MySQL server using the provided server information.

If the database does not exist, it creates the database. If a 'users' table does not exist, it creates the table with columns for username, email, and password. If a 'cars' table does not exist, it creates the table with columns for id (auto-increment primary key), make, model, year, price, trim, and color.

The code also populates the 'cars' table with initial values for Toyota Camry, Honda Accord, and Ford Fusion.

Finally, it closes the database connection.
*/

// Database connection parameters
$servername = "localhost";
$username = "userfa23";
$password = "pwdfa23";
$dbname = "bcs350fa23";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sqlCreateDB) === TRUE) {
    echo "Database created successfully or already exists.\n <br>";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}

// Select the created database
$conn->select_db($dbname);

// Create 'users' table
$sqlCreateUsersTable = "CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($sqlCreateUsersTable) === TRUE) {
    echo "Table 'users' created successfully or already exists.\n <br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "\n";
}

// Create 'cars' table with 'id' as auto-increment primary key
$sqlCreateCarsTable = "CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(255),
    model VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    trim VARCHAR(255) NOT NULL,
    color VARCHAR(255) NOT NULL,
    UNIQUE KEY unique_make_model (make, model)
)";
if ($conn->query($sqlCreateCarsTable) === TRUE) {
    echo "Table 'cars' created successfully or already exists.\n <br>";
} else {
    echo "Error creating table 'cars': " . $conn->error . "\n";
}


// Populate 'cars' table with initial values
$sqlInsertInitialValues = "INSERT INTO cars (make, model, year, price, trim, color) VALUES
    ('Toyota', 'Camry', 2022, 25000.00, 'LE', 'Blue'),
    ('Honda', 'Accord', 2022, 27000.00, 'EX', 'Red'),
    ('Ford', 'Fusion', 2022, 23000.00, 'SE', 'Silver')
    ON DUPLICATE KEY UPDATE
    model=VALUES(model), year=VALUES(year), price=VALUES(price), trim=VALUES(trim), color=VALUES(color)";

if ($conn->query($sqlInsertInitialValues) === TRUE) {
    echo "Initial values inserted into 'cars' table successfully.\n <br>";
} else {
    echo "Error inserting initial values: " . $conn->error . "\n";
}

// Close the database connection
$conn->close();
?>
