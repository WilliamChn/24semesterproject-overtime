<?php
// Database credentials
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

// Check for the delete button action for workouts
if (isset($_POST['delete_workout'])) {
    // First, find the most recent workout entry's ID
    // First, find the most recent entry's ID
    $result = $conn->query("SELECT id FROM Workout ORDER BY id DESC LIMIT 1");

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['id'];

        // Prepare the delete statement with the most recent ID
        $sql = "DELETE FROM Workout WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Statement preparation failed: " . $conn->error);
        }

        // Bind the most recent id and execute the deletion
        $stmt->bind_param("i", $last_id);

        if ($stmt->execute()) {
            echo "Most recent workout record deleted successfully";
            echo $last_id;
        } else {
            echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close(); // Close the prepared statement
    } else {
        echo "No records found to delete.";
    }
}

$conn->close(); // Close the database connection
?>
