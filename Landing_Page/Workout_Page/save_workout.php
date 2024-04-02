<?php
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

// Now, insert the new workout data into the Workout table
$insertSql = "INSERT INTO Workout (style, duration, calories) VALUES (?, ?, ?)";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param("sid", $workoutNum, $duration, $totalCaloriesBurned);
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
