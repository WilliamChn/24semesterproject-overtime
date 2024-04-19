<?php
// Database credentials
session_start();
$servername = "oceanus.cse.buffalo.edu";
$username = "ziangche";
$password = "50415271";
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

// SQL to delete the most recent row for the logged-in user
$sql = "DELETE FROM Meals_Info WHERE user_id = ? ORDER BY id DESC LIMIT 1"; // assuming 'id' is your auto-increment column

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);

// Execute and check success
if ($stmt->execute()) {
    echo "Most recent meal for user deleted successfully";
} else {
    echo "Error deleting meal: " . $stmt->error;
}

$stmt->close();
$conn->close();