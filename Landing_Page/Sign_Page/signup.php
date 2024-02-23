<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database configuration
    $servername = "oceanus.cse.buffalo.edu";
    $username = "lanakim"; // Variable name should be consistent
    $password = "50408039"; // Variable name should be consistent
    $dbname = "cse442_2024_spring_team_b_db";
    $port = 3306;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Capture and sanitize user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']); // Capture the email
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']); // Capture the confirm password

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit(); // Stop the script if passwords don't match
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username or email already exists
    $checkUser = $conn->prepare("SELECT * FROM Login_Info WHERE Username = ? OR Email = ?");
    $checkUser->bind_param("ss", $username, $email);
    $checkUser->execute();
    $result = $checkUser->get_result();
    $checkUser->close();

    if ($result->num_rows > 0) {
        echo "Username or Email already exists. Please choose a different one.";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO Login_Info (Username, Email, Password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            echo "New record created successfully";
            // You could redirect to the login page or start a session here
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>
