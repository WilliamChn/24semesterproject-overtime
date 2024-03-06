<?php
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

// Get the data from the POST request
$itemName = $conn->real_escape_string($_POST['itemName']);
$calories = intval($_POST['calories']); // Ensure that it is an integer
$protein = intval($_POST['protein']);    // Ensure that it is an integer

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO `Meals_Info` (Food, Protein, Calorie) VALUES (?, ?, ?)");
$stmt->bind_param("sii", $itemName, $protein, $calories);

// Set parameters and execute
if($stmt->execute()) {
    http_response_code(200); // Explicitly setting the response code
    echo "New records created successfully";
} else {
    http_response_code(500); // Internal Server Error
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
