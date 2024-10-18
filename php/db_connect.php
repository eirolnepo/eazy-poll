<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);

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

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'survey_db';
$backup_dir = 'backups/';

// Define the path to the MySQL executable
$mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe'; // Adjust this path as necessary
$mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe'; // Path to mysqldump

// Function to export the database
function exportDatabase($host, $username, $password, $dbname, $backup_dir, $mysqldumpPath) {
    // Create a timestamped backup filename
    $backup_file = $backup_dir . $dbname . '_' . date('Y-m-d_H-i-s') . '.sql';

    // Create the backup command
    $command = "$mysqldumpPath --user=$username --password=$password --host=$host $dbname > $backup_file";

    // Execute the command
    system($command, $output);

    if ($output === 0) {
        echo "Database exported successfully to $backup_file<br>";
    } else {
        echo "Error occurred during backup<br>";
    }
}

// Function to import the database
function importDatabase($host, $username, $password, $dbname, $backup_dir, $mysqlPath) {
    // Get the latest backup file
    $files = glob($backup_dir . '*.sql');
    if (empty($files)) {
        echo "No backup files found.<br>";
        return;
    }
    $latest_file = max($files);

    // Create the restore command
    $command = "$mysqlPath --user=$username --password=$password --host=$host $dbname < $latest_file";

    // Execute the command
    system($command, $output);

    if ($output === 0) {
        echo "Database restored successfully from $latest_file<br>";
    } else {
        echo "Error occurred during restoration. Error code: $output.<br>";
    }
}


// Determine the action to take: export or import
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'export') {
        exportDatabase($host, $username, $password, $dbname, $backup_dir, $mysqldumpPath);
    } elseif ($action === 'import') {
        importDatabase($host, $username, $password, $dbname, $backup_dir, $mysqlPath);
    } else {
        echo "Invalid action. Use 'export' or 'import'.<br>";
    }
} else {
    echo "Use ?action=export or ?action=import in the URL. for Importing and Exporting<br>";
}
?>

