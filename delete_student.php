<?php
require_once "includes/config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Deleting from users table will cascade delete the profile because of foreign key constraint
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit;
?>
