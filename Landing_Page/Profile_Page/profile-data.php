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

// Fetch user's personal info
$stmt = $conn->prepare("SELECT p_dob, p_weight, p_height FROM Personal_Info WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$personalInfoResult = $stmt->get_result();
if ($personalInfoResult && $personalInfoResult->num_rows > 0) {
    $personalInfo = $personalInfoResult->fetch_assoc();
    $dob = new DateTime($personalInfo['p_dob']);
    $today = new DateTime('today');
    $response['age'] = $dob->diff($today)->y;
    $response['weight'] = $personalInfo['p_weight'];
    $response['height'] = $personalInfo['p_height'];
} else {
    $response['personalInfo'] = 'No personal info found for user.';
}
$stmt->close();

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

// Existing code to get the longest workout time, adjust to fetch the type as well
$stmt = $conn->prepare("
    SELECT Workout.Duration, Workout_Stats.type
    FROM Workout
    INNER JOIN Workout_Stats ON Workout.Style = Workout_Stats.num
    WHERE Workout.user_id = ? 
    ORDER BY Workout.Duration DESC 
    LIMIT 1
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$workoutResult = $stmt->get_result();

if ($workoutResult && $workoutResult->num_rows > 0) {
    $row = $workoutResult->fetch_assoc();
    $response['longestWorkout'] = array(
        'type' => $row['type'],
        'duration' => $row['Duration']
    );
} else {
    $response['longestWorkout'] = null;
}
$stmt->close();




// Get the most recent workout from the Workout table based on the highest ID
$stmt = $conn->prepare("
    SELECT Workout.*, Workout_Stats.type 
    FROM Workout 
    INNER JOIN Workout_Stats ON Workout.Style = Workout_Stats.num 
    WHERE Workout.user_id = ? 
    ORDER BY Workout.Id DESC 
    LIMIT 1
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$recentWorkoutResult = $stmt->get_result();

if ($recentWorkoutResult && $recentWorkoutResult->num_rows > 0) {
    $row = $recentWorkoutResult->fetch_assoc();
    $response['recentWorkout'] = array(
        'type' => $row['type'], // Fetch the name of the workout type
        'duration' => $row['Duration'],
    );
} else {
    $response['recentWorkout'] = null;
}
$stmt->close();


header('Content-Type: application/json');
echo json_encode($response);
?>