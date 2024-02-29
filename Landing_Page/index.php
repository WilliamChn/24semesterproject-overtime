<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Overtime Fitness</title>
<style>
    body, h1, a {
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
    }
    
    .navbar {
  position: absolute;
  top: 0;
  left: 0; /* Ensure the navbar starts from the left */
  width: 100%;
  background: rgba(0, 0, 0, 1);
  padding: 15px 0;
  display: flex;
  justify-content: space-between; /* Aligns items to the start and end */
  align-items: center;
  box-sizing: border-box;
}

/* New .navbar-right styles */
.navbar-right {
  display: flex; /* Align the links in a row */
  justify-content: flex-end; /* Align the links to the right */
}

/* Update existing .navbar a styles if needed */
.navbar a {
  color: white;
  padding: 0 20px; /* Adjust padding as needed */
  text-decoration: none;
  text-transform: uppercase;
  font-weight: bold;
}

/* Add hover effect for the links */
.navbar a:hover {
  color: #777; /* Dark grey color on hover */
}
    
    .welcome-section {
    background-image: url('landing-page.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 100vh; /* This ensures that the section is at least the height of the viewport */
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    color: white;
}
    
    .join-button {
        background-color: #000000; /* Black background */
        color: white; /* White text color */
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 20px; /* Larger font size */
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 5px; /* Rounded corners */
    }
    
    .join-button:hover {
        background-color: #555; /* Dark grey background on hover */
    }
    
</style>
</head>
<body>

    <?php
include 'check_session.php'; // Include the session check script

if (isLoggedIn()) {
    // If the user is logged in, display the navbar
    echo '<div class="navbar">
            <a href="#home">OVERTIME</a>
            <div class="navbar-right">
                <a href="Meal_Page/index.html">Log Meals</a>
                <a href="Workout_Page">Log Workouts</a>
                <a href="Profile_Page">Profile</a>
            </div>
        </div>';
}
else{
    echo '<div class="navbar">
    <a href="#home">OVERTIME</a>
</div>';   
}
?>

<div class="welcome-section">
    <h1>WELCOME TO OVERTIME</h1>
    <a href="Login_Page/login.html" class="join-button">Join Now</a>
</div>

</body>
</html>