<?php
include "db.php";
$id=$_GET['id'];
$stmt = $conn->prepare("DELETE FROM student WHERE college_id=?");
$stmt->bind_param("s", $id);
$stmt->execute();
header("Location: dashboard.php");
