
<?php
session_start();
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

$user_id = $_SESSION['user_id']; // The user's ID from the session

// Add Date to the selected fields in your SQL query
$sql = "SELECT Workout.ID, Workout_Stats.type AS Style, Workout.Duration, Workout.Calories, Workout.Date, Workout.user_id
        FROM Workout
        INNER JOIN Workout_Stats ON Workout.Style = Workout_Stats.num
        WHERE Workout.user_id = ?"; // Use prepared statement for security

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

// Execute the statement and get the result
$stmt->execute();
$result = $stmt->get_result();

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

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
