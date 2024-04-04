<?php
// Database configuration
session_start();
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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id']; // The user's ID from the session

// SQL to delete all rows from Meals_Info for the logged-in user
$sql = "DELETE FROM Meals_Info WHERE user_id = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);

// Execute and check success
if ($stmt->execute()) {
    echo "All meals for user deleted successfully";
} else {
    echo "Error deleting meals: " . $stmt->error;
}

$stmt->close();
$conn->close();