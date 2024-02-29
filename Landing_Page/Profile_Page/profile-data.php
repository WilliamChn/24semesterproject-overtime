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

// Handle POST request for water intake
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['waterIntake'])) {
    $waterIntake = filter_input(INPUT_POST, 'waterIntake', FILTER_VALIDATE_INT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO Water_Intake (Water) VALUES (?)");
    $stmt->bind_param("i", $waterIntake);

    // Execute and check success
    if ($stmt->execute()) {
        // Successfully inserted the water intake
        $response['waterUpdateStatus'] = 'Success';
    } else {
        // Handle error
        $response['waterUpdateStatus'] = 'Error';
    }

    $stmt->close();
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

// Get the total water intake from the Water_Intake table
$waterSumQuery = "SELECT SUM(Water) AS TotalWater FROM Water_Intake";
$waterResult = $conn->query($waterSumQuery);

if ($waterResult && $waterResult->num_rows > 0) {
    $waterRow = $waterResult->fetch_assoc();
    $response['totalWater'] = $waterRow['TotalWater'];
} else {
    $response['totalWater'] = 0;
}

$conn->close();

// Set header to tell that the response is JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
