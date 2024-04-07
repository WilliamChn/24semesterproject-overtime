<?php
$lunch_item = filter_input(INPUT_POST, "lunch_item", FILTER_VALIDATE_INT);

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
// Prepare and bind
$sql = "INSERT INTO lunch (lunch_item) VALUES (?)";
$stmt = $conn->prepare($sql);
// Check if the statement was prepared correctly
if (!$stmt) {
    die("Statement preparation failed: " . $conn->error);
}
// Bind the parameters and execute
$stmt->bind_param("i", $lunch_item);
if ($stmt->execute()) {
    echo "New records created successfully";
} else {
    echo "Error: " . $stmt->error;
}
if (isset($_POST['delete_lunch'])) {
    // Create a SQL query to delete all records from the message table
    $sql = "DELETE FROM lunch";   
    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Lunch records deleted successfully";
    } else {
        echo "Error deleting records: " . $conn->error;
    }
}
// Close statement and connection
$stmt->close();
$conn->close();
?>

