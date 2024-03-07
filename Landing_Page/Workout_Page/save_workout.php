<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Send JSON response

$servername = "oceanus.cse.buffalo.edu";
$username = "lanakim";
$password = "50408039";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

if (isset($_POST['style']) && isset($_POST['duration'])) {
    $workoutStyle = $_POST['style'];
    $workoutDuration = $_POST['duration'];

    $stmt = $conn->prepare("INSERT INTO Workout (Style, Duration) VALUES (?, ?)");
    $stmt->bind_param("si", $workoutStyle, $workoutDuration);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "New record created successfully"]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => "No data provided"]);
}
?>
