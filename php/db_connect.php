<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS survey_db";
if ($conn->query($sql_create_db) === TRUE) {
    // Check if the database was just created (not if it existed before)
    if ($conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'survey_db'")->num_rows == 0) {
        echo "<script>alert('Database created successfully');</script>";
    }
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db("survey_db");

// Create table if it doesn't exist
$sql_create_table = "CREATE TABLE IF NOT EXISTS users (
    user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    pass VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_create_table) === TRUE) {
    // Check if the table was just created (not if it existed before)
    if ($conn->query("SHOW TABLES LIKE 'users'")->num_rows == 0) {
        echo "<script>alert('Table \"users\" created successfully');</script>";
    }
} else {
    die("Error creating table: " . $conn->error);
}

// Close the connection
$conn->close();
?>
