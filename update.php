<?php
include "db.php";
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM student WHERE id=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$s = $stmt->get_result()->fetch_assoc();

if (isset($_POST['update'])) {
$stmt = $conn->prepare("UPDATE student SET Name=?, Branch=?, year=? WHERE id=?");
    $stmt->bind_param("ssss", $_POST['name'], $_POST['branch'], $_POST['year'], $id);
    $stmt->execute();
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>
<head><title>Update</title></head>
<body>
<div class="box">
<h2>Update Student</h2>
<form method="post">
<input name="name" value="<?= $s['Name'] ?>">
<input name="branch" value="<?= $s['Branch'] ?>">
<input name="year" value="<?= $s['year'] ?>">
<button name="update">Update</button>
</form>
</div>
</body>
</html>
