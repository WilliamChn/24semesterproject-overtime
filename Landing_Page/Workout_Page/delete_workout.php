<?php
session_start();
$servername = "oceanus.cse.buffalo.edu";
$username = "lanakim";
$password = "50408039";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$workoutId = $_POST['id'] ?? '';
// Add user_id in the POST request
$user_id = $_SESSION['user_id']; // The user's ID from the session

// Check if both workoutId and user_id are provided
if ($workoutId && $user_id) {
    $stmt = $conn->prepare("DELETE FROM Workout WHERE ID = ? AND user_id = ?");
    // Bind the workoutId and user_id parameters
    $stmt->bind_param("ii", $workoutId, $user_id);

    if ($stmt->execute()) {
        echo "Workout deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "No workout ID provided";
}

$conn->close();
?>