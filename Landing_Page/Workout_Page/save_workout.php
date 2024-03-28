<?php
// Database connection variables
$servername = "oceanus.cse.buffalo.edu";
$username = "lanakim";
$password = "50408039";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$style = $_POST['style'] ?? '';
$duration = $_POST['duration'] ?? 0;

$stmt = $conn->prepare("INSERT INTO Workout (Style, Duration) VALUES (?, ?)");
$stmt->bind_param("si", $style, $duration);

if ($stmt->execute()) {
    echo "New workout saved successfully. Workout ID: " . $stmt->insert_id;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
