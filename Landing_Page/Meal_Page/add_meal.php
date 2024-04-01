<?php
session_start(); // Start the session to access the user ID

// Database configuration
$servername = "oceanus.cse.buffalo.edu";
$username = "lanakim";
$password = "50408039";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    die('User not logged in.');
}

$userId = $_SESSION['user_id']; // Retrieve the user ID from the session

// Get the data from the POST request
$itemName = $conn->real_escape_string($_POST['itemName']);
$calories = intval($_POST['calories']); // Ensure that it is an integer
$protein = intval($_POST['protein']);    // Ensure that it is an integer

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO `Meals_Info` (user_id, Food, Protein, Calorie) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isii", $userId, $itemName, $protein, $calories);

// Set parameters and execute
if($stmt->execute()) {
    http_response_code(200); // Success
    echo "New record created successfully";
} else {
    http_response_code(500); // Internal Server Error
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

