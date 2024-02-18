<?php
// Credentials should be stored securely and not hardcoded
$servername = "oceanus.cse.buffalo.edu";
$username = "lanakim";
$password = "50408039"; // This should be changed immediately
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the welcome message from the database
$sql = "SELECT * FROM `Landing2`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo $row["Welcome"];
    }
} else {
    echo "0 results";
}

$conn->close();
?>