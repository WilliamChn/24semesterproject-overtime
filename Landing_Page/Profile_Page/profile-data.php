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

$proteinSumQuery = "SELECT SUM(Protein) AS TotalProtein FROM Meals_Info";
$result = $conn->query($proteinSumQuery);

$response = array();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['totalProtein'] = $row['TotalProtein'];
} else {
    $response['totalProtein'] = 0;
}

$conn->close();

// Set header to tell that the response is JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
