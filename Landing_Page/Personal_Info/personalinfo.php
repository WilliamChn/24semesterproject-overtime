<?php
// Retrieve user input safely
$name = filter_input(INPUT_POST, "p_name", FILTER_SANITIZE_STRING);
$dob = filter_input(INPUT_POST, "p_dob", FILTER_SANITIZE_STRING); // Changed from p_age to p_dob
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
$sql = "INSERT INTO `Personal_Info` (p_name, p_dob, p_weight, p_height) VALUES (?, ?, ?, ?)"; // Changed p_age to p_dob in SQL
$stmt = $conn->prepare($sql);
// Check if the statement was prepared correctly
if (!$stmt) {
    die("Statement preparation failed: " . $conn->error);
}
// Since we're working with a date, we'll change the bind_param types accordingly. The 's' stands for string, and 'i' stands for integer.
// The DOB will be treated as a string in this context because dates are usually stored in databases as strings in the 'YYYY-MM-DD' format.
$stmt->bind_param("ssii", $name, $dob, $weight, $height); // Changed from "siii" to "ssii" because $dob is a string
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
