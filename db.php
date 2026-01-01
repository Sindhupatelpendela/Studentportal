<?php
$conn = new mysqli("localhost", "root", "", "if0_40783545_student_portal");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
