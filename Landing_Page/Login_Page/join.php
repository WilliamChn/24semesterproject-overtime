<?php
// Database credentials
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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // (Your login logic will go here)
    // You would check the credentials against the database and start a session if they are correct

    // For now, we'll just close the connection
    $conn->close();
    
    // Redirect to a new page upon successful login (you should replace 'some_page.php' with the actual page you want to redirect to)
    header("Location: join.php");
    exit();
} else {
    // If the form wasn't submitted, redirect back to the login form
    header("Location: login.html");
    exit();
}
?>