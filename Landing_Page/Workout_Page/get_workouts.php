<?php
// Database connection credentials
$servername = "oceanus.cse.buffalo.edu";
$username = "lanakim";
$password = "50408039";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to select the workout data
$sql = "SELECT ID, Style, Duration FROM Workout";
$result = $conn->query($sql);

// Array to hold the workouts
$workouts = [];

// Check if we got results and loop over them
if ($result && $result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $workouts[] = $row;
    }
}

// Set the content type to application/json for proper client-side handling
header('Content-Type: application/json');

// Convert the $workouts array to JSON and print it out
echo json_encode($workouts);

// Close the database connection
$conn->close();
?>
