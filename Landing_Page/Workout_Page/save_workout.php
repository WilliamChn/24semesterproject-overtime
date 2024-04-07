<?php
session_start();

// Database connection variables
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

// Get the workout number and duration from the POST request
$workoutNum = $_POST['num'];
$duration = $_POST['duration'];

// Fetch the calories burned per minute for this workout type from Workout_Stats
$sql = "SELECT calories FROM Workout_Stats WHERE num = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $workoutNum);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$caloriesPerMinute = $row['calories'];

// Calculate total calories burned
$totalCaloriesBurned = $caloriesPerMinute * $duration;

// Retrieve user_id from the POST request
$user_id = $_SESSION['user_id']; // The user's ID from the session


// Update the insert SQL to include user_id
$insertSql = "INSERT INTO Workout (style, duration, calories, user_id) VALUES (?, ?, ?, ?)";
$insertStmt = $conn->prepare($insertSql);
// Bind the new user_id parameter as well
$insertStmt->bind_param("sidi", $workoutNum, $duration, $totalCaloriesBurned, $user_id);


if($insertStmt->execute()) {
    echo "Workout saved successfully!";
} else {
    echo "Error: " . $insertStmt->error;
}

// Close connections
$stmt->close();
$insertStmt->close();
$conn->close();
?>
