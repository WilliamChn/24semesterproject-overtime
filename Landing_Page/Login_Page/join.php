<?php
session_start(); // Start a new session or resume the existing one

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture and sanitize user input
    $userInput = mysqli_real_escape_string($conn, $_POST['username']);
    $passInput = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT Password FROM Login_Info WHERE Username = ?");
    $stmt->bind_param("s", $userInput);

    // Execute the statement
    $stmt->execute();
    // Bind result variables
    $stmt->bind_result($hashedPasswordFromDB);

    // Fetch value
    if ($stmt->fetch() && password_verify($passInput, $hashedPasswordFromDB)) {
        // If the fetch succeeds and the password verifies, start the session
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $userInput;
        
        // Redirect to user profile page or dashboard

        //TODO
        
        header("Location: ../Profile_Page/index.html"); // Make sure this path is correct
        exit();
    } else {
        // If the user does not exist or password does not match
        // Redirect back to the login with an error message
        header("Location: login.html?error=invalid_credentials"); // Adjust as necessary
        exit();
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
