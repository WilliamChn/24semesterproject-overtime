<?php
// Database credentials should be secured and not hardcoded in production
$servername = "oceanus.cse.buffalo.edu";
$username = "lanakim";
$password = "50408039";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;port=$port;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch workout types from the database
    $stmt = $pdo->query("SELECT num, type FROM Workout_Stats ORDER BY num");
    $workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($workouts);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
