<?php
session_start();

// Check if the user is logged in and has an ID set in the session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Retrieve user input safely
$name = filter_input(INPUT_POST, "p_name", FILTER_SANITIZE_STRING);
$dob = filter_input(INPUT_POST, "p_dob", FILTER_SANITIZE_STRING);
$weight = filter_input(INPUT_POST, "p_weight", FILTER_SANITIZE_NUMBER_INT);
$height = filter_input(INPUT_POST, "p_height", FILTER_SANITIZE_NUMBER_INT);
$userId = $_SESSION['user_id']; // Retrieve the user ID from the session


// Database credentials should be secured
$servername = "oceanus.cse.buffalo.edu";
$username = "ziangche";
$password = "50415271";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement with placeholders
$sql = "INSERT INTO `Personal_Info` (user_id, p_name, p_dob, p_weight, p_height) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared correctly
if (!$stmt) {
    die("Statement preparation failed: " . $conn->error);
}

// Bind the parameters to the SQL query including the user_id
$stmt->bind_param("issii", $userId, $name, $dob, $weight, $height);

// Execute the statement and check if it was successful
if ($stmt->execute()) {
    // Redirect to the profile page upon successful record creation
    header("Location: ../Profile_Page/index.html");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>