<?php
session_start(); // Start a new session or resume the existing one

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        echo json_encode(array('success' => false, 'error' => $conn->connect_error));
        exit();
    }

    // Capture and sanitize user input
    $userInput = mysqli_real_escape_string($conn, $_POST['username']);
    $passInput = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($passInput !== $confirm_password) {
        echo json_encode(array('success' => false, 'error' => 'Passwords do not match.'));
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($passInput, PASSWORD_DEFAULT);

    // Check if the username or email already exists
    $checkUser = $conn->prepare("SELECT * FROM Login_Info WHERE Username = ? OR Email = ?");
    $checkUser->bind_param("ss", $userInput, $email);
    $checkUser->execute();
    $result = $checkUser->get_result();
    $checkUser->close();

    if ($result->num_rows > 0) {
        echo json_encode(array('success' => false, 'error' => 'Username or Email already exists.'));
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO Login_Info (Username, Email, Password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $userInput, $email, $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['loggedin'] = true; // Set a session variable to indicate the user is logged in
            $_SESSION['username'] = $userInput; // Store the username in session
    
            // Get the last inserted ID to store in the session
            $_SESSION['user_id'] = $conn->insert_id; // This gets the auto-incremented ID from the last query
    
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'error' => $stmt->error));
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>
