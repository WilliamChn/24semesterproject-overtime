<?php
// Database configuration
$servername = "oceanus.cse.buffalo.edu";
$username = "lanakim";
$password = "50408039";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to delete all rows from Meals_Info
$sql = "DELETE FROM Meals_Info";

if ($conn->query($sql) === TRUE) {
    echo "All meals deleted successfully";
} else {
    echo "Error deleting meals: " . $conn->error;
}

$conn->close();
?>