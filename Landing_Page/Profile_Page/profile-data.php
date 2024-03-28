<?php
session_start();

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

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page, or handle not logged in user
    die('User not logged in.');
}

$userId = $_SESSION['user_id']; // The user's ID from the session

$response = array();

// Handle POST request for water intake
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['waterIntake'])) {
    $waterIntake = filter_input(INPUT_POST, 'waterIntake', FILTER_VALIDATE_INT);

    if ($waterIntake !== false) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO Water_Intake (user_id, Water) VALUES (?, ?)");
        $stmt->bind_param("ii", $userId, $waterIntake);

        // Execute and check success
        if ($stmt->execute()) {
            $response['waterUpdateStatus'] = 'Success';
        } else {
            $response['waterUpdateStatus'] = 'Error';
        }

        $stmt->close();
    } else {
        $response['waterUpdateStatus'] = 'Invalid input';
    }
}

// Get the total protein from the Meals_Info table
$stmt = $conn->prepare("SELECT SUM(Protein) AS TotalProtein FROM Meals_Info WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$proteinResult = $stmt->get_result();

if ($proteinResult && $proteinResult->num_rows > 0) {
    $row = $proteinResult->fetch_assoc();
    $response['totalProtein'] = $row['TotalProtein'];
} else {
    $response['totalProtein'] = 0;
}
$stmt->close();

// Get the total water intake from the Water_Intake table
$stmt = $conn->prepare("SELECT SUM(Water) AS TotalWater FROM Water_Intake WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$waterResult = $stmt->get_result();

if ($waterResult && $waterResult->num_rows > 0) {
    $row = $waterResult->fetch_assoc();
    $response['totalWater'] = $row['TotalWater'];
} else {
    $response['totalWater'] = 0;
}
$stmt->close();

// Get the total calorie intake from the Meals_Info table
$stmt = $conn->prepare("SELECT SUM(Calorie) AS TotalCalories FROM Meals_Info WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$calorieResult = $stmt->get_result();

if ($calorieResult && $calorieResult->num_rows > 0) {
    $row = $calorieResult->fetch_assoc();
    $response['totalCalories'] = $row['TotalCalories'];
} else {
    $response['totalCalories'] = 0;
}
$stmt->close();

// Get the longest workout time from the Workout table
$stmt = $conn->prepare("SELECT MAX(Duration) AS LongestWorkout FROM Workout WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$workoutResult = $stmt->get_result();

if ($workoutResult && $workoutResult->num_rows > 0) {
    $row = $workoutResult->fetch_assoc();
    $response['longestWorkout'] = $row['Duration']; // Assume 'Duration' is stored in a column named 'Duration'
} else {
    $response['longestWorkout'] = 0;
}
$stmt->close();


// Get the most recent workout from the Workout table based on the highest ID
$stmt = $conn->prepare("SELECT * FROM Workout WHERE user_id = ? ORDER BY Id DESC LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$recentWorkoutResult = $stmt->get_result();

if ($recentWorkoutResult && $recentWorkoutResult->num_rows > 0) {
    $row = $recentWorkoutResult->fetch_assoc();
    $response['recentWorkout'] = array(
        'style' => $row['Style'], // Replace with your actual column name for the workout style/type
        'duration' => $row['Duration'], // Replace with your actual column name for the workout duration
        // Include any other relevant information you want to show
    );
} else {
    $response['recentWorkout'] = null;
}
$stmt->close();

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
