<?php
session_start();

// Check if the user is logged in and has an ID set in the session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id']; // Retrieve the user ID from the session

// Database credentials
$servername = "oceanus.cse.buffalo.edu";
$username = "ziangche";
$password = "50415271";
$dbname = "cse442_2024_spring_team_b_db";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare an array to store the response
$response = array();

// Check if POST request and required fields are set
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updates = [];
    $types = ''; // This will hold the types for the parameters
    $params = []; // This will hold the parameters for the prepared statement

    // Check if name is being updated
    if (isset($_POST['p_name'])) {
        $updates[] = "p_name = ?";
        $types .= 's'; // Type string
        $params[] = filter_input(INPUT_POST, 'p_name', FILTER_SANITIZE_STRING);
    }

    // Check if date of birth is being updated
    if (isset($_POST['p_dob'])) {
        $newDOB = filter_input(INPUT_POST, 'p_dob', FILTER_SANITIZE_STRING);
        $updates[] = "p_dob = ?";
        $types .= 's'; // Type string
        $params[] = $newDOB;

        // Calculate the age from the new DOB
        $dobDateTime = new DateTime($newDOB);
        $currentDate = new DateTime('now');
        $age = $currentDate->diff($dobDateTime)->y;

        // Include the new age in the response
        $response['new_age'] = $age;
    }


    // Check if weight is being updated
    if (isset($_POST['p_weight'])) {
        $updates[] = "p_weight = ?";
        $types .= 'i'; // Type integer
        $params[] = filter_input(INPUT_POST, 'p_weight', FILTER_SANITIZE_NUMBER_INT);
    }

    // Check if height is being updated
    if (isset($_POST['p_height'])) {
        $updates[] = "p_height = ?";
        $types .= 'i'; // Type integer
        $params[] = filter_input(INPUT_POST, 'p_height', FILTER_SANITIZE_NUMBER_INT);
    }

    if (!empty($updates)) {
        // Include user_id in the types and params
        $types .= 'i';
        $params[] = $userId;

        // Convert the array to a comma-separated string for the SQL statement
        $setStr = implode(', ', $updates);

        // Prepare the SQL statement with placeholders
        $stmt = $conn->prepare("UPDATE Personal_Info SET {$setStr} WHERE user_id = ?");

        // Check if the statement was prepared correctly
        if (!$stmt) {
            die("Statement preparation failed: " . $conn->error);
        }

        // Bind the parameters to the SQL query
        $stmt->bind_param($types, ...$params);

        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
            $response['message'] = $stmt->error;
        }

        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No data provided for update.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
