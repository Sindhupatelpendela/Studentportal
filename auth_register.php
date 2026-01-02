<?php
require_once "includes/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'student';

    // 1. Check duplicates
    $check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        header("Location: register.php?error=Username or Email already exists");
        exit;
    }

    // 2. Create User
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        $stmt->execute();
        $user_id = $conn->insert_id;

        // 3. Create Profile Placeholder
        $stmt2 = $conn->prepare("INSERT INTO student_profiles (user_id, full_name, college_id) VALUES (?, ?, ?)");
        $stmt2->bind_param("iss", $user_id, $name, $college_id);
        $stmt2->execute();

        $conn->commit();
        header("Location: login.php?success=Account created! Please login.");
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: register.php?error=System Error: " . $e->getMessage());
    }
}
?>
