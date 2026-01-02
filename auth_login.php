<?php
require_once "includes/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $expected_role = $_POST['role']; // 'admin' or 'student'

    // Determine redirect pages based on role
    $login_page = ($expected_role == 'admin') ? "login_admin.php" : "login.php";
    $success_page = ($expected_role == 'admin') ? "dashboard.php" : "student_portal.php";

    // Prepare Query
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // 1. Verify Password
        if (password_verify($password, $row['password'])) {
            
            // 2. Strict Role Check (Prevent Student from logging into Admin)
            if ($row['role'] !== $expected_role) {
                header("Location: $login_page?error=Access Denied: Incorrect Role");
                exit;
            }

            // 3. Set Session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            
            if ($row['role'] == 'admin') {
                $_SESSION['admin'] = true;
            }

            header("Location: $success_page");
            exit;
        }
    }

    header("Location: $login_page?error=Invalid Username or Password");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>
