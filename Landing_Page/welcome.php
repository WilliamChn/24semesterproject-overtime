<?php
$servername = "oceanus.cse.buffalo.edu"; // this may vary
$username = "lanakim"; // your database username
$password = "50408039"; // your database password
$dbname = "Landing2";

// Create connection
$conn = new mysqli($oceanus.cse.buffalo.edu, $lanakim, $50408039, $Landing2);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (join_date) VALUES (NOW())");
$stmt->execute();

echo "<h1>Welcome to Overtime!</h1>";

$stmt->close();
$conn->close();
?>
