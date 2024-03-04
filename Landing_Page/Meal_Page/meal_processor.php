<?php
// Database configuration
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

$sql = "SELECT * FROM `food_database___sheet1`"; // The table name should be replaced with your actual table name
$result = $conn->query($sql);

$options = "<option value=''>Select a meal</option>";
if ($result->num_rows > 0) {
    // Output data of each row as a dropdown option
    while($row = $result->fetch_assoc()) {
        $options .= '<option value="'. htmlspecialchars($row["Item"]) .'">' . htmlspecialchars($row["Item"]) . ' - Calories: ' . htmlspecialchars($row["Calories"]) . ' Protein: ' . htmlspecialchars($row["Protein"]) . 'g</option>';
    }
} else {
    $options .= '<option value="">No meals available</option>';
}
$conn->close();

echo $options;
?>