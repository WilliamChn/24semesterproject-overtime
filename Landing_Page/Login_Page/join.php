<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');
error_reporting(E_ALL);

session_start(); // Start a new session or resume the existing one

// Set session timeout as 5 minutes (300 seconds)
$inactive = 300;

// Check if $_SESSION['timeout'] is set
if (isset($_SESSION['timeout'])) {
    // Calculate the session's lifetime
    $session_life = time() - $_SESSION['timeout'];

    if ($session_life > $inactive) {
        // If the session has been inactive for more than the allowed time, destroy it
        session_unset();
        session_destroy();
        setcookie('PHPSESSID', '', time() - 3600, '/'); // Destroy the session cookie
        header("Location: login.html"); // Redirect to login page
        exit();
    }
}

$_SESSION['timeout'] = time(); // Update the session's time

// Database credentials
$servername = "oceanus.cse.buffalo.edu";
$username = "aigeorge";
$password = "50405877";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Perform session cleanup
$cleanupStmt = $conn->prepare("DELETE FROM user_sessions WHERE expiry_date < NOW()");
if ($cleanupStmt) {
    $cleanupStmt->execute();
    $cleanupStmt->close();
} else {
    die("Error preparing cleanup statement: " . htmlspecialchars($conn->error));
}

// Check for a logout request
if (isset($_GET['logout'])) {
    // Remove the user's session token from the database
    if (isset($_COOKIE['remember_me'])) {
        $stmt = $conn->prepare("DELETE FROM user_sessions WHERE token = ?");
        if ($stmt) {
            $stmt->bind_param("s", $_COOKIE['remember_me']);
            $stmt->execute();
            $stmt->close();
        }
        // Make sure to set the cookie with the same parameters as when you set it
        setcookie('remember_me', '', time() - 3600, "/", "", false, true);
    }

    // Clear session data
    $_SESSION = array();
    session_destroy();
    setcookie('PHPSESSID', '', time() - 3600, '/'); // Destroy the session cookie
    header("Location: login.html"); // Redirect to the login page
    exit();
}
// Handle login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = mysqli_real_escape_string($conn, $_POST['username']);
    $passInput = mysqli_real_escape_string($conn, $_POST['password']);

    $stmt = $conn->prepare("SELECT `id`, `Password` FROM `Login_Info` WHERE `Username` = ?");
    if ($stmt) {
        $stmt->bind_param("s", $userInput);
        $stmt->execute();
        $stmt->bind_result($userId, $hashedPasswordFromDB);
        if ($stmt->fetch() && password_verify($passInput, $hashedPasswordFromDB)) {
            $stmt->close(); // Close the statement here before preparing another statement

            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $userInput;
            $_SESSION['user_id'] = $userId;

            // Check if "Remember Me" was selected
            if (!empty($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(16)); // Generate a new token each time

                // Store it in the database
                $insertStmt = $conn->prepare("INSERT INTO user_sessions (user_id, token, expiry_date) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 MINUTE))");
                if ($insertStmt) {
                    $insertStmt->bind_param("is", $userId, $token);
                    $insertStmt->execute();
                    $insertStmt->close(); // Ensure the statement is closed after execution

                    // Set the cookie with the new token
                    setcookie('remember_me', $token, time() + 14400, "/", "", false, true); // Set for 4 hours
                } else {
                    die("Error preparing statement: " . htmlspecialchars($conn->error));
                }
            }

            header("Location: ../Profile_Page/index.html"); // Redirect to profile page
            exit();
        } else {
            $stmt->close(); // Close the statement if no results are fetched before redirecting
            header("Location: login.php?error=invalid_credentials");
            exit();
        }
    } else {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
}
$conn->close();