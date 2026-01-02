<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Railway Diagnostic Tool</h1>";

$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$name = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

echo "<p>Checking Environment Variables...</p>";
echo "<pre>";
echo "HOST: $host\n";
echo "USER: $user\n";
echo "NAME: $name\n";
echo "PORT: $port\n";
echo "</pre>";

if (!$host) {
    die("❌ Error: Railway Environment Variables are missing. Did you add the MySQL Service?");
}

echo "<p>Attempting Connection...</p>";

try {
    $conn = new mysqli($host, $user, $pass, $name, $port);
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }
    echo "<h2 style='color:green'>✅ Connection Successful!</h2>";
    echo "<p>Database '$name' is accessible.</p>";
    
    // Check tables
    $res = $conn->query("SHOW TABLES");
    echo "<h3>Existing Tables:</h3><ul>";
    while($row = $res->fetch_row()) {
        echo "<li>{$row[0]}</li>";
    }
    echo "</ul>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ Connection Failed</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>
