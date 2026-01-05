<?php
$conn = new mysqli('localhost', 'root', '');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($conn->query("CREATE DATABASE IF NOT EXISTS student_portal_db") === TRUE) {
    echo "Database created or already exists.\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
}
$conn->close();
?>
