<?php
// Retrieve user input safely
$name = filter_input(INPUT_POST, "p_name", FILTER_SANITIZE_STRING);
$age = filter_input(INPUT_POST, "p_age", FILTER_SANITIZE_NUMBER_INT); 
$weight = filter_input(INPUT_POST, "p_weight", FILTER_SANITIZE_NUMBER_INT);
$height = filter_input(INPUT_POST, "p_height", FILTER_SANITIZE_NUMBER_INT);

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
$sql = "INSERT INTO `Personal_Info` (p_name, p_age, p_weight, p_height) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
// Check if the statement was prepared correctly
if (!$stmt) {
    die("Statement preparation failed: " . $conn->error);
}
// Bind the parameters to the SQL query
$stmt->bind_param("siii", $name, $age, $weight, $height);
// Execute the statement and check if it was successful
if ($stmt->execute()) {
    // Redirect to the profile page upon successful record creation
    header("Location: ../Profile_Page/index.html");
    exit; // Ensure no further execution if redirect is successful
} else {
    echo "Error: " . $stmt->error;
}
// Close statement and connection
$stmt->close();
$conn->close();
?>
