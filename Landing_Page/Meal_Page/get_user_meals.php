<?php
// get_user_meals.php

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

// Prepare the SELECT statement
$stmt = $conn->prepare("SELECT Food FROM `Meals_Info` WHERE user_id = ?");
$stmt->bind_param("i", $userId);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$meals = [];
while ($row = $result->fetch_assoc()) {
    $meals[] = $row;
}

$stmt->close();
$conn->close();

// Output the meals as JSON
header('Content-Type: application/json');
echo json_encode($meals);
?>