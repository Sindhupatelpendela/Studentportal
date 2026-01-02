<?php
include "db.php";

if (isset($_POST['add'])) {
$stmt = $conn->prepare("INSERT INTO student (college_id, Name, Branch, year) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $_POST['college_id'], $_POST['name'], $_POST['branch'], $_POST['year']);
    $stmt->execute();
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head><title>Add Student</title></head>
<body>
<div class="box">
<h2>Add Student</h2>
<form method="post">
<input name="college_id" placeholder="College ID" required>
<input name="name" placeholder="Name" required>
<input name="branch" placeholder="Branch" required>
<input name="year" placeholder="Year" required>
<button name="add">Add</button>
</form>
</div>
</body>
</html>
