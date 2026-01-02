<?php
require_once "includes/config.php";

$user = $_POST['username'];
$pass = $_POST['password'];

// Authenticate against USERS table (Unified Login)
$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($pass, $row['password'])) {
        // Login Success
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        // Role Redirect
        if ($row['role'] == 'admin') {
            $_SESSION['admin'] = true; // Support legacy checks if any
            header("Location: dashboard.php"); // Admin Dashboard
        } else {
            header("Location: student_portal.php"); // Student Dashboard
        }
        exit;
    }
}

header("Location: login.php?error=Invalid Credentials");
exit;
?>
