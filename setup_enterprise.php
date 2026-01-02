<?php
require_once "includes/config.php";

echo "<h1>NRSC Portal - Enterprise Setup</h1>";

// 1. Users Table (Authentication)
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if($conn->query($sql)) echo "✅ Users table ready.<br>";
else echo "❌ Error: " . $conn->error . "<br>";

// 2. Student Profiles Table (Detailed Portal Info)
$sql = "CREATE TABLE IF NOT EXISTS student_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    college_id VARCHAR(50) UNIQUE NOT NULL,
    branch VARCHAR(100),
    year VARCHAR(20),
    dob DATE,
    phone VARCHAR(15),
    address TEXT,
    bio TEXT,
    profile_pic VARCHAR(255) DEFAULT 'default_avatar.png',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if($conn->query($sql)) echo "✅ Profiles table ready.<br>";
else echo "❌ Error: " . $conn->error . "<br>";

// 3. Create Default Admin (if not exists)
$admin_user = 'admin';
$admin_pass = password_hash('admin', PASSWORD_DEFAULT);
$check = $conn->query("SELECT * FROM users WHERE role='admin'");
if ($check->num_rows == 0) {
    // Insert Admin
    $conn->query("INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@nrsc.gov.in', '$admin_pass', 'admin')");
    echo "✅ Super Admin created (User: admin, Pass: admin).<br>";
}

echo "<hr><h3>Setup Complete.</h3> <a href='login.php'>Go to Login</a>";
?>
