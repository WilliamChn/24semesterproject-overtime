<?php
// Retrieve user input safely
$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
$age = filter_input(INPUT_POST, "age", FILTER_SANITIZE_NUMBER_INT); 
$weight = filter_input(INPUT_POST, "weight", FILTER_SANITIZE_NUMBER_INT);
$height = filter_input(INPUT_POST, "height", FILTER_SANITIZE_NUMBER_INT);

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
$sql = "INSERT INTO Personal_Info (Name, Age, Weight, Height) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
// Check if the statement was prepared correctly
if (!$stmt) {
    die("Statement preparation failed: " . $conn->error);
}
// Bind the parameters to the SQL query
$stmt->bind_param("siii", $name, $age, $weight, $height);
// Execute the statement and check if it was successful
if ($stmt->execute()) {
    echo "New records created successfully";
} else {
    echo "Error: " . $stmt->error;
}
// Close statement and connection
$stmt->close();
$conn->close();
?>
