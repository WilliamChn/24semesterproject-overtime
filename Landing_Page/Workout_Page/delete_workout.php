<?php
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

if ($workoutId) {
    $stmt = $conn->prepare("DELETE FROM Workout WHERE ID = ?");
    $stmt->bind_param("i", $workoutId);

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