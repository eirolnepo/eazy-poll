<?php
    include 'db_connect.php';

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
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(255) NOT NULL,
    lname VARCHAR(255) NOT NULL,
    contact_num VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
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

$sql_create_table_survey = "CREATE TABLE IF NOT EXISTS surveys (
    survey_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description text NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_create_table_survey) === TRUE) {
    // Check if the table was just created (not if it existed before)
    if ($conn->query("SHOW TABLES LIKE 'surveys'")->num_rows == 0) {
        echo "<script>alert('Table \"surveys\" created successfully');</script>";
    }
} else {
    die("Error creating table: " . $conn->error);
}

$sql_create_table_question = "CREATE TABLE IF NOT EXISTS questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    survey_id INT NOT NULL,
    question_text VARCHAR(255) NOT NULL,
    question_type VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_create_table_question) === TRUE) {
    // Check if the table was just created (not if it existed before)
    if ($conn->query("SHOW TABLES LIKE 'questions'")->num_rows == 0) {
        echo "<script>alert('Table \"questions\" created successfully');</script>";
    }
} else {
    die("Error creating table: " . $conn->error);
}

$sql_create_table_choices = "CREATE TABLE IF NOT EXISTS choices (
    choice_id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    choice_text VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_create_table_choices) === TRUE) {
    // Check if the table was just created (not if it existed before)
    if ($conn->query("SHOW TABLES LIKE 'choices'")->num_rows == 0) {
        echo "<script>alert('Table \"choices\" created successfully');</script>";
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

// Define the path to the MySQL and mysqldump executables
$mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe'; // Adjust this path as necessary
$mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe'; // Path to mysqldump

// Function to delete old backup files
function deleteOldBackups($backup_dir) {
    $files = glob($backup_dir . '*.sql');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); // Delete the file
        }
    }
}

// Function to export the database
function exportDatabase($conn, $host, $username, $password, $dbname, $backup_dir, $mysqldumpPath) {
    // Check if the `users` table is empty
    if (isTableEmpty($conn, 'users')) {
        return "Export skipped: The `users` table is empty.";
    }

    // Delete old backups before exporting
    deleteOldBackups($backup_dir);

    // Create a timestamped backup filename
    $backup_file = $backup_dir . $dbname . '_' . date('Y-m-d_H-i-s') . '.sql';

    // Create the backup command
    $command = "$mysqldumpPath --user=$username --password=$password --host=$host $dbname > $backup_file";

    // Execute the command
    system($command, $output);

    if ($output === 0) {
        return "Database exported successfully to $backup_file";
    } else {
        return "Error occurred during backup";
    }
}

// Function to import the database
function importDatabase($host, $username, $password, $dbname, $backup_dir, $mysqlPath) {
    // Get the latest backup file
    $files = glob($backup_dir . '*.sql');
    if (empty($files)) {
        return "No backup files found.";
    }
    $latest_file = max($files);

    // Create the restore command
    $command = "$mysqlPath --user=$username --password=$password --host=$host $dbname < $latest_file";

    // Execute the command
    system($command, $output);

    if ($output === 0) {
        return "Database restored successfully from $latest_file";
    } else {
        return "Error occurred during restoration. Error code: $output.";
    }
}

function dropDatabase($conn, $dbname) {
    $sql_drop_db = "DROP DATABASE IF EXISTS $dbname";
    if ($conn->query($sql_drop_db) === TRUE) {
        return "Database dropped successfully.";
    } else {
        return "Error dropping database: " . $conn->error;
    }
}

function isTableEmpty($conn, $tableName) {
    $result = $conn->query("SELECT COUNT(*) AS count FROM $tableName");
    $row = $result->fetch_assoc();
    return $row['count'] == 0;
}

// Initialize a message variable to display feedback
$message = "";

// Determine the action to take: export or import
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'export') {
        $message = exportDatabase($conn, $host, $username, $password, $dbname, $backup_dir, $mysqldumpPath);
        header("Location: index.php");
    } elseif ($action === 'import') {
        $message = importDatabase($host, $username, $password, $dbname, $backup_dir, $mysqlPath);
        header("Location: index.php");
    } elseif ($action === 'drop') {
        $message .= dropDatabase($conn, $dbname);
        header("Location: index.php?action=import");
    } else {    
        $message = "Invalid action. Use 'export' or 'import'.";
    }
} else {
    $message = "Use ?action=export, ?action=import, or ?action=drop in the URL for Importing, Exporting, and Dropping";
}
?>

