<?php
// includes/config.php
// NRSC ENTERPRISE PORTAL - InfinityFree Version

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// ========================================
// DATABASE CONFIGURATION
// Fill in YOUR InfinityFree credentials below:
// ========================================
// AUTOMATIC RAILWAY CONFIG
// Since standard env vars are failingDNS resolution, we parse the full connection URL if available.
if (getenv('MYSQL_URL')) {
    $url = parse_url(getenv('MYSQL_URL'));
    $db_host = $url["host"];
    $db_user = $url["user"];
    $db_pass = $url["pass"];
    $db_name = substr($url["path"], 1);
    $db_port = $url["port"];
} else {
    // Fallback to manual variables
    $db_host = getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: "localhost";
    $db_user = getenv('DB_USER') ?: getenv('MYSQLUSER') ?: "root";
    $db_pass = getenv('DB_PASS') ?: getenv('MYSQLPASSWORD') ?: "";
    $db_name = getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: "student_portal_db";
    $db_port = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: 3306;
}

if (empty($db_port)) $db_port = 3306;
// ========================================

// Connect to Database
// DEBUG: Uncomment to see what host is actually being used
// echo "DEBUG: Host: $db_host | Port: $db_port<br>";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, (int)$db_port);

if ($conn->connect_error) {
    die("
    <div style='font-family:Arial;max-width:500px;margin:50px auto;padding:20px;border:2px solid red;background:#ffe0e0;'>
        <h2 style='color:red;'>Database Connection Error</h2>
        <p>Could not connect to the database.</p>
        <p><strong>Error:</strong> " . htmlspecialchars($conn->connect_error) . "</p>
        <hr>
        <p>Please check your database credentials in <code>includes/config.php</code></p>
    </div>
    ");
}

// Create Tables if they don't exist
$check = $conn->query("SHOW TABLES LIKE 'users'");
if ($check && $check->num_rows == 0) {
    // Create Users Table
    $conn->query("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create Student Profiles Table
    $conn->query("CREATE TABLE student_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL UNIQUE,
        full_name VARCHAR(100) NOT NULL,
        college_id VARCHAR(50) UNIQUE NOT NULL,
        branch VARCHAR(100),
        year VARCHAR(20),
        dob DATE,
        phone VARCHAR(20),
        address TEXT,
        bio TEXT,
        profile_pic VARCHAR(255) DEFAULT 'default.png',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    
    // Create Default Admin User
    $admin_pass = password_hash('admin', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@nrsc.gov.in', '$admin_pass', 'admin')");
}

function sanitize($conn, $input) {
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($input))));
}
?>
