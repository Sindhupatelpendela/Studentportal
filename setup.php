<?php
$host = "localhost";
$user = "root";
$pass = "";

// Connect to MySQL
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Database
$sql = "CREATE DATABASE IF NOT EXISTS student_portal_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

$conn->select_db("student_portal_db");

// Create 'login' table
$sql = "CREATE TABLE IF NOT EXISTS login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
$conn->query($sql);

// Create 'student' table
$sql = "CREATE TABLE IF NOT EXISTS student (
    college_id VARCHAR(50) PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    College VARCHAR(100),
    Branch VARCHAR(100),
    year VARCHAR(20)
)";
$conn->query($sql);

// Insert Admin User
$username = "admin";
$password = "admin";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if admin exists
$check = $conn->query("SELECT * FROM login WHERE username='$username'");
if ($check->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    if ($stmt->execute()) {
        echo "Admin user created (User: admin, Pass: admin).<br>";
    }
} else {
    echo "Admin user already exists.<br>";
}

echo "Setup complete! <a href='index.php'>Go to Home</a>";
?>
